<?php
/**
 * Default Machine — Features, Specs, Warranties accordion
 *
 * Reads ACF fields off the product post. Hides empty sections; hides
 * the whole section if nothing populates.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
if (!$product instanceof \WC_Product) {
    return;
}

$post_id = $product->get_id();

$acf_get = function (string $key) use ($post_id) {
    return function_exists('get_field') ? get_field($key, $post_id) : null;
};

$standard_features = $acf_get('standard_features');
$specs_html        = $acf_get('specs');
$specs_img         = $acf_get('specs_img');
$footprints        = $acf_get('footprint');
$warranties        = $acf_get('warranties');
$manuals           = $acf_get('manuals');
$literature        = $acf_get('literature');

$sections = [];

if (!empty($standard_features)) {
    ob_start();
    if (is_array($standard_features)) :
        ?>
        <ul class="spec-list text-blue-700">
            <?php foreach ($standard_features as $feature) : ?>
                <li><?php echo esc_html(is_string($feature) ? $feature : ''); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <div class="prose prose-sm text-blue-700 max-w-none"><?php echo wp_kses_post($standard_features); ?></div>
    <?php endif;
    $sections[__('Standard Features', 'standard')] = ob_get_clean();
}

if (!empty($specs_html) || !empty($footprints)) {
    ob_start(); ?>
    <?php if (!empty($specs_html)) : ?>
        <div class="prose prose-sm text-blue-700 max-w-none">
            <?php echo wp_kses_post($specs_html); ?>
        </div>
    <?php endif; ?>

    <?php if (is_array($footprints) && !empty($footprints)) : ?>
        <div class="mt-6 grid gap-4">
            <?php foreach ($footprints as $footprint) :
                $fp_id    = is_object($footprint) ? ($footprint->ID ?? 0) : (int) $footprint;
                if (!$fp_id) continue;
                $fp_url   = get_permalink($fp_id);
                $fp_title = get_the_title($fp_id);
                $fp_image = get_the_post_thumbnail_url($fp_id, 'large');
            ?>
                <a href="<?php echo esc_url($fp_url); ?>" class="block border border-blue-200 bg-white hover:border-blue-400 transition-all">
                    <?php if ($fp_image) : ?>
                        <img src="<?php echo esc_url($fp_image); ?>" alt="<?php echo esc_attr($fp_title); ?>" class="w-full h-auto" loading="lazy">
                    <?php endif; ?>
                    <p class="px-4 py-3 text-sm text-blue-700 font-medium"><?php echo esc_html($fp_title); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif;
    $sections[__('Specifications', 'standard')] = ob_get_clean();
}

if (!empty($warranties)) {
    ob_start(); ?>
    <div class="prose prose-sm text-blue-700 max-w-none">
        <?php echo wp_kses_post($warranties); ?>
    </div>
    <?php $sections[__('Warranty & Patents', 'standard')] = ob_get_clean();
}

if (is_array($manuals) && !empty($manuals) || is_array($literature) && !empty($literature)) {
    ob_start();

    $render_resource_list = function (array $items): void {
        foreach ($items as $item) {
            $item_id = is_object($item) ? ($item->ID ?? 0) : (int) $item;
            if (!$item_id) continue;
            $url   = get_permalink($item_id);
            $title = get_the_title($item_id);
            ?>
            <li>
                <a href="<?php echo esc_url($url); ?>" class="inline-flex items-center gap-2 text-sm text-blue-700 hover:text-blue-500 transition-colors">
                    <?php icon('arrow-right', ['class' => 'w-4 h-4 shrink-0']); ?>
                    <span><?php echo esc_html($title); ?></span>
                </a>
            </li>
            <?php
        }
    };
    ?>
    <div class="grid sm:grid-cols-2 gap-6">
        <?php if (is_array($manuals) && !empty($manuals)) : ?>
            <div>
                <h4 class="text-xs font-mono uppercase tracking-wider text-blue-500 mb-3"><?php esc_html_e('Manuals', 'standard'); ?></h4>
                <ul class="grid gap-2"><?php $render_resource_list($manuals); ?></ul>
            </div>
        <?php endif; ?>
        <?php if (is_array($literature) && !empty($literature)) : ?>
            <div>
                <h4 class="text-xs font-mono uppercase tracking-wider text-blue-500 mb-3"><?php esc_html_e('Literature', 'standard'); ?></h4>
                <ul class="grid gap-2"><?php $render_resource_list($literature); ?></ul>
            </div>
        <?php endif; ?>
    </div>
    <?php $sections[__('Resources', 'standard')] = ob_get_clean();
}

if (empty($sections)) {
    return;
}
?>

<section id="machine-specs" class="section bg-blue-50" aria-labelledby="default-specs-title">
    <div class="container section-content">

        <?php
        // Only render the side image column when there's a dedicated specs_img.
        // Otherwise the featured photo would just repeat what's already in the
        // fold gallery.
        $side_image_url = '';
        $side_image_alt = $product->get_name();
        if (!empty($specs_img)) {
            $side_image_url = is_array($specs_img)
                ? ($specs_img['url'] ?? '')
                : (is_numeric($specs_img) ? wp_get_attachment_image_url((int) $specs_img, 'large') : (string) $specs_img);
            if (is_array($specs_img) && !empty($specs_img['alt'])) {
                $side_image_alt = $specs_img['alt'];
            }
        }
        $grid_class = $side_image_url
            ? 'grid lg:grid-cols-[5fr_4fr] gap-12 items-start'
            : 'max-w-3xl';
        ?>
        <div class="<?php echo esc_attr($grid_class); ?>">

            <div>
                <div class="section-header-left mb-12">
                    <p class="section-eyebrow"><?php esc_html_e('Details & Specifications', 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="default-specs-title" class="section-title"><?php esc_html_e('Built to spec', 'standard'); ?></h2>
                </div>

                <div data-accordion-group>
                    <?php $first = true; foreach ($sections as $title => $content) : ?>
                        <details class="accordion"<?php echo $first ? ' open' : ''; ?>>
                            <summary>
                                <?php echo esc_html($title); ?>
                                <span class="accordion__icon"><?php icon('chevron-down', ['class' => 'w-4 h-4']); ?></span>
                            </summary>
                            <div class="accordion__body">
                                <?php echo $content; // pre-escaped during build ?>
                            </div>
                        </details>
                    <?php $first = false; endforeach; ?>
                </div>
            </div>

            <?php if ($side_image_url) : ?>
                <div class="hidden lg:block sticky top-24">
                    <div class="bg-white border border-blue-200 overflow-hidden">
                        <img src="<?php echo esc_url($side_image_url); ?>" alt="<?php echo esc_attr($side_image_alt); ?>" class="w-full h-auto" loading="lazy">
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>
