import Link from "next/link";
import { ago, Progress, StaffShell } from "@/components/ui";
import { requireStaff } from "@/lib/auth";
import { q } from "@/lib/db";

export const dynamic = "force-dynamic";

type Row = {
  id: string;
  practice_name: string;
  contact_name: string;
  total: number;
  verified: number;
  awaiting: number;
  stuck: number;
  last_activity: Date | null;
};

export default async function Dashboard() {
  const email = await requireStaff();
  // Clients needing attention first: anyone stuck, then anyone waiting on us to confirm.
  const rows = await q<Row>(`
    select c.id, c.practice_name, c.contact_name,
           count(i.id)::int as total,
           count(i.id) filter (where i.status = 'verified')::int as verified,
           count(i.id) filter (where i.status = 'client_done')::int as awaiting,
           count(i.id) filter (where i.status = 'stuck')::int as stuck,
           max(i.updated_at) as last_activity
    from clients c
    left join onboarding_items i on i.client_id = c.id
    where c.archived_at is null
    group by c.id
    order by count(i.id) filter (where i.status = 'stuck') desc,
             count(i.id) filter (where i.status = 'client_done') desc,
             c.created_at desc`);

  return (
    <StaffShell email={email}>
      <h1>Clients</h1>
      {rows.length === 0 ? (
        <div className="card empty">
          <p>No clients yet.</p>
          <Link href="/clients/new" className="btn btn-primary">
            Add your first client
          </Link>
        </div>
      ) : (
        <div className="card table-card">
          <table>
            <thead>
              <tr>
                <th>Practice</th>
                <th>Progress</th>
                <th>Needs you</th>
                <th>Last activity</th>
              </tr>
            </thead>
            <tbody>
              {rows.map((r) => (
                <tr key={r.id}>
                  <td>
                    <Link href={`/clients/${r.id}`} className="row-link">
                      {r.practice_name}
                    </Link>
                    <div className="muted">{r.contact_name}</div>
                  </td>
                  <td className="progress-cell">
                    <Progress done={r.verified} total={r.total} />
                    <span className="muted">
                      {r.verified}/{r.total} confirmed
                    </span>
                  </td>
                  <td>
                    {r.stuck > 0 && <span className="badge badge-stuck">{r.stuck} stuck</span>}{" "}
                    {r.awaiting > 0 && <span className="badge badge-client_done">{r.awaiting} to confirm</span>}
                  </td>
                  <td className="muted">{ago(r.last_activity)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </StaffShell>
  );
}
