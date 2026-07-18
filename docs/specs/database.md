# Behavior spec: database

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Make `DRY_RUN=1 npm run db:apply` genuinely read-only across every numbered migration, and stop masking real write failures in the legacy scripts touched by this fix. — #110
*Landed 2026-07-18 · type: bugfix*

- `DRY_RUN=1` dispatches every numbered migration without changing WordPress posts, metadata, terms, options, redirects, media files/metadata, caches, or rewrite state.
- Scripts 014, 020, 021, 022, 024, 026, 031, 032, 033, 036, 037, and 038 report intended actions instead of writing.
- Broad `|| true` handling is removed from write paths in those scripts; expected read-side no-match cases remain explicit, while real WP-CLI failures stop the replay.
- Real-mode writes check command results or verify the expected post-write state instead of assuming success.
- A repeatable repository-owned regression check proves the dry-run contract against the disposable local WordPress database and detects future numbered migrations that omit dry-run handling.
- `npm run build` passes.

## Harden destructive DB migration identity guards so a fresh production pull cannot cause unrelated content or the wrong WooCommerce product to be modified. — #111
*Landed 2026-07-18 · type: bugfix*

- Migration 046 accepts only page 20405 with the expected page identity and either empty content or an iframe whose normalized source host is exactly `readinessassessment.b.abacusai.app`; unknown non-empty content fails loudly and remains untouched.
- Migration 046 verifies the `_wp_page_template` write succeeded before clearing the known legacy iframe content.
- Migration 049 requires keeper 18732 to be a published `product` named `UNIQ™ Automatic Control System UNQ-SSQ3-A`, slugged `uniq-automatic-control-system`, with exact regular price `22500.00` before drafting anything.
- Migration 049 still verifies duplicate 2799 is the expected `product`, title, slug `uniq-control-system`, and exact regular price `21700.00`.
- Both migrations remain idempotent, default to read-only, and return nonzero on a dangerous identity or write failure.
- `npm run build` passes.
