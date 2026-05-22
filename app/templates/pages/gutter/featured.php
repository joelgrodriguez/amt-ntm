<?php
/**
 * Seamless Gutter Machines — Featured Model Band
 *
 * Surfaces the category featured machine (MACH II 5"/6" Combo) as a
 * full-bleed dark band, matching the same treatment used in the
 * parent /machines lineup. Sits at position 3.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_machines;

$machines = get_gutter_machines();

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

<section class="section" aria-labelledby="gutter-featured-title">
    <div class="container section-content">
        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('Featured', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="gutter-featured-title" class="section-title">
                <?php esc_html_e('Our Featured Gutter Machine', 'standard'); ?>
            </h2>
        </div>

        <?php get_template_part('templates/pages/machines/lineup-flagship', null, ['machine' => $featured]); ?>
    </div>
</section>
