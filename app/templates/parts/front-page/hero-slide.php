<?php
/**
 * Hero Slide Template Part
 *
 * Renders a single slide in the hero slider. Composition:
 *   - photo region (full-bleed)
 *   - bottom-left content stack: eyebrow, title, slogan, CTA
 *
 * Stats are intentionally not shown here. The photography and the
 * headline are the hero; engineered/spec voice lives in the flagships
 * and three-step sections downstream.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'cta_machine' => __('View Machine', 'standard'),
];

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
$is_first         = $index === 0;
?>

<div
    class="hero-slider__slide"
    data-slide-index="<?php echo esc_attr((string) $index); ?>"
    aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
    <?php if (!$is_first) : ?>
        data-image-url="<?php echo esc_url($background_image); ?>"
        data-image-alt="<?php echo esc_attr($title); ?>"
        <?php if ($background_video) : ?>data-video-url="<?php echo esc_url($background_video); ?>"<?php endif; ?>
    <?php endif; ?>
>
    <!-- Photo region -->
    <div class="hero-slider__photo">
        <?php if ($is_first) : ?>
            <?php if ($background_video) : ?>
                <video
                    class="hero-slider__media hero-slider__video"
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
                    'class'         => 'hero-slider__media hero-slider__image',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                ]); ?>
            <?php endif; ?>
        <?php endif; ?>

        <div class="hero-overlay"></div>
        <div class="hero-overlay__grain"></div>

        <div class="hero-slider__content">
            <div class="container hero-slider__content-inner">
                <?php if ($category) : ?>
                    <span class="hero-slider__category">
                        <span class="hero-slider__category-dot" aria-hidden="true"></span>
                        <span><?php echo esc_html($category); ?></span>
                    </span>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h2 class="hero-slider__title">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($slogan) : ?>
                    <p class="hero-slider__slogan">
                        <?php echo esc_html($slogan); ?>
                    </p>
                <?php endif; ?>

                <div class="hero-slider__cta">
                    <a href="<?php echo esc_url($learn_more_url); ?>" class="btn btn-primary">
                        <?php echo esc_html($content['cta_machine']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
