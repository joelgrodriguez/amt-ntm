# Engineered Motion System — Implementation Plan

> **For agentic workers:** Implement task-by-task. Steps use checkbox (`- [ ]`) syntax. TDD where a test harness fits (pure JS helpers via `node --test`); markup/CSS is build-verified + deferred browser QA (DevKinsta serves the main checkout, not this worktree).

**Goal:** Promote the service-hub scroll-reveal effect into a deliberate, tasteful, site-wide motion system with named, varied reveal types — sharing one motion-token vocabulary with the existing transitions.dev micro-interaction layer — and retrofit it onto the front page, machine product pages, and service hub.

**Architecture:** Two layers, one vocabulary. **Layer A** = transitions.dev (`transitions.css`) for in-place micro-interactions — untouched in spirit. **Layer B** = the reveal/stagger scroll-entrance system (`animations.css` + `ScrollReveal.js`) — upgraded: standardize on the brand ease `cubic-bezier(0.22, 1, 0.36, 1)`, add named reveal variants (`reveal`, `reveal-stagger`, `reveal-image`, `reveal-rule`) addressable via a `data-reveal` attribute, keep IntersectionObserver-once + reduced-motion + LCP-safe defaults. Guard against the "uniform reflex": one variant per section TYPE, never per element.

**Tech stack:** Vanilla ES modules (Vite), Tailwind v4, CSS custom properties. No new JS dependency (restraint principle). Node `node --test` for the one pure helper.

---

## Brand guardrails (DESIGN.md)
- 200–300ms, 8–16px travel, ease-out, **no bounce/elastic**. Share `cubic-bezier(0.22, 1, 0.36, 1)` with transitions.dev.
- `prefers-reduced-motion` enforced (instant `is-visible`, zero travel).
- Reveals **enhance an already-visible default** — never `display:none`/visibility-gate (LCP + bfcache + headless must not ship blank).
- Zero radius, no shadows — motion adds neither.

## File structure
- **Modify** `app/resources/css/animations.css` — reconcile ease to brand curve; add `reveal-image`, `reveal-rule`; keep `reveal`/`reveal-left/right/scale`/`stagger`; extend reduced-motion block to new variants.
- **Modify** `app/resources/js/modules/ScrollReveal.js` — extend default selector to cover `[data-reveal]`; add a tiny pure helper `revealSelector()` (testable); keep observer-once + reduced-motion behavior.
- **Create** `app/resources/js/modules/ScrollReveal.test.mjs` — `node --test` for the pure helper.
- **Modify** `app/templates/parts/front-page/*.php` (selected sections) — add deliberate `data-reveal` per section type.
- **Modify** `app/templates/woo/product/single-machine.php` — wrap section parts with deliberate reveals (varied, not uniform).
- **Modify** `app/templates/template-service-hub.php` — already uses `.stagger`; align to the new API where it reads cleaner.
- **Modify** `DESIGN.md` — add `## Motion System` section documenting the two layers, tokens, variants, and the one-variant-per-section-type rule.

---

## Task 1: Reconcile ease + add named reveal variants (CSS)

**Files:** Modify `app/resources/css/animations.css`

- [ ] **Step 1:** In the `@theme` block, align the reveal ease to the brand curve so Layer B shares Layer A's vocabulary. Change:
  ```css
  --ease-out: cubic-bezier(0.33, 1, 0.68, 1);
  ```
  to:
  ```css
  /* Shared with transitions.dev --*-ease so both motion layers settle identically. */
  --ease-out: cubic-bezier(0.22, 1, 0.36, 1);
  ```
  (Leave `--ease-in-out` and `--ease-spring` as-is.)

- [ ] **Step 2:** Reduce the default reveal travel from 20px to 12px (brand range 8–16px), on `.reveal`, `.reveal-left`, `.reveal-right` (the translate values). `.reveal-scale` stays at `scale(0.95)`. Example for `.reveal`:
  ```css
  .reveal {
    opacity: 0;
    transform: translateY(12px);
    transition:
      opacity var(--duration-slow) var(--ease-out),
      transform var(--duration-slow) var(--ease-out);
  }
  ```
  Apply the same 20px→12px change to `.reveal-left` (`translateX(-12px)`) and `.reveal-right` (`translateX(12px)`).

