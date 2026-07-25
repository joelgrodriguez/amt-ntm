<?php
/**
 * Careers — What it's like
 *
 * Photo-led day-to-day flavor using approved About/support assets.
 * Captions stay role- and place-based — no named employees, no HR-risky claims.
 *
 * @package Standard
 * @usage Careers (templates/template-careers.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('On the floor', 'standard'),
    'title'   => __('Most roles sit close to manufacturing, service, or the customer.', 'standard'),
    'lede'    => __('NTM is a manufacturing and support company. Day-to-day work often happens on the shop floor, in the service bay, or on the phone with machine owners.', 'standard'),
];

$scenes = [
    [
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-production-001.jpg'),
        'alt'     => __('NTM production technician inspecting a rollforming machine on the Aurora shop floor.', 'standard'),
        'label'   => __('Aurora manufacturing', 'standard'),
        'caption' => __('Assembly, quality checks, and crating at headquarters in Aurora, Colorado.', 'standard'),
    ],
    [
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-service-001.jpg'),
        'alt'     => __('NTM service technician working on a rollforming machine at the Aurora Service & Engineering Center.', 'standard'),
        'label'   => __('Service & engineering', 'standard'),
        'caption' => __('Service work and operator training at the Aurora Service & Engineering Center.', 'standard'),
    ],
    [
        'image'   => \Standard\Url\canonical('https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-team-production-007.jpg'),
        'alt'     => __('NTM production floor showing rollforming machine assembly.', 'standard'),
        'label'   => __('Hermosillo plant', 'standard'),
        'caption' => __('Second manufacturing facility in Hermosillo, Mexico, supporting production and regional demand.', 'standard'),
    ],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="careers-life-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-16 mb-12 lg:mb-16">
            <div class="lg:col-span-6 grid gap-6 content-start">
                <p class="font-mono uppercase tracking-wider text-xs text-blue-500">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <h2 id="careers-life-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight text-balance">
                    <?php echo esc_html($content['title']); ?>
                </h2>
            </div>
            <div class="lg:col-span-6 flex lg:items-end">
                <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>
            </div>
        </div>

        <ul class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8" role="list">
            <?php foreach ($scenes as $scene) : ?>
                <li>
                    <figure class="grid gap-4">
                        <div class="aspect-[4/3] w-full overflow-hidden bg-blue-100 border border-blue-200">
                            <?php \Standard\Images\responsive_image($scene['image'], $scene['alt'], 'large', [
                                'class'   => 'block w-full h-full object-cover',
                                'loading' => 'lazy',
                            ]); ?>
                        </div>
                        <figcaption class="grid gap-1">
                            <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                                <?php echo esc_html($scene['label']); ?>
                            </span>
                            <span class="font-sans text-blue-900 text-base leading-snug">
                                <?php echo esc_html($scene['caption']); ?>
                            </span>
                        </figcaption>
                    </figure>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
