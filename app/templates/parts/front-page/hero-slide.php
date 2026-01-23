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

$content = [
    'as_low_as'     => __('As low as', 'standard'),
    'apr_label'     => __('APR', 'standard'),
    'months_label'  => __('mos', 'standard'),
    'cta_finance'   => __('Build & Finance', 'standard'),
    'cta_learn'     => __('Learn More', 'standard'),
];

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
            fetchpriority="<?php echo $is_first ? 'high' : 'auto'; ?>"
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
                <span class="hero-slider__finance-prefix"><?php echo esc_html($content['as_low_as']); ?></span>
                <div class="hero-slider__finance-details">
                    <div class="hero-slider__finance-item">
                        <span class="hero-slider__finance-value"><?php echo esc_html($finance_apr); ?></span>
                        <span class="hero-slider__finance-label"><?php echo esc_html($content['apr_label']); ?></span>
                    </div>
                    <span class="hero-slider__finance-divider"></span>
                    <div class="hero-slider__finance-item">
                        <span class="hero-slider__finance-value"><?php echo esc_html($finance_months); ?></span>
                        <span class="hero-slider__finance-label"><?php echo esc_html($content['months_label']); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="hero-slider__cta">
            <a href="<?php echo esc_url($finance_url); ?>" class="btn btn-sm btn-light">
                <?php echo esc_html($content['cta_finance']); ?>
            </a>
            <a href="<?php echo esc_url($learn_more_url); ?>" class="btn btn-sm btn-outline-light">
                <?php echo esc_html($content['cta_learn']); ?>
            </a>
        </div>
    </div>
</div>
