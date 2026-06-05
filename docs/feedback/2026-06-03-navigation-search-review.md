# Navigation & Information Architecture Working Session — 2026-06-03

Stakeholder review of the full buyer-journey navigation revamp (all four sections), the megamenu, machine/landing-page templates, and the upgraded site search.

- **Meeting:** NTM Site New Nav & Info Arch Working Session
- **Date:** 2026-06-03, 4:31 PM (56m 36s)
- **Presenter:** Joel Rodriguez
- **Attendees:** Thad Barnette, Evita Sola, Alex Spence (Rick referenced but not present)
- **Related work:** [`docs/plans/2026-05-27-buyer-journey-navigation-flow.md`](../plans/2026-05-27-buyer-journey-navigation-flow.md), [`docs/plans/2026-05-26-navigation-simplification-plan.md`](../plans/2026-05-26-navigation-simplification-plan.md), [`docs/plans/2026-05-22-search-implementation-plan.md`](../plans/2026-05-22-search-implementation-plan.md), [`docs/presentations/navigation-flow/`](../presentations/navigation-flow/)

The new nav follows the **buyer's journey** (originally Thad's vision), replacing the old bucket nav (Resources / Machines / Learning Center). Four sections:

1. **Get Started** (awareness — "new to roll forming")
2. **Choose Your Machine** (consideration)
3. **How to Buy** (decision)
4. **Get Support** (post-sale)

Inspirations: Impact (buyer-journey nav), Tesla & Toyota (megamenu style). Thad confirmed the overall structure maps cleanly to the buyer's journey and "at first look, that looks really good." Closing reactions: "very intuitive," "a lot of work went into this… awesome work," "great job."

---

## Section 1 — Get Started

### Renames / label decisions
- **Rename the section "Get Started" → "New to Roll Forming?"** Evita: "Get Started" is ambiguous next to the specific siblings. Thad agreed immediately — aligns with the "turn panel buyers into panel makers" strategy. Alex: "I like that." **Decided.**
- **Rename the "See if it fits" heading.** Joel dislikes it. Options floated: "Is this for you?", "Is it right for your business?", "Is it the right time?", "Are you ready?" Joel liked "Are you ready?" — will run options through AI and pick. **Open — needs final choice.**

### Removes
- **Remove "Roof panel vs. gutter machines" link.** Flagged in an earlier meeting as not useful. Joel will keep the page in the background (for LLM/AI-agent retrieval) but pull it from the nav. Removing it leaves only two links under Start Here → needs a replacement (below). **Decided to remove.**

