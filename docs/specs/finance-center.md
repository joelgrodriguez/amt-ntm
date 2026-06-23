# Behavior spec: finance-center

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Remove the 36% high-end APR figure from the Finance Center. Finance percentages are a compliance surface (Adam: "We can't show 36%"). Audited — only two files carry it. — #31
*Landed 2026-06-23 · type: fix*

- `app/templates/pages/finance-center/faq.php:28-29` — rewrite the answer so it no longer states "8% to 36% APR". Keep it truthful without naming the high-end rate.
- `app/templates/pages/finance-center/corbel.php:38-40` — the `APR range` row value `'8–36%'` no longer shows 36%.
- `npm run build` passes.
