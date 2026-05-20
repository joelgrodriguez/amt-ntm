<?php
/**
 * About — Leadership & Industry Standing
 *
 * Quiet light section. No chrome bars. Restates the global reach claim
 * and lists the three industry associations as a hairline-divided mono
 * row. No closing CTA here; that lives in the shared closer that runs
 * after this section.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Industry standing', 'standard'),
    'title'   => __('The world\'s leading portable rollforming manufacturer. We don\'t use that line lightly.', 'standard'),
    'lede'    => __('NTM machines run on six continents. If you need a gutter machine in Ghana or a roof panel machine in Russia, we can get it there. We are a proud member of the three associations that keep the industry honest.', 'standard'),
];

$memberships = [
    [
        'short' => 'MCA',
        'name'  => __('Metal Construction Association', 'standard'),
        'meta'  => __('Industry standards body', 'standard'),
    ],
    [
        'short' => 'NRCA',
        'name'  => __('National Roofing Contractors Association', 'standard'),
        'meta'  => __('National contractor organization', 'standard'),
    ],
    [
        'short' => 'CRA',
        'name'  => __('Colorado Roofing Association', 'standard'),
        'meta'  => __('Home state association', 'standard'),
    ],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-leadership-title">
    <div class="container">

        <!-- Eyebrow + headline + lede -->
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-red mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="about-leadership-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight mb-6">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                <?php echo esc_html($content['lede']); ?>
            </p>
        </div>

        <!-- Memberships row: three mono cells on a hairline rail. -->
        <dl class="grid grid-cols-1 md:grid-cols-3 border-t border-blue-200 [&>div]:border-l [&>div]:border-blue-200 [&>div:first-child]:border-l-0">
            <?php foreach ($memberships as $i => $m) : ?>
                <div class="grid gap-2 px-6 py-8 lg:px-8 lg:py-10 <?php echo $i > 0 ? 'border-t md:border-t-0 border-blue-200' : ''; ?>">
                    <dt class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400">
                        <?php esc_html_e('Member', 'standard'); ?>
                    </dt>
                    <dd class="grid gap-1">
                        <span class="font-mono font-medium text-blue-900 text-2xl md:text-3xl leading-none tracking-tight">
                            <?php echo esc_html($m['short']); ?>
                        </span>
                        <span class="font-sans text-blue-700 text-sm md:text-base leading-snug">
                            <?php echo esc_html($m['name']); ?>
                        </span>
                        <span class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400 mt-1">
                            <?php echo esc_html($m['meta']); ?>
                        </span>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>

    </div>
</section>
