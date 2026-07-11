---
name: admiral
description: Coordinate Admiral-managed Orca coding work with GitHub issue tasks, blocked-by dependencies, validation, and local review handoff. Use automatically in any repo with .admiral/config.json when the user asks to create or work on a task, ticket, issue, feature, bug, chore, TODO, or implementation plan, even if Admiral is not named.
---

# Admiral

## Contract

Use `.admiral/README.md` and `.admiral/config.json` as the local contract. Tasks are GitHub issues driven through the `gh` CLI; Orca owns the spawned worktrees, terminals, and browser tabs; Admiral branches task work from the `dev` integration branch and validates with `npm run build`. Protected branches such as `main` and `master` are not Admiral development bases.

Task creation is mandatory in Admiral-installed repos. If the user asks to create a task, ticket, issue, feature, bug, chore, TODO, or implementation plan and does not provide an existing issue number or URL, run `admiral task create` before code work. Do this even if the user does not say "Admiral".

**The ~30-minute rule.** The issue + worktree + spawned-agent + review + land ceremony is fixed overhead that a small change never pays back. If the work is under ~30 minutes of agent effort or confined to one file/concern, skip the ceremony: fix it inline on a short-lived branch off `dev`, validate, and merge — no issue, no worktree, no spawned agent (unless the user explicitly asks for a tracked task). At or above that threshold, or when the work parallelizes or has dependencies, the full Admiral flow is the win.

`admiral task create "<goal>"` opens the GitHub issue with no `status:*` label (open + unlabeled = ready). The issue number it prints is the task ID for every later command. Dependencies go in the issue's `## Blocked by` section via `--blocked-by 12,14`; a task is ready only when every blocker is closed.

Do not merge into `dev` from an Orca captain's worktree. Do not run raw `git push`, `git merge`, `admiral task land`, or `admiral task cleanup --apply` from a spawned task worktree. Commit, validate, run `admiral task review <n>`, and stop. See the installed AGENTS.md Admiral Workflow section for the full hard-rules list, the Session Report table shape, and the two-failures-then-consult rule — this skill does not restate them.

## Knowledgebase

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before unfamiliar feature work — these are the machine-readable knowledgebase agents should parse. `docs/architecture/index.html` is a human browser view of the same data; agents should read the JSON, not the HTML.

Run `admiral map` when files, routes, entrypoints, tests, commands, boundaries, or documented flows change. Run `admiral map --check` before review; stale architecture docs mean the task is not done.

A durable behavior spec accretes at `docs/specs/<area>.md` as tasks land — a newest-first log of what each area is supposed to do. Read the spec for your task's area before changing existing behavior; browse any area with `admiral spec <area>` or list them with `admiral spec`. It is generated automatically on land; never hand-edit it.

## Start Work

1. Run `admiral task ready --json`. These are the open, unstarted, unblocked tasks.
2. Pick one. If none are ready, inspect blockers with `admiral graph`.
3. Start it with `admiral task start <n>`. This refuses blocked tasks, asks Orca to create a worktree on branch `<n>-<kebab-title>` from `dev`, and sets `status:in-progress`. Route the captain's model per work type (load the `route` skill): `admiral task start <n> --agent-command "<full launch command>"` launches a flagged captain (model, effort, bypass) that a bare `--agent` id can't express. Pass `--agent-command` more than once to run a captain squadron in the one worktree (e.g. one on the UI, one on the DB).

## Plain Feature Requests

If the user asks for a feature, decompose it yourself into a ladder of small, independently landable issues. Do not make the user write a giant orchestration prompt. Each issue should be understandable to a human, verifiable by a captain, and useful before the whole feature is done.

1. **Burn the fog first.** If the user did not provide an existing issue number or URL, read the architecture knowledgebase and inspect the relevant code before asking anything. When the feature is unfamiliar, the area is new to you, or "good" is something you'd only recognize on sight, load the `explore-unknowns` skill and walk the four quadrants — settled ground, the answerable questions (interview one at a time, biggest architectural blast-radius first), the taste to surface with a react-to-it prototype, and a blindspot pass for landmines. The output is a reviewed map. Don't slice from a foggy map.
2. **Slice at API seams.** Each slice should behave like a tiny library: a named module boundary, typed inputs/outputs, and a test at the seam. If a slice needs three unrelated systems booted before it can be checked, sharpen the seam. One concern per slice; if a slice hides multiple variables or a broad verb ("make it robust", "add the backend"), reslice it until the next issue can be accepted or rejected by one focused check.
3. **Create 1-6 issues** with `admiral task create`, one per vertical slice, ordered by real dependencies with `--blocked-by`. Capture each issue number it prints. Fill the enriched issue body: **What to build** (the contract), **API seam** (module / typed I/O / ownership), **Acceptance criteria**, **Verification** (the exact commands that prove it and what stays green), **Blocked by**. Leave **Rationale** blank until the slice lands. If task creation fails, run `admiral doctor` and stop instead of working untracked.
4. **Then run the normal Start Work loop** on `admiral task ready`.

