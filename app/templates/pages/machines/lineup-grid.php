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

use function Standard\MachinesData\get_machine_categories;

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
                <h3 class="text-lg font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-200 pb-4">
                    <?php echo esc_html($category['label']); ?>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-<?php echo esc_attr((string) $cols); ?>">
                    <?php foreach ($top_row as $idx => $machine) : ?>
                        <?php
                        // Right border: every card except last in its row
                        // sm (2-col): even-index cards get border-r (0, 2, 4…)
                        // lg (n-col): every card except nth gets border-r
                        $is_last_sm = ($idx % 2 === 1) || ($idx === count($top_row) - 1);
                        $is_last_lg = (($idx + 1) % $cols === 0) || ($idx === count($top_row) - 1);
                        $border_classes = 'border-b border-slate-200';
                        $border_classes .= $is_last_sm ? '' : ' sm:border-r';
                        $border_classes .= $is_last_lg ? ' lg:border-r-0' : ' lg:border-r';
                        ?>
                        <div class="<?php echo esc_attr($border_classes); ?>">
                            <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!empty($bottom_row)) : ?>
                        <?php
                        $overflow_count = count($bottom_row);
                        $offset = (int) floor(($cols - $overflow_count) / 2);
                        ?>
                        <?php foreach ($bottom_row as $i => $machine) : ?>
                            <?php
                            $col_start = $offset + $i + 1;
                            $is_last = ($i === $overflow_count - 1);
                            $overflow_border = 'border-b border-slate-200';
                            $overflow_border .= ($i % 2 === 0 && !$is_last) ? ' sm:border-r' : '';
                            $overflow_border .= $is_last ? '' : ' lg:border-r';
                            ?>
                            <div class="lg:col-start-<?php echo esc_attr((string) $col_start); ?> <?php echo esc_attr($overflow_border); ?>">
                                <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>
