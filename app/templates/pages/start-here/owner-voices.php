<?php
/**
 * Start Here — Owner Voices
 *
 * Owner proof, rendered with the shared chrome-bar testimonial slider so
 * it reads as the same system as the front page and the machine pages.
 * The quote set is hand-picked to the start-a-business frame (people who
 * jumped into the metal trade and grew), and ties into the page: Andrews
 * built Classic Metals (the proof link in the-case), Cisneros is the
 * gutter owner whose install photo runs in the-day.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 * @see   templates/parts/testimonial-slider.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$voices = [
    [
        'quote'    => __('If you’re trying to jump into the metal business, contact New Tech Machinery. They’re going to give you the information that you need, and they’re going to help you grow your business.', 'standard'),
        'name'     => 'Danaik Garay',
        'company'  => 'Alsteel Metal Manufacturing',
        'location' => 'Fort Myers, Florida',
        'slug'     => 'Danaik-1',
    ],
    [
        'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and the ease of switching out dies. If you do have a problem, there’s a sales team you can call.', 'standard'),
        'name'     => 'Todd Andrews',
        'company'  => 'Classic Metals Inc.',
        'location' => 'Chester, South Carolina',
        'slug'     => 'Todd',
    ],
    [
        'quote'    => __('New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted something I can rely on.', 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'location' => 'Greeley, Colorado',
        'slug'     => 'Abel',
    ],
];

get_template_part('templates/parts/testimonial-slider', null, [
    'section_id'   => 'start-here-voices-title',
    'testimonials' => $voices,
    'content'      => [
        'eyebrow'    => __('People who started where you are', 'standard'),
        'channel'    => __('Owner stories', 'standard'),
        'sr_title'   => __('Owner stories', 'standard'),
        'nav_label'  => __('Owner story navigation', 'standard'),
        'cta_label'  => __('Read more owner stories', 'standard'),
        'cta_url'    => '/learning-center/category/testimonials/',
        'count_left' => __('Owners', 'standard'),
    ],
]);
