<?php
/**
 * Trailer Data
 *
 * Single source of truth for the NTM trailer lineup and its machine
 * compatibility. Every trailer surface — the /machines/trailer/ models grid,
 * the compatibility matrix, the contextual strip on machine product pages, and
 * the /machines landing callout — reads this module so the families, specs,
 * links, and compatibility never drift apart.
 *
 * Prices resolve live from WooCommerce via MachinesData\get_product_price();
 * the literals here are a fallback for environments without WooCommerce only,
 * and match the current store prices. Product slugs are NOT uniform across the
 * catalog (tr12-trailer, tr12l-trailer-2, trailer-tr12xl, trailer-tr23,
 * trailer-tr23g), so links resolve by slug through get_product_url() rather
 * than being assembled by hand.
 *
 * Specs are lifted verbatim from the WooCommerce product descriptions — no
 * invented claims. TR12 variants are 12,000 lb tandem-axle; TR23/TR23G are
 * 23,000 lb multi-axle for the triple overhead reel rack.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\TrailerData;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The five sellable trailer families, keyed by model code.
 *
 * `slug`     WooCommerce product slug (resolves URL + live price).
 * `axle`     Axle configuration spec value.
 * `capacity` Rated towing capacity spec value.
 * `rack`     The reel-rack configuration the deck is built for.
 * `hitch`    Coupling type.
 * `summary`  One-line description grounded in the product copy.
 * `fallback_price` Store price at authoring time; only used if Woo is absent.
 *
 * @return array<string, array<string, string>>
 */
function get_trailers(): array {
    return [
        'TR12-D' => [
            'slug'           => 'tr12-trailer',
            'name'           => __('TR12-D Trailer', 'standard'),
            'axle'           => __('Tandem axle', 'standard'),
            'capacity'       => __('12,000 lb', 'standard'),
            'rack'           => __('Single machine', 'standard'),
            'hitch'          => __('Bumper-pull', 'standard'),
            'summary'        => __('NATM-compliant 12,000 lb tandem-axle trailer for towing any NTM roofing machine to the jobsite — crane lifting eyes, drop-foot jack stands, and electric brakes with breakaway kit.', 'standard'),
            'fallback_price' => __('$19,650', 'standard'),
        ],
        'TR12L' => [
            'slug'           => 'tr12l-trailer-2',
            'name'           => __('TR12L Trailer', 'standard'),
            'axle'           => __('Tandem axle', 'standard'),
            'capacity'       => __('12,000 lb', 'standard'),
            'rack'           => __('Single machine', 'standard'),
            'hitch'          => __('Bumper-pull', 'standard'),
            'summary'        => __('NATM-compliant 12,000 lb tandem-axle trailer with the same jobsite kit — crane lifting eyes, drop-foot jack stands, and electric brakes with breakaway — in a longer deck.', 'standard'),
            'fallback_price' => __('$20,500', 'standard'),
        ],
        'TR12XL' => [
            'slug'           => 'trailer-tr12xl',
            'name'           => __('TR12XL Trailer', 'standard'),
            'axle'           => __('Tandem axle', 'standard'),
            'capacity'       => __('12,000 lb', 'standard'),
            'rack'           => __('Single overhead reel rack', 'standard'),
            'hitch'          => __('Bumper-pull', 'standard'),
            'summary'        => __('12,000 lb tandem-axle trailer sized for a machine running the single overhead reel rack.', 'standard'),
            'fallback_price' => __('$25,900', 'standard'),
        ],
        'TR23' => [
            'slug'           => 'trailer-tr23',
            'name'           => __('TR23 Trailer', 'standard'),
            'axle'           => __('3-axle', 'standard'),
            'capacity'       => __('23,000 lb', 'standard'),
            'rack'           => __('Triple overhead reel rack', 'standard'),
            'hitch'          => __('Bumper-pull', 'standard'),
            'summary'        => __('23,000 lb three-axle trailer for the triple overhead reel rack. Couples to a standard rear hitch, so it goes behind the truck you already run.', 'standard'),
            'fallback_price' => __('$32,600', 'standard'),
        ],
        'TR23G' => [
            'slug'           => 'trailer-tr23g',
            'name'           => __('TR23G Trailer', 'standard'),
            'axle'           => __('3-axle', 'standard'),
            'capacity'       => __('23,000 lb', 'standard'),
            'rack'           => __('Triple overhead reel rack', 'standard'),
            'hitch'          => __('Gooseneck (in-bed)', 'standard'),
            'summary'        => __('The same 23,000 lb capacity in a gooseneck configuration. Couples to an in-bed hitch for more stability and a tighter turning radius.', 'standard'),
            'fallback_price' => __('$34,200', 'standard'),
        ],
    ];
}

