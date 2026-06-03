<?php
/**
 * Choose Your Machine — Hero
 *
 * Data wrapper for the shared hero-category part. This is a working page,
 * not a campaign: the headline states the job (pick the machine that
 * matches your work), the primary CTA drops to the fork, the secondary
 * routes confident browsers straight to the full catalog. Mirrors the vs
 * hero rhythm so the two decision pages read as the same company.
 *
 * @package Standard
 *
 * @usage Choose Your Machine (page-choose-your-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'choose-hero',
    'content'    => [
        'kicker'            => __('CHOOSE YOUR MACHINE // FIND YOUR FIT', 'standard'),
        'title'            => __('Ten Machines.<br class="hidden lg:inline"> Find the One That Fits Your Work.', 'standard'),
        'subtitle'         => __('You know the trade. Start with what you make on the jobsite, then read each machine by the work it suits. No quiz required, the lineup is right here.', 'standard'),
        'cta_primary'      => __('Start with what you make', 'standard'),
        'cta_primary_url'  => '#the-fork',
        'cta_secondary'    => __('See all machines', 'standard'),
        'cta_secondary_url' => '/machines/',
        'poster'           => content_url('/uploads/2022/03/roof-panel-machines-blue-background.jpg'),
        'poster_alt'       => __('The New Tech Machinery portable rollforming lineup on a blue background', 'standard'),
    ],
    'meta' => [
        ['label' => __('The lineup', 'standard'), 'value' => __('10 machines', 'standard')],
        ['label' => __('Roof & wall', 'standard'), 'value' => __('6 machines', 'standard')],
        ['label' => __('Seamless gutter', 'standard'), 'value' => __('4 machines', 'standard')],
    ],
]);
