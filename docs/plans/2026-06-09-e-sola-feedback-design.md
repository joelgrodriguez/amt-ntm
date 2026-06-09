# E. Sola Annotated-Screenshots Feedback — Implementation Design

**Date:** 2026-06-09
**Source feedback:** [`docs/feedback/2026-06-02-annotated-screenshots-review.md`](../feedback/2026-06-02-annotated-screenshots-review.md)
**Design system of record:** [`DESIGN.md`](../../DESIGN.md)
**Status:** Design — pending user review, then `writing-plans`.

---

## 1. Framing

Most of Evita's markup is **not new design** — it asks the templates to obey the `DESIGN.md` already in the repo. The verification pass (read against current code, file:line evidence) sorted every annotation into one of four buckets:

- **Compliance drift** — code violates an existing system rule (red where blue belongs, loose leading where the system implies tight). Fix = make the code match the doc.
- **New decision** — the system has no rule yet (lighten product-image backgrounds, left→right hero fade). Fix = decide, then add the rule to `DESIGN.md` so it's captured, not re-litigated.
- **Content / asset** — copy or imagery only the NTM team owns (Mazzella reword, field photos, explainer paragraphs). Fix = `TODO(copy)` placeholder + asset slot, real value supplied by the team.
- **Already done** — verification proved it's compliant; **dropped from scope** (listed in §7 so we don't redo it).

The plan is **one phased implementation** (user-chosen): global compliance first (cheap, high-leverage, lands once), then per-page polish, then content/assets last.

**Guiding constraint:** every change cites the `DESIGN.md` rule it satisfies, or — if it's a new decision — adds that rule to `DESIGN.md` in the same change. No silent design drift.

---

## 2. What verification changed

Confirmed **already compliant — removed from scope** (evidence in §7):

- Body text color is already consistent `text-blue-600` sitewide. (No drift to gray/slate found.)
- Accessory image tiles already carry a 1px `border-blue-200`, no shadow — exactly the compliant "encapsulate the floating photos" fix. **Done.**
- The hero legibility overlay **exists** (`hero-overlay.css`, 42% black wash) and is **already applied** to the combo, SSQ3, and MACH II Family heroes — only the *category* heroes lack it.
- MACH II Family cards already use light `bg-blue-50` image wells — the "make them light like the featured card" target is already met **on that page** (still open on the gutter category + machine-product pages).
- Machine-fit "RECONSIDER IF" is already full-bleed background with a `.container` inner — "make it full width" is already the standard pattern. **No change** (unless Evita means edge-to-edge content, see §6 open question).
- Resources "Downloads & Support" heading→list gap is already `gap-12 lg:gap-16` (48–64px). Subjective; not changing without a specific target.

This removed ~6 items and converted "lighten dark backgrounds" from a sitewide sweep into a **2-surface change** (gutter featured card + machine-product image wells), since the pillar page is already correct.

---

## 3. Phase 1 — Global compliance (CSS/token + shared parts)

These land once and propagate everywhere. Highest leverage; do first.

### 3.1 Red → blue on section eyebrows/callouts
**Rule:** `DESIGN.md` §2.4 (10% rule — red is a *pinpoint* accent, not a section-label color) and §5.4 (eyebrow color = red **or** blue-500; blue is the system default).

**Drift found (full grep, all 5 red-eyebrow sites — wider than the first verification pass, which scoped to product parts only):**
- `app/templates/woo/product/parts/machine-breakdown.php:53` — `text-red` on the "Built to Perform" forming-system eyebrow. This part renders for **every machine**, so red leaks onto every product page's breakdown section. **→ blue-500.**
- `app/templates/woo/product/parts/testimonials.php:69` — `text-red` on the "Trusted by Contractors" eyebrow. **→ blue-500.**
- `app/templates/template-articles.php:48` — `text-red` eyebrow on the articles module. **→ blue-500.**
- `app/templates/template-service-hub.php:446` — `text-red` caption-eyebrow. **→ blue-500.**
- `app/templates/parts/hero-asymmetric.php:63` — `text-red` eyebrow on the asymmetric hero. **→ blue-500.**

**Change:** swap `text-red` → `text-blue-500` on all five. Bullets in `.spec-list` are already `text-blue-700` — no change.

