export const ITEM_KINDS = ["meta", "google_ads", "ga4", "gtm", "gbp"] as const;
export type ItemKind = (typeof ITEM_KINDS)[number];
export type ItemStatus = "pending" | "stuck" | "client_done" | "verified";

export type Client = {
  id: string;
  practice_name: string;
  contact_name: string;
  contact_email: string;
  locations: number;
  link_token: string;
  created_by: string;
  created_at: Date;
  last_reminded_at: Date | null;
};

export type Item = {
  id: string;
  client_id: string;
  kind: ItemKind;
  status: ItemStatus;
  diagnostic: Record<string, string>;
  client_note: string | null;
  verified_by: string | null;
  updated_at: Date;
};

export const STATUS_LABEL: Record<ItemStatus, string> = {
  pending: "To do",
  stuck: "Needs help",
  client_done: "Done — confirming",
  verified: "Confirmed",
};
