<?php
/**
 * Machine Product — Hero Section
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];

if (!$product) {
    return;
}

$hero    = $machine['hero'] ?? [];
$finance = $machine['finance'] ?? [];

$headline = !empty($hero['headline']) ? $hero['headline'] : $product->get_name();
$subtitle = !empty($hero['subtitle']) ? $hero['subtitle'] : $product->get_short_description();
$image    = \Standard\Url\canonical($hero['hero_image'] ?? $hero['image'] ?? '');
$video    = $hero['video'] ?? '';
$has_mp4  = $video !== '' && preg_match('/\.mp4($|\?)/i', $video);

$price_display = !empty($finance['price_range']) ? $finance['price_range'] : $product->get_price_html();
$machine_name  = $product->get_name();
$machine_short = $machine_name;
if (
    function_exists('Standard\\MachinesData\\get_all_machines')
    && function_exists('Standard\\MachineProductData\\get_slug_aliases')
) {
    $aliases   = \Standard\MachineProductData\get_slug_aliases();
    $data_slug = $aliases[$product->get_slug()] ?? $product->get_slug();
    foreach (\Standard\MachinesData\get_all_machines(true) as $m) {
        if (($m['slug'] ?? '') === $data_slug) {
            $machine_short = $m['name'] ?? $machine_name;
            break;
        }
    }
}
?>

<section id="machine-hero" class="hero hero--machine-product" aria-labelledby="machine-hero-title">
    <div class="hero__photo">
        <?php if ($has_mp4) : ?>
            <video
                class="hero__media hero__media--video"
                muted
                loop
                playsinline
                preload="none"
                data-hero-video-src="<?php echo esc_url($video); ?>"
                data-hero-video-type="video/mp4"
                <?php if (!empty($image)) : ?>poster="<?php echo esc_url($image); ?>"<?php endif; ?>
            ></video>
        <?php elseif (!empty($image)) : ?>
            <?php \Standard\Images\responsive_image($image, $headline, 'full', [
                'class'         => 'hero__media',
                'loading'       => 'eager',
                'fetchpriority' => 'high',
            ]); ?>
        <?php endif; ?>

        <div class="hero-overlay"></div>
        <div class="hero-overlay__grain"></div>

        <p class="hero__watermark hero__watermark--top-right">
    <span class="md:hidden"><?php echo esc_html($machine_short); ?></span>
    <span class="hidden md:inline"><?php echo esc_html($machine_name); ?></span>
</p>

        <div class="hero__content">
            <div class="container hero__content-inner">
                <h1 id="machine-hero-title" class="hero__title">
                    <?php
                    echo wp_kses($headline, ['br' => ['class' => true]]);
                    ?>
                </h1>
                <?php if (!empty($subtitle)) : ?>
                    <p class="hero__slogan"><?php echo wp_kses_post($subtitle); ?></p>
                <?php endif; ?>
                <?php if (!empty($price_display)) : ?>
                    <p class="hero__meta">
                        <?php esc_html_e('Starting at', 'standard'); ?>
                        <span class="hero__meta-value"><?php echo wp_kses_post($price_display); ?></span>
                        <?php if (!empty($finance['note'])) : ?>
                            <?php
                            // Linkify "Trailer sold separately" to /machines/trailer/.
                            // The helper returns already-escaped HTML with only a
                            // controlled <a>; wp_kses is a belt-and-suspenders allowlist.
                            $note_html = function_exists('Standard\\TrailerData\\linkify_note')
                                ? \Standard\TrailerData\linkify_note((string) $finance['note'])
                                : esc_html((string) $finance['note']);
                            ?>
                            <span class="mt-2 max-w-md text-xs normal-case tracking-normal text-blue-200"><?php echo wp_kses($note_html, ['a' => ['href' => true, 'class' => true]]); ?></span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                <div class="hero__cta">
                    <?php $hero_configurator_url = \Standard\Woo\Catalog\get_configurator_url($product->get_slug()); ?>
                    <a href="<?php echo esc_url($hero_configurator_url ?: \Standard\Url\internal('/contact/')); ?>" class="btn btn-primary"<?php echo $hero_configurator_url ? ' target="_blank" rel="noopener"' : ''; ?>>
                        <?php esc_html_e('Build & Quote', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                    <a href="#machine-breakdown" class="btn btn-outline-light">
                        <?php esc_html_e('See Specs', 'standard'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
