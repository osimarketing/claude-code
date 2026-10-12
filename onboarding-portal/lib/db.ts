import { Pool } from "pg";

// Reuse one pool across hot reloads in dev.
const g = globalThis as unknown as { pgPool?: Pool };
const pool = g.pgPool ?? new Pool({ connectionString: process.env.DATABASE_URL, max: 5 });
if (process.env.NODE_ENV !== "production") g.pgPool = pool;

export async function q<T>(text: string, params: unknown[] = []): Promise<T[]> {
  const res = await pool.query(text, params);
  return res.rows as T[];
}

export async function log(clientId: string, actor: string, message: string) {
  await q("insert into events (client_id, actor, message) values ($1, $2, $3)", [clientId, actor, message]);
}
