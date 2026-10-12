# PatientPath Onboarding Portal

Internal tool for getting access to a new practice's marketing accounts. Staff create a client, the client gets one link, and both sides see the same live checklist. Clients never share passwords: they add us as a partner or user, and keep ownership.

**Phase 0 (this):** guided steps for Meta, Google Ads, GA4, Tag Manager and Google Business Profile, with a diagnostic question per step ("Who manages your Facebook Page?") that branches into the right instructions. Staff confirm each item by hand.

## How it works

| Who | Does |
|---|---|
| Staff | Sign in with a magic link (only `ALLOWED_EMAIL_DOMAINS`). Create a client, pick which accounts you need, email them the link. |
| Client | Opens the link (no login), answers one question per account, follows the steps, taps **I've done this** or **I'm stuck**. |
| Staff | Dashboard sorts clients needing attention first. Check the access landed, click **Mark confirmed**, or **Reopen for client**. |
| System | Emails staff when a client is stuck or finishes. Weekday cron reminds clients with steps still to do, every `REMINDER_INTERVAL_DAYS`. |

Item statuses: **To do** → **Done — confirming** (client says done) → **Confirmed** (staff verified). **Needs help** means it's waiting on us, so those are never sent reminders.

## Run locally

```bash
cp .env.example .env.local   # set DATABASE_URL and SESSION_SECRET at minimum
npm install
npm run migrate
npm run dev                  # http://localhost:3000
```

With `RESEND_API_KEY` empty, every email (including sign-in links) prints to the server console.

## Deploy (Vercel + Neon or Supabase Postgres)

1. Import the repo into Vercel with **Root Directory** set to `onboarding-portal`.
2. Set every variable from `.env.example`. `APP_URL` must be the deployed URL. `CRON_SECRET` enables the reminder cron in `vercel.json`.
3. Run `npm run migrate` against the production `DATABASE_URL` once (and after schema changes; it's idempotent).
4. In Resend, verify the domain used in `EMAIL_FROM`.

Fill in `META_BUSINESS_ID` and `AGENCY_GOOGLE_EMAIL` before sending real links. Clients see these in their instructions.

## Editing the steps

All client-facing copy lives in `lib/steps.ts`: one entry per account with its question, answers and instructions. Each answer has an outcome:

- `instructions`: show the steps, then the client marks it done or stuck
- `nothing_needed`: marked done right away ("we'll set it up")
- `needs_help`: flagged as stuck and staff are emailed

Step text supports `**bold**`, `` `code` `` and `[links](https://…)`. To add a new account type, add its kind to `ITEM_KINDS` in `lib/types.ts` and an entry to `STEPS`.

## Next phases

- **Phase 1:** real API grants for GA4, GTM and Google Ads. Register providers in `lib/access.ts`; the wizard already shows a **Connect automatically** button for any kind with a `connectUrl`. Client OAuth tokens are used once to make the grant and never stored.
- **Phase 2:** auto-verify Meta by reading our own Business Manager's partner assets with a System User token (no Meta App Review needed). Add Google Business Profile API.
- **Phase 3:** non-ad items: hosting/DNS, call tracking, review platforms, practice management systems.

## Layout

```
app/onboard/[token]/   client wizard (public, scoped by link token)
app/clients/, app/dashboard/   staff pages (requireStaff on every page and action)
app/actions/           server actions: staff.ts, onboard.ts
app/api/cron/reminders/  reminder job
lib/steps.ts           onboarding catalog: questions, answers, instructions
lib/access.ts          Phase 1 seam for API-backed grants
db/schema.sql          Postgres schema
```