**Deliberately NOT changed (red is correct here):**
- `app/templates/parts/cta/youtube.php:31` — YouTube brand red, intentional (commented as such).
- `app/templates/parts/about/origin.php:120` — `text-red` on timeline **year** values; these are spec/metadata accents in the five-machine timeline, a legitimate pinpoint use, not a section eyebrow. Leave.

This is the whole "make callouts/bullets blue" item — 5 swaps, 2 deliberate keeps.

### 3.2 Red → blue on default CTAs (`btn-emphasis` audit)
**Rule:** `DESIGN.md` §5.1 — red CTA fill is "one moment of maximum emphasis, one per page, ideally." Evita: "only use red if we have a secondary CTA beside it / a 2-up."

**Drift found — 4 `btn-emphasis` (red, `buttons.css:41`) usages, each a lone primary CTA in its section:**
- `app/templates/pages/gutter/machii-callout.php:66` — "Meet the MACH II Family"
- `app/templates/pages/roof-wall/featured.php:116` — featured product CTA
- `app/templates/pages/machines/machines-flagship.php:113` — flagship CTA
- `app/templates/pages/finance-center/corbel.php:96` — Corbel "commit" CTA (`btn--commit`, full-width)

**Change:** review each against Evita's rule ("red only if a secondary CTA sits beside it"). Where the red button is **solo** in its section, swap `btn-emphasis` → `btn-primary`. The Corbel one (`btn--commit`) is the finance-center's single decisive action and *may* be a legitimate one-per-page ignition — **flag for your call** rather than auto-swapping. No new variant needed; `btn-primary` (blue) exists.

**Note:** this overlaps the finance-center work captured in the [2026-06-05 service & financing review](../feedback/2026-06-05-service-financing-review.md) — reconcile before touching `corbel.php`.

### 3.3 Section-title line-height
**Rule:** new — `.section-title` currently sets no explicit `line-height` (`sections.css:62`), so multi-line section headings render at browser-default ~1.2+ while the hero H1s use tight `leading-[0.95]`–`[1.05]`. Evita repeatedly: "tighten to match the H1."

