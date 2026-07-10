# Plan 001: Add a PHP lint gate + minimal CI so a syntax error can never ship

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- package.json scripts/release/to-master.sh docs/deploy/prod-content-merge-plan.md .github/`
> If any in-scope file changed since this plan was written, compare the
> "Current state" excerpts against the live code before proceeding; on a
> mismatch, treat it as a STOP condition.

## Status

- **Priority**: P1
- **Effort**: S–M
- **Risk**: LOW
- **Depends on**: none
- **Category**: dx
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

This WordPress theme is about to launch to production. The only pre-deploy gate today is `npm run build` (asset bundling — it never parses PHP). The theme uses PHP 8.0+ syntax (`match()` in `app/inc/woo/catalog.php:120`), runs `declare(strict_types=1)` everywhere, and has ~325 PHP files loaded through 40 `require`s in `app/functions.php`. One syntax error or bad include = white-screen on a lead-gen site, with zero automated detection. A `php -l` sweep plus a minimal GitHub Actions workflow closes the exact failure class most likely to take the site down. A production PHP-version verification gate is also added to the cutover runbook: the theme fatals on PHP < 8.0, and nothing currently verifies the production web PHP version.

## Current state

- `package.json` (repo root) — scripts block has build/db/release entries only, no lint:

```json
"scripts": {
  "dev": "vite",
  "build": "vite build",
  "db:apply": "scripts/db/apply",
  "content:export": "php scripts/content/export-copy.php",
  "content:xlsx": "php scripts/content/export-xlsx.php",
  "preview": "vite preview",
  "release:strip": "scripts/release/strip-dev-tooling.sh",
  "release:clean": "scripts/release/clean-dev-tooling-worktree.sh",
  "release:hooks": "scripts/release/install-git-hooks.sh",
  "release:master": "scripts/release/to-master.sh"
}
```

- `.github/` does not exist. No CI of any kind.
- `scripts/release/to-master.sh` — the release script; it runs `npm run build` (around line 37) as its only validation before publishing to master. Read the file before editing; anchor on the line invoking the build.
- `docs/deploy/prod-content-merge-plan.md` — the cutover runbook. Section `## 4. Runbook` begins at line ~120; `### Phase R — Rebase local DB onto prod copy` at line ~143. Line ~154 already notes: `# 3. Domain fix (use php8.3 for wp-cli — default CLI php is 7.4 and fatals on the theme)`. There is no gate verifying the **production web** PHP version.
- Host PHP is 8.3 (Herd): `php -v` → `PHP 8.3.24 (cli)`.
- Repo conventions: plain bash scripts under `scripts/`, `set -euo pipefail` headers (see `scripts/db/000-template.sh.example`).

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint one file | `php -l app/functions.php` | `No syntax errors detected` |
| Full lint (after step 1) | `npm run lint:php` | exit 0, `PHP lint OK` |
| Node present | `node -v` | v18+ |

Note: your worktree has no `node_modules`. Do NOT run `npm install` or `npm run build` — the reviewer runs the build post-merge. `npm run lint:php` works without install because it only shells out to `php`.

## Scope

**In scope** (the only files you may modify/create):
- `package.json` (add one script)
- `.github/workflows/ci.yml` (create)
- `scripts/release/to-master.sh` (insert one gate line)
- `docs/deploy/prod-content-merge-plan.md` (insert one checklist gate)

**Out of scope** (do NOT touch):
- Any file under `app/` — this plan adds gates, it does not fix code.
- `package-lock.json`, `node_modules`, any other release script.
- PHPCS/PHPStan setup — explicitly deferred (no composer stack in this repo yet).

## Git workflow

