# CLAUDE.md

Claude is the primary agent for this repo. Other agents should read this same file through symlinks:

- `AGENTS.md` for Codex-style agents
- `.agents/skills` points to `.claude/skills`

If an agent reads this file through another name, same rules. No alternate personalities hiding in the couch cushions.

## Vibe

- Never open with Great question, I'd be happy to help, or Absolutely. Just answer.
- Brevity is mandatory. If the answer fits in one sentence, use one sentence.
- Have opinions. Strong ones. Commit to a take and explain the tradeoff when it matters.
- Delete corporate filler. If it sounds like an employee handbook, cut it.
- Humor is allowed when it lands. Do not force jokes.
- Call out bad ideas early. Charm over cruelty, but do not sugarcoat.
- Swearing is allowed when it earns its spot. Do not overdo it.
- Never gaslight the user. If something is uncertain, say what is unknown and how to prove it.
- Be the assistant you'd actually want to talk to at 2am. Not a corporate drone. Not a sycophant. Just... good.

## How To Work

- Act like a high-performing senior engineer: concise, direct, execution-focused.
- Prefer simple, maintainable, production-friendly solutions.
- Write low-complexity code that is easy to read, debug, and modify.
- Keep APIs small, behavior explicit, and naming clear.
- Avoid cleverness unless it clearly improves the result.
- Use convention over configuration. Rails got this right.
- Add a short teaching line when a hard subject appears. One useful sentence beats a lecture.
- When researching options, give pros, cons, tradeoffs, and the recommended choice.
- Do not add heavy abstractions, extra layers, or large dependencies for small features.

## Git Workflow

This repo uses three branch roles:

- `master`: release branch only.
- `dev`: integration branch.
- `feat/*`, `fix/*`, `chore/*`: worktree branches.

Rules:

- Worktrees start from `dev`.
- Worktrees may push only their own remote feature branch.
- Worktrees must not push to `origin/dev` or `origin/master`.
- The `dev` checkout is the only place that pushes `origin/dev`.
- The `master` checkout is the only place that pushes `origin/master`.
- Merge completed worktree branches into `dev`.
- Release by merging `dev` into `master`, then pushing `master`.
- No `git push origin dev:master`. It works technically and is still a bad habit.

Recommended commands:

```bash
git switch dev
git pull --ff-only origin dev
git worktree add .worktrees/feature-name -b feat/feature-name dev
```

When feature work is done:

```bash
git switch dev
git pull --ff-only origin dev
git merge --no-ff feat/feature-name -m "Merge feature-name worktree updates into dev"
git push origin dev
```

Release:

```bash
git switch master
git pull --ff-only origin master
git merge --ff-only dev
git push origin master
```

## Permissions

- On any branch except `master`, proceed with normal coding, inspection, build, and test commands without needless permission prompts.
- Still ask before deleting files, force pushing, resetting history, changing secrets, or running destructive commands.
- On `master`, slow down. Only release merge and release push work belongs there.
- Do not edit, delete, or reorganize unrelated files while on `master`.

## Design Principles

### Mobile-first, always

Every UI change starts at the smallest viewport and scales up. No exceptions.

- Author base styles for mobile. Use Tailwind utilities **without** a breakpoint prefix as the mobile baseline.
- Scale up with `sm:` / `md:` / `lg:` / `xl:` prefixes only. Never use `max-*:` prefixes to scale down from a desktop default.
- When reviewing a design or screenshot, ask "what does this look like at 375px wide?" before touching code. If you don't know, ask.
- Test mobile first in the browser. If it works on mobile and breaks on desktop, that's a smaller fix than the reverse.
- Touch targets ≥ 44×44px. Tap, not hover, is the primary interaction.
- Prefer single-column layouts at base, grids/multi-column at `md:` and up.

This applies to every spawned worktree doing UI work too.

## Shogun & Worktree Agents

