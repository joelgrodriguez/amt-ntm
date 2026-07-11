---
name: route
description: >-
  Route a piece of work to the right agent CLI (Claude, Codex, OpenCode/GLM,
  Grok/Composer) with verified launch flags — model, effort, and permission
  bypass. Use when the user says "route this", "/route", "which model for
  <work>", "launch an agent for <task>", "spawn <copy|impl|debug|verify>
  work", or is deciding which of their agent CLIs to run for writing, copy,
  design, marketing, implementation, debugging, or verification. Outputs the
  exact shell command to run (directly, via Orca's `terminal create --command`,
  or `worktree create`). In Admiral-installed repos, `.admiral/config.json`
  `router` is the operational source of truth; this skill documents the default
  table and override guidance.
---

# Route

Pick the agent + launch command for a piece of work, by work type. All commands
are verified against the installed CLIs (`--help`, 2026-07). Bypass/auto-approve
is on everywhere by design — worktree isolation is the safety net, not per-call
prompts. Effort values are tunable defaults; edit them in the table below.

In Admiral-installed projects, routing decisions are deterministic and live in
`.admiral/config.json` under `router`. The fixed seats are `orchestrator`,
`scout`, `implementer`, `verifier`, `reviewer`, `marketer`, and `docs`. Each
seat has a full launch `command` plus an ordered `fallback` chain; `typeDefaults`
maps issue `type:` labels to seats. Override a project by editing only the seat
you need, for example `router.seats.implementer.command`. `admiral task start
<n> --route` infers from the task type, `--route verifier` overrides. When an
agent hits a spend/auth wall, respawn it manually with the seat's fallback
command: `admiral task start <n> --agent-command "<fallback command>"`.