/**
 * Machine → compatible trailer model codes.
 *
 * Keyed by the machines-data slug (the canonical machine key). MACH II gutter
 * machines are intentionally ABSENT: they get no trailer callout. The strip and
 * matrix both read this map, so "which machines show a trailer" lives in exactly
 * one place.
 *
 * @return array<string, string[]>
 */
function get_compatibility(): array {
    return [
        '5vc-5v-crimp'   => ['TR12-D'],
        'ssr-multipro-jr' => ['TR12-D'],
        'ssh-multipro'   => ['TR12-D'],
        'ssq-ii-multipro' => ['TR12-D'],
        'ssq3-multipro'  => ['TR12-D'],
        'bg7-box-gutter' => ['TR12L'],
        'wav-wall-panel' => ['TR12XL', 'TR23', 'TR23G'],
    ];
}

/**
 * Rows for the compatibility matrix, grouped by trailer family.
 *
 * Inverts get_compatibility() into the reader's mental model: "this trailer
 * runs these machines." Display names come from machines-data so the matrix
 * matches the machine cards site-wide.
 *
 * @return array<int, array{model: string, machines: array<int, array{name: string, url: string}>}>
 */
function get_matrix_rows(): array {
    $trailers      = get_trailers();
    $compatibility = get_compatibility();

    // Human machine names + URLs, resolved once from machines-data.
    $machine_meta = [];
    if (function_exists('Standard\\MachinesData\\get_all_machines')) {
        foreach (\Standard\MachinesData\get_all_machines(true) as $m) {
            $slug = $m['slug'] ?? '';
            if ($slug === '') {
                continue;
            }
            $machine_meta[$slug] = [
                'name' => $m['name'] ?? $slug,
                'url'  => $m['url'] ?? '',
            ];
        }
    }

    // Invert machine→trailers into trailer→machines, preserving trailer order.
    $by_trailer = [];
    foreach (array_keys($trailers) as $model) {
        $by_trailer[$model] = [];
    }
    foreach ($compatibility as $machine_slug => $models) {
        foreach ($models as $model) {
            if (!isset($by_trailer[$model])) {
                continue;
            }
            $meta = $machine_meta[$machine_slug] ?? ['name' => $machine_slug, 'url' => ''];
            $by_trailer[$model][] = [
                'name' => $meta['name'],
                'url'  => $meta['url'],
            ];
        }
    }

    $rows = [];
    foreach ($trailers as $model => $trailer) {
        if (empty($by_trailer[$model])) {
            continue;
        }
        $rows[] = [
            'model'    => $model,
            'name'     => $trailer['name'],
            'capacity' => $trailer['capacity'],
            'axle'     => $trailer['axle'],
            'machines' => $by_trailer[$model],
        ];
    }

    return $rows;
}

/**
 * Resolve a machines-data slug from any product slug (WC or data slug).
 *
 * Machine product pages carry the WooCommerce slug (e.g.
 * ssq3-roof-panel-machine); compatibility is keyed by the data slug
 * (ssq3-multipro). This maps one to the other via the alias table.
 */
function resolve_machine_slug(string $slug): string {
    if (function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        $aliases = \Standard\MachineProductData\get_slug_aliases();
        if (isset($aliases[$slug])) {
            return $aliases[$slug];
        }
    }
    return $slug;
}

