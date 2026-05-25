# Git Worktree Flow

Use this skill when creating worktrees, finishing feature work, syncing `dev`, or releasing to `master`.

## Branch Roles

- `master`: release branch only.
- `dev`: integration branch.
- `feat/*`, `fix/*`, `chore/*`: worktree branches.

## Create A Worktree

Always branch from current `dev`:

```bash
git switch dev
git pull --ff-only origin dev
git worktree add .worktrees/feature-name -b feat/feature-name dev
```

## Worktree Rules

- A plain coding prompt starts the task workflow. Do not make the user explain
  Superset mechanics.
- If no Superset task exists for the current branch, create one before editing
  code and move it to `In Progress`.
- When the work is committed and validated, move the task to `In Review` and
  stop. Maestro lands it.
- A worktree may push only its own feature branch.
- A worktree must not push to `origin/dev`.
- A worktree must not push to `origin/master`.
- Use `git push -u origin HEAD` only when the current branch is a feature branch.

## Merge Work Into Dev

Only Maestro merges worktree branches into `dev`, and only from the `dev`
checkout. A spawned worktree agent never lands its own work.

```bash
git switch dev
git pull --ff-only origin dev
git merge --no-ff feat/feature-name -m "Merge feature-name worktree updates into dev"
git push origin dev
```

Use a merge commit for landed worktree branches. It makes the whole worktree
easy to revert if DevKinsta shows a broken preview.

Before merging:

- Confirm the Superset task is `In Review`.
- Use `superset tasks statuses list` before status updates; status changes need IDs.
- When changing labels, pass the full label set you want to keep.
- Inspect `git log --oneline dev..branch` and `git diff --stat dev...branch`.
- Refuse messy `wip` work unless the user explicitly accepts it.
- Run the smallest useful validation for the change.

After merging:

- Push `origin/dev` from the `dev` checkout only.
- Sync latest `dev` back into active worktrees.
- Mark the Superset task `Done`.

If the merge conflicts, stop and inspect the branch graph. Do not invent a merge
strategy while tired.

When the user says "land reviewed work", this means: find AMT Maestro tasks in
`In Review`, inspect their branch commits/diffs, validate, merge approved
branches into `dev`, push `origin/dev`, sync active worktrees, and mark tasks
`Done`.

Prefer the project script:

```bash
npm run maestro:sync-tasks # repair missing task cards from active worktrees
npm run maestro:review  # dry run
npm run maestro:land    # execute
```

## Release To Master

Only the `master` checkout pushes remote `master`:

```bash
git switch master
git pull --ff-only origin master
git merge --ff-only dev
git push origin master
```

## Hard No

Do not run:

```bash
git push origin dev:master
git push origin HEAD:dev
git push origin HEAD:master
```

These commands bypass the branch roles. They are how clean workflows become weird archaeology.
