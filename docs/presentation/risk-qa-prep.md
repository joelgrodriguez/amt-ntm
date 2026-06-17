# Risk & Objection Prep — "what if things go bad"

The tough questions leadership may throw, each with a crisp answer you can say out loud.
These are **not** in any feedback doc — built for this presentation. Answers are grounded in
repo facts where possible; anything I couldn't verify is flagged **[Joel to confirm]** —
fill those in before you present, don't wing them.

Posture: you're asking for buy-in on direction + showing progress, not launch approval. So
the honest answer to most "what if" questions is "here's how we de-risk it," not "it can't
fail." Confidence comes from having thought about failure, not from pretending it's impossible.

---

### Q: "What's our rollback plan if this tanks?"
The site is a **theme** — the new design is code we control, versioned in git. Reverting is a
code rollback, not a rebuild: we can put the previous theme back. Content, products, and URLs
live in the database and don't change with the theme. The old site is saved and stored — we
can roll back to it whenever we want. But honestly, a full rollback isn't what we'd do. Every
change is documented, so when something looks off we fix the specific thing, not revert the
whole launch. Most "issues" are small and known — e.g. we removed the Learning Center bucket
from the top nav, but the pages and URLs are all still live; that's a nav decision, not a loss.
The full rollback exists as the safety net; the day-to-day plan is targeted, documented fixes.

