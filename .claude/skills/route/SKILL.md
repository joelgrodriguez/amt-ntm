---
name: route
description: >-
  Route work across Fable, Codex Sol, Grok 4.5, Claude Opus 4.8, GLM,
  and Gemini Antigravity with verified launch flags. In Admiral-installed
  repos, `.admiral/config.json` `router` is the operational source of truth.
---

# Route

Current contract: in Admiral-installed projects, routing lives in
`.admiral/config.json` under `router`. Use `admiral task start <n> --route
[seat]` for tracked tasks, or run the chosen command directly for one-offs.
Never use a bare Orca `--agent` id when model, effort, or bypass flags matter.

## Fleet

Codex Sol medium is the default long-lived driver/commodore. Fable 5 high is a
bounded one-shot brain for consequential mission planning and synthesis, not
the terminal watcher. Reserve Codex high or xhigh for genuinely hard reasoning,
debugging, architecture, and exploit hunting.

## Role Matrix

This is the canonical fleet. Admiral's seven fixed seats are launch primitives;
the debugger, security, and Gemini critic roles are workflows composed from
those seats and direct one-shot commands.

| Role | Model | Job |
| --- | --- | --- |
| Brain | Fable 5 high | Mission, synthesis, planning, and decisions |
| Scout | Grok 4.5 | Codebase, web, and market research |
| Builder | Codex Sol medium, high when warranted | Implementation |
| Debugger | Codex Sol xhigh | Root-cause analysis and difficult fixes |
| Security strategist | Fable 5 high | Threat model and audit direction |
| Exploit hunter | Codex Sol xhigh | Proofs of concept and real exploitability |
| Copywriter | Grok 4.5 | Persuasive customer-facing copy |
| UX/SEO critic | Gemini 3.1 Pro High | Independent customer and search perspective |
| Bulk critic | Gemini 3.5 Flash High | Page batches, metadata, and inventories |
| Verifier | GLM 5.2 | Tests, builds, and mechanical checks |
| Flagship taste | Claude Opus 4.8 xhigh | Final brand and product review |
| Judgment fallback | Gemini 3.1 Pro High | Final alternate for scout, marketer, and docs |
| Mechanical fallback | Gemini 3.5 Flash High | Final alternate for implementer and verifier |

Fable-primary orchestrator and reviewer seats end with GLM 5.2. Opus is `xhigh`
because that is the explicit flagship-taste override; normal building starts at
Codex medium and moves to high only when the implementation warrants it.

The fixed Admiral seats are `orchestrator`, `scout`, `implementer`, `verifier`,
`reviewer`, `marketer`, and `docs`. The package defaults are:

| Work | Seat | Launch command |
| --- | --- | --- |
| Consequential one-shot planning / synthesis | `orchestrator` | `claude --model fable --effort high --dangerously-skip-permissions` |
| Feature / bugfix / refactor / backend / UI / chore | `implementer` | `codex -m gpt-5.6-sol -c model_reasoning_effort=medium --dangerously-bypass-approvals-and-sandbox` |
| Codebase scouting / research | `scout` | `grok -m grok-4.5` |
| Marketing / CTA / ad copy / conversion / social | `marketer` | `grok -m grok-4.5` |
| Build/test verification | `verifier` | `opencode run -m zhipuai-coding-plan/glm-5.2 "<prompt>"` |
| Routine cross-model code review | `reviewer` | `claude --model fable --effort high --dangerously-skip-permissions` |
| Docs / knowledgebase / README / guide / changelog / ADR | `docs` | `claude --agent docs-writer --model claude-opus-4-8 --effort xhigh --dangerously-skip-permissions` |

Fallback chains preserve a different-vendor first alternate, then end with GLM
5.2 for Fable-primary seats, Gemini 3.1 Pro High for judgment-heavy seats, or
Gemini 3.5 Flash High for mechanical seats. Gemini fallbacks use the verified
read-only `agy --mode plan --print` one-shot form.

`typeDefaults` maps issue `type:*` labels to seats. Each seat has a full
`command`, enforced `timeout`, and ordered `fallback` list. Defaults are:
orchestrator 15m, scout 8m, implementer 30m, verifier 10m, reviewer 5m,
marketer 8m, and docs 15m. Fallbacks are operator guidance; Admiral does not
auto-respawn or retry agents. `admiral upgrade --apply` adds missing defaults
without replacing project seat edits.

Existing installs keep configured seat values during ordinary upgrade. To adopt
the package's current type mappings, seat commands, fallbacks, and timeouts, run
`admiral upgrade --refresh-router` to preview exact dotted keys, then
`admiral upgrade --refresh-router --apply`. Custom router extensions and
non-router config remain untouched. New installs receive the current fleet by
default.

Review-lane recommendations are separate from launch routing. `admiral task
report <n>` reads `.admiral/config.json` `reviewLanes` and issue `risk:*`
labels, then reports lanes without spawning agents.

## Launch Mechanics

Tracked task, inferred from `type:*`:

```bash
admiral task start <n> --route
```

Tracked task, explicit seat:

```bash
admiral task start <n> --route verifier
```

Tracked task, explicit normal implementation command:

```bash
admiral task start <n> --agent-command "codex -m gpt-5.6-sol -c model_reasoning_effort=medium --dangerously-bypass-approvals-and-sandbox"
```

Explicit commands can opt into enforcement with `--timeout 10m`. A timeout
terminates the process group, exits 124, and records reportable runtime
metadata.

Existing Orca worktree, extra terminal:

