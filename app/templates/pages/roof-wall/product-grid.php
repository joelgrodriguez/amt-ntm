<?php
/**
 * Roof & Wall Panel Machines — Product Grid
 *
 * 3-column grid of roof & wall panel machines.
 * Reuses the lineup-card.php template part for each machine.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_roof_wall_machines;
use function Standard\MachinesData\get_card_border_classes;
use function Standard\MachinesData\get_overflow_border_classes;

$content = [
    'eyebrow' => __('The Lineup', 'standard'),
    'title'   => __('Roof & Wall Panel Machines', 'standard'),
];

$machines = get_roof_wall_machines();
$count = count($machines);
$cols = 3;
$has_overflow = ($count % $cols !== 0);
$top_row = $has_overflow ? array_slice($machines, 0, $cols) : $machines;
$bottom_row = $has_overflow ? array_slice($machines, $cols) : [];
?>

<section id="product-grid" class="section" aria-labelledby="roof-wall-grid-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="roof-wall-grid-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($top_row as $idx => $machine) : ?>
                <div class="<?php echo esc_attr(get_card_border_classes($idx, count($top_row), $cols)); ?>">
                    <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                </div>
            <?php endforeach; ?>

            <?php if (!empty($bottom_row)) : ?>
                <?php
                $overflow_count = count($bottom_row);
                $offset = (int) floor(($cols - $overflow_count) / 2);
                ?>
                <?php foreach ($bottom_row as $i => $machine) : ?>
                    <?php $col_start = $offset + $i + 1; ?>
                    <div class="lg:col-start-<?php echo esc_attr((string) $col_start); ?> <?php echo esc_attr(get_overflow_border_classes($i, $overflow_count)); ?>">
                        <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>
