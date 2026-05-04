<?php
/**
 * Accessory Product — Compatible Machines
 *
 * Renders machine cards prepared by the accessory helpers.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$cards = \Standard\Woo\Accessories\get_compatible_machine_cards(4);

if ($cards === []) {
    return;
}
?>

<section class="section bg-light" aria-labelledby="compatible-machines-title">
    <div class="container section-content">
        <div class="section-header-left mb-10">
            <p class="section-eyebrow"><?php esc_html_e('Compatibility', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="compatible-machines-title" class="section-title"><?php esc_html_e('Works With These Machines', 'standard'); ?></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach (array_slice($cards, 0, 4) as $card) : ?>
                <?php get_template_part('templates/woo/product/parts/product-card-link', null, compact('card')); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
