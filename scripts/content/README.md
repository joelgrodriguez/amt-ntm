# Content review — export pillar/landing page copy to CSV

The marketing copy on NTM's pillar and landing pages **is not editable in
WordPress admin.** Those pages have empty post content; their copy is hardcoded
in the theme's PHP (template parts and the data functions in
`app/inc/machines-data.php`). So review happens in a spreadsheet, and edits come
back to a developer to apply to the code.

This is the round-trip:

```
npm run content:export   →  CSV the content team edits  →  developer applies edits to PHP  →  ships to dev
```

## 1. Export (developer)

```bash
npm run content:export
```

Writes CSVs to `scripts/content/exports/` (gitignored — regenerate any time):

- `copy-review-<page>.csv` — one file per page (Home, About, Safety, …), focused.
- `copy-review-all.csv` — every string, combined.

Each row is one piece of copy. Columns:

| column            | meaning                                                        |
|-------------------|---------------------------------------------------------------|
| `page`            | which page (`home`, `safety`, `uniq`, …)                       |
| `section`         | where on the page (`Hero`, `Portability Pillars`, `Faq Items`)|
| `field`           | the role (`title`, `body`, `label`, `question`, `answer`, …)   |
| `key`             | **match anchor — do not edit.** How the edit is applied back.  |
| `current_content` | the existing copy. **Do not edit.** Leave it as the reference. |
| `new_content`     | **the only column you edit.** Put the revised copy here.       |

## 2. Review (content team)

1. Open the CSV in Excel or Google Sheets (it opens natively, accents included).
2. Read `current_content`. To change it, type the replacement in `new_content`.
3. **Leave a row's `new_content` blank to keep the current copy.** Only filled-in
   rows are treated as changes.
4. **Do not touch `key` or `current_content`** — those are how the developer finds
   the exact string to replace. Editing them breaks the apply step.
5. Don't reorder, insert, or delete rows. Add a `note` column at the far right if
   you need to leave a comment; it's ignored on import.
6. Hand the file back (same filename is fine).

What's in scope: the marketing copy on Home, About, Machines (overview), Roof &
Wall, Seamless Gutter, UNIQ, Safety, Trailer, Choose Your Machine, Start Here,
and Finance Center. Not in scope (yet): deep per-machine spec sheets, and any
copy that would change a **page URL** — flag URL changes separately, they need a
redirect, not just a copy edit.

## 3. Apply (developer)

Edits are applied **manually, with review** — no auto-rewrite of source in v1.
For each row where `new_content` is non-empty and differs from `current_content`:

1. Locate the string **by its key's position** — the Nth `__()` call
   (`<file>::__#N`) or the Nth copy-pair in the function (`<file>::<fn>()::pair#N`).
   Locate by position, not by a blind text search: short labels like "Service" or
   "Training" recur, so a content-only `str_replace` is ambiguous.
2. **Guard:** confirm the string at that position still equals `current_content`.
   If it doesn't (the file changed since export), flag the row and skip it —
   re-export and re-review rather than risk editing the wrong string.
3. Swap in `new_content` at that exact position, review the full diff, confirm no
   slug/URL changed, then commit and land to `dev` via the normal worktree flow.

## How it works

`export-copy.php` reads `manifest.php` (the page → files map that defines scope),
then uses PHP's `token_get_all()` to parse each file and pull out:

- every `__('…', 'standard')` call in the template parts, and
- every `'copy-key' => '…'` pair inside the named data functions (FAQs, UNIQ
  resources, ROI, pillars), skipping non-copy keys like `url`, `icon`, `kind`.

Parsing the tokens (instead of regex) is what makes apostrophes, commas, `$2M+`,
and both quote styles come through intact. To widen scope, add a page or a
function name to `manifest.php` and re-run.
