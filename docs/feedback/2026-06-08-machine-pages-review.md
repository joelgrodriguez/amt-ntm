# Machine Product Pages Working Session — 2026-06-08

Stakeholder review of the rebuilt **machine pages** — the four category roll-up pages, the simplified per-machine landing pages, and the two custom "pillar" pages (Q3, Mark 2 combo). This is the "Machines meeting" flagged as upcoming in [2026-06-03](2026-06-03-navigation-search-review.md).

- **Meeting:** NTM Website Machine Product Pages Working Session
- **Date:** 2026-06-08, 8:01 PM (~31m of content; the recording runs 4h but the working session ends ~31m, the rest is unrelated cross-talk)
- **Presenter:** Joel Rodriguez
- **Attendees:** Thad Barnette, Alex Spence, Evita Sola, Rick Zand
- **Related work:** machine category templates, per-machine `single` templates, the Q3 + Mark 2 custom landing templates, [`2026-06-05-service-financing-review.md`](2026-06-05-service-financing-review.md)
- **Names referenced (not present):** Copel (scoped the demo to Q3-only), Terry, Alex Crisman (machine drawings/3D source), Tom (video subject — "stop buying factory panels"), Jim (SSR footage source)

Goal: walk every machine page and surface red flags before the demo. Overall reaction was strong — Thad: "the StoryBrand vibe is really good… it just connects with people"; Rick: "nice job, Joel." Most feedback is **content/image/asset review** (pricing, photos, customer stories, accuracy) plus a handful of **theme-codeable polish items**.

---

## Page inventory (what was presented)

The machines IA now has four roll-up pages plus per-machine pages:

1. **All machines** (`see all machines`) — video ("Who is New Tech Machinery? It's you"), SEO intro ("stop buying panels, start making profit"), machines by category, Q3 featured call-out, full comparison table, Gunnison Sheet Metal social proof, quiz CTA, learning-center articles, FAQs, final CTA.
2. **Roof & wall panel machines** — SEO intro ("take your roof panel operation to the next level"), SSQ3 call-out, lineup, table (panel machines only), Hayes article social proof, unique-controller mention, quiz/FAQ/learning-center/CTA.
3. **Seamless gutter machines** — "What is a 5?" video, 5"/6" combo featured, lineup, table (gutter machines only), CNS Rain Gutters story, Mach 2 landing-page call-out, quiz/FAQ/learning-center/CTA.
4. **(category pages each carry a condensed comparison table scoped to that category.)**

Per-machine pages come in two flavors:

