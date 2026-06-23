# NTM Website Update — Stakeholder Review & Post-Launch Roadmap — 2026-06-17

The stakeholder demo every prior review session was building toward, and the
meeting [the presentation deck](../presentation/slides.html) was built for. Joel
walked leadership through the full redesigned site (strategy slides → live tour),
then took feedback. This is the **top of the funnel** — execs, not the working
team — so most feedback is product/strategy/content direction plus a few
launch-gating concerns (pricing display, rollout/testing). Adam Copel drove most
of it. Verdict was positive — "fantastic work… really, really well done… this is
pretty awesome" — with a punch list of content, pricing, legal, and rollout items
to close before the **first-week-of-July launch**.

This doc is the single implementation source for the 2026-06-17 session: the
meeting record and feedback up top, the **pre-launch action items** in the middle,
and the **post-launch roadmap** (folded in from the standing cross-session
roadmap) at the bottom.

- **Meeting:** NTM Website Update Presentation
- **Date:** 2026-06-17, 5:59 PM (~1h 6m)
- **Presenter:** Joel Rodriguez
- **Attendees:** Adam Copel (driving most feedback), Thad Barnette, Evita Sola, Alex Spence, Rick Zand, Ben Bradley
- **Setting:** leadership together in the Denver conference room on a large-screen TV (per Thad)
- **Names referenced (not present / mentioned):** Terry ("it has to be better"), Brian/Bryan, Jenkins/Jake (counsel — safety disclaimer review), Kimble, Caldwell/Corbel
- **Related work:** [`2026-06-02`](2026-06-02-annotated-screenshots-review.md) (Mazzella → AMT thread), [`2026-06-03`](2026-06-03-navigation-search-review.md) (nav/IA), [`2026-06-05`](2026-06-05-service-financing-review.md) (service + finance), [`2026-06-08`](2026-06-08-machine-pages-review.md) (machine pages), [`2026-06-09`](2026-06-09-web-images-review.md) (image swaps)

Goal of the meeting: get leadership buy-in on the **look, feel, and direction**,
not pixel-level design — and show progress, not launch approval. **Achieved.**
Adam, twice: "fantastic work, dude… really, really well done"; "this is pretty
awesome." Ben: "it looks a lot better than what we have currently." The hard
structural bet — **navigation by buyer intent instead of by catalog** — landed;
Adam explicitly came around on it ("Oh, yeah, I do too"). The action items below
are the gap between "approved" and "launchable."

---

## What was presented

Joel framed the redesign around the **buyer's journey** instead of the org chart,
fixing the four problems with the old site: navigation built like the catalog,
slow content discovery / weak search, dense/old "too much orange" look, and weak
mobile (the site is ~60% mobile traffic, skewing owner-support; desktop skews
buyer/education).

**Framing (deck, ~first 12 min):** the redesign goal ("guide people from first
look to a confident purchase"), the four problems, the buyer-journey reframe (4
nav labels = aware → consider → decide → own), look & feel as "industry leader,
not catalog vendor," the "you're buying the people who built it" partnership
message, the Finance Center, the two-paths buying strategy (guided vs.
direct-to-configurator), and the Service/Owner-support hub.

**Live tour (~rest):** homepage → the four nav buckets (New to Roll Forming /
first-time-buyer playlist + "start your own business" page → Choose Your Machine
/ instant machine grid, category roll-ups for panel and gutter, all-machines
page, Q3 and Mark 2 pillar pages, a simple machine page → How to Buy / Finance
Center + configurator path → Get Owner Support / Service Hub + per-machine
service pages) → About page → Learning Center.

---

## Feedback captured

### Pricing — SSQ3 lead-number + trailer asterisk (Adam) — **pre-launch**

Adam's biggest single content callout. The SSQ3 page shows **$130K–$143K**, which
Adam says "has the trailer" baked in — and our competitor (On Roll / KWM) quotes
**minus the trailer**, so theirs *looks* cheaper.

> "I would start with the lower number and just have an asterisk below — trailer
> sold separately… go with 85 or 90, and then there'll be an asterisk and underneath
> trailer sold separately."

