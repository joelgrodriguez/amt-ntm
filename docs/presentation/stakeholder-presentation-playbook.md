# NTM Website — Stakeholder Presentation Playbook

> **Purpose of this doc:** Joel rebuilt the New Tech Machinery (NTM) website over several
> months and needs to present it to a **mixed room of execs and marketing**. It's his
> **first presentation of this kind**. This playbook is the full plan: the strategy
> narrative, a slide outline, a live-demo script, and a safety-net sheet. A follow-up
> agent should build the three deliverables (A, B, C below) from this spine.
>
> **Win conditions:** (1) **buy-in on the direction** and (2) **showcase progress**.
> NOT a launch-approval ask. The room should leave saying "yes, this is right, keep
> going," and impressed by how far it's come.
>
> **This is a presentation task, not a code task.** Nothing in the theme changes.

---

## 1. The strategic story (the ammunition)

Everything below was verified against the actual codebase and docs. These are facts Joel
can stand on.

### The ONE big idea

Navigation shifted from **catalog buckets** (Machines / Profiles / Resources / Learning
Center / Service & Support) to a **buyer-journey model** — four action labels tied to the
job the visitor is doing:

1. **New to Roll Forming?** → aware
2. **Choose Your Machine** → consider
3. **How to Buy** → decide
4. **Get Owner Support** → own

> Buyers don't think in catalogs. They think in jobs: "Am I new to this? Which machine?
> How do I pay? I own one — now what?" The new nav answers those four questions. **This is
> the whole pitch in one sentence.**

### StoryBrand / Endless Customers is ALREADY built in — present it as DONE

The site's own copy positions the **contractor as hero, NTM as guide**, with an explicit
3-step plan (**Explore · Build · Finance**), repeated dual CTAs, real stakes, and
self-service tools. This is the strongest card in the deck. Proof, pulled from live copy:

- Hero / why-own: *"Buying panels from someone else is profit you give away. Rolling your
  own is the path to keeping it."*
- Plan clarity: *"The machine you pick is the one we build."*
- Self-service empowerment: *"No phone call. No sales gatekeeper. The whole path runs in
  the browser."*
- Transparency: *"No 'contact us for pricing' stall. Your build returns a transparent,
  itemized quote."*
- Choice/guide tone: *"Use the configurator for a live quote, or talk to a specialist who
  will build it with you. Same price, same machine, your choice."*
- Transformation: *"Join thousands of contractors who stopped waiting on suppliers and
  started rolling their own profits."*

**Line to land:** "We didn't bolt StoryBrand on — it's how the whole site is built."

### Feature: Finance Center

Was "a black hole." Now a guided **router** — every way to pay, in one place:

