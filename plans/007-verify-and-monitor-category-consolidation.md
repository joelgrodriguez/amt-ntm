# Plan 007: Verify the category-URL consolidation is complete and durable, and monitor the equity transfer

> **Executor instructions**: This is a verification, capture, and monitoring
> plan — mostly read-only analysis plus one possible `db/redirects.json`
> update. Run every check and record the result. If a check fails in a way
> that needs a redirect or config change, follow the step; if it needs a
> product/content decision, STOP and report. Update the status row in
> `plans/README.md` when the verification pass is complete (monitoring
> continues on a schedule after that).
>
> **Drift check (run first)**: `git log --oneline -5 -- db/redirects.json`
> and confirm the redirects file still exists. The live-site checks below are
> the real source of truth regardless of code drift.

## Status

- **Priority**: P2
- **Effort**: M (mostly monitoring over 4–8 weeks, low code)
- **Risk**: LOW
- **Depends on**: plan 006 should land early in the monitoring window (harden
  the 301 targets before measuring the transfer)
- **Category**: technical SEO / ops
- **Planned at**: commit `bc5ecf2`, 2026-07-21

## Why this matters

The gutter/panel cannibalization is **already fixed at the redirect layer**:
`/product-category/roof-wall-panel-machines/` and
`/product-category/gutter-machines/` both return **301** to their marketing
landing pages on prod (verified 2026-07-21), and `db/redirects.json` carries
13 `product-category` redirects. But "the redirect exists" is not "the
consolidation is complete and durable." Three things are unverified: (1) that
*every* retired product-category 301s correctly (not just the two spot-checked),
(2) that the redirect set actually survives a fresh prod DB pull — the
`scripts/db/apply` runner only prints a *reminder* to import redirects, it does
not import them, so this is a known durability gap, and (3) whether Google is
actually transferring the position-1–4 rankings from the old URLs to the
landing pages, or stalling. This plan closes those three gaps.

## Current state

- Redirects live (verified via `curl -sk -o /dev/null -w '%{http_code} %{redirect_url}'`):
  - `/product-category/roof-wall-panel-machines/` → 301 → `/roof-wall-panel-machines/`
  - `/product-category/gutter-machines/` → 301 → `/seamless-gutter-machines/`
- `db/redirects.json` — Redirection-plugin export, 360 total redirects, 13
  containing `product-category`, dated ~2026-07-02.
- `app/inc/seo.php` — `RETIRED_PRODUCT_CATEGORY_SLUGS`
  (`roof-wall-panel-machines`, `gutter-machines`, `accessories-add-on-equipment`)
  are excluded from Yoast XML sitemaps (`exclude_retired_product_category_terms_from_yoast_sitemaps`).
- `scripts/db/apply:152-153` — prints only a *reminder* that redirects are
  captured in `db/redirects.json` and imported "via the Redirection plugin".
  There is no automated redirect import in the replay path.
- No theme code links to `/product-category/` (verified: the grep over
  `app/**/*.php` returns only breadcrumb/sitemap/RETIRED references, no hrefs).
- GSC access is available via the `AMT_Reporting` MCP tools
  (`gsc_filtered_search`, `gsc_search_summary`) for the `ntm` site — use them
  for the monitoring steps; no credentials needed in this repo.

## Commands / tools you will need

| Purpose | Command / tool | Expected |
|---|---|---|
| Redirect status | `curl -sk -o /dev/null -w '%{http_code} -> %{redirect_url}\n' <url>` | `301 -> <landing>` |
| Enumerate product_cat terms | `wp term list product_cat --fields=slug,count` (via DevKinsta docker exec, see `scripts/db/apply` for the exec pattern) | list of slugs |
| GSC positions | `AMT_Reporting` `gsc_filtered_search` (site `ntm`, `page_contains`) | position rows |

## Scope

**In scope**:
- Read-only verification of live redirects, sitemaps, and internal links.
- At most one update to `db/redirects.json` if a retired category is found
  NOT redirecting (add the missing 301 via the Redirection plugin, re-export).
- A short monitoring note appended to this plan (baseline + follow-up readings).

**Out of scope** (STOP and report instead of doing):
- Changing which URL is canonical, or redirecting the landing pages anywhere.
- Deleting product-category terms or Woo products.
- Any theme code change (H1/copy is plan 006).
- Automating the redirect import into `scripts/db/apply` — that is a real fix
  worth doing, but it is a tooling change to propose to the operator, not to
  improvise here (see Maintenance notes).

## Steps

### Step 1: Confirm every retired product-category 301s correctly

Enumerate the `product_cat` terms and check each retired one's live status:

