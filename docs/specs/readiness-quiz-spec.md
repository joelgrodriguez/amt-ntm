# Panel Machine Readiness Quiz — ported spec

Extracted 2026-07-16 from the Abacus.AI Next.js export at
`quiz/ntm_readiness_assessment_export/` (components/quiz-container.tsx,
results-display.tsx). This is the source of truth for the in-house port
(issue #94) so an executor does not have to reverse-engineer the React app.
The `/quiz` export directory is untracked working material and may be removed.

## Engine

Point-sum: each answer has a point value; sum all answers → total score →
score band → machine recommendation via a decision tree (tree wins over band
for the specific machine; band sets the readiness message).

## Questions (id · question · [option → points])

1. **annual_volume** — Based on ~2,000 sq ft avg roof, annual panel install sq ft?
   - Under 20,000 (~10 roofs) → 5
   - 20,000–60,000 (~10–30) → 10
   - 60,000–100,000 (~30–50) → 15
   - Over 100,000 (50+) → 20
2. **job_type** — Project types? (stores value for tree: commercial/both)
   - Residential only → 5 (`residential`)
   - Commercial only → 10 (`commercial`)
   - Both → 15 (`both`)
3. **jobs_per_year** — Roofing jobs completed annually?
   - Under 25 → 5 · 25–75 → 10 · 75–150 → 15 · Over 150 → 20
4. **portability_needs** — Importance of moving equipment between sites?
   - Shop only → 2 · Mostly shop → 5 · Frequent jobsite → 10
5. **delivery_delays** — How often panel deliveries delayed?
   - Rarely → 2 · Occasionally → 5 · Frequently → 8 · Constantly → 10
6. **machine_operator** — Crew member available to operate? (desc: free training in Aurora, CO)
   - Yes ready → 10 · Could assign → 7 · Challenge → 3
7. **panel_profiles** — Profile diversity offered?
   - 1–2 profiles → 2 · Many → 10
8. **panel_sizes** — Rib heights worked with? (value: small/large)
   - 1"–1.5" → 5 (`small`) · Up to 2.5" → 10 (`large`)
9. **additional_profiles** — Plan board & batten / soffit / flush wall / underdeck? (value)
   - No → 0 (`no`) · Yes → 5 (`yes`)
10. **power_source** — Best power source? (value)
    - Gas → 5 (`gas`) · Electric → 5 (`electric`) · Both → 10 (`both`)
11. **hydraulics** — Importance of hydraulic shear vs manual? (value)
    - Not important → 0 (`no`) · Nice to have → 5 (`nice`) · Very important → 10 (`yes`)
12. **notching** — Need automatic notching? (value)
    - No → 0 (`no`) · Yes → 10 (`yes`)
13. **budget** — Budget for machine? (value)
    - $50k–70k → 3 (`low`) · $70k–100k → 7 (`mid`) · $100k–130k+ → 10 (`high`)

## Score bands (readiness message)

- score < 30 → **Not Ready** — "current volume and operations may not justify the investment… at this time."
- score < 55 → **Somewhat Ready** — "some operational indicators… but growth may be needed first."
- score < 80 → **Ready** — "strong indicators for adopting portable rollforming equipment."
- score ≥ 80 → **Highly Ready** — "excellent candidate… let's find the right machine."

## Machine recommendation decision tree (first match wins)

1. **notching = Yes** → **SSQ3™ MultiPro** (only machine with built-in notching).
2. **panel_sizes = small AND hydraulics = no** → **SSR™ MultiPro Jr.** (compact, manual shear, lower cost).
3. **panel_sizes = small AND hydraulics = yes** → **SSH™ MultiPro** (hydraulic shear, roof panel).
4. **additional_profiles = yes** → **SSQ3™ MultiPro** (multi-profile: board & batten, soffit, underdeck).
5. _(remaining branches in results-display.tsx ~line 180+ — executor to finish extracting: large panels, commercial volume, etc. Default/fallback tier likely SSQ3 or SSH.)_

## Machine URLs (verified live 2026-07-16)

- SSQ3: `/machines/roof-wall-panel-machines/ssq3-multipro/` (id 18601)
- SSH: `/machines/roof-wall-panel-machines/ssh-roof-panel-machine/` (id 3821)
- SSR: `/machines/roof-wall-panel-machines/ssr-multipro-jr-roof-panel-machine/` (id 340)

Use relative paths + `\Standard\Url\internal()`, not hardcoded prod URLs.

## Supporting article links (per recommendation)

- SSR: "The Budget-Friendly Cost of the SSR MultiPro Jr." → `/learning-center/ntm-ssr-multipro-jr-best-budget-portable-rollformer/`
- SSH: "SSH MultiPro Roof Panel Machine: A Solid Portable Rollformer" → `/learning-center/ssh-multipro-roof-panel-machine-a-solid-portable-roll-former/`
- SSQ3: "The True Cost of an SSQ3 MultiPro" → `/learning-center/cost-of-an-ssq3-multipro-roof-wall-panel-machine/`

## Lead capture

The export's lead-capture step is a **HubSpot form embed** (`hubspot-form-container`).
This theme already loads HubSpot forms natively — reuse `app/inc/hubspot.php`
`hubspot_form()` + `app/resources/js/modules/HubspotForms.js`. Do NOT rebuild form handling.
