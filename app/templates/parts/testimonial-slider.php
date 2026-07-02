<?php
/**
 * Shared Template Part — Testimonial Slider (chrome-bar)
 *
 * The canonical chrome-bar testimonial composition: top + bottom mono
 * rails, border-x edge rails, red 2x2 dot, segmented dot indicator, and
 * a square portrait beside the quote. Autoplays, pauses on hover/focus,
 * driven by js/modules/SocialProof.js (which wires every .social-proof
 * section on the page independently).
 *
 * Extracted so the front page, single-machine pages, and landing pages
 * all render ONE slider, instead of three copies of the same markup.
 * Pass the testimonial set and the surrounding labels via args.
 *
 * @package Standard
 *
 * @param array  $testimonials Array of {quote, name, company, location, slug}.
 *                             `slug` names the portrait on the CDN.
 * @param array  $content      {eyebrow, channel, sr_title, nav_label,
 *                             cta_label, cta_url, count_left}.
 * @param string $section_id   ID for the sr-only h2 (aria-labelledby).
 * @param string $anchor       Optional id on the <section> for in-page
 *                             jump links (e.g. subnav "Case Study").
 * @param string $cdn          Portrait CDN base (optional override).
 *
 * @usage get_template_part('templates/parts/testimonial-slider', null, [...])
 * @see   js/modules/SocialProof.js
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$testimonials = $args['testimonials'] ?? [];
if (empty($testimonials)) {
    return;
}

$content = wp_parse_args($args['content'] ?? [], [
    'eyebrow'    => __('From the field', 'standard'),
    'channel'    => __('Customer testimonials', 'standard'),
    'sr_title'   => __('Customer testimonials', 'standard'),
    'nav_label'  => __('Testimonial navigation', 'standard'),
    'cta_label'  => __('All customer stories', 'standard'),
    'cta_url'    => '/learning-center/category/testimonials/',
    'count_left' => __('Stories', 'standard'),
]);

$section_id = $args['section_id'] ?? 'testimonial-slider-title';
$anchor     = $args['anchor'] ?? '';
$cdn        = \Standard\Url\canonical($args['cdn'] ?? 'https://newtechmachinery.com/wp-content/uploads/2025/06');
$total      = count($testimonials);
?>

<section <?php if ($anchor !== '') : ?>id="<?php echo esc_attr($anchor); ?>" <?php endif; ?>class="social-proof scroll-mt-24 bg-blue-50 text-blue-600 border-y border-blue-200" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <h2 id="<?php echo esc_attr($section_id); ?>" class="sr-only">
        <?php echo esc_html($content['sr_title']); ?>
    </h2>

    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['eyebrow']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span class="hidden md:inline"><?php echo esc_html($content['channel']); ?></span>
                    <span class="text-blue-900">
                        <span class="social-proof__current" data-current>1</span>
                        <span aria-hidden="true">/</span>
                        <?php echo esc_html((string) $total); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider body -->
    <div class="border-x border-blue-200 container">
        <div
            class="social-proof__track max-w-[1040px] mx-auto grid"
            role="region"
            aria-roledescription="carousel"
            aria-label="<?php echo esc_attr($content['sr_title']); ?>"
        >
            <?php foreach ($testimonials as $index => $testimonial) : ?>
                <?php $is_active = $index === 0; ?>
                <blockquote
                    class="social-proof__slide [grid-area:1/1] grid gap-8 p-6 md:p-10 lg:p-12 md:grid-cols-[140px_1fr] md:gap-12 md:items-start transition-opacity duration-200 ease-out <?php echo $is_active ? 'opacity-100' : 'opacity-0 pointer-events-none'; ?>"
                    data-index="<?php echo esc_attr((string) $index); ?>"
                    aria-roledescription="<?php esc_attr_e('slide', 'standard'); ?>"
                    aria-label="<?php echo esc_attr(sprintf(__('%1$d of %2$d', 'standard'), $index + 1, $total)); ?>"
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
                        class="w-28 h-28 md:w-[140px] md:h-[140px] object-cover mx-auto md:mx-0"
                        loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>"
                        <?php echo $is_active ? '' : 'fetchpriority="low"'; ?>
                        decoding="async"
                    />

                    <div class="grid gap-6 content-start text-center md:text-left">
                        <p class="font-sans font-medium text-blue-900 text-xl leading-snug md:text-2xl">
                            &ldquo;<?php echo esc_html($testimonial['quote']); ?>&rdquo;
                        </p>

                        <footer>
                            <cite class="not-italic grid gap-1">
                                <span class="font-mono uppercase font-medium tracking-wider text-sm text-blue-900">
                                    <?php echo esc_html($testimonial['name']); ?>
                                </span>
                                <span class="font-sans text-base text-blue-600">
                                    <?php echo esc_html($testimonial['company']); ?>
                                </span>
                                <span class="font-mono uppercase tracking-wider text-xs text-blue-500">
                                    <?php echo esc_html($testimonial['location']); ?>
                                </span>
                            </cite>
                        </footer>
                    </div>
                </blockquote>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <span class="hidden md:inline"><?php echo esc_html((string) $total); ?></span>
                    <span class="hidden md:inline"><?php echo esc_html($content['count_left']); ?></span>
                </div>

                <nav class="flex items-center gap-1" aria-label="<?php echo esc_attr($content['nav_label']); ?>">
                    <button
                        type="button"
                        class="social-proof__prev grid place-items-center w-11 h-11 -my-4 border-none bg-transparent cursor-pointer text-blue-900 transition-colors duration-150 hover:text-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                        aria-label="<?php esc_attr_e('Previous testimonial', 'standard'); ?>"
                    >
                        <?php icon('chevron-left', ['class' => 'w-4 h-4']); ?>
                    </button>

                    <span class="flex items-center gap-1 px-1">
                        <?php foreach ($testimonials as $index => $testimonial) : ?>
                            <?php $is_active = $index === 0; ?>
                            <button
                                type="button"
                                class="social-proof__dot h-3 border-none cursor-pointer transition-colors duration-150 hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2 <?php echo $is_active ? 'bg-red w-3' : 'bg-blue-300 w-1'; ?>"
                                data-index="<?php echo esc_attr((string) $index); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('View testimonial %d', 'standard'), $index + 1)); ?>"
                                <?php echo $is_active ? 'aria-current="true"' : ''; ?>
                            ></button>
                        <?php endforeach; ?>
                    </span>

                    <button
                        type="button"
                        class="social-proof__next grid place-items-center w-11 h-11 -my-4 border-none bg-transparent cursor-pointer text-blue-900 transition-colors duration-150 hover:text-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-2"
                        aria-label="<?php esc_attr_e('Next testimonial', 'standard'); ?>"
                    >
                        <?php icon('chevron-right', ['class' => 'w-4 h-4']); ?>
                    </button>
                </nav>

                <div class="flex items-center gap-2 pr-3">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>"
                        class="text-blue-900 inline-flex items-center gap-2 hover:text-blue-500 transition-colors duration-150"
                    >
                        <span><?php echo esc_html($content['cta_label']); ?></span>
                        <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
