# Plan 011: Wire the manual/profile → product cross-sell (real product cards, no placeholders)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md`.
>
> **Environment check (run FIRST, before the drift check)**: your worktree may
> have been cut from the wrong base. Run `git rev-parse --short HEAD` and
> `ls plans/`. If `plans/` is missing or HEAD is not a descendant of dev, run
> `git switch -C advisor/011-machine-cross-sell dev` (or, if your worktree
> directory has vanished and your cwd fell back to the shared main checkout,
> do NOT work there — `git worktree add <scratchpad>/wt-011 -B advisor/011-machine-cross-sell dev`
> and work inside it).
>
> **Drift check**: `git diff --stat 7acf7d4..HEAD -- app/single-manual.php app/templates/parts/single/spec-sheet-layout.php app/inc/machine-product-data.php`
> Expect empty. On any mismatch with the excerpts below, STOP.

## Status

- **Priority**: P2
- **Effort**: M
- **Risk**: LOW–MED (visual change on manual singles + all spec-sheet-layout consumers)
- **Depends on**: plan 004 (DONE — `get_machine_product_link` is memoized)
- **Category**: direction / feature
- **Planned at**: commit `7acf7d4`, 2026-07-10

## Why this matters

The site exists to convert owners and shoppers into product conversations. Manual pages show "Related NTM Machines" as placeholder cards — a gray settings icon where the machine photo should be, linking to a tag archive instead of the machine's product page (`single-manual.php`, `@todo` comment from the original build). Profile/footprint/download/resource/literature pages (via the shared `spec-sheet-layout.php`) already resolve the product link but render text-only rows. Wiring real product images and product-page links turns every library page into a doorway to the configurator/quote flow. The resolver already exists and is request-memoized (`Standard\MachineProductData\get_machine_product_link()`, hardened in plan 004) — this plan is presentation wiring, not new data plumbing.

## Current state

- `app/inc/machine-product-data.php:183` — the resolver (do NOT modify it):

```php
/**
 * @return array{url: string, name: string, image: string, image_alt: string}|null
 *               Product link data, or null if the product isn't found.
 *               `image` is '' when the product has no featured image.
 */
function get_machine_product_link(string $machine_key, string $image_size = 'woocommerce_thumbnail'): ?array {
```

- `app/single-manual.php` — `$machine_tags = get_the_tags();` (line 34); `$content` keys include `related_machines`, `manuals_available` (`'%d manuals available'`), `no_machines`, `add_tags_hint` (lines 22-30). The file currently has NO `use function` import for the resolver. The Related Machines grid (inside the post loop, ~lines 75-110) renders per tag:

```php
<?php foreach ($machine_tags as $machine_tag) :
    // @todo: Connect tag to WooCommerce product
    // Find product by matching tag name to product title/SKU
    // $product = wc_get_products(['name' => $machine_tag->name, 'limit' => 1]);
?>
    <?php $manual_tag_url = add_query_arg(['post_type' => 'manual'], get_tag_link($machine_tag->term_id)); ?>
    <a href="<?php echo esc_url($manual_tag_url); ?>" class="group block border border-blue-200 bg-white hover:border-blue-500 transition-colors">
        <!-- Machine Image Placeholder -->
        <div class="aspect-video bg-blue-50 flex items-center justify-center border-b border-blue-200">
            <?php icon('settings', ['class' => 'w-12 h-12 text-blue-300 group-hover:text-blue-500 transition-colors']); ?>
        </div>
        <div class="p-4">
            <p class="font-medium text-blue-900 group-hover:text-blue-500 transition-colors">
                <?php echo esc_html($machine_tag->name); ?>
            </p>
            <p class="text-xs text-blue-500 mt-1 font-mono">
                <?php printf(esc_html($content['manuals_available']), $machine_tag->count); ?>
            </p>
        </div>
    </a>
<?php endforeach; ?>
```

- `app/templates/parts/single/spec-sheet-layout.php:176-191` — the shared compat list ("Rolls On" on profiles; also used by single-footprint/download/resource/literature):

```php
<?php if (is_array($machine_tags) && !empty($machine_tags)) : ?>
    <ul class="grid gap-2 list-none p-0 m-0">
        <?php foreach ($machine_tags as $machine_tag) :
            $product_link = get_machine_product_link($machine_tag->slug);
            $href = $product_link['url'] ?? get_tag_link($machine_tag->term_id);
        ?>
            <li>
                <a href="<?php echo esc_url($href); ?>"
                   class="group grid gap-1 px-4 py-3 bg-white border border-blue-200 no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                    <span class="font-sans font-semibold text-blue-900 leading-snug tracking-tight group-hover:text-blue-500 transition-colors">
                        <?php echo esc_html($machine_tag->name); ?>
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
```

- Conventions: Tailwind-first, mobile-first (base styles mobile, `sm:`/`lg:` up); zero border-radius, no shadows (DESIGN.md); `icon()` helper for SVGs; escaping helpers on all output. Before editing, read `.agents/skills/typography-system.md` and `.agents/skills/spacing-system.md` if present.

## Commands you will need

| Purpose | Command | Expected |
|---|---|---|
| PHP lint | `php -l <file>` | `No syntax errors detected` |