- [ ] **Step 3:** Add two new named variants in the `@layer utilities` block, after `.reveal-scale`:
  ```css
  /* Image settle — for product photography. Subtle scale + clip from bottom,
     so the machine "arrives" rather than fades. Materials beyond opacity are
     allowed when they stay smooth (transform/clip-path only — no layout). */
  .reveal-image {
    opacity: 0;
    transform: scale(1.04);
    clip-path: inset(0 0 12% 0);
    transition:
      opacity var(--duration-slow) var(--ease-out),
      transform var(--duration-slow) var(--ease-out),
      clip-path var(--duration-slow) var(--ease-out);
  }
  .reveal-image.is-visible {
    opacity: 1;
    transform: scale(1);
    clip-path: inset(0 0 0 0);
  }

  /* Hairline rule draw-in — for blueprint/spec dividers. Scales a 1px rule
     from the left. The element must be the rule itself (e.g. a bordered or
     bg-filled thin element), not a wrapper. */
  .reveal-rule {
    opacity: 0;
    transform: scaleX(0);
    transform-origin: left center;
    transition:
      opacity var(--duration-base) var(--ease-out),
      transform var(--duration-slow) var(--ease-out);
  }
  .reveal-rule.is-visible {
    opacity: 1;
    transform: scaleX(1);
  }
  ```

- [ ] **Step 4:** Extend the `@media (prefers-reduced-motion: reduce)` block to cover the new variants — add `.reveal-image, .reveal-rule` to the selector list that resets `opacity:1; transform:none; transition:none;` and explicitly reset `clip-path: none;` for `.reveal-image`:
  ```css
  .reveal,
  .reveal-left,
  .reveal-right,
  .reveal-scale,
  .reveal-image,
  .reveal-rule {
    opacity: 1;
    transform: none;
    transition: none;
  }
  .reveal-image { clip-path: none; }
  ```

- [ ] **Step 5:** Build-verify: `cd <worktree> && npm run build` → completes clean; `grep -c "reveal-image\|reveal-rule" app/dist/css/app.*.css` → ≥1.

- [ ] **Step 6:** Commit:
  ```bash
  git add app/resources/css/animations.css
  git commit -m "feat(motion): share brand ease + add reveal-image/reveal-rule variants

  Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
  ```

---

## Task 2: Extend ScrollReveal to the data-reveal API (JS + test)

**Files:** Modify `app/resources/js/modules/ScrollReveal.js`; Create `app/resources/js/modules/ScrollReveal.test.mjs`

The current `defaults.selector` is a hardcoded class list. Extract selector-building into a pure, tested helper and add `[data-reveal]` so a section can opt into a named variant via attribute (`<section data-reveal="stagger">`). The variant attribute maps to the same CSS classes by JS adding the matching class — keeps CSS the single source of the visual.

- [ ] **Step 1: Write failing test** — create `app/resources/js/modules/ScrollReveal.test.mjs`:
  ```js
  import { test } from 'node:test';
  import assert from 'node:assert/strict';
  import { revealClassFor } from './ScrollReveal.js';

  test('revealClassFor: maps data-reveal value to the reveal class', () => {
    assert.equal(revealClassFor('stagger'), 'stagger');
    assert.equal(revealClassFor('image'), 'reveal-image');
    assert.equal(revealClassFor('rule'), 'reveal-rule');
    assert.equal(revealClassFor('left'), 'reveal-left');
    assert.equal(revealClassFor('right'), 'reveal-right');
    assert.equal(revealClassFor('scale'), 'reveal-scale');
  });

  test('revealClassFor: empty / unknown / "fade" → base reveal class', () => {
    assert.equal(revealClassFor(''), 'reveal');
    assert.equal(revealClassFor('fade'), 'reveal');
    assert.equal(revealClassFor('bogus'), 'reveal');
    assert.equal(revealClassFor(undefined), 'reveal');
  });
  ```

- [ ] **Step 2:** Run `node --test app/resources/js/modules/ScrollReveal.test.mjs` → FAIL (export missing).

