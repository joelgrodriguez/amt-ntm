# Site Search Implementation Plan

**Goal:** Make site search fast, predictable, and genuinely useful across machines, learning content, profiles, manuals, downloads, resources, products, literature, and footprints.

**Hard rule:** No `pricesheet` results. Pricing sheets stay out of public site search.

**Current take:** Relevanssi Premium is already active, so use it as the ranking and indexing engine. Do not hand-roll full-text SQL. That would be slower, buggier, and harder to tune.

**Scope:** Search logic, indexing, redirects, relevance, observability, and QA. No UI redesign in this plan.

---

## Searchable Content Contract

Searchable post types:

- `post`
- `page`
- `video`
- `literature`
- `resource`
- `download`
- `manual`
- `profile`
- `product`
- `footprint`

Explicitly excluded:

- `pricesheet`
- `cutlist`
- `attachment` as direct results
- WordPress/system post types

PDF/file search should point users to the owning content item, not raw attachment pages. Raw attachment pages are usually SEO sludge.

---

## Query Contract

Canonical query params:

- `s`: keyword search
- `post_type`: one or more allowed post types
- `category`: category slug
- `tag`: post tag slug
- `machine`: machine taxonomy slug
- `department`: department slug, backed by the `content_department` taxonomy
- `product_cat`: WooCommerce product category slug
- `product_tag`: WooCommerce product tag slug

Supported compatibility params:

- `type`
- `lc_type`
- `lc_category`
- `lc_machine`
- `_sft_category`
- `_sft_post_tag`

Compatibility params are accepted so old links do not silently rot. They should not become the preferred API.

---

## Implementation Phases

### Phase 1: Stabilize Base Search

Status: partly done.

- [x] Centralize allowed post types in `app/inc/search.php`.
- [x] Keep `pricesheet` out.
- [x] Include indexed useful content types: `literature`, `footprint`.
- [x] Support native and legacy taxonomy filters.
- [x] Prevent empty `?s=` from dumping hundreds of results.
- [x] Keep Relevanssi filters enabled with `suppress_filters => false`.
- [x] Add focused WP-CLI smoke checks for the query contract in `scripts/search-smoke.php`.
- [x] Block direct `attachment` results from normal search.

### Phase 2: Fix Relevanssi Index Hygiene

Status: partly done.

- [ ] Remove stale indexed post types from Relevanssi settings, especially `bogus`.
- [x] Add theme-level Relevanssi guardrails so `pricesheet` and `cutlist` are excluded at index/result time.
- [x] Block direct `attachment` search results while preserving the option to use PDF text on parent content.
- [ ] Rebuild the Relevanssi index so previously indexed excluded content is purged.
- [ ] Decide whether attachments should be indexed for PDF content discovery.
- [ ] If attachments stay indexed, configure results to favor the parent post, not the attachment itself.

Recommended index post types:

- `post`
- `page`
- `product`
- `video`
- `profile`
- `literature`
- `resource`
- `download`
- `manual`
- `footprint`

### Phase 3: Tune Relevance

Status: not started.

- [ ] Keep title weighting high.
- [ ] Give machine taxonomy matches a strong boost.
- [ ] Give exact title matches a strong boost.
- [ ] Boost manuals for searches containing `manual`, `guide`, `pdf`, `operation`, `controller`, or machine model names.
- [ ] Boost products for machine model searches like `ssq`, `ssq3`, `mach ii`, `bg7`, `wav`, `ssh`, `ssr`.
- [ ] Add synonyms for common search language:
  - `ssqii`, `ssq ii`, `ssq2`
  - `ssq3`, `ssq 3`
  - `mach2`, `mach ii`
  - `roof panel`, `standing seam`
  - `gutter machine`, `seamless gutter`
  - `manual`, `owner manual`, `operator manual`
- [ ] Add stopword exceptions if important short machine terms get swallowed.

