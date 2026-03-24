<?php
/**
 * Machines Page — Lineup Grid
 *
 * Toyota-style product lineup: clean columns with product image,
 * year, bold name, spec highlights, and "Explore More" link.
 * Two categories: Roof & Wall Panel Machines, Seamless Gutter Machines.
 *
 * Roof machines with 6 items use a 4-col grid with the last 2 cards
 * centered via flex wrapper. Gutter machines show price + dual CTAs.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_machine_categories;
use function Standard\Grid\get_card_border_classes;
use function Standard\Grid\get_overflow_border_classes;

$content = [
    'eyebrow' => __('Our Machines', 'standard'),
    'title'   => __('Machines for Every Project', 'standard'),
];

$categories = get_machine_categories();
?>

<section id="lineup" class="section" aria-labelledby="lineup-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="lineup-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <?php foreach ($categories as $slug => $category) : ?>
            <?php
            $machines = $category['machines'];
            $count = count($machines);
            $cols = $count >= 4 ? 4 : $count;
            $has_overflow = ($cols === 4 && $count % 4 !== 0);
            $top_row = $has_overflow ? array_slice($machines, 0, 4) : $machines;
            $bottom_row = $has_overflow ? array_slice($machines, 4) : [];
            ?>
            <div>
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <h3 class="text-lg font-semibold text-slate-400 uppercase tracking-wider">
                        <?php echo esc_html($category['label']); ?>
                    </h3>
                    <?php if (!empty($category['url'])) : ?>
                        <a href="<?php echo esc_url($category['url']); ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary/80 transition-colors no-underline">
                            <?php esc_html_e('View All', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-<?php echo esc_attr((string) $cols); ?>">
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
        <?php endforeach; ?>

    </div>
</section>
