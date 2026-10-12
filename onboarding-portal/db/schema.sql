-- Idempotent: safe to re-run via `npm run migrate`.

create table if not exists clients (
  id               uuid primary key default gen_random_uuid(),
  practice_name    text not null,
  contact_name     text not null,
  contact_email    text not null,
  locations        int  not null default 1 check (locations > 0),
  link_token       text not null unique,
  created_by       text not null,
  created_at       timestamptz not null default now(),
  last_reminded_at timestamptz,
  archived_at      timestamptz
);

create table if not exists onboarding_items (
  id          uuid primary key default gen_random_uuid(),
  client_id   uuid not null references clients(id) on delete cascade,
  kind        text not null,
  status      text not null default 'pending'
              check (status in ('pending', 'stuck', 'client_done', 'verified')),
  -- The client's diagnostic answer plus any fields they entered, e.g. {"answer":"yes","customer_id":"123-456-7890"}
  diagnostic  jsonb not null default '{}',
  client_note text,
  verified_by text,
  updated_at  timestamptz not null default now(),
  unique (client_id, kind)
);

create table if not exists events (
  id         bigserial primary key,
  client_id  uuid not null references clients(id) on delete cascade,
  actor      text not null,
  message    text not null,
  created_at timestamptz not null default now()
);
create index if not exists events_client_idx on events (client_id, created_at desc);

-- Single-use magic-link tokens; only the SHA-256 hash is stored.
create table if not exists login_tokens (
  token_hash text primary key,
  email      text not null,
  expires_at timestamptz not null,
  used_at    timestamptz
);
