<?php
/**
 * Social Proof Section Template Part
 *
 * Testimonial slider showcasing real customer quotes.
 * Auto-advances with navigation dots; pauses on hover/focus.
 *
 * Photo URLs point to the production CDN; portraits are public
 * marketing assets and won't move.
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
    'title'     => __('What Our Customers Are Saying', 'standard'),
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
        'photo'    => $cdn . '/Danaik-1.png',
    ],
    [
        'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and ease of switching out dies. If you do have a problem, there’s a sales team that you can call.', 'standard'),
        'name'     => 'Todd Andrews',
        'company'  => 'Classic Metals Inc.',
        'location' => 'Chester, South Carolina',
        'photo'    => $cdn . '/Todd.png',
    ],
    [
        'quote'    => __('I chose New Tech Machinery over the competition because of how valued I felt as a customer. They truly valued me and appreciated me as a person, it wasn’t just another sale.', 'standard'),
        'name'     => 'Jim Averill',
        'company'  => 'Gunnison Sheet Metal',
        'location' => 'Gunnison, Colorado',
        'photo'    => $cdn . '/Jim.png',
    ],
    [
        'quote'    => __('For me, New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted to have something that I can rely on.', 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'location' => 'Greeley, Colorado',
        'photo'    => $cdn . '/Abel.png',
    ],
    [
        'quote'    => __('The relationship I’ve built with NTM has been fantastic. I call up Tom and tell him what I want, and I think he has me what I need on paper within the day.', 'standard'),
        'name'     => 'Mike Lemke',
        'company'  => 'Lemke Exteriors',
        'location' => 'Moorehead, Minnesota',
        'photo'    => $cdn . '/Mike.png',
    ],
    [
        'quote'    => __('New Tech overall has been great to work with, and between the panel options and the service, that’s why we keep going back to them.', 'standard'),
        'name'     => 'Keith Ryan',
        'company'  => 'Metal Maniacs',
        'location' => 'Fort Myers, Florida',
        'photo'    => $cdn . '/Keith.png',
    ],
];

if (empty($testimonials)) {
    return;
}
?>

<section class="social-proof section bg-blue-50" aria-labelledby="social-proof-title">
    <div class="container grid gap-10 lg:gap-12">
        <h2 id="social-proof-title" class="section-title text-center">
            <?php echo esc_html($content['title']); ?>
        </h2>

        <div class="social-proof__slider relative max-w-3xl mx-auto text-center grid justify-center gap-8 lg:gap-10">
            <div class="social-proof__track">
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <blockquote
                        class="social-proof__slide grid gap-6 <?php echo $index === 0 ? 'block' : 'hidden'; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                    >
                        <img
                            src="<?php echo esc_url($testimonial['photo']); ?>"
                            alt=""
                            width="120"
                            height="120"
                            class="mx-auto w-24 h-24 lg:w-28 lg:h-28 rounded-full object-cover border border-blue-200"
                            loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                            decoding="async"
                        />

                        <p class="text-lg text-blue-800 font-medium leading-relaxed md:text-xl lg:text-2xl">
                            &ldquo;<?php echo esc_html($testimonial['quote']); ?>&rdquo;
                        </p>

                        <footer class="grid gap-1">
                            <cite class="not-italic grid gap-0.5">
                                <span class="font-sans font-medium text-blue-900 text-base lg:text-lg">
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
                    </blockquote>
                <?php endforeach; ?>
            </div>

            <nav class="flex justify-center gap-2" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <button
                        type="button"
                        class="social-proof__dot h-3 border-none cursor-pointer transition-all duration-200 hover:bg-blue-400 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2 <?php echo $index === 0 ? 'bg-blue-500 w-8' : 'bg-blue-200 w-3'; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                        aria-label="<?php echo esc_attr(sprintf(__('View testimonial %d', 'standard'), $index + 1)); ?>"
                    ></button>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="flex justify-center">
            <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-outline-dark">
                <?php echo esc_html($content['cta_label']); ?>
            </a>
        </div>
    </div>
</section>
