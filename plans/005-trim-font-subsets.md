# Plan 005: Trim unused non-Latin font subsets from the build

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- app/resources/css/fonts.css app/inc/fonts.php`
> On any change since this SHA, compare the excerpts below; on mismatch, STOP.

## Status

- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: perf
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

The theme self-hosts Noto Sans / Noto Sans Mono via `@font-face` blocks in `app/resources/css/fonts.css`. Every unicode-range subset referenced there gets emitted by Vite into `app/dist/assets/` — currently 46 woff2 files totaling ~940KB, of which 34 are Greek, Greek-ext, Cyrillic, Cyrillic-ext, Devanagari, and Vietnamese subsets that an English-language US marketing site never serves (browsers skip them via `unicode-range`, but they bloat the deployed artifact, the CSS `@font-face` table parsed on every page, and the CDN cache). Keep `latin` and `latin-ext` (accented names in testimonials/international dealer content); drop the rest.

## Current state

- `app/resources/css/fonts.css` — header comment:

```css
/**
 * Self-hosted Noto font faces.
 *
 * Mirrors the Bunny Fonts families/weights previously loaded in PHP:
 * Noto Sans 400/500/600/700 and Noto Sans Mono 400/500.
 */
```

The file is a sequence of `@font-face` blocks, one per (family × weight × subset), each preceded by a `/* subset */` comment and carrying a `unicode-range`. Example block:

```css
/* greek */
@font-face {
  font-family: 'Noto Sans';
  font-style: normal;
  font-weight: 400;
  ...
  src: url("../fonts/noto-sans/noto-sans-greek-400-normal.woff2") format("woff2");
  unicode-range: U+0370-0377,...;
}
```

- Subsets present per family/weight: greek, latin, cyrillic, greek-ext, latin-ext, devanagari, vietnamese, cyrillic-ext (Noto Sans in 400/500/600/700; Noto Sans Mono in 400/500).
- `app/inc/fonts.php` preloads only two latin files (`FONT_PRELOAD_FILES = ['noto-sans/noto-sans-latin-400-normal.woff2', 'noto-sans-mono/noto-sans-mono-latin-500-normal.woff2']`) — no change needed there.
- Font binaries live in `app/resources/fonts/noto-sans/` and `app/resources/fonts/noto-sans-mono/`.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| Count kept blocks | `grep -c '@font-face' app/resources/css/fonts.css` | `12` after the edit (2 subsets × (4 + 2) weights) |
| No stray refs | `grep -c 'greek\|cyrillic\|devanagari\|vietnamese' app/resources/css/fonts.css` | `0` after the edit |

(Worktree has no `node_modules`; do NOT run `npm run build` — the reviewer builds post-merge and verifies the emitted asset count drops.)

## Scope

**In scope**:
- `app/resources/css/fonts.css` (delete non-latin `@font-face` blocks)
- `app/resources/fonts/noto-sans/` and `app/resources/fonts/noto-sans-mono/` (delete the woff2 files no longer referenced)

**Out of scope** (do NOT touch):
- `app/inc/fonts.php` — already latin-only preloads.
- `latin` and `latin-ext` blocks/files for BOTH families — these stay.
- `vite.config.js`, `app/resources/css/_app.css`.

## Git workflow

- Branch: `advisor/005-trim-font-subsets`
- One commit, subject like: `Trim non-Latin font subsets from the build`
- Do NOT push. (`git rm` the deleted woff2 files so the deletion is tracked.)

## Steps

### Step 1: Delete non-latin `@font-face` blocks from fonts.css

Remove every `@font-face` block (including its `/* subset */` comment line) whose `src` filename contains `greek`, `greek-ext`, `cyrillic`, `cyrillic-ext`, `devanagari`, or `vietnamese` — for both families and all weights. Keep every block whose filename contains `latin` (this includes `latin-ext`). Update the header comment to note the trim:

```css
 * Subsets: latin + latin-ext only (non-Latin subsets removed 2026-07 —
 * English-language site; re-add a subset here if content ever needs it).
```

**Verify**: `grep -c '@font-face' app/resources/css/fonts.css` → `12`. `grep -c 'latin' app/resources/css/fonts.css` → `≥12`. `grep -c 'greek\|cyrillic\|devanagari\|vietnamese' app/resources/css/fonts.css` → `0`.

### Step 2: Delete the now-unreferenced font binaries

```bash
cd app/resources/fonts
for f in noto-sans/*.woff2 noto-sans-mono/*.woff2; do
  case "$f" in
    *latin*) ;;                      # keep latin + latin-ext
    *) git rm "$f" ;;
  esac
done
```

**Verify**: `ls app/resources/fonts/noto-sans/ | wc -l` → `8` (latin + latin-ext × 4 weights); `ls app/resources/fonts/noto-sans-mono/ | wc -l` → `4` (latin + latin-ext × 2 weights). `grep -rn 'noto-sans-greek\|noto-sans-cyrillic\|noto-sans-devanagari\|noto-sans-vietnamese' app/resources app/inc` → no matches.

## Test plan

No JS/CSS test harness. The greps above are the gates. Reviewer post-merge: `npm run build` succeeds and `ls app/dist/assets/*.woff2 | wc -l` drops from 46 to 12.

## Done criteria

- [ ] `grep -c '@font-face' app/resources/css/fonts.css` → `12`
- [ ] Zero non-latin subset references anywhere under `app/resources` and `app/inc`
- [ ] Deleted binaries are `git rm`'d (show in `git status` as deletions)
- [ ] `git status --porcelain` touches only fonts.css and font binaries

## STOP conditions

- The kept-block count isn't 12 after the edit (the file's family × weight × subset matrix differs from this plan's expectation — report the actual matrix).
- Any file outside `app/resources/fonts/` or `fonts.css` references a non-latin subset filename.

## Maintenance notes

- If the site ever ships translated content (Spanish manuals are mentioned in TODO.md — Spanish is covered by latin/latin-ext), only genuinely non-Latin scripts need re-adding.
- Reviewer should scrutinize: that `latin-ext` blocks survived — deleting them would break accented characters in dealer/testimonial names.
