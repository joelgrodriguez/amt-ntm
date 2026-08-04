---
name: admiral
description: Coordinate Admiral-managed Orca coding work with GitHub issue tasks, blocked-by dependencies, validation, and local review handoff. Use automatically in any repo with .admiral/config.json when the user asks to create or work on a task, ticket, issue, feature, bug, chore, TODO, or implementation plan, even if Admiral is not named.
---

# Admiral

## Contract

Use `.admiral/README.md` and `.admiral/config.json` as the local contract. Tasks are GitHub issues driven through the `gh` CLI; Orca owns the spawned worktrees, terminals, and browser tabs; Admiral branches task work from the `dev` integration branch, and `task review` validates once with `npm run build` before handoff. Protected branches such as `main` and `master` are not Admiral development bases.

Task stage lives only in GitHub issue state and `status:*` labels: an open issue with no `status:*` label is ready, `status:in-progress` means active, `status:in-review` means handed off, and closed means done. There is no second stage store.

Task creation is mandatory in Admiral-installed repos. If the user asks to create a task, ticket, issue, feature, bug, chore, TODO, or implementation plan and does not provide an existing issue number or URL, run `admiral task create` before code work. Do this even if the user does not say "Admiral".

**The ~30-minute rule.** The issue + worktree + spawned-agent + review + land ceremony is fixed overhead that a small change never pays back. If the work is under ~30 minutes of agent effort or confined to one file/concern, skip the ceremony: fix it inline on a short-lived branch off `dev`, validate, and merge — no issue, no worktree, no spawned agent (unless the user explicitly asks for a tracked task). At or above that threshold, or when the work parallelizes or has dependencies, the full Admiral flow is the win.

`admiral task create "<goal>"` opens the GitHub issue with no `status:*` label (open + unlabeled = ready). The issue number it prints is the task ID for every later command. Dependencies go in the issue's `## Blocked by` section via `--blocked-by 12,14`; a task is ready only when every blocker is closed.

Do not merge into `dev` from an Orca captain's worktree. Do not run raw `git push`, `git merge`, `admiral task land`, or `admiral task cleanup --apply` from a spawned task worktree. Use targeted checks while working, commit, run `admiral task review <n>` for the single full gate, and stop. See the installed AGENTS.md Admiral Workflow section for the full hard-rules list, the Session Report table shape, and the two-failures-then-consult rule — this skill does not restate them.

## Command Surface

Supported task commands are `create`, `update`, `list`, `ready`, `start`, `sync`, `report`, `review`, `land`, `cleanup`, `iterate`, and `cancel`. If a task command is not in that list, do not invent it; use `gh issue comment` for coordination and `admiral task report` for handoff context.

## Routing Contract

Load the installed `route` skill and treat `.admiral/config.json` `router` as
operational truth. Codex Sol medium is the default long-lived
driver/commodore. Fable 5 high is the bounded one-shot brain for consequential
mission planning and synthesis, not the terminal watcher. Codex
`gpt-5.6-sol` medium is the normal builder; its implementation fallback
is Gemini 3.5 Flash High through Antigravity. GLM 5.2 is the primary verifier,
with Codex Sol medium and then Gemini 3.5 Flash High as fallbacks. Fable-primary
seats end with GLM 5.2; judgment-heavy seats end with Gemini 3.1 Pro High.
Grok 4.5 owns scouting, research, and copy. Bounded Fable high handles routine
Codex-authored review. Pinned Claude Opus 4.8 xhigh is reserved for explicit
flagship taste passes.

Security is a two-pass workflow: Fable produces the threat-model strategy,
then Codex Sol xhigh hunts concrete exploits and rates actual exploitability
P0-P3. Customer-facing pages are also two passes: Grok 4.5 writes the copy,
then Gemini 3.1 Pro High critiques it read-only through Antigravity:

```bash
agy --model "Gemini 3.1 Pro (High)" --mode plan --print "<critique prompt>" --print-timeout 10m
```

The Antigravity executable is `agy`; `--mode plan` prevents the critic from
editing files.

For repetitive page batches, metadata, and inventories, use Gemini 3.5 Flash
High as a bulk critic. Send consequential or ambiguous findings to Gemini
3.1 Pro High or pinned Opus 4.8 xhigh. Gemini critics are direct workflow
passes, not additional Admiral seats.

## Knowledgebase

Read `docs/architecture/map.json` and `docs/architecture/flows.json` before unfamiliar feature work — these are the machine-readable knowledgebase agents should parse. `docs/architecture/index.html` is a human browser view of the same data; agents should read the JSON, not the HTML.

Run `admiral map` when files, routes, entrypoints, tests, commands, boundaries, or documented flows change. Run `admiral map --check` before review; stale architecture docs mean the task is not done.

A durable behavior spec accretes at `docs/specs/<area>.md` as tasks land — a newest-first log of what each area is supposed to do. Read the spec for your task's area before changing existing behavior; browse any area with `admiral spec <area>` or list them with `admiral spec`. It is generated automatically on land; never hand-edit it.

## Start Work

1. Run `admiral task ready --json`. These are the open, unstarted, unblocked tasks.
2. Pick one. If none are ready, inspect blockers with `admiral graph`.
3. Start it with `admiral task start <n>`. This refuses blocked tasks, asks Orca to create a worktree on branch `<n>-<kebab-title>` from `dev`, and sets `status:in-progress`. Use `admiral task start <n> --route [seat]` when the configured router can pick the launch command, or pass the routed command directly with `--agent-command "<full launch command>"`. `--agent-command` launches a flagged captain (model, effort, bypass) that a bare `--agent` id can't express. Pass it more than once to run several captains in one worktree.

