# Content review — export pillar/landing page copy to CSV

The marketing copy on NTM's pillar and landing pages **is not editable in
WordPress admin.** Those pages have empty post content; their copy is hardcoded
in the theme's PHP (template parts and the data functions in
`app/inc/machines-data.php`). So review happens in a spreadsheet, and edits come
back to a developer to apply to the code.

This is the round-trip:

```
npm run content:xlsx   →  Excel workbook the content team edits  →  developer applies edits to PHP  →  ships to dev
```

## 1. Export (developer)

Two formats, same data, same anchors — pick whichever the team prefers:

```bash
npm run content:xlsx     # one .xlsx workbook, a tab per page + an "All" tab
npm run content:export   # plain CSVs (one per page + combined)
```

Both write to `scripts/content/exports/` (gitignored — regenerate any time):

- `copy-review.xlsx` — **the recommended hand-off.** One tab per page plus an
  `All` tab, frozen header row, wrapped text. Sheets are unlocked, so the team
  can sort, filter, and edit freely — just leave `key` / `current_content` alone
  (they're the match anchors; see below).
- `copy-review-<page>.csv` — one CSV per page (Home, About, Safety, …), focused.
- `copy-review-all.csv` — every string in one CSV.

Scope (15 tabs): the pillar/landing pages (Home, About, Machines, Roof & Wall,
Seamless Gutter, UNIQ, Safety, Trailer, Choose, Start Here, Finance), the custom
**MACH II Landing**, the **SSQ3** and **MACH II** machine product pages (copy
from `app/data/machines/*.php`), and the shared **Machine Product UI** labels.

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

1. Open `copy-review.xlsx` in Excel (or import to Google Sheets). Each tab is a
   page; the `All` tab is everything in one place.
2. Read `current_content`. To change it, type the replacement in `new_content`.
   The sheets are unlocked so you can sort and filter; just don't edit `key` or
   `current_content` (step 4).
3. **Leave a row's `new_content` blank to keep the current copy.** Only filled-in
   rows are treated as changes.
4. **Do not touch `key` or `current_content`** — those are how the developer finds
   the exact string to replace. Editing them breaks the apply step.
5. Don't reorder, insert, or delete rows. Add a `note` column at the far right if
   you need to leave a comment; it's ignored on import.
6. Hand the file back (same filename is fine).

Some copy contains HTML (e.g. `<br>` or `<strong>`) — leave the tags as-is unless
the change is intentional; they render on the page.

Not in scope: any copy that would change a **page URL** — flag URL changes
separately, they need a redirect, not just a copy edit. Pure spec/catalog values
(prices, dimensions like "25 min") that aren't `__()`-wrapped in the data files
are also excluded; ask if the team needs those too.

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
