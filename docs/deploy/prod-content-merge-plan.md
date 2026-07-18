# Production Content Merge & Cutover Plan

**Date:** 2026-07-08
**Sites:** `newtech` (local dev, DB `newtech`) · `newtech-1` (fresh prod copy, DB `newtech_1`)
**Goal:** Launch the new theme on production without losing any content added on prod
since our baseline pull, and without losing any dev-created content or configuration.

---

## 1. Direction decision

**Prod's database is the source of truth. We rebase `newtech`'s DB onto the fresh
prod copy, then replay everything we own from git.** We do NOT copy the local DB to
prod, and we do NOT move the theme into `newtech-1`.

Why:

- Prod kept moving (Spanish brochures, new videos/posts, editorial edits, redirects)
  while we built. Its DB is live business data; ours is a stale May snapshot plus
  our changes.
- The repo was designed for exactly this: theme in git, DB-side changes captured as
  idempotent `scripts/db/` replays + `db/redirects.json`. Rebasing onto a fresh prod
  DB is the documented release path (`docs/superpowers/specs/db-persistence-strategy.md`).
- **Naive merging is impossible anyway: post IDs collide.** Both sides allocated IDs
  in the 20654–20745 range (e.g. 20704 = local SSQ3 photo vs prod NTM logo;
  20711 = local knowledgebase article vs prod financing landing page). Any
  row-level copy in either direction corrupts one side.

