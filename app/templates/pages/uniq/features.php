<?php
/**
 * UNIQ Page — Feature Grid
 *
 * Four-column blueprint grid with hairline column dividers between
 * features. Mono spec affix (01 / DISPLAY) sits above each title; sans
 * body underneath. The pattern reads like a spec sheet, not a
 * marketing icon-grid.
 *
 * The hairline dividers use the right-edge convention so the last
 * column doesn't ship an orphan border. On <md the grid collapses to a
 * single column and the dividers become bottom borders.
 *
 * @package Standard
 *
 * @usage page-uniq-control-system.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_uniq_detailed_features;

$content = [
    'eyebrow'  => __('Capabilities', 'standard'),
    'title'    => __('What the UNIQ Controller Does', 'standard'),
    'subtitle' => __('Four jobs, one interface. Every input the operator needs is on the touchscreen; every override is on the push-button panel.', 'standard'),
];

$features = get_uniq_detailed_features();
$last_idx = count($features) - 1;
?>

<section class="section bg-white border-y border-blue-200" aria-labelledby="uniq-features-title">
    <div class="container section-content">

        <div class="section-header max-w-3xl mx-auto">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="uniq-features-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle max-w-2xl mx-auto">
                <?php echo esc_html($content['subtitle']); ?>
            </p>
        </div>

        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 border-blue-200 divide-y divide-blue-200 md:divide-y-0">
            <?php foreach ($features as $i => $feature) :
                // Single-col (mobile): rely on .divide-y on the <ul>.
                // 2-col (md): row 1 (i=0,1) gets bottom border; col 1 (i=0,2) gets right border.
                // 4-col (lg): no bottom borders; right border on all but the last (i=0,1,2).
                $cls = '';
                // md row separator (items 0,1)
                if ($i < 2) {
                    $cls .= ' md:border-b md:border-blue-200 lg:border-b-0';
                }
                // md column separator (items 0,2)
                if ($i % 2 === 0) {
                    $cls .= ' md:border-r md:border-blue-200';
                }
                // lg column separator (items 0,1,2 — everyone but the last)
                if ($i < $last_idx) {
                    $cls .= ' lg:border-r lg:border-blue-200';
                }
            ?>
                <li class="grid gap-4 content-start p-6 lg:p-8<?php echo esc_attr($cls); ?>">
                    <span class="font-mono text-[11px] uppercase tracking-[0.18em] text-blue-500">
                        <?php echo esc_html($feature['spec']); ?>
                    </span>
                    <h3 class="font-sans font-medium text-xl text-blue-900 leading-tight">
                        <?php echo esc_html($feature['title']); ?>
                    </h3>
                    <p class="font-sans text-base text-blue-600 leading-relaxed">
                        <?php echo esc_html($feature['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
