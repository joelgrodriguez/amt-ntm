<?php
/**
 * Shared Template Part — Brand Statement
 *
 * Centered narrative block by default (bg-blue-50). When an `image`
 * arg is supplied, switches to a two-column layout: 16:9 image on the
 * left, copy on the right, left-aligned. Used on /machines,
 * /roof-wall-panel-machines/, /seamless-gutter-machines/.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $title       Headline (H2).
 *     @type string $text        Body paragraph.
 *     @type string $section_id  ID for aria-labelledby.
 *     @type string $image       Optional. Image URL. Activates two-column layout.
 *     @type string $image_alt   Optional. Alt text for the image.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$title      = $args['title'] ?? '';
$text       = $args['text'] ?? '';
$section_id = $args['section_id'] ?? 'brand-statement-title';
$image      = $args['image'] ?? '';
$image_alt  = $args['image_alt'] ?? '';

if ($title === '' || $text === '') {
    return;
}
?>

<section class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <?php if ($image !== '') : ?>
        <div class="container">
            <div class="grid gap-10 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">
                <div>
                    <img
                        src="<?php echo esc_url($image); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        class="w-full aspect-video object-cover"
                        loading="lazy"
                    >
                </div>
                <div class="grid gap-6">
                    <div class="section-divider"></div>
                    <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title">
                        <?php echo wp_kses($title, ['br' => ['class' => true]]); ?>
                    </h2>
                    <p class="section-subtitle">
                        <?php echo wp_kses($text, ['br' => ['class' => true]]); ?>
                    </p>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="container grid gap-6 max-w-3xl mx-auto text-center">
            <div class="section-divider-center"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title">
                <?php echo wp_kses($title, ['br' => ['class' => true]]); ?>
            </h2>
            <p class="section-subtitle max-w-2xl mx-auto">
                <?php echo wp_kses($text, ['br' => ['class' => true]]); ?>
            </p>
        </div>
    <?php endif; ?>
</section>
