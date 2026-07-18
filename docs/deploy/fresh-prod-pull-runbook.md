# Fresh Prod Pull — Cutover Runbook

**The one page to follow after pulling a fresh production DB into DevKinsta.**

The theme is in git. The database is **not**. A fresh prod pull replaces the
whole local DB, wiping every DB-side change (slugs, redirects, product
categories/tags/flags, page templates, custom meta). Only what's in git
survives. This runbook is the exact sequence to get a freshly-pulled DB back to
current.

Background (the *why*): `docs/superpowers/specs/db-persistence-strategy.md`.
Where a new change gets captured: `scripts/db/README.md`.

---

## TL;DR

```bash
# 1. Pull fresh prod DB into DevKinsta (MyKinsta / DevKinsta UI) — this WIPES local DB.
# 2. Get the code:
git switch dev && git pull --ff-only origin dev
# 3. Replay every captured DB change (catalog scripts + redirects, in order):
npm run db:apply
# 4. Flush + verify (below).
```

If `npm run db:apply` finishes without error, the DB side is done. Everything
below is detail and verification.

---

## The order of operations

A fresh pull has **no** repo fixes in it. The sequence is always:

1. **Pull fresh prod DB** — via MyKinsta/DevKinsta. Wipes local DB. (Theme files
   on disk are untouched, but the DB now knows nothing about our fixes.)
2. **Pull the code** — `git switch dev && git pull --ff-only origin dev`. This is
   the durable half: all theme code + all the capture files (`scripts/db/*.sh`,
   `db/redirects.json`).
3. **Replay the DB** — `npm run db:apply`. This is the volatile half, rebuilt
   from the repo.
4. **Flush caches / rewrite rules**, then **verify**.

Never step 3 before step 2 — the replay scripts live in the code you just pulled.

---

## What `npm run db:apply` actually does

Runs `scripts/db/apply`, which executes every `scripts/db/NNN-*.sh` **in filename
order**, then reminds you about the manual channels. Key facts:

- **Applies for real by default** (`DRY_RUN=0`). A replay that silently no-ops is
  the exact failure this system exists to prevent.
- **Idempotent** — every script is safe to re-run; already-applied changes skip.
- **Stops on first error** (`set -e`) — a red run means something's wrong; don't
  ignore it.
- **Redirects are included** — `037-import-redirects.sh` replays
  `db/redirects.json` (1,482 entries) as part of the run. You do **not** import
  redirects by hand.

Preview without writing:

```bash
DRY_RUN=1 npm run db:apply
```

Target a non-DevKinsta install:

```bash
WP_CONTAINER="" WP_PATH=/path/to/wp scripts/db/apply
```

---

## The capture channels (what's covered, what's manual)

| Channel | Holds | Replayed by | Manual? |
|---|---|---|---|
| **Catalog scripts** | product cats/tags/meta/flags, page templates, slugs, page content | `scripts/db/NNN-*.sh` via `npm run db:apply` | No — automatic |
| **Redirects** | Redirection-plugin rows | `db/redirects.json` via `037` (inside `db:apply`) | No — automatic |
| **Content departments** | Service Hub `content_department` assignments | `db/imports/content-departments.csv` via `036` | No — automatic |
| **ACF / CPT-UI** | field groups, custom post types/taxonomies | `db/acf-cptui/` | **Currently empty — see note** |

**ACF/CPT-UI note:** there is no `db/acf-cptui/` export today. The `profile` /
`manual` / `video` etc. CPT-UI definitions live in the DB and **ride along on a
fresh prod pull** — prod already has them, so nothing to replay. This channel
only becomes relevant if you edit a CPT/taxonomy/ACF group **locally** and need
it to persist; then export to `db/acf-cptui/` in that same task. Until then,
this row is a no-op.

---

## ⚠️ Manual assets — uploads binaries (NOT git, NOT db:apply)

Some deliverables are **binary files in `wp-content/uploads/`**, which is
neither in the theme git repo nor in the DB-script channel. `git pull` won't
bring them and `db:apply` only registers the *attachment row* — the file itself
must already be on disk. **You must upload these to prod manually (MyKinsta)**,
then the matching `db:apply` script registers them.

| File | Where | Registered by | Why it matters |
|---|---|---|---|
| `20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p-optimized.mp4` | prod `/wp-content/uploads/2026/05/` | `scripts/db/035` | The MACH II family + Combo hero videos point at this optimized file (#85). **If it's not uploaded, both hero videos 404.** |

Steps at cutover:
1. Upload the file above to prod's `/wp-content/uploads/2026/05/` via MyKinsta.
2. Run `npm run db:apply` (035 registers the attachment).
3. Verify the hero video plays on the MACH II family + Combo pages.

The original (`...720p.mp4`, no `-optimized`) is kept on prod for rollback; you
don't need to touch it.

---

## After the replay: flush + verify

```bash
# Flush rewrite rules (slug/template changes) and object cache.
docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech rewrite flush --allow-root
docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech cache flush --allow-root
```

Spot-check the highest-risk items (these are the ones a pull silently breaks):

- **Readiness quiz** (`046`): `/portable-rollforming-machine-readiness-assessment/`
  renders the in-theme quiz, **not** a bare "Not Found" iframe.
- **Redirects** (`037`): a known legacy URL 301s instead of 404ing.
- **Profiles filter** (`047`): `/profiles/` sidebar → "Filter by Machine" lists
  **SSQ3 MultiPro**.
- **Corbel lender logo** (`044`): Finance Center shows the Corbel logo, not a
  placeholder.
- **Placeholder schema purged** (`041`/`042`): no broken placeholder JSON-LD in
  page source.
- **Product copy notes** (`048`): EZ-Counter, PLC07, PLC08 excerpts show their
  machine-fitment notes.
- **Duplicate UNIQ controller** (`049`): only ONE UNIQ Automatic Control System
  is published (the $22,500 one); the old `/…/uniq-control-system/` URL 301s to
  it.
- **MACH II hero video** (manual upload + `035`): the family + Combo hero videos
  play (see the Manual assets section — this needs the file uploaded first).

Full dry-run preview of what each script would touch:
`DRY_RUN=1 npm run db:apply`.

---

## Compare local vs prod (optional sanity)

`scripts/db/diff-prod-vs-dev.sh` reports drift between the two. Use it if a
replay looks incomplete or you suspect a script didn't cover a change.

---

## The rule that keeps this working

**Every DB-side change gets captured in the same task that makes it.** No silent
DB edits. If you change catalog data, a slug, a redirect, a template
assignment, or a CPT/ACF definition and it isn't also a file in this repo, the
next fresh pull erases it. When in doubt, `scripts/db/README.md` has the
"where does my change go?" table.

Slug changes are always **two** files: the slug-edit script **and** an old→new
redirect. Never a bare slug change.
