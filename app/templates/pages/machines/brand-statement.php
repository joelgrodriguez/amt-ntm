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

<section class="section" aria-labelledby="brand-statement-title">
    <div class="container grid gap-6 max-w-3xl mx-auto text-center">
        <div class="section-divider-center"></div>
        <h2 id="brand-statement-title" class="section-title">
            <?php echo esc_html($content['title']); ?>
        </h2>
        <p class="section-subtitle">
            <?php echo esc_html($content['text']); ?>
        </p>
    </div>
</section>
