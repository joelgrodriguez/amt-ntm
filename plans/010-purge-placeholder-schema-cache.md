# Plan 010: Purge the placeholder rows from Schema Pro's per-post output cache (db 042)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md`.
>
> **Drift check (run first)**: `ls scripts/db/ | grep '^042'` → empty (042 free),
> and `scripts/db/041-unpublish-placeholder-custom-schema.sh` exists on your branch.

## Status

- **Priority**: P1 (completes plan 009 — production is serving the broken schema from this cache)
- **Effort**: S
- **Risk**: LOW–MED (mass meta deletion, but strictly scoped to rows containing the placeholder)
- **Depends on**: plans/009-remove-placeholder-schema-db.md (DONE — source post 18575 is draft)
- **Category**: seo / db-capture
- **Planned at**: commit `f8e20f9`, 2026-07-10

## Why this matters

Plan 009 drafted the placeholder "Sitewide Custom Schema" source post, but WP Schema Pro caches its rendered output per post in the meta key `wp_schema_pro_optimized_structured_data`. **788 posts** still carry the invalid placeholder block (contains `NEWTECH-UPDATE`) in that cache, so ~788 pages — including the flagship machine pages — keep serving broken JSON-LD. Deleting a cache row is safe by design: Schema Pro regenerates it from the live schema posts on next render, and the placeholder source is now draft, so it cannot regenerate. This purge is what actually fixes production at cutover (via `npm run db:apply`).

## Current state

- Verified count: `SELECT COUNT(*) FROM wp_postmeta WHERE meta_key='wp_schema_pro_optimized_structured_data' AND meta_value LIKE '%NEWTECH-UPDATE%'` → **788**.
- Post 18575 ("Sitewide Custom Schema", `aiosrs-schema`) is `draft` (plan 009). Post 18578 ("Home Page Custom Schema") is legitimate and publish — its cached copies are clean and must NOT be touched (they don't match the needle, so the scoped delete naturally spares them).
- Flagship canary: post **18601** (`/machines/roof-wall-panel-machines/ssq3-multipro/`) has a placeholder-bearing cache row (verified 2026-07-10).
- Script conventions: model on your own `scripts/db/041-unpublish-placeholder-custom-schema.sh` — same header style, DRY_RUN default 1, direct `docker exec` for `eval-file` (the runner-quirk workaround you documented), mktemp without suffix, `set -uo pipefail`, no `set -e`.
- WP-CLI form: `docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech <cmd> --allow-root`.

## Scope

**In scope**:
- Create `scripts/db/042-purge-placeholder-schema-cache.sh`
- Run canary (one post), then apply the full purge locally (`DRY_RUN=0`)

**Out of scope** (do NOT touch):
- Any `wp_schema_pro_optimized_structured_data` row NOT containing the needle
- The Schema Pro plugin, its settings, posts 18578/17947/17880/4656/4655
- Any other meta key; any theme code

## Git workflow

- Continue on your existing branch `advisor/009-remove-placeholder-schema` in your existing worktree.
- One new commit, subject like: `Purge placeholder Schema Pro output cache (db 042)`
- Do NOT push.

## Steps

### Step 1: Canary — purge one post's cache row and verify the page

Delete the cache meta for post 18601 only (via `wp post meta delete 18601 wp_schema_pro_optimized_structured_data` or a scoped eval), then:

- `curl -sk https://newtech.local/machines/roof-wall-panel-machines/ssq3-multipro/ -o /dev/null -w "%{http_code}"` → `200`
- `curl -sk <same URL> | grep -c 'NEWTECH-UPDATE'` → `0`
- Report whether the meta row regenerated after the curl (re-query it) and, if it did, confirm the regenerated value contains no `NEWTECH-UPDATE`.

**Verify**: all three results in your report. If the page is not 200 or the placeholder persists → STOP.

### Step 2: Write `scripts/db/042-purge-placeholder-schema-cache.sh`

Same conventions as 041. The script (single eval-file, direct docker exec):

1. Selects post_ids where `meta_key='wp_schema_pro_optimized_structured_data'` and the value contains `NEWTECH-UPDATE` (use a `$wpdb->get_col` prepared LIKE query, or `get_posts` + meta check — prefer `$wpdb` for 788 rows).
2. DRY_RUN=1: prints the count and first 10 ids, changes nothing.
3. DRY_RUN=0: `delete_post_meta($id, 'wp_schema_pro_optimized_structured_data')` per id (delete the whole cache row — Schema Pro regenerates clean; do not try to surgically edit the cached JSON). Echo the deleted count.
4. Idempotent: a re-run matches 0 rows and says so.
5. Header: WHY (009's drafting doesn't invalidate Schema Pro's per-post output cache; 788 pages still serve broken JSON-LD; replayed at cutover to fix prod), the canary evidence, and the verification curls.

**Verify**: `bash -n scripts/db/042-purge-placeholder-schema-cache.sh` → exit 0.

### Step 3: Dry-run, then apply

Dry-run must report ~787 remaining (788 minus the canary). Apply with `DRY_RUN=0`.

**Verify (apply)**: count query → `0`.

### Step 4: Site-wide verification

- `curl -sk https://newtech.local/ | grep -c 'NEWTECH-UPDATE'` → `0`
- `curl -sk https://newtech.local/machines/roof-wall-panel-machines/ssq3-multipro/ | grep -c 'NEWTECH-UPDATE'` → `0`
- Pick 2 more affected URLs (a video post, a manual or profile) from the earlier type breakdown, curl each → `200` and `0` placeholder hits.
- JSON-LD parse check on the flagship: every `application/ld+json` block parses (`python3` json.loads loop) → 0 parse failures.

### Step 5: Idempotency re-run

Second `DRY_RUN=0` run → "0 rows" / no-op output, exit 0.

## Done criteria

- [ ] `scripts/db/042-purge-placeholder-schema-cache.sh` exists, `bash -n` clean, 041-style conventions
- [ ] Placeholder cache-row count → 0
- [ ] All Step 4 curls pass (200s, zero `NEWTECH-UPDATE`, zero JSON-LD parse failures on flagship)
- [ ] Re-run is a clean no-op
- [ ] One new commit on `advisor/009-remove-placeholder-schema`; `git status` clean

## STOP conditions

- Canary page 5xx/fatal after deleting its cache row (revert impossible — the row is a cache — but STOP and report before bulk).
- The placeholder persists on the canary page after cache deletion + a second fresh curl (another cache layer exists — report what you find).
- The count query matches a wildly different number than ~788 (state changed since planning).

## Maintenance notes

- If Schema Pro is ever repopulated with a repaired Sitewide Custom Schema, its output caches rebuild on render — nothing here blocks that.
- The owner decision from plan 009 stands: evaluate whether Schema Pro earns its keep next to Yoast + the theme's machine-schema emitter. That's Joel's call, post-launch.
