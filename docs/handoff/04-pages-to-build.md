# 04 — Pages to Build (templates + placeholders only)

**This pass = template scaffolds, not real content.** You're creating PHP page templates and stub WordPress pages so the new nav has somewhere to link. Production copy comes in a later pass.

## What every new page needs

1. **A PHP template file** in `app/page-<slug>.php` with a `Template Name:` header so it shows up in the WP page admin's "Page Attributes → Template" dropdown.
2. **A WordPress page** created with the matching slug, the template assigned, and a clear placeholder body.
3. **A nav link** in `app/inc/desktop-nav.php` and `app/inc/mobile-nav.php` pointing at the new page.

That's it. No content blocks, no Gutenberg patterns, no hero copy, no FAQ items.

---

## The placeholder template (use this for ALL nine new pages)

Every new page template follows this exact shape. Copy/paste, change only the `Template Name`, `$page_slug`, and `$page_title` lines.

```php
<?php
/**
 * Template Name: NTM — Start Here (placeholder)
 *
 * Placeholder template. Production content will be added in a later pass.
 * Part of the four-action IA rebuild — see docs/handoff/.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$page_slug  = 'start-here';
$page_title = 'Start Here';

get_header();
?>

<main id="primary" class="site-main">
    <section class="placeholder-page mx-auto max-w-3xl px-6 py-24 text-center">
        <p class="mb-4 font-mono text-xs uppercase tracking-widest text-neutral-500">
            In development · NTM IA rebuild
        </p>
        <h1 class="mb-4 text-4xl font-bold tracking-tight">
            🚧 <?php echo esc_html($page_title); ?>
        </h1>
        <p class="text-lg text-neutral-600">
            This page is scaffolded but not yet built. Production content will be added in a follow-up pass.
        </p>
        <p class="mt-8 font-mono text-xs text-neutral-400">
            Template: <code><?php echo esc_html(basename(__FILE__)); ?></code> ·
            Slug: <code>/<?php echo esc_html($page_slug); ?>/</code>
        </p>
    </section>
</main>

<?php
get_footer();
```

**Notes for the agent:**

- The Tailwind utility classes above are illustrative. If the project's Tailwind setup doesn't include any of them, swap for project equivalents or omit and use raw HTML. The placeholder just needs to be visible and clearly marked as WIP.
- Do NOT pull in `hero-asymmetric.php`, `value-prop-cards.php`, `final-cta.php`, or any other production template part. Those are for the content-build pass.
- Do NOT call `the_content()` — there's no Gutenberg content to render.
- Do NOT add CSS. If you must add anything beyond Tailwind utilities, ask first.

---

## The nine new pages to scaffold

For each, create the PHP template file AND the WordPress page (use WP-CLI in the DevKinsta container).

### Phase 1 — Mega menu rebuild (do this first, before any pages)

| What | Where |
|---|---|
| Restructure mega menu data | `app/inc/desktop-nav.php` — replace `items` array per `03-mega-menu-spec.md` |
| Render new flyout type | `app/templates/parts/mega-menu.php` — add new `type` branch (e.g. `flyout-groups`) |
| Mirror on mobile | `app/inc/mobile-nav.php` + `app/templates/parts/mobile-menu.php` + `mobile-menu-panel.php` |
| Persistent header CTA | `app/header.php` — confirm "Talk to a specialist" renders on the right |
| Active-state highlighting | `current_paths` arrays in `desktop-nav.php` per `05-pages-to-reuse.md` |

When this phase ships, mega-menu links pointing at `NEW` URLs should use temporary `#` placeholders. Phase 2 replaces them with real URLs as pages get scaffolded.

### Phase 2 — Anchor page scaffolds

These four are the most important. The new mega menu's anchor items point at these URLs.

| # | URL | Template file | Template Name |
|---|---|---|---|
| 1 | `/start-here/` | `app/page-start-here.php` | `NTM — Start Here (placeholder)` |
| 2 | `/choose-your-machine/` | `app/page-choose-your-machine.php` | `NTM — Choose Your Machine (placeholder)` |
| 3 | `/owner-support/` | `app/page-owner-support.php` | `NTM — Owner Support (placeholder)` |
| 4 | `/how-buying-works/` | `app/page-how-buying-works.php` | `NTM — How Buying Works (placeholder)` |

### Phase 3 — Gap page scaffolds

Same pattern. Lower priority. Scaffold them so the secondary links in the mega menu don't 404.

| # | URL | Template file | Template Name |
|---|---|---|---|
| 5 | `/compare-roof-panel-machines/` | `app/page-compare-roof-panel-machines.php` | `NTM — Compare Roof Panel Machines (placeholder)` |
| 6 | `/roof-panel-vs-gutter/` | `app/page-roof-panel-vs-gutter.php` | `NTM — Roof Panel vs Gutter (placeholder)` |
| 7 | `/add-a-machine/` | `app/page-add-a-machine.php` | `NTM — Add a Machine (placeholder)` |
| 8 | `/first-time-buyer-playlist/` | `app/page-first-time-buyer-playlist.php` | `NTM — First-Time Buyer Playlist (placeholder)` |
| 9 | `/request-parts/` | `app/page-request-parts.php` | `NTM — Request Parts (placeholder)` |

> **Note on `/request-parts/`:** still scaffold the placeholder. Don't try to build the form. The data-source question is a separate decision.

---

## How to create the WP page for each template (WP-CLI)

Run inside the DevKinsta PHP container. Example for `/start-here/`:

```bash
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech post create \
  --post_type=page \
  --post_status=publish \
  --post_title='Start Here' \
  --post_name='start-here' \
  --post_content='Placeholder. See template.' \
  --allow-root
```

Get the new post ID from the output, then assign the template:

```bash
docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech post meta update <POST_ID> \
  _wp_page_template page-start-here.php \
  --allow-root
```

Repeat for the other eight pages. **Confirm with the user before creating any page** that has a slug conflict with an existing page (check `inventory/page.csv` first).

---

## What this pass does NOT include

- ❌ Writing hero copy, body copy, or any production text for the new pages
- ❌ Designing hero blocks, value-prop cards, FAQ accordions, or any content sections
- ❌ Building Gutenberg blocks, block patterns, or reusable blocks
- ❌ Adding new CSS, design tokens, or component classes
- ❌ Wiring up forms, calculators, or interactive features
- ❌ Building the `/request-parts/` form (placeholder only — data source TBD)

These are all explicitly out of scope for this pass. They get scoped and assigned later, once the IA is shipped and reviewed.

---

## What this pass DOES include (the definition of done)

✅ Mega menu (desktop + mobile) rebuilt per `03-mega-menu-spec.md`
✅ Homepage hero CTAs swapped to point at the four action paths
✅ 9 PHP page template files exist in `app/`
✅ 9 WordPress pages exist with the correct slugs and templates assigned
✅ Every link in the new mega menu lands on either an existing page (200 OK) or a clearly-marked placeholder
✅ Active-state highlighting works for representative URLs (see `07-verification.md`)
✅ `npm run build` succeeds with no errors
✅ No existing URL regressed
