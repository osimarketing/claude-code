import type { ItemKind } from "./types";

// Step text supports **bold**, `code` and [label](https://url) — see components/Rich.tsx.

export type Ctx = { agencyName: string; metaBusinessId: string; googleEmail: string; locations: number };

export type Outcome =
  | "instructions" // show steps, then the client marks it done (or stuck)
  | "nothing_needed" // nothing for the client to do; we'll set it up
  | "needs_help"; // flag to staff straight away

export type Field = { name: string; label: string; placeholder?: string; pattern?: string; hint?: string };
export type Answer = {
  value: string;
  label: string;
  outcome: Outcome;
  steps?: (c: Ctx) => string[];
  /** Extra details the client enters when marking this path done. */
  fields?: Field[];
};

export type StepDef = {
  kind: ItemKind;
  title: string;
  why: string;
  question: string;
  answers: Answer[];
};

export function agencyCtx(locations: number): Ctx {
  return {
    agencyName: process.env.AGENCY_NAME || "PatientPath",
    metaBusinessId: process.env.META_BUSINESS_ID || "(your PatientPath contact will send this)",
    googleEmail: process.env.AGENCY_GOOGLE_EMAIL || "(your PatientPath contact will send this)",
    locations,
  };
}

const metaShare = (c: Ctx) =>
  `Share it with us: **Settings → Users → Partners → Add → "Give a partner access to your assets"**, enter our Business ID \`${c.metaBusinessId}\`, then select your Page, ad account and pixel/dataset (if you have them) with **Full control** and save.`;

export const STEPS: StepDef[] = [
  {
    kind: "meta",
    title: "Facebook & Instagram",
    why: "Lets us run your ads and manage your Page — without your password. Your practice keeps ownership and can remove us any time.",
    question: "How is your practice's Facebook Page managed today?",
    answers: [
      {
        value: "portfolio",
        label: "We have a Meta Business Portfolio (Business Manager)",
        outcome: "instructions",
        steps: (c) => [
          "Open [Meta Business Settings](https://business.facebook.com/settings) and choose your practice's portfolio.",
          metaShare(c),
          "If an Instagram account is connected, add it in the same screen.",
        ],
      },
      {
        value: "personal",
        label: "From someone's personal Facebook profile (e.g. the doctor's)",
        outcome: "instructions",
        steps: (c) => [
          "Log into Facebook as the person who manages the Page.",
          "Go to [business.facebook.com](https://business.facebook.com/overview) and create a business portfolio using the practice's name and a practice email address.",
          'In the new portfolio, open **Settings → Accounts → Pages → Add → "Add an existing Facebook Page"** and pick your practice\'s Page.',
          metaShare(c),
          "This keeps the Page owned by the practice rather than any one person — or us.",
        ],
      },
      {
        value: "previous_agency",
        label: "A previous marketing company set it up",
        outcome: "instructions",
        steps: (c) => [
          "Ask them to share your assets with us directly. You can forward this:",
          `"Please add ${c.agencyName} as a partner on our Facebook Page, ad account and pixel. Their Meta Business ID is ${c.metaBusinessId}."`,
          "If they haven't done it within a few days, tap **I'm stuck** below and we'll take it from there.",
        ],
      },
      { value: "none", label: "We don't have a Facebook Page", outcome: "nothing_needed" },
      { value: "unsure", label: "Not sure", outcome: "needs_help" },
    ],
  },
  {
    kind: "google_ads",
    title: "Google Ads",
    why: "Lets us manage your search ads. You stay the owner of the account and its billing.",
    question: "Does your practice have a Google Ads account?",
    answers: [
      {
        value: "yes",
        label: "Yes",
        outcome: "instructions",
        steps: (c) => [
          "Sign in at [ads.google.com](https://ads.google.com).",
          "Go to **Admin → Access and security**, then the **Users** tab, and click **+**.",
          `Enter \`${c.googleEmail}\`, choose **Admin** access, and send the invitation.`,
          "Copy your 10-digit customer ID from the top right of the screen and enter it below.",
        ],
        fields: [
          {
            name: "customer_id",
            label: "Google Ads customer ID",
            placeholder: "123-456-7890",
            pattern: "\\d{3}-?\\d{3}-?\\d{4}",
            hint: "Shown at the top right in Google Ads.",
          },
        ],
      },
      { value: "no", label: "No", outcome: "nothing_needed" },
      { value: "unsure", label: "Not sure", outcome: "needs_help" },
    ],
  },
  {
    kind: "ga4",
    title: "Google Analytics",
    why: "Lets us see which marketing actually brings patients to your website.",
    question: "Does your website use Google Analytics?",
    answers: [
      {
        value: "yes",
        label: "Yes",
        outcome: "instructions",
        steps: (c) => [
          "Sign in at [analytics.google.com](https://analytics.google.com).",
          "Click **Admin** (the gear, bottom left), then **Account access management**.",
          "Click **+** (top right) → **Add users**.",
          `Enter \`${c.googleEmail}\`, choose the **Administrator** role, and click **Add**.`,
        ],
      },
      { value: "no", label: "No", outcome: "nothing_needed" },
      { value: "unsure", label: "Not sure", outcome: "needs_help" },
    ],
  },
  {
    kind: "gtm",
    title: "Google Tag Manager",
    why: "Lets us install tracking for appointment requests and calls without touching your website code.",
    question: "Does your website use Google Tag Manager?",
    answers: [
      {
        value: "yes",
        label: "Yes",
        outcome: "instructions",
        steps: (c) => [
          "Sign in at [tagmanager.google.com](https://tagmanager.google.com).",
          "Click **Admin**, then **User Management** under your account, then **+ → Add users**.",
          `Enter \`${c.googleEmail}\`. Set account permissions to **Administrator** and give your website's container **Publish** access.`,
          "Click **Invite**.",
        ],
      },
      // GTM is easy for us to check ourselves, so "not sure" doesn't need to page anyone.
      { value: "no", label: "No", outcome: "nothing_needed" },
      { value: "unsure", label: "Not sure", outcome: "nothing_needed" },
    ],
  },
  {
    kind: "gbp",
    title: "Google Business Profile",
    why: "Your listing on Google Search and Maps — often the #1 source of new-patient calls.",
    question: "Can you sign in to manage your Google Business Profile?",
    answers: [
      {
        value: "yes",
        label: "Yes",
        outcome: "instructions",
        steps: (c) => [
          "Sign into the Google account that manages your listing, then search your practice's name on Google — the profile editor appears at the top.",
          "Click **⋮ (More) → Business Profile settings → People and access → Add**.",
          `Enter \`${c.googleEmail}\`, choose **Manager**, and click **Invite**.`,
          ...(c.locations > 1 ? [`Repeat for each of your ${c.locations} locations.`] : []),
        ],
      },
      {
        value: "no_access",
        label: "Someone else controls it, or we're not sure who",
        outcome: "instructions",
        steps: () => [
          "Search your practice's name on Google and click **Own this business?** on your listing.",
          "Follow Google's steps to **request access**. Google asks the current owner to respond.",
          "Let us know below once you've requested it — if it stalls, tap **I'm stuck** and we'll help.",
        ],
      },
      { value: "none", label: "We don't have one", outcome: "nothing_needed" },
    ],
  },
];

export function stepFor(kind: string) {
  return STEPS.find((s) => s.kind === kind);
}

export function answerFor(kind: string, value: string | undefined) {
  return stepFor(kind)?.answers.find((a) => a.value === value);
}
