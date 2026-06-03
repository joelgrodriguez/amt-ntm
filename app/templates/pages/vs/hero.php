<?php
/**
 * Roof Panel vs Gutter — Hero
 *
 * Data wrapper for the shared hero-category template part. Mirrors the
 * /machines hero rhythm: text rail left, 16:9 visual panel right.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'vs-hero',
    'content'    => [
        'kicker'              => __('START HERE // WHICH MACHINE?', 'standard'),
        'title'               => __('Roof Panel or Gutter Machine?<br class="hidden lg:inline"> Here’s How to Tell.', 'standard'),
        'subtitle'            => __('New Tech Machinery builds two families of portable rollformers. One forms the panels that become roofs and walls. The other forms seamless gutters. The right choice starts with what you make on the jobsite.', 'standard'),
        'cta_primary'         => __('Find your machine', 'standard'),
        'cta_primary_url'     => '#the-fork',
        'cta_secondary'       => __('Take the machine quiz', 'standard'),
        'cta_secondary_url'   => '/choose-your-machine/',
        'poster'              => content_url('/uploads/2022/03/roof-panel-machines-blue-background.jpg'),
        'poster_alt'          => __('Portable roof panel machines on a blue New Tech Machinery background', 'standard'),
    ],
    'meta' => [
        ['label' => __('Decision', 'standard'), 'value' => __('Roof or gutter', 'standard')],
        ['label' => __('Format', 'standard'), 'value' => __('On-site forming', 'standard')],
        ['label' => __('Next step', 'standard'), 'value' => __('Pick a lane', 'standard')],
    ],
]);
