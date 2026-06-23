# Behavior spec: machines

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Build reusable 5-Pillar Ironclad Support strip + wire into pillar, category & machine pages — #37
*Landed 2026-06-23 · type: feat*

- Build reusable 5-Pillar Ironclad Support strip + wire into pillar, category & machine pages

## A production-ready trailer landing page — "NTM trailer vs. traditional" — at route `/machines/upgrades/trailer`. Adam, stakeholder review 2026-06-17 (mentioned twice, on his checklist): the NTM trailer is expensive *because* it's engineered for the machine (bolt-down, chip trays, heavy-duty). We're not telling that story; it pairs with the SSQ3 "trailer sold separately" asterisk (#34) by explaining *why* the trailer costs what it does.

Lead framing: **engineered-as-one-system** (the trailer is designed AROUND the machine; a generic trailer can't be). — #36
*Landed 2026-06-23 · type: feature*

- Page template + section parts built, production-quality, mobile-first.
- Hero + vs-traditional comparison + engineering features (the 7 reasons) + the two trailer models + pricing tie-in + final CTA.
- Real images/content from the source article + products.
- WP page created at `/machines/upgrades/trailer` with template assigned, captured as a replayable db script.
- `npm run build` passes.

## Lead SSQ3 pricing with the **$85K base machine price** + asterisk "trailer sold separately", not the trailer-inclusive $130K–$143K range. Adam, stakeholder review 2026-06-17 (his biggest single content callout): competitor On Roll/KWM quotes minus the trailer, so our trailer-inclusive range looked more expensive. Joel chose $85K (2026-06-22).

Pricing is data-driven: `app/data/machines/ssq3-multipro.php` `finance.price_range` feeds every render (configurator-cta, hero, cta-finance, comparison) framed as "Starting at". A data change fixes all surfaces at once. Hero shows the bare value (no note slot), so the asterisk lives on the value itself; configurator-cta + cta-finance render the note. — #34
*Landed 2026-06-23 · type: fix*

- `finance.price_range` leads with $85K base (asterisk on value for the hero).
- `finance.note` carries "trailer sold separately" + config caveat.
- All "Starting at" surfaces reflect the base price (no template change needed).
- `npm run build` passes.

## Remove the "which machine" quiz CTA from the **gutter** landing page only. Adam: "Remove pick your machine quiz on the gutter landing pages." Leave it on the other two pages that include it. — #32
*Landed 2026-06-23 · type: chore*

- `app/page-seamless-gutter-machines.php:39` — drop the `which-machine` `get_template_part` include.
- `app/page-machines.php` and `app/page-roof-wall-panel-machines.php` keep their includes (scope = gutter only).
- `npm run build` passes.
