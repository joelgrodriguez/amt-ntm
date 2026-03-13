<?php
/**
 * Seamless Gutter Machines — Product Grid
 *
 * 2-column grid of seamless gutter machines.
 * Reuses the lineup-card.php template part for each machine.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_gutter_machines;
use function Standard\MachinesData\get_card_border_classes;

$content = [
    'eyebrow' => __('The Lineup', 'standard'),
    'title'   => __('Seamless Gutter Machines', 'standard'),
];

$machines = get_gutter_machines();
$cols = 2;
?>

<section id="product-grid" class="section" aria-labelledby="gutter-grid-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="gutter-grid-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2">
            <?php foreach ($machines as $idx => $machine) : ?>
                <div class="<?php echo esc_attr(get_card_border_classes($idx, count($machines), $cols)); ?>">
                    <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
