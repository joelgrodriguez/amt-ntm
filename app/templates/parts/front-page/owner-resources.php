<?php
/**
 * Front-page section: owner resources fast-lane.
 *
 * People who already own an NTM machine need a direct path to reference
 * material — manuals, profiles, UL test info, and the Service Hub — without
 * drilling through the sales funnel (service-dept feedback, Ron; issue #105).
 * The buyer-facing decision tools live in the separate `tools` section.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Already Using an NTM Machine?', 'standard'),
    'title'   => __('Machine Resources.', 'standard'),
    'body'    => __('Find NTM machine manuals, service and support resources, panel profile specifications, and UL testing documentation.', 'standard'),
];

$resources = [
    [
        'icon'  => 'life-buoy',
        'title' => __('Get Support', 'standard'),
        'url'   => '/service-hub/',
    ],
    [
        'icon'  => 'file-text',
        'title' => __('Manuals & Specs', 'standard'),
        'url'   => '/machines/manuals/',
    ],
    [
        'icon'  => 'folder',
        'title' => __('Panel Profiles', 'standard'),
        'url'   => '/profiles/',
    ],
    [
        'icon'  => 'check',
        'title' => __('UL Test Booklets', 'standard'),
        'url'   => '/learning-center/resource/ul-test-information/',
    ],
];
?>

<section class="section bg-blue-50 border-y border-blue-200" aria-labelledby="owner-resources-title">
    <div class="container grid gap-12 lg:gap-16">

        <?php get_template_part('templates/parts/section-header', null, [
            'id'          => 'owner-resources-title',
            'eyebrow'     => $content['eyebrow'],
            'eyebrow_dot' => false,
            'title'       => $content['title'],
            'lede'        => $content['body'],
            'max_width'   => 'max-w-2xl',
        ]); ?>

        <div class="grid grid-cols-2 gap-px border border-blue-200 bg-blue-200 md:grid-cols-4">
            <?php foreach ($resources as $resource) : ?>
            <a
                href="<?php echo esc_url(\Standard\Url\internal($resource['url'])); ?>"
                class="group flex min-h-[9rem] flex-col justify-between bg-white p-5 no-underline transition-colors duration-200 hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px] sm:p-6"
                >
                    <h3 class="font-mono text-sm font-medium uppercase tracking-wider text-blue-700 transition-colors duration-200 group-hover:text-blue-50 md:text-base">
                        <?php echo esc_html($resource['title']); ?>
                    </h3>
                    <div class="flex items-end justify-between">
                        <?php icon($resource['icon'], [
                            'class'       => 'w-8 h-8 text-blue-700 transition-colors duration-200 group-hover:text-blue-50 md:w-10 md:h-10',
                            'aria-hidden' => 'true',
                        ]); ?>
                        <?php icon('arrow-right', [
                            'class'       => 'w-5 h-5 text-blue-400 transition-colors duration-200 group-hover:text-blue-50',
                            'aria-hidden' => 'true',
                        ]); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
