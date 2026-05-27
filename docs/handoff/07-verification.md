# 07 — Verification

Before moving a Superset task to **In Review**, prove the work is correct. Don't hand back work you haven't verified.

## Phase 1 — Mega menu rebuild

### Visual check (browser)

1. Run `npm run dev` from your worktree root and load `https://newtech.local` (DevKinsta) or wherever the local site serves from.
2. Hover each of the four top-level labels: **Get started**, **Choose your machine**, **How to buy**, **Get owner support**.
3. For each flyout, verify:
   - The intro column shows on the left with the right title + body + secondary CTA
   - Three groups render in columns 2–4
   - The first item in each group is visually emphasized (anchor item)
   - Every link in `03-mega-menu-spec.md` is present and points at the correct URL
   - Section accent color matches (green / blue / orange / aqua)
4. Click through every link. Each one either lands on the existing page (200 OK) or shows a clearly-placeholder NEW page (Phase 2 work).
5. Test the mobile menu at 375px wide:
   - Four sections expand cleanly
   - Tap targets are at least 44×44px
   - All links work
6. Check the persistent "Talk to a specialist" CTA renders in the desktop header right rail.

### Code check

```bash
# Run from your worktree root
npm run build          # production build must succeed
# If the theme has phpstan or phpcs configured, run those too
```

### Active-state check

Visit each of these URLs and confirm the correct top-level label is highlighted:

| URL | Should highlight |
|---|---|
| `/` | (none — home) |
| `/learning-center/` | Get started |
| `/machines/` | Choose your machine |
| `/roof-wall-panel-machines/` | Choose your machine |
| `/seamless-gutter-machines/` | Choose your machine |
| `/ssq3-multi-pro/` | Choose your machine |
| `/configurator/` | How to buy |
| `/contact/` | How to buy |
| `/leasing-financing/` | How to buy |
| `/service-hub/` | Get owner support |
| `/manuals/` | Get owner support |
| `/warranty-registration/` | Get owner support |

If any of these don't highlight correctly, fix the `current_paths` array in `desktop-nav.php`.

### Regression check

Visit the top 10 highest-traffic pages (ask the user for the list — or use a sample of the URLs in `05-pages-to-reuse.md`). Confirm none of them 404 or render broken. The IA change must not break a single existing URL.

## Phase 2 — Anchor page scaffolds (placeholders, not real content)

For each of `/start-here/`, `/choose-your-machine/`, `/owner-support/`, `/how-buying-works/`:

1. The PHP template file exists in `app/page-<slug>.php`.
2. The template file has a `Template Name:` header so it appears in the WP admin's Page Attributes → Template dropdown.
3. The WP page exists with the correct slug:
   ```bash
   docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech post list --post_type=page --name=start-here --allow-root
   ```
4. The page has the template assigned (`_wp_page_template` post meta points at the right file).
5. The page renders without PHP warnings (check `wp-content/debug.log` if `WP_DEBUG` is on).
6. The rendered page shows the **placeholder body** — a heading with the page title and a clear `🚧 In development` marker. NOT real content.
7. The mega-menu link pointing at this page now lands on it (HTTP 200), even though the body is placeholder.
8. The page is mobile-responsive at 375px (the placeholder template should be — it's just centered text).

**You are NOT verifying:**
- Production copy (there isn't any)
- Content blocks like hero, value-prop cards, FAQs (none exist on these pages yet)
- Forms or interactive features (out of scope)

## Phase 3 — Gap page scaffolds (placeholders, not real content)

Same as Phase 2, for each of `/compare-roof-panel-machines/`, `/roof-panel-vs-gutter/`, `/add-a-machine/`, `/first-time-buyer-playlist/`, `/request-parts/`.

Each one needs:
1. PHP template file in `app/page-<slug>.php`
2. `Template Name:` header
3. Matching WP page with the template assigned
4. Placeholder body rendered
5. Mega-menu link lands on it (HTTP 200)

**Special note on `/request-parts/`:** Scaffold the placeholder template like the others. Do NOT build the parts form. The data-source question gets answered in a separate conversation.

**Special note on `/compare-roof-panel-machines/`:** Scaffold the placeholder. Do NOT build the comparison table yet. The table needs design + data wiring that's out of scope for this pass.

## Branch & commit hygiene

Before moving the task to **In Review**:

```bash
git status                    # clean working tree, all changes committed
git log --oneline -10         # commits have clear messages explaining WHY
git push origin <branch>      # your feature branch is pushed
```

Commit messages should follow the repo convention. Include this footer in each commit:

```
Co-Authored-By: Mastra Code (anthropic/claude-opus-4-7) <noreply@mastra.ai>
```

(Or your equivalent agent footer.)

## What the Superset task description should look like when handed back

```
Branch: feat/nav-ia
Workspace: <your worktree>
Path: <absolute path>
Agent: Claude (or whichever)
Goal: Rebuild mega menu IA: 4 action labels, 2-lane flyouts
Status: In Review
Summary:
  - Replaced get_desktop_nav() items with 4 action-driven labels
  - Added new mega-menu type 'flyout-groups' rendering in mega-menu.php
  - Updated mobile-nav.php to mirror the same structure
  - Swapped homepage hero CTAs to the 4 action paths
  - Scaffolded 9 placeholder PHP page templates (no production content yet)
  - Created 9 WP pages with templates assigned and 🚧 placeholder bodies
  - All existing URLs verified intact
Commits:
  <list of commit SHAs and one-liners>
Validation:
  - npm run build: passed
  - All 4 mega menus render correctly on desktop and mobile
  - Active-state highlighting verified for 12 representative URLs
  - No existing URLs regressed (sampled 10 high-traffic pages)
Risk:
  - <anything the user should review carefully — e.g., a slug you weren't sure about>
```

## When to stop and ask the user instead of pushing through

- You're tempted to write real page content beyond the placeholder
- You're tempted to pull in `hero-asymmetric.php`, `value-prop-cards.php`, or any content-building template part on a new page
- You're tempted to build Gutenberg blocks for any new page
- An existing page slug looks awkward in the new IA (e.g. `/ntm-machine-quote-checklist-thank-you/` doesn't feel like a "quote checklist" link)
- The homepage hero component can't cleanly fit four CTAs
- A "NEW" URL in the spec conflicts with an existing slug
- A mobile-menu redesign feels necessary
- You think a new CSS file, design token, or color is required

Asking is cheap. Guessing isn't. This pass is scaffolding only — when in doubt, leave it for the next pass.