- Branch: `advisor/001-php-lint-ci` (create from the worktree's HEAD: `git switch -c advisor/001-php-lint-ci`)
- Commit style: short imperative subject, e.g. `Add PHP lint gate and minimal CI` (match `git log --oneline`: "Guard TOC rail against heading-less posts").
- Do NOT push. Do NOT open a PR.

## Steps

### Step 1: Add `lint:php` script to package.json

Add to the `scripts` block (keep JSON valid, keys in the existing order with `lint:php` after `build`):

```json
"lint:php": "find app -name '*.php' -not -path '*/node_modules/*' -print0 | xargs -0 -P 4 -n 20 php -l > /dev/null && echo 'PHP lint OK'"
```

**Verify**: `npm run lint:php` → prints `PHP lint OK`, exit 0.
**Verify failure detection**: `echo '<?php if (' > /tmp/lint-canary.php && php -l /tmp/lint-canary.php; echo "exit=$?"` → non-zero exit (proves `php -l` signals errors); then `rm /tmp/lint-canary.php`.

### Step 2: Create `.github/workflows/ci.yml`

```yaml
name: CI
on:
  push:
    branches: [dev, master]
  pull_request:
jobs:
  php-lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - run: npm run lint:php
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: npm
      - run: npm ci
      - run: npm run build
```

**Verify**: `python3 -c "import yaml,sys; yaml.safe_load(open('.github/workflows/ci.yml'))" && echo YAML-OK` → `YAML-OK` (if PyYAML is unavailable, verify with `ruby -ryaml -e "YAML.load_file('.github/workflows/ci.yml'); puts 'YAML-OK'"`; if neither exists, note it in your report — do not install anything).

### Step 3: Gate the release script

In `scripts/release/to-master.sh`, immediately BEFORE the line that runs the production build (`npm run build`), insert:

```bash
npm run lint:php
```

Preserve the file's existing error-handling style (it should already `set -e` or check exit codes — read the header; if it does not fail on error, wrap as `npm run lint:php || { echo "PHP lint failed — aborting release"; exit 1; }`).

**Verify**: `grep -n 'lint:php' scripts/release/to-master.sh` → one match, on a line number smaller than the `npm run build` line (`grep -n 'npm run build' scripts/release/to-master.sh`).

### Step 4: Add the production PHP-version gate to the runbook

In `docs/deploy/prod-content-merge-plan.md`, inside section `## 4. Runbook`, directly under the `### Phase R — Rebase local DB onto prod copy (rehearsal now, repeat at cutover)` heading, insert this checklist line as its own paragraph before the numbered steps:

```markdown
> **Gate (before cutover):** In MyKinsta, confirm the LIVE and STAGING
> environments run **PHP ≥ 8.1**. The theme uses PHP 8.0+ syntax
> (`match()` in `app/inc/woo/catalog.php`) and white-screens on PHP 7.4 —
> the local wp-cli php8.3 pin (step 3 below) only protects CLI, not the
> web runtime.
```

**Verify**: `grep -n 'PHP ≥ 8.1' docs/deploy/prod-content-merge-plan.md` → one match between the `## 4. Runbook` line and the `# 1. Backups` line.

## Test plan

No unit tests (this repo has none and this plan adds tooling, not logic). The verification gates above are the test: lint passes on the current tree, lint detects a canary syntax error, YAML parses, release script ordering is correct.

## Done criteria

- [ ] `npm run lint:php` exits 0 and prints `PHP lint OK`
- [ ] `.github/workflows/ci.yml` exists and parses as YAML
- [ ] `grep -c 'lint:php' scripts/release/to-master.sh` → `1`, positioned before the build
- [ ] `grep -c 'PHP ≥ 8.1' docs/deploy/prod-content-merge-plan.md` → `1`
- [ ] `git status --porcelain` shows only the four in-scope files changed/created

## STOP conditions

Stop and report back (do not improvise) if:

- `scripts/release/to-master.sh` has no line invoking `npm run build` (the release flow changed).
- `docs/deploy/prod-content-merge-plan.md` has no `### Phase R` heading.
- `npm run lint:php` fails on the CURRENT tree (that means a real pre-existing syntax error — report the file; do not fix it).
- `package.json` scripts block differs materially from the excerpt above.

## Maintenance notes

- Any future PHPStan/PHPCS adoption (see the `wp-phpstan` skill) should hang off the same `lint:php`-style npm script and the same CI file.
- If the repo moves to mainline CI-gated landing (`admiral queue run`), this workflow is the validation it runs.
- Reviewer should scrutinize: the xargs batch flags (`-P 4 -n 20`) — output interleaving is fine since stdout is discarded; failures still propagate via xargs exit code 123.
