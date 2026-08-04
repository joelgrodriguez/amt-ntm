# Admiral for Orca

Admiral coordinates Orca agent worktrees for `standard-press` on top of
GitHub. Tasks are GitHub issues; the issue is the truth. An open issue with no
`status:*` label is ready; at most one `status:*` label tracks active work.
Orca owns the spawned task worktrees, terminals, and browser tabs. The
integration worktree is `dev`; protected branches such as `main`
and `master` are never used as Admiral development bases.

## Lifecycle

1. `admiral task create "<goal>"` opens a GitHub issue with no status label
   (open + unlabeled = ready). Dependencies go in the issue body's
   `## Blocked by` section via `--blocked-by 12,14`.
2. `admiral task ready` lists open, unstarted, unblocked tasks. This is how
   agents pick work. A task is ready when every issue under its `## Blocked by`
   heading is closed.
3. `admiral task start <n>` refuses blocked tasks (`--force` overrides), asks
   Orca to create a worktree on branch `<n>-<kebab-title>` from
   `dev`, and sets `status:in-progress`. Use `--route [seat]` to
   choose the launch command from the configured router; use `--agent-command
   "<full launch command>"` when you already picked the exact flagged command.
   `--agent-command` is repeatable for several collaborating agents in one
   worktree. Routed seats enforce their configured wall-clock timeout; explicit
   commands can opt in with `--timeout 10m`.
4. The worktree agent uses targeted checks while working, commits, then runs
   `admiral task review <n>`, which runs `npm run build` exactly once
   before handoff. In the default local landing workflow review
   only sets `status:in-review`; it does not push and does not open a
   PR. `admiral task report <n>` gives the reviewer/coordinator the fuller
   handoff view: GitHub, Orca, git, inferred agent roles, and recent local
   Admiral events.
5. From the clean `dev` integration worktree, the reviewer/coordinator runs
   `admiral task land <n>`. It merges the local task branch with
   `git merge --no-ff --no-commit`, validates, commits `Land #<n>: <title>`,
   closes the issue (done), and removes the `status:*` label. It does not
   push. It then removes the provider worktree and attempts `git branch -d`.
   Git may retain an unmerged or squash-merged PR branch; Admiral warns with
   recovery/status details and never uses force or rolls back the successful
   land. It comments `Unblocked: #<n> closed. All blockers clear.` on each
   issue this one was blocking once its last open blocker clears.
   `admiral task cancel <n>` closes an issue as not planned instead.
   `admiral task iterate <n>` reopens a closed issue and sends it back to
   `status:in-progress`.

```text
open (ready when unblocked) -> in-progress -> in-review -> closed
                     ^                            |
                     '--------- iterate ----------'
```

Hard rule: spawned Orca worktree agents do not merge into `dev`,
run raw `git push`, or run `admiral task land`. They use targeted checks,
commit, run `admiral task review <n>` for the single full gate, and stop.

## Project Contract

Admiral creates three concise, human-owned context files when they are missing
and never overwrites them on install or upgrade:

- `PRODUCT.md` defines the product purpose, users, outcomes, and boundaries.
- `CONTEXT.md` records the current project state, direction, constraints, and
  working agreements.
- `docs/decisions/README.md` indexes durable technical and product decisions.

Task preambles point to the files that exist in that order so agents can read
only as much context as the task needs. The files guide the work; they do not
track it. GitHub issues remain the task and lifecycle store, Orca worktrees
remain the execution store, and Git history remains the change record.

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
- `status:<stage>` (present only while the issue is open and active)

At most one `status:*` label is present at a time. Admiral creates and swaps
these labels with `gh issue edit`: `status:in-progress` and
`status:in-review`. An open issue with no status label is staged/ready.
`task land` closes the issue (done) and removes the label; `task cancel`
closes it as not planned and also removes the label -- closed is the
terminal state, no label needed.

## Review Lanes

`admiral task report <n>` recommends advisory review lanes from issue labels. It
does not spawn agents. `cross-vendor` is always present; the default
`reviewLanes` config also maps optional labels to focused checks:
`risk:security` -> `security`, `risk:data-integrity` -> `data-integrity`,
`risk:concurrency` -> `concurrency`, `risk:accessibility` -> `accessibility`,
and `risk:performance` -> `performance`.

Run `admiral doctor` after install to verify gh is authenticated, the configured
repo is reachable, and issue labels are readable.

## Commands

```bash
admiral task create "Fix checkout tax rounding" --type bugfix --area checkout --blocked-by 12
admiral task ready
admiral task list
admiral task start <n> --route
admiral task start <n> --agent-command "codex -m gpt-5.6-sol -c model_reasoning_effort=medium --dangerously-bypass-approvals-and-sandbox"
admiral task report <n>
admiral task review <n> --summary "Committed fix"
admiral task land <n>
admiral task cleanup <n> --dry-run
admiral task cleanup <n> --apply
admiral task cancel <n>
admiral task iterate <n>
admiral graph        # dependency DAG from the issue bodies
```

Ordinary `admiral upgrade --apply` preserves configured router values while
adding missing defaults. To adopt the package's current type mappings, seat
commands, fallbacks, and runtime budgets, preview with
`admiral upgrade --refresh-router`, then apply with
`admiral upgrade --refresh-router --apply`. The preview names every router key
that would change; custom router extensions and non-router config are preserved.

Every command accepts `--json`. `task report <n> --json` is the best handoff
format for automation because it includes the live issue status, Orca terminal
state, commit subjects, changed files, recent terminal output, events, and
inferred agent lanes such as planning, reading, implementation, verification,
and marketing. The human-readable output is a Markdown `Session Report` table.

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
together with `--blocked-by`), read the architecture knowledgebase, and work
the first task `admiral task ready` returns. The CLI provides the rails; the
agent does the decomposition.

If the request is only to create a task, the agent should stop after
`admiral task create` and report the issue number.

## PR Landing Mode

The default landing workflow is local. Existing installs that set
`workflow.landing` to `pr` keep the older GitHub PR flow: `task review` pushes
and opens or updates a PR, and `task land` merges that PR with `gh pr merge`.
Use PR landing only when that is explicitly configured in `.admiral/config.json`.

## Cleanup

Successful landing cleans the task worktree automatically and asks Git to
delete the local task branch only when Git confirms it is merged. Explicit
cleanup remains available for canceled tasks and recovery after an
automatic-cleanup warning:

```bash
admiral task cleanup <n> --dry-run
admiral task cleanup <n> --apply
```

Cleanup refuses while the issue is still open unless `--force` is supplied. It
removes the Orca worktree with `orca worktree rm --worktree issue:<n> --json`
and attempts to delete only the local task branch with `git branch -d`. The two
steps are independent and idempotent; failures are warnings, and `--json`
reports each status/error. Automatic landing cleanup uses the same non-force
operations and never deletes remote branches.

## Multi-Agent Coordination

Agents should use the bundled Admiral skill and these commands before editing:

```bash
admiral map --check
admiral task ready
```

The architecture map keeps the codebase explainable. The `## Blocked by`
sections control order. Use Orca worktree comments and issue comments
(`gh issue comment <n> --body "<note>"`) for human-visible progress
checkpoints and handoffs.
