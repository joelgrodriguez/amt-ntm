<?php
/**
 * Machines Page — Brand Statement
 *
 * Centered brand value proposition text block with square grid pattern.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'title' => __('Stop Buying Panels. Start Making Profit.', 'standard'),
    'text'  => __("Every panel you buy from a supplier is profit you're giving away. NTM portable rollformers let you fabricate standing seam roofing and seamless gutters on-site, cutting material costs in half, winning more bids, and controlling your own schedule. Trusted by contractors in 40+ countries.", 'standard'),
];
?>

<section class="py-20 md:py-28 lg:py-36 xl:py-44" aria-labelledby="brand-statement-title">
    <div class="container grid gap-8 max-w-5xl mx-auto text-center">
        <h2 id="brand-statement-title" class="text-5xl font-medium tracking-tight text-blue-900 leading-[0.95] md:text-6xl lg:text-7xl xl:text-8xl">
            <?php echo esc_html($content['title']); ?>
        </h2>
        <p class="text-lg text-blue-600 leading-relaxed max-w-2xl mx-auto lg:text-xl">
            <?php echo esc_html($content['text']); ?>
        </p>
    </div>
</section>