This repo uses Shogun for GitHub-issue-backed worktree orchestration. GitHub
issues are the task source of truth; the configured GitHub Projects board's
Status field tracks `Staged -> Processing -> Reviewing -> Verifying -> Done`.
The `dev` checkout lands reviewed work. Spawned worktrees commit, validate,
run `shogun task review <n>`, and stop.

Use `.shogun/README.md` as the local workflow guide. `.shogun/active.md` and
`.shogun/archive.md` are local notes only. If your agent supports skills, load
the Shogun skill from `.agents/skills`, `.claude/skills`, or
`.opencode/skills` before taking Shogun work.

Task creation is mandatory here. If the user asks to create a task, ticket,
issue, feature, bug, chore, TODO, or implementation plan and did not provide an
existing issue number or URL, create it first with `shogun task create`. Do
this even when the user does not say "Shogun". Do not work untracked.

`shogun task create "<goal>"` opens the GitHub issue, applies Shogun labels,
and puts it in the `Staged` board column. Capture the issue number it prints;
that is what `task start`, `task review`, and `task land` expect. Dependencies
go in the issue's `## Blocked by` section (`--blocked-by 12,14`); a task is
ready only when every blocker is closed. Pick work with `shogun task ready`,
never by guessing.

Useful commands:

```bash
shogun doctor
shogun task create "<goal>" --type <work-type> --area <area> --agent <agent>
shogun task ready
shogun task start <n>
shogun task review <n> --summary "..."
shogun task land <n>
shogun task approve <n>
shogun task iterate <n>
shogun map
shogun map --check
```

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before
unfamiliar feature work. If files, routes, entrypoints, tests, commands,
boundaries, or documented flows change, run `shogun map` and verify with
`shogun map --check`.

If you are running from `dev`:

- Do not edit theme code from `dev` for feature work. Spawn a worktree.
- Create or update a GitHub issue before spawning a worktree.
- Merge gate: only land branches in `Reviewing`, after the user asks to land or
  pull worktree changes.
- Land with a merge commit from the `dev` checkout, then push `origin/dev` from
  this checkout only.
- After landing, sync `dev` back into every active worktree. Skip dirty
  worktrees and report them.
- `shogun task land <n>` moves landed tasks to `Verifying`; user approval moves
  them to `Done`.

If you are a spawned worktree agent:

- You're running under `~/.superset/worktrees/<name>/` or another worktree path.
- A normal coding request is enough. Create or find the GitHub issue yourself.
- Do your assigned work on your branch. Commit frequently with clear messages.
- Never merge into `dev` or `master`. Never push `origin/dev` or
  `origin/master`. Pushing your own feature branch is fine.
- Keep the issue current when possible. If the CLI is unavailable, put the
  task-ready details in your final response.
- Before editing code, move the task to `Processing` with `shogun task start`.
- When your task is done, validate with `npm run build`, run
  `shogun task review <n> --summary "..."`, and stop.
- If you hit a blocker or ambiguity, stop and surface it rather than guessing.

## Shared Skills

Shared repo skills live in `.claude/skills`.

Agents that do not know Claude's layout should read `.agents/skills`, which points to the same directory.

- Before touching layout-heavy frontend code, read `.agents/skills/spacing-system.md`.
- Before writing or editing fonts, type, headings, eyebrows, or any class touching `font-mono`/`font-sans`, read `.agents/skills/typography-system.md`.
- Before creating worktrees, merging feature work, or releasing, read `.agents/skills/git-worktree-flow.md`.

Global Claude skills are available at `/Users/jrodriguez/.claude/skills`. Use them when the task matches:

- WordPress work: `wp-project-triage`, `wordpress-router`, `wp-wpcli-and-ops`, `wp-performance`, `wp-rest-api`, `wp-plugin-development`, `wp-block-development`, `wp-block-themes`, `wp-interactivity-api`, `wp-abilities-api`, `wp-phpstan`, `wpds`
- Frontend/design work: `frontend-design`, `canvas-design`
- Ruby/Rails work: `dhh-rails-programmer`, `dhh-rails-architecture`, `dhh-ruby-style`, `dhh-code-reviewer`

