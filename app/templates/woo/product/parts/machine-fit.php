<?php
/**
 * Machine Product — "Is For / Is Not For" Fit Section
 *
 * Two side-by-side cards (stacked on mobile) that help visitors
 * quickly determine whether this machine fits their needs.
 *
 * @package Standard
 * @var array{machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_link;

$machine = $args['machine'] ?? [];
$fit     = $machine['fit'] ?? null;

if (!$fit || (empty($fit['is_for']) && empty($fit['is_not_for']))) {
    return;
}

$is_for     = $fit['is_for'] ?? [];
$is_not_for = $fit['is_not_for'] ?? [];
?>

<section id="machine-fit" class="section-compact bg-slate-50" aria-labelledby="machine-fit-title">
    <div class="container section-content">

        <div class="section-header">
            <span class="section-eyebrow"><?php esc_html_e('Machine Fit', 'standard'); ?></span>
            <div class="section-divider-center"></div>
            <h2 id="machine-fit-title" class="section-title">
                <?php esc_html_e('Is This Machine Right for You?', 'standard'); ?>
            </h2>
        </div>

        <div class="machine-fit__grid">

            <?php if (!empty($is_for)) : ?>
                <div class="machine-fit__card machine-fit__card--for">
                    <h3 class="machine-fit__heading machine-fit__heading--for">
                        <?php icon('check', ['class' => 'w-5 h-5']); ?>
                        <?php esc_html_e('This Machine IS For', 'standard'); ?>
                    </h3>
                    <ul class="machine-fit__list">
                        <?php foreach ($is_for as $item) : ?>
                            <li><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($is_not_for)) : ?>
                <div class="machine-fit__card machine-fit__card--not-for">
                    <h3 class="machine-fit__heading machine-fit__heading--not-for">
                        <?php icon('x', ['class' => 'w-5 h-5']); ?>
                        <?php esc_html_e('This Machine Is NOT For', 'standard'); ?>
                    </h3>
                    <ul class="machine-fit__list">
                        <?php foreach ($is_not_for as $item) :
                            $text    = is_array($item) ? $item['text'] : $item;
                            $machine_key = is_array($item) ? ($item['machine'] ?? null) : null;
                            $link    = $machine_key ? get_machine_product_link($machine_key) : null;
                        ?>
                            <li>
                                <?php echo esc_html($text); ?>
                                <?php if ($link) : ?>
                                    <span class="machine-fit__alt">
                                        &rarr; <a href="<?php echo esc_url($link['url']); ?>" class="machine-fit__link">
                                            <?php echo esc_html($link['name']); ?>
                                        </a>
                                    </span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>
