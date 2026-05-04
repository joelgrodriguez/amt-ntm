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

Only the `dev` checkout pushes remote `dev`:

```bash
git switch dev
git pull --ff-only origin dev
git merge --ff-only feat/feature-name
git push origin dev
```

If fast-forward fails, stop and inspect the branch graph. Do not invent a merge strategy while tired.

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
