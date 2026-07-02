# Behavior spec: content

<!-- shogun:auto — appended on land, newest first. Read top-down for current behavior. -->

## Link 'How To Change A Profile' videos from machine pages, profiles pages, and support menu (per-machine content exists in LC) — #68
*Landed 2026-07-02 · type: feature*

- Link 'How To Change A Profile' videos from machine pages, profiles pages, and support menu (per-machine content exists in LC)

## Unlock XLSX content-review sheets: drop sheet protection so the content team can sort/filter/edit freely — #47
*Landed 2026-06-24 · type: chore*

- Unlock XLSX content-review sheets: drop sheet protection so the content team can sort/filter/edit freely

## Content-review XLSX export: build a multi-tab Excel workbook (one tab per page + All tab, locked reference columns) from the content CSV extractor — #46
*Landed 2026-06-23 · type: feature*

- Content-review XLSX export: build a multi-tab Excel workbook (one tab per page + All tab, locked reference columns) from the content CSV extractor

## Content-review CSV export: extract pillar/landing page copy from PHP to an editable CSV for the content team, with a human-reviewed re-apply path — #45
*Landed 2026-06-23 · type: feature*

- Content-review CSV export: extract pillar/landing page copy from PHP to an editable CSV for the content team, with a human-reviewed re-apply path

## Page ID 11062 ("NTM Machine Quote Checklist", slug `ntm-machine-quote-checklist-thank-you`) opens with `<h3>Thank you for submitting the form.</h3>` — leftover confirmation copy on a page that has no form. Rewrite the heading. Page content lives in the WP DB, so the change MUST be captured as a replayable script in `scripts/db/` (repo hard rule: DB changes are wiped on fresh prod pull). — #33
*Landed 2026-06-23 · type: fix*

- Stray "Thank you for submitting the form." heading replaced with checklist-appropriate copy on page 11062.
- Change captured as an idempotent, numbered, re-runnable script in `scripts/db/` (wired into `npm run db:apply`).
- NOTE for Joel: slug contains `-thank-you`; renaming it needs a slug script + redirect entry. Flag, do not rename without sign-off.
- `npm run build` passes.
