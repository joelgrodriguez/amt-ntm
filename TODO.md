# Theme TODO

Tracking future enhancements. Culled 2026-07-10 after the pre-launch audit —
everything previously listed here had already shipped:

- Mobile filter drawer → `filter-sidebar.php` renders a `<details class="filter-drawer">` on mobile for all sidebar consumers.
- Profile/manual WooCommerce card integration → landed via advisor plan 011 (matched machine tags render product cards/thumbnails linking to product pages; unmatched fall back to tag archives).
- Profile/manual archive pages with filters → `archive.php` scoped-catalog mode at `/learning-center/profile/` and `/learning-center/manual/`.
- Search integration for profiles/manuals → `search.php` + `content-search.php` native-card dispatch.
- Compatible profiles/manuals on machine product pages → profile selector/carousels + the resources section's Operator Manual row.

Audit-deferred engineering items (CSS bundle split, YouTube facade,
`machines-data.php` split, dark-surface `text-blue-400` audit, Schema Pro
keep/kill decision, video captions/transcripts QA) are tracked in
`plans/README.md` — not duplicated here.

---

## Cross-sell alias coverage

**Priority:** Medium
**Status:** Open (data, not code)
**File:** `app/inc/machine-product-data.php` (`get_slug_aliases()`)

Only 15 of 66 profile machine-tag usages currently resolve to a WooCommerce
product (verified 2026-07-10). Tags whose slug matches no product slug or alias
fall back gracefully to tag archives. To raise coverage, add `wc-slug =>
tag-slug` entries to the alias map per machine — no template changes needed.

## Language indicator for Spanish manuals

**Priority:** Low
**Status:** Not started

Some manuals are in Spanish. Add a language indicator to `card-manual.php`
and/or the single-manual hero (likely a taxonomy or meta flag first — nothing
in the data model marks language today).
