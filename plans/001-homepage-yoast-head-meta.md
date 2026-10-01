# Plan 001: Fix the homepage's Yoast title, meta description, and Open Graph tags

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**: `git diff --stat c5d139b..HEAD -- scripts/db/`
> If `scripts/db/` gained a script numbered 053 or higher since this plan was
> written, renumber yours to the next free number. If the "Current state" DB
> values below no longer match (Step 1 verifies this), treat it as a STOP
> condition.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug (SEO)
- **Planned at**: commit `c5d139b`, 2026-07-21

## Why this matters

The live homepage `<meta name="description">` literally ends mid-sentence with
`...` — that exact text is stored in the database, so every Google snippet, and
the site-summary line of the Yoast-generated `/llms.txt` that AI crawlers
ingest, ends in a truncated ellipsis. The Yoast SEO title also targets
"Seamless Gutter Machines" while the H1 says "Portable Rollforming Machines"
(category pages already win the gutter-machine queries — Search Console data
confirms the homepage wins brand queries). Finally, the Open Graph fields are
unset, so shares render as og:title "Home" with a scraped testimonial fragment
as the description. This is a **database** fix, not a theme fix: per this
repo's DB-persistence strategy, it must be captured as a replayable
`scripts/db/NNN-*.sh` migration or it will be wiped by the next fresh prod
DB pull.

## Current state

Verified against the local DevKinsta DB on 2026-07-21 (local DB = fresh prod
pull + replay, so prod holds the same values):

- Static front page is post ID **199** (title "Home"), resolved via
  `get_option('page_on_front')`. **Never hardcode 199** — resolve it at
  runtime; prod IDs could differ.