Teaching line: relevance is not magic; it is a weighted argument about what the user probably meant.

### Phase 4: Add Search-Aware Filtering

Status: not started.

- [ ] Keep the main search query as the source of truth.
- [ ] Build filter counts from the current query context, not global post counts.
- [ ] Cache expensive count queries by normalized search params.
- [ ] Keep filters additive: keyword plus type plus taxonomy.
- [ ] Make invalid filters return zero results, not a fallback firehose.

Facet counts are just filtered counts run beside the main query. Cache them or they become a tiny denial-of-service machine.

### Phase 5: Result Quality Rules

Status: not started.

- [ ] Do not show raw attachment results.
- [ ] Avoid duplicate results when a PDF and parent post both match.
- [ ] Prefer the most useful content type when multiple items have the same title.
- [ ] Use Relevanssi excerpts where available.
- [ ] Preserve normal WordPress pagination.
- [ ] Ensure private, draft, hidden WooCommerce, noindex, and excluded posts never leak into results.

### Phase 6: Legacy Redirect Plan

Status: future work. Do not implement now.

Old URLs still appear in theme code and production history:

- `/search-results/?_sft_category=testimonials`
- `/profile-search/?_sft_category=profiles-metal-roof-wall-panel`
- `/profile-search/?_sft_category=profiles-gutter`
- `/profile-search/?_sft_category=clip-relief-rib-rollers`
- `/profile-search/?_sft_post_tag=...`

Future redirect tasks:

- [ ] Inventory all legacy Search & Filter URLs from theme code, redirects, analytics, and crawl data.
- [ ] Decide canonical destinations for each legacy route.
- [ ] Add 301 redirects only after canonical search/filter URLs are stable.
- [ ] Preserve query intent when redirecting:
  - `_sft_category` maps to `category`
  - `_sft_post_tag` maps to `tag` or `machine`, depending on term ownership
- [ ] Add tests for representative legacy URLs.
- [ ] Update hard-coded theme links after redirects are verified.

Recommended canonical direction:

- General filtered content: `/?s=&category={slug}`
- Profile category filtering: `/profiles/` or category archive, depending on final profile archive strategy
- Machine-specific filtering: `/?s=&machine={slug}` when the `machine` taxonomy is the real intent

Do not blindly redirect everything to `/search/`. That loses intent and creates useless analytics.

### Phase 7: Search Observability

Status: not started.

- [ ] Enable query logging without IP collection.
- [ ] Review zero-result searches monthly.
- [ ] Review high-volume searches monthly.
- [ ] Track searches that bounce quickly.
- [ ] Add redirects or synonyms for proven misses.
- [ ] Keep a short `docs/search-tuning-log.md` once tuning starts.

### Phase 8: QA Matrix

Status: not started.

Smoke-test searches:

- `gutter`
- `ssq`
- `ssq3`
- `mach ii`
- `manual`
- `controller`
- `brochure`
- `profile`
- `footprint`
- `testimonials`

Filter combinations:

- keyword only
- post type only
- category only
- machine only
- keyword plus post type
- keyword plus category
- keyword plus machine
- legacy `_sft_category`
- legacy `_sft_post_tag`
- invalid post type
- empty `s`

Expected behavior:

- No `pricesheet` results.
- No raw attachment results.
- Empty search returns zero results unless filters are present.
- Invalid post type returns zero results.
- Relevanssi relevance order remains active.
- Pagination works.

---

## Definition Of Done

- Search has one documented query contract.
- Relevanssi index settings match the theme contract.
- Legacy URLs have a migration plan before any redirects ship.
- Search logs produce useful tuning work, not noise.
- Smoke tests cover common machine, manual, profile, product, and learning-center searches.
- No `pricesheet` result can appear through normal search.

## Smoke Test Command

From the WordPress root after this theme is active:

```bash
wp eval-file wp-content/themes/amt-ntm/scripts/search-smoke.php --allow-root
```
