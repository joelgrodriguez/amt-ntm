---
name: shogun
description: Coordinate Shogun-managed coding work with GitHub issue tasks, blocked-by dependencies, file reservations, agent messages, validation, and PR review handoff. Use automatically in any repo with .shogun/config.json when the user asks to create or work on a task, ticket, issue, feature, bug, chore, TODO, or implementation plan, even if Shogun is not named.
---

# Shogun

## Contract

Use `.shogun/README.md` and `.shogun/config.json` as the local contract. Tasks are GitHub issues driven through the `gh` CLI; Shogun branches from `dev` and validates with `npm run build`.

Task creation is mandatory in Shogun-installed repos. If the user asks to create a task, ticket, issue, feature, bug, chore, TODO, or implementation plan and does not provide an existing issue number or URL, run `shogun task create` before code work. Do this even if the user does not say "Shogun".

`shogun task create "<goal>"` opens the GitHub issue and puts it in the `Staged` column of the configured Projects board. The issue number it prints is the task ID for every later command. Dependencies go in the issue's `## Blocked by` section via `--blocked-by 12,14`; a task is ready only when every blocker is closed.

Do not merge into `dev` from an agent worktree. Commit, validate, run `shogun task review <n>`, and stop.

If `.shogun/config.json` says `workflow.mode` is `mainline`, do not manually merge or direct-land. Queue the branch with `shogun queue add <n> --branch <branch>`, let `shogun queue run` land through validation/CI, then use `shogun task accept` or `shogun task revert --commit <merge-sha>`.

## Knowledgebase

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before unfamiliar feature work. Use `docs/architecture/index.html` for the visual architecture/flow map.

Run `shogun map` when files, routes, entrypoints, tests, commands, boundaries, or documented flows change. Run `shogun map --check` before review; stale architecture docs mean the task is not done.

## Start Work

1. Run `shogun task ready --json`. These are the open, unstarted, unblocked tasks.
2. Pick one. If none are ready, inspect blockers with `shogun graph`.
3. Start it with `shogun task start <n>`. This refuses blocked tasks, creates a worktree on branch `<n>-<kebab-title>` from `dev`, and moves the board to `Processing`.
4. Reserve files before editing: `shogun reserve add <n> <paths...> --agent <name> --ttl 2h`.
5. If a reservation conflicts, do not edit those files. Send a message and pick another ready task.

## Plain Feature Requests

If the user asks for a feature, decompose it yourself. Do not make the user write a giant orchestration prompt.

1. If the user did not provide an existing issue number or URL, read the architecture knowledgebase and inspect the relevant code first.
2. Create 1-6 small issues with `shogun task create`, one per vertical slice, ordered by real dependencies with `--blocked-by`. Capture each issue number it prints. If task creation fails, run `shogun doctor` and stop instead of working untracked.
3. Then run the normal Start Work loop on `shogun task ready`.

The `## Blocked by` sections are the order of operations. Keep slices small enough that another agent can grab the next ready issue without reading your mind.

## During Work

- Keep edits inside the claimed task's goal.
- Reserve extra files before touching them.
- Use `shogun message send "..." --to <agent|coordinator> --from <name> --task <n>` for blockers, handoffs, or decisions.
- Check your inbox with `shogun message list --to <name> --unread`.
- If genuinely blocked on a decision, add the blocker as an issue and wire it with `shogun task update <n> --blocked-by <blocker>`.

## Finish Work

1. Run the validation command.
2. Commit the work on the task branch.
3. Run `shogun task review <n> --summary "..."`. It opens (or updates) the PR -- body starts with `Fixes #<n>` -- and moves the board to `Reviewing`.
4. In mainline mode, queue instead: `shogun queue add <n> --branch <branch>`.
5. Release reservations with `shogun reserve release <n>`.

Landing is `shogun task land <n>` from the base checkout (human or coordinator): it merges the PR, closes the issue, moves the board to `Verifying`, and comments on each newly unblocked issue. `approve` is the only command that moves a task to `Done`.

## Judgment

The blocked-by DAG is the order of operations. Reservations are collision control. Messages are coordination memory. If any of those disagree with the user's direct instruction, stop and surface the conflict.
