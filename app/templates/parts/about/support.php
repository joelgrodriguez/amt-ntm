<?php
/**
 * About — Support & Standing
 *
 * The "what happens after the sale" section. Two facilities anchor the
 * top, four post-sale pillars in the middle (parts, training, financing,
 * service), industry memberships as a footer row.
 *
 * This is where distributors, second-purchase buyers, and the committee
 * buyers stop scrolling. It earns its keep by being concrete: real plant
 * locations, real programs, named associations.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('After the sale', 'standard'),
    'title'   => __('You\'re buying the support, the parts, and the people. The machine is just where it starts.', 'standard'),
    'lede'    => __('Two manufacturing facilities, in-house parts inventory, factory-direct training, financing partners, and a service team that travels. NTM is backed by Mazzella Companies, which means the capital is there to keep investing.', 'standard'),
];

$facilities = [
    [
        'label'   => __('HQ · Sales · Manufacturing', 'standard'),
        'city'    => __('Aurora, Colorado', 'standard'),
        'address' => __('16265 E. 33rd Dr., Suite 40', 'standard'),
        'meta'    => __('Headquarters, engineering, assembly, QC', 'standard'),
        'year'    => __('Since 1991', 'standard'),
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-production-001.jpg',
        'alt'     => __('NTM production technician inspecting a rollforming machine on the Aurora shop floor.', 'standard'),
    ],
    [
        'label'   => __('Service · Support · Training', 'standard'),
        'city'    => __('Aurora, Colorado', 'standard'),
        'address' => __('16401 E. 33rd Dr., Suite 10', 'standard'),
        'meta'    => __('Service & engineering center, operator training', 'standard'),
        'year'    => __('Across the street', 'standard'),
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-service-001.jpg',
        'alt'     => __('NTM service technician working on a rollforming machine at the Aurora Service & Engineering Center.', 'standard'),
    ],
    [
        'label'   => __('Manufacturing', 'standard'),
        'city'    => __('Hermosillo, Mexico', 'standard'),
        'address' => __('Latitud Industrial Park', 'standard'),
        'meta'    => __('Second manufacturing facility, regional support for Latin America', 'standard'),
        'year'    => __('Since 2004', 'standard'),
        'image'   => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-production-007.jpg',
        'alt'     => __('NTM production floor showing rollforming machine assembly.', 'standard'),
    ],
];

$pillars = [
    [
        'label' => __('Parts pipeline', 'standard'),
        'body'  => __('Replacement parts ship from NTM inventory. The same drive rollers, controllers, and shear components used in current production.', 'standard'),
    ],
    [
        'label' => __('Operator training', 'standard'),
        'body'  => __('Hands-on training at the Aurora facility. Crews leave knowing how to run the machine and how to troubleshoot it.', 'standard'),
    ],
    [
        'label' => __('Financing', 'standard'),
        'body'  => __('Equipment financing through partner lenders, structured for owner-operators and growing crews.', 'standard'),
    ],
    [
        'label' => __('Field service', 'standard'),
        'body'  => __('Service techs travel for installs, refurbishments, and major repairs. Phone support for everything else.', 'standard'),
    ],
];

$memberships = [
    ['short' => 'MCA',  'name' => __('Metal Construction Association',         'standard')],
    ['short' => 'NRCA', 'name' => __('National Roofing Contractors Association', 'standard')],
    ['short' => 'CRA',  'name' => __('Colorado Roofing Association',           'standard')],
];
?>

<section class="bg-blue-50 py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-support-title">
    <div class="container">

        <div class="max-w-3xl mx-auto text-center mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500 mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-support-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight text-balance mb-6">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl mx-auto">
                <?php echo esc_html($content['lede']); ?>
            </p>
        </div>

        <ul class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-16 lg:mb-20" role="list">
            <?php foreach ($facilities as $facility) : ?>
                <li class="grid gap-5 content-start">
                    <figure class="grid gap-5">
                        <div class="aspect-[4/5] w-full overflow-hidden bg-blue-100">
                            <?php \Standard\Images\responsive_image($facility['image'], $facility['alt'], 'large', [
                                'class'   => 'block w-full h-full object-cover',
                                'loading' => 'lazy',
                            ]); ?>
                        </div>
                        <figcaption class="grid gap-2">
                            <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                                <?php echo esc_html($facility['label']); ?>
                            </span>
                            <p class="font-sans font-medium text-blue-900 text-xl md:text-2xl leading-tight tracking-tight">
                                <?php echo esc_html($facility['city']); ?>
                            </p>
                            <p class="font-mono text-sm text-blue-700 leading-snug">
                                <?php echo esc_html($facility['address']); ?>
                            </p>
                            <p class="font-sans text-blue-700 text-sm leading-snug mt-1">
                                <?php echo esc_html($facility['meta']); ?>
                            </p>
                            <p class="font-mono uppercase tracking-wider text-xs text-blue-500 mt-2">
                                <?php echo esc_html($facility['year']); ?>
                            </p>
                        </figcaption>
                    </figure>
                </li>
            <?php endforeach; ?>
        </ul>

        <dl class="border-t border-blue-200 grid gap-y-10 gap-x-10 md:grid-cols-2 lg:grid-cols-4 pt-10 lg:pt-12">
            <?php foreach ($pillars as $pillar) : ?>
                <div class="grid gap-3 content-start">
                    <dt class="font-mono uppercase tracking-wider text-xs text-blue-500">
                        <?php echo esc_html($pillar['label']); ?>
                    </dt>
                    <dd class="font-sans text-blue-700 text-base leading-relaxed">
                        <?php echo esc_html($pillar['body']); ?>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>

        <div class="mt-12 lg:mt-16 pt-8 border-t border-blue-200">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500 mb-5">
                <?php esc_html_e('Industry memberships', 'standard'); ?>
            </p>
            <ul class="grid gap-x-10 gap-y-4 sm:grid-cols-3" role="list">
                <?php foreach ($memberships as $m) : ?>
                    <li class="flex items-baseline gap-3">
                        <span class="font-mono font-medium text-blue-900 text-lg leading-none tracking-tight">
                            <?php echo esc_html($m['short']); ?>
                        </span>
                        <span class="font-sans text-blue-700 text-sm leading-snug">
                            <?php echo esc_html($m['name']); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</section>
