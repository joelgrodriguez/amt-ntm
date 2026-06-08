# Shogun for Superset

Shogun coordinates Superset worktrees for `standard-press`. Superset tasks are
the source of truth; Shogun handles the boring status moves and landing ritual.

## Lifecycle

1. Create a Superset task with `shogun task create` (it starts in `Staged`).
2. Spawn a Superset workspace from `dev`.
3. Move the task to `Processing` when the agent starts.
4. The worktree agent commits, validates, updates the task, moves it to `Reviewing`, and stops.
5. From `dev`, merge the branch and move the task to `Verifying`.
6. You review the running app from `dev`.
7. Approve it to `Done`, or send it back to `Processing` for iteration.

```text
Staged -> Processing -> Reviewing -> Verifying -> Done
                ^             |            |
                |_____________|____________|
```

Hard rule: spawned worktree agents do not merge into `dev`. Shogun
lands reviewed work.

## Task Contract

Task titles use `[Shogun] <slug>: <goal>`.

Every task should have:

- `shogun`
- `project:<slug>`
- `branch:<branch>`
- `type:<feature|bugfix|ui|copy|backend|refactor|test|docs|chore|research>`
- `area:<domain>`
- `agent:<codex|claude|opencode|...>`
- `risk:<low|medium|high>`

These statuses live in the team's Linear **Issue statuses** and sync into
Superset, so they are real board columns. `Verifying` is the human-QA gate: a
task moves there after its branch merges into `dev`, and `approve`
is the only command that moves it to `Done`.

Run `shogun doctor` after install to verify the
Staged/Processing/Reviewing/Verifying/Done statuses have synced from Linear.

## Commands

```bash
shogun task create "Fix checkout tax rounding" --type bugfix --area checkout --agent codex
shogun task list
shogun task start <task>
shogun task review <task> --summary "Committed fix" --validation "npm run build"
shogun task land <task> --branch shogun/fix-checkout-tax-rounding
shogun task approve <task>
shogun task iterate <task> "Tighten the mobile spacing"
```

`land` leaves the Superset task in `Verifying`. `approve` is the only command
that moves a task from `Verifying` to `Done`.

## Plain Feature Requests

You should be able to say:

```text
Build <feature>. Use Shogun.
```

The installed AGENTS.md and Shogun skill tell the agent to read the architecture
knowledgebase, create a small dependency graph when one does not exist, reserve
files, and work the first ready node. The CLI provides the rails; the agent does
the decomposition.

## Mainline Mode

Review mode is the default. Mainline mode keeps worktrees as scratchpads, but
lands tiny queued branches through validation/CI instead of waiting on a PR loop:

```bash
shogun mode mainline --ci-command "npm run build"
shogun queue add <task> --branch shogun/<slug>
shogun queue run --max 1
shogun queue run --max 1 --no-pull   # local-only repos without origin/<base>
shogun task accept <task>
shogun task revert <task> --commit <merge-sha>
```

Do not treat mainline mode as permission to skip reservations, tests, or revert
discipline. CI is the gate. In mainline mode, do not direct-land with
`shogun task land --branch`; queue the branch.

## Multi-Agent Coordination

Agents should use the bundled Shogun skill and these commands before editing:

```bash
shogun map --check
shogun graph ready
shogun graph claim <task> --agent <name>
shogun reserve add <task> <paths...> --agent <name>
shogun message send "Blocked on API shape" --to coordinator --task <task>
```

The architecture map keeps the codebase explainable. `flows.json` documents the
real product/system paths rendered in `index.html`. The graph controls order.
Reservations control file collisions. Messages keep handoffs out of stale
terminal scrollback.
