# Plan 009: Remove the broken placeholder "Sitewide Custom Schema" from the DB (captured + replayable)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- scripts/db/`
> Also confirm script number 041 is still free: `ls scripts/db/ | grep '^041'` → empty.

## Status

- **Priority**: P1 (launch-relevant: production is serving this broken schema today)
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: seo / db-capture
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

Verified live (2026-07-10): every page of the site emits a JSON-LD block from **WP Schema Pro** that is (a) syntactically INVALID — it contains JavaScript-style `/* ... */` comments inside JSON, so no crawler can parse it — and (b) full of literal template placeholders: `"logo": "https://newtech.local/path-to-logo.png"`, `"foundingDate": "YYYY"`, and `sameAs` URLs like `https://www.linkedin.com/company/NEWTECH-UPDATE`. The source is Schema Pro custom-markup entry **post 18575, "Sitewide Custom Schema"** (post type `aiosrs-schema`, meta key `bsf-aiosrs-custom-markup`). The local DB mirrors production — **prod is serving this broken block right now**. Yoast (active) already emits the proper Organization/WebSite graph, so this block is pure noise even if it were fixed. Repo hard rule: DB-side changes must be captured as replayable scripts in `scripts/db/` in the same task — a fresh prod pull at cutover would otherwise resurrect the junk.

## Current state

- Schema Pro entries in the DB (`wp post list --post_type=aiosrs-schema`):

```
ID     post_title                  post_status
18578  Home Page Custom Schema     publish
18575  Sitewide Custom Schema      publish
17947  Video Object                publish
17880  FAQ                         publish
4656   Product                     publish
4655   Article                     publish
```

- Post 18575 meta keys: `bsf-aiosrs-schema-type`, `bsf-aiosrs-custom-markup`, `bsf-aiosrs-schema-location`. The custom markup contains the placeholder Organization block (verified — includes the string `NEWTECH-UPDATE`).
- Post 18578 ("Home Page Custom Schema") has not been inspected — Step 1 inspects it.
- The other four entries (Video Object, FAQ, Product, Article) are Schema Pro's normal typed schemas — NOT in scope; do not touch.
- Script conventions — read `scripts/db/000-template.sh.example` and `scripts/db/040-profile-featured-images.sh` in full before writing anything. Key rules: idempotent ("set to X", never toggle); use the `wp` function inherited from `scripts/db/apply` (never hardcode `docker exec ... --allow-root`); one logical change per script; `DRY_RUN=1` by default with `DRY_RUN=0` to write; header comment explains WHY.
- WP-CLI must go through the php8.3 pin, which `scripts/db/apply` already handles. For your own ad-hoc inspection commands use: `docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech <cmd> --allow-root`.
- Local site: `https://newtech.local` (self-signed TLS — use `curl -sk`).

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| Inspect a schema post | `docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech post meta get <ID> bsf-aiosrs-custom-markup --allow-root` | prints the markup |
| Bash lint the new script | `bash -n scripts/db/041-*.sh` | exit 0 |
| Count broken blocks on a page | `curl -sk https://newtech.local/ \| grep -c 'NEWTECH-UPDATE'` | `1` before the fix, `0` after |

## Scope

**In scope**:
- Create `scripts/db/041-unpublish-placeholder-custom-schema.sh`
- Run it against the local DB (`DRY_RUN=0` via the documented runner path)
- If inspection shows post 18578 is ALSO placeholder junk, include it in the same script (same logical change: "remove placeholder custom-markup entries")

**Out of scope** (do NOT touch):
- Schema Pro posts 17947, 17880, 4656, 4655 (real typed schemas).
- The Schema Pro plugin itself (do not deactivate/uninstall — plugin strategy is an owner decision; note it for the reviewer instead).
- Theme code, `db/redirects.json`, any other DB rows.

## Git workflow

- Branch: `advisor/009-remove-placeholder-schema`
- One commit, subject like: `Unpublish placeholder Schema Pro custom markup (db 041)`
- Do NOT push.

## Steps

### Step 1: Inspect both custom-markup entries

```bash
docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech post meta get 18575 bsf-aiosrs-custom-markup --allow-root | head -30
docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech post meta get 18578 bsf-aiosrs-custom-markup --allow-root | head -30
```

Decision rule: an entry is "placeholder junk" if its markup contains ANY of: `NEWTECH-UPDATE`, `path-to-logo`, `"YYYY"`, or `/*`. Record which of the two qualify. (18575 is known-junk; 18578 is expected to be similar but must be verified.)

