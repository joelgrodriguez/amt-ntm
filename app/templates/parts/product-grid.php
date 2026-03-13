<?php
/**
 * Product Grid — Shared Template Part
 *
 * Responsive grid of machine cards with overflow row centering.
 * Reuses the lineup-card.php template part for each machine.
 *
 * @package Standard
 *
 * @param array  $machines   Array of machine data.
 * @param int    $cols       Number of columns (2 or 3).
 * @param array  $content    {eyebrow, title}
 * @param string $section_id ID used for aria-labelledby and section id.
 */

declare(strict_types=1);

use function Standard\MachinesData\get_card_border_classes;
use function Standard\MachinesData\get_overflow_border_classes;

$machines   = $args['machines'] ?? [];
$cols       = $args['cols'] ?? 3;
$content    = $args['content'] ?? [];
$section_id = $args['section_id'] ?? 'product-grid';

$count        = count($machines);
$has_overflow  = ($count % $cols !== 0);
$top_row       = $has_overflow ? array_slice($machines, 0, $cols) : $machines;
$bottom_row    = $has_overflow ? array_slice($machines, $cols) : [];
$grid_cols_class = $cols === 2 ? 'sm:grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3';
?>

<section id="<?php echo esc_attr($section_id); ?>" class="section" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 <?php echo esc_attr($grid_cols_class); ?>">
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
