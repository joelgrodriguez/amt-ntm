<?php
/**
 * Hero Slide Template Part
 *
 * Renders a single slide in the hero slider.
 * Expects $args array with machine data.
 *
 * @package Standard
 */

declare(strict_types=1);

// Get machine data from args
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
$finance_apr      = $machine['finance_apr'] ?? '';
$finance_months   = $machine['finance_months'] ?? '';
$finance_url      = $machine['finance_url'] ?? '#';
$learn_more_url   = $machine['learn_more_url'] ?? '#';
$is_first         = $index === 0;
?>

<div
    class="hero-slider__slide"
    data-slide-index="<?php echo esc_attr((string) $index); ?>"
    aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
>
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
        <img
            class="hero-slider__media hero-slider__image"
            src="<?php echo esc_url($background_image); ?>"
            alt="<?php echo esc_attr($title); ?>"
            loading="<?php echo $is_first ? 'eager' : 'lazy'; ?>"
        >
    <?php endif; ?>

    <div class="hero-slider__overlay"></div>

    <div class="hero-slider__content">
        <?php if ($category) : ?>
            <span class="hero-slider__category font-mono">
                <?php echo esc_html($category); ?>
            </span>
        <?php endif; ?>

        <?php if ($title) : ?>
            <h2 class="hero-slider__title font-mono">
                <?php echo esc_html($title); ?>
            </h2>
        <?php endif; ?>

        <?php if ($slogan) : ?>
            <p class="hero-slider__slogan">
                <?php echo esc_html($slogan); ?>
            </p>
        <?php endif; ?>

        <?php if ($finance_apr && $finance_months) : ?>
            <div class="hero-slider__finance">
                <span class="hero-slider__finance-prefix"><?php esc_html_e('As low as', 'standard'); ?></span>
                <div class="hero-slider__finance-details">
                    <div class="hero-slider__finance-item">
                        <span class="hero-slider__finance-value"><?php echo esc_html($finance_apr); ?></span>
                        <span class="hero-slider__finance-label"><?php esc_html_e('APR', 'standard'); ?></span>
                    </div>
                    <span class="hero-slider__finance-divider"></span>
                    <div class="hero-slider__finance-item">
                        <span class="hero-slider__finance-value"><?php echo esc_html($finance_months); ?></span>
                        <span class="hero-slider__finance-label"><?php esc_html_e('mos', 'standard'); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="hero-slider__cta">
            <a href="<?php echo esc_url($finance_url); ?>" class="btn btn-sm btn-light">
                <?php esc_html_e('Build & Finance', 'standard'); ?>
            </a>
            <a href="<?php echo esc_url($learn_more_url); ?>" class="btn btn-sm btn-outline-light">
                <?php esc_html_e('Learn More', 'standard'); ?>
            </a>
        </div>
    </div>
</div>
