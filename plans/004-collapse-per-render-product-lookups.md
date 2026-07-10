# Plan 004: Collapse the per-render product and attachment lookups (N+1s)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- app/inc/machine-product-data.php app/inc/service-hub-machines.php app/inc/images.php`
> On any change since this SHA, compare the excerpts below against the live
> code; on a mismatch, STOP.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: perf
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

Three uncached lookup patterns multiply DB queries on high-traffic pages of a marketing site whose buyers are often on phones:

1. `get_machine_product_link()` runs `get_posts` + `wc_get_product` + `get_post_meta` on **every call** with no memoization. `/choose-your-machine/` calls it ~10× per render (`app/templates/pages/choose/data.php:187`), and `spec-sheet-layout.php` calls it once per machine tag on every single-download/resource/literature page.
2. `resolve_product_id()` loads and hydrates **the entire published product catalog** to find one product by slug.
3. `get_attachment_id()` in `images.php` calls `attachment_url_to_postid()` — an uncached postmeta scan — once per unique image URL per request; card grids issue one per card.

## Current state

- `app/inc/machine-product-data.php:183-218` — namespace `Standard\MachineProductData`:

```php
function get_machine_product_link(string $machine_key, string $image_size = 'woocommerce_thumbnail'): ?array {
    $aliases = get_slug_aliases();
    $reverse = array_flip($aliases);
    $slugs   = isset($reverse[$machine_key]) ? [$reverse[$machine_key]] : [$machine_key];

    if (!in_array($machine_key, $slugs, true)) {
        $slugs[] = $machine_key;
    }

    foreach ($slugs as $slug) {
        $posts = get_posts([
            'post_type'   => 'product',
            'name'        => $slug,
            'numberposts' => 1,
            'fields'      => 'ids',
        ]);
        ...
```

- `app/inc/service-hub-machines.php:285-310` — namespace has `resolve_product_id()`:

```php
$products = \function_exists('Standard\\Woo\\Cache\\get_products')
    ? \Standard\Woo\Cache\get_products(['limit' => -1, 'status' => 'publish'])
    : \wc_get_products(['limit' => -1, 'status' => 'publish']);

foreach ($products as $product) {
    if (\in_array($product->get_slug(), $candidates, true)) {
        return (int) $product->get_id();
    }
}
```

(Its caller `get_machine_footprint()` static-caches per slug — lines 216-220 — so the full-catalog hydration happens once per unique slug per request; still one full hydration per cold page.)

- `app/inc/images.php:19-33` — namespace `Standard\Images`:

```php
function get_attachment_id(string $url): int {
    static $cache = [];

    if ($url === '') {
        return 0;
    }

    if (!array_key_exists($url, $cache)) {
        $cache[$url] = function_exists('attachment_url_to_postid')
            ? (int) attachment_url_to_postid($url)
            : 0;
    }

    return $cache[$url];
}
```

- The repo's existing memoization convention is a `static` array — exemplar: `get_product_url()` in `app/inc/machines-data.php:33-61` (`static $urls = null;` built once). Match it.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint | `php -l <file>` per touched file | `No syntax errors detected` |

(Worktree has no `node_modules`; do not run npm commands. Runtime query-count verification happens post-merge by the reviewer on the served checkout.)

## Scope

**In scope**:
- `app/inc/machine-product-data.php` (only `get_machine_product_link`)
- `app/inc/service-hub-machines.php` (only `resolve_product_id`)
- `app/inc/images.php` (only `get_attachment_id`)

