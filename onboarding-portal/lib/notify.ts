import { log, q } from "./db";
import { appUrl, sendEmail } from "./email";
import { stepFor } from "./steps";
import type { Client, Item } from "./types";

function agencyName() {
  return process.env.AGENCY_NAME || "PatientPath";
}

export async function emailClient(client: Client, kind: "invite" | "reminder", actor: string) {
  const link = appUrl(`/onboard/${client.link_token}`);
  const firstName = client.contact_name.split(" ")[0];
  let text: string;
  let subject: string;

  if (kind === "invite") {
    subject = `${agencyName()}: getting access set up for ${client.practice_name}`;
    text = `Hi ${firstName},

To get started, we need access to a few of ${client.practice_name}'s marketing accounts. This link walks you through each one — it takes about 10 minutes, and you never share passwords:

${link}

You stay the owner of every account and can remove our access at any time. If anything's confusing, tap "I'm stuck" and we'll help.

— The ${agencyName()} team`;
  } else {
    const open = await q<Pick<Item, "kind">>(
      "select kind from onboarding_items where client_id = $1 and status = 'pending'",
      [client.id],
    );
    const list = open.map((i) => `  • ${stepFor(i.kind)?.title ?? i.kind}`).join("\n");
    subject = `Reminder: ${open.length} step${open.length === 1 ? "" : "s"} left for ${client.practice_name}`;
    text = `Hi ${firstName},

Just a nudge — we're still waiting on:

${list}

Pick up where you left off: ${link}

If something's blocking you, tap "I'm stuck" on that step and we'll reach out.

— The ${agencyName()} team`;
  }

  await sendEmail({ to: client.contact_email, subject, text });
  await q("update clients set last_reminded_at = now() where id = $1", [client.id]);
  await log(client.id, actor, kind === "invite" ? "Emailed onboarding link" : "Sent reminder");
}

export async function alertStaff(client: Client, subject: string, body: string) {
  const to = process.env.STAFF_NOTIFY_EMAIL;
  if (!to) return;
  await sendEmail({
    to,
    subject: `[Onboarding] ${client.practice_name}: ${subject}`,
    text: `${body}\n\n${appUrl(`/clients/${client.id}`)}`,
  });
}
