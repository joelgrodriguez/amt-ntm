# NTM Website Update — Leadership Presentation Review — 2026-06-17

The stakeholder demo every prior review session was building toward, and the
meeting [the presentation deck](../presentation/slides.html) was built for. Joel
walked leadership through the full redesigned site (strategy slides → live tour),
then took feedback. This is the **top of the funnel** — execs, not the working
team — so most feedback is product/strategy/content direction plus a few
launch-gating concerns (pricing display, AI-bot liability, rollout/testing). Adam
Copel drove most of it. Verdict was positive — "fantastic work… really, really
well done… this is pretty awesome" — with a punch list of content, pricing,
legal, and rollout items to close before the **first-week-of-July launch**.

- **Meeting:** NTM Website Update Presentation
- **Date:** 2026-06-17, 5:59 PM (~1h 6m)
- **Presenter:** Joel Rodriguez
- **Attendees:** Adam Copel (driving most feedback), Thad Barnette, Evita Sola, Alex Spence, Rick Zand, Ben Bradley
- **Setting:** leadership together in the Denver conference room on a large-screen TV (per Thad)
- **Names referenced (not present / mentioned):** Terry ("it has to be better"), Brian/Bryan, Jenkins/Jake (counsel — safety/AI disclaimer review), Kimble, Sally (the HubSpot AI bot, not a person), Caldwell/Corbel
- **Related work:** [`2026-06-02`](2026-06-02-annotated-screenshots-review.md) (Mazzella → AMT thread), [`2026-06-03`](2026-06-03-navigation-search-review.md) (nav/IA), [`2026-06-05`](2026-06-05-service-financing-review.md) (service + finance), [`2026-06-08`](2026-06-08-machine-pages-review.md) (machine pages), [`2026-06-09`](2026-06-09-web-images-review.md) (image swaps), [`roadmap.md`](roadmap.md) (deferred items)

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
service pages) → About page → Learning Center → the live **"Sally" HubSpot AI
chat** (on the current site).

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
**Decided — content with a legal-review gate.** (The related Sally AI-bot safety
workstream is tracked under **Sally**, below.)

### Sally (the HubSpot AI bot) — now live, three concerns (Adam, Rick) — **pre-launch ops**

Sally (HubSpot-powered AI chat, model not disclosed) is **already live** and was
demoed in the room — the biggest single new thread of the meeting. She graduated
from a roadmap "idea" ([2026-06-03](2026-06-03-navigation-search-review.md)) to a
live, in-room demo with real cost + liability concerns. Three distinct threads:

1. **Cost / abuse throttling (Adam):** plan is 3,000 points/month, ~1 token per
   *resolved conversation*; currently in a 2-week free window. Adam wants a
   **"speedometer"** / rate cap on "internet warriors" burning tokens — floated
   ~50 questions as a ceiling. *Action — Rick: confirm token mechanics +
   investigate a per-user rate limit.*
2. **Safety-answer liability (Adam, Ben — big discussion):** the bot must answer
   safety questions **succinctly and absolutely**, never with hedged paragraphs.
   Examples the room wants locked: "Can I run my machine with the covers off?" →
   **"No. Under no circumstances,"** full stop. "Can I put my hand through the
   shear?" → hard no. Plan: a **sit-down session** (Adam, Ben, Rick, maybe Brian)
   to write ~100 canned safety Q&As, then have the lawyer review (Jenkins,
   ~$350–500/hr). Rick: "I can customize any of those answers." *Action —
   schedule the safety-Q&A session; Rick implements canned answers; lawyer
   reviews.*
3. **Answer quality / model tuning (Ben/Adam):** poke at how Sally answers,
   update the model/prompt as needed. *Ongoing.*