- **Simple machine landing page** — the default. Hero with Build/Quote/Talk-to-a-specialist buttons, specs (footprint front-and-center, controller/sources links cleaned up), profiles (moving **above** accessories), accessories, final CTA. "Basically the same thing we currently have, just cleaned up."
- **Custom pillar page** — only **Q3** and **Mark 2 combo** for the demo. Side **section-nav** (moved off the top per Thad's earlier suggestion) linking machine-fit / panels / accessories / engineering specs / download & support / social proof / FAQ / CTA, with a position highlight. Mobile drops the section-nav and is a plain scroll.

---

## Feedback captured

### Image crop — mockup images cut off (Evita, presenter-confirmed)

- Evita: on the machines dropdown / category pages, the **mockup images get cut off top and bottom**. "It looks like you have some kind of margin around the image, but it doesn't fit it."
- Joel: it's a **16:9 crop** on images that shouldn't be cropped. Fine for accessories (no clean boundaries), wrong for machine mockups. Fix: **make them taller / let the machine image fit fully** rather than hard-cropping 16:9. Same issue appears on the simple machine landing page. **Decided — theme fix.** Evita offered to adjust source images if needed; Joel expects to fix it in CSS.

### All-machines hero video swap (Rick)

- Rick: on the **roof & wall panel** page's "make more money on every roof job" headline, the current video is the **About Us** video ("Who is NTM? It's you") — he'd rather a video that **matches the headline**, e.g. the **Tom "stop buying factory panels"** video.
- The "make more money…" headline currently lives on the **combined/all-machines** page (the universal page). Plan that emerged: **move that headline to the roof & wall panel page**, put the **SSQ–Q2 video** (Alex to send) there, and **write a new headline** for the universal all-machines page.
- Alex: only the one Tom video exists; just decide which page it lands on. **Decided — content/asset (Alex sends video) + copy (new universal headline).**

### Five-pillar "ironclad support" strip (Rick)

- Rick asked whether the **five-pillar ironclad support** content is visible anywhere on these pages. It isn't yet.
- Plan: build a **reusable strip/CTA component** for the five pillars that can drop into any section. Thad: "that's something that can go a lot of places." **Decided — build reusable strip.** (Already partially surfaced on the "start your own rollforming business" landing page Joel built.)

### Profiles — ordering + featured-4 + total count (Alex, Thad)

- Alex: profiles are showing **clip relief and bead ribs first** before more representative profiles. Wants control over order — lead with the main profiles, push clip relief / bead ribs later.
- Joel offered two options: (a) order/reorder the full list, or (b) show **4 featured profiles** + a "view the rest compatible with this machine" link to a filtered page. Alex liked the **top-4 featured** idea.
- Thad: **show the total count** so users don't think there are only four — e.g. "16 profiles to choose from." Applies even with a carousel; without the count it's a confusion point. **Decided — theme: profile ordering + featured-4 + total count.**

### Q3 customer story — no real Q3 story yet (Alex)

- The current Q3 social-proof case study features an **old machine** (no Q3). NTM doesn't have a full Q3 customer story yet.
- Plan: pull **quotes/shorts from Alex's Laramie, WY (Q3) trip** — even a short quote works for the demo. Thad: "you don't have to go crazy with that one." **Action — Alex (content/asset).**

### Mark 2 combo page — images + copy (Alex, Thad)

- Alex: several images on the Mark 2 combo "built to perform" section are **terrible** — flagged for the image-swap session.
- Copy fix: the machine-fit section says "panels" — for a **gutter** machine it should be **"gutters."** Settled wording (half-joke, half-real): **"Your gutters, your way."** **Decided — copy fix on Mark 2 / gutter pages.**
- The Q3 profiles-first ordering is done on Mark 2; the **Q3 page still needs the same profiles-above-accessories** treatment. **Action — theme (apply to Q3).**

### Gutter forming/installing video → Mark 2 combo hero (Alex)

- Alex: the video of the crew forming a gutter and installing it currently lives on the **standalone Mach 2** marketing page. Since the **combo page is a pillar page**, that video should **play in the hero background** there — swap the hero image for the video. Thad + Evita agree.
- Evita: "it would be great if we could have videos on all these pages — that top hero section is prime real estate." **Decided — swap Mark 2 combo hero image → background video** (asset exists). General "video in every hero" is a roadmap goal, not day-one.

### Section-nav gap / spacing polish (presenter-identified)

- On the Q3 pillar page, Joel flagged: **Download & Support has too large a gap** above it — reduce. Also needs **padding added** near the section-nav, and the mobile version of one section "doesn't look right." **Decided — theme spacing polish.**

### Mobile section-nav (Thad asked, Joel decided)

- Thad asked what the pillar page looks like on mobile. Joel: **drops the section-nav** on mobile, plain scroll — deliberately simpler to avoid confusing users. Could add a mobile bar later but chose not to. Built mobile-first. **No change — intentional.** (One mobile section still needs a visual fix.)

---

## Roadmap (explicitly deferred — not day-one)

These came up as "we'd love to, but not for launch." Thad's guidance: **put them in the roadmap** ("30/60 days out") so stakeholders see the trajectory, and only pull forward if they make it a priority at the demo.

- **Custom pillar page for every machine** (not just Q3 + Mark 2). The goal is every machine gets the detailed custom treatment; scoped to two for the demo to avoid over-committing on assets. Thad: "they're going to say the custom one's way better than the SSR page" — get ahead of it with a roadmap line. Alex: some (e.g. SSR from Jim's footage) could be done quickly if prioritized.
- **Videos in every machine hero** — prime real estate; blocked on assets.
- **Toyota-style sticky machine sub-nav** for each machine (the pattern Joel is emulating).
- **Photographic / 3D spec callouts** — Joel has Alex Crisman's drawings but the 3D renders are a bigger lift; deferred to keep the demo polished. ("I want to do this so bad.")

---

## Cross-cutting / logistics

- **Pricing** across all pages to be updated from **Rick's recent upload**. **Action — Joel.**
- **Evita already gave Joel machine-page feedback** separately; he expects those updates done by ~2026-06-09. **In progress.**
- **Content export → Rick:** Joel to **export all page content to a spreadsheet** for Rick to edit (accuracy/language pass), then re-import — faster than Rick sifting page-by-page. Thad's one ask: make sure **all the language is accurate**. **Action — Joel exports, Rick edits.**
- **Images call with Alex — 2026-06-09** ("our call tomorrow") to go through flagged images. **Scheduled.**
- Everything pushed to **staging by EOD 2026-06-08**; team to click through daily and flag red flags. Go-live target still **end of June**.

---

## Action Items

> **Checkbox legend:** `[x]` shipped & verified in live code · `[~]` provisional (in code, flagged / awaiting sign-off) · `[ ]` open. This is a review session — most items are content/asset/pricing (team input) or theme polish not yet started.

### Theme (codeable now)
- [ ] **Fix the 16:9 mockup crop** on category pages + simple machine landing pages — machine mockups must fit fully, not hard-crop top/bottom (accessories can keep object-fit cover) — *decided* (Evita + Joel)
- [ ] **Profiles: ordering + featured-4 + total count** — control profile order, show top-4 featured with a "view all compatible profiles" link to a filtered page, and display the total count ("16 profiles to choose from") — *decided* (Alex + Thad)
- [ ] **Apply profiles-above-accessories to the Q3 page** (already done on Mark 2 + simple pages) — *action*
- [ ] **Build a reusable five-pillar "ironclad support" strip/CTA** that drops into any section — *decided* (Rick + Thad)
- [ ] **Swap Mark 2 combo hero image → background video** (gutter forming/installing video, currently on the standalone Mach 2 page) — *decided* (Alex + Thad + Evita)
- [ ] **Reduce the Download & Support gap** on the Q3 pillar page; add padding near the section-nav; fix the one mobile section that "doesn't look right" — *decided, spacing polish*
- [ ] **Copy: "panels" → "gutters"** on the Mark 2 / gutter machine-fit section ("Your gutters, your way") — *decided*

### Content / copy (Rick + Joel)
- [ ] **Move "make more money on every roof job" headline** to the roof & wall panel page; **write a new headline** for the universal all-machines page — *decided* (Rick)
- [ ] **Export all machine-page content to a spreadsheet** for Rick to edit, then re-import — *action — Joel*
- [ ] **Accuracy/language pass** on all machine-page content — *action — Rick* (Thad's gate: "make sure all the language is accurate")
- [ ] **Update pricing** across all machine pages from Rick's recent upload — *action — Joel*
- [ ] **Apply Evita's earlier machine-page feedback** (given separately; target ~2026-06-09) — *in progress — Joel*

### Assets (Alex)
- [ ] **Send the SSQ–Q2 video** for the roof & wall panel page hero — *action — Alex*
- [ ] **Confirm the Tom "stop buying factory panels" video** placement (which page) — *open*
- [ ] **Q3 customer story** — pull quotes/shorts from the Laramie, WY trip (no full Q3 story exists yet) — *action — Alex*
- [ ] **Swap flagged Mark 2 "built to perform" images** at the 2026-06-09 image call — *scheduled — Alex + Joel*

### Roadmap (deferred, surface at the demo)
- [ ] **Custom pillar page for every machine** (not just Q3 + Mark 2) — 30/60-day roadmap line; pull forward only if prioritized
- [ ] **Videos in every machine hero** — blocked on assets
- [ ] **Toyota-style sticky per-machine sub-nav**
- [ ] **Photographic / 3D spec callouts** (Alex Crisman drawings → 3D renders) — bigger lift, deferred

---

## Follow-ups

- This is the **Machines meeting** flagged in [2026-06-03](2026-06-03-navigation-search-review.md). The "Landing-pages meeting" (gating policy, "Understand the Investment") is still the remaining planned session.
- The bulk of this session is **content/asset/pricing review**, not theme code — most items need Rick (copy/accuracy), Alex (videos/images/Q3 story), or the shared image-swap session. The **theme-codeable** subset (image crop, profile ordering/featured-4/count, five-pillar strip, Mark 2 hero video, Q3 profiles reorder, section-nav spacing, gutter copy) is the part this worktree can act on.
- Go-live target: **end of June**.
