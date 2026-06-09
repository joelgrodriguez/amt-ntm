# Post-Launch Roadmap

Everything explicitly **deferred past the end-of-June launch** across the three stakeholder review sessions. These are the "we'd love to, but not day-one" items — parked deliberately, not dropped. Pre-launch must-dos live in each session's own action-item list, not here.

**Source sessions:**
- [2026-06-03 — Navigation & Info Arch](2026-06-03-navigation-search-review.md)
- [2026-06-05 — Service & Support + Financing](2026-06-05-service-financing-review.md)
- [2026-06-08 — Machine Product Pages](2026-06-08-machine-pages-review.md)

**Framing (Thad, 2026-06-08):** put deferred work on the roadmap with a horizon — "30 days out, 60 days out" — so stakeholders see the trajectory at the demo and we get ahead of "why isn't the custom page on every machine?" Pull an item forward only if they make it a priority.

**Each item:** what it is · why it's deferred · what unblocks it · rough horizon.

> **Horizon legend:** `30d` post-launch (assets/content mostly in hand) · `60d` (needs build + assets) · `phase-2` (real project, own spec) · `long-term` (vision, not yet scoped). Horizons are planning guesses, not commitments.

---

## Machines

### Custom pillar page for every machine — `60d`
Only **Q3** and **Mark 2 combo** got the full custom landing treatment (side section-nav, machine-fit, panels/gutters, engineering specs, social proof). Every machine should eventually get it.
- **Why deferred:** scoped to two for the demo to avoid over-committing on assets; Copel originally scoped it to Q3-only. The template exists and is reusable — the blocker is content/photos/video per machine, not code.
- **Unblocks it:** per-machine assets (Alex has SSR footage from Jim that could move quickly if prioritized). Thad's guidance: expect "the custom one's way better than the SSR page" and have the roadmap line ready.

### Video in every machine hero — `60d`
Hero is "prime real estate" (Evita); the goal is a background video in every machine hero, not a static image.
- **Why deferred:** blocked on per-machine video assets.
- **Unblocks it:** the asset library. The **Mark 2 combo hero video swap is the pre-launch exception** (asset exists) and lives in that session's action items, not here — this roadmap item is the *general* rollout.

### Toyota-style sticky per-machine sub-nav — `phase-2`
The pattern Joel is emulating (Toyota's per-model sticky nav). The Q3/Mark 2 side section-nav is the first step; the full per-machine sticky-nav system is the bigger ambition.
- **Why deferred:** larger UX system; the current section-nav covers the demo need.
- **Unblocks it:** design time once the pillar-page rollout is further along.

### 3D / photographic spec callouts — `phase-2`
Annotated machine imagery on the spec sections (Joel: "I want to do this so bad") — the Toyota-style "here's this part, here's that part" treatment.
- **Why deferred:** Joel has Alex Crisman's machine drawings, but turning them into 3D renders is a "bigger lift." Held back to keep the demo polished.
- **Unblocks it:** a dedicated render/illustration effort against the Crisman drawings.

### Compare tool — `phase-2`
Apple-style add-to-compare showing machines side by side with specs.
- **Why deferred:** flagged "phase 2" at the nav session; the per-category comparison tables cover the launch need.
- **Unblocks it:** its own spec — interaction model, which specs to surface, mobile behavior.

---

## Service & Support

### Direct service-case creation from the mobile app — `long-term`
The website service-request flow (create a NetSuite case directly, no HubSpot middle-step) is the **pre-launch** target. Doing the same **from the mobile app** is the longer arc — direct case creation is explicitly a driver for the app itself.
- **Why deferred:** depends on the website NetSuite/Seligo integration landing first, plus app work outside this theme.
- **Unblocks it:** the website service-request integration (see the 2026-06-05 research todo), then app-side build. Same source-tracking decision gate applies (Thad won't ship a flow that loses request-source visibility).

### Migrate all owner-support / per-machine content into the Service Hub — `30d`
Move the current owner-support knowledge base and per-machine top-question content into the new Service Hub.
- **Why deferred:** large **manual** job; doing it before the Hub structure settles would be double work.
- **Unblocks it:** finalized Service Hub structure + the `service` content-tag review (Alex). Mostly content labor, not code.

### Parts e-commerce for replaceable parts — `long-term`
Let owners buy small replaceable parts (caster wheel, spring, handle, bolt, screw) directly, no service ticket — to cut the service-team ticket backlog.
- **Why deferred:** flagged "long-term" at the nav session; a real e-commerce surface (catalog, cart, fulfillment) well beyond launch scope.
- **Unblocks it:** its own project — SKU/catalog source, checkout, fulfillment path.

---

## Finance

> No finance-specific items are deferred to *this* roadmap. The Finance Center rebuild is a pre-launch deliverable; its open items (Kathy/Terry content discovery, Section 179/Apex link confirmation) are **pre-launch actions** in the 2026-06-05 list, not roadmap items. Left here as a placeholder so the theme grouping is complete and nobody assumes finance work was forgotten.

---

## Navigation & Discovery

### Promo / offer banners — `30d`
Promotional/offer banners in the nav, plus per-machine offer banners. Joel to mock up.
- **Why deferred:** "idea" at the nav session; needs a mock and a decision on the offer/merchandising model.
- **Unblocks it:** a mockup + which offers/campaigns drive them.

### "Sally" agent bot + programmatic article CTAs — `phase-2`
Programmatic CTAs at article start/end, and integrating the "Sally" agent bot.
- **Why deferred:** "idea / to build" at the nav session; the bot integration is its own effort.
- **Unblocks it:** scoping the Sally integration + a CTA-injection pattern for articles.

---

## Cross-cutting notes

- **Pre-launch ≠ roadmap.** Items like pricing updates, the 16:9 mockup-crop fix, profile ordering, the five-pillar strip, copy/accuracy passes, the Mark 2 hero video swap, and the NetSuite website request-flow research are **launch-targeted** and tracked in their own session docs — intentionally excluded here.
- **The demo move:** present this roadmap *at* the demo. It converts "why isn't X done?" into "X is on the 30/60-day plan," and lets stakeholders pull items forward by trading them against launch scope (Thad: "we can do that, but we can't do this other thing").
- **Go-live target:** end of June 2026. All horizons are measured from launch.
- This doc is a synthesis of the three feedback sessions — when a session doc changes a deferred item's status, reflect it here too.