```bash
orca terminal create --worktree issue:<n> --command "<full launch command>" --json
```

New Orca worktree outside Admiral:

```bash
orca worktree create --name <branch> --base-branch dev --json
orca terminal create --worktree name:<branch> --command "<full launch command>" --json
```

`orca worktree create --agent <id>` accepts only a bare runtime id. It cannot
carry model, effort, or bypass flags.

## Security Workflow

Security is two jobs, in order:

1. Fable creates the threat-model strategy: assets, trust boundaries, attacker
   capabilities, abuse paths, and ranked exploit hypotheses.
2. Codex Sol xhigh hunts concrete exploits from that strategy, rates actual
   exploitability P0-P3, and sketches proof-of-concept paths.

```bash
claude --model fable --effort high --dangerously-skip-permissions "Threat-model this system. Map assets, trust boundaries, attacker capabilities, abuse paths, and ranked exploit hypotheses."
codex -m gpt-5.6-sol -c model_reasoning_effort=xhigh --dangerously-bypass-approvals-and-sandbox "Use the threat model to hunt concrete exploits. Rate actual exploitability P0-P3 and sketch a proof of concept for each finding."
```

Do not collapse these into generic review. Strategy and exploit hunting catch
different failures.

## Customer-Facing Page Workflow

Grok 4.5 writes the customer-facing copy first. Gemini 3.1 Pro High then
critiques clarity, credibility, specificity, objections, and CTA friction in a
read-only pass. The author applies the critique.

```bash
grok -m grok-4.5 --single "Act as the copywriter. Rewrite the customer-facing page for one clear job, concrete value, credible proof, and one primary CTA."
agy --model "Gemini 3.1 Pro (High)" --mode plan --print "Critique the customer-facing page copy. Find unclear claims, weak proof, missing objections, and CTA friction. Rank fixes by conversion impact; do not edit files." --print-timeout 10m
```

The Antigravity executable is `agy`. The verified discovery and one-shot
commands are:

```bash
agy models
agy --model "Gemini 3.1 Pro (High)" --mode plan --print "<critique prompt>" --print-timeout 10m
```

`--mode plan` keeps the critic read-only; `--print` exits after one response.
Gemini is deliberately a workflow critic rather than an eighth Admiral seat.

## Bulk Critic Workflow

Use Gemini 3.5 Flash High for repetitive review across page batches, metadata,
inventories, and other large mechanical content sets. It is a critic, not an
Admiral seat and not the final taste reviewer.

```bash
agy --model "Gemini 3.5 Flash (High)" --mode plan --print "Review this batch for missing, duplicated, inconsistent, or weak metadata. Return a prioritized inventory; do not edit files." --print-timeout 10m
```

Escalate only consequential or ambiguous findings to Gemini 3.1 Pro High or
the Opus flagship-taste pass.

## Quality Patterns

- **Substantial work** changes user-visible behavior, architecture, public APIs,
  authentication/security, stored data, payments, deployment behavior, or
  crosses multiple system boundaries. Documentation, copy-only edits,
  formatting, generated files, narrow tests, and mechanical configuration
  updates are not substantial. When uncertain, skip external review unless the
  change could realistically cause data loss, security exposure, downtime, or
  a broken customer workflow.
- After substantial work, run at most one cross-model review; the reviewer is
  never the author. The builder applies valid findings and the verifier runs
  targeted checks. Do not automatically launch a second review pass.
- Routine Codex-authored review uses bounded Fable high. If Fable authored the
  work, choose another vendor instead of reusing the reviewer seat.
- Opus 4.8 xhigh is an explicit flagship brand/product taste pass, not the
  routine code-review watcher.
- Plan before thorny work: Fable drafts, a different vendor attacks, Codex
  executes the surviving plan.
- Mechanical verification uses GLM 5.2. Terminal fallbacks use GLM or Gemini
  according to the seat; taste uses pinned Opus 4.8 xhigh.
- Codex defaults to medium. Move to high or xhigh only when the task needs the
  reasoning depth; security exploit hunting always uses xhigh.

## Flag Reference

- Claude Code: `--model <model>`, `--effort <low|medium|high|xhigh>`,
  `--dangerously-skip-permissions`.
- Codex: `-m/--model <model>`, `-c model_reasoning_effort=<level>`,
  `--dangerously-bypass-approvals-and-sandbox`; headless: `codex exec`.
- OpenCode: headless execution uses `opencode run -m <provider/model>
  "<prompt>"`.
- Grok: `-m grok-4.5`; one-shot output uses `--single "<prompt>"`.
- Antigravity: `agy --model "Gemini 3.1 Pro (High)" --mode plan --print
  "<prompt>" --print-timeout 10m`.

## Quick Map

- Default session brain and planning -> Fable 5 high
- Normal implementation -> Codex `gpt-5.6-sol` medium
- Hard debugging / architecture -> Codex Sol high or xhigh
- Security -> Fable threat model, then Codex Sol xhigh exploit hunt
- Scout / research / persuasion copy -> Grok 4.5
- Customer-facing page -> Grok copywriter, then Gemini critique through `agy`
- Bulk page / metadata / inventory critique -> Gemini 3.5 Flash High
- Docs / API prose / flagship taste -> pinned Claude Opus 4.8 xhigh
- Mechanical verify -> GLM 5.2
- Fable-primary fallback -> GLM 5.2
- Judgment-heavy fallback -> Gemini 3.1 Pro High through `agy`
- Mechanical fallback -> Gemini 3.5 Flash High through `agy`
