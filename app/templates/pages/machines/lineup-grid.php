<?php
/**
 * Machines Page — Lineup
 *
 * Per category: if the first machine has a flagship badge, render it
 * as a full-bleed featured band. Render the remaining machines as a
 * 3-column lineup grid below. No 4+2 overflow centering: the flagship
 * carries the visual peak, the grid stays even.
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

$content = [
    'kicker' => __('01 / OUR MACHINES', 'standard'),
    'title'  => __('Machines for Every Project', 'standard'),
];

$categories = get_machine_categories();
?>

<section id="lineup" class="section" aria-labelledby="lineup-title">
    <div class="container section-content">

        <div class="grid gap-4 max-w-3xl">
            <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-500">
                <?php echo esc_html($content['kicker']); ?>
            </p>
            <h2 id="lineup-title" class="text-4xl font-medium tracking-tight text-blue-900 md:text-5xl lg:text-6xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <?php foreach ($categories as $slug => $category) : ?>
            <?php
            $machines = $category['machines'];

            // Flagship = first machine with a non-empty badge.
            $flagship       = null;
            $flagship_index = null;
            foreach ($machines as $i => $machine) {
                if (!empty($machine['badge'])) {
                    $flagship       = $machine;
                    $flagship_index = $i;
                    break;
                }
            }

            $rest = $machines;
            if ($flagship_index !== null) {
                array_splice($rest, $flagship_index, 1);
            }

            $rest_count = count($rest);
            $grid_cols  = $rest_count >= 3 ? 3 : max(1, $rest_count);
            ?>
            <div class="grid gap-10">

                <div class="flex items-baseline justify-between gap-4 border-b border-blue-200 pb-4">
                    <h3 class="font-mono text-sm font-medium text-blue-700 uppercase tracking-wider">
                        <?php echo esc_html($category['label']); ?>
                    </h3>
                    <?php if (!empty($category['url'])) : ?>
                        <a href="<?php echo esc_url(\Standard\Url\internal($category['url'])); ?>" class="inline-flex items-center gap-1 text-sm font-medium text-blue-500 hover:text-blue-500/80 transition-colors no-underline shrink-0">
                            <?php esc_html_e('View all', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if ($flagship) : ?>
                    <?php get_template_part('templates/pages/machines/lineup-flagship', null, ['machine' => $flagship]); ?>
                <?php endif; ?>

                <?php if (!empty($rest)) : ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($rest as $idx => $machine) : ?>
                            <div class="<?php echo esc_attr(get_card_border_classes($idx, $rest_count, $grid_cols)); ?>">
                                <?php get_template_part('templates/pages/machines/lineup-card', null, ['machine' => $machine]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>
</section>