**The three-seat model (Joel's override, 2026-07).** Coding work runs on a
fixed cast:
- **Brain / orchestrator / planner → Claude Fable 5 (high).** Fable is the one
  that *thinks*: analyzes the codebase, plans, decides, and dispatches. It holds
  the mission; it doesn't grep files itself. Fable is the **premium seat** —
  the smartest and best coder, chosen for quality, not thrift. The reason it
  delegates reading (Composer) and writing (Codex) is to spend its expensive
  reasoning on judgment, not on grinding through files.
- **Executor / code writer → GPT-5.5 (xhigh) via Codex.** When code actually
  gets written, Codex writes it. (Replaces Composer as the implementer.)
- **Reader / scout → Composer 2.5.** Composer reads the codebase and reports
  findings *back to Fable*, who orchestrates. Reader by default; it still writes
  code when Joel explicitly routes a write task to it.

So "inspect this codebase" → Fable directs, Composer reads and reports, Fable
analyzes and decides, Codex executes any resulting changes. Fable is the brain
of the whole loop.

**Token-saving driver rule.** For long unattended orchestration where the driver
mostly dispatches, watches, and hands off, use **Codex as the driver seat** to
save Claude/Fable tokens. Use Fable/Claude as the driver when the chain needs
taste, planning judgment, or explicit Fable supervision.

**The stack has three layers — the commodore is above Fable.**
```
YOU (English)
  └─ Commodore: Claude Opus 4.8 xhigh   ← the session Joel sits in
       └─ dispatches → Fable (brain)     ← Orca terminal, per project/worktree
            ├─ Composer (reads/scouts)
            └─ Codex (writes code)
```
Joel talks to the **commodore** (Opus xhigh), not to Fable. The commodore
interprets English, routes, and drives Orca/Admiral. It dispatches **Fable as a
worker brain** into an Orca terminal; Fable runs its own crew (Composer reads,
Codex writes) and reports back up.

**Backup when Fable runs dry → the CONDUCTOR respawns as Opus. Fable cannot
rescue itself.** A dead/credit-exhausted Fable terminal has no way to call
`orca terminal create` or summon a replacement — it just errors and sits. So the
parachute is **commodore-owned supervision**, not a model feature on Fable:
- The commodore **watches** the Fable terminal (this is exactly what the
  `escalate` skill does — poll the terminal, detect stalled/errored/credit-wall).
- On a credit/auth/trust error, the commodore manually spins a **new** terminal
  with Opus in the same worktree and re-dispatches the same task:
  `admiral task start <n> --agent-command "claude --model opus --effort xhigh --dangerously-skip-permissions"`.
- Fable never knows; the layer above it did the swap.

`--fallback-model opus` (Claude's in-process flag) is a **red herring here**: it
only works in headless `--print` mode and swaps *within one process* — it cannot
drive Orca or respawn a watchable terminal. Use it only for headless dispatched
work where no live terminal is needed. For the normal watch-a-terminal workflow,
the commodore watches the terminal directly and, on a wall, manually launches
the fallback command above in the same Orca worktree with the same task
context.

Not an "ultracode" effort — the installed `claude` CLI (v2.1) accepts only
`low|medium|high|xhigh|max`; there is no `ultracode` effort flag, so both the
commodore and the parachute run at `xhigh` (dial to `max` only if depth can't be
compromised). If a future Claude Code build exposes `ultracode` as a real
`--effort` value, wire it in then.

## The routing table

| Work type | Agent | Launch command |
|---|---|---|
| Docs / knowledgebase / READMEs / guides / changelog / PR / ADR | **`docs-writer` agent** (Claude Opus) | `claude --agent docs-writer --model opus --effort xhigh --dangerously-skip-permissions` |
| Naming / brand voice / long-form | Claude Opus | `claude --model opus --effort xhigh --dangerously-skip-permissions` |
| UI/UX design / API design | Claude Opus | `claude --model opus --effort xhigh --dangerously-skip-permissions` |
| Marketing / CTA / ad copy / conversion / social | Grok Build | `grok -m grok-build` |
| Research — gather / web / current / social / market | Grok Build | `grok -m grok-build` |
| Research → written report / brief | Claude Opus | `claude --model opus --effort xhigh --dangerously-skip-permissions` |
| **Orchestration / planning / analysis (the brain)** | **Claude Fable 5 high** | `claude --model fable --effort high --dangerously-skip-permissions` |
| Architecture / hard reasoning / plan a thorny change | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| **Implementation / code writing (executor)** | **GPT-5.5 xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| **Codebase reading / scouting → report to the brain** | Composer 2.5 (reader) | `grok -m grok-composer-2.5-fast` |
| Debugging / investigation / root-cause | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| Verification — cheap/bulk (build passes? tests green?) | GLM 5.2 (OpenCode) | `opencode run -m zhipuai-coding-plan/glm-5.2 "<prompt>"` |
| Verification — taste (does this UI/copy read well?) | Claude Opus | `claude --model opus --effort xhigh --dangerously-skip-permissions` |
| **Review** — adversarial second opinion on finished work | **A DIFFERENT model than the author** (see Quality patterns) | reviewer-dependent |
| **Security audit** — hunt exploits, rate by real exploitability | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| **Plan** — draft a plan for a thorny change | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |

**Effort dial** (per lane):
- Codex: `-c model_reasoning_effort=<low|medium|high|xhigh>`. **xhigh is the
  premium reasoning tier** — use it for architecture, hard reasoning, and
  debugging (Codex xhigh is worth the tokens; the "furnace" concern is about
  Claude/Fable xhigh, NOT Codex). Bulk/execution work stays at high or lower.
- Claude: `--effort <low|medium|high|xhigh>`. **Opus defaults to `xhigh`** by
  Joel's override (2026-07). Note: this overrides the general "Claude/Fable
  xhigh is a furnace / diminishing returns" caution — his call, his tokens. Dial
  a specific call down to `high` only if xhigh is visibly overkill for it.
- OpenCode/GLM: verify runs **headless** — `opencode run -m <model> "<prompt>"`.
  Free models available (no cost): `opencode/nemotron-3-ultra-free` (strongest),
  `deepseek-v4-flash-free`, `mimo-v2.5-free`, `north-mini-code-free`. GLM 5.2
  stays the DEFAULT tester; free models are a fallback/experiment lane, not the
  CI-critical default (free-model uptime isn't guaranteed).
- Grok: `--effort <level>` (only `grok-composer` supports it; `grok-build` doesn't).

## Judgment layer — lives in the admiral skill (don't pick a model)

The orchestration judgment (mission loop, agent supervision, done gate) is
consolidated into the **admiral skill's "Judgment layer" section** — the
standalone done-check / escalate / orchestrate skills are retired (2026-07-06).
The contracts still consume `route`: the mission loop fills seats from this
table, supervision re-routes via it, and the done gate picks its cross-vendor
reviewer (`reviewer ≠ author`) from the Review row. Validated shape
(Hashimoto/Jinjing): planner → coder → judge with premium brains only on
plan+judge. For long unattended runs the driver seat defaults to Codex to save
Claude/Fable tokens; Fable/Claude drives when taste or planning judgment is in
the loop. Auto-advance is **`dev`-only**; `master`/remote never happen
unattended. If Fable's credits run dry, the commodore respawns Opus manually
with `admiral task start <n> --agent-command "claude --model opus --effort xhigh --dangerously-skip-permissions"`.

## Quality patterns (these matter more than the roster)

The routing table picks who does the work. These three patterns decide whether
the work is *good*. They're the difference between a routing table and a
workflow — borrowed from oh-my-pi (advisor) and oh-my-openagent (hyperplan /
security-research), adapted to these CLIs.

### 1. Cross-model review — the reviewer is never the author

The single highest-leverage habit. After substantive work, a **different model**
reviews it — different models fail differently, so a fresh model catches what the
author's blind spots missed. Rule: `reviewer != author`.

| Work done by | Reviewed by |
|---|---|
| Codex (impl / debug / arch) | Claude Fable or Opus |
| Grok Composer (scout report / any code it wrote) | Codex xhigh |
| Grok Build (copy) | Claude Opus (copy) |
| Claude (docs / design / a plan) | Codex xhigh |
| GLM (cheap verify) | escalate to Claude/Codex only if the cheap pass is uncertain |

Review prompt shape: *"Adversarially review this. Assume it's wrong. Find the
bug / the weak claim / the edge case the author missed. Rate findings P0–P3 with
a confidence score. Don't rubber-stamp."*

### 2. Security audit — a distinct job, not general review

Route to **Codex xhigh** with a hunter prompt, separate from #1: *"Hunt exploits
in this code. For each, rate by ACTUAL exploitability (not theoretical), P0–P3,
and sketch a proof-of-concept. Ignore style; find the ways this gets owned."*
Real exploitability rating (not a generic checklist) is what makes it useful.

### 3. Plan before thorny work — draft, then attack the plan

For anything hard (architecture, a risky refactor, a multi-step change), plan
*before* spending execution tokens:

1. **Draft** the plan with Codex xhigh (or interview first with the `grill-me`
   skill to nail scope and ambiguities).
2. **Attack** it with a *different* model (Claude Opus): *"This plan will be
   executed. Find where it's wrong, underscoped, or will break. What's the
   author not seeing?"*
3. **Execute** only the surviving plan.

A bad plan caught here costs one review; caught after execution it costs the
whole build. This is why it pays for itself.

### 4. Signal the flagship — mid-task advisor consult, not a respawn

The advisor pattern (Anthropic's Sonnet+Fable-advisor data: most of the
premium model's accuracy at a fraction of its cost): a cheap executor works
every turn, and the premium brain is a **one-shot tool call** consulted only
when stuck. In this fleet the advisor is the **flagship** — Claude Fable,
headless.

Trigger: the same step has failed **twice** (a test that won't pass, a bug
that moved, an approach that keeps unraveling). Don't grind a third time and
don't respawn — signal the flagship:

```
claude -p "Flagship consult. I am <model> working <task/branch>. Goal: <one
line>. I tried: <attempt 1, attempt 2 — what happened>. Relevant files:
<paths>. Question: what am I missing / what's the right approach?"
```

One shot, act on the answer. Costs pennies; a wrong-path hour costs the task.
Escalate to the human/commodore only when the flagship's answer requires a
decision that isn't yours (scope, product, irreversible action). Note the
consult and its outcome in the work log/report. If the stuck work IS Claude's
(reviewer ≠ author holds for advice too), signal Codex xhigh instead:
`codex exec "<same consult shape>"`.

**Step 0 — ceremony check (the ~30-minute rule).** Before picking a model, ask
whether the work is under ~30 minutes of agent effort or confined to one
file/concern. If yes: run it inline — current session or a direct shell launch,
no Admiral/Shogun task, no new worktree, no spawned agent — unless the user
explicitly asks for a tracked task. The issue + worktree + spawn + review + land
ceremony is fixed overhead that small work never pays back. At or above the
threshold, or when work parallelizes or has dependencies, dispatch normally.


1. Classify the work into a row above. Quick map:
   - **Persuasion voice** (marketing, CTA, ad, conversion, social) →
     **Grok `-m grok-build`** — punchy, current, big context.
   - **Considered voice** (docs, naming, API, brand, long-form) → **Claude**
     — taste and restraint.
   - **Research / web / current-events / market / social scan** →
     **Grok `-m grok-build`** (the search-capable model; web search ON by
     default, native X/live-search moat).
   - **Research → a written report/brief** → gather with Grok, then hand the
     findings to **Claude** for the writeup (the "considered voice" lane).
     For a *marketing* deliverable, keep it all in Grok (voice + research in
     one session, no handoff).
   - **Orchestrate / plan / analyze → Claude Fable 5 high (the brain).**
   - **Write code / implement → Codex GPT-5.5 xhigh (the executor).**
   - **Read/scout a codebase and report → Grok Composer 2.5.**
   - Debugging/root-cause → Codex. Cheap verify (build/tests) → GLM. Taste
     verify (does UI/copy read well) → Claude.
2. When two rows fit (e.g. "verify"), split by **cost/stakes**: mechanical or
   high-volume → the cheap lane (GLM); judgment or user-facing → the taste lane
   (Claude/Codex). For copy, split by **register**: persuasion → Grok,
   considered → Claude. When unsure which voice you want, run the same brief
   through both once — voice preference is taste, and a side-by-side settles it.
3. Output the launch command. Three ways to launch, pick by context:

   - **Directly in a shell** (the plain case):
     ```
     codex -m gpt-5.5 -c model_reasoning_effort=high --dangerously-bypass-approvals-and-sandbox "<prompt>"
     ```
   - **In an existing Orca worktree** (fresh agent in the current checkout —
     here, Composer as reader/scout):
     ```
     orca terminal create --worktree active --command "grok -m grok-composer-2.5-fast --always-approve" --json
     ```
   - **In a new Orca worktree** — `worktree create --agent <id>` only takes a
     bare id (`codex`, `claude`), so it CANNOT carry model/effort/bypass flags.
     To get the flagged command into a new worktree, create it, then drop the
     agent in with `terminal create --command "<full command>"`:
     ```
     orca worktree create --name <branch> --base-branch dev --json
     orca terminal create --worktree name:<branch> --command "codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox" --json
     ```

## Verified flag reference

Each agent's real launch vocabulary (source: `<cli> --help`):

- **Claude Code** — `--model <alias|full>`, `--effort <low|medium|high>`,
  `--dangerously-skip-permissions` (or `--permission-mode bypassPermissions`).
- **Codex** — `-m/--model <model>`, `-c model_reasoning_effort=<level>` (TOML
  config override), `--dangerously-bypass-approvals-and-sandbox`,
  `-a/--ask-for-approval <never|on-request|...>` if you want it to still ask.
  Non-interactive: `codex exec "<prompt>"`.
- **OpenCode** — `-m provider/model` (GLM: `zhipuai-coding-plan/glm-5.2`),
  `--variant <high|...>` for reasoning effort, `--agent <name>`. Headless:
  `opencode run -m <...> "<message>"`. List models: `opencode models`.
- **Grok** — one CLI, two models, pick with `-m/--model`:
  - `grok-build` — xAI's coding model, **512K context**, **backend web search
    supported**. Use for research/web/current and marketing copy (bigger context
    + search). This is the researcher + copy model.
  - `grok-composer-2.5-fast` — Cursor's coding model (the default), 200K. Now
    the **reader/scout** seat: reads a codebase and reports back to Fable. Still
    writes code when Joel explicitly routes a write task to it.
  Permissions: Joel's `~/.grok/config.toml` already sets
  `permission_mode = "always-approve"` globally, so no per-launch flag needed
  (add `--always-approve` only to be explicit). Other flags: `--check`
  (self-verify loop, headless), `--best-of-n <N>`, `--reasoning-effort <effort>`
  (only `grok-composer` supports effort; `grok-build` does not). Web search:
  `--disable-web-search` to turn off. List models: `grok models`.

## Web search, by agent

Verified default/opt-in state (`<cli> --help`):
- **Grok** — use `-m grok-build` (it has `supports_backend_search`; the default
  `grok-composer-2.5-fast` is coding-only). Web search default-ON + native
  X/live firehose. Best for current-events, social, market, trend research.
- **Codex** — `--search` enables the native `web_search` tool (opt-in). Good
  for technical/doc research *during* a coding task.
- **Claude** — WebSearch / WebFetch tools built in. Best when the research
  *writeup* wants taste (report, brief).
- **OpenCode/GLM** — no web search surfaced; treat GLM as offline compute.

## Notes

- `--agent <id>` (Orca `worktree create`) launches a *runtime* by bare name and
  cannot pass model/effort/bypass. `terminal create --command "<text>"` runs an
  arbitrary command string — that's the escape hatch for flagged launches.
- Bypass is on by default here; drop the `--dangerously-*` / `--always-approve`
  flag from a command to restore that agent's normal approval prompts.
- This routing is session-layer and works anywhere. It is independent of Admiral
  (the work-tracker) and Orca (the worktree spawner); it just decides *what
  command to run*.
