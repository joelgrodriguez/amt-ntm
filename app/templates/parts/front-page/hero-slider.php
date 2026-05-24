<?php
/**
 * Hero Slider Template Part
 *
 * Full-viewport hero slider for the front page.
 * Displays featured machines with background images/videos.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Machines\get_hero_slides;

$content = [
    'section_label' => __('Featured', 'standard'),
    'prev_label'    => __('Previous slide', 'standard'),
    'next_label'    => __('Next slide', 'standard'),
    'nav_label'     => __('Slide navigation', 'standard'),
    'go_to_slide'   => __('Go to slide %d', 'standard'),
];
$slides = get_hero_slides();

if (empty($slides)) {
    return;
}

$total_slides = count($slides);
$first_slide = $slides[0] ?? null;
?>

<?php if ($first_slide && !empty($first_slide['background_image'])) : ?>
<link rel="preload" as="image" href="<?php echo esc_url($first_slide['background_image']); ?>" fetchpriority="high">
<?php endif; ?>

<section class="hero-slider" aria-label="<?php echo esc_attr($content['section_label']); ?>">
    <div class="hero-slider__track">
        <?php foreach ($slides as $index => $slide) : ?>
            <?php
            get_template_part('templates/parts/front-page/hero-slide', null, [
                'machine' => $slide,
                'index'   => $index,
            ]);
            ?>
        <?php endforeach; ?>
    </div>
    <button
        type="button"
        class="hero-slider__nav hero-slider__nav--prev"
        aria-label="<?php echo esc_attr($content['prev_label']); ?>"
    >
        <?php icon('arrow-left', ['class' => 'hero-slider__nav-icon']); ?>
    </button>

    <button
        type="button"
        class="hero-slider__nav hero-slider__nav--next"
        aria-label="<?php echo esc_attr($content['next_label']); ?>"
    >
        <?php icon('arrow-right', ['class' => 'hero-slider__nav-icon']); ?>
    </button>
    <div class="hero-slider__chrome">
        <div class="hero-slider__progress" role="tablist" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
            <?php for ($i = 0; $i < $total_slides; $i++) : ?>
                <button
                    type="button"
                    class="hero-slider__segment <?php echo $i === 0 ? 'hero-slider__segment--active' : ''; ?>"
                    aria-label="<?php echo esc_attr(sprintf($content['go_to_slide'], $i + 1)); ?>"
                    aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                    role="tab"
                    data-slide="<?php echo esc_attr((string) $i); ?>"
                ></button>
            <?php endfor; ?>
        </div>

    </div>

</section>
