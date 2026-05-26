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
        'label'    => __('Headquarters', 'standard'),
        'city'     => 'Aurora, Colorado',
        'meta'     => __('Engineering, manufacturing, training, support', 'standard'),
        'year'     => __('Since 1991', 'standard'),
    ],
    [
        'label'    => __('Second facility', 'standard'),
        'city'     => 'Hermosillo, Mexico',
        'meta'     => __('Manufacturing, regional support for Latin America', 'standard'),
        'year'     => __('Since 2004', 'standard'),
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

        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-red mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-support-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight mb-6">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                <?php echo esc_html($content['lede']); ?>
            </p>
        </div>

        <div class="grid gap-px bg-blue-200 border border-blue-200 mb-12 lg:mb-16 md:grid-cols-2">
            <?php foreach ($facilities as $facility) : ?>
                <div class="bg-blue-50 p-8 lg:p-10 grid gap-3 content-start">
                    <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                        <?php echo esc_html($facility['label']); ?>
                    </span>
                    <p class="font-sans font-medium text-blue-900 text-2xl md:text-3xl leading-tight tracking-tight">
                        <?php echo esc_html($facility['city']); ?>
                    </p>
                    <p class="font-sans text-blue-700 text-base leading-snug">
                        <?php echo esc_html($facility['meta']); ?>
                    </p>
                    <p class="font-mono uppercase tracking-wider text-xs text-red mt-2">
                        <?php echo esc_html($facility['year']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <dl class="border-t border-blue-200 grid gap-y-10 gap-x-10 md:grid-cols-2 lg:grid-cols-4 pt-10 lg:pt-12">
            <?php foreach ($pillars as $pillar) : ?>
                <div class="grid gap-3 content-start">
                    <dt class="font-mono uppercase tracking-wider text-xs text-red">
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
