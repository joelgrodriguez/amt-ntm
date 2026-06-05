# Service & Support + Financing Working Session — 2026-06-05

Stakeholder review of the new **Service Hub** (replacing the current owner-support knowledge base) and a walkthrough of the rebuilt **Finance Center** page.

- **Meeting:** NTM Website Service & Support Working Session
- **Date:** 2026-06-05, 4:30 PM (27m 22s)
- **Presenter:** Joel Rodriguez
- **Attendees:** Thad Barnette, Alex Spence, Evita Sola
- **Related work:** [`2026-06-03-navigation-search-review.md`](2026-06-03-navigation-search-review.md) (this is the "service meeting" + "financing" follow-ups flagged there), `app/templates/template-service-hub.php`, `app/page-finance-center.php`
- **Names referenced (not present):** Ben Bradley (Service Manager), Rick, Mike Storey (NetSuite access), Alex Gray & Jason Lovin (Seligo/NetSuite integration), Kathy (NTM Finance), Terry, Michelle (old financing video)

Goal: focus on the **Get Owner Support** section — the Service Hub landing page that will replace the current support site — and pull detailed ideas to make it more useful for both owners and the service team. Reactions to the financing page: "that's cool," "good stuff," "epic," "use your own lender" idea added live.

---

## Part 1 — Service Hub

### Service request flow (the big one)

The current flow: owner fills out a **HubSpot form** → service team receives it by email → **manually creates a case in NetSuite**. The goal is to eliminate the HubSpot middle-step and let owners **create a service case directly from the website** (and eventually from the mobile app — direct case creation is a driver for the app). Ben Bradley wants this too.