So: lead with the **base machine price (~$85–90K)**, asterisk → "trailer sold
separately." Confirmed current data is `$130K – $143K` in `ssq3-multipro.php`
(`price_range`, `low_price` 130000 / `high_price` 143000) — needs a base price +
disclaimer treatment, applied to the pricing pattern across machine pages (Adam:
this is "titled in the pricing stuff" generally, not just Q3 — entry ~$85K,
souped-up ~$140–150K). Joel: "we can definitely do that." Ties to the existing
pricing-update action from [2026-06-08](2026-06-08-machine-pages-review.md).
**Decided — content + template: pricing display convention.**

### Trailer — needs its own page ("NTM trailer vs. traditional") (Adam) — **pre-launch-ish**

Tied to pricing. Our trailer is expensive *because* it's engineered for the
machine (heavy-duty, chip trays, bolt-down), and we're not telling that story.

> "I want to do NTM trailer versus traditional… if you don't understand that it
> was specifically engineered and designed for our machine and really how heavy
> duty it is, I think we're missing an opportunity."

New **trailer article / landing page**: NTM trailer vs. traditional, engineering
+ durability framing. Pairs with the pricing-asterisk note (explains *why* the
trailer costs what it does). Joel: a landing page exists / he can spin one up —
~halfway there already per the 3D conversation. Adam wants it before launch and
mentioned it twice (also on his end-of-meeting checklist).

### Orange — settled (no action)

Adam, repeatedly and half-jokingly: "it needs a lot more old 1970 NTM orange put
back into it… that's my big takeaway, there's not enough orange." He loves the
legacy orange. Joel held the line — orange is gone from the palette by design:
"there is no orange anywhere other than in old images. It's gone." Adam:
"good call, good job." Ben echoed the tease at the end; Joel offered him the
nostalgia opt-out. **No action — the disciplined palette is a deliberate
look-and-feel decision and it survived contact with the loudest voice in the
room.** (Confirmed: no orange token in `theme.css`.)

### Finance Center — drop the 36% APR (Adam) — **pre-launch, content**

> "We can't show 36%."

The Corbel section shows an **8–36% APR** range. Remove the 36% / high-end rate
mention (placeholder; finance figures are a compliance surface — audit all of
them). Lives in `app/templates/pages/finance-center/faq.php` ("8% to 36% APR")
and `corbel.php` (`'8–36%'`). Joel: "we can remove that… through content."

### Quote checklist page — broken copy

The "how to get a quote / quote checklist" page reads **"thank you for filling out
the form"** — leftover confirmation copy on a non-form page. Needs a copy rewrite.

### Gutter landing — remove the machine quiz (Adam)

> "Remove pick your machine quiz on the gutter landing pages."

The which-machine quiz CTA renders on the gutter category landing page via
`which-machine.php`, included by `page-seamless-gutter-machines.php` (also on
`page-machines.php` and `page-roof-wall-panel-machines.php` — scope removal to
the gutter page unless told otherwise).

### Gutter / Mark 2 pages — lead with product advantage vs. KWM (Adam) — **content**

> "I think we really have to talk about our product advantages and our durability…
> make sure this one is benefit rich on our product differentiation over the KWMs."

Content pass on the gutter machine pages — durability + differentiation vs. KWM
specifically. *(Rick + Joel.)*

### About page — history, ownership, portability story

Three threads:

1. **History/timeline needs confirming** — Joel flagged some dates "would need to
   be updated"; Thad raised how much to say about the parent company. (NTM has
   manufactured machines since '91.)
2. **Parent-company handling (AMT / Sheffield)** — Thad surfaced the open
   question of how much to say about **AMT (parent)** and sister brands; this is
   the long-running thread behind the **"reword Mazzella references"** correction
   from [2026-06-02](2026-06-02-annotated-screenshots-review.md) — NTM split off,
   parent is now AMT. **Adam's call:** *don't* put AMT/Sheffield on the NTM site
   directly. One discreet link: "our parent company" → AMT site → from there to
   Sheffield. "I don't think you put Sheffield on the website… you make them
   travel to get there." **Decided.**