## Plain Feature Requests

If the user asks for a feature, decompose it yourself into a ladder of small, independently landable issues. Do not make the user write a giant orchestration prompt. Each issue should be understandable to a human, verifiable by a captain, and useful before the whole feature is done.

1. **Burn the fog first.** If the user did not provide an existing issue number or URL, read the architecture knowledgebase and inspect the relevant code before asking anything. When the feature is unfamiliar, the area is new to you, or "good" is something you'd only recognize on sight, load the `explore-unknowns` skill and walk the four quadrants — settled ground, the answerable questions (interview one at a time, biggest architectural blast-radius first), the taste to surface with a react-to-it prototype, and a blindspot pass for landmines. The output is a reviewed map. Don't slice from a foggy map.
2. **Slice at API seams.** Each slice should behave like a tiny library: a named module boundary, typed inputs/outputs, and a test at the seam. If a slice needs three unrelated systems booted before it can be checked, sharpen the seam. One concern per slice; if a slice hides multiple variables or a broad verb ("make it robust", "add the backend"), reslice it until the next issue can be accepted or rejected by one focused check.
3. **Create 1-6 issues** with `admiral task create`, one per vertical slice, ordered by real dependencies with `--blocked-by`. Capture each issue number it prints. Fill the enriched issue body: **What to build** (the contract), **API seam** (module / typed I/O / ownership), **Acceptance criteria**, **Verification** (the exact commands that prove it and what stays green), **Blocked by**. Leave **Rationale** blank until the slice lands. If task creation fails, run `admiral doctor` and stop instead of working untracked.
4. **Then run the normal Start Work loop** on `admiral task ready`.

The `## Blocked by` sections are the order of operations. Keep slices small enough that another captain can grab the next ready issue without reading your mind. If implementation hits a snag and a slice starts changing unrelated things, stop broadening the patch — split the issue, re-wire `--blocked-by`, and resume. Reslicing is progress, not failure. As each slice lands, fill its issue's `## Rationale` from the implementation Deviations log — the *why*, the invariants, the dead ends — so `docs/specs/<area>.md` accretes a why-record, not just a what-log.

## Audit Output Becomes Tasks

Advisor skills that audit and plan (e.g. `improve`) propose; Admiral disposes. When an audit produces implementation plans — under `plans/` or `advisor-plans/` — do not keep them as a parallel backlog and do not use the advisor's own execute/dispatch variant in an Admiral repo (it bypasses the land pipeline). Convert each selected plan into an issue with `admiral task create`, mapping the plan's content onto the enriched issue body (What to build, Acceptance criteria, Verification commands) and its dependency ordering onto `--blocked-by`. Record the issue number in the plan's index and mark the plan converted so the next audit reconciles instead of re-planning. Execution then goes through the normal Start Work loop with a routed captain. The single source of truth for pending work is the GitHub issues, never a plans directory.

## During Work

- Keep edits inside the claimed task's goal.
- Checkpoint meaningful progress. On the **orca** provider, use `orca worktree
  set --worktree active --comment "..." --json`. On the **herdr** provider there
  is no worktree-comment equivalent (its CLI is socket-based) — checkpoint with
  `gh issue comment <n> --body "<progress>"` instead. `gh issue comment` works
  under either provider, so prefer it if you are unsure which one is configured.
- Use `gh issue comment <n> --body "..."` for blockers, handoffs, or decisions.
- Use `admiral task report <n>` for handoff/status: it combines GitHub, Orca,
  git changed files, inferred captain roles, and recent local Admiral events.
  Human-readable reports are Markdown headed `Session Report` with the content
  in a table, and include commits, changed files, recent output, and launched
  captains. Reports also recommend review lanes from issue `risk:*` labels:
  `cross-vendor` is baseline, while `security`, `data-integrity`,
  `concurrency`, `accessibility`, and `performance` are conditional.
- If an existing task worktree needs another agent terminal, launch it through
  the configured provider directly; `admiral task start` creates task
  worktrees, it is not a same-worktree respawn command.
- If genuinely blocked on a decision, add the blocker as an issue and wire it with `admiral task update <n> --blocked-by <blocker>`.

## Finish Work

1. Run targeted checks while working; do not poll or repeatedly run the full gate.
2. Commit the work on the task branch.
3. Run `admiral task review <n> --summary "..."`. It runs the configured full validation gate exactly once before handoff. In the default local landing workflow it then sets `status:in-review`; it does not push and does not open a PR. If the project explicitly sets `workflow.landing` to `pr`, Admiral keeps the older PR review behavior.

Landing is `admiral task land <n>` from the clean `dev` integration checkout (human or reviewer/coordinator): it locally merges the task branch, validates, commits `Land #<n>: <title>`, closes the issue (done), removes the `status:*` label, comments on each newly unblocked issue, then removes the task worktree and attempts `git branch -d`. Git may retain an unmerged or squash-merged PR branch; Admiral warns with structured recovery/status details, never uses force, and never rolls back a successful land. It does not push. `admiral task cancel <n>` closes an issue as not planned instead; explicit `admiral task cleanup <n> --apply` remains available for canceled tasks and recovery.

Before landing or handing off a squadron task, run `admiral task report <n>`
to see which captains were launched, their inferred lanes (planning, reading,
implementation, verification, marketing), and whether recent events need
attention.

## Judgment

The blocked-by DAG is the order of operations. If it disagrees with the user's direct instruction, stop and surface the conflict.