Do not copy global skills into this repo. Read the source skill when needed, then act.

## Local WordPress

This project runs locally in DevKinsta.

- WordPress root: `/Users/jrodriguez/Development/Kinsta/public/newtech`
- Theme root: `/Users/jrodriguez/Development/Kinsta/public/newtech/wp-content/themes/amt-ntm`
- `wp-config.php` lives at the WordPress root and contains the local DB settings.
- DevKinsta containers usually include `devkinsta_nginx`, `devkinsta_fpm`, `devkinsta_db`, `devkinsta_adminer`, and `devkinsta_mailhog`.
- Use WP-CLI through the DevKinsta PHP container when WordPress state matters.
- Do not print DB passwords, salts, auth keys, or other secrets in chat or logs unless the user explicitly asks. Even locally, leaking secrets is still sloppy.

Preferred inspection commands:

```bash
docker ps
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech option get home --allow-root
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech option get siteurl --allow-root
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech theme list --allow-root
```

### Hard rule: capture all DB-side changes

The theme is git-controlled. **The database is not.** Releasing means merging the
theme to `master`, pulling a **fresh production DB** locally, and re-adding the
theme from git — which **wipes every local DB change** (slugs, redirects, product
categories/tags, custom flags). The only thing that survives a fresh pull is what
is in git.

Therefore, any time an agent changes DevKinsta DB / WordPress state that must
persist past a fresh prod pull, it MUST also capture that change as a replayable
file in the repo, in the **same task** — no silent DB edits. Channels:

- **WooCommerce catalog data** (categories, tags, slugs, flags) → an idempotent
  WP-CLI script in `scripts/db/` (numbered, safe to re-run). Replayed by
  `npm run db:apply`.
- **Redirects** → export to `db/redirects.json` (Redirection plugin export), not
  hand-scripted.
- **Slug changes** → always two steps: the slug edit script **and** an old→new
  redirect entry. Never a bare slug change.

Inspection-only commands (reading state) need no capture. See
`docs/superpowers/specs/db-persistence-strategy.md` for the full strategy and
`docs/superpowers/specs/data-normalization-backlog.md` for the queued fixes.

## Build Commands

```bash
npm run dev      # Start Vite dev server with HMR (port 5173)
npm run build    # Production build to app/dist/
npm run preview  # Preview production build
```

## Architecture

This is a WordPress classic theme using Vite and Tailwind CSS v4 for modern asset bundling.

### Directory Structure

- Root: build tooling (`package.json`, `vite.config.js`, `node_modules/`)
- `app/`: WordPress theme files (PHP, assets, templates)

### Asset Pipeline

Entry points:

- `app/resources/js/_app.js`: main JS entry, imports CSS and initializes modules
- `app/resources/css/_app.css`: main CSS entry, imports Tailwind and theme styles

Vite integration:

- Development assets are served from the Vite dev server with HMR.
- Production assets use `app/dist/.vite/manifest.json`.
- `app/functions.php` handles both modes through `is_vite_dev()`.

CSS architecture:

- `app/resources/css/theme.css`: design tokens via `@theme`
- `app/resources/css/base.css`: base element styles
- `app/resources/css/components.css`: reusable component classes
- `app/resources/css/utilities.css`: custom utility classes
- `app/resources/css/animations.css`: animation definitions
- `app/safelist.txt`: WordPress block classes preserved in production builds

**CSS authoring rule: Tailwind-first.** Use Tailwind utility classes directly in PHP/HTML templates. Only reach for custom CSS when Tailwind cannot express the style — complex selectors (`:nth-child` stagger delays, `.is-open .parent .child`), pseudo-element tricks (`::after` scrims), or JS-toggled state classes (`.is-closing`, `.is-open`). Never write a custom class just to wrap a single utility.

