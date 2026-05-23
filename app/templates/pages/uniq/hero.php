<?php
/**
 * UNIQ Page — Hero
 *
 * Data wrapper for the shared hero-category part. Mono kicker carries
 * the "control system" identifier, the Wistia tutorial sits in the
 * right panel, and the meta rail communicates compatibility at a glance
 * (the legacy page buried that in body copy).
 *
 * The primary CTA points to the software update download — that's the
 * one place red earns the pinpoint accent (DESIGN.md §2.4).
 *
 * @package Standard
 *
 * @usage page-uniq-control-system.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'uniq-hero',
    'content'    => [
        'kicker'            => __('NTM // CONTROL SYSTEM', 'standard'),
        'title'             => __('UNIQ® Automatic Control System', 'standard'),
        'subtitle'          => __('A redesigned 7" touchscreen brain for your SSQ II™, SSQ3, and WAV rollformers — automatic drive, shear, and notching from one panel.', 'standard'),
        'cta_primary'       => __('Download Software Update', 'standard'),
        'cta_primary_url'   => '/machines/uniq-control-system-update/',
        'cta_secondary'     => __('Watch the Tutorial', 'standard'),
        'cta_secondary_url' => 'https://fast.wistia.net/embed/iframe/vf198bnz3w',
        'video'             => 'https://fast.wistia.net/embed/iframe/vf198bnz3w',
        'poster'            => '',
    ],
    'meta' => [
        ['label' => __('Standard On', 'standard'), 'value' => 'WAV'],
        ['label' => __('Optional On', 'standard'), 'value' => 'SSQ II · SSQ3'],
        ['label' => __('Display', 'standard'), 'value' => '7″ Touch'],
    ],
]);