/**
 * Is a machine product compatible with a trailer?
 *
 * Accepts a WooCommerce product slug or a machines-data slug. Returns false for
 * MACH II and anything else not in the compatibility map — the gate that keeps
 * the trailer strip off MACH II product pages.
 */
function is_trailer_compatible(string $slug): bool {
    $data_slug = resolve_machine_slug($slug);
    return array_key_exists($data_slug, get_compatibility());
}

/**
 * The trailer families compatible with a given machine, as display-ready rows.
 *
 * Each row carries the model code, name, resolved live price (with fallback),
 * product URL, and the spec values the strip renders. Empty array when the
 * machine has no compatible trailer (e.g. MACH II).
 *
 * @return array<int, array<string, string>>
 */
function get_trailers_for_machine(string $slug): array {
    $data_slug     = resolve_machine_slug($slug);
    $compatibility = get_compatibility();
    if (!isset($compatibility[$data_slug])) {
        return [];
    }

    $trailers = get_trailers();
    $rows     = [];
    foreach ($compatibility[$data_slug] as $model) {
        if (!isset($trailers[$model])) {
            continue;
        }
        $rows[] = build_trailer_row($model, $trailers[$model]);
    }

    return $rows;
}

/**
 * All five trailer families as display-ready rows, in lineup order.
 *
 * Used by the /machines/trailer/ models grid.
 *
 * @return array<int, array<string, string>>
 */
function get_all_trailer_rows(): array {
    $rows = [];
    foreach (get_trailers() as $model => $trailer) {
        $rows[] = build_trailer_row($model, $trailer);
    }
    return $rows;
}

/**
 * Linkify a "Trailer sold separately" mention in a pricing note.
 *
 * Returns escaped HTML: the surrounding note text runs through esc_html(), and
 * only the matched phrase becomes a link to /machines/trailer/. When the phrase
 * is absent the whole note is returned esc_html()'d unchanged, so this is safe
 * to wrap any note. The match is case-insensitive on the exact phrase and keeps
 * the note's original casing in the link text.
 *
 * Callers must echo the result WITHOUT re-escaping (it is already safe HTML).
 */
function linkify_note(string $note): string {
    $phrase = 'Trailer sold separately';
    $pos    = stripos($note, $phrase);

    if ($pos === false) {
        return esc_html($note);
    }

    $before  = substr($note, 0, $pos);
    $matched = substr($note, $pos, strlen($phrase));
    $after   = substr($note, $pos + strlen($phrase));

    $url = function_exists('Standard\\Url\\internal')
        ? \Standard\Url\internal('/machines/trailer/')
        : '/machines/trailer/';

    return esc_html($before)
        . '<a href="' . esc_url($url) . '" class="underline underline-offset-2 decoration-blue-400 hover:decoration-white">'
        . esc_html($matched)
        . '</a>'
        . esc_html($after);
}

/**
 * Assemble one display row: live price, product URL, image, specs.
 *
 * @param array<string, string> $trailer
 * @return array<string, mixed>
 */
function build_trailer_row(string $model, array $trailer): array {
    $slug = $trailer['slug'];

    $price = null;
    if (function_exists('Standard\\MachinesData\\get_product_price')) {
        $price = \Standard\MachinesData\get_product_price($slug);
    }
    $price = $price ?? $trailer['fallback_price'];

    $url = '#';
    if (function_exists('Standard\\MachinesData\\get_product_url')) {
        $url = \Standard\MachinesData\get_product_url($slug);
    }

    $image = '';
    if (function_exists('Standard\\MachinesData\\get_product_image')) {
        $image = \Standard\MachinesData\get_product_image($slug);
    }

    return [
        'model'    => $model,
        'name'     => $trailer['name'],
        'slug'     => $slug,
        'capacity' => $trailer['capacity'],
        'axle'     => $trailer['axle'],
        'rack'     => $trailer['rack'],
        'hitch'    => $trailer['hitch'],
        'summary'  => $trailer['summary'],
        'price'    => $price,
        'url'      => $url,
        'image'    => $image,
    ];
}
