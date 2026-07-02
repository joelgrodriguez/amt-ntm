<?php
/**
 * Machine Product — Customer Story (chrome-bar testimonial)
 *
 * Mirrors templates/parts/front-page/social-proof.php so the site reads
 * as one chrome-bar testimonial system instead of a magazine spread
 * here and a strip on the home page.
 *
 * Falls back to the same shared testimonial set used on the front page
 * when per-machine testimonials aren't authored yet.
 *
 * @package Standard
 * @var array{machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 * @see js/modules/SocialProof.js - Slider functionality
 * @see templates/parts/front-page/social-proof.php - Shared composition
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine      = $args['machine'] ?? [];
$testimonials = $machine['testimonials'] ?? [];

$cdn = \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2025/06');

// Shared fallback set — same authored quotes used on the front page so
// the "shared testimonial" between surfaces is intentional, not coincidence.
if (empty($testimonials)) {
    $testimonials = [
        [
            'quote'    => __('If you’re trying to jump into the metal business, contact New Tech Machinery. They’re the best! They’re going to give you the information that you need, and they’re going to help you grow your business.', 'standard'),
            'name'     => 'Danaik Garay',
            'company'  => 'Alsteel Metal Manufacturing',
            'location' => 'Fort Myers, Florida',
            'slug'     => 'Danaik-1',
        ],
        [
            'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and ease of switching out dies. If you do have a problem, there’s a sales team that you can call.', 'standard'),
            'name'     => 'Todd Andrews',
            'company'  => 'Classic Metals Inc.',
            'location' => 'Chester, South Carolina',
            'slug'     => 'Todd',
        ],
        [
            'quote'    => __('I chose New Tech Machinery over the competition because of how valued I felt as a customer. They truly valued me and appreciated me as a person, it wasn’t just another sale.', 'standard'),
            'name'     => 'Jim Averill',
            'company'  => 'Gunnison Sheet Metal',
            'location' => 'Gunnison, Colorado',
            'slug'     => 'Jim',
        ],
        [
            'quote'    => __('For me, New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted to have something that I can rely on.', 'standard'),
            'name'     => 'Abel Cisneros',
            'company'  => 'C&S Rain Gutters',
            'location' => 'Greeley, Colorado',
            'slug'     => 'Abel',
        ],
        [
            'quote'    => __('The relationship I’ve built with NTM has been fantastic. I call up Tom and tell him what I want, and I think he has me what I need on paper within the day.', 'standard'),
            'name'     => 'Mike Lemke',
            'company'  => 'Lemke Exteriors',
            'location' => 'Moorehead, Minnesota',
            'slug'     => 'Mike',
        ],
        [
            'quote'    => __('New Tech overall has been great to work with, and between the panel options and the service, that’s why we keep going back to them.', 'standard'),
            'name'     => 'Keith Ryan',
            'company'  => 'Metal Maniacs',
            'location' => 'Fort Myers, Florida',
            'slug'     => 'Keith',
        ],
    ];
}
if (empty($testimonials)) {
    return;
}

$content = [
    'eyebrow'    => __('From the field', 'standard'),
    'channel'    => __('Customer testimonials', 'standard'),
    'sr_title'   => __('Customer testimonials', 'standard'),
    'nav_label'  => __('Testimonial navigation', 'standard'),
    'cta_label'  => __('All customer stories', 'standard'),
    'cta_url'    => '/learning-center/category/testimonials/',
    'count_left' => __('Stories', 'standard'),
];

get_template_part('templates/parts/testimonial-slider', null, [
    'section_id'   => 'machine-case-study-title',
    'anchor'       => 'machine-case-study',
    'testimonials' => $testimonials,
    'content'      => $content,
    'cdn'          => $cdn,
]);