3. **Portability-is-our-DNA narrative** — Adam dictated a near-complete
   StoryBrand-style chunk he wants woven in: **portability is in our DNA**;
   on-demand metal roofs & gutters reshape metal construction — drive costs down,
   minimize waste, give contractors control of the manufacturing process; *why*
   portability wins = **unlimited length, no seams, fewer leak points, controlled
   waste** (historically big-box shipped limited lengths that underserved end
   users). Joel: "that's all StoryBrand, we can incorporate that." Adam noted it
   **overlaps the homepage "who our machines are for / the why"** — same story,
   two surfaces; dedupe.

### Machine page content polish (Q3) — value-prop alignment + image fixes

Per-section asks on the Q3 pillar page (content/assets, not layout):

- **Align copy to imagery** — "precision panel after panel" → show a **panel /
  roller set** image; assign each value prop the right photo.
- "Built to take a beating" → link the **machine-rolling-down-the-hill video**.
- Top-left coil photo has **sun glare** — retouch (Adam still likes the shot with
  the sun). Images are Alex's new shots; Adam asked if final → "somewhat final,"
  content team still reviews.
- Add **the trailer (Trey) accessory** to the accessories callout.
- Headings/value props still subject to content-team (Rick) review.

### Safety messaging — legal gate (do NOT ship un-reviewed)

Adam wants safety front-and-center ("safety, safety, safety" — interlocks, the
safety progression over time, "safest machine on the market"), but the room
**flagged liability hard:** state **facts only** ("designed with an interlock"),
never claims that "would be used against you."

> "I love the idea. I just want to make sure he [the lawyer] says it."

Anything safety-related goes to counsel (Jenkins/Jake) before publishing.
**Decided — content with a legal-review gate.**

### Testing & rollout plan — how do we de-risk launch (Adam) — **pre-launch, the real gate**

Adam's central worry, stated plainly: *"there's many a failed invention that the
room thought had to be better"* and didn't go anywhere. He's not worried about
SEO — he's worried about **UX**: will real users struggle to find things? He
asked specifically about **A/B testing** (old site vs. new to some users, compare
data) and a measured rollout.

Joel's answer: **SEO is safe** — top indexed pages keep the same URLs + content,
just redressed (no URL changes). For UX validation: **Microsoft Clarity**
(heatmaps + session recordings, the same tool used to study the current site) at
launch, plus a **staged release** — sales & service first for training/feedback,
then a customer cohort, then wider. Joel flagged a real nuance: **internal users
test differently** than first-time buyers (insiders search by the old
labels/buttons; the whole change is navigational), so internal feedback ≠ market
feedback. Adam wants to **know the mechanics** of a gradual/A-B rollout — "if it's
easy, great; if it's Herculean," reconsider. *Action — Joel: research
A/B-test / staged-rollout mechanics + confirm Clarity is installed pre-launch.*

### Configurator strategy — confirmed

Adam liked the shift away from dumping users straight into the Corbel configurator
(which generated lots of abandoned quotes). New flow — guide → educate →
quote/contact — was endorsed implicitly. No change.

### Five-pillar messaging — more up front (Adam → Rick) — **content**

> "Be a little more vocal about the five pillar content and messaging, Rick…
> those value props and benefits more up front."

Surface the five-pillar value props / benefits more prominently across the
content, not buried (ties to the reusable five-pillar strip decided
[2026-06-08](2026-06-08-machine-pages-review.md)).

### Calendar booking — more prominent (Adam) — **content/UX**

> "The calendar needs to be a little more front and center — booking an
> appointment with the salesperson… a little easier and a little more prevalent."

"Talk to a specialist" currently routes to the contact page; Adam wants the
**direct calendar-booking** path (book straight into a salesperson's calendar —
already a capability) surfaced more, not buried in a form. Scope TBD.

### GDPR / cookie consent — launch-day toggle

