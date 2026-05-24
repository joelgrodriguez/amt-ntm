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

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Grid\get_card_border_classes;
use function Standard\Grid\get_lg_grid_cols_class;

$machines   = $args['machines'] ?? [];
$content    = $args['content'] ?? [];
$section_id = $args['section_id'] ?? 'product-grid';

$count = count($machines);
$cols = $args['cols'] ?? null;
if (!$cols) {
    if ($count % 3 === 0) {
        $cols = 3;
    } elseif ($count % 2 === 0) {
        $cols = 2;
    } else {
        $cols = 3;
    }
}
$cols = max(1, min(4, (int) $cols));
?>

<section id="<?php echo esc_attr($section_id); ?>" class="section" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 <?php echo esc_attr(get_lg_grid_cols_class($cols)); ?> border border-blue-200">
            <?php foreach ($machines as $idx => $machine) : ?>
                <div class="<?php echo esc_attr(get_card_border_classes($idx, $count, $cols)); ?>">
                    <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
