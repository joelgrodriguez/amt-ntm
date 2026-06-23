# Safety page — copy review for counsel

**For:** Jenkins / Jake (legal review)
**Re:** new `/safety` landing page, stakeholder review 2026-06-17 ("Safety messaging — legal gate")
**Status:** the page is built but the WP page is **Draft / not public**. It does **not** publish until you sign off. Created in draft by `scripts/db/029-safety-landing-page-draft.sh`; publish later with `wp post update <id> --post_status=publish`.

## Ground rules applied (per the 2026-06-17 room)

- **Facts only.** Every line states what equipment *is* or *does*, or points to already-published content. No claim about what an injury or outcome the equipment prevents.
- **No superlatives.** "Safest machine on the market" and any ranking claim are **deliberately excluded** (see Exclusions).
- This doc lists **every safety-worded string the page renders**, tagged FACT or CLAIM. Target is 100% FACT; anything you read as a CLAIM, flag and we rewrite or cut.

---

## 1. Hero (`templates/pages/safety/hero.php`)

| String | Tag | Note |
|---|---|---|
| Eyebrow: "Safety" | FACT | label only |
| H1: "Operator protection, engineered into the machine." | FACT | a stance about design, not a ranking |
| Subtitle: "NTM machines ship with guard interlocks, a power-interruption safety circuit, and on-controller operator alerts as part of the design. Paired with one-on-one operator training, that is how a crew runs the machine day in and day out." | FACT | each item is verifiable equipment; "as part of the design" = factual, not superlative |

## 2. Safety systems grid (`get_safety_systems()` in `app/inc/machines-data.php`)

Section header: eyebrow "Built-in safety systems" · title "What is on the machine" · subtitle "The protective equipment that ships as part of an NTM machine. Each is a system on the machine, not an add-on." — **FACT.**

| # | Title | Body | Tag | Counsel note |
|---|---|---|---|---|
| 01 | Cover & Guard Sensors | "RFID cover sensors detect when guards are in place. With a guard removed, the UNIQ control system enters maintenance mode and the automatic run, shear, and programming functions are disabled." | FACT | Describes documented UNIQ maintenance-mode behavior (knowledgebase). Confirm wording matches the manual. |
| 02 | Shear Warning Strobe | "A strobe on the controller signals before the shear actuates, so the operator has a visual cue at the point of operation." | FACT | "visual cue" is a description, not a protection promise. |
| 03 | Power Interruption Safety Circuit | "A dedicated circuit governs how the machine responds to a loss of power, so a power interruption does not leave the drive in an uncontrolled state." | FACT | Confirm "uncontrolled state" phrasing is accurate to the circuit's actual behavior. |
| 04 | Panel & Gutter Recognition | "The control system recognizes the loaded profile before a run, confirming the machine is set up for the material it is about to form." | FACT | |
| 05 | Interior LED Lighting | "Interior LEDs light the forming area so the operator can see the coil path and rollers during setup and operation." | FACT | |
| 06 | Operator Training | "Every new machine owner gets one-on-one operator training, where safe operation is covered alongside running the machine and the profiles." | FACT | Confirm "every new machine owner" is universally true (it is the standing offer per About/Ironclad copy). |

## 3. Safe-operation resources (`templates/pages/safety/safe-operation.php`)

Links to **already-published** learning-center content (lowest risk — these are live today). No new safety copy authored here; labels are the existing titles.

| Label | Links to (verified live 2026-06-23) | Tag |
|---|---|---|
| "10 Best Safety Practices When Using a Portable Rollformer" | `/learning-center/best-safety-practices-portable-rollformer-infographic/` | FACT (existing content) |
| "10 Safety Tips for Operating a Portable Roof Panel or Gutter Machine" | `/learning-center/video/safe-rollforming-machine-operation-video/` | FACT (existing content) |
| "Simple Steps to Safe Machine Operation Infographic" | `/learning-center/download/simple-steps-safe-machine-operation-infographic/` | FACT (existing content) |
| "What to Expect in a Portable Rollforming Machine Training Session" | `/learning-center/what-to-expect-portable-rollforming-machine-training-session/` | FACT (existing content) |

Section header: "Run it right" / "The equipment is half of it. These walk an operator through safe day-to-day use…" — FACT.

## 4. Final CTA (`templates/pages/safety/final-cta.php`)

| String | Tag |
|---|---|
| "Questions about operating safely?" | FACT |
| "Talk to a specialist about operator training and the safety systems on the machine you are considering." | FACT |

---

## Proof-needed TODOs (raised by research, NOT yet on this page)

These regulatory **claims** live elsewhere in the site today and were deliberately **kept off** this page until substantiated. Flagging for your call:

- **"CE-Compliant Safety"** — appears in the UNIQ feature grid (`get_uniq_features()`, `machines-data.php`). CE marking is a regulatory claim; no certificate is linked in the codebase. Decide: link CE documentation, or soften the wording.
- **"NATM compliant"** (trailer engineering page) — National Association of Trailer Manufacturers compliance claim; no credential linked. Decide: link the credential, or soften.

If you want either claim *added* to the safety page, we need the supporting document first.

## Deliberate exclusions (kept OFF the page on purpose)

- **"Safest machine on the market"** / any superlative or ranking. Adam wanted the energy; the room said state facts only. Excluded.
- **Any injury-outcome promise** ("prevents injuries," "keeps operators safe from…"). The equipment is described by what it does, not what it prevents.
- **The shear hazard warning** ("can cause serious bodily injury or death," from the support docs) — kept in the support/knowledgebase context where it belongs, not promoted to a marketing page without your direction.

## Sign-off

- [ ] Counsel reviewed all strings above (FACT/CLAIM tags accepted or corrections noted)
- [ ] CE / NATM decision made (link proof, soften, or leave off)
- [ ] Approved to publish → flip the WP page from Draft to Publish
