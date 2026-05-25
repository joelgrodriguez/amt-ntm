<?php
/**
 * Machines Page — SSQ3 Flagship Call-Out
 *
 * Surfaces the SSQ3 MultiPro between the full lineup grid and the
 * comparison table. Buyer flow: scan the whole lineup -> meet the
 * flagship as the "if you're still deciding, here's the one" moment
 * -> step into the comparison table that crowns SSQ3 anyway.
 *
 * Deliberately *not* the typographic marquee used on
 * /roof-wall-panel-machines/. This is the proof-points variant:
 * image left, badge + descriptor + name + price + highlight bullets
 * + single CTA right. Same machine, different beat.
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

$flagship = null;
foreach (get_machine_categories() as $category) {
    foreach ($category['machines'] ?? [] as $machine) {
        if (($machine['slug'] ?? '') === 'ssq3-multipro') {
            $flagship = $machine;
            break 2;
        }
    }
}

if (!$flagship) {
    return;
}

$product_url = \Standard\Url\internal($flagship['url']);
?>

<section
    class="section bg-blue-50 reveal"
    aria-labelledby="machines-flagship-title"
>
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php esc_html_e('The Flagship', 'standard'); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="machines-flagship-title" class="section-title">
                <?php esc_html_e('Still Deciding? Start Here.', 'standard'); ?>
            </h2>
        </div>

        <div class="grid bg-blue-900 text-white overflow-hidden lg:grid-cols-[5fr_4fr] lg:items-stretch">

            <div class="relative aspect-[4/3] bg-blue-800 lg:aspect-auto lg:min-h-[480px]">
                <?php \Standard\Images\responsive_image($flagship['image'], $flagship['name'], 'large', [
                    'class' => 'absolute inset-0 w-full h-full object-contain p-6 lg:p-12',
                ]); ?>
            </div>

            <div class="grid content-center gap-6 p-8 lg:gap-7 lg:p-12 xl:p-16">

                <div class="flex flex-wrap items-center gap-3">
                    <?php if (!empty($flagship['badge'])) : ?>
                        <span class="badge badge-emphasis">
                            <?php echo esc_html($flagship['badge']); ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($flagship['descriptor'])) : ?>
                        <span class="font-mono text-xs uppercase tracking-[0.15em] text-blue-300">
                            <?php echo esc_html($flagship['descriptor']); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h3 class="text-4xl font-medium tracking-tight text-white lg:text-5xl">
                    <a href="<?php echo esc_url($product_url); ?>" class="no-underline text-inherit hover:text-blue-200 transition-colors">
                        <?php echo esc_html($flagship['name']); ?>
                    </a>
                </h3>

                <?php if (!empty($flagship['price'])) : ?>
                    <div class="grid gap-1">
                        <p class="text-2xl font-medium text-white">
                            <?php echo esc_html($flagship['price']); ?>
                        </p>
                        <p class="font-mono text-xs text-blue-300 uppercase tracking-wider">
                            <?php esc_html_e('Starting at', 'standard'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($flagship['highlights'])) : ?>
                    <ul class="grid gap-3 text-blue-200">
                        <?php foreach ($flagship['highlights'] as $highlight) : ?>
                            <li class="flex gap-3">
                                <span aria-hidden="true" class="font-mono text-xs text-blue-500 mt-1.5 shrink-0">&rarr;</span>
                                <span><?php echo esc_html($highlight); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="<?php echo esc_url($product_url); ?>" class="btn btn-emphasis">
                        <?php esc_html_e('Explore the SSQ3', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>
