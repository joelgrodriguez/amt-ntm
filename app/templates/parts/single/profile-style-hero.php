<?php
/**
 * Single-post hero — profile / resource / manual.
 *
 * No image, no meta, no excerpt. Eyebrow + divider + sans semibold H1
 * on bg-blue-50 with the structural dot-grid. Matches the landing-page
 * hero rhythm used by the profiles, resources, manuals, and service-hub
 * archives.
 *
 * Args (all optional):
 *
 *   eyebrow  string  Visible label above the title. Defaults to the
 *                    post type's singular_name (e.g. "Profile", "Resource",
 *                    "Manual"). Pass an explicit string to override —
 *                    e.g. profiles surface the post's primary category
 *                    name instead.
 *   title    string  H1 text. Defaults to get_the_title().
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$type_object = get_post_type_object((string) get_post_type());
$default_eyebrow = $type_object?->labels->singular_name ?? ucfirst((string) get_post_type());

$args = wp_parse_args($args ?? [], [
    'eyebrow' => $default_eyebrow,
    'title'   => get_the_title(),
]);
?>

<header class="pattern-dot-grid pattern-dot-grid--surface border-b border-blue-200 bg-blue-50 pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="container">
        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php echo esc_html($args['eyebrow']); ?></p>
            <div class="section-divider"></div>
            <h1 class="font-semibold text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight break-words">
                <?php echo esc_html($args['title']); ?>
            </h1>
        </div>
    </div>
</header>