(Owned by Rick/leadership in HubSpot — outside this theme's code, but launch-
gating, so tracked here.)

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
- [ ] **Remove 36% APR** from the Finance Center (and audit all finance percentages for compliance) — `finance-center/faq.php` ("8% to 36% APR") and `corbel.php` (`'8–36%'`). *(content)*
- [ ] **Fix quote-checklist page copy** — remove the stray "thank you for filling out the form" confirmation text. *(content)*
- [ ] **Remove the which-machine quiz** from the gutter landing page — drop the `which-machine.php` include from `page-seamless-gutter-machines.php`. *(theme)*
- [ ] **Gutter / Mark 2 pages: benefit/differentiation pass** — durability + advantages vs. KWM. *(content; Rick + Joel)*
- [ ] **About — confirm history/timeline** dates. *(content)*
- [ ] **About — parent-company link only** — single "our parent company" link → AMT (→ Sheffield); no AMT/Sheffield branding on the NTM site. Resolves the [Mazzella → AMT](2026-06-02-annotated-screenshots-review.md) thread. *(content + theme)*
- [ ] **About + homepage — weave in the portability-DNA narrative** (Adam's StoryBrand copy: on-demand, unlimited length/no seams, fewer leaks, lean/waste-control, since '91); dedupe with homepage "why/who for." *(content; Rick)*
- [ ] **Five-pillar messaging up front** — surface the five-pillar value props/benefits more prominently across the content, not buried. *(content; Rick)*
- [ ] **Q3 page — align copy to imagery** — "precision panel after panel" → panel/roller-set image; assign each value prop the right photo. *(asset: Alex / content)*
- [ ] **Q3 page — "built to take a beating"** → link the machine-down-the-hill video. *(content/theme)*
- [ ] **Q3 page — retouch the sun glare** on the top-left coil photo. *(asset: Alex)*
- [ ] **Q3 page — add the trailer (Trey) accessory** to the accessories callout. *(content)*
- [ ] **Sally — token mechanics + rate-limit** — confirm per-conversation token cost; investigate a "speedometer" cap on abusive usage. *(ops; Rick)*
- [ ] **Sally — safety-answer Q&A bank** — schedule the sit-down (Adam/Ben/Rick/Brian), write ~100 canned absolute-answer safety Q&As, Rick implements, lawyer (Jenkins) reviews. *(ops + legal gate)*
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

## Roadmap candidates (post-launch — confirm against [`roadmap.md`](roadmap.md))

These came up as "future / phase-two" and should land on the roadmap, not the
launch list:

- **3D walkthrough for the SSQ3** (Toyota.com-style turntable / 360). Joel ~halfway; explicitly *not* holding the launch. Best done with a real turntable shoot at an owner site — Adam offered to source a **turntable photo-rig owner in Cleveland/Denver** (the proper way to capture it, vs. a faked 3D render). → aligns with existing roadmap "3D / photographic spec callouts."
- **Service-request → NetSuite automation** — service requests created from the website flow directly into NetSuite as tickets, removing manual re-entry (a service-team pain point). Already on the roadmap from [2026-06-05](2026-06-05-service-financing-review.md); reaffirmed at exec level. Phase-two.
- **Siding** — where it lives (own site vs. section vs. banner), distributor handling, custom-profile policy. "Future conversations" — needs its own decision + spec.
- **Custom profiles** — whether to list/offer them on the site at all (standing-seam only; avoid solar/fence/custom-gutter). Leadership decision pending (Adam/Thad/Joel).
- **Third-party service / coil sourcing callouts** — shout-outs to distributors who service, and reputable coil suppliers (Drexel, Sheffield, Paclat). Philosophical/policy decision first.

---

## Follow-ups

- **This was the buy-in gate, and direction passed.** The hard bet — intent-based navigation over catalog navigation — survived the room, including Adam's initial skepticism. Look & feel (no orange, disciplined palette) survived the loudest legacy-orange advocate. Win condition met.
- **Launch target: first week of July 2026.** Development is "done" per Joel — remaining work is content, images, pricing, copy, and the trailer page. Two weeks of ramp-up + **sales/service training** (Thad).
- **The two real risks the room raised are non-code:** (1) **Sally liability** — the AI bot is live and answering safety questions with hedged paragraphs; needs the canned-answer + lawyer pass *before* a public launch. (2) **Rollout de-risking** — Adam wants proof real users won't get lost, via Clarity + a staged release, not a flip-the-switch cutover. Both tracked to done before go-live.
- **Microsoft Clarity** to be installed at launch for heatmaps + session replay (already used to inform the redesign).
- **Safety is the one true blocker shape:** anything safety-related is gated on counsel; don't let it slip into the launch un-reviewed.
- The theme-codeable subset this work owns is small — pricing display convention, calendar-booking CTA surfacing, the parent-company link, the gutter-quiz removal, and the trailer page. Most other items belong to **Rick (content/Sally), Alex (assets), or leadership (strategy decisions)**.
- The big quotable for the room (Adam, twice): "it has to be better" — tempered with healthy caution about launching without a test plan. The rollout-mechanics research answers that concern.
