import Link from "next/link";
import { logout } from "@/app/actions/staff";
import { STATUS_LABEL, type ItemStatus } from "@/lib/types";

export function StatusBadge({ status }: { status: ItemStatus }) {
  return <span className={`badge badge-${status}`}>{STATUS_LABEL[status]}</span>;
}

export function Progress({ done, total }: { done: number; total: number }) {
  const pct = total ? Math.round((done / total) * 100) : 0;
  return (
    <div className="progress" role="progressbar" aria-valuenow={pct} aria-valuemin={0} aria-valuemax={100}>
      <span style={{ width: `${pct}%` }} />
    </div>
  );
}

export function ago(date: Date | string | null) {
  if (!date) return "—";
  const mins = Math.round((Date.now() - new Date(date).getTime()) / 60000);
  if (mins < 1) return "just now";
  if (mins < 60) return `${mins}m ago`;
  if (mins < 60 * 24) return `${Math.round(mins / 60)}h ago`;
  return `${Math.round(mins / 1440)}d ago`;
}

export function StaffShell({ email, children }: { email: string; children: React.ReactNode }) {
  return (
    <>
      <header className="topbar">
        <Link href="/dashboard" className="brand">
          <span className="mark" aria-hidden />
          PatientPath <span className="brand-sub">Onboarding</span>
        </Link>
        <nav>
          <Link href="/dashboard">Clients</Link>
          <Link href="/clients/new" className="btn btn-primary">
            New client
          </Link>
          <form action={logout}>
            <button className="linklike" title={`Signed in as ${email}`}>
              Sign out
            </button>
          </form>
        </nav>
      </header>
      <main className="wrap">{children}</main>
    </>
  );
}
