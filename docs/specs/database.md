# Behavior spec: database

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Harden destructive DB migration identity guards so a fresh production pull cannot cause unrelated content or the wrong WooCommerce product to be modified. — #111
*Landed 2026-07-18 · type: bugfix*

- Migration 046 accepts only page 20405 with the expected page identity and either empty content or an iframe whose normalized source host is exactly `readinessassessment.b.abacusai.app`; unknown non-empty content fails loudly and remains untouched.
- Migration 046 verifies the `_wp_page_template` write succeeded before clearing the known legacy iframe content.
- Migration 049 requires keeper 18732 to be a published `product` named `UNIQ™ Automatic Control System UNQ-SSQ3-A`, slugged `uniq-automatic-control-system`, with exact regular price `22500.00` before drafting anything.
- Migration 049 still verifies duplicate 2799 is the expected `product`, title, slug `uniq-control-system`, and exact regular price `21700.00`.
- Both migrations remain idempotent, default to read-only, and return nonzero on a dangerous identity or write failure.
- `npm run build` passes.
