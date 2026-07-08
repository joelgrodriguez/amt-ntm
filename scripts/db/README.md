# scripts/db — capturing DB changes so they survive a fresh prod pull

The theme is in git. The database is not. Pulling a fresh production DB locally
wipes every DB-side change. **Anything that must persist has to be a replayable
file in the repo.** This directory (plus `db/`) is where those files live.

Full rationale: `docs/superpowers/specs/db-persistence-strategy.md`.
Queued fixes: `docs/superpowers/specs/data-normalization-backlog.md`.

## After pulling a fresh prod DB

```bash
npm run db:apply              # replays scripts/db/NNN-*.sh against DevKinsta
```

Then re-import the other two channels (see below): redirects and ACF/CPT-UI.

## The four capture channels — where does my change go?

| You changed… | Capture it as… |
|---|---|
| Product category / tag / meta / flag | a new `scripts/db/NNN-*.sh` (idempotent) |
| A slug | `scripts/db/NNN-*.sh` **and** an old→new redirect in `db/redirects.json` |
| A redirect | export to `db/redirects.json` (Redirection plugin) |
| An ACF field group / CPT-UI post type or taxonomy | export to `db/acf-cptui/` (see below) |
| A `content_department` (Service Hub) assignment | re-export `db/imports/content-departments.csv` (replayed by `036-import-content-departments.sh`) |
| Reading/inspecting only | nothing — no capture needed |

### 1. Catalog data → numbered scripts

Copy `000-template.sh.example` to `NNN-short-description.sh` (next free number),
make it idempotent, call `wp ...` (the runner injects the right target). Each
script is one logical change and should reference the backlog item it resolves.

### Worked example — moving `profiles` out of `/learning-center/`

Say we move profiles from `/learning-center/<slug>/` to `/profiles/<slug>/`.
The template/query work is theme code (git). The URL change is **not** — it's
two DB changes that must be captured here, or they're wiped on the next pull:

1. **The permalink change** → a `scripts/db/NNN-move-profiles-permalinks.sh`
   that idempotently sets the `profile` post type's rewrite base / each post's
   slug to the new path.
2. **The redirect** → an old→new 301 (`/learning-center/<slug>/` →
   `/profiles/<slug>/`) added to `db/redirects.json` so inbound links and SEO
   don't break.

Neither step lives in the theme. Without both files, pulling fresh prod silently
reverts the move. This is the Channel-3 slug rule in practice: **a URL change is
never just a theme change.**

### 2. Redirects → `db/redirects.json`

The site uses the **Redirection** plugin (redirects are DB rows in
`wp_redirection_items`, not `.htaccess`). **The plugin exposes no WP-CLI
export** — verified — so we read/write the rows directly with `wp db query`.
There are ~351 enabled redirects, so this file is the source of truth; never
recreate redirects by hand.

```bash
# export all enabled redirects to the repo (run from theme root)
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech db query \
  "SELECT JSON_ARRAYAGG(JSON_OBJECT('source', match_url, 'target', action_data, \
   'code', action_code, 'group', group_id)) \
   FROM wp_redirection_items WHERE status='enabled';" \
  --skip-column-names --allow-root | python3 -m json.tool > db/redirects.json
```

Re-import after a fresh pull is handled by `037-import-redirects.sh` (runs in
the normal `db:apply` pass): it reads `db/redirects.json` and creates only the
entries whose source URL is missing, via the plugin's `Red_Item` API — the
plugin's own bulk import does not dedupe, so never use it against a DB that
already has most of the rows.

### 3. ACF field groups + CPT-UI post types/taxonomies → `db/acf-cptui/`

**These are DB-stored on this site, not code-registered** (verified: post types
`profile`, `cutlist`, `literature`, `pricesheet`, `manual`, `footprint`,
`video`, etc. are defined via ACF/CPT-UI in the database). So edits to a field
group or a CPT definition are lost on a fresh pull unless exported.

```bash
# ACF field groups (one JSON per group; SCF/ACF can also auto-sync to a dir)
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech \
  acf export --all --allow-root > db/acf-cptui/acf-field-groups.json

# CPT-UI definitions live as posts (acf-post-type / acf-taxonomy or
# cptui_* options). Export the relevant config; confirm the exact mechanism
# (ACF-native post types vs CPT-UI plugin) before trusting one path.
```

> The ACF/CPT-UI export commands above are a starting point — verify the exact
> CLI the installed plugin versions expose and lock them in. This channel is
> confirmed *necessary* but its precise export verb is still TODO.

## Open question (deferred)

Whether these same scripts/exports run against **production** at release, or prod
is fixed separately, is not yet decided. Scripts are written
environment-agnostic (`WP_CONTAINER`/`WP_PATH`) so the decision costs no rewrite
later. See the strategy doc.
