---
name: explore-unknowns
description: Burn the fog before slicing a feature into Shogun tasks. A guided quadrant walk (known/unknown × known/unknown) that turns a vague request into a reviewed map — the thing you decompose from. Use when a feature is unfamiliar, the codebase area is new to you, "good" is something you'd only recognize on sight, or you can't yet name the slices. Runs at the Staged stage, before `shogun task create`. Pairs with the shogun skill (which slices the resulting map into blocked-by issues).
---

# Explore Unknowns

The map is not the territory. Your prompt, skills, and context are the map;
the codebase and its real constraints are the territory. The gap between them
is **unknowns** — and with a strong model, the quality of the work is
bottlenecked by how well you clarify them, not by the model. Burn the fog
*before* you slice, because an unknown found during implementation is expensive
to unwind, and an unknown that changes the architecture is expensive twice.

This skill produces one artifact: **a map** the human has reviewed. It creates
no worktrees and writes no code. When the map is clear, the shogun skill slices
it into `shogun task create --blocked-by` issues.

## The four quadrants

Walk them in order. Each stage hands the human something concrete to *react to* —
reacting beats imagining. Prefer a self-contained HTML artifact (via the Artifact
tool) whenever the answer is visual, comparative, or a set of options; use the
terminal for conceptual and scope questions.

1. **Known knowns** — what's already settled. Silently scan the code and the
   architecture knowledgebase (`docs/architecture/map.json`, `flows.json`),
   then state the settled ground back with file citations so the human can
   correct a wrong assumption before it propagates. This is the floor of the map.

2. **Known unknowns** — the answerable questions you're aware of. Resolve them
   **one at a time, highest architectural blast-radius first**, each closed by a
   user answer, a read of the territory, or a recorded OPEN. Give your
   recommended answer with each question so the human can accept, reject, or edit.
   → *This is the interview.* "Interview me one question at a time about anything
   ambiguous; prioritize questions where my answer changes the architecture."

3. **Unknown knowns** — taste and constraints so obvious you'd never write them
   down, but recognize on sight. Extract them by handing the human artifacts to
   react to, never by asking "what do you want?"
   → *This is brainstorm/prototype.* "Make one HTML file with 4 wildly different
   design directions with fake data so I can react to the layout before you touch
   the real app." Small spec changes cause large code changes — surface taste here,
   not during implementation.

4. **Unknown unknowns** — what you haven't considered at all. Sweep every file
   the feature touches for landmines, unwritten conventions, and dead prior
   attempts; teach them back as evidence cards.
   → *This is the blindspot pass.* Use the literal words: "I'm adding X but know
   nothing about this area — do a **blindspot pass** to find my **unknown
   unknowns** and teach me so I can prompt you better." Give context on who you
   are and what you already know, so the sweep targets *your* blind spots.

## References beat descriptions

When you can't describe what you want in words, point at source. A library that
implements the exact behavior, a component you like, a repo in another language —
have the agent *read the code*, not just a screenshot, and reimplement the
semantics. If a reference implementation exists, the first slice is a
replication spike, not a from-scratch guess. Capture the source links in the map.

## Hand over the map

The walk is done when a single self-contained page (or the Staged issue body)
holds all four quadrants: settled ground, resolved + still-OPEN questions, the
taste you surfaced, the landmines you found, and any reference links. That map —
not this conversation — is what the shogun skill slices. If a quadrant is still
foggy after the walk, that fog becomes the first slice's job, recorded as an
OPEN the first issue must resolve.

## Carries into implementation and after

The walk doesn't end at Staged — unknowns surface during and after the build too:

- **During:** tell the implementing agent to keep an `implementation-notes.md`
  with a **Deviations** log — when an edge case forces a change from the plan,
  it picks the conservative option, logs it under Deviations, and keeps going.
  In Shogun that deviation is also the signal to reslice: split the issue and
  re-wire `--blocked-by` rather than broaden the patch. The Deviations log is the
  raw material the land-time **Rationale** section is written from.
- **After:** before landing, have the agent produce a short explainer + a **quiz**
  on what changed — you only accept once you can pass it — and, for buy-in, a
  single pitch artifact packaging the map, the diff, and the notes. The durable
  *why* from this lands in `docs/specs/<area>.md` via the issue's `## Rationale`
  section.

Every explainer, brainstorm, interview, prototype, and reference is a cheap way
to find out what you didn't know before it gets expensive to fix.
