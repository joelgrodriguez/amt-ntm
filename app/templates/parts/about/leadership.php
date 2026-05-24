<?php
/**
 * About — Industry Standing
 *
 * Posture section, not a second leadership claim. Names the industry
 * associations NTM participates in and adds a secondary CTA to the
 * current machine lineup so a reader who came in on the leadership
 * claim has a fast exit to the current product surface.
 *
 * Title was previously a restated leadership boast; it now reads as
 * an industry-participation header. Body lede is one factual sentence,
 * no superlatives, no hedge.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'  => __('Industry standing', 'standard'),
    'title'    => __('Where we sit in the industry.', 'standard'),
    'lede'     => __('NTM machines run on six continents. We participate in the three associations that keep the industry honest.', 'standard'),
    'cta'      => __('See the current lineup', 'standard'),
    'cta_url'  => '/machines/',
];

$memberships = [
    [
        'short' => 'MCA',
        'name'  => __('Metal Construction Association', 'standard'),
    ],
    [
        'short' => 'NRCA',
        'name'  => __('National Roofing Contractors Association', 'standard'),
    ],
    [
        'short' => 'CRA',
        'name'  => __('Colorado Roofing Association', 'standard'),
    ],
];
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="about-leadership-title">
    <div class="container">
        <div class="max-w-4xl mb-10 lg:mb-12">
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
        <ul class="border-t border-blue-200 grid gap-x-10 gap-y-4 sm:grid-cols-3 pt-6" role="list">
            <?php foreach ($memberships as $m) : ?>
                <li class="grid gap-1">
                    <span class="font-mono font-medium text-blue-900 text-xl md:text-2xl leading-none tracking-tight">
                        <?php echo esc_html($m['short']); ?>
                    </span>
                    <span class="font-sans text-blue-700 text-sm leading-snug">
                        <?php echo esc_html($m['name']); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="mt-10 lg:mt-14">
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-secondary">
                <?php echo esc_html($content['cta']); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>

    </div>
</section>
