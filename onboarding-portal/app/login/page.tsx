import { redirect } from "next/navigation";
import { requestLogin } from "@/app/actions/staff";
import { currentStaff } from "@/lib/auth";

export const dynamic = "force-dynamic";

export default async function Login({ searchParams }: { searchParams: Promise<{ sent?: string; expired?: string }> }) {
  if (await currentStaff()) redirect("/dashboard");
  const sp = await searchParams;
  return (
    <main className="center">
      <div className="card narrow">
        <div className="brand">
          <span className="mark" aria-hidden />
          PatientPath <span className="brand-sub">Onboarding</span>
        </div>
        <h1>Staff sign-in</h1>
        {sp.sent ? (
          <p>If that address is allowed, a sign-in link is on its way. It expires in 15 minutes.</p>
        ) : (
          <form action={requestLogin} className="stack">
            {sp.expired && <p className="error">That link expired or was already used. Request a new one.</p>}
            <label>
              Work email
              <input type="email" name="email" required autoComplete="email" placeholder="you@orthosyneticsinc.com" />
            </label>
            <button className="btn btn-primary">Email me a sign-in link</button>
          </form>
        )}
      </div>
    </main>
  );
}
