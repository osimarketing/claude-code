"use server";

// Public actions behind the client's onboarding link. Every write is scoped by link token,
// and nothing here can touch an item staff have already confirmed.

import { revalidatePath } from "next/cache";
import { log, q } from "@/lib/db";
import { alertStaff } from "@/lib/notify";
import { stepFor } from "@/lib/steps";
import type { Client, Item, ItemStatus } from "@/lib/types";

async function load(token: string, kind: string) {
  const step = stepFor(kind);
  if (!step) throw new Error("Unknown step");
  const [client] = await q<Client>("select * from clients where link_token = $1 and archived_at is null", [token]);
  if (!client) throw new Error("Link not found");
  return { client, step };
}

async function update(client: Client, kind: string, set: string, params: unknown[]) {
  const rows = await q(
    `update onboarding_items set ${set}, updated_at = now()
     where client_id = $1 and kind = $2 and status <> 'verified' returning id`,
    [client.id, kind, ...params],
  );
  revalidatePath(`/onboard/${client.link_token}`);
  return rows.length > 0;
}

async function alertIfFinished(client: Client) {
  const [{ remaining }] = await q<{ remaining: number }>(
    "select count(*)::int as remaining from onboarding_items where client_id = $1 and status in ('pending', 'stuck')",
    [client.id],
  );
  if (remaining === 0) {
    await alertStaff(client, "finished their steps", `${client.contact_name} has completed every step. Time to confirm access.`);
  }
}

export async function answerItem(token: string, kind: string, formData: FormData) {
  const { client, step } = await load(token, kind);
  const answer = step.answers.find((a) => a.value === formData.get("answer"));
  if (!answer) return;
  const status: ItemStatus =
    answer.outcome === "nothing_needed" ? "client_done" : answer.outcome === "needs_help" ? "stuck" : "pending";

  const changed = await update(
    client,
    kind,
    "diagnostic = jsonb_build_object('answer', $3::text), status = $4, client_note = null",
    [answer.value, status],
  );
  if (!changed) return;
  await log(client.id, "client", `${step.title}: "${answer.label}"`);
  if (status === "stuck") {
    await alertStaff(client, `needs help with ${step.title}`, `${client.contact_name} answered "${answer.label}".`);
  }
  if (status === "client_done") await alertIfFinished(client);
}

export async function completeItem(token: string, kind: string, formData: FormData) {
  const { client, step } = await load(token, kind);
  const [item] = await q<Item>("select * from onboarding_items where client_id = $1 and kind = $2", [client.id, kind]);
  const answer = step.answers.find((a) => a.value === item?.diagnostic.answer);
  if (answer?.outcome !== "instructions") return;
  const fields: Record<string, string> = {};
  for (const f of answer.fields ?? []) {
    const value = String(formData.get(f.name) ?? "").trim();
    // The browser enforces the pattern first; this is the backstop.
    if (f.pattern && !new RegExp(`^(?:${f.pattern})$`).test(value)) return;
    fields[f.name] = value;
  }
  const changed = await update(client, kind, "diagnostic = diagnostic || $3::jsonb, status = 'client_done'", [
    JSON.stringify(fields),
  ]);
  if (!changed) return;
  await log(client.id, "client", `Marked ${step.title} done`);
  await alertIfFinished(client);
}

export async function stuckItem(token: string, kind: string, formData: FormData) {
  const { client, step } = await load(token, kind);
  const note = String(formData.get("note") ?? "").trim().slice(0, 2000) || null;
  const changed = await update(client, kind, "status = 'stuck', client_note = $3", [note]);
  if (!changed) return;
  await log(client.id, "client", `Stuck on ${step.title}${note ? `: ${note}` : ""}`);
  await alertStaff(client, `stuck on ${step.title}`, `${client.contact_name} says: ${note ?? "(no details given)"}`);
}

export async function resetItem(token: string, kind: string) {
  const { client, step } = await load(token, kind);
  const changed = await update(client, kind, "status = 'pending', diagnostic = '{}'::jsonb, client_note = null", []);
  if (changed) await log(client.id, "client", `Restarted ${step.title}`);
}
