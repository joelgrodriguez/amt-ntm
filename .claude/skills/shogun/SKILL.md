---
name: shogun
description: Coordinate Shogun-managed Orca coding work with GitHub issue tasks, blocked-by dependencies, file reservations, agent messages, validation, and local review handoff. Use automatically in any repo with .shogun/config.json when the user asks to create or work on a task, ticket, issue, feature, bug, chore, TODO, or implementation plan, even if Shogun is not named.
---

# Shogun

## Contract

Use `.shogun/README.md` and `.shogun/config.json` as the local contract. Tasks are GitHub issues driven through the `gh` CLI; Orca owns the spawned worktrees, terminals, and browser tabs; Shogun branches task work from the `dev` integration branch and validates with `npm run build`. Protected branches such as `main` and `master` are not Shogun development bases.

Task creation is mandatory in Shogun-installed repos. If the user asks to create a task, ticket, issue, feature, bug, chore, TODO, or implementation plan and does not provide an existing issue number or URL, run `shogun task create` before code work. Do this even if the user does not say "Shogun".

`shogun task create "<goal>"` opens the GitHub issue and adds `status:staged`. The issue number it prints is the task ID for every later command. Dependencies go in the issue's `## Blocked by` section via `--blocked-by 12,14`; a task is ready only when every blocker is closed.

Do not merge into `dev` from an Orca agent worktree. Do not run raw `git push`, `git merge`, `shogun task land`, or `shogun task cleanup --apply` from a spawned task worktree. Commit, validate, run `shogun task review <n>`, and stop.

If `.shogun/config.json` says `workflow.mode` is `mainline`, do not manually merge or direct-land. Queue the branch with `shogun queue add <n> --branch <branch>`, let `shogun queue run` land through validation/CI, then use `shogun task accept` or `shogun task revert --commit <merge-sha>`.

## Knowledgebase

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before unfamiliar feature work. Use `docs/architecture/index.html` for the visual architecture/flow map.

Run `shogun map` when files, routes, entrypoints, tests, commands, boundaries, or documented flows change. Run `shogun map --check` before review; stale architecture docs mean the task is not done.

A durable behavior spec accretes at `docs/specs/<area>.md` as tasks land — a newest-first log of what each area is supposed to do. Read the spec for your task's area before changing existing behavior; browse any area with `shogun spec <area>` or list them with `shogun spec`. It is generated automatically on land; never hand-edit it.

## Start Work

1. Run `shogun task ready --json`. These are the open, unstarted, unblocked tasks.
2. Pick one. If none are ready, inspect blockers with `shogun graph`.
3. Start it with `shogun task start <n>`. This refuses blocked tasks, asks Orca to create a worktree on branch `<n>-<kebab-title>` from `dev`, and moves the issue to `Processing`. Route the worktree's model per work type (load the `route` skill): `shogun task start <n> --agent-command "<full launch command>"` launches a flagged agent (model, effort, bypass) that a bare `--agent` id can't express. Pass `--agent-command` more than once to run several collaborating agents in the one worktree (e.g. one on the UI, one on the DB).
4. Reserve files before editing: `shogun reserve add <n> <paths...> --agent <name> --ttl 2h`.
5. If a reservation conflicts, do not edit those files. Send a message and pick another ready task.

## Plain Feature Requests

If the user asks for a feature, decompose it yourself into a ladder of small, independently landable issues. Do not make the user write a giant orchestration prompt. Each issue should be understandable to a human, verifiable by an agent, and useful before the whole feature is done.