The `## Blocked by` sections are the order of operations. Keep slices small enough that another captain can grab the next ready issue without reading your mind. If implementation hits a snag and a slice starts changing unrelated things, stop broadening the patch — split the issue, re-wire `--blocked-by`, and resume. Reslicing is progress, not failure. As each slice lands, fill its issue's `## Rationale` from the implementation Deviations log — the *why*, the invariants, the dead ends — so `docs/specs/<area>.md` accretes a why-record, not just a what-log.

## Audit Output Becomes Tasks

Advisor skills that audit and plan (e.g. `improve`) propose; Admiral disposes. When an audit produces implementation plans — under `plans/` or `advisor-plans/` — do not keep them as a parallel backlog and do not use the advisor's own execute/dispatch variant in an Admiral repo (it bypasses the land pipeline). Convert each selected plan into an issue with `admiral task create`, mapping the plan's content onto the enriched issue body (What to build, Acceptance criteria, Verification commands) and its dependency ordering onto `--blocked-by`. Record the issue number in the plan's index and mark the plan converted so the next audit reconciles instead of re-planning. Execution then goes through the normal Start Work loop with a routed captain. The single source of truth for pending work is the GitHub issues, never a plans directory.

## During Work

- Keep edits inside the claimed task's goal.
- Use `orca worktree set --worktree active --comment "..." --json` for meaningful progress checkpoints.
- Use `gh issue comment <n> --body "..."` for blockers, handoffs, or decisions.
- Use `admiral task report <n>` for handoff/status: it combines GitHub, Orca,
  git changed files, inferred captain roles, and recent local Admiral events.
  Human-readable reports are Markdown headed `Session Report` with the content
  in a table, and include commits, changed files, recent output, and launched
  captains.
- If an agent hits a spend/auth wall, respawn it manually: `admiral task start
  <n> --agent-command "<fallback command from the route table>"`.
- If genuinely blocked on a decision, add the blocker as an issue and wire it with `admiral task update <n> --blocked-by <blocker>`.

## Finish Work

1. Run the validation command.
2. Commit the work on the task branch.
3. Run `admiral task review <n> --summary "..."`. In the default local landing workflow it only sets `status:in-review`; it does not push and does not open a PR. If the project explicitly sets `workflow.landing` to `pr`, Admiral keeps the older PR review behavior.

Landing is `admiral task land <n>` from the clean `dev` integration checkout (human or commodore): it locally merges the task branch, validates, commits `Land #<n>: <title>`, closes the issue (done), removes the `status:*` label, and comments on each newly unblocked issue. It does not push. `admiral task cancel <n>` closes an issue as not planned instead; cleanup is manual after landing with `admiral task cleanup <n> --dry-run` then `admiral task cleanup <n> --apply`.

Before landing or handing off a squadron task, run `admiral task report <n>`
to see which captains were launched, their inferred lanes (planning, reading,
implementation, verification, marketing), and whether recent events need
attention.

## Judgment

The blocked-by DAG is the order of operations. If it disagrees with the user's direct instruction, stop and surface the conflict.

## Judgment layer

Absorbed from the retired done-check / escalate / orchestrate skills (2026-07-06). Three contracts; `route` (or the config `router`) picks every model — never pick one here.

**Mission loop** (for a real task driven end to end): draft the plan (arch/plan lane) → attack it with a *different* vendor ("this will be executed — find where it's wrong or underscoped") → execute in an Orca worktree via `admiral task start --agent-command` with a `STATUS:` self-report line in the preamble → watch (below) → cross-vendor review → done gate (below). Announce seat assignments in one line, then proceed. For long unattended runs, drive from Codex to save Claude tokens; drive from Claude when taste work (design/copy/docs) is in the chain.

**Supervision** (a spawned agent is running): poll `orca terminal show` previews — the preview sees the boxed composer; the raw tail does not. `STATUS: DONE` → done gate. `STATUS: STUCK` / no output ~5 min (longer for xhigh reasoning — thinking ≠ stalled) / repeated identical error → re-route; a looping vendor won't un-loop itself, so re-route crosses vendors. **Credit/quota wall needs BOTH**: the terminal is dead (`connected`/`writable`/`lastOutputAt` from `orca terminal list --json`, not error text alone) AND an exhaustion string is the CLI's own last output (confirm with `orca terminal read` if it scrolled past). Then respawn the seat's routed fallback in the SAME worktree with the SAME task: `admiral task start <n> --agent-command "<fallback command from the route table>"`. Announce the swap in one line. Novel failure → surface, don't guess. Leave the dead terminal for forensics.

**Done gate** (before advancing finished work): three requirements — validation green (delegate to a verifier, don't run it in the judging seat), cross-vendor review clean (`reviewer ≠ author`, different vendor), no P0 open. All three met → land to `dev` and notify in one line after (`✓ task #14 → dev — tests green, cross-review clean, no P0`). Anything else — inconclusive check, P2/P3 judgment calls, any P0, novel ambiguity — surface with the specific reason and stop. Never auto-advance to `master`/`main` or push remote.
