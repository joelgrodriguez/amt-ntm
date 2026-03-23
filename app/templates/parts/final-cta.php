<?php
/**
 * Shared Template Part — Final CTA
 *
 * Split-layout call-to-action designed for high-ticket B2B conversion.
 * Left: copy, "what to expect" bullets, testimonial, CTA buttons.
 * Right: specialist photo with name/title.
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args:
 *   - content: array (title, text, expect_items, testimonial, specialist, cta_primary, cta_primary_url, cta_secondary, cta_secondary_url)
 *   - section_id: string for aria-labelledby
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content    = $args['content'] ?? [];
$section_id = $args['section_id'] ?? 'final-cta-title';

if (empty($content)) {
    return;
}

$specialist  = $content['specialist'] ?? [];
$testimonial = $content['testimonial'] ?? [];
$expect      = $content['expect_items'] ?? [];
?>

<section class="section bg-slate-900" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <!-- Copy Panel -->
            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow"><?php esc_html_e("Let's Talk", 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title text-white">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle text-slate-300 max-w-xl">
                        <?php echo esc_html($content['text']); ?>
                    </p>
                </div>

                <?php if (!empty($expect)) : ?>
                    <!-- What to Expect -->
                    <div class="grid gap-4">
                        <p class="text-sm font-semibold uppercase tracking-wider text-secondary">
                            <?php esc_html_e('What to expect on the call', 'standard'); ?>
                        </p>
                        <ul class="grid gap-3" role="list">
                            <?php foreach ($expect as $item) : ?>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <?php icon('check', ['class' => 'w-5 h-5 text-secondary shrink-0 mt-0.5']); ?>
                                    <span><?php echo esc_html($item); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($testimonial)) : ?>
                    <!-- Testimonial -->
                    <blockquote class="border-l-4 border-secondary pl-5">
                        <p class="text-slate-300 italic">
                            &ldquo;<?php echo esc_html($testimonial['quote']); ?>&rdquo;
                        </p>
                        <footer class="mt-2 text-sm text-slate-400">
                            <strong class="text-white"><?php echo esc_html($testimonial['name']); ?></strong>
                            <?php if (!empty($testimonial['company'])) : ?>
                                <span>&mdash; <?php echo esc_html($testimonial['company']); ?></span>
                            <?php endif; ?>
                        </footer>
                    </blockquote>
                <?php endif; ?>

                <!-- CTAs -->
                <div class="grid gap-3">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo esc_url($content['cta_primary_url']); ?>" class="btn btn-secondary btn-lg">
                            <?php echo esc_html($content['cta_primary']); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                        <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light btn-lg">
                            <?php echo esc_html($content['cta_secondary']); ?>
                        </a>
                    </div>
                    <p class="text-sm text-slate-400">
                        <?php esc_html_e('Free 30-min call. No obligation.', 'standard'); ?>
                    </p>
                </div>
            </div>

            <!-- Specialist Panel -->
            <?php if (!empty($specialist)) : ?>
                <div class="flex flex-col gap-6">

                    <!-- Image Collage -->
                    <div class="relative aspect-[4/3] w-full">
                        <?php if (!empty($specialist['image_machine'])) : ?>
                            <img
                                src="<?php echo esc_url($specialist['image_machine']); ?>"
                                alt=""
                                class="absolute top-0 left-0 w-2/5 aspect-square object-contain bg-white p-3 shadow-md"
                                loading="lazy"
                            >
                        <?php endif; ?>
                        <?php if (!empty($specialist['image_action'])) : ?>
                            <img
                                src="<?php echo esc_url($specialist['image_action']); ?>"
                                alt=""
                                class="absolute bottom-0 right-0 w-3/5 h-3/5 object-cover shadow-lg"
                                loading="lazy"
                            >
                        <?php endif; ?>
                        <!-- Specialist portrait + name card -->
                        <div class="absolute top-[10%] left-[20%] z-20 w-3/5 h-[85%] flex flex-col shadow-xl">
                            <img
                                src="<?php echo esc_url($specialist['image']); ?>"
                                alt="<?php echo esc_attr($specialist['name']); ?>"
                                class="w-full flex-1 object-cover"
                                loading="lazy"
                            >
                            <div class="bg-slate-800 px-4 py-3">
                                <p class="text-xs uppercase tracking-wider text-secondary font-semibold">
                                    <?php esc_html_e('Book a call with', 'standard'); ?>
                                </p>
                                <p class="text-base font-bold text-white">
                                    <?php echo esc_html($specialist['name']); ?>
                                </p>
                                <p class="text-xs text-slate-400">
                                    <?php echo esc_html($specialist['role']); ?>
                                    <?php if (!empty($specialist['detail'])) : ?>
                                        &middot; <?php echo esc_html($specialist['detail']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
