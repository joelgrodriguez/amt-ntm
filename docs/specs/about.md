# Behavior spec: about

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Add one discreet "our parent company" link to AMT (Architectural Metals Technologies, https://archmettech.com/) on the About page. Adam's call, stakeholder review 2026-06-17: one understated link, NO AMT/Sheffield branding on the NTM site — "you make them travel to get there." Resolves the long-running Mazzella→AMT rewording thread.

Cleanest spot: the "Company data" aside in `app/templates/parts/about/origin.php` already has a "Founded → Denver, 1991" callout row. Add a second row "Parent company → AMT" as a quiet external link. The callout renderer currently `esc_html`s the value, so add optional `href`/`label` support to render an anchor for this one row.

Keep `p2`'s "independent American manufacturer" framing — it's the right tone, don't dilute it. — #35
*Landed 2026-06-23 · type: feature*

- One "Parent company" callout linking to https://archmettech.com/ (rel/target appropriate for an external link).
- No Sheffield, no AMT logo/branding — just the link.
- Existing "Founded" callout still renders (renderer stays backward-compatible).
- `npm run build` passes.
