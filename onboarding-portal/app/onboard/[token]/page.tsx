import { notFound } from "next/navigation";
import { answerItem, completeItem, resetItem, stuckItem } from "@/app/actions/onboard";
import { Progress, StatusBadge } from "@/components/ui";
import { Rich } from "@/components/Rich";
import { providerFor } from "@/lib/access";
import { q } from "@/lib/db";
import { agencyCtx, STEPS, type Ctx, type StepDef } from "@/lib/steps";
import type { Client, Item } from "@/lib/types";

export const dynamic = "force-dynamic";

export default async function Onboard({ params }: { params: Promise<{ token: string }> }) {
  const { token } = await params;
  const [client] = await q<Client>("select * from clients where link_token = $1 and archived_at is null", [token]);
  if (!client) notFound();
  const items = await q<Item>("select * from onboarding_items where client_id = $1", [client.id]);
  const ctx = agencyCtx(client.locations);
  const steps = STEPS.filter((s) => items.some((i) => i.kind === s.kind));
  const done = items.filter((i) => i.status === "client_done" || i.status === "verified").length;

  return (
    <main className="wizard">
      <header className="wizard-head">
        <div className="brand">
          <span className="mark" aria-hidden />
          {ctx.agencyName}
        </div>
        <h1>Welcome, {client.contact_name.split(" ")[0]}</h1>
        <p>
          To start marketing {client.practice_name}, we need access to a few accounts. It takes about 10 minutes. You never
          share a password — you add us as a partner, your practice keeps ownership, and you can remove us any time.
        </p>
        <Progress done={done} total={items.length} />
        <p className="muted">
          {done === items.length
            ? "All done — thank you! We'll confirm everything on our side."
            : `${done} of ${items.length} done. You can close this page and come back any time.`}
        </p>
      </header>
      {steps.map((step, n) => (
        <ItemCard
          key={step.kind}
          n={n + 1}
          token={token}
          client={client}
          step={step}
          item={items.find((i) => i.kind === step.kind)!}
          ctx={ctx}
        />
      ))}
    </main>
  );
}

function ItemCard({ n, token, client, step, item, ctx }: { n: number; token: string; client: Client; step: StepDef; item: Item; ctx: Ctx }) {
  const answer = step.answers.find((a) => a.value === item.diagnostic.answer);
  const reset = (
    <form action={resetItem.bind(null, token, step.kind)}>
      <button className="linklike">Start this step over</button>
    </form>
  );

  let body: React.ReactNode;
  if (item.status === "verified") {
    body = <p>Confirmed — we have the access we need. Thank you!</p>;
  } else if (item.status === "client_done") {
    body = (
      <>
        <p>{answer?.outcome === "nothing_needed" ? "Nothing to do here — we'll take care of it." : "Thanks! We'll confirm the access came through."}</p>
        {reset}
      </>
    );
  } else if (item.status === "stuck") {
    body = (
      <>
        <p>We've been notified and will reach out to help{item.client_note ? " with:" : "."}</p>
        {item.client_note && <blockquote>{item.client_note}</blockquote>}
        {reset}
      </>
    );
  } else if (!answer) {
    const connect = providerFor(step.kind)?.connectUrl?.(client);
    body = (
      <>
        {connect && (
          <a className="btn btn-primary" href={connect}>
            Connect automatically
          </a>
        )}
        <form action={answerItem.bind(null, token, step.kind)} className="stack">
          <fieldset>
            <legend>{step.question}</legend>
            {step.answers.map((a) => (
              <label key={a.value} className="choice">
                <input type="radio" name="answer" value={a.value} required />
                {a.label}
              </label>
            ))}
          </fieldset>
          <button className="btn btn-primary">Next</button>
        </form>
      </>
    );
  } else {
    body = (
      <>
        <p className="muted">You said: {answer.label}</p>
        <ol className="steps">
          {(answer.steps?.(ctx) ?? []).map((s, i) => (
            <li key={i}>
              <Rich text={s} />
            </li>
          ))}
        </ol>
        <form action={completeItem.bind(null, token, step.kind)} className="stack">
          {answer.fields?.map((f) => (
            <label key={f.name}>
              {f.label}
              <input name={f.name} required placeholder={f.placeholder} pattern={f.pattern} />
              {f.hint && <span className="muted">{f.hint}</span>}
            </label>
          ))}
          <button className="btn btn-primary">I&apos;ve done this</button>
        </form>
        <details className="stuck">
          <summary>I&apos;m stuck</summary>
          <form action={stuckItem.bind(null, token, step.kind)} className="stack">
            <label>
              What&apos;s getting in the way? (optional)
              <textarea name="note" rows={3} maxLength={2000} />
            </label>
            <button className="btn">Ask for help</button>
          </form>
        </details>
        {reset}
      </>
    );
  }

  return (
    <section className={`card item item-${item.status}`}>
      <div className="item-head">
        <h2>
          <span className="step-n">{n}</span>
          {step.title}
        </h2>
        <StatusBadge status={item.status} />
      </div>
      <p className="why">{step.why}</p>
      {body}
    </section>
  );
}