- Current postmeta on that post:
  - `_yoast_wpseo_title` = `Seamless Gutter Machines & Rollforming Equipment | %%sitename%%`
  - `_yoast_wpseo_metadesc` = `New Tech Machinery manufactures and sells portable rollforming equipment for standing seam metal roofing and the seamless gutter industries...`
    (the trailing `...` is literal, stored in the DB)
  - `_yoast_wpseo_opengraph-title` = `` (empty)
  - `_yoast_wpseo_opengraph-description` = `` (empty)
  - `_yoast_wpseo_opengraph-image` / `-image-id` = `` (empty — leave these
    alone; Yoast falls back to the page's featured image, which is fine)
- Yoast SEO Premium is active and **renders head tags from its
  `wp_yoast_indexable` table, not directly from postmeta**. Updating postmeta
  alone will NOT change the rendered page until the indexable row for the
  front page is invalidated (Step 3 handles this).
- Migration-script conventions live in `scripts/db/README.md`. Match the
  structure of `scripts/db/052-disable-schema-pro-site-navigation.sh` exactly:
  bash wrapper, `DRY_RUN=1` default (runner flips it), guarded writes that
  verify current values before changing them, idempotent re-runs that no-op,
  `set -uo pipefail` (deliberately NOT `set -e` — see comment in 041/052),
  and the docker `wp eval-file` runner block at the bottom (copy it verbatim
  from 052, changing only the tmp-file prefix).

## Target values

| Meta key | New value |
|---|---|
| `_yoast_wpseo_title` | `Portable Rollforming Machines \| %%sitename%%` |
| `_yoast_wpseo_metadesc` | `New Tech Machinery builds portable rollforming machines for standing seam metal roofing and seamless gutters, so contractors form panels on-site, on demand.` |
| `_yoast_wpseo_opengraph-title` | `Portable Rollforming Machines \| New Tech Machinery` |
| `_yoast_wpseo_opengraph-description` | same string as the new `_yoast_wpseo_metadesc` |

(The metadesc is 156 characters — inside the 150–160 window. The `%%sitename%%`
variable is Yoast template syntax and must be stored literally in the title
meta; do NOT expand it yourself. The OG fields take literal strings.)

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint | `npm run lint:php` | exit 0 |
| Migration tests | `npm run test:db-migrations` | exit 0 |
| Dry-run one script | `bash scripts/db/053-homepage-yoast-head-meta.sh` | `[dry-run]` lines, exit 0 |
| Apply one script | `DRY_RUN=0 bash scripts/db/053-homepage-yoast-head-meta.sh` | applied lines, exit 0 |
| Rendered check | `curl -sk https://newtech.local/ \| grep -o '<title>[^<]*</title>'` | new title |

The DB is shared between worktrees and the served checkout, so DB-side
verification via curl works even if you are working from a git worktree.

## Scope

**In scope** (the only files you should create/modify):
- `scripts/db/053-homepage-yoast-head-meta.sh` (create)

**Out of scope** (do NOT touch):
- `app/inc/seo.php` — that file is a fallback that no-ops while Yoast is
  active; it is not involved in this bug.
- The WordPress admin UI — the change must be a replayable script.
- `_yoast_wpseo_opengraph-image` / `-image-id` — the featured-image fallback
  is correct.
- Any other post's Yoast meta.

## Git workflow

- Branch off `dev`: `joelgrodriguez/053-homepage-yoast-head-meta`
- One commit; message style matches `git log` (short imperative subject, e.g.
  "Capture homepage Yoast head meta fix").
- Do NOT push or merge unless the operator instructed it.

## Steps

### Step 1: Confirm current DB values

Run (read-only):

```bash
docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech --allow-root eval '
$front = (int) get_option("page_on_front");
echo "front=$front\n";
echo get_post_meta($front, "_yoast_wpseo_metadesc", true) . "\n";
echo get_post_meta($front, "_yoast_wpseo_title", true) . "\n";'
```

**Verify**: output shows the exact stale strings from "Current state". If the
metadesc no longer ends in `...` or the title differs → STOP condition (someone
already fixed it).

### Step 2: Write `scripts/db/053-homepage-yoast-head-meta.sh`

Model on `scripts/db/052-disable-schema-pro-site-navigation.sh`. The embedded
PHP must:

1. Resolve `$front = (int) get_option('page_on_front')`; if `<= 0`, print a
   `skip:` line and return.
2. For each of the four meta keys, read the current value and apply a
   **guarded** update:
   - `_yoast_wpseo_title`: only overwrite if the current value is exactly the
     stale string above (or already the target — then print `skip: already
     applied`). Any other value → print `skip: unexpected value '<value>';
     leaving unchanged` and do NOT write (mirrors 052's legacy-value guard).
   - `_yoast_wpseo_metadesc`: same pattern, guarded on the exact stale string.
   - The two OG keys: only write if currently empty (`''`) or already the
     target. A non-empty different value → `skip`, don't clobber.
3. Honor `NTM_DRY_RUN` exactly like 052: print `[dry-run] would set …` without
   writing when dry.
4. After a real write, re-read each meta and error (exit 1) if the stored
   value doesn't match the target.

**Verify**: `bash scripts/db/053-homepage-yoast-head-meta.sh` (dry run) →
prints the matched current values and `[dry-run] would set` lines for all four
keys, exit 0. Run it twice — identical output (no state changed).

### Step 3: Invalidate the Yoast indexable in the same script

After the guarded meta writes (and only when NOT dry-run and at least one
write happened), delete the front page's indexable row so Yoast rebuilds it
with the new meta on the next request:

```php
$deleted = $wpdb->delete(
    $wpdb->prefix . 'yoast_indexable',
    ['object_id' => $front, 'object_type' => 'post'],
    ['%d', '%s']
);
echo "    purged {$deleted} yoast_indexable row(s) for post {$front}.\n";
```

In dry-run mode, instead SELECT and report how many rows would be purged.
Deleting the row is safe — Yoast recreates indexables on demand; this is its
own recovery path.

**Verify**: dry-run output now also reports `would purge 1 yoast_indexable
row(s)` (or 0 if Yoast hasn't built one, which is fine).

### Step 4: Apply and check the rendered page

```bash
DRY_RUN=0 bash scripts/db/053-homepage-yoast-head-meta.sh
```

**Verify** (all against `curl -sk https://newtech.local/`):
- `grep -o '<title>[^<]*</title>'` → `<title>Portable Rollforming Machines | New Tech Machinery</title>`
- `grep -o '<meta name="description"[^>]*>'` → contains `on-site, on demand.` and does NOT contain `...`
- `grep -o '<meta property="og:title"[^>]*>'` → contains `Portable Rollforming Machines`, not `Home`

Then run the script a third time (default dry-run) → every line reads
`skip: already applied` (idempotency proof).

### Step 5: Lint and migration tests

**Verify**: `npm run lint:php` → exit 0. `npm run test:db-migrations` →
exit 0. If the test suite fails specifically because the new script is missing
a guard the tests enforce, read the failure message and add the guard it names;
if the failure is unrelated to your script, STOP and report.

## Test plan

The migration guard suite (`scripts/db/tests/migration-guards.test.php`, run
via `npm run test:db-migrations`) automatically covers scripts in
`scripts/db/`. No separate new test file; the idempotency and guard behavior
proven in Steps 2 and 4 are the functional tests.

## Done criteria

- [ ] `scripts/db/053-homepage-yoast-head-meta.sh` exists, executable, dry-run by default
- [ ] `DRY_RUN=0` run applied all four metas; re-run no-ops with `skip` lines
- [ ] `curl -sk https://newtech.local/ | grep '<title>'` shows the new title
- [ ] Rendered meta description contains no `...`
- [ ] Rendered `og:title` is no longer `Home`
- [ ] `npm run lint:php` and `npm run test:db-migrations` exit 0
- [ ] No files outside the in-scope list modified (`git status`)
- [ ] `plans/README.md` status row updated

## STOP conditions

Stop and report back (do not improvise) if:

- Step 1 shows values that differ from the "Current state" strings.
- The `wp_yoast_indexable` table does not exist (Yoast version drift).
- After applying, the rendered `<title>` still shows the old value — do NOT
  start deleting other Yoast rows or flushing caches beyond Step 3; report.
- `npm run test:db-migrations` fails for a reason unrelated to your script.

## Maintenance notes

- At production cutover this script replays via `npm run db:apply` like every
  other `scripts/db/NNN-*.sh` — no manual admin work needed.
- If marketing later edits the homepage SEO fields in wp-admin, the guards
  make this script no-op silently (by design: admin edits win).
- Reviewer should scrutinize: the guard strings match the DB exactly (byte
  for byte, including `&` and the trailing `...`), and post ID is resolved,
  never hardcoded.
