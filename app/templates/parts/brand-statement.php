<?php
/**
 * Shared Template Part — Brand Statement
 *
 * Centered narrative block on bg-blue-50. Carries the page's mid-funnel
 * thesis paragraph: keyword-rich, indexable, above-the-fold-adjacent.
 *
 * Used on /machines, /roof-wall-panel-machines/, /seamless-gutter-machines/
 * and anywhere else a single thesis statement belongs between hero and
 * product grid.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $title      Headline (H2).
 *     @type string $text       Body paragraph.
 *     @type string $section_id ID for aria-labelledby.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$title      = $args['title'] ?? '';
$text       = $args['text'] ?? '';
$section_id = $args['section_id'] ?? 'brand-statement-title';

if ($title === '' || $text === '') {
    return;
}
?>

<section class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container grid gap-6 max-w-3xl mx-auto text-center">
        <div class="section-divider-center"></div>
        <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title">
            <?php echo esc_html($title); ?>
        </h2>
        <p class="section-subtitle max-w-2xl mx-auto">
            <?php echo esc_html($text); ?>
        </p>
    </div>
</section>
