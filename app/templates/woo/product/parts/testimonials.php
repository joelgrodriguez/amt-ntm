<?php
/**
 * Machine Product — Testimonial Carousel
 *
 * Auto-advancing testimonial slider with dot navigation.
 * Replaces the static social-proof grid with a more engaging carousel.
 *
 * @package Standard
 * @var array{machine: array} $args
 *
 * @usage Single Machine Product (single-machine.php)
 * @see js/modules/SocialProof.js - Slider functionality
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine      = $args['machine'] ?? [];
$testimonials = $machine['testimonials'] ?? [];

// Placeholder testimonials when machine data is empty
if (empty($testimonials)) {
    $testimonials = [
        [
            'quote'    => __('We paid off our machine in eight months. Now every panel we roll is pure profit — I wish we\'d made the switch years ago.', 'standard'),
            'name'     => 'Mike Thompson',
            'company'  => 'Thompson Roofing & Sheet Metal',
            'location' => __('Denver, CO', 'standard'),
        ],
        [
            'quote'    => __('Having our own rollformer changed everything. We quote jobs same-day, control our own schedule, and win more bids than ever.', 'standard'),
            'name'     => 'Sarah Mitchell',
            'company'  => 'Mitchell Metal Works',
            'location' => __('Austin, TX', 'standard'),
        ],
        [
            'quote'    => __('The training and support from NTM is what sealed the deal. They didn\'t just sell us a machine — they set us up for success from day one.', 'standard'),
            'name'     => 'James Carter',
            'company'  => 'Peak Roofing Solutions',
            'location' => __('Salt Lake City, UT', 'standard'),
        ],
        [
            'quote'    => __('Our crews love not waiting on panel deliveries anymore. We show up, roll what we need on-site, and get the job done faster.', 'standard'),
            'name'     => 'David Chen',
            'company'  => 'Precision Metal Roofing',
            'location' => __('Portland, OR', 'standard'),
        ],
        [
            'quote'    => __('I started with one machine and a pickup truck. Three years later I\'ve got a full crew and more work than I can handle. Best investment I ever made.', 'standard'),
            'name'     => 'Carlos Mendez',
            'company'  => 'Mendez Roofing LLC',
            'location' => __('Phoenix, AZ', 'standard'),
        ],
    ];
}

$content = [
    'title'     => __('What Our Customers Say', 'standard'),
    'nav_label' => __('Testimonial navigation', 'standard'),
];
?>

<section class="social-proof section bg-slate-900" aria-labelledby="testimonials-title">
    <div class="container">
        <div class="section-content">

            <div class="section-header">
                <p class="section-eyebrow text-secondary">
                    <?php esc_html_e('Trusted by Contractors Nationwide', 'standard'); ?>
                </p>
                <div class="section-divider-center"></div>
                <h2 id="testimonials-title" class="section-title text-white">
                    <?php echo esc_html($content['title']); ?>
                </h2>
            </div>

            <div class="social-proof__slider relative max-w-4xl mx-auto text-center grid justify-center gap-8 lg:gap-10">
                <div class="social-proof__track">
                    <?php foreach ($testimonials as $index => $testimonial) : ?>
                        <blockquote
                            class="social-proof__slide grid gap-8 <?php echo $index === 0 ? 'block' : 'hidden'; ?>"
                            data-index="<?php echo esc_attr((string) $index); ?>"
                        >
                            <?php icon('quote', ['class' => 'w-10 h-10 text-secondary mx-auto md:w-12 md:h-12']); ?>

                            <p class="text-xl text-white font-medium leading-relaxed md:text-2xl lg:text-3xl">
                                &ldquo;<?php echo esc_html($testimonial['quote']); ?>&rdquo;
                            </p>

                            <footer class="text-slate-300">
                                <cite class="not-italic">
                                    <span class="block text-lg font-semibold text-white">
                                        <?php echo esc_html($testimonial['name']); ?>
                                    </span>
                                    <span class="text-sm">
                                        <?php echo esc_html($testimonial['company']); ?>
                                    </span>
                                    <?php if (!empty($testimonial['location'])) : ?>
                                        <span class="block text-xs text-slate-400 mt-1">
                                            <?php echo esc_html($testimonial['location']); ?>
                                        </span>
                                    <?php endif; ?>
                                </cite>
                            </footer>
                        </blockquote>
                    <?php endforeach; ?>
                </div>

                <nav class="flex justify-center gap-2" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
                    <?php foreach ($testimonials as $index => $testimonial) : ?>
                        <button
                            type="button"
                            class="social-proof__dot h-3 rounded-full border-none cursor-pointer transition-all duration-200 hover:bg-slate-400 focus-visible:outline-2 focus-visible:outline-secondary focus-visible:outline-offset-2 <?php echo $index === 0 ? 'bg-secondary w-8' : 'bg-slate-600 w-3'; ?>"
                            data-index="<?php echo esc_attr((string) $index); ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('View testimonial %d', 'standard'), $index + 1)); ?>"
                        ></button>
                    <?php endforeach; ?>
                </nav>
            </div>

        </div>
    </div>
</section>