(No node_modules in worktrees — never run npm. Reviewer builds and does the visual pass post-merge on the served checkout.)

## Scope

**In scope**:
- `app/single-manual.php` (the Related Machines grid + one `use function` import)
- `app/templates/parts/single/spec-sheet-layout.php` (the compat `<li>` markup only)

**Out of scope** (do NOT touch):
- `app/inc/machine-product-data.php` — resolver is done.
- `app/templates/parts/card-product.php` — the Woo catalog card is a different surface; do not reuse it here (it expects search-shaped product data).
- The filter sidebar, hero, or any other section of `single-manual.php`.
- `single-profile.php` / `single-footprint.php` — they pass args to spec-sheet-layout; no changes needed there.

## Git workflow

- Branch: `advisor/011-machine-cross-sell` (from dev — see environment check)
- One commit, subject like: `Wire manual/profile machine tags to product pages with real images`
- Do NOT push.

## Steps

### Step 1: single-manual.php — real product cards

1. Add the import after the existing `use function Standard\ContentTaxonomy\get_terms_for_post_type;` line:
   `use function Standard\MachineProductData\get_machine_product_link;`
2. Add a `view_machine` key to `$content`: `'view_machine' => __('View machine', 'standard'),`
3. Replace the per-tag card body: resolve `$product_link = get_machine_product_link($machine_tag->slug);` at the top of the loop (replacing the `@todo` comment block).
   - **Matched** (`$product_link !== null`): the card `<a>` href becomes `$product_link['url']`. The image well keeps its classes (`aspect-video ... border-b border-blue-200`) but changes `bg-blue-50` to `bg-white p-2` and renders, when `$product_link['image'] !== ''`:
     ```php
     <img src="<?php echo esc_url($product_link['image']); ?>"
          alt="<?php echo esc_attr($product_link['image_alt'] !== '' ? $product_link['image_alt'] : $machine_tag->name); ?>"
          class="max-h-full max-w-full object-contain"
          loading="lazy" decoding="async">
     ```
     (when `image` is `''`, keep the settings-icon placeholder inside the matched card). The meta line becomes the destination label: `<?php echo esc_html($content['view_machine']); ?>` followed by the arrow icon `icon('arrow--right', ['class' => 'w-3 h-3'])` — keep the mono/text-xs classes.
   - **Unmatched** (`null`): keep EXACTLY the current behavior (tag-archive href, placeholder icon well, "%d manuals available" meta).
4. Keep the grid wrapper, empty-state, and everything else untouched.

**Verify**: `php -l app/single-manual.php` → clean. `grep -c '@todo: Connect tag' app/single-manual.php` → `0`. `grep -c 'get_machine_product_link' app/single-manual.php` → `2` (import + call).

### Step 2: spec-sheet-layout.php — thumbnails in the compat list

In the compat `<li>` loop, resolve `$image = $product_link['image'] ?? '';` and `$image_alt = ($product_link['image_alt'] ?? '') !== '' ? $product_link['image_alt'] : $machine_tag->name;`. Change the `<a>` from `grid gap-1 px-4 py-3` to `flex items-center gap-3 px-4 py-3` (other classes unchanged) and, before the name `<span>`, render when `$image !== ''`:

```php
<span class="flex h-12 w-12 shrink-0 items-center justify-center bg-white" aria-hidden="false">
    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($image_alt); ?>"
         class="max-h-full max-w-full object-contain" loading="lazy" decoding="async">
</span>
```

No thumbnail block at all when the product or its image is missing (row stays text-only, exactly as today).

**Verify**: `php -l app/templates/parts/single/spec-sheet-layout.php` → clean. `grep -c 'object-contain' app/templates/parts/single/spec-sheet-layout.php` → `1`.

## Test plan

No PHP test harness. Gates: lints + greps above. Reviewer post-merge on the served checkout: a manual page with matched tags (machine tags like "SSQ II MultiPro") shows product photos and links to `/machines/...` product URLs; an unmatched tag still shows the placeholder + tag-archive link; a profile page's "Rolls On" list shows thumbnails; `single-download`/`single-literature` pages (same layout part) render without breakage.

## Done criteria

- [ ] Both files pass `php -l`
- [ ] `@todo: Connect tag` gone from single-manual.php
- [ ] Matched cards link to product URLs with real images; unmatched cards byte-identical behavior to today
- [ ] spec-sheet-layout rows show thumbnails only when a product image resolves
- [ ] `git status --porcelain` shows only the two in-scope files

## STOP conditions

- The excerpts don't match the live code (drift).
- `get_machine_product_link` is not importable from single-manual.php's context (function_exists check fails at runtime is NOT verifiable in the worktree — but if the namespace/function name in machine-product-data.php differs from this plan, stop).
- You find yourself modifying the resolver or card-product.php.

## Maintenance notes

- If machine post_tag slugs drift from product slugs/aliases, cards degrade gracefully to tag archives — the alias map in `machine-product-data.php` (`get_slug_aliases`) is where to fix a miss, not the templates.
- Reviewer visual-pass checklist: image wells must not stretch photos (object-contain), dark-on-light text contrast unchanged, tap area still the whole card.
