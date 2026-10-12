import { q } from "@/lib/db";
import { emailClient } from "@/lib/notify";
import type { Client } from "@/lib/types";

export const dynamic = "force-dynamic";

// Nudges clients with steps still "To do". Stuck steps are excluded: those are waiting on us.
export async function GET(req: Request) {
  const secret = process.env.CRON_SECRET;
  if (!secret || req.headers.get("authorization") !== `Bearer ${secret}`) {
    return new Response("Unauthorized", { status: 401 });
  }
  const days = Number(process.env.REMINDER_INTERVAL_DAYS) || 3;
  const due = await q<Client>(
    `select c.* from clients c
     where c.archived_at is null
       and c.created_at < now() - interval '1 day'
       and (c.last_reminded_at is null or c.last_reminded_at < now() - make_interval(days => $1))
       and exists (select 1 from onboarding_items i where i.client_id = c.id and i.status = 'pending')`,
    [days],
  );
  const failed: string[] = [];
  for (const client of due) {
    try {
      await emailClient(client, "reminder", "system");
    } catch (err) {
      console.error(`Reminder failed for ${client.id}`, err);
      failed.push(client.id);
    }
  }
  return Response.json({ reminded: due.length - failed.length, failed });
}
