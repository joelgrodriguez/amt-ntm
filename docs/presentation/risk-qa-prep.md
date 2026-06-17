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
live in the database and don't change with the theme. So a bad launch is recoverable, and we
can stage the go-live rather than flip everything at once.
**[Joel to confirm: your exact rollback mechanism — is it "re-activate the old theme," a git
revert + redeploy, or a staging→prod promotion you can reverse? Say the real one.]**

### Q: "Will we lose SEO / break existing links?"
No — this was a design rule from day one. **Existing URLs stay alive.** `/machines/`,
`/seamless-gutter-machines/`, `/profiles/`, every product page, every Learning Center post —
they all keep their URLs and get reached *through* the new navigation, not replaced by it.
(Documented: `docs/handoff/02-the-change.md` line 29, and `docs/handoff/05-pages-to-reuse.md` —
a "do NOT touch their URLs" list.) The nav is a new front door to the same rooms.

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
theoretical, we can watch the analytics after go-live and adjust — this isn't a one-way door.
**[Joel to confirm: do you have analytics/heatmaps set up to actually watch post-launch
behavior? If yes, name the tool. If no, "we'll instrument it" is the honest answer.]**

### Q: "Why change what already works? What's the ROI?"
Two concrete wins, not vibes. (1) The old finance page was — Thad's words — "literally a black
hole": two paragraphs and a contact form. It's now a full Finance Center that routes buyers to
every way to pay. (2) The configurator removes the "contact us for pricing" stall — buyers get
a real number themselves, which qualifies them before sales ever picks up the phone. The whole
redesign trades "more clicks into a catalog" for "a clearer path to a qualified quote."
**[Joel to confirm: any baseline metric you can cite — current quote volume, bounce rate, or
conversion — so "better" has a number behind it? Even a rough one helps.]**

### Q: "What's the cost and timeline risk?"
Go-live target is **end of June**. The build is largely done; what's left is content and
integration, not core development. The real open dependencies (and who owns them) are:
machine pricing (Rick), finance-info accuracy (Kathy + Terry), direct service requests
(NetSuite / Seligo), and content tags + final images (Alex). The risk isn't "will the site
get built" — it's "will those inputs land in time," which is partly why I'm here asking for
your help unblocking them.
**[Joel to confirm: is end-of-June still real given where the open items stand? If it's
slipping, say so here — better than being caught.]**

### Q: "What if the financing information is wrong, or creates a legal problem?"
Two safeguards. (1) An accuracy review with Kathy (NTM Finance) and Terry is **already on the
schedule** before go-live — financing content doesn't ship unchecked. (2) The page is explicit
that NTM is a **guide, not a lender**: it carries a disclaimer that financing agreements are
between the buyer and the lender, and tells buyers to read the paperwork and ask their loan
agent about rates and terms. We route to lenders; we don't make financing promises.
(Source: 06-05, Finance Center; disclaimer in `app/templates/pages/finance-center/`.)

### Q: "Who maintains this after launch?"
**[Joel to confirm: this is yours to answer — is it you, an internal team, an agency, a
retainer? Have a real answer; "we'll figure it out" reads as a gap to leadership.]**

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
