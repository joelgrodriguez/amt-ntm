# Compliance & certification claims — review for counsel

**For:** Jenkins / Jake (legal review)
**Re:** certification/compliance wording that shipped live with no legal gate (unlike `/safety`, which had one — see `docs/legal/safety-copy-review.md`)
**Status:** all "Certified" language and the CE claim were **softened in code on 2026-07-01 (task #49)**. This doc lists every compliance-shaped claim on the site, what evidence exists in-repo, and what still needs counsel sign-off.

## Ground rules applied

- **NATM is self-attested, not third-party certified.** NATM's Compliance Verification Program is a member self-attestation with periodic consultation; it is not a "certification" issued by an independent body. The word **"Certified" was the exposure** and has been removed everywhere for NATM.
- **"NATM Compliant" is retained.** The compliance decal photo shipped in the trailer page (`/uploads/2023/09/NATM-Compliant-trailer-sticker.png`, rendered in `app/templates/pages/trailer/engineering.php`) substantiates "compliant," not "certified."
- **CE marking is a regulated EU self-declaration.** No CE declaration of conformity or certificate exists anywhere in this repo, so the CE claim was removed until one is produced.
- No new claims were invented; softened copy describes only equipment already documented in-repo.

---

## 1. NATM claims (trailer page, `/machines/trailer/`)

| # | Location | Before (live until #49) | After (softened) | Evidence in-repo | Counsel to confirm |
|---|---|---|---|---|---|
| 01 | `app/templates/pages/trailer/hero.php` meta rail | Label "Certified" / value "NATM Compliant" | Label "Compliance" / value "NATM Compliant" | Compliance decal photo (engineering section) | That current NTM trailers (TR23/TR23G) carry the NATM decal and NTM's CVP participation is current |
| 02 | `app/templates/pages/trailer/engineering.php` (reason 04) | Spec "Certification" · "Certified compliant with the National Association of Trailer Manufacturers, the quality and safety credential business owners look for." | Spec "Compliance" · "Built in compliance with National Association of Trailer Manufacturers (NATM) standards, the quality and safety benchmark business owners look for." | Same decal photo, rendered directly beside this copy | Same as 01; also that "standards" is the right noun for NATM's Guidelines for Recommended Minimum Manufacturing Practices |
| 03 | `app/templates/pages/trailer/vs-traditional.php` (row 7) | Concern "Certification" · "Certified compliant with the National Association of Trailer Manufacturers (NATM)." | Concern "Compliance" · "Built in compliance with National Association of Trailer Manufacturers (NATM) standards." | Same decal photo (adjacent section) | Same as 01 |
| 04 | `app/templates/pages/trailer/engineering.php` photo alt text | "NATM compliance certification sticker on an NTM trailer" | "NATM compliance sticker on an NTM trailer" | The photo itself | — |

**Nuance flagged for counsel:** the engineering section's specs (12,000 lb class, 750 lb tongue weight) describe the source article's reference trailer; the sellable TR23/TR23G models are 23,000 lb. The decal photo is from the article-era trailer. If the current TR23/TR23G units are the ones NTM attests as NATM-compliant, confirm the decal/attestation applies to them too.

## 2. CE claim (UNIQ feature grid, machines page)

| # | Location | Before (live until #49) | After (softened) | Evidence in-repo | Counsel to confirm |
|---|---|---|---|---|---|
| 05 | `app/inc/machines-data.php` `get_uniq_features()` (rendered by `app/templates/pages/machines/uniq-spotlight.php`) | Title "CE-Compliant Safety" · "Controls start drive, notching, and shear functions with safety compliance." | Title "Interlocked Safety Controls" · "Start drive, notching, and shear functions run through the controller's safety interlocks." | UNIQ maintenance-mode interlock and RFID guard sensors documented in `app/data/knowledgebase/articles.php` and `get_safety_systems()` | Whether NTM holds a CE declaration of conformity for the UNIQ controller. If yes, the CE claim can return **with the document linked**; until then it stays off. |

Legacy references to "CE-compliant safety features" remain in internal research/planning docs only (`docs/ntm-machines.md`, `docs/plans/2026-03-03-machines-page-*.md`) — those quote the old site and are not rendered anywhere.

## 3. Warranty claims (unchanged — flagged for review)

These are affirmative coverage promises; wording was **not** changed in #49 because they may be accurate, but no warranty document lives in this repo to check them against.

| # | Location | Current wording | Evidence in-repo | Counsel to confirm |
|---|---|---|---|---|
| 06 | `app/inc/machines-data.php` (roof & wall FAQ, ~line 665) | "Every NTM machine is backed by a comprehensive warranty covering manufacturing defects and workmanship. Specific coverage terms vary by model." | None | "Comprehensive" is a characterization — confirm it matches the actual limited-warranty terms, or reword to "limited warranty" |
| 07 | `app/inc/machines-data.php` (gutter FAQ, ~line 756) | "Every NTM gutter machine includes a 3-year limited warranty covering manufacturing defects and workmanship. Drive rollers (the heart of the machine) carry a lifetime warranty." | None | The 3-year term, and whether the roller warranty is a **limited** lifetime warranty against separation (as the contact FAQ states) rather than an unqualified "lifetime warranty" |
| 08 | `app/inc/contact-data.php` (FAQ, ~line 40) | "NTM offers a three-year limited warranty against manufacturer's defects, including electrical, and a limited lifetime warranty against separation of the drive rollers." + link to `/general-terms-conditions/` | Links to the published terms page (page content lives in WP, not this repo) | That this matches the current published General Terms & Conditions. Note items 07 and 08 phrase the roller warranty differently — align 07 to 08's qualified wording if counsel prefers |

## 4. Uses of "certified/certification" left alone (deliberate)

- `docs/legal/safety-copy-review.md` — this review doc's own discussion of the claims (updated with pointers here).
- Internal planning/research docs under `docs/` — historical records, not rendered.
- No customer quotes or unrelated marketing uses of "certified" were found in rendered templates (repo-wide grep, 2026-07-01).

## Sign-off

- [ ] NATM: confirm current CVP participation and decal applicability to TR23/TR23G (items 01–04)
- [ ] CE: produce declaration of conformity, or confirm the claim stays off (item 05)
- [ ] Warranty: confirm terms and align roller-warranty phrasing (items 06–08)
- [ ] Any wording above read as a CLAIM rather than FACT → flag and we rewrite or cut
