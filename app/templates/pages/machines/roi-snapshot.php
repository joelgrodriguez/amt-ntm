<?php
/**
 * Machines Page — ROI Snapshot
 *
 * Compact stat bar showing key ROI data points with link
 * to the existing profit calculator tool.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_roi_stats;

$content = [
    'eyebrow'  => __('Return on Investment', 'standard'),
    'title'    => __('The Numbers Speak for Themselves', 'standard'),
    'cta_text' => __('Calculate Your Profit', 'standard'),
    'cta_url'  => '/learning-center/download/portable-rollforming-profit-calculator/',
    'cta_secondary_text' => __('Read the Full ROI Breakdown', 'standard'),
    'cta_secondary_url'  => '/learning-center/what-is-the-roi-for-a-portable-metal-roof-panel-machine/',
];

$stats = get_roi_stats();
?>

<section class="section bg-blue-900" aria-labelledby="roi-snapshot-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-medium uppercase tracking-wider text-blue-500">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="roi-snapshot-title" class="text-3xl font-medium text-white md:text-4xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-4xl mx-auto text-center">
            <?php foreach ($stats as $stat) : ?>
                <div class="grid gap-2">
                    <span class="text-3xl font-medium text-blue-500 sm:text-4xl lg:text-5xl">
                        <?php echo esc_html($stat['stat']); ?>
                    </span>
                    <span class="text-sm text-blue-400 uppercase tracking-wider">
                        <?php echo esc_html($stat['label']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-primary">
                <?php echo esc_html($content['cta_text']); ?>
                <?php icon('dollar-sign', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light">
                <?php echo esc_html($content['cta_secondary_text']); ?>
            </a>
        </div>

    </div>
</section>
