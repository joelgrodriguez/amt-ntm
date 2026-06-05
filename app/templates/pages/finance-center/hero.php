<?php
/**
 * Finance Center — Hero
 *
 * Reuses the shared hero-category part (same as /machines/): dot-grid
 * backdrop, text rail left, 16:9 click-to-play video panel right, mono meta
 * rail. The video is the "How to Finance Your NTM Machine" Wistia media; the
 * poster (a standing-seam rooftop) is the LCP and the iframe only loads on
 * click, so the hero stays fast.
 *
 * The visible marketing headline is an H2 inside the part; the part also
 * emits an sr-only H1 from the WP page title, which carries the financing
 * keyword for SEO.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();

// hero_video (set in the DB, captured in scripts/db/) wins; fall back to the
// known finance Wistia media so the hero always ships with the video.
$fallback_video = 'https://newtechmachinery.wistia.com/medias/hesm0txl1n';
$video = \Standard\PageTemplates\get_page_field($post_id, ['hero_video'], $fallback_video, false);

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'finance-hero',
    'content'    => [
        'kicker'                => __('NTM FINANCE CENTER // EQUIPMENT FINANCING', 'standard'),
        'title'                 => __('Financing for Portable<br class="hidden lg:inline"> Rollforming Machines.', 'standard'),
        'subtitle'              => __('Every way to pay for your metal roofing or seamless gutter machine, in one place. Apply online in minutes, claim Section 179, or work with NTM’s preferred lender.', 'standard'),
        'cta_primary'           => __('See all options', 'standard'),
        'cta_primary_url'       => '#finance-paths',
        'cta_primary_icon'      => 'arrow-down',
        'cta_secondary'         => __('Watch how it works', 'standard'),
        'cta_secondary_trigger' => true,
        'video'                 => $video,
        'poster'                => content_url('/uploads/2025/09/Machine-on-rooftop.jpg'),
        'poster_alt'            => __('NTM portable rollformer running standing-seam panels on a rooftop', 'standard'),
    ],
    'meta' => [
        ['label' => __('Apply', 'standard'), 'value' => __('Online', 'standard')],
        ['label' => __('Decision', 'standard'), 'value' => __('4–8 hrs', 'standard')],
        ['label' => __('Credit pull', 'standard'), 'value' => __('Soft', 'standard')],
    ],
]);
