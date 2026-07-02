# DB Persistence Strategy — capture every DB-side change as a replayable file

**Status:** Canonical. This is the strategy doc that `CLAUDE.md` ("Hard rule:
capture all DB-side changes"), `scripts/db/README.md`, `scripts/db/apply`, and
the individual `scripts/db/NNN-*.sh` headers all point to.

**Provenance note:** this file was referenced across the repo before it existed
on disk; it was reconstructed on 2026-07-01 from what the repo already enforces
(the CLAUDE.md hard rule, the `scripts/db/` README + runner + script headers,
`db/redirects.json`, and `scripts/db/diff-prod-vs-dev.sh`). Nothing here is
aspirational — if a mechanism is described below, it exists in the repo today,
and open ends are labeled as open.

## The problem

The theme is git-controlled. **The database is not.** The working assumption for
most of the project was: releasing means merging the theme to `master`, pulling a
**fresh production DB** locally, and re-adding the theme from git — which wipes
every local DB change (slugs, redirects, product categories/tags, page rows,
post meta, custom flags). The only thing that survives a fresh pull is what is
in git.

> Teaching line: WordPress splits state between code (templates, in git) and
> content/config (rows in MySQL, not in git). Any fix that lives in a row is a
> fix that a DB refresh silently deletes.

## The rule

Any time an agent changes DevKinsta DB / WordPress state that must persist past
a fresh prod pull, it MUST also capture that change as a replayable file in the
repo, **in the same task**. No silent DB edits. Inspection-only commands
(reading state) need no capture.

## The four capture channels

| You changed… | Capture it as… |
|---|---|
| Product category / tag / meta / flag / page row / post content | a new `scripts/db/NNN-*.sh` (idempotent) |
| A slug | `scripts/db/NNN-*.sh` **and** an old→new redirect in `db/redirects.json` |
| A redirect | export to `db/redirects.json` (Redirection plugin rows) |
| An ACF field group / CPT-UI post type or taxonomy definition | export to `db/acf-cptui/` |
| Reading/inspecting only | nothing |

### 1. Catalog & content data → numbered WP-CLI scripts

`scripts/db/NNN-*.sh`, run in filename order by the apply runner. Copy
`scripts/db/000-template.sh.example` to the next free number. One logical change
per script; reference the backlog item it resolves
(`data-normalization-backlog.md`, sibling doc).

Existing scripts as of 2026-07-01: `010` (strip hardcoded excerpt buttons),
`020`/`021` (SEO meta), `022` (Finance Center template + hero video), `023`
(product-card thumbnail regen), `024` (knowledgebase seed), `025` (article H2
headings), `026` (service-search page), `027` (quote-checklist heading), `028`
(trailer landing page), `029`/`030` (safety page draft → publish). Their headers
are the best worked examples of the contract below.

### 2. Redirects → `db/redirects.json`

The site uses the **Redirection** plugin; redirects are DB rows in
`wp_redirection_items`, not `.htaccess`. The plugin exposes **no WP-CLI export**
(verified), so the export reads the rows directly with `wp db query` — the exact
command lives in `scripts/db/README.md`. ~352 enabled redirects are captured;
the JSON file is the source of truth. Never recreate redirects by hand.

Re-import after a fresh pull is intended to be a `scripts/db/NNN-*.sh` migration
that reads `db/redirects.json` and upserts the rows (idempotent). **That script
has not been authored yet** — the standing decision is to author it when first
needed; the committed export is what keeps the data safe in the meantime.

### 3. ACF field groups + CPT-UI definitions → `db/acf-cptui/`

Post types like `profile`, `cutlist`, `literature`, `pricesheet`, `manual`,
`footprint`, `video` are **DB-stored, not code-registered** (defined via
ACF/CPT-UI in the database). Edits to a field group or CPT definition are lost
on a fresh pull unless exported to `db/acf-cptui/`. Starting-point export
commands are in `scripts/db/README.md`; the channel is confirmed *necessary*,
but the exact export verb for the installed plugin versions is **still TODO** —
verify before trusting one path. Note: the definitions themselves ride along on
a fresh prod pull (they exist on prod); only *local* definition edits need
capturing.

### The slug two-step (never a bare slug change)

A URL change is never just a theme change. Any slug/permalink change is **two**
captured files:

1. the slug edit as a `scripts/db/NNN-*.sh`, and
2. the old→new 301 in `db/redirects.json`.

Without both, a fresh pull silently reverts the move and inbound links/SEO
break. `scripts/db/diff-prod-vs-dev.sh` section 7 audits for slug changes that
lack a redirect.

## The idempotency contract

Every `scripts/db/NNN-*.sh` must be safe to run twice. In practice:

- **"Set to X", never "toggle" or "append."** `wp post term set`, `wp post meta
  update` — operations whose second run is a no-op.
- **Resolve by slug, not hardcoded post ID.** A fresh prod pull renumbers posts;
  scripts look targets up by slug (and parent) every run.
- **Get-or-create, never blind create.** Page-creating scripts (026, 028, 029)
  find an existing page first; re-runs must not duplicate.
- **Never demote.** 030 publishes the safety page if draft, no-ops if published,
  and never un-publishes.
- **Content surgery = literal replace of an exact known block** (025, 027):
  once replaced, the pattern no longer matches, so re-runs are no-ops. These
  scripts default to `DRY_RUN=1`; set `DRY_RUN=0` to write.
- **Use the inherited `wp()` function**, not hardcoded
  `docker exec … --allow-root` — the runner injects the right target. (Known
  quirk: the exported `wp()` can return empty stdout inside command substitution
  in a child bash; 024 and 025 document the workaround.)
- **One logical change per script**, with a header that says what it does, why
  it exists, and why it's safe.

## The apply flow

```bash
npm run db:apply              # → scripts/db/apply
```

`scripts/db/apply`:

1. Resolves the target: `WP_CONTAINER` (default `devkinsta_fpm`) + `WP_PATH`
   (default `/www/kinsta/public/newtech`). Empty `WP_CONTAINER` runs `wp`
   directly on the host — so the same scripts can target a non-Docker/prod
   install with zero rewrites.
2. Exports a `wp()` wrapper so migration scripts stay environment-agnostic.
3. Sanity-checks `wp core is-installed` before touching anything.
4. Runs every `scripts/db/[0-9]*.sh` in filename order; a failing script stops
   the run (`set -e`).
5. Reminds you that redirects (`db/redirects.json`) and ACF/CPT-UI config
   (`db/acf-cptui/`) are separate channels that need their own re-import.

## Launch-day reconcile (the plot twist)

The launch mechanic decided for the NTM launch (2026-06) **inverts the original
assumption**: the local dev DB is the source of truth, pushed up to Kinsta
staging then prod — *not* a fresh prod pull that wipes local state. So at launch,
dev wins by default, and the question flips from "what did the pull wipe?" to
"what did prod gain since dev branched that a push would overwrite?"

`scripts/db/diff-prod-vs-dev.sh` is the **read-only** launch-reconcile helper
that answers it. Pull fresh prod into DevKinsta as a second DB, then run it. It
writes nothing (pure SELECTs) and reports seven sections:

1. Content counts by post type, prod vs dev.
2. **The drift check** — prod content modified after the branch date (these rows
   are exactly what a dev push would overwrite; empty = content-safe push).
3. WooCommerce orders (wp_posts + HPOS) — if prod has real orders, do NOT
   overwrite those tables from dev.
4. Users registered on prod after the branch date.
5. `wp_options` hygiene — dev-only junk (`siteurl`/`home`, localhost/devkinsta
   values, `blog_public`) that must not ship.
6. Go-live toggles — Clarity project id, `blog_public=1`, CookieYes presence.
7. Slug-change heuristic — dev pages whose slug differs from prod for the same
   ID, each of which must have a redirect in `db/redirects.json`.

The capture discipline stays valuable either way: if launch pushes dev up, the
scripts document *why* dev data looks the way it does and can rebuild it from a
future prod pull; if anything is ever re-pulled, `npm run db:apply` replays it.

## Open questions (deferred, deliberately)

- **Do the scripts run against production at release, or is prod fixed
  separately?** Not yet decided. Scripts are written environment-agnostic
  (`WP_CONTAINER`/`WP_PATH`) so the decision costs no rewrite later.
- **Redirects re-import script** — not yet authored (export-only for now).
- **ACF/CPT-UI export verb** — channel confirmed necessary, exact CLI TODO.
- **010's `BUTTON_PATTERN`** — cannot be finalized until the next fresh pull
  shows the real prod excerpt markup (see the script header).

## Related

- `scripts/db/README.md` — the hands-on how-to for this strategy.
- `docs/superpowers/specs/data-normalization-backlog.md` — the queued data
  fixes this pipeline exists to carry.
- `docs/feedback/2026-06-17-stakeholder-review.md` — source of several captured
  fixes (027, 028, 029/030) and the open data items.
