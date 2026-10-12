import { consumeLogin } from "@/app/actions/staff";

// A button rather than signing in on page load: email security scanners (e.g. Outlook Safe
// Links) prefetch links with GET, which would otherwise burn the single-use token.
export default async function Verify({ searchParams }: { searchParams: Promise<{ token?: string }> }) {
  const { token = "" } = await searchParams;
  return (
    <main className="center">
      <form action={consumeLogin} className="card narrow stack">
        <h1>Sign in</h1>
        <input type="hidden" name="token" value={token} />
        <button className="btn btn-primary">Continue to the portal</button>
      </form>
    </main>
  );
}
