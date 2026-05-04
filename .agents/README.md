# Shared Agent Files

`CLAUDE.md` is the source of truth for this repo.

Common agent entry points should point back to it:

- `AGENTS.md`

Shared skills live in `.claude/skills`. The `.agents/skills` path points to that same directory for agents that do not know Claude's layout.

Current shared skills:

- `spacing-system.md`
- `git-worktree-flow.md`
