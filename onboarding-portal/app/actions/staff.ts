"use server";

import crypto from "node:crypto";
import { revalidatePath } from "next/cache";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { hashToken, isAllowedEmail, requireStaff, sealSession, SESSION_COOKIE } from "@/lib/auth";
import { log, q } from "@/lib/db";
import { appUrl, sendEmail } from "@/lib/email";
import { emailClient } from "@/lib/notify";
import { stepFor } from "@/lib/steps";
import { ITEM_KINDS, type Client, type ItemStatus } from "@/lib/types";

const str = (fd: FormData, key: string) => String(fd.get(key) ?? "").trim();

export async function requestLogin(formData: FormData) {
  const email = str(formData, "email").toLowerCase();
  if (isAllowedEmail(email)) {
    const token = crypto.randomBytes(32).toString("base64url");
    await q("insert into login_tokens (token_hash, email, expires_at) values ($1, $2, now() + interval '15 minutes')", [
      hashToken(token),
      email,
    ]);
    await sendEmail({
      to: email,
      subject: "Your onboarding portal sign-in link",
      text: `Sign in (this link expires in 15 minutes and works once):\n\n${appUrl(`/login/verify?token=${token}`)}`,
    });
  }
  // Same response either way, so the form doesn't reveal which addresses are allowed.
  redirect("/login?sent=1");
}

export async function consumeLogin(formData: FormData) {
  const [row] = await q<{ email: string }>(
    `update login_tokens set used_at = now()
     where token_hash = $1 and used_at is null and expires_at > now()
     returning email`,
    [hashToken(str(formData, "token"))],
  );
  if (!row || !isAllowedEmail(row.email)) redirect("/login?expired=1");
  const session = sealSession(row.email);
  (await cookies()).set(SESSION_COOKIE, session.value, {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "lax",
    path: "/",
    maxAge: session.maxAge,
  });
  redirect("/dashboard");
}

export async function logout() {
  (await cookies()).delete(SESSION_COOKIE);
  redirect("/login");
}

export async function createClient(formData: FormData) {
  const staff = await requireStaff();
  const practice = str(formData, "practice_name");
  const contact = str(formData, "contact_name");
  const email = str(formData, "contact_email").toLowerCase();
  const locations = Math.min(99, Math.max(1, Math.trunc(Number(formData.get("locations")) || 1)));
  const kinds = ITEM_KINDS.filter((k) => formData.get(`item_${k}`) === "on");
  if (!practice || !contact || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email) || kinds.length === 0) {
    redirect("/clients/new?error=1");
  }

  // One statement, so a client never exists without its checklist.
  const [client] = await q<Client>(
    `with c as (
       insert into clients (practice_name, contact_name, contact_email, locations, link_token, created_by)
       values ($1, $2, $3, $4, $5, $6) returning *
     ), items as (
       insert into onboarding_items (client_id, kind) select c.id, unnest($7::text[]) from c
     )
     select * from c`,
    [practice, contact, email, locations, crypto.randomBytes(24).toString("base64url"), staff, kinds],
  );
  await log(client.id, staff, "Created client");
  if (formData.get("send_now") === "on") await emailClient(client, "invite", staff);
  redirect(`/clients/${client.id}`);
}

async function setItemStatus(itemId: string, status: ItemStatus, verb: string) {
  const staff = await requireStaff();
  // Reopening is a full reset so the client re-answers the diagnostic question.
  const [row] = await q<{ client_id: string; kind: string }>(
    `update onboarding_items set
       status = $2::text,
       verified_by = case when $2::text = 'verified' then $3 end,
       diagnostic = case when $2::text = 'pending' then '{}'::jsonb else diagnostic end,
       client_note = case when $2::text = 'pending' then null else client_note end,
       updated_at = now()
     where id = $1
     returning client_id, kind`,
    [itemId, status, staff],
  );
  if (!row) return;
  await log(row.client_id, staff, `${verb} ${stepFor(row.kind)?.title ?? row.kind}`);
  revalidatePath(`/clients/${row.client_id}`);
}

export async function verifyItem(itemId: string) {
  await setItemStatus(itemId, "verified", "Confirmed access to");
}

export async function reopenItem(itemId: string) {
  await setItemStatus(itemId, "pending", "Reopened");
}

async function emailAction(clientId: string, kind: "invite" | "reminder") {
  const staff = await requireStaff();
  const [client] = await q<Client>("select * from clients where id = $1", [clientId]);
  if (!client) return;
  await emailClient(client, kind, staff);
  revalidatePath(`/clients/${clientId}`);
}

export async function sendLink(clientId: string) {
  await emailAction(clientId, "invite");
}

export async function sendReminder(clientId: string) {
  await emailAction(clientId, "reminder");
}
