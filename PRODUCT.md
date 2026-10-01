# Product

## Register

brand

## Users

The site speaks to working contractors first, with distributors and larger metal-roofing companies reading over their shoulder.

- **Primary: independent contractor and crew-owner.** Owns a roofing, gutter, or sheet-metal crew. Buying an NTM machine is a five- to six-figure decision they make personally. ROI-driven, skeptical of marketing, trusts peer video over brochures. Often 40+, frequently 50+. Uses the site from a phone in a truck, a shop laptop, or a desktop after hours.
- **Secondary: estimator or owner at an established roofing / metal panel company.** Bigger purchase committee. Vets legitimacy on the about page before sending the link to the boss.
- **Tertiary: international distributor or dealer.** Different conversation: reliability, parts pipeline, training, brand prestige. Must read credibly beyond the US.

Across all three, the job is the same: *quickly verify that NTM is the real thing* — the company you'd bet a major capital purchase on, with machines backed by a serious operation.

## Product Purpose

newtech-ntm.com is the marketing site for **New Tech Machinery (NTM)**, the world's leading manufacturer of portable rollforming machines (metal roof panel machines, seamless gutter machines, and the control systems that run them). NTM invented the modern portable roof panel category (SSP, 1991) and portable seamless gutter category (MACH II, 1994). Aurora, Colorado HQ; Hermosillo, Mexico plant (2004); Mazzella Companies since 2015; machines in 40+ countries.

The site converts serious shoppers into specialist conversations: configurator sessions, quote requests, and dealer/distributor calls. It is not a transactional storefront. Every surface reduces perceived risk and makes the next conversation easier.

Marketing surfaces (about and peers) do three jobs: plant the leadership flag, show longevity and stability, and read like the same company that built the machine pages. Aesthetic coherence is part of the proof.

## Outcomes

Success is a started conversation, never a completed checkout. Five surfaces carry the outcome (flows in `docs/architecture/flows.json`):

| Path | Ends at |
|---|---|
| **Buyer funnel** — home → Choose Your Machine → category → machine page | Open Configurator (external Corbel) or Explore Financing |
| **Lead capture** — any lead surface, contact band, or specialist CTA | HubSpot form → CRM |
| **Service request** — `/service-hub/` → scoped search → request form | HubSpot service request |
| **Learning Center** — filtered library → article → machine tags | Back into the buyer funnel |
| **Finance Center** — `/machines/leasing-financing/` | Configurator quote *or* a specialist |

Navigation is ordered by buyer intent — aware → consider → decide → own — not catalog structure. That is the product decision; nav PHP files implement it.

## Boundaries

- **In scope:** marketing, education, spec/price presentation, owner self-serve support, routing a qualified visitor into a form or the configurator.
- **Not a storefront.** WooCommerce models the machine catalog (products, categories, tags, permalinks), not a purchase path. Machine pricing lives in `app/data/machines/`, not Woo. The machine template removes add-to-cart; `app/inc/woo/setup.php` is catalog-only. *Unverified:* whether cart/checkout pages themselves are disabled at the WordPress level (DB state).
- **Configurator is external (Corbel).** The theme owns the link and short configurator slug, not the quote experience.
- **CRM is external (HubSpot).** Every form is a HubSpot embed; the theme does not process submissions.
- **Financing percentages are reviewed copy** (`docs/specs/finance-center.md`, `docs/legal/`).
- **Safety and certification claims are legally reviewed.** Do not strengthen CE/NATM language without sign-off (`docs/legal/compliance-claims-review.md`).

## Brand Personality

**Pioneering. Grounded. Precision-built.**

- **Pioneering, not pioneering-in-the-past.** Leadership is current tense. 1991 is the receipt for a claim still made today, not nostalgia.
- **Grounded, not rugged.** American-manufactured, multi-generation trust — industrial-premium, not industrial-rugged. Cybertruck, not Wrangler.
- **Precision-built.** Spec-sheet aesthetic with premium restraint. Zero ornament, mono-editorial labeling, hairline-blueprint structure, full-bleed product photography.

Voice: plainspoken, opinionated, confident without bragging. Sentences a contractor would say. Never corporate, sentimental, or overpolished. Leadership claims land as fact, not slogan.

## Anti-references

- **Corporate handshake / B2B stock.** No hardhats-shaking-hands, boardrooms, or generic "Our Story" photography.
- **Dirt-and-sparks industrial cliches.** No mud, sparks, grime, harsh orange light. The category defaults here; we do not.
- **Generic SaaS About template.** No big-number metric heroes, identical team grids, three-icon value stacks.
- **Heritage-brand sepia nostalgia.** No vintage type, faux-letterpress, "Since 1991" as craft sentiment.
- **Mazzella-as-the-headline.** Acquisition is a stability proof point, not the protagonist.

## Design Principles

Strategic principles; visual rules live in `DESIGN.md`.

1. **The machine is the brag.** Lead with equipment, firsts, country count, years — never mission statements or values posters.
2. **Engineered, not corporate.** If a paragraph could ship on any B2B site, rewrite it.
3. **Leadership as fact, not slogan.** Back claims with dated proof: SSP 1991, MACH II 1994, 40+ countries, category firsts.
4. **Aesthetic coherence is a proof point.** Marketing pages share the machine-page visual grammar. Drift reads as sloppiness.
5. **Restraint scales; ornament doesn't.** One accent, two fonts, zero radius, no shadows. Quiet pages read older and more trusted.

## Accessibility & Inclusion

WCAG AA across the site. Practical bars:

- Body copy minimum 16px (buyers skew 50+, often phones in sunlight).
- Color contrast meets AA; aim higher on dark-section body copy near the line.
- Generous mobile tap targets; CTAs are not 32px buttons.
- `prefers-reduced-motion` is enforced (`DESIGN.md` §9).
- Real heading hierarchy and landmarks on every page.
- Video that carries content load includes captions or transcripts.