### Adds / replacements
- **Add the link Thad shared in chat** ("What is portable roll forming" article) to replace the removed roof-vs-gutter link. Note: that video still shows the old NASR/Messer machine in the thumbnail — flagged but acceptable for now. **Decided.**
- Alex floated adding a generic "What is roll forming" item; Thad clarified the existing "What is an NTM machine?" link already points at the "What is portable roll forming" article, so it's covered.
- Consider **"5 reasons not to wait"** content (Rick's) for the "See if it fits" section. Alex to drop in chat. **Idea.**

### Content / page updates
- **"What is an NTM machine?" article is from 2022 — needs updating** (Alex has had a new video on his list). Tied to the publish-date decision below.
- **First Time Buyer Playlist** (custom page) and **Start Your Roll Forming Business** (custom page) both **need thorough review/sign-off** for accuracy before launch.

### Ideas / open questions raised here
- **Programmatic CTA in articles.** Alex wants a "talk to a specialist" path near the top of educational articles for users who want to move faster. Joel will add a programmatic CTA at the start and/or end of all articles (currently CTAs come via HubSpot). The forthcoming **customer agent bot** (Rick calls it "Sally," human name/face per Terry) in the lower-right will also serve this. **Idea / to build.**
- **Publish-date handling (AI/schema decision).** Updated articles now drop the original publish date and show only the new date. Two approaches debated: (a) keep original date + "updated on" slash, or (b) show new date + an in-article "originally published… updated 2026" callout. Joel chose (b) for now; affects schema, which is what AI reads first. **Open — confirm policy with Rick.**

---

## Section 2 — Choose Your Machine

### Megamenu
- **Add machines directly into the megamenu.** Thad wants to see the machines (cards) in the mega menu, like Joel's earlier iteration (categories on the side → machines populate on selection). Joel agreed, will rework and share. This is the most important panel. **Decided.**

### Renames / label decisions
- **Rename "Mach 2 family."** Alex: not tied to that name; wants something like "Mach 2 — 5"/6"/6.5" combo" mirroring the SSR/SSH naming, or per Thad "K-style gutter machines," so users who don't know the model name still understand what they're clicking. Joel expects it to change. **Open — needs final naming.**
- **Reorder machine landing-page sections: put Profiles above Accessories** (Thad — profiles are the more important thing). **Decided.**

### Template / UX
- **Sticky section nav: try a sidebar instead of a header sticky nav.** Thad almost didn't notice the top sticky nav for the first couple scrolls; the Learning Center's sidebar nav reads better and left-to-right is easier than up-down. Joel will build a sidebar-nav template variant to compare. Alex likes the jump-around nav generally. **Decided to prototype.**
- **Compare tool (phase 2).** Joel wants an Apple-style add-to-compare tool showing machines side by side with specs. **Idea — phase 2.**

### Content / page updates
- **Machine prices are out of date** (Thad flagged; Joel's update didn't catch — mock machine not even showing). Joel will pull correct pricing from Rick's newly published numbers. **Action.**
- **Video thumbnails wrong/missing** — the 5"/6" combo video shows the NASR thumbnail (not in that video); other Wistia videos show no thumbnail. Alex will pull correct thumbnails for all those videos; Joel will add them. **Action.**
- **"Help me choose" content needs review:** the machine selection guide (Joel-created) needs review; the "which coil width" page has a display issue to fix; a comparison article references the Q2 → needs heavy update to SSQ3 (Thad: R vs H vs Q is the better comparison, also needs SSQ3 update; on Rick's radar).
- **Configurator placement reasoning** — Thad reminder: when presenting to leadership, explain *why* configurator links are placed where they are (guide to the right machine first, start quote from the machine page) rather than big "go to configurator now" buttons everywhere. Trade-off: possibly fewer but better-qualified quotes. **Talking point for leadership.**

### Images / legal
- **Drop the recurring placeholder image** Alex flagged — no share card, doesn't meet new legal requirements; ditch it. Joel will swap in Alex's newer edited premium images. **Action.**
- **Mach 2 "how it works" image** should show someone actually mounting the coil. Evita already sent video stills (Ron loading) to use. **Action.**

---

## Section 3 — How to Buy

### Renames / label decisions
- **Rename "Request a Quote."** It currently just hits the contact page. Reframe TurboTax-style (DIY configurator vs. expert-assisted): options floated — "get expert help / get an expert quote / get help with a quote / get a tailored quote / get a specialist-created quote." Since "get a quote" is already the label, a "get help with your quote"-style variant makes sense. **Open — needs final wording.**
- **Rename "Understanding the deal."** Joel isn't happy with it. Thad: lean toward the money/investment angle — "understand the investment" suggested and liked. **Decided → "Understand the Investment"** (Thad/Joel/Evita all positive).
- **Remove the "(expert shortcut)" parenthetical** — Thad: "sounds like AI." **Decided to remove.**

### Removes / dedup
- **Remove the duplicate "what to know before a quote" link.** There were two; Alex pointed to the newer one Rick updated (with video). Keep "How to get a quote," remove the older duplicate. **Decided.**

### Adds
- **Add a "Configure" link directly below "Request a Quote"** in the highlighted column (Alex — for consistency, since configure currently lives only on the right). Joel agreed. **Decided.**

### Content / page updates
- **"What to know before a quote" article is from 2023** — Thad: any core educational piece in the nav should be updated with correct info and a recent updated date. Alex shared the newer Rick-updated version (with video) in chat. **Action.**
- **Financing/leasing page** is missing its video — Joel needs to locate it. **Action.**
- **Spruce up the "How to get a quote" checklist page** (make it nicer). **Action.**

### Gating
- **Ungate "How to get a quote"** — Thad: "this right here shouldn't be gated." The **gutter machine selection guide / Gutter Machine eBook stays gated** (intentionally, for sales-team leads). Revisit broader gating policy in the landing-pages meeting. **Decided (this piece ungated); policy TBD.**

### Configurator behavior
- **Configurator must open in a new tab every time**, and needs a link back to the NTM site from Corbel (currently it takes over the full page with no way back). Best practice: new tab so users can return. **Action.**

---

## Section 4 — Get Support

Goal: serve existing owners *and* reinforce to prospective buyers that an NTM purchase comes with real support (value-add to the buyer journey). New **Service Hub** replaces the current knowledge base — click a machine → manuals, spec/brochures, and all service/repair content tagged to that machine, plus troubleshooting resources.

### Renames / reorder
- **Move the Troubleshooting section to the top** of the service videos (Alex — troubleshooting should lead on service-related pages). **Decided.**

### Removes
- **Remove "Knowledge base" nav item** — it's just a quick link to the machine pages; Joel: "I don't know if that even needs to be there." **Likely remove (Joel-proposed).**
- **Remove "Owner support landing"** — currently a placeholder; "I don't think that needs to be there." **Likely remove (Joel-proposed).**

### Content / cleanup
- **Clean up manual tags** — Joel to work on the manual filter tags with Rick and Alex. **Action.**
- **Service Hub still in progress** (per-machine tagged content); **service content filter template is broken** (clearing filters reloads the same page) — needs fixing. **Action.**
- **Fix spacing on the Request Training page.** **Action.**
- **Troubleshooting articles** and the "prevent voiding your warranty" piece have a display issue to fix. **Action.**
- **Unique controller section** — Joel unsure it warrants its own section; Thad: "I think it does." **Decided to keep.**
- Deeper service-page discussion deferred to the dedicated **service meeting**.

### Ideas
- **Parts e-commerce (long-term).** Joel's vision: let owners buy small replaceable parts (caster wheel, spring, handle, bolt, screw) directly without a service ticket, to cut the service-team ticket backlog. **Idea — long-term.**

---

## Section 5 — Search

Live header search: instant results capped at top 5 (cap adjustable), link to full results page, Enter submits, result-type filters (Articles / Videos / Resources / Manuals / Downloads). Joel framed it as still in progress. Reactions: "I like that a lot," "that's cool."

- **Relabel the "5 results" badge → "Top 5 results."** Evita: "5 results" reads as "only 5 exist" when more match. Joel agreed; Evita confirmed "more helpful." **Decided.**
- **Fine-tune the result-type filters** — Joel: "there still needs some fine-tuning there." **Action (presenter-identified).**
- **Decide the result cap** — keep 5 or raise it (configurable). **Open (presenter-identified).**

---

## Cross-cutting ideas

- **Promo / offer banners in the navigation.** Thad wants the ability to promote a new thing (banner) in the nav, with a few mockup ideas for the team. Alex: lots of white space below the How-to-Buy highlighted links — a full-width banner could fill it, same spacing across all options, optionally different per option. Joel: easy to add a configurable banner below each megamenu column; also wants per-machine offer banners on the machines page (e.g. "0% financing" on the Q3 instead of the flagship banner). Joel will mock this up when he reverts the Choose-Your-Machine section to machines. **Idea — Joel to mock up.**
- **Publish-date / "updated on" policy** (see Get Started) applies site-wide to every refreshed educational article. **Open — confirm with Rick.**
- **Programmatic article CTAs + "Sally" agent bot** (see Get Started) span the whole site.

---

## Action Items

Joel committed (55:35) to extract **all** action steps. Grouped by type; source tagged. "Decided" = agreed on the call; "Open" = needs a final choice; "Idea" = future/phase-2.

> **Checkbox legend:** `[x]` shipped & verified in live code · `[~]` provisional (live in code but flagged `TODO(copy)`, awaiting final sign-off) · `[ ]` open. Reconciled against `dev..feedback` git history 2026-06-05.

### Renames (labels)
- [x] Rename **"Get Started" → "New to Roll Forming?"** — *decided* (Evita/Thad/Alex) — shipped (desktop-nav.php)
- [x] Rename **"Understanding the deal" → "Understand the Investment"** — *decided* (Thad) — shipped (desktop-nav.php)
- [x] Pick final wording for the **"See if it fits"** heading — *final: "Are You Ready?"* (locked 2026-06-05)
- [x] Pick final wording for **"Request a Quote"** — *final: "Get help with your quote"* (locked 2026-06-05)
- [ ] Rename **"Mach 2 family"** to model-descriptive (e.g. "Mach 2 — 5"/6"/6.5" combo" or "K-style gutter machines") — *open*
- [x] Remove the **"(expert shortcut)"** parenthetical — *decided* (Thad) — shipped (desktop-nav.php)

### Removes
- [x] Remove **"Roof panel vs. gutter machines"** from nav (keep page in background for AI) — *decided* — shipped (desktop-nav.php)
- [ ] Remove the **duplicate "what to know before a quote"** link, keep the newer one — *decided* — **still open:** two distinct quote-prep links live ("What to know before quoting" + "How to get a quote on an NTM machine"); which is the older dupe to drop is Alex's call (the newer Rick-updated one with video stays)
- [x] Remove **"Knowledge base"** nav item — *Joel-proposed, likely* — shipped (desktop-nav.php)
- [x] Remove **"Owner support landing"** placeholder — *Joel-proposed, likely* — shipped (desktop-nav.php; the `owner-support` id/path that remains is Section 4 "Get Owner Support" itself, not the old placeholder link)

### Adds / reorders
- [x] Add machines (cards) into the **megamenu** under Choose Your Machine — *decided* (Thad) — shipped (mega-menu.php + desktop-nav.php)
- [x] Replace removed roof-vs-gutter link with the **"What is portable roll forming"** article — *decided* — shipped (desktop-nav.php)
- [x] Add **"Configure"** link below "Request a Quote" in How to Buy — *decided* (Alex) — shipped (desktop-nav.php)
- [x] Reorder machine pages: **Profiles above Accessories** — *decided* (Thad) — already ordered correctly (single-machine.php: profile-selector before accessories)
- [ ] Move **Troubleshooting to the top** of Get Support service videos — *decided* (Alex) — *open (service-page templates, not nav)*

### Templates / UX
- [x] Build a **sidebar section-nav** template variant to compare against the header sticky nav — *decided to prototype* (Thad) — enabled on single-machine.php via `.layout-with-rail` (2026-06-05)
- [ ] Make the **configurator open in a new tab** every time + add a link back to the NTM site from Corbel — *action*
- [ ] Fix the **service content filter template** (clearing filters reloads same page) — *action*
- [ ] Fix **Request Training page spacing**; fix troubleshooting / "prevent voiding warranty" display issues — *action*

### Content / data
- [ ] Update **machine prices** from Rick's newly published numbers (current update didn't catch) — *action*
- [ ] Add correct **video thumbnails** (Alex to pull; combo video shows wrong NASR thumbnail) — *action*
- [ ] Update dated articles in nav: **"What is an NTM machine?" (2022)**, **"what to know before a quote" (2023)**, Q2 comparison → SSQ3 — *action*
- [ ] Review/sign off custom pages: **First Time Buyer Playlist**, **Start Your Roll Forming Business**, machine selection guide — *action*
- [ ] **Clean up manual tags** (with Rick + Alex) — *action*
- [ ] Swap out the **placeholder image** Alex flagged (legal/share-card); use Evita's coil-loading stills for Mach 2 — *action*
- [ ] Locate the missing **financing/leasing video**; spruce up the **"How to get a quote" checklist** page — *action*

### Search
- [ ] Relabel **"5 results" → "Top 5 results"** — *decided* (Evita)
- [ ] Fine-tune **result-type filters** — *action* (Joel)
- [ ] Decide the **result cap** (5 vs. more) — *open* (Joel)

### Policy / decisions to confirm
- [ ] Confirm **publish-date / "updated on" policy** (schema-affecting) with Rick — *open*
- [ ] Confirm **gating policy** in the landing-pages meeting ("How to get a quote" ungated; gutter eBook stays gated) — *open*
- [ ] Add **programmatic CTAs** to article start/end; integrate **"Sally"** agent bot — *idea/to build*

### Ideas (future / phase 2)
- [ ] **Compare tool** (Apple-style side-by-side specs) — *phase 2*
- [ ] **Promo/offer banners** in nav + per-machine offer banners (Joel to mock up) — *idea*
- [ ] **Parts e-commerce** for small replaceable parts (cut service-ticket backlog) — *long-term*

---

## Follow-ups

- Joel to apply action steps and send a status message when done. (This document is the extraction.)
- **More review meetings planned**, same format, deeper on specific sections: a **machines meeting** (machine-page templates, R/H/Q comparison), a **service meeting** (Get Support depth), and a **landing-pages meeting** (gating policy, "Understand the Investment" content). Each gets its own dated file here.
