# Shogun for Orca

Shogun coordinates Orca agent worktrees for `standard-press` on top of
GitHub. Tasks are GitHub issues; the issue is the truth. The configured GitHub
Projects board is a view: its Status field tracks each task's stage. Orca owns
the spawned worktrees, terminals, and browser tabs.

## Lifecycle

1. `shogun task create "<goal>"` opens a GitHub issue (stage `Staged`).
   Dependencies go in the issue body's `## Blocked by` section via
   `--blocked-by 12,14`.
2. `shogun task ready` lists open, unstarted, unblocked tasks. This is how
   agents pick work. A task is ready when every issue under its `## Blocked by`
   heading is closed.
3. `shogun task start <n>` refuses blocked tasks (`--force` overrides), asks
   Orca to create a worktree on branch `<n>-<kebab-title>` from
   `dev`, and moves the board to `Processing`.
4. The worktree agent commits, validates with `npm run build`, then
   runs `shogun task review <n>` -- it opens (or updates) a PR whose body
   starts with `Fixes #<n>` and moves the board to `Reviewing`.
5. `shogun task land <n>` merges the PR via `gh pr merge`, closes the issue,
   moves the board to `Verifying`, and comments `Unblocked: #<n> closed. All
   blockers clear.` on each issue this one was blocking once its last open
   blocker clears.
6. You review the running app from `dev`. `shogun task approve <n>`
   moves the board to `Done`; `shogun task iterate <n>` reopens the issue and
   sends it back to `Processing`.

```text
Staged -> Processing -> Reviewing -> Verifying -> Done
                ^             |            |
                |_____________|____________|
```

Hard rule: spawned Orca worktree agents do not merge into `dev`. Shogun
lands reviewed work through the PR.

## Task Contract

The issue body uses three sections:

```markdown
## What to build

## Acceptance criteria
- [ ] ...

## Blocked by
- #12
- #14
```

(or `None - can start immediately`). Only `#n` references under the
`## Blocked by` heading create dependencies; mentions elsewhere in the body do
not.

Every task issue carries the labels:

- `shogun`
- `client:<client>`
- `project:<orca-project>`
- `type:<feature|bugfix|ui|copy|backend|refactor|test|docs|chore|research>`
- `area:<domain>`
- `agent:<codex|claude|opencode|...>`
- `risk:<low|medium|high>`

The board's Status field must have the options `Staged`, `Processing`,
`Reviewing`, `Verifying`, `Done`, and `Canceled`. If one is missing, Shogun
fails and tells you which option to add -- it never tracks stage anywhere else.
`Verifying` is the human-QA gate: a task moves there when its PR lands, and
`approve` is the only command that moves it to `Done`.

Run `shogun doctor` after install to verify gh is authenticated (with the
`project` scope), the configured repo is reachable, the board exists, and the
Status field has all six options.

## Commands

```bash
shogun task create "Fix checkout tax rounding" --type bugfix --area checkout --blocked-by 12
shogun task ready
shogun task list
shogun task start <n> --agent codex
shogun task review <n> --summary "Committed fix"
shogun task land <n>
shogun task approve <n>
shogun task iterate <n>
shogun graph        # dependency DAG from the issue bodies
```

Every command accepts `--json`.

## Plain Feature Requests

You should be able to say:

```text
Build <feature>. Use Shogun.
```

Or simply:

```text
Create a task for <feature>.
```

The installed AGENTS.md and Shogun skill tell the agent to create the issues
first with `shogun task create` (one issue per small vertical slice, wired
together with `--blocked-by`), read the architecture knowledgebase, reserve
files, and work the first task `shogun task ready` returns. The CLI provides
the rails; the agent does the decomposition.

If the request is only to create a task, the agent should stop after
`shogun task create` and report the issue number.

## Mainline Mode

Review mode is the default. Mainline mode keeps worktrees as scratchpads, but
lands tiny queued PRs through validation/CI instead of waiting on a review loop:

```bash
shogun mode mainline --ci-command "npm run build"
shogun queue add <n> --branch <n>-<slug>
shogun queue run --max 1
shogun task accept <n>
shogun task revert <n> --commit <merge-sha>
```

Blocked tasks are refused at `queue add` and skipped at `queue run`. In
mainline mode, do not direct-land with `shogun task land`; queue the branch.

## Multi-Agent Coordination

Agents should use the bundled Shogun skill and these commands before editing:

```bash
shogun map --check
shogun task ready
shogun reserve add <n> <paths...> --agent <name>
shogun message send "Blocked on API shape" --to coordinator --task <n>
```

The architecture map keeps the codebase explainable. The `## Blocked by`
sections control order. Reservations control file collisions (and post a
one-line comment on the issue so humans can see what's held). Messages keep
handoffs out of stale terminal scrollback. Use Orca worktree comments for
human-visible progress checkpoints.
