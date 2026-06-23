<?php
/**
 * Trailer Page — Hero
 *
 * Data wrapper for the shared hero-category part. Leads with the
 * engineered-as-one-system claim (the trailer is built around the machine,
 * a generic trailer can't be). Right panel runs the article's Wistia video.
 * Meta rail surfaces the three facts that frame the whole page: capacity,
 * NATM compliance, and balanced tongue weight.
 *
 * No dot-grid backdrop: this is a product/upgrade surface, not a category
 * landing page (pattern => false).
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'trailer-hero',
    'pattern'    => false,
    'content'    => [
        'kicker'             => __('NTM // TRAILER', 'standard'),
        'title'              => __('A Trailer Engineered for the Machine, Not Adapted to It', 'standard'),
        'subtitle'           => __('Most trailers are a flat deck you bolt a machine onto and hope. The NTM trailer is built the other way around: the deck, the axles, the brakes, and the scrap trays are all designed for one job, hauling a six-figure rollformer to the next site and back without a scratch.', 'standard'),
        'cta_primary'        => __('Request a Quote', 'standard'),
        'cta_primary_url'    => '/contact/',
        'cta_primary_icon'   => 'arrow-right',
        'cta_secondary'      => __('See the two models', 'standard'),
        'cta_secondary_url'  => '#trailer-models',
        'video'              => 'https://newtechmachinery.wistia.com/medias/d43ez7v1wc',
        'poster'             => content_url('/uploads/2023/09/Trailer-TR12.jpg'),
        'poster_alt'         => __('An NTM trailer purpose-built to haul a portable rollforming machine', 'standard'),
    ],
    'meta' => [
        ['label' => __('Capacity', 'standard'), 'value' => __('Up to 23,000 lb', 'standard')],
        ['label' => __('Certified', 'standard'), 'value' => __('NATM Compliant', 'standard')],
        ['label' => __('Tongue Weight', 'standard'), 'value' => __('750 lb balanced', 'standard')],
    ],
]);
