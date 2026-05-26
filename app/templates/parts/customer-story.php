<?php
/**
 * Shared Template Part — Customer Story
 *
 * Two-column case study: image + stats under the image on one side,
 * pull quote + attribution + CTA on the other. Image is 16:9. Stats
 * sit directly under the image so the photographic evidence and the
 * numeric evidence read as one column of proof.
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
$anchor         = $args['anchor'] ?? '';
$cta_icon       = $content['cta_icon'] ?? 'arrow-right';
$background     = $args['background'] ?? 'bg-blue-50';

if (empty($content)) {
    return;
}
$render_media = function () use ($content, $stats) :void {
    ?>
    <div class="grid gap-6">
        <img
            src="<?php echo esc_url($content['image']); ?>"
            alt="<?php echo esc_attr($content['name'] . ', ' . $content['company']); ?>"
            class="w-full aspect-video object-cover"
            loading="lazy"
        >

        <?php if (!empty($stats)) : ?>
            <div class="grid grid-cols-3 gap-6">
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
        <?php endif; ?>
    </div>
    <?php
};
?>

<section <?php if ($anchor !== '') : ?>id="<?php echo esc_attr($anchor); ?>" <?php endif; ?>class="section <?php echo esc_attr($background); ?>" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">

            <?php if ($image_position === 'left') $render_media(); ?>
            <div class="grid gap-8 content-start">
                <?php $has_eyebrow = !empty($content['eyebrow']); ?>
                <?php if ($has_eyebrow) : ?>
                    <div class="section-header-left">
                        <p id="<?php echo esc_attr($section_id); ?>" class="section-eyebrow">
                            <?php echo esc_html($content['eyebrow']); ?>
                        </p>
                        <div class="section-divider"></div>
                    </div>
                <?php else : ?>
                    <span id="<?php echo esc_attr($section_id); ?>" class="sr-only">
                        <?php echo esc_html($content['name'] . ', ' . $content['company']); ?>
                    </span>
                <?php endif; ?>

                <blockquote class="text-xl font-medium text-blue-900 leading-snug tracking-tight md:text-2xl lg:text-3xl">
                    &ldquo;<?php echo esc_html($content['quote']); ?>&rdquo;
                </blockquote>

                <div>
                    <p class="font-medium text-blue-900">
                        <?php echo esc_html($content['name']); ?>
                    </p>
                    <p class="text-sm text-blue-500">
                        <?php echo esc_html($content['company']); ?> &middot; <?php echo esc_html($content['machine']); ?>
                    </p>
                </div>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-sm btn-ghost px-0">
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

            <?php if ($image_position === 'right') $render_media(); ?>

        </div>
    </div>
</section>
