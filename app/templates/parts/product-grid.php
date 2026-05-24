<?php
/**
 * Product Grid — Shared Template Part
 *
 * Responsive grid of machine cards. Renders every machine through the
 * single canonical card-product partial so /roof-wall-panel-machines,
 * /seamless-gutter-machines, and any other consumer share one card style.
 *
 * @package Standard
 *
 * @param array  $machines    Array of machine data (raw rows from machines-data).
 * @param array  $content     {eyebrow, title}
 * @param string $section_id  ID used for aria-labelledby and section id.
 * @param string $category_key Category key for the to_card_product mapper
 *                             ('roof-wall' or 'gutter'). Defaults to '' which
 *                             leaves the category label empty on the card.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\to_card_product;

$machines     = $args['machines'] ?? [];
$content      = $args['content'] ?? [];
$section_id   = $args['section_id'] ?? 'product-grid';
$category_key = (string) ($args['category_key'] ?? '');
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

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($machines as $machine) : ?>
                <?php get_template_part('templates/parts/card-product', null, [
                    'product' => to_card_product($machine, $category_key),
                ]); ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
