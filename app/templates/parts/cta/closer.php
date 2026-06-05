<?php
/**
 * Shared Template Part — Closer CTA
 *
 * Simple dark-band close-of-page CTA. Centered title, subline, one
 * primary mono button. Reused across the front page, the about page,
 * and anywhere else a unified close belongs.
 *
 * If a surface needs a heavier close (specialist photo, testimonial,
 * "what to expect" bullets), use templates/parts/final-cta.php instead.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $title               Headline copy.
 *     @type string $text                Supporting subline.
 *     @type string $cta_primary         Button label.
 *     @type string $cta_primary_url     Button href (run through internal()).
 *     @type bool   $cta_primary_new_tab Optional. Open the primary button in a new tab. Default false.
 *     @type string $cta_secondary       Optional secondary button label.
 *     @type string $cta_secondary_url   Optional secondary button href.
 *     @type string $section_id          ID for aria-labelledby.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'title'             => __('Ready to Take Control of Your Business?', 'standard'),
    'text'              => __('Join thousands of contractors who stopped waiting on suppliers and started rolling their own profits.', 'standard'),
    'cta_primary'       => __('Talk to a Specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => '',
    'cta_secondary_url' => '',
    'section_id'        => 'closer-cta-title',
];

$content = wp_parse_args($args ?? [], $defaults);

$primary_new_tab = !empty($content['cta_primary_new_tab']);
?>

<section class="section bg-blue-900" aria-labelledby="<?php echo esc_attr($content['section_id']); ?>">
    <div class="container grid gap-8 lg:gap-10 text-center">

        <div class="grid gap-4">
            <h2 id="<?php echo esc_attr($content['section_id']); ?>" class="final-cta__title font-sans font-medium text-white">
                <?php echo esc_html($content['title']); ?>
            </h2>

            <p class="final-cta__subtitle font-sans text-blue-300 max-w-2xl mx-auto">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>" class="btn btn-primary"<?php echo $primary_new_tab ? ' target="_blank" rel="noopener"' : ''; ?>>
                <?php echo esc_html($content['cta_primary']); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <?php if (!empty($content['cta_secondary']) && !empty($content['cta_secondary_url'])) : ?>
                <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-light">
                    <?php echo esc_html($content['cta_secondary']); ?>
                </a>
            <?php endif; ?>
        </div>

    </div>
</section>
