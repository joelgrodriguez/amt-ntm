# Decision Records

Durable *why* for choices that outlive the task that made them. Not a heavyweight
ADR process — one place for decisions that keep getting re-litigated.

## What already records what

Write a decision record only when none of these covers it.

| Artifact | Records | Written by |
|---|---|---|
| `docs/specs/<area>.md` | *What* each landed task changed (newest-first) | `admiral` on land — **never hand-edit** |
| GitHub issue `## Rationale` | *Why* one slice went that way | Landing agent |
| `plans/README.md` rejections | Audit findings deliberately **not** acted on | Advisor |
| `docs/architecture-boundaries.md` | Layer rules and reasoning | Hand-maintained |
| `DESIGN.md` / `PRODUCT.md` / `CONTEXT.md` | Visual system; product intent; domain invariants | Hand-maintained |
| `docs/superpowers/specs/*.md` | Standing strategy (e.g. DB persistence) | Hand-maintained |
| `docs/audits/`, `docs/legal/`, `docs/feedback/` | Investigations, compliance, stakeholder calls | Per-task |

## When to write a record here

Write one when **all three** are true:

1. Expensive or disruptive to reverse (URL scheme, source of truth, plugin boundary, release mechanic).
2. Spans more than one task — no single issue `## Rationale` owns it.
3. A reasonable agent could undo it by accident. If the code makes it obvious, the code is the record.

Do **not** write one for bug fixes, copy, visual tweaks, one-file refactors, or anything already in `DESIGN.md` / `PRODUCT.md` / `CONTEXT.md`. Rule of thumb: explaining the same "no, we tried that" to a second agent → write the record.

## Format

One file: `docs/decisions/NNN-kebab-title.md`, under a page.

```markdown
# NNN. <Decision, stated as the thing that is now true>

**Status:** Accepted | Superseded by NNN | Reversed
**Date:** YYYY-MM-DD
**Task:** #<issue>

## Context
What forced the choice — constraint, failure, or conflict.

## Decision
What we do now. Present tense, specific enough to obey.

## Alternatives rejected
- **<Option>** — why not. Include disproof if one exists.

## Consequences
What this costs, forbids, and must stay true.
```

Rules: never delete (supersede instead); cite evidence (paths, issues, measurements); mark open questions explicitly; index the record in the same commit.

## Index

*No standalone decision records yet.* Standing decisions live in the artifacts named — promote one here only when it meets the three-part test.

| Standing decision | Recorded in |
|---|---|
| Activated theme is `amt-ntm/app`, not repo root | `README.md`, `CLAUDE.md` |
| Navigation is hardcoded PHP; no WP nav menus | `app/inc/desktop-nav.php`, `app/inc/mobile-nav.php` |
| DB not in git; durable DB changes are replayable files | `docs/superpowers/specs/db-persistence-strategy.md` |
| Launch pushes dev DB up — not a fresh prod pull that wipes local state | same, §Launch-day reconcile |
| Theme/Woo owns Product schema; Schema Pro for FAQ + Video only | `docs/specs/seo.md`, schema-pro audit |
| Retired product-category archives 301 to designed landings | `docs/specs/woo.md`, `docs/specs/seo.md` |
| Trailer page at `/machines/trailer/` (Woo permalink collision) | `docs/specs/machines.md` |
| Tailwind-first; custom CSS only where utilities cannot express it | `CLAUDE.md`, `DESIGN.md` |
| Zero radius, no shadows, two fonts | `DESIGN.md` §1 |
| AMT linked once, discreetly; no AMT/Sheffield branding | `docs/specs/about.md` |
| `master` releases strip dev-only tooling and docs | `scripts/release/dev-tooling-paths.txt`, `docs/specs/release.md` |

## Related

- `.admiral/README.md` — task lifecycle; where `## Rationale` is filled.
- `docs/architecture/map.json` — machine-readable repo map (`admiral map`).
