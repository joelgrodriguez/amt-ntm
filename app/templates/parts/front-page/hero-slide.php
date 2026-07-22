<?php
/**
 * Hero Slide Template Part
 *
 * Renders a single front-page hero slide with copy and media.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? [];
$index   = $args['index'] ?? 0;

if (empty($machine)) {
    return;
}

$id               = $machine['id'] ?? '';
$category         = $machine['category'] ?? '';
$title            = $machine['title'] ?? '';
$slogan           = $machine['slogan'] ?? '';
$background_image = $machine['background_image'] ?? '';
$background_video = $machine['background_video'] ?? '';
$learn_more_url   = $machine['learn_more_url'] ?? '#';
$cta_label        = $machine['cta_label'] ?? __('View Machine', 'standard');
$focal_point      = $machine['focal_point'] ?? '';
$is_first         = $index === 0;

$photo_style = $focal_point !== '' ? sprintf('--hero-pos: %s;', $focal_point) : '';
?>

<div
    id="hero-slide-<?php echo esc_attr((string) $index); ?>"
    class="hero-slider__slide"
    data-slide-index="<?php echo esc_attr((string) $index); ?>"
    aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
    <?php // Hidden slides carry a focusable CTA link; `inert` keeps them out
          // of the tab order until HeroSlider.js activates the slide. ?>
    <?php if (!$is_first) : ?>inert<?php endif; ?>
>
    <div class="hero__photo"<?php if ($photo_style) : ?> style="<?php echo esc_attr($photo_style); ?>"<?php endif; ?>>
        <?php if ($is_first) : ?>
            <?php if ($background_video) : ?>
                <video
                    class="hero__media hero__media--video"
                    autoplay
                    muted
                    loop
                    playsinline
                    poster="<?php echo esc_url($background_image); ?>"
                >
                    <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
                </video>
            <?php endif; ?>

            <?php if ($background_image) : ?>
                <?php \Standard\Images\responsive_image($background_image, $title, 'full', [
                    'class'         => 'hero__media',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                ]); ?>
            <?php endif; ?>
        <?php else : ?>
            <!-- Deferred media: full responsive markup (srcset/sizes) rendered
                 server-side but parked in an inert template so nothing loads
                 until HeroSlider.js hydrates the slide. -->
            <template class="hero-slide__media-template">
                <?php if ($background_video) : ?>
                    <video
                        class="hero__media hero__media--video"
                        autoplay
                        muted
                        loop
                        playsinline
                        poster="<?php echo esc_url($background_image); ?>"
                    >
                        <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
                    </video>
                <?php endif; ?>

                <?php if ($background_image) : ?>
                    <?php \Standard\Images\responsive_image($background_image, $title, 'full', [
                        'class' => 'hero__media',
                    ]); ?>
                <?php endif; ?>
            </template>
        <?php endif; ?>

        <div class="hero-overlay"></div>
        <div class="hero-overlay__grain"></div>

        <div class="hero__content">
            <div class="container hero__content-inner">
                <?php if ($category) : ?>
                    <span class="hero__eyebrow">
                        <span class="hero__eyebrow-dot" aria-hidden="true"></span>
                        <span><?php echo esc_html($category); ?></span>
                    </span>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h2 class="hero__title">
                        <?php echo wp_kses($title, ['br' => ['class' => []]]); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($slogan) : ?>
                    <p class="hero__slogan">
                        <?php echo esc_html($slogan); ?>
                    </p>
                <?php endif; ?>

                <div class="hero__cta">
                    <a href="<?php echo esc_url($learn_more_url); ?>" class="btn btn-primary">
                        <?php echo esc_html($cta_label); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
