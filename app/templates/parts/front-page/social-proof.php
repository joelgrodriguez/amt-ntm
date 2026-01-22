<?php
/**
 * Social Proof Section Template Part
 *
 * Testimonial slider showcasing customer quotes.
 * Auto-advances with navigation dots.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see js/modules/SocialProof.js - Slider functionality
 *
 * @todo Replace hardcoded testimonials with custom post type query
 */

declare(strict_types=1);

// Placeholder testimonials - will be replaced with CPT query
$testimonials = [
    [
        'quote'    => __('We paid off our machine in the first year. Now every panel we roll is pure profit.', 'standard'),
        'name'     => 'Mike Thompson',
        'position' => __('Owner', 'standard'),
        'company'  => 'Thompson Roofing Co.',
    ],
    [
        'quote'    => __('Having our own rollformer changed everything. We quote jobs same-day and win more bids than ever.', 'standard'),
        'name'     => 'Sarah Mitchell',
        'position' => __('President', 'standard'),
        'company'  => 'Mitchell Metal Works',
    ],
    [
        'quote'    => __('The training and support from NTM is incredible. They set us up for success from day one.', 'standard'),
        'name'     => 'James Rodriguez',
        'position' => __('Operations Manager', 'standard'),
        'company'  => 'Peak Roofing Solutions',
    ],
    [
        'quote'    => __('Best investment we ever made. Our crews love not having to wait on panel deliveries anymore.', 'standard'),
        'name'     => 'David Chen',
        'position' => __('Founder', 'standard'),
        'company'  => 'Precision Metal Roofing',
    ],
];

if (empty($testimonials)) {
    return;
}
?>

<section class="social-proof py-16 bg-slate-900 md:py-20 lg:py-24" aria-labelledby="social-proof-title">
    <div class="container">
        <h2 id="social-proof-title" class="sr-only">
            <?php esc_html_e('Customer Testimonials', 'standard'); ?>
        </h2>

        <div class="social-proof__slider relative max-w-4xl mx-auto text-center">
            <div class="social-proof__track">
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <blockquote
                        class="social-proof__slide <?php echo $index === 0 ? 'social-proof__slide--active' : ''; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                    >
                        <?php icon('quotes', ['class' => 'w-10 h-10 text-secondary mx-auto mb-6 md:w-12 md:h-12']); ?>

                        <p class="text-xl text-white font-medium leading-relaxed mb-8 md:text-2xl lg:text-3xl">
                            "<?php echo esc_html($testimonial['quote']); ?>"
                        </p>

                        <footer class="text-slate-300">
                            <cite class="not-italic">
                                <span class="block text-lg font-semibold text-white">
                                    <?php echo esc_html($testimonial['name']); ?>
                                </span>
                                <span class="text-sm">
                                    <?php echo esc_html($testimonial['position']); ?>, <?php echo esc_html($testimonial['company']); ?>
                                </span>
                            </cite>
                        </footer>
                    </blockquote>
                <?php endforeach; ?>
            </div>

            <nav class="flex justify-center gap-2 mt-10" aria-label="<?php esc_attr_e('Testimonial navigation', 'standard'); ?>">
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <button
                        type="button"
                        class="social-proof__dot <?php echo $index === 0 ? 'social-proof__dot--active' : ''; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                        aria-label="<?php echo esc_attr(sprintf(__('View testimonial %d', 'standard'), $index + 1)); ?>"
                    ></button>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>
</section>
