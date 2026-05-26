<?php
/**
 * UNIQ Page feature grid.
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
$count    = count($features);
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

        <ul role="list" class="grid grid-cols-1 md:grid-cols-2 divide-y divide-blue-200 md:divide-y-0">
            <?php foreach ($features as $i => $feature) :
                // 2x2 pair grid. Row 1 = items 0,1. Row 2 = items 2,3.
                // Borders:
                //   - md+: items in row 1 (i<2) get a bottom border
                //          to separate from row 2.
                //   - md+: items in column 1 (even i) get a right
                //          border to separate from column 2.
                $cls = '';
                if ($i < $count - 2) {
                    $cls .= ' md:border-b md:border-blue-200';
                }
                if ($i % 2 === 0) {
                    $cls .= ' md:border-r md:border-blue-200';
                }
            ?>
                <li class="grid gap-4 content-start p-8 md:p-10 lg:p-12<?php echo esc_attr($cls); ?>">
                    <span class="font-mono font-medium text-[11px] uppercase tracking-mono-label text-blue-500">
                        <?php echo esc_html($feature['spec']); ?>
                    </span>
                    <h3 class="font-sans font-medium text-xl md:text-2xl text-blue-900 leading-tight">
                        <?php echo esc_html($feature['title']); ?>
                    </h3>
                    <p class="font-sans text-base text-blue-600 leading-relaxed max-w-prose">
                        <?php echo esc_html($feature['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
