## What to build

<one paragraph: the contract this slice unlocks and the user-visible outcome>

## API seam

<the boundary this slice owns: module/file, the functions or types it exposes,
the data shape in and out, and who owns it. If a slice needs three unrelated
systems booted before it can be checked, the seam is wrong — sharpen it. Omit
only for a slice with no code seam (pure docs/config).>

## Acceptance criteria

- [ ] ...

## Verification

<the exact commands/scenarios/probes that prove the seam, and what must stay
green. Name commands, not intentions — e.g. `npm test`, a route to open, a
fixture to run. This is what the reviewer and the validation command check.>

## Blocked by

None - can start immediately

## Rationale

<optional, written as the slice lands: why this shape and not the obvious
alternative, the invariants that must keep holding, and any approach tried and
rejected (dead ends). Leave blank while building; fill from the implementation
Deviations log before review. This is what accretes into docs/specs/<area>.md —
the durable *why*, beyond the *what* the acceptance criteria already record.>