1. **Burn the fog first.** If the user did not provide an existing issue number or URL, read the architecture knowledgebase and inspect the relevant code before asking anything. When the feature is unfamiliar, the area is new to you, or "good" is something you'd only recognize on sight, load the `explore-unknowns` skill and walk the four quadrants — settled ground, the answerable questions (interview one at a time, biggest architectural blast-radius first), the taste to surface with a react-to-it prototype, and a blindspot pass for landmines. The output is a reviewed map. Don't slice from a foggy map.
2. **Slice at API seams.** Each slice should behave like a tiny library: a named module boundary, typed inputs/outputs, and a test at the seam. If a slice needs three unrelated systems booted before it can be checked, sharpen the seam. One concern per slice; if a slice hides multiple variables or a broad verb ("make it robust", "add the backend"), reslice it until the next issue can be accepted or rejected by one focused check.
3. **Create 1-6 issues** with `shogun task create`, one per vertical slice, ordered by real dependencies with `--blocked-by`. Capture each issue number it prints. Fill the enriched issue body: **What to build** (the contract), **API seam** (module / typed I/O / ownership), **Acceptance criteria**, **Verification** (the exact commands that prove it and what stays green), **Blocked by**. Leave **Rationale** blank until the slice lands. If task creation fails, run `shogun doctor` and stop instead of working untracked.
4. **Then run the normal Start Work loop** on `shogun task ready`.

The `## Blocked by` sections are the order of operations. Keep slices small enough that another agent can grab the next ready issue without reading your mind. If implementation hits a snag and a slice starts changing unrelated things, stop broadening the patch — split the issue, re-wire `--blocked-by`, and resume. Reslicing is progress, not failure. As each slice lands, fill its issue's `## Rationale` from the implementation Deviations log — the *why*, the invariants, the dead ends — so `docs/specs/<area>.md` accretes a why-record, not just a what-log.

## During Work

- Keep edits inside the claimed task's goal.
- Reserve extra files before touching them.
- Use `orca worktree set --worktree active --comment "..." --json` for meaningful progress checkpoints.
- Use `shogun message send "..." --to <agent|coordinator> --from <name> --task <n>` for blockers, handoffs, or decisions.
- Check your inbox with `shogun message list --to <name> --unread`.
- Use `shogun task report <n>` for handoff/status: it combines GitHub, Orca,
  git changed files, inferred agent roles, reservations, messages, and recent
  local Shogun events. Human-readable reports are Markdown headed `Session
  Report` with the content in a table.
- Use `shogun task report <n> --since-last` after an agent answer when you need
  the delta since the previous report; Shogun uses Orca terminal read cursors
  and includes commits, changed files, recent output, and launched agents.
- If `task sync` or `task report` says Fable appears blocked by spend limits,
  credits, auth, or a trust prompt, run
  `shogun agent fallback <n> --to "claude --model opus --effort xhigh --dangerously-skip-permissions"`.
  This is Shogun/conductor supervision, not Claude's in-process fallback; do not
  silently auto-respawn.
- If genuinely blocked on a decision, add the blocker as an issue and wire it with `shogun task update <n> --blocked-by <blocker>`.

## Finish Work

1. Run the validation command.
2. Commit the work on the task branch.
3. Run `shogun task review <n> --summary "..."`. In the default local landing workflow it only moves the issue to `Reviewing`; it does not push and does not open a PR. If the project explicitly sets `workflow.landing` to `pr`, Shogun keeps the older PR review behavior.
4. In mainline mode, queue instead: `shogun queue add <n> --branch <branch>`.
5. Release reservations with `shogun reserve release <n>`.

Landing is `shogun task land <n>` from the clean `dev` integration checkout (human or coordinator): it locally merges the task branch, validates, commits `Land #<n>: <title>`, and moves the issue to `Verifying`. It does not push. `approve` is the only command that closes the issue, moves a task to `Done`, and comments on each newly unblocked issue; cleanup is manual after `Done` with `shogun task cleanup <n> --dry-run` then `shogun task cleanup <n> --apply`.

Before landing or handing off a multi-agent task, run `shogun task report <n>`
to see which agents were launched, their inferred lanes (planning, reading,
implementation, verification, marketing), and whether reservations/messages or
recent events need attention.

## Judgment

The blocked-by DAG is the order of operations. Reservations are collision control. Messages are coordination memory. If any of those disagree with the user's direct instruction, stop and surface the conflict.
