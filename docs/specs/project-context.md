# Behavior spec: project-context

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Establish the Standard-inspired project contract using repository evidence. Create or complete PRODUCT.md, CONTEXT.md, and docs/decisions/README.md so future Admiral agents can understand product intent, shared domain language, system boundaries, invariants, sources of truth, and how architectural decisions are recorded. — #115
*Landed 2026-07-18 · type: docs*

- Create or fill PRODUCT.md from durable repository evidence; preserve useful existing content rather than replacing it.
- Create or fill CONTEXT.md with project terminology, actors, core entities, ownership boundaries, external systems, invariants, sources of truth, and known ambiguities.
- Create or fill docs/decisions/README.md with a lightweight decision-record contract appropriate to this repository; preserve any existing decision history.
- Explicitly mark unknowns and ambiguities instead of inventing facts.
- Keep the documents concise, project-specific, and useful to development agents.
- Do not change product or runtime behavior.
- Run the configured repository validation command and make it pass.
- Run the Admiral architecture map check when architecture mapping is enabled.
- Commit the work and hand it off with Admiral task review.
