---
name: shogun
description: Coordinate Shogun-managed Superset coding work with task graphs, file reservations, agent messages, validation, and review handoff. Use when working in a project installed with Shogun, handling Superset tasks, reserving files, coordinating multiple agents, or moving work through review.
---

# Shogun

## Contract

Use `.shogun/README.md` and `.shogun/config.json` as the local contract. Shogun uses Superset, branches from `dev`, and validates with `npm run build`.

Do not merge into `dev` from an agent worktree. Commit, validate, move the task to review, and stop.

If `.shogun/config.json` says `workflow.mode` is `mainline`, do not manually merge. Queue the branch with `shogun queue add <task> --branch <branch>`, let `shogun queue run` land through validation/CI, then use `shogun task accept` or `shogun task revert --commit <merge-sha>`.

## Knowledgebase

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before unfamiliar feature work. Use `docs/architecture/index.html` for the visual architecture/flow map.

Run `shogun map` when files, routes, entrypoints, tests, commands, boundaries, or documented flows change. Shogun creates `flows.json` when missing but preserves existing project-specific flows. Run `shogun map --check` before review; stale architecture docs mean the task is not done.

## Start Work

1. Run `shogun graph ready --json`.
2. Pick one ready graph node. If none are ready, inspect blockers with `shogun graph list`.
3. Claim it with `shogun graph claim <task> --agent <name>`.
4. Reserve files before editing: `shogun reserve add <task> <paths...> --agent <name> --ttl 2h`.
5. Remember the graph node's `parentTask`. That is the Superset task ID for review/landing commands.
6. If a reservation conflicts, do not edit those files. Send a message and choose another ready task.

## Plain Feature Requests

If the user asks for a feature and there is no useful graph yet, create the graph yourself. Do not make the user write a giant orchestration prompt.

1. If the user did not provide an existing Superset task ID or URL, create the parent task first: `shogun task create "<feature>" --type feature --area <area> --agent <name>`.
2. Capture the returned Superset task ID. If task creation fails, run `shogun doctor` and stop instead of creating local-only graph work.
3. Read the architecture knowledgebase and inspect the relevant code.
4. Create 3-6 small graph nodes with `shogun graph add ... --parent <superset-task-id>`, ordered by real dependencies.
5. Prefer boring nodes: architecture docs, data/API, UI, tests, validation. Skip nodes that do not apply.
6. Then run the normal Start Work loop.

A graph is just the local order of operations. Superset is the task source of truth. Keep the graph small enough that another agent can grab the next obvious piece without reading your mind.

## During Work

- Keep edits inside the claimed task's goal.
- Reserve extra files before touching them.
- Use `shogun message send "..." --to <agent|coordinator> --from <name> --task <task>` for blockers, handoffs, or decisions.
- Check your inbox with `shogun message list --to <name> --unread`.
- If blocked, run `shogun graph block <task> "reason"` and explain the non-obvious edge case.

## Finish Work

1. Run the validation command.
2. Commit the work on the task branch.
3. Mark graph work done with `shogun graph done <graph-node>`.
4. If this was the last open graph node for the parent task, move the parent Superset task to review with `shogun task review <parent-task> --summary "..." --validation "npm run build"`.
5. In mainline mode, queue the parent task with `shogun queue add <parent-task> --branch <branch>`.
6. Release reservations with `shogun reserve release <graph-node>`.
7. Leave a short summary and QA note on the parent Superset task.

## Judgment

The graph is the order of operations. Reservations are collision control. Messages are coordination memory. If any of those disagree with the user's direct instruction, stop and surface the conflict.
