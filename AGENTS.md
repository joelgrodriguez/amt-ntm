# AGENTS.md

Instructions for every agent working in the New Tech Machinery (NTM) theme repo.
This is the only instruction file; do not add a `CLAUDE.md`. Global rules in
`~/.agents/AGENTS.md` (tone, testing policy, model routing) still apply.

## The one rule: work on `dev`

All work happens on `dev` or on a feature branch cut from `dev`. `master` is
not a working branch. It only receives `dev` through the release script, and
exists to ship the theme to staging and then production.

- Before any edit, run `git branch --show-current`. If it prints `master`,
  switch to `dev` first.
- Never commit, edit, or fix anything directly on `master`, even a one-line
  hotfix. Fix it on `dev`, then release.
- GitHub's default branch is `master`, so tools may suggest it as the base.
  Branch from `dev` and target `dev` anyway.
- Changes flow one way: feature branch → `dev` → `master`. Never backwards.

## Read first

Stop when you have enough context for the task.

1. `README.md` — layout, build commands, asset pipeline, release, deployment.
2. `PRODUCT.md` — what the site is for and what it deliberately does not do.
3. `CONTEXT.md` — domain terms, ownership boundaries, external systems.
4. `docs/specs/<area>.md` — current behavior of the area you are touching.
5. `DESIGN.md` — the visual system, for UI work.

## Branches: what `dev` keeps and `master` strips

- `dev` is where we develop. It keeps everything: this file, `CONTEXT.md`,
  `PRODUCT.md`, `DESIGN.md`, `KINSTA_DEPLOYMENT_RESEARCH.md`, `.agents/`,
  `docs/`, `plans/`, `db/`, and `scripts/db/`.
- `master` holds only what production needs. `npm run release:master` merges
  `dev` into `master`, then deletes every path listed in
  `scripts/release/dev-tooling-paths.txt`.
- A new dev-only file or folder must be added to that list, or it ships.
- Never merge, fast-forward, rebase, or cherry-pick `master` into `dev`.
  `master` carries "strip dev-only agent tooling" commits; pulling them into
  `dev` deletes the dev tooling. This happened on July 25, 2026.
- If this file is missing on `dev`, stop and report it.

## Git

- Work on a feature branch cut from `dev`. Merge finished work into `dev`.
- `master` is touched only by `npm run release:master`. Never
  `git push origin dev:master`.
- A push to `dev` or `master` runs `.github/workflows/ci.yml`. A push to
  `master` also deploys to Kinsta staging. Check with Joel before pushing.

## Release and deploy

```bash
git switch master
git pull --ff-only origin master
RELEASE_TARGET_BASE_URL=https://<kinsta-staging-host> npm run release:master
git switch dev
```

- The release deploys the theme (`app/`) to Kinsta **staging** only. It never
  deploys the database, uploads, or plugins.
- Production is a manual MyKinsta selective push by Joel. Do not attempt it.
- The release deletes `docs/` from the working tree, including git-ignored
  files. `*.log` is git-ignored, so commit test evidence with
  `git add -f docs/test-evidence/<file>` before releasing.

## Local site

- DevKinsta serves the main checkout at `https://newtech.local`, not
  worktrees. Browser checks and E2E tests must run from the main checkout.
- WP-CLI needs the `php8.3` pin (see `README.md`).
- The local nginx returns 503 when requests arrive too fast. Pace scripted
  requests about 1.5 seconds apart.

## Database changes

The database is not in git, and a fresh production pull wipes local changes.
Capture every DB-side change as an idempotent script in `scripts/db/` (or
`db/redirects.json` for redirects) and replay with `npm run db:apply`. See
`scripts/db/README.md`. Staging and production databases change only when
Joel runs those scripts there.

## Code

- PHP: `declare(strict_types=1)`, `Standard\…` namespaces, includes registered
  in `app/functions.php`.
- Machine content, specs, and pricing live in `app/data/machines/`. Machine
  lifecycle (for example a discontinued model and its replacement) lives in
  `app/inc/machine-status.php`.
- Navigation is hardcoded in `app/inc/desktop-nav.php` and
  `app/inc/mobile-nav.php`.
- Tailwind-first: use utilities in templates. Write custom CSS only for what
  Tailwind cannot express.
- Mobile-first: unprefixed utilities are the mobile baseline; scale up with
  `sm:` / `md:` / `lg:`. Touch targets are at least 44×44px.
- Icons: `<?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>`.
- Before layout work, read `.agents/skills/spacing-system.md`. Before type
  work, read `.agents/skills/typography-system.md`.

## Tests

- `npm run lint:php` and the `test:*` scripts in `package.json` run locally
  without WordPress.
- E2E scripts (`test:*-e2e`) run in real Chrome against the local site and
  write evidence to `docs/test-evidence/`.
- Write the failing test before the code, and save both the failing and
  passing runs.