- **Apply with Corbel** (fast: soft credit pull, decision in 4–8 hrs, 36/48/60-mo terms)
- **Section 179** (tax: deduct up to $1.22M, pairs with financing)
- **Bank lenders** (5 partners listed as peers to compare, not a ranked push)
- **Bring your own lender** (NTM supplies quotes/specs to the buyer's bank)
- **Build · Quote · Finance** flow tied to the configurator; FAQ schema; specialist fallback
- Honest disclaimer: NTM is a guide, not a lender. Builds trust.
- Files: `app/templates/pages/finance-center/*`, `app/page-finance-center.php`

### Feature: navigation & mobile philosophy

- **Desktop mega-menu** with *deliberate sequential panel switching* — one panel fully
  closes before the next opens. From `MegaMenu.js`: *"One motion at a time reads deliberate
  and premium; overlapping slides read jagged."*
- **Mobile** is a gesture-aware full-height drawer: 56px touch targets, swipe-back/swipe-down,
  screen-reader live regions, `inert` on inactive panels.
- **Same IA on phone and desktop** — no re-learning. Built mobile-first (most contractors
  are on a phone in a truck).
- Files: `app/resources/js/modules/MegaMenu.js`, `MobileMenu.js`, `app/inc/desktop-nav.php`,
  `app/inc/mobile-nav.php`

### The look & feel (acknowledge, don't debate)

Industrial / engineered: zero border-radius, blue-tinted neutrals + red accent, two-font
discipline (Noto Sans for reading, Noto Sans **Mono** to tag spec/data), a curated 12-piece
motion system, mobile-first throughout. Canonical docs:
`.agents/skills/typography-system.md`, `.agents/skills/spacing-system.md`, `DESIGN.md`.

### Supporting upgrades (rapid-fire)

- **Live header search** — instant results, top-5 cap, type filters (articles/videos/manuals/downloads)
- **Service Hub** — per-machine manuals, firmware, troubleshooting, training; replaces the old fragmented KB
- **Unified machine data layer** — one schema drives specs, pricing, finance data (`app/data/machines/*.php`)
- **Engineered motion system** — reusable, `prefers-reduced-motion` compliant

### Stakeholder alignment already exists — use it

Two recorded feedback sessions (`docs/feedback/2026-06-03-navigation-search-review.md`,
`docs/feedback/2026-06-05-service-financing-review.md`) show Thad / Evita / Alex / Rick /
Kathy / Terry already engaged. This is **not a cold pitch** — it's "here's where your
feedback landed." Pull 3–4 concrete "you said → we did" changes for the deck.

---

## 2. The protocol (what a redesign presentation normally looks like)

Joel asked what the standard is. **Story → Strategy → Show → Ask.**

1. **Do NOT open by clicking around the live site.** The #1 rookie mistake — it turns a
   presentation into a confusing tech demo and loses the room in two minutes.
2. **Open with the problem**, then the **one big idea**, then **reveal the site as proof.**
   Slides carry the *thinking*; the live site is the *payoff*.
3. **Budget:** ~40% framing (slides), ~50% guided live demo, ~10% the ask + discussion.
4. **Format: slides first, then live site.** Standard, and safest for a first-timer — the
   slides are the rail. Freeze? Read the next slide. The live site enters only after the
   strategy is set, so every click *confirms* a point already made.
5. **One narrator, one mouse.** Co-presenters hand off at section breaks, never mid-demo.
6. **Always have a fallback** for the live demo (see §4 Safety).

---

## 3. Deliverables to produce

Three artifacts, in this order. The narrative is the hard part; the demo just illustrates it.

### A. Slide deck (~11 slides, the "framing rail")

One idea per slide. Heavy visuals, almost no body text — Joel talks, the slides don't.

| # | Slide | Point to land |
|---|-------|---------------|
| 1 | Title / one-liner | "The New NTM Website — built around how contractors actually buy." |
| 2 | **The problem** | Old site = NTM's org chart (catalog buckets). Buyers think in *jobs*. Show old-nav vs new-nav side by side. *The whole pitch in one slide.* |
| 3 | **The big idea** | Buyer-journey navigation. Four labels = four buyer states (aware → consider → decide → own). |
| 4 | **The framework** | StoryBrand / Endless Customers. Customer = hero, NTM = guide. "We didn't bolt it on — it's how the site is built." Quote one line of live copy. |
| 5 | **Finance Center** | "Financing was a black hole. Now it's a guided router — every way to pay, in one place." Four paths. |
| 6 | **Configurator funnel** | Build · Quote · Finance. The 3-step plan made real — transparent pricing, no "call for a quote." |
| 7 | **Navigation & mobile** | Mobile-first. Premium deliberate motion. Same structure on phone and desktop. (Philosophy, not CSS.) |
| 8 | **The new look & feel** | Industrial / engineered, consistent, intentional. *Explicitly:* "I'm not here to debate colors today — the point is the system is coherent." (Pre-empts bikeshedding.) |
| 9 | **Supporting upgrades** | Live search · Service Hub · machine data layer. One line each. |
| 10 | **Where your feedback landed** | 3–4 concrete "you said → we did" changes from the 2026-06 sessions. Collaborative, not imposed. |
| 11 | **The ask + what's next** | "(1) Tell me the *direction* is right. (2) Here's what's left: content accuracy, NetSuite form, case studies." Maps to both win conditions. |

### B. Live-demo script (the 50%, scripted click-path)

Each stop = one strategic point, demonstrated, then move on. The demo *confirms* the slides.

1. **Homepage hero** — "Notice it talks to *you*, the contractor, about *your* margin — not about us."
2. **Open mega-menu, switch panels slowly** — "Watch one panel close before the next opens. Deliberate. That's the premium feel." (Show the sequencing on purpose.)
3. **Walk the 4 labels** — "Not product categories. The four questions every buyer asks."
4. **How to Buy → Finance Center** — tour the 4 paths — "Every way to pay, honestly. We even tell them to use their own bank."
5. **Build · Quote · Finance** — open configurator — "No 'call for pricing.' Real number, instantly."
6. **Resize to mobile / pull out a phone** — mobile menu, swipe — "Built mobile-first. Most contractors are on a phone in a truck." *(Strongest single 'wow' for non-technical execs.)*
7. **Header search** — type a query — "Find a manual in two seconds."
8. **Machine page** — finance CTA + specs — close the loop back to the journey.

End back on the homepage so the last thing they see is the hero thesis.

### C. Talking-points / FAQ sheet (Joel's safety net, one page)

- **The 3 sentences he must nail** — thesis, the big idea, the ask — memorized cold.
- **Likely exec questions + crisp answers:** "Is it live?" · "What's it cost to finish?" ·
  "Why these four labels?" · "Did we lose SEO / existing pages?" (existing URLs preserved —
  see `docs/handoff/05-pages-to-reuse.md`) · "Why the mono font?" (it tags data; deliberate
  B2B signal).
- **Bikeshedding deflection:** "Great note — fast tweak, I'll log it. Can we keep the lens on
  whether the *direction* is right?"
- **Open items to name honestly** (don't get surprised): some machine prices out of date,
  NetSuite service form pending, content-accuracy review with Kathy/Terry queued. Naming
  these = credibility.

---

## 4. Build order, verification & safety

**Build order:** (1) lock the deck outline/narrative and get Joel's sign-off → (2) script the
demo to mirror it stop-for-stop → (3) write the safety sheet → (4) build the actual slides
last. The deck should *look* like the site: blue-tinted neutrals, mono labels, sharp corners,
screenshots over bullet walls. Consider the `frontend-design` / `canvas-design` skills, or
plain Google Slides.

**Verification before the room:**
- Dry-run out loud, timed. Target 20–25 min talk + 10 min discussion. Over time? Cut features
  (slide 9) before story (slides 2–4).
- Rehearse the live demo on the actual presenting machine and room network. *Note: DevKinsta
  serves the **main checkout**, not worktrees — confirm the demo URL serves the current build.*
- **Record a screen-capture of the demo as the fallback.** If wifi/laptop dies, play the video.
  Non-negotiable for a live walkthrough.
- Pressure-test with one colleague playing a skeptical exec — have them interrupt, fixate on a
  color, ask "is it done?" Practice the deflections.
- Confirm open items are current against the feedback docs so slide 11 doesn't over/under-promise.

---

## 5. Out of scope

- No code, design, or copy changes to the site for this task.
- Not a launch-approval business case (win = direction + showcase, not go-live).
- Not re-litigating colors/fonts — the deck *deflects* that by design.

---

## Appendix — source material for the follow-up agent

- Strategy/brand voice: `PRODUCT.md`, `DESIGN.md`
- Navigation rebuild spec: `docs/handoff/` (01–07), esp. `02-the-change.md`, `03-mega-menu-spec.md`
- Existing URLs preserved: `docs/handoff/05-pages-to-reuse.md`
- Stakeholder feedback: `docs/feedback/2026-06-03-navigation-search-review.md`,
  `docs/feedback/2026-06-05-service-financing-review.md`
- Buyer-journey rationale: `docs/plans/2026-05-27-buyer-journey-navigation-flow.md`
- Design system: `.agents/skills/typography-system.md`, `.agents/skills/spacing-system.md`
- Finance Center: `app/templates/pages/finance-center/*`, `app/page-finance-center.php`
- Navigation: `app/resources/js/modules/MegaMenu.js`, `MobileMenu.js`, `app/inc/desktop-nav.php`
- Architecture map: `docs/architecture/map.json`, `docs/architecture/flows.json`
