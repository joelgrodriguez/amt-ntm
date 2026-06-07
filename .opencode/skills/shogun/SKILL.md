---
name: shogun
description: Coordinate Shogun-managed multi-agent coding work with task graphs, file reservations, agent messages, validation, and review handoff. Use when working in a project installed with Shogun, handling Shogun/Superset/Orca tasks, reserving files, coordinating multiple agents, or moving work through review.
---

# Shogun

## Contract

Use `.shogun/README.md` and `.shogun/config.json` as the local contract. This project uses `superset`, branches from `dev`, and validates with `npm run build`.

Do not merge into `dev` from an agent worktree. Commit, validate, move the task to review, and stop.

If `.shogun/config.json` says `workflow.mode` is `mainline`, do not manually merge. Queue the branch with `shogun queue add <task> --branch <branch>`, let `shogun queue run` land through validation/CI, then use `shogun task accept` or `shogun task revert --commit <merge-sha>`.

## Knowledgebase

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before unfamiliar feature work. Use `docs/architecture/index.html` for the visual architecture/flow map.

Run `shogun map` when files, routes, entrypoints, tests, commands, boundaries, or documented flows change. Shogun creates `flows.json` when missing but preserves existing project-specific flows. Run `shogun map --check` before review; stale architecture docs mean the task is not done.

## Start Work

1. Run `shogun graph ready --json`.
2. Pick one ready task. If none are ready, inspect blockers with `shogun graph list`.
3. Claim it with `shogun graph claim <task> --agent <name>`.
4. Reserve files before editing: `shogun reserve add <task> <paths...> --agent <name> --ttl 2h`.
5. If a reservation conflicts, do not edit those files. Send a message and choose another ready task.

## During Work

- Keep edits inside the claimed task's goal.
- Reserve extra files before touching them.
- Use `shogun message send "..." --to <agent|coordinator> --from <name> --task <task>` for blockers, handoffs, or decisions.
- Check your inbox with `shogun message list --to <name> --unread`.
- If blocked, run `shogun graph block <task> "reason"` and explain the non-obvious edge case.

## Finish Work

1. Run the validation command.
2. Commit the work on the task branch.
3. In review mode, move the Shogun task to review with `shogun task review <task> --summary "..." --validation "npm run build"`.
4. In mainline mode, queue it with `shogun queue add <task> --branch <branch>`.
5. Mark graph work done with `shogun graph done <task>`.
6. Release reservations with `shogun reserve release <task>`.
7. Leave a short summary and QA note in the task.

## Judgment

The graph is the order of operations. Reservations are collision control. Messages are coordination memory. If any of those disagree with the user's direct instruction, stop and surface the conflict.
