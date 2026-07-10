# Plan 008: Repo hygiene — dead parts, truthful README, vestigial dist config, stale agent docs

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- README.md .distignore .gitattributes CLAUDE.md scripts/release/dev-tooling-paths.txt app/templates/parts app/templates/pages/machines`
> On any change since this SHA to a file you are about to edit/delete,
> re-verify the claims below before proceeding.

## Status

- **Priority**: P2
- **Effort**: S–M
- **Risk**: LOW
- **Depends on**: none
- **Category**: tech-debt / docs
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

Four hygiene problems mislead humans and agents: (1) seven template parts are dead code that ships to production; (2) `README.md` is the unmodified "Standard Press" starter doc — wrong name, wrong layout, wrong menu model — and it's the ONE doc that survives the release strip; (3) `.distignore` + a `.gitattributes` export-ignore block describe a distribution mechanism nothing uses (the real strip list is `scripts/release/dev-tooling-paths.txt`), so adding a path there silently does nothing; (4) `CLAUDE.md` still points agents at `.shogun/` (renamed to `.admiral/`), shows WP-CLI examples that fatal (missing the `php8.3` pin), and describes fonts as "Bunny Fonts" when they are self-hosted. Also: the new `plans/` directory must be added to the release strip list so it never ships to master.

## Current state

- **Dead template parts** (zero references at plan time; each was checked against BOTH `get_template_part('...full-path')` style AND the two-arg `get_template_part('...stem', 'variant')` style):
  - `app/templates/parts/configurator.php`
  - `app/templates/parts/configurator-cta.php` (mentions in other files are prose inside doc-comments only; the live configurator part is `app/templates/pages/finance-center/configurator.php` — do NOT touch that one)
  - `app/templates/parts/front-page/who-is-ntm.php` (front-page.php loads hero-slider, explore-machines, quiz, portability-dna, why-own, flagships, social-proof, learning-center, tools, three-step-plan, final-cta — not who-is-ntm)
  - `app/templates/parts/hero-asymmetric.php`
  - `app/templates/parts/post-hero.php`
  - `app/templates/parts/service-hub/machine-card.php`
  - `app/templates/pages/machines/image-break.php`
  - NOT dead (two-arg loads — do NOT delete): `templates/parts/content-none.php` (`get_template_part('templates/parts/content', 'none')` in index.php:32, archive.php:226, template-articles.php:85) and `templates/parts/content-search.php` (search.php:196, archive.php:220).
- **README.md** — starts `# Standard Press`, documents `resources/`, `inc/`, `templates/` at repo ROOT (real layout is under `app/`), claims "three registered menus" (nav is hardcoded PHP in `app/inc/desktop-nav.php`/`mobile-nav.php` by design), no mention of DevKinsta, `db:apply`, or the release pipeline.
- **`.distignore`** — root-level paths (`resources/`, `safelist.txt`) that don't exist at root; `grep -rn distignore scripts package.json vite.config.js` → no consumers. The real mechanism: `scripts/release/strip-dev-tooling.sh` reads `scripts/release/dev-tooling-paths.txt`.
- **`.gitattributes`** — contains an `export-ignore` block (lines ~36-44) with the same phantom root paths; `git archive` is not the deploy path. The rest of the file (diff/linguist attrs, if any) stays.
- **`scripts/release/dev-tooling-paths.txt`** — ends with `docs` (no `plans` entry). Format: one path per line, `#` comments.
- **CLAUDE.md** (dev-only, stripped at release):
  - Lines ~109-110 and later (~366, ~370, ~399): "Use `.shogun/README.md` as the local workflow guide" and `.shogun/active.md`/`archive.md` references — the tooling was renamed; the directory is `.admiral/`.
  - Lines ~206-208, "Preferred inspection commands": `docker exec devkinsta_fpm wp --path=... option get home --allow-root` — fatals, because the container's default CLI PHP is 7.4 and the theme uses `match()`. Working form: `docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech option get home --allow-root`.
  - Architecture section: "`app/inc/fonts.php`: Bunny Fonts" — stale; `fonts.php`'s own header says "Self-hosted font preloads."

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| Reference check (per dead part) | see Step 1 | zero hits |
| PHP lint (nothing should need it) | — | — |

(Worktree has no `node_modules`; do not run npm.)

## Scope

**In scope**:
- Delete: the seven dead part files listed above (after re-verification)
- Rewrite: `README.md`
- Delete: `.distignore`; remove the stale `export-ignore` block from `.gitattributes`
- Edit: `CLAUDE.md` (three specific fixes only), `scripts/release/dev-tooling-paths.txt` (add `plans`)

**Out of scope** (do NOT touch):
- `templates/parts/content-none.php`, `content-search.php` — ALIVE via two-arg loads.
- `app/templates/pages/finance-center/configurator.php` — the live configurator.
- CLAUDE.md content beyond the three named fixes (no restructuring, no dedup of its repeated sections).
- Any other file under `app/`.

## Git workflow

- Branch: `advisor/008-repo-hygiene`
- Commits: one for deletions, one for docs (or a single commit) — subject like: `Remove dead template parts; fix README, dist config, and stale agent docs`
- Use `git rm` for deletions. Do NOT push.

## Steps

### Step 1: Re-verify then delete each dead part

For EACH of the seven files, run both checks (substitute the path stem):

