import { notFound } from "next/navigation";
import { reopenItem, sendLink, sendReminder, verifyItem } from "@/app/actions/staff";
import { CopyButton } from "@/components/CopyButton";
import { ago, Progress, StaffShell, StatusBadge } from "@/components/ui";
import { requireStaff } from "@/lib/auth";
import { q } from "@/lib/db";
import { appUrl } from "@/lib/email";
import { answerFor, stepFor, STEPS } from "@/lib/steps";
import type { Client, Item } from "@/lib/types";

export const dynamic = "force-dynamic";

const UUID = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

export default async function ClientPage({ params }: { params: Promise<{ id: string }> }) {
  const email = await requireStaff();
  const { id } = await params;
  if (!UUID.test(id)) notFound();
  const [client] = await q<Client>("select * from clients where id = $1", [id]);
  if (!client) notFound();

  const [items, events] = await Promise.all([
    q<Item>("select * from onboarding_items where client_id = $1", [id]),
    q<{ actor: string; message: string; created_at: Date }>(
      "select actor, message, created_at from events where client_id = $1 order by created_at desc limit 25",
      [id],
    ),
  ]);
  items.sort((a, b) => STEPS.findIndex((s) => s.kind === a.kind) - STEPS.findIndex((s) => s.kind === b.kind));
  const link = appUrl(`/onboard/${client.link_token}`);
  const verified = items.filter((i) => i.status === "verified").length;

  return (
    <StaffShell email={email}>
      <div className="page-head">
        <div>
          <h1>{client.practice_name}</h1>
          <p className="muted">
            {client.contact_name} · <a href={`mailto:${client.contact_email}`}>{client.contact_email}</a> ·{" "}
            {client.locations} location{client.locations === 1 ? "" : "s"}
          </p>
        </div>
        <div className="head-progress">
          <Progress done={verified} total={items.length} />
          <span className="muted">
            {verified}/{items.length} confirmed
          </span>
        </div>
      </div>

      <section className="card stack">
        <h2>Client link</h2>
        <div className="link-row">
          <input readOnly value={link} aria-label="Onboarding link" />
          <CopyButton value={link} />
        </div>
        <div className="actions">
          <form action={sendLink.bind(null, client.id)}>
            <button className="btn">Email link to client</button>
          </form>
          <form action={sendReminder.bind(null, client.id)}>
            <button className="btn">Send reminder now</button>
          </form>
          <span className="muted">Last emailed {ago(client.last_reminded_at)}</span>
        </div>
      </section>

      <section className="stack">
        <h2>Checklist</h2>
        {items.map((item) => {
          const step = stepFor(item.kind);
          const { answer: answerValue, ...fields } = item.diagnostic;
          const answer = answerFor(item.kind, answerValue);
          return (
            <div key={item.id} className={`card item item-${item.status}`}>
              <div className="item-head">
                <h3>{step?.title ?? item.kind}</h3>
                <StatusBadge status={item.status} />
              </div>
              <dl>
                <dt>Client said</dt>
                <dd>{answer?.label ?? <span className="muted">Not answered yet</span>}</dd>
                {Object.entries(fields).map(([k, v]) => (
                  <div key={k} className="dl-row">
                    <dt>{answer?.fields?.find((f) => f.name === k)?.label ?? k}</dt>
                    <dd>
                      <code>{v}</code>
                    </dd>
                  </div>
                ))}
                {item.client_note && (
                  <div className="dl-row">
                    <dt>Client note</dt>
                    <dd className="note">{item.client_note}</dd>
                  </div>
                )}
                {item.verified_by && (
                  <div className="dl-row">
                    <dt>Confirmed by</dt>
                    <dd>{item.verified_by}</dd>
                  </div>
                )}
              </dl>
              <div className="actions">
                {item.status !== "verified" && (
                  <form action={verifyItem.bind(null, item.id)}>
                    <button className="btn btn-primary">Mark confirmed</button>
                  </form>
                )}
                {item.status !== "pending" && (
                  <form action={reopenItem.bind(null, item.id)}>
                    <button className="btn">Reopen for client</button>
                  </form>
                )}
                <span className="muted">Updated {ago(item.updated_at)}</span>
              </div>
            </div>
          );
        })}
      </section>

      <section className="card">
        <h2>Activity</h2>
        <ul className="events">
          {events.map((e, i) => (
            <li key={i}>
              <span className="muted">{ago(e.created_at)}</span> <strong>{e.actor}</strong> {e.message}
            </li>
          ))}
        </ul>
      </section>
    </StaffShell>
  );
}