**Out of scope** (do NOT touch):
- `app/inc/machines-data.php` (`get_product_url` is already cached — it's the exemplar, not a target)
- `app/inc/woo/cache.php` — the transient layer is fine as-is
- Any template file; any call-site signature. All three functions keep their exact signatures and return types.

## Git workflow

- Branch: `advisor/004-collapse-product-lookups`
- One commit, subject like: `Memoize product-link, product-id, and attachment-id lookups`
- Do NOT push.

## Steps

### Step 1: Memoize `get_machine_product_link()`

Add a static cache keyed by `$machine_key . '|' . $image_size` at the top of the function; return the cached value (including cached `null` — use `array_key_exists`, not `isset`) and store the result before every return path. Shape:

```php
function get_machine_product_link(string $machine_key, string $image_size = 'woocommerce_thumbnail'): ?array {
    static $cache = [];
    $cache_key = $machine_key . '|' . $image_size;
    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }
    // ... existing body, assigning the found array or null to $cache[$cache_key]
    // and returning it at both exit points.
```

**Verify**: `php -l app/inc/machine-product-data.php` → clean. `grep -n 'array_key_exists' app/inc/machine-product-data.php` → at least one match inside `get_machine_product_link`.

### Step 2: Replace the full-catalog scan in `resolve_product_id()`

Replace the `get_products(['limit' => -1, ...])` + `foreach` block with a direct slug query over the candidate slugs:

```php
foreach ($candidates as $slug) {
    $ids = \get_posts([
        'post_type'   => 'product',
        'name'        => $slug,
        'post_status' => 'publish',
        'numberposts' => 1,
        'fields'      => 'ids',
    ]);
    if (!empty($ids)) {
        return (int) $ids[0];
    }
}

return 0;
```

Keep the existing `$candidates` construction and the `wc_get_products` function-exists guard at the top (replace its body usage — the guard can remain as an early `return 0` if WooCommerce is absent).

**Verify**: `php -l app/inc/service-hub-machines.php` → clean. `grep -n "limit' => -1" app/inc/service-hub-machines.php` → no matches remaining in `resolve_product_id` (other functions in the file may legitimately keep theirs — only this function is in scope).

### Step 3: Add a persistent-object-cache layer to `get_attachment_id()`

Keep the static array (fast path), add `wp_cache_get`/`wp_cache_set` around the `attachment_url_to_postid` call:

```php
if (!array_key_exists($url, $cache)) {
    $key   = 'url2id_' . md5($url);
    $found = wp_cache_get($key, 'amt-ntm-images');
    if ($found !== false) {
        $cache[$url] = (int) $found;
    } else {
        $cache[$url] = function_exists('attachment_url_to_postid')
            ? (int) attachment_url_to_postid($url)
            : 0;
        wp_cache_set($key, $cache[$url], 'amt-ntm-images', 12 * HOUR_IN_SECONDS);
    }
}
```

(Without a persistent object cache this degrades to exactly today's behavior; with one — Kinsta prod — repeat renders skip the postmeta scan. A cached `0` is acceptable: attachment URLs are stable and the TTL bounds staleness.)

**Verify**: `php -l app/inc/images.php` → clean. `grep -n 'wp_cache_get' app/inc/images.php` → one match.

## Test plan

No PHP test harness exists. Machine-checkable gates are the lints + greps above. Reviewer's post-merge smoke: load `/choose-your-machine/` and a service-hub machine page on the served checkout; with Query Monitor or `SAVEQUERIES`, confirm `get_posts` product lookups collapse to ≤1 per unique machine key.

## Done criteria

- [ ] All three files pass `php -l`
- [ ] `get_machine_product_link` memoizes null results too (uses `array_key_exists`)
- [ ] `resolve_product_id` contains no `limit' => -1` catalog scan
- [ ] `get_attachment_id` consults `wp_cache_get` before `attachment_url_to_postid`
- [ ] Function signatures unchanged (`grep -n 'function get_machine_product_link\|function resolve_product_id\|function get_attachment_id'` shows identical signatures)
- [ ] `git status --porcelain` shows only the three in-scope files modified

## STOP conditions

- Any excerpt above doesn't match the live code (drift).
- The `$candidates` construction in `resolve_product_id` turns out to rely on partial-match semantics a direct `name=` query can't express (it shouldn't — slugs are exact) — stop and report rather than approximating.

## Maintenance notes

- If machine products ever become variable products, `type => 'simple'` filters elsewhere (`machines-data.php:40`) matter — this plan's slug query has no type filter on purpose (matches current behavior).
- The `amt-ntm-images` cache group: if an attachment's URL is ever re-pointed at a different attachment (rare), stale hits last ≤12h. Flush with `wp cache flush` if it ever matters.
