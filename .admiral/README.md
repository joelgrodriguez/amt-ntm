# Admiral for Orca

Admiral coordinates Orca agent worktrees for `standard-press` on top of
GitHub. Tasks are GitHub issues; the issue is the truth. A single `status:*`
issue label tracks each task's stage. Orca owns the spawned task worktrees,
terminals, and browser tabs. The integration worktree is `dev`;
protected branches such as `main` and `master` are never used as Admiral
development bases.

## Lifecycle

1. `admiral task create "<goal>"` opens a GitHub issue (stage `Staged`).
   Dependencies go in the issue body's `## Blocked by` section via
   `--blocked-by 12,14`.
2. `admiral task ready` lists open, unstarted, unblocked tasks. This is how
   agents pick work. A task is ready when every issue under its `## Blocked by`
   heading is closed.
3. `admiral task start <n>` refuses blocked tasks (`--force` overrides), asks
   Orca to create a worktree on branch `<n>-<kebab-title>` from
   `dev`, and moves the issue to `Processing`. Pick the worktree's
   model per work type via the `route` skill: `--agent-command "<full launch
   command>"` launches a flagged agent (model/effort/bypass); pass it more than
   once to run several collaborating agents in the one worktree.
4. The worktree agent commits, validates with `npm run build`, then
   runs `admiral task review <n>`. In the default local landing workflow this
   only moves the issue to `Reviewing`; it does not push and does not open a
   PR. `admiral task report <n>` gives the coordinator the fuller handoff view:
   GitHub, Orca, git, inferred agent roles, reservations, messages, and recent
   local Admiral events. If a Fable terminal is blocked by spend limits, credits,
   auth, or a trust prompt, `task sync` and `task report` warn but do not
   auto-respawn; the coordinator runs `admiral agent fallback <n> --to "claude --model opus --effort xhigh --dangerously-skip-permissions"`.
5. From the clean `dev` integration worktree, the coordinator runs
   `admiral task land <n>`. It merges the local task branch with
   `git merge --no-ff --no-commit`, validates, commits `Land #<n>: <title>`,
   and moves the issue to `Verifying`. It does not push.
6. You review the running app from `dev`. `admiral task approve <n>`
   closes the issue, moves it to `Done`, and comments
   `Unblocked: #<n> closed. All blockers clear.` on each issue this one was
   blocking once its last open blocker clears. `admiral task iterate <n>`
   reopens the issue and sends it back to `Processing`.

```text
Staged -> Processing -> Reviewing -> Verifying -> Done
                ^             |            |
                |_____________|____________|
```

Hard rule: spawned Orca worktree agents do not merge into `dev`,
run raw `git push`, or run `admiral task land`. They commit, validate, run
`admiral task review <n>`, and stop.

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

- `admiral`
- `client:<client>`
- `project:<orca-project>`
- `type:<feature|bugfix|ui|copy|backend|refactor|test|docs|chore|research>`
- `area:<domain>`
- `agent:<codex|claude|opencode|...>`
- `risk:<low|medium|high>`
- `status:<stage>`

Exactly one `status:*` label should be present at a time. Admiral creates and
swaps these labels with `gh issue edit`: `status:staged`,
`status:processing`, `status:reviewing`, `status:verifying`, `status:done`,
and `status:canceled`.
`Verifying` is the human-QA gate: a task moves there when `task land` succeeds, and
`approve` is the only command that moves it to `Done`.

Run `admiral doctor` after install to verify gh is authenticated, the configured
repo is reachable, and issue labels are readable.
Use `admiral doctor --agents` after configuring `agentPreflight.commands` in
`.admiral/config.json` to smoke-test headless agent launch commands.

## Commands

```bash
admiral task create "Fix checkout tax rounding" --type bugfix --area checkout --blocked-by 12
admiral task ready
admiral task list
admiral task start <n> --agent-command "codex -m gpt-5.5 -c model_reasoning_effort=high --dangerously-bypass-approvals-and-sandbox"
admiral task report <n>
admiral agent fallback <n> --to "claude --model opus --effort xhigh --dangerously-skip-permissions"
admiral task review <n> --summary "Committed fix"
admiral task land <n>
admiral task cleanup <n> --dry-run
admiral task cleanup <n> --apply
admiral task approve <n>
admiral task iterate <n>
admiral graph        # dependency DAG from the issue bodies
```

Every command accepts `--json`. `task report <n> --json` is the best handoff
format for automation because it includes the live issue status, Orca terminal
state, commit subjects, changed files, recent terminal output, reservations,
messages, events, and inferred agent lanes such as planning, reading,
implementation, verification, and marketing. The human-readable output is a
Markdown `Session Report` table. Use `task report <n> --since-last` after an
agent answer to read only new Orca terminal output since the previous report.

## Plain Feature Requests

You should be able to say:

```text
Build <feature>. Use Admiral.
```

Or simply:

```text
Create a task for <feature>.
```

The installed AGENTS.md and Admiral skill tell the agent to create the issues
first with `admiral task create` (one issue per small vertical slice, wired
together with `--blocked-by`), read the architecture knowledgebase, reserve
files, and work the first task `admiral task ready` returns. The CLI provides
the rails; the agent does the decomposition.

If the request is only to create a task, the agent should stop after
`admiral task create` and report the issue number.

## Mainline Mode

Review mode is the default. Mainline mode keeps worktrees as scratchpads, but
lands tiny queued PRs through validation/CI instead of waiting on a review loop:

```bash
admiral mode mainline --ci-command "npm run build"
admiral queue add <n> --branch <n>-<slug>
admiral queue run --max 1
admiral task accept <n>
admiral task revert <n> --commit <merge-sha>
```

Blocked tasks are refused at `queue add` and skipped at `queue run`. In
mainline mode, do not direct-land with `admiral task land`; queue the branch.

## PR Landing Mode

The default landing workflow is local. Existing installs that set
`workflow.landing` to `pr` keep the older GitHub PR flow: `task review` pushes
and opens or updates a PR, and `task land` merges that PR with `gh pr merge`.
Use PR landing only when that is explicitly configured in `.admiral/config.json`.

## Cleanup

Cleanup is manual after QA:

```bash
admiral task cleanup <n> --dry-run
admiral task cleanup <n> --apply
```

Cleanup refuses before `Done` unless `--force` is supplied. It removes the Orca
worktree with `orca worktree rm --worktree issue:<n> --json` and deletes only
the local task branch with `git branch -d`; it does not delete remote branches.

## Multi-Agent Coordination

Agents should use the bundled Admiral skill and these commands before editing:

```bash
admiral map --check
admiral task ready
admiral reserve add <n> <paths...> --agent <name>
admiral message send "Blocked on API shape" --to coordinator --task <n>
```

The architecture map keeps the codebase explainable. The `## Blocked by`
sections control order. Reservations control file collisions (and post a
one-line comment on the issue so humans can see what's held). Messages keep
handoffs out of stale terminal scrollback. Use Orca worktree comments for
human-visible progress checkpoints.