- **Embed the NetSuite native service-request form** on the site to generate the case directly. **Idea — needs investigation.**
- **Hard decision gate (Thad):** only do this if NetSuite can still **track the *source*** of the service request. "If we can still track the source of the service request in NetSuite... then I'm fine with using it. But if we can't, and it's just 'a case was created from who knows'... then I don't want to do it." Don't lose source visibility. **Decision gate.**
- Likely integration path is **Seligo** (NTM's integrator) — Joel would work with **Alex Gray and Jason Lovin**, who operate Seligo and build the integration. Seligo can pass unlimited objects back and forth and supports workflows. **Action.**
- Joel floated a HubSpot-direct route (like his CallScalar integration) that creates the case + contact in HubSpot without Seligo; **Thad thinks that's unlikely** given NetSuite's security setup. Joel to investigate. **Open.**
- Joel needs to **see the NetSuite form and how it works** to make the call; needs **Mike Storey** to confirm his NetSuite access level for the NTM side. **Action — Joel + Mike Storey.**
- Open sub-question Joel raised: do we still want to **track service requests in HubSpot** for the data, or only NetSuite? Thad: "I do see a world where that data could be valuable." Leaning toward keeping trackability somewhere. **Open.**

### Service Hub hero — content-first, not ticket-first

Thad: looking at the hero, "it makes me just want to open a service request" — but the page's purpose is to **avoid frivolous service requests/calls** by helping owners self-serve first.

- **Move the "Open a service request" button lower** on the page; lead with a **"view our content / solve your problem here"**-style CTA first. **Decided** (Thad + Alex).
- Alex: "almost half the questions they get... it's just, 'oh, here, let me send you this video or article.'" If owners find it themselves here, that's a big win for the service team. **Rationale.**

### FAQ / top-questions section

- **Add an FAQ section** to the Service Hub surfacing the most common questions up front. **Decided.**
- Reuse existing data: when the service/support section first launched, the team ran a **"top 10" exercise** to identify the most-asked questions — including **per machine**. Those are the existing knowledge-base-style articles. Joel to pull from that. **Action — Joel to review the old top-10 list.**

### Unique controller section

- **Add a download button for the latest firmware/software** directly in the unique-controller section. Currently there's a link to the software page; Thad wants the most recent version downloadable right from here. **Decided.**
- **Reuse the same link** as the existing software-update page so updating it updates everywhere. **Decided — single source.**

### Service content tagging + content library

- The **`department` taxonomy** (with a `service` sub-tag) is how service content is curated onto these pages. Going forward, content can be tagged `service`, `sales`, `finance`, etc. to surface on the right pages. **Built / approach confirmed.**
- AI did a **first pass** tagging service-related articles (Joel exported the spreadsheet, had AI comb the DB and tag). Still needs a **human review** by someone who knows the content. **Action — Alex to review/finalize the service tags** (he has the spreadsheet; hasn't gotten to it yet).
- The **service content library search-results template needs fixing** — Joel: "the template search results... isn't correct." Has a sticky nav on arrival. **Action.**
- Joel will **transfer all current owner-support / per-machine top-question content over to the Service Hub** — deferred (large manual job, doing it now would be double work).

### Machines grid (design polish)

- **Condense the machine cards:** drop the little descriptions, go **3-up columns**, and **reduce vertical spacing**. Design-only tweak. **Decided.**

### Video thumbnail

- The "5 common questions" video (Ben Bradley) needs the right thumbnail. **Alex to send the standard thumbnail**; Joel will add it. **Action.**

---

## Part 2 — Finance Center

Joel rebuilt the old finance page (previously ~two paragraphs + a contact form — Thad: "it's literally like a black hole") into a full **Finance Center**: three financing paths up top (Apply with Corbel / Section 179 deduction / finance through a bank), a configurator tutorial section, Section 179 breakdown, lenders-we-work-with, and an FAQ. Thad: "I like having a built-out finance page. This is good stuff."

### Removes

- **Remove the Michelle financing video.** Thad: "we do not want Michelle to be the face of our financing anymore." Alex confirmed it was already pulled from the current live page. Joel will **replace it with an image**. **Decided.**

### Adds

- **Add a 4th financing option: "use your own lender."** Owners can use their own credit union / bank, not just NTM's lenders. Add it **both** at the top (as a 4th path alongside the existing three) **and** in the lenders section. **Decided** (Thad).

### Content / page updates

- **Content discovery with Kathy (NTM Finance) + Terry** before go-live — accuracy review of all financing info. Doesn't block the demo, but **put it on their radar before the demo**, and schedule a working session in June (go-live end of June). **Action — Joel to loop in Kathy + Terry.**
- **Configurator tutorial video CSS is broken** — the video (Rick's new configurator tutorial) is supposed to sit on the **right-hand side** of that section; the CSS isn't applying. **Action — fix.** (Page also links the older Alex configurator tutorial + the new Rick one.)
- **Section 179 / Apex link** — Joel consolidated the Section 179 article; it links out to "this thing with Apex." Unsure if that's the desired destination. **Open — confirm.**

### Already in place (no action)

- Lenders-we-work-with section, FAQ section, three-step configurator flow ("configure your machine → see real pricing instantly → apply in the same flow") linking straight to the configurator, final contact CTA.

---

## Cross-cutting

- **Image swap session** — Alex + Joel to meet **next week** to swap out site images. Alex free until **Thursday afternoon**, then traveling to **Laramie, Wyoming** to shoot **Q3 images**. **Action — schedule.**
- More review meetings planned next week (Thad at a show part of the week).

---

## Action Items

> **Checkbox legend:** `[x]` shipped & verified in live code · `[~]` provisional (in code, flagged / awaiting sign-off) · `[ ]` open. New session — most items are open by design (they need NetSuite/finance-team/content input, not just theme code).

### Service request flow
- [ ] Investigate **embedding the NetSuite native service-request form** to create a case directly from the site — *idea, gated below*
- [ ] **Decision gate:** confirm NetSuite can still **track the request source** if we drop the HubSpot form — Thad won't ship without it — *decision gate*
- [ ] Work with **Alex Gray + Jason Lovin (Seligo)** on the integration path — *action*
- [ ] **Joel + Mike Storey:** confirm Joel's NetSuite access and review the actual form — *action*
- [ ] Decide whether to **still track service requests in HubSpot** for the data — *open*

### Service Hub page
- [x] **Move the "Open a service request" CTA lower**; lead the hero with a content-first / "solve your problem here" CTA — *decided* (Thad + Alex) — hero primary now "Find your machine" → `#service-hub-machines`; service-request demoted to secondary (still in the specialist band). (template-service-hub.php, ab8f3a6) 2026-06-05. *Blind — needs post-merge eyeball.*
- [~] **Add an FAQ / top-questions section**, reusing the existing "top 10 most-asked" per-machine list — *decided* — section built as Band 4.5 via the shared faq-accordion part (FAQPage JSON-LD); copy is `TODO(copy)` pending Joel's curated top-10 (service-hub/faq.php, 559e348) 2026-06-05.
- [ ] **Add a firmware/software download button** to the unique-controller section, reusing the existing software-page link (single source) — *decided* (Thad) — *codeable once the canonical download URL is in hand; see research todo §3.*
- [x] **Condense the machines grid:** drop descriptions, 3-up columns, less vertical spacing — *decided, design-only* — `compact` arg on machine-photo-card drops the descriptor; grid now 1/2/3-up with tighter gap (93a5ec9) 2026-06-05. *Blind — needs post-merge eyeball.*
- [ ] **Fix the service content-library search-results template** — *action* (presenter-identified) — *needs a live repro before any fix (no root cause captured); see research todo §2.*
- [ ] **Transfer current owner-support / per-machine top-question content** into the Service Hub — *action, deferred (large manual job)*

### Content / data (team input)
- [ ] **Alex to review/finalize the `service` content tags** (AI did a first pass; needs a human eye) — *action*
- [ ] **Alex to send the "5 common questions" video thumbnail**; Joel adds it — *action*

### Finance Center
- [x] **Remove the Michelle video**, replace with an image — *decided* (Thad) — *no-op verify: the rebuilt page never had a Michelle video; it uses a static configurator mockup + learning-center video links. (The only "Michelle" left is a vanity segment in the Apex lender URL, `financewithapex.com/michelle/` — tracked under the Section 179/Apex item.)*
- [x] **Add a 4th "use your own lender" option** — top of page **and** lenders section — *decided* (Thad) — 4th path card (`dollar-sign`) + a reinforcing note in the lenders section; path grid now md:2-up/lg:4-up (paths.php + lenders.php, 55e0b93) 2026-06-05. *Blind — needs post-merge eyeball.*
- [x] **Fix the configurator tutorial video CSS** (should sit on the right) — *action* — **root cause: not a video or CSS bug.** The configurator mockup `<img>` src double-counted the `app/` segment (`THEME_URI` already includes it), so it 404'd and rendered as a black box. Dropped the duplicate segment (configurator.php, 6a8bf83) 2026-06-05. *Blind — needs post-merge eyeball that the PNG renders real content.*
- [ ] **Confirm the Section 179 / Apex outbound link** is the right destination — *open* — *note: the Apex lender link is `financewithapex.com/michelle/`; confirm that vanity URL is current.*
- [ ] **Content discovery with Kathy (NTM Finance) + Terry** for accuracy; flag before demo, schedule in June (go-live end of June) — *action*

### Logistics
- [ ] **Schedule the image-swap session** with Alex (before Thursday; he's in Laramie, WY after for Q3 images) — *action*

---

## Follow-ups

- This session continues the **service** and **financing** threads opened in [2026-06-03](2026-06-03-navigation-search-review.md). Most items here need **external input** (NetSuite/Seligo, Kathy/Terry on finance, Alex on tags/thumbnails) rather than theme-only code changes.
- **Shipped 2026-06-05** (5 commits, `6a8bf83`→`93a5ec9`): config-image fix, "use your own lender" path, content-first hero, FAQ section (placeholder copy), machines-grid condense. All blind (worktree can't browser-QA) — flagged for post-merge eyeball.
- **Still open external/research items** are tracked with owner/blocker/next-action in `docs/superpowers/todos/2026-06-05-service-financing-research-followups.md` (local working doc): NetSuite/Seligo service-request flow, content tagging, top-10 FAQ copy, search-results repro, controller firmware URL, Kathy/Terry finance discovery, Section 179/Apex link, image-swap session.
- Go-live target: **end of June**.