### PHP Structure

All PHP uses `declare(strict_types=1)` and the `Standard` namespace.

Includes loaded by `app/functions.php`:

- `app/inc/vite.php`: Vite integration
- `app/inc/setup.php`: theme supports and nav menus
- `app/inc/sidebars.php`: widget areas
- `app/inc/fonts.php`: Bunny Fonts
- `app/inc/icons.php`: SVG icon loader with caching
- `app/inc/walkers/`: custom nav menu walkers

Template hierarchy:

- Standard templates: `app/header.php`, `app/footer.php`, `app/index.php`, `app/single.php`, `app/page.php`, `app/search.php`, `app/404.php`
- Template parts: `app/templates/parts/`

### JavaScript Modules

Located in `app/resources/js/modules/`:

- `MobileMenu.js`: full-width mobile menu with header state management
- `ScrollReveal.js`: scroll-based reveal animations

### Icons

SVG icons live in `app/assets/icons/`. Use the `icon()` helper in templates:

```php
<?php icon('arrow--right', ['class' => 'w-5 h-5']); ?>
```

## Development Notes

- Theme requires PHP 8.0+ and WordPress 6.0+.
- Nav menus: `primary`, `mobile`, `footer`.
- The Vite dev server URL is auto-detected.
- When `npm run dev` runs, Vite writes the URL to `app/.vite-dev-server`.
- PHP reads `app/.vite-dev-server` to load dev assets from Docker/DevKinsta or localhost.

## Shogun Workflow

Use `.shogun/README.md` as the local workflow guide. Tasks are GitHub issues;
the configured GitHub Projects board's Status field tracks each task's stage.
Orca owns spawned worktrees, terminals, and browser tabs. `.shogun/active.md`
and `.shogun/archive.md` are local notes only.
If your agent supports skills, load the Shogun skill from `.agents/skills`,
`.claude/skills`, or `.opencode/skills` before taking Shogun work.

Task creation is mandatory here. If the user asks to create a task, ticket,
issue, feature, bug, chore, TODO, or implementation plan and did not provide an
existing issue number or URL, create it first with `shogun task create`. Do
this even when the user does not say "Shogun". Do not work untracked.

`shogun task create "<goal>"` opens the GitHub issue, applies the Shogun
labels, and puts it in the `Staged` board column. Capture the issue number it
prints -- it is what `task start`, `task review`, and `task land` expect.
Dependencies go in the issue's `## Blocked by` section (`--blocked-by 12,14`);
a task is ready only when every blocker is closed. Pick work with
`shogun task ready`, never by guessing.

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before
unfamiliar feature work. If files, routes, entrypoints, tests, commands,
boundaries, or documented flows change, run `shogun map` and verify with
`shogun map --check`.
For plain feature requests, create 1-6 small issues wired with `--blocked-by`
unless the user already gave you an issue number. Then start the first task
`shogun task ready` returns. Do not make the user paste a giant orchestration
prompt.
If Shogun mode is `mainline`, queue completed branches with `shogun queue add`
and let `shogun queue run` land through validation/CI. Do not manually merge.

Agents working in spawned Orca worktrees must commit, validate with
`npm run build`, and run `shogun task review <n>` -- it opens or
updates the PR (body starts with `Fixes #<n>`) and moves the board to
`Reviewing` -- then stop. Do not merge into `dev`; Shogun lands
reviewed work via `shogun task land`.

Use this task flow: `Staged -> Processing -> Reviewing -> Verifying -> Done`.
`Verifying` is the human-QA gate: `task land` merges the PR, closes the issue,
moves the board there, and comments on newly unblocked issues; `approve` is
the only command that marks a task `Done`. If review fails, `task iterate <n>`
reopens the issue and returns it to `Processing`; after `Done`, create a
follow-up task.

Use `orca worktree set --worktree active --comment "..." --json` for meaningful
progress checkpoints, especially before waiting on review or external input.
