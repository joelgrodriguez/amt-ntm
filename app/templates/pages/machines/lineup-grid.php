<?php
/**
 * Machines Page — Lineup
 *
 * Per category: a single grid of card-product cards. Every machine renders
 * through the same canonical card so the page reads as one consolidated
 * lineup. Flagship machines surface their identity via the `Flagship` badge
 * on the card itself; no separate spotlight band.
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
use function Standard\MachinesData\to_card_product;

$content = [
    'eyebrow' => __('Our Machines', 'standard'),
    'title'   => __('Machines for Every Project', 'standard'),
];

$categories = get_machine_categories();
?>

<section id="lineup" class="section" aria-labelledby="lineup-title">
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="lineup-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <?php foreach ($categories as $key => $category) : ?>
            <div class="grid gap-10">

                <div class="flex items-baseline justify-between gap-4">
                    <h3 class="text-xl font-medium text-blue-900 tracking-tight md:text-2xl">
                        <?php echo esc_html($category['label']); ?>
                    </h3>
                    <?php if (!empty($category['url'])) : ?>
                        <a href="<?php echo esc_url(\Standard\Url\internal($category['url'])); ?>" class="inline-flex items-center gap-1 text-sm font-medium text-blue-500 hover:text-blue-500/80 transition-colors no-underline shrink-0">
                            <?php esc_html_e('View all', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($category['machines'] as $machine) : ?>
                        <?php get_template_part('templates/parts/card-product', null, [
                            'product' => to_card_product($machine, $key),
                        ]); ?>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endforeach; ?>

    </div>
</section>