```bash
for slug in roof-wall-panel-machines gutter-machines accessories-add-on-equipment; do
  curl -sk -o /dev/null -w "%{http_code} $slug -> %{redirect_url}\n" \
    "https://newtechmachinery.com/product-category/$slug/"
done
```

**Expected**: each returns `301` to the correct landing page
(`/roof-wall-panel-machines/`, `/seamless-gutter-machines/`, and the
accessories landing — confirm the intended target for accessories with the
operator if unclear). Any retired slug returning `200` is a gap → Step 2.
Any 301 pointing at the WRONG target → STOP and report.

### Step 2 (only if a gap found): Capture the missing redirect

If a retired category returns 200 (still live), add a 301 to the correct
landing page **via the Redirection plugin** (wp-admin → Tools → Redirection,
or `wp redirection redirect create` if the CLI subcommand is available), then
re-export `db/redirects.json` so it survives a prod pull. Commit only
`db/redirects.json`.

**Verify**: re-run Step 1 → the slug now 301s. `grep -c product-category db/redirects.json`
increased by the number added.

### Step 3: Confirm no live internal link points through a redirect

Internal links should hit the landing pages directly (a link to a 301 wastes a
hop and dilutes signal). Check the rendered nav, footer, and homepage:

```bash
for u in "" "machines/" "roof-wall-panel-machines/"; do
  curl -sk "https://newtechmachinery.com/$u" | grep -o 'href="[^"]*product-category[^"]*"' | sort -u
done
```

**Expected**: no output (no internal links to `/product-category/`). If any
appear, record the source page — fixing them is a small follow-up (likely a
menu or widget in the DB, not theme code).

### Step 4: Baseline the equity transfer (GSC)

Using `AMT_Reporting` `gsc_filtered_search` (site `ntm`, last 28 days), record
the CURRENT position of BOTH the old and new URLs for the head terms, so the
transfer can be tracked. Query `page_contains` for each and note positions:

- Head terms to track: `roof panel machine`, `metal roof panel machine`,
  `standing seam machine`, `metal roofing machine`, `gutter machine`,
  `seamless gutter machine`.
- For each, record: position on `/product-category/...` (old) vs the landing
  page (new), and total clicks/impressions.

Append the readings to the "Monitoring log" section below with the date.

**Success definition**: over 4–8 weeks, impressions/clicks and position shift
from the `/product-category/` URLs to the landing pages, and the landing pages
reach *at least* the old URLs' positions (panel head terms were pos 1–4;
gutter "gutter machine" was pos ~4.5). A landing page that plateaus 5+
positions worse than the old URL after 8 weeks means the 301 target is
underperforming → revisit plan 006 (on-page relevance).

### Step 5: Re-read at 2, 4, and 8 weeks

Repeat Step 4 at each interval, appending to the log. This is the actual
deliverable — the transfer is not instant and a single reading proves nothing.

## Monitoring log

(Executor appends dated rows: `YYYY-MM-DD | term | old-URL pos | landing pos | clicks | impr`.)

- _baseline pending_

## Done criteria

- [ ] All three retired product-categories 301 to the correct landing page (Step 1)
- [ ] Any gap captured in `db/redirects.json` (Step 2) — or "no gaps" recorded
- [ ] No live internal links to `/product-category/` (Step 3) — or sources logged
- [ ] GSC baseline recorded in the Monitoring log (Step 4)
- [ ] Follow-up readings scheduled/added at 2/4/8 weeks (Step 5)
- [ ] `plans/README.md` status row updated to reflect "verification done,
      monitoring ongoing"

## STOP conditions

Stop and report back (do not improvise) if:

- A product-category 301s to the WRONG landing page (a mis-mapped redirect can
  send ranking equity to the wrong page — worse than none).
- A landing page is found redirecting or non-canonical (it must be the final
  200 target).
- After 8 weeks the landing pages have NOT absorbed the old rankings and sit
  materially worse — this is a strategy signal (the redirect bet isn't paying
  off) that the operator must weigh, not an executor fix.

## Maintenance notes

- **Durability gap worth fixing separately**: `scripts/db/apply` only *reminds*
  about redirects; a fresh prod DB pull that skips the manual Redirection import
  silently drops all 360 redirects (including these consolidations). Proposing
  an automated `db/redirects.json` import step in the replay path is a
  high-value follow-up — flag it to the operator; do not build it inside this
  plan.
- Once the transfer completes and the landing pages hold the rankings, the
  product-category URLs can stay 301'd indefinitely (301s are permanent and
  cost nothing). Do not delete the source terms — that would break the 301s.
- This plan's value is the *measurement*: it tells you whether the consolidation
  (plans done before this) actually worked, which no code check can confirm.
