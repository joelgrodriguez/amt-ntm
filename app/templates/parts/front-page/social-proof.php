<?php
/**
 * Social Proof Section Template Part
 *
 * "Field notebook" testimonial slider. Stripped of outer chrome
 * (used by video-section and three-step-plan) so the section reads
 * as a register shift from system to person. Mono eyebrow above a
 * left-aligned section title; square photo frame instead of the
 * SaaS-default round portrait; city is treated as a dateline.
 *
 * Manual navigation only (no autoplay). Dots are the only nav.
 *
 * Photo URLs point to the production CDN; portraits are public
 * marketing assets and won't move. Theme adds a preconnect hint
 * for that origin in inc/setup.php.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see js/modules/SocialProof.js - Slider functionality
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'   => __('From the field', 'standard'),
    'sr_title'  => __('Customer testimonials', 'standard'),
    'nav_label' => __('Testimonial navigation', 'standard'),
    'cta_label' => __('See all customer stories', 'standard'),
    'cta_url'   => 'https://newtechmachinery.com/search-results/?_sft_category=testimonials',
];

$cdn = 'https://newtechmachinery.com/wp-content/uploads/2025/06';

$testimonials = [
    [
        'quote'    => __('If you’re trying to jump into the metal business, contact New Tech Machinery. They’re the best! They’re going to give you the information that you need, and they’re going to help you grow your business.', 'standard'),
        'name'     => 'Danaik Garay',
        'company'  => 'Alsteel Metal Manufacturing',
        'location' => 'Fort Myers, Florida',
        'slug'     => 'Danaik-1',
    ],
    [
        'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and ease of switching out dies. If you do have a problem, there’s a sales team that you can call.', 'standard'),
        'name'     => 'Todd Andrews',
        'company'  => 'Classic Metals Inc.',
        'location' => 'Chester, South Carolina',
        'slug'     => 'Todd',
    ],
    [
        'quote'    => __('I chose New Tech Machinery over the competition because of how valued I felt as a customer. They truly valued me and appreciated me as a person, it wasn’t just another sale.', 'standard'),
        'name'     => 'Jim Averill',
        'company'  => 'Gunnison Sheet Metal',
        'location' => 'Gunnison, Colorado',
        'slug'     => 'Jim',
    ],
    [
        'quote'    => __('For me, New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted to have something that I can rely on.', 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'location' => 'Greeley, Colorado',
        'slug'     => 'Abel',
    ],
    [
        'quote'    => __('The relationship I’ve built with NTM has been fantastic. I call up Tom and tell him what I want, and I think he has me what I need on paper within the day.', 'standard'),
        'name'     => 'Mike Lemke',
        'company'  => 'Lemke Exteriors',
        'location' => 'Moorehead, Minnesota',
        'slug'     => 'Mike',
    ],
    [
        'quote'    => __('New Tech overall has been great to work with, and between the panel options and the service, that’s why we keep going back to them.', 'standard'),
        'name'     => 'Keith Ryan',
        'company'  => 'Metal Maniacs',
        'location' => 'Fort Myers, Florida',
        'slug'     => 'Keith',
    ],
];

if (empty($testimonials)) {
    return;
}
?>

<section class="social-proof section bg-blue-50 border-t border-blue-200" aria-labelledby="social-proof-title">
    <h2 id="social-proof-title" class="sr-only">
        <?php echo esc_html($content['sr_title']); ?>
    </h2>

    <div class="container grid gap-8 lg:gap-10">
        <div class="social-proof__slider relative max-w-3xl mx-auto grid gap-8 lg:gap-10">
            <div class="grid gap-4 pb-6 border-b border-blue-300 md:grid-cols-[140px_1fr] md:gap-10">
                <p class="font-mono uppercase text-xs tracking-wider text-blue-700">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
            </div>

            <div
                class="social-proof__track"
                role="region"
                aria-roledescription="carousel"
                aria-label="<?php esc_attr_e('Customer testimonials', 'standard'); ?>"
            >
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <?php $is_active = $index === 0; ?>
                    <blockquote
                        class="social-proof__slide grid gap-8 md:grid-cols-[140px_1fr] md:gap-10 md:items-start <?php echo $is_active ? '' : 'hidden'; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                        aria-roledescription="<?php esc_attr_e('slide', 'standard'); ?>"
                        aria-label="<?php echo esc_attr(sprintf(__('%1$d of %2$d', 'standard'), $index + 1, count($testimonials))); ?>"
                        <?php echo $is_active ? '' : 'aria-hidden="true"'; ?>
                    >
                        <img
                            src="<?php echo esc_url($cdn . '/' . $testimonial['slug'] . '-150x150.png'); ?>"
                            srcset="<?php echo esc_url($cdn . '/' . $testimonial['slug'] . '-150x150.png'); ?> 150w, <?php echo esc_url($cdn . '/' . $testimonial['slug'] . '-300x300.png'); ?> 300w"
                            sizes="(min-width: 768px) 140px, 120px"
                            alt=""
                            role="presentation"
                            width="140"
                            height="140"
                            class="w-28 h-28 md:w-[140px] md:h-[140px] object-cover rounded-full mx-auto md:mx-0"
                            loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>"
                            <?php echo $is_active ? '' : 'fetchpriority="low"'; ?>
                            decoding="async"
                        />

                        <div class="grid gap-6 text-center md:text-left">
                            <p class="text-lg text-blue-900 font-medium leading-relaxed md:text-xl">
                                &ldquo;<?php echo esc_html($testimonial['quote']); ?>&rdquo;
                            </p>

                            <footer class="grid gap-1">
                                <cite class="not-italic grid gap-1">
                                    <span class="font-mono uppercase text-blue-900 text-sm tracking-wider font-medium">
                                        <?php echo esc_html($testimonial['name']); ?>
                                    </span>
                                    <span class="font-sans text-blue-700 text-sm lg:text-base">
                                        <?php echo esc_html($testimonial['company']); ?>
                                    </span>
                                    <span class="font-mono uppercase text-blue-500 text-xs tracking-wider">
                                        <?php echo esc_html($testimonial['location']); ?>
                                    </span>
                                </cite>
                            </footer>
                        </div>
                    </blockquote>
                <?php endforeach; ?>
            </div>

            <nav class="flex justify-center gap-2" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <?php $is_active = $index === 0; ?>
                    <button
                        type="button"
                        class="social-proof__dot h-3 border-none cursor-pointer transition-all duration-200 hover:bg-blue-400 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2 <?php echo $is_active ? 'bg-blue-500 w-8' : 'bg-blue-200 w-3'; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                        aria-label="<?php echo esc_attr(sprintf(__('View testimonial %d', 'standard'), $index + 1)); ?>"
                        <?php echo $is_active ? 'aria-current="true"' : ''; ?>
                    ></button>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="flex justify-center">
            <a
                href="<?php echo esc_url($content['cta_url']); ?>"
                class="btn btn-outline-dark"
                target="_blank"
                rel="noopener"
            >
                <?php echo esc_html($content['cta_label']); ?>
            </a>
        </div>
    </div>
</section>
