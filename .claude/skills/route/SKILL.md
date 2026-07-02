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
  or `worktree create`). Session-layer routing — independent of Shogun.
---

# Route

Pick the agent + launch command for a piece of work, by work type. All commands
are verified against the installed CLIs (`--help`, 2026-07). Bypass/auto-approve
is on everywhere by design — worktree isolation is the safety net, not per-call
prompts. Effort values are tunable defaults; edit them in the table below.

## The routing table

| Work type | Agent | Launch command |
|---|---|---|
| Docs / long-form / naming / brand voice | Claude Opus | `claude --model opus --effort high --dangerously-skip-permissions` |
| UI/UX design / API design | Claude Opus | `claude --model opus --effort high --dangerously-skip-permissions` |
| Marketing / CTA / ad copy / conversion / social | Grok Build | `grok -m grok-build` |
| Research — gather / web / current / social / market | Grok Build | `grok -m grok-build` |
| Research → written report / brief | Claude Opus | `claude --model opus --effort high --dangerously-skip-permissions` |
| Architecture / hard reasoning / plan a thorny change | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| Clean-spec implementation (bulk coding) | Grok Composer 2.5 | `grok -m grok-composer-2.5-fast` |
| Debugging / investigation / root-cause | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| Verification — cheap/bulk (build passes? tests green?) | GLM 5.2 (OpenCode) | `opencode -m zhipuai-coding-plan/glm-5.2 --variant high` |
| Verification — taste (does this UI/copy read well?) | Claude Opus | `claude --model opus --effort high --dangerously-skip-permissions` |
| **Review** — adversarial second opinion on finished work | **A DIFFERENT model than the author** (see Quality patterns) | reviewer-dependent |
| **Security audit** — hunt exploits, rate by real exploitability | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |
| **Plan** — draft a plan for a thorny change | Codex **xhigh** | `codex -m gpt-5.5 -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox` |

**Effort dial** (per lane):
- Codex: `-c model_reasoning_effort=<low|medium|high|xhigh>`. **xhigh is the
  premium reasoning tier** — use it for architecture, hard reasoning, and
  debugging (Codex xhigh is worth the tokens; the "furnace" concern is about
  Claude/Fable xhigh, NOT Codex). Bulk/execution work stays at high or lower.
- Claude: `--effort <low|medium|high>`. Avoid pushing Claude/Fable to its
  highest tier for routine work — diminishing returns for the token cost.
- OpenCode/GLM: `--variant high`.
- Grok: `--effort <level>` (only `grok-composer` supports it; `grok-build` doesn't).

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
| Grok (impl / copy) | Codex xhigh (code) · Claude Opus (copy) |
| Codex (debug / arch) | Claude Opus |
| Claude (docs / design) | Codex xhigh |
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

## How to route

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
   - Bulk implementation → Grok. Debugging/root-cause → Codex. Cheap verify
     (build/tests) → GLM. Taste verify (does UI/copy read well) → Claude.
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
   - **In an existing Orca worktree** (fresh agent in the current checkout):
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
  - `grok-composer-2.5-fast` — Cursor's coding model (the default), 200K. Use
    for clean-spec implementation.
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
- This routing is session-layer and works anywhere. It is independent of Shogun
  (the work-tracker) and Orca (the worktree spawner); it just decides *what
  command to run*.
