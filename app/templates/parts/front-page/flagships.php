<?php
/**
 * Flagships Section — Front Page
 *
 * Two stacked product bands, alternating image-left / image-right, calling
 * out the roofing flagship (SSQ3) and the gutter flagship (MACH II Combo).
 * Replaces the earlier configurator promo section.
 *
 * Why this composition: the rest of the page is largely sections that
 * describe the *funnel* (router, tools, three-step, why-own, social-proof).
 * This is the page's second moment of full product gravity after the hero
 * slider — real machines, real specs, equal weight to "two product lines."
 *
 * Each band pulls live data from `data/machines/*.php` via
 * `get_machine_product_data()`. The roof slot points its product page
 * link at the SSQ II permalink for now, matching the same temporary
 * convention used by the hero slider (see `inc/machines.php`); swap to
 * the SSQ3 permalink when the SSQ3 WooCommerce product ships.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;

$flagships = [
    [
        'data_slug'    => 'ssq3-multipro',
        'model_label'  => 'SSQ3',
        'product_slug' => 'ssq-roof-panel-machine', // SSQ II permalink for now; swap to ssq3 when WC product ships.
        'image_align'  => 'left',
        'cta_specs'    => __('See full specs', 'standard'),
    ],
    [
        'data_slug'    => 'mach-ii-combo-gutter',
        'model_label'  => 'MACH II Combo',
        'product_slug' => 'mach-ii-5-6-gutter-machine',
        'image_align'  => 'right',
        'cta_specs'    => __('See full specs', 'standard'),
    ],
];

$rendered_count = 0;
?>

<section class="bg-white" aria-labelledby="flagships-title">
    <h2 id="flagships-title" class="sr-only">
        <?php esc_html_e('Our flagship machines', 'standard'); ?>
    </h2>

    <?php foreach ($flagships as $i => $flagship) :
        $data = get_machine_product_data($flagship['data_slug']);
        if (!$data) {
            continue;
        }

        $category   = $data['category'] ?? '';
        $slogan     = $data['hero']['headline'] ?? $data['slogan'] ?? '';
        $subtitle   = $data['hero']['subtitle'] ?? '';
        $hero_image = $data['hero']['image'] ?? $data['hero']['hero_image'] ?? '';
        $stats      = array_slice($data['stats'] ?? [], 0, 3);

        $product_post = get_page_by_path($flagship['product_slug'], OBJECT, 'product');
        $specs_url    = ($product_post && $product_post->post_status === 'publish')
            ? get_permalink($product_post)
            : '#';
        $configure_url = \Standard\Url\with_query('/build-finance/', ['machine' => $flagship['data_slug']]);

        $image_first_on_lg = $flagship['image_align'] === 'left';
        $rendered_count++;
        $is_first = $rendered_count === 1;
    ?>
    <div class="<?php echo $is_first ? '' : 'border-t border-blue-200'; ?>">
        <div class="container">
            <div class="grid gap-10 py-16 lg:grid-cols-2 lg:gap-16 lg:py-24 lg:items-center">

                <!-- Image cell -->
                <div class="<?php echo $image_first_on_lg ? 'lg:order-1' : 'lg:order-2'; ?>">
                    <?php if ($hero_image) : ?>
                        <?php \Standard\Images\responsive_image($hero_image, $data['hero']['headline'] ?? '', 'large', [
                            'class'   => 'w-full h-auto block',
                            'loading' => 'lazy',
                        ]); ?>
                    <?php endif; ?>
                </div>

                <!-- Content cell -->
                <div class="grid gap-6 lg:gap-8 content-start <?php echo $image_first_on_lg ? 'lg:order-2' : 'lg:order-1'; ?>">

                    <!-- Eyebrow: red dot + MODEL line, mono uppercase -->
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                        <p class="font-mono uppercase tracking-wider text-xs text-blue-700">
                            <?php echo esc_html(sprintf(__('Model · %s', 'standard'), $flagship['model_label'])); ?>
                        </p>
                    </div>

                    <!-- Headline -->
                    <h3 class="font-sans font-medium text-blue-900 tracking-tight leading-tight text-3xl md:text-4xl lg:text-5xl">
                        <?php echo esc_html($slogan); ?>
                    </h3>

                    <?php if ($subtitle) : ?>
                        <p class="font-sans text-blue-600 text-base lg:text-lg max-w-xl leading-relaxed">
                            <?php echo esc_html($subtitle); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Spec strip: 3 mono spec cells, hairline dividers -->
                    <?php if (!empty($stats)) : ?>
                        <dl class="grid grid-cols-3 border-y border-blue-200" aria-label="<?php echo esc_attr(sprintf(__('%s key specs', 'standard'), $flagship['model_label'])); ?>">
                            <?php foreach ($stats as $j => $stat) : ?>
                                <div class="py-4 px-4 <?php echo $j > 0 ? 'border-l border-blue-200' : ''; ?> <?php echo $j === 0 ? 'pl-0' : ''; ?>">
                                    <dt class="font-mono uppercase tracking-wider text-xs text-blue-400 mb-1">
                                        <?php echo esc_html($stat['label']); ?>
                                    </dt>
                                    <dd class="font-mono font-medium text-blue-900 text-xl lg:text-2xl">
                                        <?php echo esc_html($stat['value']); ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>

                    <!-- CTA pair -->
                    <div class="flex flex-wrap items-center gap-4">
                        <a
                            href="<?php echo esc_url($specs_url); ?>"
                            class="btn btn-primary"
                        >
                            <?php echo esc_html($flagship['cta_specs']); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                        <a
                            href="<?php echo esc_url($configure_url); ?>"
                            class="btn btn-secondary"
                        >
                            <?php esc_html_e('Configure & quote', 'standard'); ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
</section>
