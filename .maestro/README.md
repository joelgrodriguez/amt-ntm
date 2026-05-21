# Maestro

The orchestrator's scratch space, used by the agent running on `dev` to track
spawned Superset worktree workspaces.

Only this `README.md` is committed. The live logs (`active.md`, `archive.md`)
are created on first use from the templates below, and stay local — they're
gitignored on purpose so stale workspace IDs and in-flight notes never pollute
git history.

## Bootstrap

On a fresh clone, the Maestro creates these two files the first time it spawns
or archives a worktree:

- `active.md` — worktrees currently in flight
- `archive.md` — worktrees that have been merged or abandoned

If you want to seed them by hand, copy the templates at the bottom of this file
into `.maestro/active.md` and `.maestro/archive.md`.

## Agent routing

- **Claude** — UI, frontend, templates, CSS, anything visual or design-y
- **Codex** — backend PHP, REST endpoints, architecture, careful programming
- **OpenCode (DeepSeek)** — mechanical work: comments, search/replace, renames

## Lifecycle

1. You: "spin up a worktree for X"
2. Maestro: picks agent, creates workspace + branch off `dev`, sends initial prompt
3. Maestro: adds entry to `active.md` (creating the file if needed)
4. You: iterate by telling Maestro what to say to that worktree
5. You: "land it" — Maestro fetches branch, FF-merges to `dev`, pushes
   `origin/dev`, moves entry to `archive.md`

## Preview note

DevKinsta serves a single site at one URL, pointed at this `dev` checkout's
files on disk. You preview a worktree's work by merging it into `dev`. If it
breaks, revert the merge commit on `dev`.

---

## active.md template

```markdown
# Maestro — Active Worktrees

Live log of in-flight Superset workspaces spawned from this `dev` checkout.
When a worktree is merged into `dev`, its entry moves to `archive.md`.

---

_No active worktrees._

<!--
Template for an active entry:

## feat/<slug> — <Agent> — <status>
- **Workspace:** <superset workspace id>
- **Branch:** feat/<slug>
- **Path:** ~/.superset/worktrees/<dir>
- **Started:** YYYY-MM-DD HH:MM
- **Goal:** one-line summary of what this worktree is for
- **Last prompt:** "…"
- **Last activity:** YYYY-MM-DD HH:MM
- **Notes:** anything useful (blockers, decisions, follow-ups)
-->
```

## archive.md template

```markdown
# Maestro — Archived Worktrees

History of worktrees that have been merged into `dev` (or abandoned).
Newest at the top.

---

_No archived worktrees yet._

<!--
Template for an archived entry:

## feat/<slug> — landed YYYY-MM-DD
- **Agent:** Claude | Codex | OpenCode
- **Merge commit:** <sha>
- **Goal:** one-line summary
- **Outcome:** shipped | reverted | abandoned
- **Notes:** anything worth remembering
-->
```