**Change:** add `line-height` to `.section-title` in `sections.css`, tied to the existing `--leading-heading-lg` (1.2) / a new tight value. Apply once; every `.section-title` across the site tightens. **Capture the chosen leading in `DESIGN.md` §3.3** (the type-scale table already lists leading per role — make `.section-title`'s explicit).

This single change resolves the "tighten line spacing" item on MACH II Family, gutter category, roof/wall, and machines-overview headings simultaneously.

### 3.4 Hero overlay on category heroes
**Rule:** `DESIGN.md` §5.10 / §10 — subtle legibility gradient is the one sanctioned hero exception. The overlay component already exists and is used on product/pillar heroes.

**Change:** add the existing `.hero-overlay` markup to `app/templates/parts/hero-category.php` (used by `page-machines.php` and `page-roof-wall-panel-machines.php`). No new CSS — reuse the component.

**Open decision (see §6):** Evita's reference is a Honda hero with a **left→right** dark-to-transparent fade; the current component is a **uniform 42% wash**. Decide whether to (a) ship the existing wash on category heroes for consistency, or (b) introduce a directional left→right variant and roll it across all heroes. Recommendation in §6.

---

## 4. Phase 2 — Per-page polish

After Phase 1, these are the remaining page-specific items. Grouped by template.

### 4.1 Heading alignment ("center + make full width")
**Rule:** `DESIGN.md` §7 — headings are "centered or hard-left, never freeform." Both are valid; Evita wants specific ones centered because left-aligned-on-its-own "looks empty."

Mechanical swap `section-header-left` → `section-header` and `section-divider` → `section-divider-center` (per the spacing-system skill), on:
- `app/templates/pages/machii/family-portrait.php:50` — "Three K-style configurations."
- `app/templates/pages/machii/variant-matrix.php:51` — "Three MACH II machines. Pick yours."
- `app/templates/parts/about/people.php` — "When you call NTM…" (also drop max-w constraint for full width)
- `app/templates/parts/about/support.php` — "After the Sale" headline/paragraph
- Learning-center headers: `templates/parts/learning-center.php` — pass `align => 'center'` for the gutter, roof/wall, and machines-overview instances; cap lede width to match the card grid below.

### 4.2 Lighten product-image backgrounds
**Rule:** new decision. MACH II Family pillar already uses `bg-blue-50` (the target). Bring two surfaces in line:
- `app/templates/pages/machines/lineup-flagship.php:37` — featured card image well is `bg-blue-800` (dark navy). Change to a light tint (`bg-blue-50`, matching the pillar reference) — verify text/contrast on the card still holds since the card body is dark.
- Machine-product image wells where dark — confirm during implementation.

**Capture:** add a line to `DESIGN.md` §6 (Imagery): "Product-image wells use `--color-blue-50` (light tint), never a dark fill — the machine reads as a clean studio shot, not a night shot."

### 4.3 Card image consistency / clipping
- **Clipping (roof/wall + machines overview):** `card-product.css:44-51` uses `aspect-ratio:16/9` + `object-contain p-6`. `object-contain` shouldn't crop — Evita's "cutting off at the bottom" is likely the `p-6` padding + wrapper height interaction. **Needs a browser repro at the served checkout** before fixing (can't QA from the worktree — see `project_worktree_vs_served_checkout`). Flag as "fix post-merge with eyeball."
- **"Images more similar in size":** the 16:9 + `object-contain` already normalizes the box; the *visual* size differs because source images have different intrinsic dimensions. Real fix is consistent source crops (asset task) or a tighter inner padding. Treat as content/asset, not CSS.

### 4.4 Decision Tools section completeness (roof/wall + machines overview)
`templates/parts/front-page/tools.php` — title/eyebrow only, no description. Evita: add a description under the heading and/or per-box, or enlarge icons above the text. **Structural + copy.** Add an optional subtitle slot (renders only if provided); supply `TODO(copy)` placeholder description.

### 4.5 Eyebrow bullet consistency (MACH II Family)
Eyebrow dots are added per-instance (some have a `<span>` dot, some don't). Evita: "drop the bullet on THE FAMILY eyebrow; the others look better without it." Decision: **standardize — no leading dot on `.section-eyebrow`** (remove the per-instance dot spans on `family-portrait.php` and `final-cta.php`), unless we want the dot as a deliberate system element. Recommend dropping for consistency. Capture the decision in `DESIGN.md` §5.4 (currently says dot is "optional" — make it "not used on section eyebrows; reserved for X" or remove).

### 4.6 Explainer paragraphs under empty headings
Add an **optional subtitle slot** to the section-header pattern in these parts (renders only when copy is provided), with `TODO(copy)` placeholders:
- machine-product: profiles, accessories, Full Details/specs section headers (`profile-selector.php`, `accessories.php`, `specs-accordion.php` — none currently accept a subtitle).
- MACH II Family comparison table (`comparison-table.php` — no subtitle slot; alternatively tighten `mb-8`→`mb-6` if no copy).

### 4.7 Two confirmed bugs
- **Broken combo image:** `app/data/machines/mach-ii-combo-gutter.php:124` — `https://newtech.local/...` dev URL → replace with the production URL (or a transparent product asset, per Evita). **Bug, not polish.**
- **UNIQ column stretch:** `app/templates/template-service-hub.php:260` — the two-column `grid md:grid-cols-2` makes the DOCUMENTATION box stretch to match the VIDEO TUTORIALS column height. Evita wants it to size to its own content. Fix: apply `items-start` / `self-start` so columns don't equal-height, while preserving the divider border. Verify the border seam still reads correctly.

### 4.8 Contact FAQ spacing
`app/inc/contact-data.php:44` — the troubleshooting answer is an `<ol>` with no inter-item spacing; rendered raw in `contact-faq-list.php:41`. Add `gap`/`space-y` to the list rendering (template-side, so it applies to all multi-item answers) rather than editing the data string.

---

## 5. Phase 3 — Content & assets (team-supplied)

I build the slots and placeholders; NTM supplies the real values. Each ships as `TODO(copy)` / asset-pending so nothing goes live as final-but-wrong.

### 5.1 Mazzella reword — **highest priority, factually wrong**
NTM split off from Mazzella; the About page still claims the relationship. **4 references** to fix:
- `app/templates/parts/about/origin.php:26` — "Since 2015, NTM has been part of Mazzella Companies…" (body paragraph)
- `app/templates/parts/about/origin.php:36` — "Mazzella Companies (since 2015)" (Parent Company metadata callout)
- `app/templates/parts/about/origin.php:6` — explanatory comment (update to match)
- `app/templates/parts/about/support.php:26` — "NTM is backed by Mazzella Companies, which means the capital is there…"
- (Footer `facebook.com/NTMMazzella/` URL — leave; external account, not body copy. Flag for the team to confirm.)

**Needs from team:** the correct current ownership wording. Until supplied, wrap each in `TODO(copy)` with a neutral placeholder ("New Tech Machinery, an independent American manufacturer" or similar) so the page is not actively wrong in the interim.

### 5.2 Imagery (assets Evita already supplied in `docs/feedback/e_sola/`)
- Coil-loading field shots (`Screenshot 2026-06-02 at 9.13.40/9.14.23 AM.png`) → MACH II Family "Mount the coil" step (`workflow.php:28`, currently a turnstile diagram), combo "Compact and Light", SSQ3 forming-system.
- SSQ3 product photo (`SSQ3_OL_0226 2.png`) → knock out to transparent → SSQ3 specs "Full Details" image slot.
- Colored panel/profile images → profiles page + machine-page profile carousels (currently gray fallback when a profile post has no featured image — the real fix is populating featured images in WP, captured as a DB/content task, not code).

**Process note:** image uploads are WordPress-media / served-checkout actions, not git changes. Per `project_db_change_capture`, any media-library or featured-image assignment must be captured replayably (or noted as a manual content step) — code only references the URLs.

### 5.3 Explainer copy
Real text for every `TODO(copy)` subtitle slot added in Phase 2.

---

## 6. Open decisions (need your call before/within the plan)

1. **Hero overlay direction.** Ship the existing uniform 42% wash on category heroes (consistent, zero new CSS), or build a left→right directional fade per Evita's Honda reference and roll it across all heroes (closer to her ask, but new CSS + re-tests every hero)? **Recommendation: ship the existing wash now (Phase 1), evaluate the directional fade as a separate follow-up** — it's a sitewide hero change that deserves its own review, and the wash already solves legibility.

2. **"Make it full width" on machine-fit.** Already full-bleed-bg + contained content (the standard pattern). Does Evita want the *content* edge-to-edge (drop the `.container`), or was the bg already what she meant? **Recommendation: leave as-is; confirm with a screenshot at demo.**

3. **Eyebrow dot — keep or kill?** Recommend kill (consistency), but it's a brand-voice call.

4. **`text-wrap: balance` on tightened headings** — adding it alongside the §3.3 leading change improves multi-line heading shape for free. Include? Recommend yes.

---

## 7. Explicitly out of scope (verified already-done)

Do **not** re-implement these — verification proved compliant:

| Item | Evidence |
|---|---|
| Body text one color | `text-blue-600` sitewide; `.section-subtitle` default; no gray/slate drift |
| Accessory image-box stroke | `card-accessory.css:20` already `border border-blue-200`, no shadow |
| Hero overlay on combo/SSQ3/MACH II | `hero.php:70` + `machii/hero.php` already render `.hero-overlay` |
| MACH II Family card backgrounds | `family-portrait.php:76` + `variant-matrix.php:99` already `bg-blue-50` |
| Machine-fit full-bleed | `machine-fit.php:37` already full-width bg + `.container` |
| Resources heading→list spacing | `.section-content gap-12 lg:gap-16` (48–64px) already generous |

---

## 8. DESIGN.md updates this work will make

Capturing the new decisions so they're not re-litigated:

- §3.3 — make `.section-title` leading explicit (Phase 1.3).
- §5.1 / §5.4 — restate red-CTA and eyebrow-color discipline with the "blue is default, red only for 1-per-page / 2-up" rule the drift violated; resolve the eyebrow-dot question.
- §6 (Imagery) — "product-image wells use `--color-blue-50`, never a dark fill."
- (If decision #1 picks the directional fade) §5.10 — document the left→right legibility gradient variant.

---

## 9. Risks / notes

- **No browser QA from this worktree.** DevKinsta serves the main checkout, not worktrees (`project_worktree_vs_served_checkout`). Visual items — card clipping, UNIQ column seam, lightened-card contrast, tightened leading — are blind fixes here; they need a post-merge eyeball at the served checkout. The plan will tag each blind item.
- **Header height fallback bug** (`project_header_height_unset`) is unrelated but lives near hero work — don't touch unless it surfaces.
- **Reconcile with 2026-06-08 machine-pages work** before landing — some polish (mockup crop, profile ordering) overlaps; don't double-fix.
- Each landed change → its own Superset task per repo convention (`feedback_always_log_tasks`).
```