Adam asked about cookie opt-in/out. Joel: turned **off in local dev**, but the
GDPR consent banner ships when the site goes live. Not new work — but a real
go-live checklist item so it doesn't get forgotten in the launch flip.

### Distributor / coil / custom-profile philosophy (Adam) — **strategy, decide-then-build**

Adam opened several "marinate on this" strategic threads — **leadership decides,
then directs the team**, not Joel-decides:

- **Third-party service** — shout out distributors that offer service (a
  distributor benefit, frequently asked).
- **Coil sourcing** — do we name reputable coil suppliers (Drexel, Sheffield,
  Paclat…) or avoid the topic? Get-in-front-of-it vs. stay-silent.
- **Custom profiles** — list custom standing-seam profiles as an option? Adam
  leans yes as a wedge against big-box, *bounded* (custom standing-seam only — no
  solar, fences, custom gutter). **Decision pending — Joel + Thad + Adam.**

### Siding — where it lives at HOF launch (Adam) — **strategy, phase-2-ish**

When siding launches (with HOF), is it a **section**, a **banner**, a homepage
block, or **its own site**? Adam's analogy: Ford trucks (sub-site within Ford)
vs. Dodge Ram (own brand). Also unresolved: **distributor handling for siding.**
Joel: templates are ready, just needs content; "explore all machines" must stay
the bread-and-butter focus. **Decision pending — future conversation; not
launch-blocking.**

---

## Action items (pre-launch)

Content/asset items overlap with the content team (Rick) and Alex — flagged inline.

- [ ] **SSQ3 pricing display:** lead with base price (~$85–90K) + asterisk "trailer sold separately"; stop leading with the $130–143K (trailer-inclusive) range. `app/data/machines/ssq3-multipro.php` + pricing pattern. *(content + theme)*
- [ ] **Apply the base-price + disclaimer pattern** to pricing generally (entry vs. souped-up framing), not just Q3. *(content + theme)*
- [ ] **Create the NTM trailer article / landing page** — "NTM trailer vs. traditional," engineering + durability story. *(content + theme; Joel ~halfway)*
- [x] **Remove 36% APR** from the Finance Center (and audit all finance percentages for compliance) — `finance-center/faq.php` ("8% to 36% APR") and `corbel.php` (`'8–36%'`). *(content)* — done #31 (faq → "from 8% APR"; corbel → "From 8%"; audit confirmed those were the only two 36% mentions)
- [x] **Fix quote-checklist page copy** — remove the stray "thank you for filling out the form" confirmation text. *(content)* — done #33 (heading "Thank you for submitting the form." → "Your Machine Quote Checklist"; captured as db script 027. **Note:** rest of the page body is still post-download boilerplate and the slug ends in `-thank-you` — see follow-up below)
- [x] **Remove the which-machine quiz** from the gutter landing page — drop the `which-machine.php` include from `page-seamless-gutter-machines.php`. *(theme)* — done #32 (kept on `page-machines.php` + `page-roof-wall-panel-machines.php`)
- [ ] **Gutter / Mark 2 pages: benefit/differentiation pass** — durability + advantages vs. KWM. *(content; Rick + Joel)*
- [ ] **About — confirm history/timeline** dates. *(content)*
- [ ] **About — parent-company link only** — single "our parent company" link → AMT (→ Sheffield); no AMT/Sheffield branding on the NTM site. Resolves the [Mazzella → AMT](2026-06-02-annotated-screenshots-review.md) thread. *(content + theme)*
- [ ] **About + homepage — weave in the portability-DNA narrative** (Adam's StoryBrand copy: on-demand, unlimited length/no seams, fewer leaks, lean/waste-control, since '91); dedupe with homepage "why/who for." *(content; Rick)*
- [ ] **Five-pillar messaging up front** — surface the five-pillar value props/benefits more prominently across the content, not buried. *(content; Rick)*
- [ ] **Q3 page — align copy to imagery** — "precision panel after panel" → panel/roller-set image; assign each value prop the right photo. *(asset: Alex / content)*
- [ ] **Q3 page — "built to take a beating"** → link the machine-down-the-hill video. *(content/theme)*
- [ ] **Q3 page — retouch the sun glare** on the top-left coil photo. *(asset: Alex)*
- [ ] **Q3 page — add the trailer (Trey) accessory** to the accessories callout. *(content)*
- [ ] **Testing/rollout mechanics** — research A/B or staged rollout (sales/service → customer cohort → wide); confirm **Microsoft Clarity** is installed at launch. *(process; Joel)*
- [ ] **Service content tagging** — make sure service content is tagged properly **by machine** (so per-machine service pages and the service filter fill correctly). *(content/data; carries over from 2026-06-05)*
- [ ] **Surface calendar booking** more prominently on "talk to a specialist" paths. *(theme; scope TBD)*
- [ ] **GDPR / cookie consent** — confirm the consent banner is enabled on the production flip (off in dev today). *(go-live checklist)*
- [ ] **Send Adam + Ben a phone link** to review on mobile; give **Ben back-end/staging access** to review everything. *(process; Joel)*
- [ ] **Train sales & service** on the new site before the customer-facing flip. *(process; Thad)*

