import crypto from "node:crypto";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";

export const SESSION_COOKIE = "pp_staff";
const SESSION_DAYS = 7;

function secret() {
  const s = process.env.SESSION_SECRET;
  if (!s || s.length < 32) throw new Error("SESSION_SECRET must be set to at least 32 characters");
  return s;
}

function sign(data: string) {
  return crypto.createHmac("sha256", secret()).update(data).digest("base64url");
}

export function hashToken(token: string) {
  return crypto.createHash("sha256").update(token).digest("hex");
}

export function isAllowedEmail(email: string) {
  const domains = (process.env.ALLOWED_EMAIL_DOMAINS ?? "orthosyneticsinc.com")
    .split(",")
    .map((d) => d.trim().toLowerCase())
    .filter(Boolean);
  const at = email.lastIndexOf("@");
  return at > 0 && domains.includes(email.slice(at + 1).toLowerCase());
}

export function sealSession(email: string) {
  const payload = Buffer.from(JSON.stringify({ email, exp: Date.now() + SESSION_DAYS * 864e5 })).toString("base64url");
  return { value: `${payload}.${sign(payload)}`, maxAge: SESSION_DAYS * 86400 };
}

function openSession(value: string | undefined): string | null {
  if (!value) return null;
  const [payload, sig] = value.split(".");
  if (!payload || !sig) return null;
  const expected = Buffer.from(sign(payload));
  const given = Buffer.from(sig);
  if (given.length !== expected.length || !crypto.timingSafeEqual(given, expected)) return null;
  try {
    const { email, exp } = JSON.parse(Buffer.from(payload, "base64url").toString());
    // Re-check the domain so removing one from ALLOWED_EMAIL_DOMAINS revokes live sessions.
    if (typeof email !== "string" || Date.now() > exp || !isAllowedEmail(email)) return null;
    return email;
  } catch {
    return null;
  }
}

export async function currentStaff() {
  return openSession((await cookies()).get(SESSION_COOKIE)?.value);
}

/** Call at the top of every staff page and staff server action. */
export async function requireStaff() {
  const email = await currentStaff();
  if (!email) redirect("/login");
  return email;
}
