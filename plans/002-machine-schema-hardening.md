# Plan 002: Harden the machine Product JSON-LD builder against bad data

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- app/inc/machine-schema.php`
> If the file changed since this plan was written, compare the "Current state"
> excerpts against the live code before proceeding; on a mismatch, STOP.

## Status

- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

`app/inc/machine-schema.php` renders the Product + FAQPage JSON-LD on flagship machine product pages — verified (2026-07-10, live curl with Yoast 28.0 + Premium active) to be the **sole** Product schema emitter on those pages (Yoast emits no Product; WooCommerce core's structured data does not fire on the custom template). That makes this file load-bearing for rich results on the money pages. Two hardening gaps: (1) `pv()` declares `string` parameters under `declare(strict_types=1)`, so a single hand-edited machine data file that supplies an int/float (e.g. `weight => 4200`) throws a `TypeError` during page render on a flagship page; (2) the `image` field can resolve to `''` (empty string), which fails Google's required-image rule for Product.

## Current state

- `app/inc/machine-schema.php` — namespace `Standard\MachineSchema`, `declare(strict_types=1)`. Machine data arrives from curated files in `app/data/machines/<slug>.php` (all 10 currently set `low_price`, so `offers` is always present today).

Excerpt, `machine-schema.php:48-63` (image fallback chain):

```php
$schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $product->get_name(),
    'description' => wp_strip_all_tags($hero['subtitle'] ?? $product->get_short_description()),
    'url'         => get_permalink($product->get_id()),
    'image'       => wp_get_attachment_url($product->get_image_id()) ?: ($hero['image'] ?? ''),
```

Excerpt, `machine-schema.php:119-125` (untyped data passed to `pv`):

```php
foreach (($machine['stats'] ?? []) as $stat) {
    $props[] = pv($stat['label'], $stat['value']);
}

foreach (($specs['materials'] ?? []) as $mat) {
    $props[] = pv('Material: ' . $mat['name'], $mat['gauge']);
}
```

Excerpt, `machine-schema.php:134-145` (the strict signature):

```php
/**
 * @param string $name
 * @param string $value
 * @return array
 */
function pv(string $name, string $value): array {
    return [
        '@type' => 'PropertyValue',
        'name'  => $name,
        'value' => $value,
    ];
}
```

- Repo conventions: all PHP `declare(strict_types=1)`; short doc-comments explaining *why*; no heavy abstractions. Exemplar: this very file.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint | `php -l app/inc/machine-schema.php` | `No syntax errors detected` |

(Host PHP is 8.3 via Herd. Your worktree has no `node_modules`; do not run npm commands.)

## Scope

**In scope**:
- `app/inc/machine-schema.php`

**Out of scope** (do NOT touch):
- `app/inc/seo.php`, `app/templates/parts/faq-accordion.php` — their unconditional/guarded emission behavior is verified-correct as of 2026-07-10; leave it.
- `app/data/machines/*.php` — do not "fix" data; the code must tolerate it.
- `app/templates/woo/product/single-machine.php` — the call site is correct.

## Git workflow

- Branch: `advisor/002-machine-schema-hardening` (`git switch -c ...`)
- One commit, subject like: `Harden machine schema against non-string data and empty image`
- Do NOT push.

## Steps

### Step 1: Widen `pv()` to accept scalars and cast internally

Replace the `pv()` function with:

```php
/**
 * PropertyValue node. Accepts any scalar because curated machine data is
 * hand-edited — a numeric weight must not fatal the page under strict_types.
 *
 * @param string $name
 * @param string|int|float $value
 * @return array
 */
function pv(string $name, string|int|float $value): array {
    return [
        '@type' => 'PropertyValue',
        'name'  => $name,
        'value' => (string) $value,
    ];
}
```

Also cast the two loop call sites that pass raw array values, so a `null`-ish or bool value never reaches the signature: in `build_additional_properties()`, change `pv($stat['label'], $stat['value'])` to `pv((string) $stat['label'], $stat['value'] ?? '')` and `pv('Material: ' . $mat['name'], $mat['gauge'])` to `pv('Material: ' . (string) $mat['name'], $mat['gauge'] ?? '')`.

**Verify**: `php -l app/inc/machine-schema.php` → no syntax errors.
**Verify behavior**: `php -r "declare(strict_types=1); require 'app/inc/machine-schema.php';" 2>&1 | head -2` will fail on ABSPATH — instead verify by inspection plus this targeted check: `php -r '$v = 4200; var_dump((string) $v);' ` → `string(4) "4200"`. Note in your report that runtime verification happens post-merge on the served checkout.

### Step 2: Guard the empty-image case

In `build_product_schema()`, build the image explicitly and omit the key when empty:

```php
$image = wp_get_attachment_url($product->get_image_id()) ?: ($hero['image'] ?? '');
```

…then only add `'image' => $image` to `$schema` when `$image !== ''` (build `$schema` without the `image` key and add it conditionally after, keeping the other keys in their current order). A Product with no `image` key is a soft warning; an `image: ""` is an error.

**Verify**: `php -l app/inc/machine-schema.php` → no syntax errors. `grep -n "image" app/inc/machine-schema.php | head -5` → shows the conditional add, no `'image' =>` inside the initial array literal.

### Step 3: Record the verified single-emitter status

Add one sentence to the file's header doc-block (after the existing description):

```
 * Verified 2026-07-10: with Yoast SEO + Premium active, this is the ONLY
 * Product emitter on machine pages (Yoast emits no Product; WC core's
 * structured data does not fire on the custom single-machine template).
 * Do not add a seo_plugin_active() guard here — it would remove the page's
 * only Product schema.
```

**Verify**: `grep -c 'ONLY Product emitter' app/inc/machine-schema.php` → `1`.

## Test plan

No PHP test harness exists in this repo. Verification = lint + the reviewer's post-merge smoke: `curl -sk https://newtech.local/machines/roof-wall-panel-machines/ssq3-multipro/` piped through a JSON-LD parse (reviewer runs this; expected: Product block parses, image key present-and-non-empty or absent).

## Done criteria

- [ ] `php -l app/inc/machine-schema.php` exits 0
- [ ] `pv()` signature is `pv(string $name, string|int|float $value)` and casts `(string) $value`
- [ ] `image` key added conditionally, never `''`
- [ ] Header doc-block records the single-emitter verification
- [ ] `git status --porcelain` shows only `app/inc/machine-schema.php` modified

## STOP conditions

- The current `pv()` body differs from the excerpt (drift).
- You find a second `render_machine_schema` call site (`grep -rn render_machine_schema app --include='*.php'` returns more than the definition + `single-machine.php:145` + its `use` line).

## Maintenance notes

- If a machine data file ever needs a non-scalar spec value (array of rates), extend `pv()` deliberately — don't loosen to `mixed`.
- If Yoast WooCommerce SEO (the addon, not Premium) is ever installed, re-run the duplicate-Product check — that addon DOES emit Product schema, and then the theme emitter needs the guard this plan deliberately did not add.
