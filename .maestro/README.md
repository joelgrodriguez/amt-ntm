# Maestro

The orchestrator's scratch space, used by the agent running on `dev` to track
spawned Superset worktree workspaces.

Superset tasks are the source of truth. The live logs (`active.md`,
`archive.md`) are optional local notes created on first use from the templates
below. They stay gitignored so stale workspace IDs and in-flight notes never
pollute git history.

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
2. Maestro: creates or updates a Superset task with labels
   `amt-ntm,maestro,worktree,<agent-label>`
3. Maestro: picks agent, creates workspace + branch off `dev`, sends initial
   prompt with the Superset task ID
4. Maestro: updates the task with branch, workspace, path, agent, and goal
   and leaves it in `Todo`
5. Worktree agent: works on its branch, commits, updates the task with summary,
   commits, validation, and risk, moves it to `In Review`, then stops
6. You: "land it" or "pull worktree changes"
7. Maestro: inspects the branch, merges it into `dev`, validates, pushes
   `origin/dev`, syncs active worktrees, and marks the task `Done`

## Preview note

DevKinsta serves a single site at one URL, pointed at this `dev` checkout's
files on disk. You preview a worktree's work by merging it into `dev`. If it
breaks, revert the merge commit on `dev`.

## Superset task contract

Task statuses:

- `Backlog`: idea only; no worktree yet
- `Todo`: approved work; a worktree may exist, but no code work has started
- `In Progress`: an agent is actively working, or the branch has unlanded changes that are not ready for review
- `In Review`: the agent finished, committed, validated, and the branch is ready for Maestro/user review before landing
- `Done`: Maestro merged into `dev`, pushed `origin/dev`, and synced worktrees
- `Canceled`: abandoned, duplicated, or reverted

Labels:

- Required: `amt-ntm`, `maestro`, `worktree`
- Agent: `agent-claude`, `agent-codex`, or `agent-opencode`
- State: `blocked`

Description template:

```text
Branch:
Workspace:
Path:
Agent:
Goal:
Status:
Summary:
Commits:
Validation:
Risk:
```

Useful commands:

```bash
superset tasks statuses list
superset tasks create --title "[AMT Maestro] <slug>: <goal>" --description "<template>" --priority medium --labels amt-ntm,maestro,worktree,agent-claude
superset tasks update <task-id-or-slug> --status-id <in-review-status-id> --labels amt-ntm,maestro,worktree,agent-claude
superset tasks list --search "AMT Maestro"
```

When changing status, run `superset tasks statuses list` first; the CLI wants a
status ID, not the status name. When changing labels, pass the full label set
you want to keep.

Landing checklist:

```bash
git switch dev
git status --short --branch
git pull --ff-only origin dev
git log --oneline dev..<branch>
git diff --stat dev...<branch>
git merge --no-ff <branch> -m "Merge <slug> worktree updates into dev"
npm run build
git push origin dev
```

After landing, sync `dev` back into active worktrees. Skip dirty worktrees and
report them instead of stomping local work.

Hard rule: spawned worktree agents do not merge into `dev`. Maestro lands work.
That is the whole point of having an orchestrator.

If review asks for changes, move the same task back to `In Progress` and keep
working on the same branch. After a task is `Done`, create a follow-up task for
new work unless the merge was reverted.

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