### Q: "Will we lose SEO / break existing links?"
No — if anything, we've helped it. Three concrete reasons, all verifiable in the codebase:
1. **Existing URLs stay alive.** `/machines/`, `/seamless-gutter-machines/`, `/profiles/`, every
   product page, every Learning Center post — they keep their URLs and are reached *through* the
   new nav, not replaced. (Documented: `docs/handoff/02-the-change.md` line 29 + the "do NOT
   touch their URLs" list in `05-pages-to-reuse.md`.) Removing the Learning Center *bucket* from
   the top nav didn't remove the pages — the content and URLs are untouched.
2. **We added structured data.** Machine pages now emit `Product` + `FAQPage` JSON-LD schema
   (`app/inc/machine-schema.php`), and FAQPage schema runs on 10+ pages (Finance Center, Start
   Here, roof-vs-gutter, MACH II, Service Hub). That makes us eligible for rich results in Google
   — more SEO surface than the old site, not less.
3. **We fixed keyword + meta hygiene.** Normalized "Roll Forming" → "Rollforming" (one word) for
   consistent keyword targeting (commit `a934943`), and caught pages that shipped with no meta
   title/description and set proper ones (commit `a66b9f0`, /start-here/).
The honest framing: we didn't delete content, we reorganized navigation while preserving every
URL, and we strengthened the technical SEO underneath. **[Joel can optionally confirm with Rick
whether there's a target keyword list driving this; the technical improvements stand on their own.]**

### Q: "Why isn't the configurator front and center? Aren't we hiding our best feature?"
Deliberate, and it's already how the site is built. A configurator is a **bottom-of-funnel
tool** — it answers "how do I spec the machine I've chosen," not "which machine is right for
me." Put it on the homepage and most visitors open it without knowing what they want: they
get overwhelmed and bounce, or build the wrong machine and generate a bad-fit quote — which
makes *more* work for sales, not less. So we guide people to the right machine first (browse
or the quiz), and the configurator lives on the machine page, next to "Talk to a Specialist."
Thad and I discussed the trade-off directly: **fewer quotes, but far better-qualified ones.**
(Source: 06-03, "Configurator placement reasoning." Shipped — the old homepage configurator
strip was replaced by the quiz CTA, commit `b66d6f7`, now in `origin/dev`.)

### Q: "What if the new navigation confuses our existing customers?"
The new nav is organized around the buyer's journey, and it's the **same four labels on phone
and desktop** — one mental model, nothing to relearn. Existing URLs still work, so anyone with
a bookmark or a Google result lands exactly where they expect. And because it's launched, not
theoretical, we're watching it. We have **Google Analytics** for behavior and traffic and
**Microsoft Clarity** for session recordings and heatmaps, both in place — plus GA is wired to
AI for daily reports. So we'll track search activity, user paths, and 404s, and can adjust on a
daily basis if anything looks off. This isn't a one-way door — we launch, we watch, we tune.

### Q: "Why change what already works? What's the ROI?"
Two concrete wins, not vibes. (1) The old finance page was — Thad's words — "literally a black
hole": two paragraphs and a contact form. It's now a full Finance Center that routes buyers to
every way to pay. (2) The configurator removes the "contact us for pricing" stall — buyers get
a real number themselves, which qualifies them before sales ever picks up the phone. The whole
redesign trades "more clicks into a catalog" for "a clearer path to a qualified quote." Add the
SEO improvements above (structured data, keyword + meta fixes) and we've widened the top of the
funnel and clarified the bottom.

On the configurator specifically — anecdotally, in a recent campaign we saw roughly **10 quotes
started but only ~4 finished**. I don't have the why for certain, but my working theory: buyers
who already know these machines build one with no problem; first-time buyers hit options
mid-configuration they don't understand and drop off. That's exactly why guiding people to the
right machine *first* (and keeping "Talk to a Specialist" one click away on every machine page)
matters — it catches the buyer who'd otherwise abandon. We'll watch this in GA/Clarity post-launch
to confirm or kill the theory.
*(Note: the 10→4 figure is from a meeting recollection, not a captured metric — present it as
anecdotal, not as documented data. No hard baseline for quote volume / conversion exists yet;
GA + Clarity will establish one going forward.)*

### Q: "What's the cost and timeline risk?"
Target go-live is **July 1st** (approximately). The build itself is largely done — the site's
about 75–80% — what's left is content, training, and migration, not core development. The real
open items:
- **Pillar-page content** — I'm putting the new content into a spreadsheet for Rick to review
  and edit as he sees fit; he hands it back and I run a script to update the pages. (Matches the
  captured "content discovery / accuracy review" actions in the feedback docs.)
- **Team training** — sales and service need a walkthrough before launch; ~two weeks to do it.
  Plan: a live session + a recorded video. Sales via their Friday tacticals; service via a
  managers' call plus a video to everyone else. Rick helps schedule it. (See the maintenance/
  training answer below.)
- **A short content-freeze at launch** — I'll need a couple of days where no one edits the site
  while I migrate content from the service website and apply URL/redirect changes. I'll give the
  team advance notice to make any edits beforehand. This is the one real bottleneck.
- Plus the standing items: finance accuracy (Kathy + Terry), direct service requests
  (NetSuite / Seligo, phase 2), final images (Alex got strong Q3 shots; a return trip planned
  for more).
The risk isn't "will the site get built" — it's "will the content review and training land on
schedule," which is partly why I'm here. There's no big external cost; I'm the developer.

### Q: "What if the financing information is wrong, or creates a legal problem?"
Two safeguards. (1) An accuracy review with Kathy (NTM Finance) and Terry is **already on the
schedule** before go-live — financing content doesn't ship unchecked. (2) The page is explicit
that NTM is a **guide, not a lender**: it carries a disclaimer that financing agreements are
between the buyer and the lender, and tells buyers to read the paperwork and ask their loan
agent about rates and terms. We route to lenders; we don't make financing promises.
(Source: 06-05, Finance Center; disclaimer in `app/templates/pages/finance-center/`.)

### Q: "Who maintains this after launch?"
I do — I'm the sole developer. That means I can iterate, adapt, and ship changes as we go, no
external dependency or agency turnaround. Combined with the GA + Clarity monitoring, we can spot
something on a Monday and have it fixed the same week. The flip side worth naming: it's a
one-person bus factor — so the documented changes, replayable DB scripts, and architecture docs
exist precisely so the work isn't locked in my head.

### Q: "How do we get the sales and service teams ready?"
A deliberate, two-format rollout before launch — and we have ~two weeks. **A live session plus a
recorded video** so it sticks and so anyone who misses the meeting still gets it:
- **Sales** — easiest to get live; join one of their Friday tacticals (this Friday or next).
- **Service** — a call with the service managers, plus a video out to everyone else.
I'll ask **Rick to help schedule** both. The point: the teams should be continually aware July 1
is coming and walk in already knowing the new nav and the configurator flow on day one.

### Q: "Is it accessible / will it work on every device?"
Yes — mobile-first by rule (most contractors are on a phone in a truck), with big touch
targets, keyboard navigation, and screen-reader support built into the navigation. The design
scales from a 375px phone up to desktop, not the other way around.

---

### General playbook for a curveball you didn't prep
- **Don't bluff.** "Good question — I don't have that number in front of me; I'll get it to you
  by [day]." That builds more trust than a confident wrong answer.
- **Redirect to direction.** Win condition is buy-in on the direction. "Let me log that — can
  we keep the lens on whether the overall direction is right?"
- **Name the de-risk, not the guarantee.** "Here's how we'd catch it / reverse it," beats
  "that won't happen."
