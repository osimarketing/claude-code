import type { Client, Item, ItemKind } from "./types";

/**
 * Seam for Phase 1. Today every item is "guided": the client follows instructions and
 * staff verify by hand. Phase 1 registers API-backed providers here — GA4 accessBindings,
 * GTM user_permissions, Google Ads CustomerUserAccessInvitation — and the client wizard
 * shows a "Connect automatically" button for any kind whose provider has a connectUrl.
 */
export interface AccessProvider {
  kind: ItemKind;
  /** Where to send the client to start an OAuth grant for this item. */
  connectUrl?(client: Client): string;
  /** Check from our side that access landed, e.g. by reading our own Business Manager's partner list. */
  verify?(client: Client, item: Item): Promise<boolean>;
}

const providers: Partial<Record<ItemKind, AccessProvider>> = {};

export function providerFor(kind: ItemKind): AccessProvider | undefined {
  return providers[kind];
}
