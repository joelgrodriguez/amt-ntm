# Data Normalization Backlog — queued DB-side fixes

**Status:** Reconstructed on **2026-07-01** from repo evidence. This file was
referenced by `CLAUDE.md`, `scripts/db/README.md`, and script headers before it
existed on disk. Everything below is mined from the `scripts/db/0*.sh` headers,
`scripts/db/README.md`, git history, and
`docs/feedback/2026-06-17-stakeholder-review.md` — **no items were invented**.

**Numbering caveat:** the original backlog numbering did not survive. The only
number pinned by evidence is **#8** — `010-strip-hardcoded-buttons-from-machine-
excerpts.sh` declares `Resolves: data-normalization-backlog.md #8`. The template
(`000-template.sh.example`) shows "recategorize a mis-filed product (backlog
#3)" as its worked example, which *suggests* a real former item but only
survives as an example — treat it as unconfirmed. New items below are unnumbered
on purpose; reference them by their heading slug.

How this doc is used: every new `scripts/db/NNN-*.sh` should reference the
backlog item it resolves (per `000-template.sh.example`). When an item lands as
a script, move it to the Completed table. Strategy and mechanics live in the
sibling doc, `db-persistence-strategy.md`.

---

## Known open items (data-side)

### Service content tagging by machine

Service content must be tagged properly **by machine** (post_tags) so
per-machine service pages and the Service Hub machine filter fill correctly.
Open action item in the 2026-06-17 stakeholder review (carries over from
2026-06-05). Content/data work; when tags are edited, capture as a
`scripts/db/NNN-*.sh`. Related: the Machine filter is currently a curated
`tag_slug => label` map in `app/inc/service-hub.php` because stray auto-created
tags made slug-inference unreliable (see "No-action decisions" below).

### Quote-checklist page — body copy + `-thank-you` slug

`027-quote-checklist-fix-stray-heading.sh` fixed only the stray heading. Still
open, per that script's SCOPE NOTE and the review doc: the page **body** is
post-download "thank you" boilerplate, and the slug itself ends in
`-thank-you` (`ntm-machine-quote-checklist-thank-you`). Both are content/IA
questions for Joel. A slug rename is a **two-step capture**: the slug script
*and* an old→new entry in `db/redirects.json`.

### 010 `BUTTON_PATTERN` — verify against real prod markup

`010-strip-hardcoded-buttons-from-machine-excerpts.sh` carries a
`⚠️ PATTERN_TODO`: the local DB already had the excerpt buttons stripped when
the script was authored, so the exact prod button markup could not be observed.
On the next fresh prod pull (buttons present), dump a machine excerpt, copy the
real markup into the script, and lock `BUTTON_PATTERN`.

### Redirects re-import script

`db/redirects.json` (~352 enabled redirects) is exported, but the
`scripts/db/NNN-*.sh` migration that re-imports it after a fresh pull has not
been authored — standing decision is "author when first needed"
(`scripts/db/README.md`). Until then a fresh pull needs a manual re-import.

### ACF/CPT-UI export verb

The `db/acf-cptui/` channel is confirmed **necessary** (post types `profile`,
`cutlist`, `literature`, `pricesheet`, `manual`, `footprint`, `video`, etc. are
DB-defined via ACF/CPT-UI), but the exact export CLI for the installed plugin
versions is still TODO per `scripts/db/README.md`. Verify and lock it in.

### Owner-support / per-machine content migration into the Service Hub

Post-launch roadmap item (`30d`, 2026-06-17 review): move the current
owner-support knowledge base and per-machine top-question content into the new
Service Hub. Large manual content/data job; anything created or re-tagged in
the DB must be captured (the knowledgebase seed pattern in `024` is the
precedent: committed fixtures + idempotent upsert script).

### Go-live DB toggles (launch checklist, not scripts)

DB-side flips that `scripts/db/diff-prod-vs-dev.sh` sections 5–6 audit at
launch: `blog_public=1` (dev is noindex today), GDPR/cookie consent (CookieYes)
enabled on the production flip, Microsoft Clarity project id set,
`siteurl`/`home` search-replaced. Tracked in the 2026-06-17 review; listed here
because they are data-side and easy to forget.

---

## Completed — captured as scripts 010–030

Each landed fix is replayable via `npm run db:apply` (details in each header).

| Script | What it fixed |
|---|---|
| `010-strip-hardcoded-buttons-from-machine-excerpts.sh` | Hardcoded CTA buttons in machine product excerpts duplicating the theme's own CTAs (**backlog #8**; pattern verification still open, above) |
| `020-roof-panel-vs-gutter-seo-meta.sh` | `/roof-panel-vs-gutter/` shipped with placeholder `<title>`/meta description (Yoast post meta) |
| `021-start-here-seo-meta.sh` | `/start-here/` shipped with no Yoast title/description |
| `022-finance-center-template-and-video.sh` | Point `/machines/leasing-financing/` at `page-finance-center.php` + set the ACF `hero_video` |
| `023-regenerate-product-card-thumbnails.sh` | Regenerate the `product-card` size after the hard-crop → bounding-box change (existing attachments kept pre-cropped files) |
| `024-seed-knowledgebase.sh` (+ `.php`) | Seed/refresh the `knowledgebase` CPT troubleshooting articles from committed fixtures (upsert on `_kb_source_url`) |
| `025-top-five-questions-headings.sh` | Convert bold-paragraph "questions" to real H2s so the JS TOC populates; fix 1,2,3,3,5 numbering |
| `026-create-service-search-page.sh` | Create `/service-hub/search/` and assign `template-service-search.php` |
| `027-quote-checklist-fix-stray-heading.sh` | Replace the stray "Thank you for submitting the form." heading on the quote-checklist page (body/slug still open, above) |
| `028-trailer-landing-page.sh` | Create `/machines/trailer/` + assign `page-trailer.php`; re-parents the stray page from the old `/machines/upgrades/trailer/` route (Woo permalink-base collision) |
| `029-safety-landing-page-draft.sh` | Create `/safety/` in **Draft** behind the counsel-review gate (issue #41) |
| `030-publish-safety-page.sh` | Publish `/safety/` — gate consciously waived (issue #43); never demotes |

Also captured outside numbered scripts: `db/redirects.json` re-exported at 352
entries (captures the trailer route move, #48).

## No-action decisions (looked like data fixes, deliberately aren't)

- **Stray machine post_tags** (term IDs 1781–1784, e.g. `mach-ii-combo-gutter`,
  `bg7-box-gutter`, auto-created from machine slugs): **not deleted** — each
  still tags 5–7 published non-service posts, so removal would orphan content.
  The Service Hub machine filter ignores them via the curated map in
  `app/inc/service-hub.php` (theme code, survives a pull). See the addendum in
  `2026-06-16-service-search-results-page-design.md`.