**Verify**: you can quote the qualifying strings for each entry in your report.

### Step 2: Write `scripts/db/041-unpublish-placeholder-custom-schema.sh`

Model on `040-profile-featured-images.sh` (header style, `set -uo pipefail`, DRY_RUN pattern) and the template's rules. The script must:

1. Resolve the target post IDs **by title + post_type, not by hardcoded ID** (IDs can shift across environments): `wp post list --post_type=aiosrs-schema --title="Sitewide Custom Schema" --field=ID` (and "Home Page Custom Schema" if Step 1 confirmed it).
2. Guard: only act when the post's `bsf-aiosrs-custom-markup` meta contains `NEWTECH-UPDATE` or `path-to-logo` (idempotency + safety: never unpublish a repaired entry someone later fixed).
3. Action: set the post to `draft` (`wp post update <ID> --post_status=draft`) — reversible, and Schema Pro stops emitting drafts. Do NOT delete.
4. Echo one line per action taken and one line when nothing matched (idempotent re-run output).
5. Header comment: WHY (broken placeholder JSON-LD emitted site-wide; Yoast owns the Organization graph; prod currently serving it), WHAT (draft the placeholder custom-markup entries), and the verification curl.

**Verify**: `bash -n scripts/db/041-unpublish-placeholder-custom-schema.sh` → exit 0.

### Step 3: Dry-run, then apply locally

Run the script the way `apply` does (read `scripts/db/apply` to see how scripts receive the `wp` function; if it sources scripts individually you can run `DRY_RUN=1 scripts/db/apply` — read its interface first; `040`'s header references the runner quirk precedent in `025`).

First dry-run (expect it to name the target posts without writing), then apply with `DRY_RUN=0`.

**Verify (dry)**: output names post 18575 (and 18578 if qualified), no DB change (`wp post get 18575 --field=post_status` still `publish`).
**Verify (apply)**: `wp post get 18575 --field=post_status` → `draft` (same for 18578 if in scope).

### Step 4: Verify the emitted head is clean

```bash
curl -sk https://newtech.local/ | grep -c 'NEWTECH-UPDATE'          # → 0
curl -sk https://newtech.local/machines/roof-wall-panel-machines/ssq3-multipro/ | grep -c 'NEWTECH-UPDATE'  # → 0
```

Also count ld+json blocks before/after on the flagship page and report the delta (`grep -o '<script type="application/ld+json"' | wc -l`). An empty `[]` ld+json block may remain (separate Schema Pro output) — report whether it's still present; do not chase it.

**Verify**: both greps → `0`.

### Step 5: Re-run the script (idempotency proof)

Run the apply again with `DRY_RUN=0`.

**Verify**: exits 0, reports nothing-to-do (targets already draft / guard string absent), post statuses unchanged.

## Test plan

The verification IS the test: dry-run names targets, apply drafts them, curl shows the placeholder gone site-wide, re-run is a no-op. All four results belong in your report.

## Done criteria

- [ ] `scripts/db/041-unpublish-placeholder-custom-schema.sh` exists, `bash -n` clean, follows 040's conventions (header, DRY_RUN, `wp` wrapper, idempotent guard)
- [ ] Post 18575 (and 18578 if junk) is `draft` locally
- [ ] `curl -sk https://newtech.local/ | grep -c 'NEWTECH-UPDATE'` → `0`
- [ ] Second apply run is a clean no-op
- [ ] `git status --porcelain` shows only the new script

## STOP conditions

- Post 18575 doesn't exist or its markup does NOT contain the placeholder strings (environment drifted — report, don't guess).
- `scripts/db/apply`'s interface doesn't match this plan's assumption about running a single script (report how it actually works instead of improvising a hardcoded docker call inside the numbered script).
- Drafting 18575 visibly breaks any page (curl a machine page + front page after apply — if HTML output errors appear, revert by republishing and report).

## Maintenance notes

- Owner decision to surface (reviewer: put this in front of Joel): Schema Pro overlaps Yoast for Organization/WebSite/Breadcrumb duties and also emitted an empty `[]` block; evaluate whether the plugin earns its keep (its Product/FAQ/Video typed schemas vs the theme's own machine-schema emitter). Repairing-and-republishing the custom markup with real data is the alternative to drafting; drafting was chosen because Yoast already owns the Organization graph.
- At cutover, `npm run db:apply` replays this script against the fresh prod DB — that is the mechanism that fixes PRODUCTION. Verify with the same curl greps against the prod domain post-cutover.