```bash
# exact-path references (should return nothing but the file itself):
grep -rn "front-page/who-is-ntm" app --include='*.php' | grep -v "parts/front-page/who-is-ntm.php"
# two-arg variant loading (stem without the -variant suffix), e.g. for hero-asymmetric:
grep -rn "get_template_part('templates/parts/hero'" app --include='*.php'
```

Two-arg stems to check: `parts/hero` (for hero-asymmetric), `parts/post` (for post-hero), `parts/configurator` (for configurator-cta), `service-hub/machine` (for machine-card), `machines/image` (for image-break), `parts/front-page/who-is` (defensive). If EVERY check is empty for a file → `git rm` it. If ANY check hits → leave that file, note it in your report.

**Verify**: `git status --porcelain | grep '^D' | wc -l` → `7` (or fewer, with each survivor explained in your report).

### Step 2: Rewrite README.md

Replace the full contents with an accurate, prod-safe doc (~60-90 lines). Must cover: theme name **AMT NTM** (`amt-ntm`, theme root `app/` — the active WP theme is `amt-ntm/app`); requirements (PHP 8.1+, WordPress 6.0+, Node 18+); build commands (`npm install`, `npm run dev`, `npm run build`, `npm run lint:php` if present on your branch — check `package.json` first); directory layout (root = build tooling, `app/` = theme: `inc/`, `templates/parts/`, `templates/pages/`, `resources/js|css`, `woocommerce/`, `dist/`); asset pipeline in two sentences (Vite manifest in prod, dev server via `app/.vite-dev-server`, `is_vite_dev()`); navigation = hardcoded PHP in `app/inc/desktop-nav.php`/`mobile-nav.php` (nav menus deliberately unregistered); fonts = self-hosted Noto (latin subsets) preloaded by `app/inc/fonts.php`; local dev = DevKinsta at `/Users/jrodriguez/Development/Kinsta/public/newtech` with the `php8.3`-pinned WP-CLI form; DB-state capture rule in two sentences (DB is not git-controlled; replayable scripts in `scripts/db/` + `db/redirects.json`, applied with `npm run db:apply`); release model in two sentences (`dev` integration → `npm run release:master` publishes stripped `master`). Do NOT document agent orchestration (that's CLAUDE.md's job, and it's stripped from master).

**Verify**: `grep -c 'Standard Press' README.md` → `0`; `grep -c 'app/' README.md` → `≥3`; `head -1 README.md` names the actual theme.

### Step 3: Remove the vestigial dist config

- `git rm .distignore`
- In `.gitattributes`, delete only the `export-ignore` lines that reference paths (the stale block); keep any `*.png binary`-style attribute lines that exist. If the file becomes empty, `git rm` it too.

**Verify**: `.distignore` gone; `grep -c 'export-ignore' .gitattributes 2>/dev/null || echo 0` → `0`.

### Step 4: Fix CLAUDE.md (three surgical edits)

1. Replace every `.shogun/` path reference with `.admiral/` (`grep -n '\.shogun' CLAUDE.md` first; replace path references — leave any prose explicitly describing the historical rename intact if present).
2. In the "Preferred inspection commands" block, change each `docker exec devkinsta_fpm wp --path=...` to `docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=...` and add one comment line above the block: `# php8.3 pin required: the container's default CLI PHP is 7.4 and fatals on this theme's match() syntax.`
3. Change the fonts line in the PHP-structure list from Bunny Fonts wording to: `app/inc/fonts.php`: self-hosted Noto font preloads (latin subsets).`

**Verify**: `grep -c '\.shogun' CLAUDE.md` → `0`; `grep -c 'php8.3 /usr/local/bin/wp' CLAUDE.md` → `≥2`; `grep -ci 'bunny' CLAUDE.md` → `0`.

### Step 5: Keep `plans/` off master

Append to `scripts/release/dev-tooling-paths.txt`, under the "Planning / design / internal docs" comment section:

```
plans
```

**Verify**: `grep -c '^plans$' scripts/release/dev-tooling-paths.txt` → `1`.

## Test plan

No test harness applies. Gates are the greps above. Reviewer post-merge runs `npm run build` (deleted parts must not be referenced by anything the build scans — safelist/`@source` scanning tolerates deletions) and spot-loads the front page + a service-hub page on the served checkout.

## Done criteria

- [ ] Up to 7 dead parts deleted, each with clean re-verification (survivors documented)
- [ ] README.md rewritten (greps in Step 2 pass)
- [ ] `.distignore` deleted; `.gitattributes` has no stale export-ignore block
- [ ] CLAUDE.md: no `.shogun`, php8.3-pinned WP-CLI examples, no Bunny reference
- [ ] `plans` present in dev-tooling-paths.txt
- [ ] `git status --porcelain` contains only in-scope paths

## STOP conditions

- Any dead-part re-verification grep returns a hit (skip that file, continue with the rest, report it).
- `.gitattributes` contains export-ignore lines pointing at paths that DO exist (e.g. `app/...`) — that would mean someone wired it up since the audit; stop and report.
- CLAUDE.md's `.shogun` references exceed ~10 hits (structure changed; report instead of mass-replacing).

## Maintenance notes

- Future dead-code sweeps: the two-arg `get_template_part` pattern is the trap that almost claimed `content-none.php`; always grep both forms.
- `dev-tooling-paths.txt` is the single source of truth for what master ships. Anything agent- or planning-related goes there, not `.distignore`.
