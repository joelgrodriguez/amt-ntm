<?php
/**
 * Shared Template Part — Customer Story
 *
 * Reusable customer case study section with pull quote, stats, and CTA.
 * Supports image on left or right via $args['image_position'].
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args:
 *   - content: array (eyebrow, quote, name, company, machine, image, cta_text, cta_url, cta_icon)
 *   - stats: array of {stat, label}
 *   - image_position: 'left' or 'right' (default: 'right')
 *   - section_id: string for aria-labelledby
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content        = $args['content'] ?? [];
$stats          = $args['stats'] ?? [];
$image_position = $args['image_position'] ?? 'right';
$section_id     = $args['section_id'] ?? 'customer-story-title';
$cta_icon       = $content['cta_icon'] ?? 'arrow-right';

if (empty($content)) {
    return;
}
?>

<section class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">

            <?php if ($image_position === 'left') : ?>
                <!-- Image — LEFT -->
                <div>
                    <img
                        src="<?php echo esc_url($content['image']); ?>"
                        alt="<?php echo esc_attr($content['name'] . ' — ' . $content['company']); ?>"
                        class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p id="<?php echo esc_attr($section_id); ?>" class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                </div>

                <blockquote class="text-xl font-mono text-blue-800 leading-relaxed lg:text-2xl">
                    <span class="text-red text-3xl leading-none" aria-hidden="true">&ldquo;</span>
                    <?php echo esc_html($content['quote']); ?>
                    <span class="text-red text-3xl leading-none" aria-hidden="true">&rdquo;</span>
                </blockquote>

                <div>
                    <p class="font-medium text-blue-900">
                        <?php echo esc_html($content['name']); ?>
                    </p>
                    <p class="text-sm text-blue-500">
                        <?php echo esc_html($content['company']); ?> &middot; <?php echo esc_html($content['machine']); ?>
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-6 border-t border-blue-200 pt-8">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="grid gap-1">
                            <span class="text-2xl font-medium text-blue-900 lg:text-3xl">
                                <?php echo esc_html($stat['stat']); ?>
                            </span>
                            <span class="text-xs text-blue-500 uppercase tracking-wider">
                                <?php echo esc_html($stat['label']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-outline-dark">
                        <?php if ($cta_icon === 'play') : ?>
                            <?php icon('play', ['class' => 'w-5 h-5']); ?>
                        <?php endif; ?>
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php if ($cta_icon === 'arrow-right') : ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <?php if ($image_position === 'right') : ?>
                <!-- Image — RIGHT -->
                <div>
                    <img
                        src="<?php echo esc_url($content['image']); ?>"
                        alt="<?php echo esc_attr($content['name'] . ' — ' . $content['company']); ?>"
                        class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
