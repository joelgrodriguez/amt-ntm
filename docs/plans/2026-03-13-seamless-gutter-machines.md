# Seamless Gutter Machines Category Page — Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Create `/seamless-gutter-machines/` category landing page mirroring the roof-wall page structure but with gutter-specific content and a right-side content hero.

**Architecture:** Same section order as roof-wall page. 3 new data functions in `machines-data.php`. 6 new thin data-wrapper templates in `app/templates/pages/gutter/`. Hero reuses the asymmetric wedge pattern but flipped (content right, video left). Shared parts (`customer-story`, `faq-accordion`, `final-cta`) reused via args. Product grid uses 2x2 layout (4 gutter machines).

**Tech Stack:** PHP 8+, Tailwind CSS v4, WordPress classic theme template hierarchy.

---

## Section Order

```
1. gutter/hero            — NEW: asymmetric wedge flipped (content RIGHT)
2. gutter/value-prop      — NEW: 3-card strip, gutter-specific benefits
3. gutter/product-grid    — NEW: 4 machines in 2x2 grid using lineup-card.php
4. machines/roi-snapshot   — REUSE as-is
5. machines/which-machine  — REUSE as-is
6. gutter/faq             — NEW: data wrapper → shared faq-accordion part
7. gutter/customer-story  — NEW: data wrapper → shared customer-story part
8. gutter/final-cta       — NEW: data wrapper → shared final-cta part
```

## File Map

| Action | File |
|--------|------|
| Create | `app/page-seamless-gutter-machines.php` |
| Create | `app/templates/pages/gutter/hero.php` |
| Create | `app/templates/pages/gutter/value-prop.php` |
| Create | `app/templates/pages/gutter/product-grid.php` |
| Create | `app/templates/pages/gutter/faq.php` |
| Create | `app/templates/pages/gutter/customer-story.php` |
| Create | `app/templates/pages/gutter/final-cta.php` |
| Modify | `app/inc/machines-data.php` — add `get_gutter_machines()`, `get_gutter_faq_items()` |

---

### Task 1: Add data functions to machines-data.php

**Files:**
- Modify: `app/inc/machines-data.php` (after `get_roof_wall_faq_items()`)

**Step 1: Add `get_gutter_machines()` function**

```php
function get_gutter_machines(): array {
    $categories = get_machine_categories();
    return $categories['gutter']['machines'] ?? [];
}
```

**Step 2: Add `get_gutter_faq_items()` function**

5 FAQ items sourced from NTM's gutter machines page:
- Cost (starts ~$12K, up to $35K+)
- Delivery timeline (4-6 weeks standard)
- Warranty (3-year limited + lifetime drive roller)
- Online purchasing (select models online, custom via sales)
- Support (phone, email, portal, service centers)

**Step 3: Verify build**

Run: `npm run build`
Expected: PASS

**Step 4: Commit**

```
feat: add gutter machine data functions
```

---

### Task 2: Create page template orchestrator

**Files:**
- Create: `app/page-seamless-gutter-machines.php`

Mirror `page-roof-wall-panel-machines.php` structure. Template Name: "Seamless Gutter Machines". All `get_template_part()` calls point to `templates/pages/gutter/*` for new sections, `templates/pages/machines/*` for reused sections (roi-snapshot, which-machine).

**Step 1: Write file**

**Step 2: Verify build**

Run: `npm run build`

**Step 3: Commit**

```
feat: add seamless gutter machines page template
```

---

### Task 3: Create hero (content RIGHT, video LEFT)

**Files:**
- Create: `app/templates/pages/gutter/hero.php`
- Reference: `app/templates/pages/roof-wall/hero.php`

**Key differences from roof-wall hero:**
- Wedge clip-path FLIPPED: `polygon(40% 0, 100% 0, 100% 100%, 55% 100%)` (content on right)
- Gradient bleed flipped to match
- Content container aligned right: `ml-auto` instead of default left
- Mobile stays full-width dark overlay (same)
- Eyebrow: "Seamless Gutter Machines" (orange)
- H1: "Seamless Gutters. Fabricated On-Site. Installed Same Day." (font-mono)
- Stats: "30+ Yrs" / "4 Machines" / "$87K Starting"
- CTA: "See the Machines" (blue) + "Talk to a Specialist" (outline)
- Video/poster: placeholder URLs (same as roof-wall for now)
- Square-grid texture clipped to right wedge

**Step 1: Write file**

**Step 2: Verify build**

Run: `npm run build`

**Step 3: Commit**

```
feat: add gutter hero with right-side content wedge
```

---

### Task 4: Create value-prop cards

**Files:**
- Create: `app/templates/pages/gutter/value-prop.php`
- Reference: `app/templates/pages/roof-wall/value-prop.php`

Same pattern-dot-grid + gradient-fade-bottom-sm + 3-card layout. Different content:
- Eyebrow: "Why Portable Gutter Machines"
- Title: "30 Years of Proven Performance"
- Cards:
  1. Icon: `settings` — "On-Site Fabrication" — seamless gutters anywhere, no pre-fab joints
  2. Icon: `trending-up` — "Industry Standard" — polyurethane drive rollers NTM pioneered
  3. Icon: `dollar-sign` — "Low Entry Cost" — starting at $87,245, flexible financing

**Step 1: Write file**

**Step 2: Verify build**

**Step 3: Commit**

```
feat: add gutter value-prop section
```

---

### Task 5: Create product grid

**Files:**
- Create: `app/templates/pages/gutter/product-grid.php`
- Reference: `app/templates/pages/roof-wall/product-grid.php`

4 gutter machines in `lg:grid-cols-2` (clean 2x2). Uses `get_gutter_machines()` and `get_card_border_classes()` helper. Calls `lineup-card.php` — gutter machines have price data so they'll render the price + dual CTA variant automatically.

Note: `$cols = 2` since there are 4 machines — clean 2x2 grid, no overflow row needed.

**Step 1: Write file**

**Step 2: Verify build**

**Step 3: Commit**

```
feat: add gutter product grid section
```

---

### Task 6: Create FAQ, customer story, and final CTA data wrappers

**Files:**
- Create: `app/templates/pages/gutter/faq.php`
- Create: `app/templates/pages/gutter/customer-story.php`
- Create: `app/templates/pages/gutter/final-cta.php`

All three are thin data wrappers calling shared parts.

**FAQ:** Calls `templates/parts/faq-accordion` with gutter FAQ items and section_id `gutter-faq-title`.

**Customer story:** Calls `templates/parts/customer-story` with placeholder content (no real gutter customer quote available from scraped content). Image left, play icon. Can be swapped later.

**Final CTA:** Calls `templates/parts/final-cta` with same overlay as roof-wall (`bg-slate-950/15`, text shadows).

**Step 1: Write all three files**

**Step 2: Verify build**

Run: `npm run build`

**Step 3: Commit**

```
feat: add gutter FAQ, customer story, and final CTA sections
```

---

### Task 7: Final verification

**Step 1: Run build**

```bash
npm run build
```

**Step 2: Verify all files exist**

```bash
ls app/templates/pages/gutter/
```

Expected: `hero.php`, `value-prop.php`, `product-grid.php`, `faq.php`, `customer-story.php`, `final-cta.php`

**Step 3: Verify no duplication**

Confirm all three shared sections (FAQ, customer-story, final-cta) are thin wrappers calling `templates/parts/*`.

---

## Activation

Create a WordPress page with slug `seamless-gutter-machines` and assign the "Seamless Gutter Machines" template.
