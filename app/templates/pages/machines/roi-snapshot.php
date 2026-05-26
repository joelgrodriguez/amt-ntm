<?php
/**
 * Machines Page — ROI Snapshot
 *
 * One dominant proof point reads as the section's headline ("$2.25 per
 * square foot"), with the supporting stats running as a single mono
 * ledger line beneath it. The calculator CTA closes the section.
 *
 * Replaces the previous three-up stat trio — that template is the
 * canonical SaaS hero-metric cliché, and the section deserves a
 * statement, not a dashboard widget.
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
    'lede'     => __('Saved on every square foot you roll, versus factory-bought panels.', 'standard'),
    'cta_text' => __('Calculate Your Profit', 'standard'),
    'cta_url'  => '/learning-center/download/portable-rollforming-profit-calculator/',
    'cta_secondary_text' => __('Read the Full ROI Breakdown', 'standard'),
    'cta_secondary_url'  => '/learning-center/what-is-the-roi-for-a-portable-metal-roof-panel-machine/',
];

$stats     = get_roi_stats();
$primary   = $stats[0] ?? null;
$secondary = array_slice($stats, 1);
?>

<section class="section bg-blue-900" aria-labelledby="roi-snapshot-title">
    <div class="container grid gap-12 lg:gap-12 lg:grid-cols-2 lg:items-end">

        <!-- Lead: the one stat. Display type, white, no decoration.
             The eyebrow is a side note, not a section masthead. -->
        <div class="grid gap-6">
            <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-300">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <?php if ($primary) : ?>
                <h2
                    id="roi-snapshot-title"
                    class="font-sans font-medium leading-[0.95] tracking-tight text-white text-5xl md:text-6xl lg:text-6xl xl:text-7xl m-0"
                >
                    <span class="text-blue-200" aria-hidden="true">+</span><?php echo esc_html($primary['stat']); ?>
                </h2>
                <p class="text-xl md:text-2xl lg:text-2xl text-blue-200 font-normal max-w-xl m-0">
                    <?php echo esc_html($content['lede']); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Supporting proof: mono ledger, sized like footnotes, not
             like co-equal hero numbers. Each stat is one line, separated
             by hairline rules. Reads as evidence behind the headline,
             not as a competing claim. -->
        <div class="grid gap-6">
            <?php if (!empty($secondary)) : ?>
                <dl class="grid divide-y divide-white/10 border-y border-white/10">
                    <?php foreach ($secondary as $stat) : ?>
                        <div class="grid grid-cols-[auto_1fr] items-baseline gap-6 py-4">
                            <dt class="font-mono text-2xl md:text-3xl text-white tabular-nums">
                                <?php echo esc_html($stat['stat']); ?>
                            </dt>
                            <dd class="font-mono text-xs uppercase tracking-[0.15em] text-blue-300 leading-snug">
                                <?php echo esc_html($stat['label']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-primary">
                    <?php echo esc_html($content['cta_text']); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-light">
                    <?php echo esc_html($content['cta_secondary_text']); ?>
                </a>
            </div>
        </div>

    </div>
</section>
