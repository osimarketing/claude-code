import { createClient } from "@/app/actions/staff";
import { StaffShell } from "@/components/ui";
import { requireStaff } from "@/lib/auth";
import { STEPS } from "@/lib/steps";

export default async function NewClient({ searchParams }: { searchParams: Promise<{ error?: string }> }) {
  const email = await requireStaff();
  const { error } = await searchParams;
  return (
    <StaffShell email={email}>
      <h1>New client</h1>
      <form action={createClient} className="card stack form-card">
        {error && <p className="error">Fill in every field and pick at least one account.</p>}
        <label>
          Practice name
          <input name="practice_name" required placeholder="Bright Smiles Orthodontics" />
        </label>
        <div className="grid-2">
          <label>
            Contact name
            <input name="contact_name" required placeholder="Office manager's name" />
          </label>
          <label>
            Contact email
            <input name="contact_email" type="email" required />
          </label>
        </div>
        <label className="short">
          Locations
          <input name="locations" type="number" min={1} max={99} defaultValue={1} />
        </label>
        <fieldset>
          <legend>Accounts we need access to</legend>
          {STEPS.map((s) => (
            <label key={s.kind} className="check">
              <input type="checkbox" name={`item_${s.kind}`} defaultChecked />
              {s.title}
            </label>
          ))}
        </fieldset>
        <label className="check">
          <input type="checkbox" name="send_now" defaultChecked />
          Email the onboarding link to the contact now
        </label>
        <button className="btn btn-primary">Create client</button>
      </form>
    </StaffShell>
  );
}
