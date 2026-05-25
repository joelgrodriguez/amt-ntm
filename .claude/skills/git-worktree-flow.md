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

- Confirm the Superset task has `ready-for-land`.
- Use `superset tasks statuses list` before status updates; status changes need IDs.
- When changing labels, pass the full label set you want to keep.
- Inspect `git log --oneline dev..branch` and `git diff --stat dev...branch`.
- Refuse messy `wip` work unless the user explicitly accepts it.
- Run the smallest useful validation for the change.

After merging:

- Push `origin/dev` from the `dev` checkout only.
- Sync latest `dev` back into active worktrees.
- Mark the Superset task `Done` and add the `landed` label.

If the merge conflicts, stop and inspect the branch graph. Do not invent a merge
strategy while tired.

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
