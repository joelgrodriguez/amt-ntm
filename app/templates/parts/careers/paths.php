<?php
/**
 * Careers — Career paths
 *
 * Candidate-facing expansion of the four disciplines already named on
 * About (engineering, production, service, customer service). No new
 * departments invented beyond that proven language.
 *
 * @package Standard
 * @usage Careers (templates/template-careers.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Career paths', 'standard'),
    'title'   => __('Where people work at New Tech Machinery', 'standard'),
    'lede'    => __('Openings change. The work usually falls into engineering, production, service, and customer support around portable rollforming machines.', 'standard'),
];

$paths = [
    [
        'role'    => __('Engineering', 'standard'),
        'caption' => __('Mechanical, electrical, and controls work on portable rollformers and related systems, based with the Aurora team.', 'standard'),
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-engineer-003.jpg'),
        'alt'     => __('NTM engineer at a workstation reviewing CAD drawings of a rollformer mechanism.', 'standard'),
    ],
    [
        'role'    => __('Production', 'standard'),
        'caption' => __('Shop-floor assembly, testing, and crating in Aurora and Hermosillo. Manufacturing jobs that stay close to the finished machine.', 'standard'),
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-production-004.jpg'),
        'alt'     => __('NTM production technician on the Aurora shop floor assembling a rollforming machine.', 'standard'),
    ],
    [
        'role'    => __('Service', 'standard'),
        'caption' => __('Field repairs, refurbishments, installs, and phone support for machines already working on jobsites.', 'standard'),
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-service-002.jpg'),
        'alt'     => __('NTM service technician inspecting a rollforming machine in the field.', 'standard'),
    ],
    [
        'role'    => __('Customer support', 'standard'),
        'caption' => __('Colorado-based customer service for machine owners and crews who need clear answers about their equipment.', 'standard'),
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-customer-service-004.jpg'),
        'alt'     => __('NTM customer service representative on a headset call with a contractor.', 'standard'),
    ],
];
?>

<section id="career-paths" class="bg-blue-50 py-16 lg:py-24 border-t border-blue-200" aria-labelledby="careers-paths-title">
    <div class="container">
        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500 mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="careers-paths-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight text-balance mb-6">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                <?php echo esc_html($content['lede']); ?>
            </p>
        </div>

        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" role="list">
            <?php foreach ($paths as $path) : ?>
                <li class="grid gap-4 content-start">
                    <figure class="grid gap-4">
                        <div class="aspect-[4/5] w-full overflow-hidden bg-blue-100">
                            <?php \Standard\Images\responsive_image($path['image'], $path['alt'], 'large', [
                                'class'   => 'block w-full h-full object-cover',
                                'loading' => 'lazy',
                            ]); ?>
                        </div>
                        <figcaption class="grid gap-2">
                            <h3 class="font-mono uppercase tracking-wider text-xs text-blue-500 m-0">
                                <?php echo esc_html($path['role']); ?>
                            </h3>
                            <p class="font-sans text-blue-900 text-base leading-snug m-0">
                                <?php echo esc_html($path['caption']); ?>
                            </p>
                        </figcaption>
                    </figure>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