- [ ] **Step 3: Implement.** Add the exported helper near the top of `ScrollReveal.js` (after the JSDoc, before `defaults`):
  ```js
  /**
   * Map a `data-reveal` attribute value to its reveal CSS class.
   * Unknown / empty / "fade" fall back to the base `reveal` class.
   * `stagger` is the one value whose class is not `reveal-`-prefixed
   * (it's a child-cascade modifier applied alongside child reveals).
   * @param {string|undefined} value
   * @returns {string}
   */
  export function revealClassFor(value) {
    switch (value) {
      case 'stagger': return 'stagger';
      case 'image':   return 'reveal-image';
      case 'rule':    return 'reveal-rule';
      case 'left':    return 'reveal-left';
      case 'right':   return 'reveal-right';
      case 'scale':   return 'reveal-scale';
      default:        return 'reveal';
    }
  }
  ```
  Then extend `defaults.selector` to include the attribute:
  ```js
  selector: '.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-image, .reveal-rule, [data-reveal]',
  ```
  And in `initScrollReveal`, after collecting `elements`, normalize any `[data-reveal]` element by adding its mapped class (so CSS owns the visual and the observer logic stays unchanged). Insert right after `const elements = document.querySelectorAll(config.selector);`:
  ```js
    elements.forEach((el) => {
      if (el.hasAttribute('data-reveal')) {
        el.classList.add(revealClassFor(el.getAttribute('data-reveal')));
      }
    });
  ```
  Leave the reduced-motion branch, observer, and unobserve-once logic exactly as they are — they already add `is-visible` and bail correctly. (The `[data-reveal]` element now carries a reveal class, so the existing `is-visible` toggle works for it unchanged.)

- [ ] **Step 4:** Run `node --test app/resources/js/modules/ScrollReveal.test.mjs` → PASS (2 tests). Also `node --check app/resources/js/modules/ScrollReveal.js`.

- [ ] **Step 5:** Commit:
  ```bash
  git add app/resources/js/modules/ScrollReveal.js app/resources/js/modules/ScrollReveal.test.mjs
  git commit -m "feat(motion): data-reveal API + tested variant mapping in ScrollReveal

  Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
  ```

---

## Task 3: Retrofit the front page (deliberate, varied)

**Files:** Modify selected `app/templates/parts/front-page/*.php`

Apply ONE reveal per section TYPE — not the same class on everything. Read each part first; add the attribute on the section's main wrapper (or `.stagger` on the existing grid where children should cascade). Do NOT gate content behind it (the element is already styled/visible; the class only adds the offset, and ScrollReveal adds `is-visible` on intersect; reduced-motion/no-JS show it immediately).

Suggested mapping (adjust to actual markup; the rule is variety-by-type):
- `explore-machines.php` — the machine grid → `stagger` on the grid wrapper.
- `flagships.php` — flagship callout image → `data-reveal="image"` on the photo; text block → `data-reveal="fade"`.
- `why-own.php` — section → `data-reveal="fade"`; any divider rule → `data-reveal="rule"`.
- `three-step-plan.php` — the 3 steps → `stagger` on their container.
- `social-proof.php` / `tools.php` / `final-cta.php` — `data-reveal="fade"` on the section, used sparingly.

- [ ] **Step 1:** Read each target part; identify the single best wrapper for its reveal type.
- [ ] **Step 2:** Add the chosen `data-reveal` / `stagger` to each, one type per section. No element gets two reveal classes. Confirm no section uses `display:none` to hide pre-reveal.
- [ ] **Step 3:** `php -l` each modified file → no syntax errors.
- [ ] **Step 4:** `npm run build` → clean.
- [ ] **Step 5:** Commit:
  ```bash
  git add app/templates/parts/front-page/
  git commit -m "feat(motion): apply varied reveals across front-page sections

  Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
  ```

---

## Task 4: Retrofit machine product pages