`newtech` stays the deploy vehicle (it's the git checkout and the DevKinsta site
we'll push from). `newtech-1` is a read-only donor + rehearsal reference.

## 2. What the diff found

Baseline: local DB was pulled from prod ≈ **2026-05-21** (local-created content
starts 05-22; prod-side divergence starts 06-01).

### 2.1 New on prod since baseline — KEPT automatically by rebasing

| What | Detail |
|---|---|
| Spanish brochure program | ~30 attachments (brochure PDFs + previews, June 3–16) + literature retitles "Flyer" → "Brochure" across ~20 literature posts + new literature 20858 (SSQ3 Spanish) |
| Blog | Post 20814 "True Cost of a Mach II" (publish), 20854 Cidan comparison (draft), full rewrites of posts 24 and 1116 |
| Videos | 20879 "5 Pillars", 20908 "Easiest Way to Build Your NTM Machine" (+thumbnails) |
| Landing page | 20760 "Save Up To 30% on Panel Costs" (Gutenberg blocks, old-theme template — see QA #6) |
| Editorial | 5 videos deliberately set **private** June 25–26 (1672, 1675, 1683, 1684, 18678 — old configurator video superseded by 20908); product 1361 renamed ROSG3 → RSG3; user cleanup (8 old accounts deleted) |
| Redirects | 4 new `/search-results/?_sf_s=…` → 0%-financing-page redirects |

### 2.2 Created locally — replay coverage

| Local content | Covered by repo today? |
|---|---|
| Trailer, safety, quiz, service-search pages, SEO meta, template meta, thumbnails | ✅ `scripts/db/010–033` via `npm run db:apply` |
| 19 knowledgebase articles + `service-repair` term + machine post_tags | ✅ `024-seed-knowledgebase` |
| 6 redirect entries (manuals, footprints, accessories, trailer) | ✅ `db/redirects.json` (verified all 6 present) |
| About page DB content edit | ✅ moot — `page-about.php` never renders `post_content` |
| **5 template-shell pages** (see 2.3) | ❌ gap → new script **034** |
| **16 attachments** (SSQ3 photo shoot, hero placeholder, Abel still) | ❌ gap → new script **035** |
| **654 `content_department` assignments** (sales 379 / service-repair 260 / training 15) | ❌ gap → export CSV + wire `wp ntm service-hub import-csv` (**036**) |
| 5 videos published locally that prod later privated | ⚠️ conflict — see Decision Gate B |

### 2.3 Gap detail: the 5 uncaptured pages (all empty template shells)

| ID (old) | Slug | Parent | Template |
|---|---|---|---|
| 20654 | `service-hub` | 0 | `templates/template-service-hub.php` |
| 20662 | `machii` | 207 (/machines/) | `page-machii.php` |
| 20671 | `choose-your-machine` | 0 | `page-choose-your-machine.php` |
| 20676 | `add-a-machine` | 0 | `page-add-a-machine.php` |
| 20678 | `request-parts` | 0 | `page-request-parts.php` |

Note: `026-create-service-search-page.sh` **requires** `service-hub` to exist and
currently skips silently on a fresh pull — 034 must run before 026's re-run, or
`apply` ordering must place 034 earlier (number it 025.5-style or renumber; simplest:
make it `014-create-shell-pages.sh` so it precedes everything that assumes them).

### 2.4 Conflict resolutions (same row edited both sides)

| Row | Resolution |
|---|---|
| Posts 24, 1116, product 1361, literature retitles | **Prod wins** (their edits are newer and editorial) — automatic via rebase |
| Page 209 About | Local edit is later but the template ignores content — no action |
| 5 privated videos | **Prod wins** except Decision Gate B |
| Page 3836 "Articles" + nav item deleted locally | Stays deleted? No — rebase resurrects them. Harmless: new theme uses hardcoded PHP nav; the Articles page is unlinked. Leave, or re-trash in QA. |

### 2.5 Uploads (files on disk)

- **Prod-only files (~1.7k):** new June uploads + thumbnail sizes prod generated.
  → union-copy into local (`rsync --ignore-existing`).
- **Local-only files (~4.9k):** thumbnail sizes our theme registers (regenerated by
  scripts 023/031 and media imports). → keep; nothing to do.
- Nothing is deleted on either side. DevKinsta push then carries the union to prod.

### 2.6 Plugins & options

Prod runs 29 active plugins; local runs the 17 the new theme was built and tested
against. `template`/`stylesheet` = `amt-ntm/app` locally. The rebase resets all of
this to prod values → must re-apply (Phase R step 4). The 12 plugins we deactivated:

| Plugin | Recommendation |
|---|---|
| search-filter-pro, wp-pagenavi, woocommerce-products-by-tags, ninja-forms | **Stay off** — replaced by theme search/pagination/catalog/HubSpot forms (nf_sub count is 0; no submission data at risk) |
| sucuri-scanner, microsoft-clarity, pixelyoursite, google features via leadin | **Decision Gate A** — security/analytics; marketing will notice if pixels stop. Default: re-enable sucuri + clarity + pixelyoursite at launch |
| hurrytimer | **Decision Gate A** — prod financing landing pages likely embed its countdown shortcodes; if off they render as raw shortcode text. Default: leave ON |
| the-events-calendar | **Decision Gate A** — 9 published events; plugin off = /events/ URLs 404. Default: leave ON, restyle later |
| admin-site-enhancements, featured-images-for-rss-feeds | Low stakes; re-enable |

## 3. Decision gates (need Joel's call, all have safe defaults)

- **A. Plugin set at launch** — table above. Default: theme-replaced plugins off,
  ops/marketing plugins back on.
- **B. Classic Metals video (1675)** — prod set it private June 26, but
  `start-here/proof.php` and `the-case.php` link
  `/learning-center/classic-metals-inc-how-to-build-a-metals-business/`.
  Options: (1) confirm with content owner why it was privated (consent/takedown?),
  (2) re-publish it, (3) swap the Start Here links to another testimonial.
  Default: ask first; if unreachable, swap links (safest legally).
- **C. Content freeze** — prod editors must pause changes from final re-pull to
  go-live. Needs a scheduled window.

## 4. Runbook

### Phase C — Capture (repo work on a worktree branch, BEFORE any DB reset)

All exports read the **current local DB** — do this first or the data is gone.

1. `014-create-shell-pages.sh` (or renumber): idempotently create the 5 pages from
   §2.3 by slug+parent, set `_wp_page_template`, publish. Model on 026.
2. `035-register-theme-media.sh`: register the 16 local-only attachments from their
   existing `uploads/2026/…` paths via `wp media import --skip-copy` (guard by
   filename lookup so it's idempotent). These back `quiz.php`
   (`ssq3-operator-at-controls.jpg`), the hero placeholder, flagship imagery —
   without rows, `responsive_image()` srcset lookups fail.
3. Export departments now:
   `wp ntm ... eval` → CSV `db/imports/content-departments.csv` (`Post ID,Department`
   from current `content_department` assignments). Add `036-import-content-departments.sh`
   that runs `wp ntm service-hub import-csv db/imports/content-departments.csv`.
   (Import skips missing IDs, so KB rows reseeded by 024 with new IDs are fine —
   024 assigns their department itself.)
4. Wire all three into `scripts/db/apply`; verify `npm run db:apply` order
   (shell pages before 026).
5. Commit, land to dev via normal flow.

### Phase R — Rebase local DB onto prod copy (rehearsal now, repeat at cutover)

> **Gate (before cutover):** In MyKinsta, confirm the LIVE and STAGING
> environments run **PHP ≥ 8.1**. The theme uses PHP 8.0+ syntax
> (`match()` in `app/inc/woo/catalog.php`) and white-screens on PHP 7.4 —
> the local wp-cli php8.3 pin (step 3 below) only protects CLI, not the
> web runtime.

```bash
# 1. Backups (both DBs) to ~/Development/kinsta/_local-only/amt-ntm/
docker exec devkinsta_db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" newtech'   > .../newtech-pre-rebase.sql
docker exec devkinsta_db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" newtech_1' > .../newtech_1-donor.sql

# 2. Replace local DB with donor
docker exec devkinsta_db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "DROP DATABASE newtech; CREATE DATABASE newtech"'
docker exec devkinsta_db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" newtech < donor dump'

# 3. Domain fix (use php8.3 for wp-cli — default CLI php is 7.4 and fatals on the theme)
wp search-replace 'newtech-1.local' 'newtech.local' --all-tables --precise

# 4. Theme + plugin set
wp theme activate amt-ntm/app
wp plugin deactivate search-filter-pro ninja-forms wp-pagenavi woocommerce-products-by-tags <+ per Gate A>

# 5. Merge prod-only upload files into local (keeps all local files)
rsync -a --ignore-existing ../newtech-1/wp-content/uploads/ wp-content/uploads/

# 6. Replay everything from git
npm run db:apply                        # now includes 014/035/036
# import db/redirects.json via Redirection (merge; prod's 4 new redirects already in base)

# 7. Rebuild derived state
wp rewrite flush && wp cache flush
wp relevanssi index                     # search depends on it
```

### Phase V — Verify (against `newtech.local`)

1. `npm run build` clean; `app/dist/.vite/manifest.json` present; no `app/.vite-dev-server`.
2. Front page, mega menu, mobile menu, machine pages (SSQ3 + SSR via default template), configurator flow (`choose-your-machine` / `add-a-machine` pages exist), quiz page, trailer, safety, service hub + service search, Start Here.
3. Learning Center: category archives (`/learning-center/category/testimonials/`), department filters (sales/service-repair/training counts ≈ 379/260/15), search.
4. **New prod content renders under new theme:** post 20814, videos 20879/20908, Spanish brochure literature entries, landing page 20760 (old-theme template `page-marketing-landing-simple.php` falls back to `page.php` — check it's presentable; fix template meta if not; check hurrytimer shortcodes).
5. Redirects: sample the 6 local + the 4 new prod `/search-results/` ones; product-category 301s.
6. Start Here testimonial links (Gate B outcome).
7. `scripts/db/diff-prod-vs-dev.sh` for drift; images: quiz section photo, hero slider, flagship band, testimonial portraits (srcset 300x300).
8. Events pages per Gate A choice.

### Phase X — Cutover

1. Announce **content freeze** on prod (Gate C).
2. Re-pull fresh prod DB via DevKinsta (prod moved again since the Jul rehearsal donor).
3. Re-run Phase R + V — should be ~30 min since everything is scripted. Re-run the
   cross-DB diff (queries in git history / this doc) against the new donor to catch
   surprises added after `newtech_1` was taken.
4. DevKinsta push `newtech` (**Files + Database**, not database-only) to
   **Kinsta staging**, then run
   `RELEASE_TARGET_BASE_URL=https://<kinsta-staging-host> scripts/release/required-media-preflight.sh`.
   This proves the MACH II optimized video and motor-panel image exist locally
   and are reachable on staging before `scripts/release/to-master.sh` can push
   `master`. QA there (Yoast active, HTTPS URLs, HubSpot form loads, GA/pixels
   firing).
5. Kinsta staging → live (Kinsta takes an automatic backup; note the restore point).
6. Post-launch: watch Redirection 404 log + GSC coverage for a week; lift freeze.

## 5. Rehearsal results (2026-07-08) — COMPLETE ✅

Phases C, R, and V were executed against the `newtech_1` donor. The local
`newtech` site now runs the new theme on a fresh prod DB with every capture
replayed. Verified: 35-URL smoke battery all-200, front page fully composed
(quiz photo srcset from re-registered media, testimonial slider, flagship
band), LC category archives + department filters (376/257/15), relevanssi
reindexed (3,658 posts), new prod content renders (5 Pillars video, True Cost
post, Save-30% landing page, Spanish brochures), redirect chains loop-free.

The rehearsal caught and fixed five capture/pipeline defects (all landed under
#76, five iterations):

1. `media regenerate` fatals on PDF attachments (ImageMagick policy) aborted
   the whole replay → 023/031 fail-soft.
2. 024/029/030 bypass the wp() wrapper and ran PHP 7.4 → php8.3 pinned.
3. 028/029 default DRY_RUN=1, so a fresh replay silently skipped the trailer
   and safety pages → apply exports DRY_RUN=0; wrapper forwards NTM_* env
   (028 saw NTM_MACHINES_ID=0 and skipped).
4. Eight more shell pages were missing — local creations whose IDs prod reused
   for revisions (invisible to ID diffs) — plus prod's `ntm-accessories` page
   needed slug adoption to `upgrades`, and `manuals`/`footprints` needed
   re-parenting under /machines/ → 014 owns all page shells now.
5. Prod's legacy redirect `/machines/manuals → /learning-center/resource/
   manuals/` + our captured reverse formed an infinite 301 loop → 038 disables
   enabled redirects whose source resolves to published content.

**Gate outcomes:** A — prod's plugin set minus search-filter-pro, ninja-forms,
wp-pagenavi, woocommerce-products-by-tags (25 active; sucuri/clarity/
pixelyoursite/hurrytimer/events-calendar/leadin stay on). B — no action
needed: the theme links the *article* URL, which is published; only the
duplicate video CPT was privated. C — still required at cutover.

**Cutover-day runbook is unchanged (Phase X)**, with timing now known:
re-pull fresh prod DB → file syncs (plugins prod-wins --delete, uploads
--ignore-existing) → DB import + search-replace → theme activate + plugin
deactivations → `npm run db:apply` (~12 min, dominated by 023) → relevanssi
index (~3 min) → rewrite/cache flush → smoke battery → DevKinsta push to
Kinsta staging → QA → promote. Backups from this rehearsal:
`~/Development/kinsta/_local-only/amt-ntm/db-backups/`.

## 6. Risks & mitigations

- **Prod drifts between donor snapshot and cutover** → freeze + mandatory re-pull +
  re-run of the diff at cutover (Phase X.2–3).
- **ID collisions** → never copy rows across DBs; only replay by slug/filename/CSV
  (all new scripts follow that rule).
- **Old-theme `_wp_page_template` values on prod pages** → fall back to `page.php`;
  scripts 021/022/026/028–033 re-point the pages we designed; QA #4 covers new prod
  pages like 20760.
- **Relevanssi index stale after rebase** → explicit reindex step.
- **CLI PHP 7.4 fatals on theme `match` expressions** → always `php8.3` for WP-CLI
  against `newtech` inside the container.
