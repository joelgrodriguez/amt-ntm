# Behavior spec: frontend

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Frontend build cleanup: dedupe transitions.css import, per-page CSS split, font weight audit + preload, remove dead deps, fix emptyOutDir typo — #54
*Landed 2026-07-02 · type: chore*

- Frontend build cleanup: dedupe transitions.css import, per-page CSS split, font weight audit + preload, remove dead deps, fix emptyOutDir typo

## Weave Adam's StoryBrand "portability is in our DNA" narrative into the site, per the 2026-06-17 stakeholder review action item. Adam flagged it **overlaps the homepage "who our machines are for / the why" — same story, two surfaces; dedupe.**

Strategy: one canonical set of four portability proof-points, **two frames** (no repeated sentences):
- **Home + category pages** = contractor benefit (what portability does for you).
- **About** = origin/belief ("portability has been our bet since '91").

Mirror the proven five-pillar Ironclad pattern (#37): one data function + one reusable template part, dropped onto surfaces with per-page header overrides. Single source of truth = no copy drift.

The four proof-points: **Unlimited Length · No Seams · Fewer Leak Points · Controlled Waste.**

### Files
- **New** `app/inc/machines-data.php` → add `get_portability_pillars()` after `get_ironclad_pillars()`
- **New** `app/templates/parts/portability-dna.php` → reusable strip (model: `templates/parts/ironclad-support.php`)
- **Edit** `app/front-page.php` → include strip before why-own in SELL block
- **Edit** `app/templates/parts/about/origin.php` → reframe p1/p2 to lead with portability-DNA (prose, not the chip strip — avoids duplicating Home)
- **Edit** `app/page-roof-wall-panel-machines.php` → include strip in consider flow
- **Edit** `app/page-seamless-gutter-machines.php` → include strip in consider flow — #39
*Landed 2026-06-23 · type: feature*

- `get_portability_pillars()` returns 4 `{label,title,body}` items, `Standard\MachinesData` namespace, `__()` i18n, docblock flags Rick's content review
- `portability-dna.php` reuses existing section/component classes (no new CSS), four-up grid, mobile-first (single col base → `lg:grid-cols-4`)
- Homepage renders strip above why-own; reads as the portability "why," does not duplicate why-own bullets
- About origin section leads with portability-DNA prose; chip strip does NOT also appear; Mazzella TODO wording untouched; AMT parent link intact
- Both category pages render the strip with distinct section_ids and non-clashing backgrounds
- On-system: blue palette only (no orange), mono eyebrow/labels + sans body per typography-system.md
- No repeated sentence between Home strip and About prose (the dedupe gate)
- `npm run build` passes