**Files:** Modify `app/templates/woo/product/single-machine.php` (and, only if a section's own wrapper is cleaner, the individual part)

`single-machine.php` composes ~12 section parts. Apply reveals at the COMPOSITION level so it's reviewable in one file, varied by section role:
- `machine-breakdown` (image-led) → wrap with `data-reveal="image"` where the photo is the focus, else `fade`.
- `stats-bar` → if it's a row of figures, `stagger` on the row.
- `machine-fit`, `resources`, `faq` → `fade`.
- `blueprint` (hairline/engineering) → `rule` on its divider, `fade` on the block.
- `profile-selector`, `accessories` (grids/carousels) → `stagger` where children should cascade.

Do NOT reveal the hero (`hero`) or anything above the fold — LCP must not be gated. Leave `floating-quote-cta`, `subnav` untouched (they're sticky/interactive, not entrances).

- [ ] **Step 1:** Read `single-machine.php` fully; decide per-part reveal type (one per type; hero excluded).
- [ ] **Step 2:** Wrap/annotate each chosen part with its reveal. Where a part is included via `get_template_part`, wrap it in a `<div data-reveal="…">` only if that doesn't break the part's own section/full-bleed layout — otherwise add the attribute inside the part's root element (read the part first).
- [ ] **Step 3:** `php -l` modified files → clean.
- [ ] **Step 4:** `npm run build` → clean.
- [ ] **Step 5:** Commit:
  ```bash
  git add app/templates/woo/product/
  git commit -m "feat(motion): apply varied reveals to machine product sections (hero excluded)

  Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
  ```

---

## Task 5: Align service hub + document the system

**Files:** Modify `app/templates/template-service-hub.php`; Modify `DESIGN.md`

- [ ] **Step 1:** Service hub already uses `.stagger` + `.reveal-scale`. Confirm it still reads cleanly under the new ease/travel (no change may be needed; if the cards' `.reveal-scale` is the only reveal, leave it). Optionally add `data-reveal="fade"` to the band intros for consistency — one per band, not per card.
- [ ] **Step 2:** Add a `## Motion System` section to `DESIGN.md` (after §9 motion, or extend §9). Document, concisely:
  - **Two layers:** Layer A = transitions.dev (in-place micro-interactions: accordions, dropdowns, modals, hovers, badges) — canonical, see `transitions.css`. Layer B = scroll entrances (`animations.css` + `ScrollReveal.js`).
  - **Shared ease:** `cubic-bezier(0.22, 1, 0.36, 1)`; durations 150/200/300ms via `--duration-*`.
  - **Reveal variants:** `fade` (default), `stagger`, `image`, `rule`, `left`, `right`, `scale`. Addressable via `data-reveal="…"` or the class directly.
  - **The rule:** one reveal variant per section TYPE, never per element. Reveals enhance an already-visible default (no `display:none`). `prefers-reduced-motion` shows everything instantly.
  - **No new JS deps.**
- [ ] **Step 3:** `php -l` service-hub file → clean. `npm run build` → clean.
- [ ] **Step 4:** Commit:
  ```bash
  git add app/templates/template-service-hub.php DESIGN.md
  git commit -m "docs(motion): document the engineered motion system in DESIGN.md

  Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
  ```

---

## Task 6: Verification

- [ ] **Step 1:** `node --test app/resources/js/modules/ScrollReveal.test.mjs` → all pass.
- [ ] **Step 2:** `npm run build` → clean; `grep -c "reveal-image\|reveal-rule\|data-reveal" app/dist/**` shows variants shipped.
- [ ] **Step 3:** Scope guard: `git diff dev...HEAD --name-only` — only motion CSS/JS, the three retrofit surfaces, and DESIGN.md. `transitions.css` NOT in the list (Layer A untouched). MegaMenu/MobileMenu bfcache code NOT touched.
- [ ] **Step 4:** Grep the retrofit files for `display:none`/`hidden` adjacent to a `data-reveal` (must be none — reveals never gate visibility).
- [ ] **Step 5:** Note deferred browser QA in the Superset Validation field: verify on served checkout post-merge that (a) each surface reveals with the intended variety, (b) above-the-fold is never blank, (c) reduced-motion shows everything instantly, (d) accordions/dropdowns still smooth (Layer A regression check).

---

## Self-review
- **Coverage:** named varied reveals (T1), data-reveal API + test (T2), front page (T3), machine product (T4), service hub + docs (T5), verify (T6). Two-layer separation preserved (transitions.css untouched). ✔
- **Uniform-reflex guard:** every retrofit task says one-variant-per-section-type explicitly. ✔
- **LCP/bfcache:** hero excluded from reveals; reveals never `display:none`; reduced-motion instant; Task 6 step 4 greps for violations. ✔
- **No placeholders:** every code step shows the code. The retrofit tasks (3,4) require reading each template first because the exact wrapper depends on real markup — this is honest, not a placeholder; the variant mapping is specified.
- **Names consistent:** `revealClassFor`, `data-reveal`, `reveal-image`, `reveal-rule`, `is-visible` used identically throughout.
