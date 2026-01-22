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

use function Standard\Machines\get_featured_machines;

$content = [
    'section_label' => __('Featured machines', 'standard'),
    'prev_label'    => __('Previous slide', 'standard'),
    'next_label'    => __('Next slide', 'standard'),
    'nav_label'     => __('Slide navigation', 'standard'),
    'go_to_slide'   => __('Go to slide %d', 'standard'),
];

// Get featured machines
$machines = get_featured_machines();

if (empty($machines)) {
    return;
}

$total_slides = count($machines);
?>

<section class="hero-slider" aria-label="<?php echo esc_attr($content['section_label']); ?>">

    <!-- Slides Track -->
    <div class="hero-slider__track">
        <?php foreach ($machines as $index => $machine) : ?>
            <?php
            get_template_part('templates/parts/front-page/hero-slide', null, [
                'machine' => $machine,
                'index'   => $index,
            ]);
            ?>
        <?php endforeach; ?>
    </div>

    <!-- Navigation Arrows -->
    <button
        type="button"
        class="hero-slider__nav hero-slider__nav--prev"
        aria-label="<?php echo esc_attr($content['prev_label']); ?>"
    >
        <?php icon('arrow--left', ['class' => 'hero-slider__nav-icon']); ?>
    </button>

    <button
        type="button"
        class="hero-slider__nav hero-slider__nav--next"
        aria-label="<?php echo esc_attr($content['next_label']); ?>"
    >
        <?php icon('arrow--right', ['class' => 'hero-slider__nav-icon']); ?>
    </button>

    <!-- Segmented Progress Bar -->
    <div class="hero-slider__progress" role="tablist" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
        <?php for ($i = 0; $i < $total_slides; $i++) : ?>
            <button
                type="button"
                class="hero-slider__segment <?php echo $i === 0 ? 'hero-slider__segment--active' : ''; ?>"
                aria-label="<?php echo esc_attr(sprintf($content['go_to_slide'], $i + 1)); ?>"
                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                role="tab"
                data-slide="<?php echo esc_attr((string) $i); ?>"
            >
                <span class="hero-slider__segment-fill"></span>
            </button>
        <?php endfor; ?>
    </div>

</section>