### Action items (gated / not launch-blocking)

- [ ] **Safety messaging** — draft, then **counsel review before publishing** anything. Facts only ("designed with an interlock"); no claims that could be "used against us." *(legal gate — Adam/Ben/Jenkins)*

### Action items (strategy — leadership decides, then directs)

- [ ] **Custom profiles** — list custom standing-seam profiles (bounded: no solar/fences/custom-gutter)? *(pending — Joel + Thad + Adam)*
- [ ] **Coil-supplier callouts** — name reputable suppliers (Drexel/Sheffield/Paclat) or stay silent? *(pending decision)*
- [ ] **Third-party-service callouts** — shout out distributors that offer service? *(pending decision)*
- [ ] **Siding placement** — section vs. banner vs. own site (Ford-trucks vs. Dodge-Ram model) + distributor handling. *(pending; launches with HOF)*

---

## Follow-ups

- **This was the buy-in gate, and direction passed.** The hard bet — intent-based navigation over catalog navigation — survived the room, including Adam's initial skepticism. Look & feel (no orange, disciplined palette) survived the loudest legacy-orange advocate. Win condition met.
- **Launch target: first week of July 2026.** Development is "done" per Joel — remaining work is content, images, pricing, copy, and the trailer page. Two weeks of ramp-up + **sales/service training** (Thad).
- **The real risk the room raised is non-code:** **rollout de-risking** — Adam wants proof real users won't get lost, via Clarity + a staged release, not a flip-the-switch cutover. Track to done before go-live.
- **Microsoft Clarity** to be installed at launch for heatmaps + session replay (already used to inform the redesign).
- **Safety is the one true blocker shape:** anything safety-related is gated on counsel; don't let it slip into the launch un-reviewed.
- The theme-codeable subset this work owns is small — pricing display convention, calendar-booking CTA surfacing, the parent-company link, the gutter-quiz removal, and the trailer page. Most other items belong to **Rick (content), Alex (assets), or leadership (strategy decisions)**.
- The big quotable for the room (Adam, twice): "it has to be better" — tempered with healthy caution about launching without a test plan. The rollout-mechanics research answers that concern.
- **Quote-checklist page (#33) — open question for Joel.** The heading fix shipped, but page 11062 ("NTM Machine Quote Checklist") is *structurally* a post-download thank-you page: body copy is "others who downloaded this guide enjoyed…" and the slug is `ntm-machine-quote-checklist-thank-you`. Two unresolved calls: (a) does the body need a real checklist rewrite, and (b) should the slug drop `-thank-you` (a rename needs a redirect entry per the DB-capture rule). Both are content/IA decisions, not mechanical — left for Joel.

---

# Post-Launch Roadmap

Everything explicitly **deferred past the launch** across the stakeholder review sessions. These are the "we'd love to, but not day-one" items — parked deliberately, not dropped. Pre-launch must-dos live in the action-item lists above (and each session's own doc), not here.

**Source sessions:**
- [2026-06-03 — Navigation & Info Arch](2026-06-03-navigation-search-review.md)
- [2026-06-05 — Service & Support + Financing](2026-06-05-service-financing-review.md)
- [2026-06-08 — Machine Product Pages](2026-06-08-machine-pages-review.md)
- 2026-06-17 — Stakeholder Presentation (this doc)

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

### 3D / photographic spec callouts (incl. SSQ3 walkthrough) — `phase-2`
Annotated machine imagery on the spec sections (Joel: "I want to do this so bad") — the Toyota-style "here's this part, here's that part" treatment, including a **3D SSQ3 walkthrough** (Toyota.com-style turntable / 360).
- **Why deferred:** Joel has Alex Crisman's machine drawings, but turning them into 3D renders is a "bigger lift." The SSQ3 walkthrough is ~halfway and explicitly *not* holding the launch. Best done with a real turntable shoot at an owner site — Adam offered to source a **turntable photo-rig owner in Cleveland/Denver** (the proper way to capture it, vs. a faked 3D render).
- **Unblocks it:** a dedicated render/illustration effort against the Crisman drawings; a turntable capture rig.

### Compare tool — `phase-2`
Apple-style add-to-compare showing machines side by side with specs.
- **Why deferred:** flagged "phase 2" at the nav session; the per-category comparison tables cover the launch need.
- **Unblocks it:** its own spec — interaction model, which specs to surface, mobile behavior.

---

## Service & Support

### Service-request → NetSuite automation (website) — `phase-2`
Service requests created from the website flow **directly into NetSuite** as tickets, removing the manual re-entry step (a service-team pain point). Reaffirmed at exec level on 2026-06-17.
- **Why deferred:** integration work (NetSuite/Seligo) beyond launch scope; the source-tracking decision gate applies (Thad won't ship a flow that loses request-source visibility).
- **Unblocks it:** the website service-request integration (see the 2026-06-05 research todo).

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

### Programmatic article CTAs — `phase-2`
Programmatic CTAs at article start/end.
- **Why deferred:** "idea / to build" at the nav session.
- **Unblocks it:** a CTA-injection pattern for articles.

---

## Strategy — leadership decides, then build

These came up on 2026-06-17 as "marinate on this" threads — leadership sets the policy, then the team builds against it.

### Custom profiles — `pending decision`
Whether to list/offer custom standing-seam profiles on the site at all (bounded: standing-seam only — no solar, fences, custom gutter). Adam leans yes as a wedge against big-box.
- **Unblocks it:** a Joel + Thad + Adam decision.

### Coil-supplier callouts — `pending decision`
Whether to name reputable coil suppliers (Drexel, Sheffield, Paclat…) or stay silent. Get-in-front-of-it vs. stay-silent.
- **Unblocks it:** a leadership policy call.

### Third-party-service callouts — `pending decision`
Whether to shout out distributors that offer service (a distributor benefit, frequently asked).
- **Unblocks it:** a leadership policy call.

### Siding placement — `pending; launches with HOF`
Where siding lives when it launches (with HOF): section vs. banner vs. homepage block vs. its own site (Ford-trucks vs. Dodge-Ram model), plus distributor handling. Joel: templates ready, needs content; "explore all machines" stays the bread-and-butter focus.
- **Unblocks it:** a future leadership conversation; not launch-blocking.

---

## Cross-cutting notes

- **Pre-launch ≠ roadmap.** Items like pricing updates, the 16:9 mockup-crop fix, profile ordering, the five-pillar strip, copy/accuracy passes, the Mark 2 hero video swap, and the NetSuite website request-flow research are **launch-targeted** and tracked in the action-item lists above (and their own session docs) — intentionally excluded from the roadmap section.
- **The demo move:** present this roadmap *at* the demo. It converts "why isn't X done?" into "X is on the 30/60-day plan," and lets stakeholders pull items forward by trading them against launch scope (Thad: "we can do that, but we can't do this other thing").
- **Go-live target:** first week of July 2026. All horizons are measured from launch.
- This roadmap is a synthesis of the feedback sessions — when a session doc changes a deferred item's status, reflect it here too.
