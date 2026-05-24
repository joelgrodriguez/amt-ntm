<?php
/**
 * Roof & Wall Panel Machines — Featured Model Band
 *
 * Surfaces the category flagship (SSQ3) as a full-bleed dark band,
 * matching the same treatment used in the parent /machines lineup.
 * Sits at position 3 so the visitor meets the flagship before
 * scrolling the full product grid.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_roof_wall_machines;

$machines = get_roof_wall_machines();
$featured = null;
foreach ($machines as $machine) {
    if (!empty($machine['featured']) || !empty($machine['badge'])) {
        $featured = $machine;
        break;
    }
}

if (!$featured) {
    return;
}
?>

<section class="section" aria-labelledby="roof-wall-featured-title">
    <div class="container section-content">
        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('Featured', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="roof-wall-featured-title" class="section-title">
                <?php esc_html_e('Our Flagship Roof Panel Machine', 'standard'); ?>
            </h2>
        </div>

        <?php get_template_part('templates/pages/machines/lineup-flagship', null, ['machine' => $featured]); ?>
    </div>
</section>
