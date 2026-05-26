<?php
/**
 * Single-post spec-sheet layout — Profiles & Footprints.
 *
 * Three-column-feel single template: hero strip on top, then a two-column
 * body — a sticky-feeling left aside with the technical drawing, PDF
 * actions, machine-compatibility list, and back-link; plus a wide right
 * column with the editor content (usually a pdfjs-embed). Profiles and
 * Footprints share this exact surface, so the layout lives here and each
 * single-* template hands it a small config dict.
 *
 * Args (all optional unless noted):
 *
 *   pdf_url          string|null  Optional. Resolved PDF URL extracted by the caller.
 *   eyebrow          string       Default: post type singular name. Used by hero + visual rhythm.
 *   alt_template     string       sprintf format for the drawing alt text. Default "%s" (title).
 *                                 Profiles override to "Technical drawing of the %s profile".
 *   compat_eyebrow   string       Section eyebrow for the compatibility block. Default "Compatibility".
 *   compat_heading   string       Section H2. Default "Compatible Machines".
 *   compat_empty     string       Empty-state copy. Default "No machines tagged yet.".
 *   tag_count_label  string       _n() pair for "%d <thing>". Default profiles.
 *   archive_url      string       URL for the back-link. Required.
 *   back_label       string       Back-link text. Required.
 *   spec_heading     string       Right-column section eyebrow. Default "Spec Sheet".
 *   pdf_missing_copy string       Shown when no pdf_url and no fallback. Default profile copy.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$post_id      = (int) get_the_ID();
$title        = get_the_title();
$machine_tags = get_the_tags();
$thumb_url    = get_the_post_thumbnail_url($post_id, 'large');

$type_object  = get_post_type_object((string) get_post_type());
$type_label   = $type_object instanceof \WP_Post_Type
    ? $type_object->labels->singular_name
    : ucfirst((string) get_post_type());

$defaults = [
    'pdf_url'          => null,
    'eyebrow'          => $type_label,
    'alt_template'     => '%s',
    'compat_eyebrow'   => __('Compatibility', 'standard'),
    'compat_heading'   => __('Compatible Machines', 'standard'),
    'compat_empty'     => __('No machines tagged yet.', 'standard'),
    'tag_count_singular' => __('%d item', 'standard'),
    'tag_count_plural'   => __('%d items', 'standard'),
    'archive_url'      => '',
    'back_label'       => __('Back', 'standard'),
    'spec_heading'     => __('Spec Sheet', 'standard'),
    'pdf_missing_copy' => __('PDF link unavailable. Try refreshing or contact NTM.', 'standard'),
];

$args = wp_parse_args($args ?? [], $defaults);

$pdf_url     = is_string($args['pdf_url']) && $args['pdf_url'] !== '' ? (string) $args['pdf_url'] : null;
$alt_text    = sprintf((string) $args['alt_template'], $title);
$archive_url = (string) $args['archive_url'];
?>

<?php get_template_part('templates/parts/single/profile-style-hero', null, [
    'eyebrow' => (string) $args['eyebrow'],
    'title'   => $title,
]); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('container py-12 lg:py-16'); ?>>

    <div class="grid gap-10 lg:grid-cols-[360px_1fr] lg:gap-16">

        <aside class="grid gap-8 self-start">
            <figure class="border border-blue-200 m-0">
                <?php if ($thumb_url) : ?>
                    <img src="<?php echo esc_url($thumb_url); ?>"
                         alt="<?php echo esc_attr($alt_text); ?>"
                         class="block w-full h-auto"
                         loading="eager"
                         decoding="async">
                <?php else : ?>
                    <span class="block font-mono text-caption uppercase tracking-widest text-blue-400 px-4 py-8 text-center">
                        <?php esc_html_e('No drawing on file', 'standard'); ?>
                    </span>
                <?php endif; ?>
            </figure>

            <?php if ($pdf_url) : ?>
                <div class="grid gap-2">
                    <a href="<?php echo esc_url($pdf_url); ?>"
                       download
                       class="inline-flex items-center justify-between gap-3 px-4 py-3 bg-blue-500 border border-blue-500 text-white hover:bg-blue-700 hover:border-blue-700 no-underline transition-colors duration-200">
                        <span class="font-mono font-medium uppercase tracking-widest text-caption">
                            <?php esc_html_e('Download PDF', 'standard'); ?>
                        </span>
                        <?php icon('download', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                    </a>
                    <a href="<?php echo esc_url($pdf_url); ?>"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center justify-between gap-3 px-4 py-3 bg-white border border-blue-200 text-blue-900 hover:border-blue-500 no-underline transition-colors duration-200">
                        <span class="font-mono font-medium uppercase tracking-widest text-caption">
                            <?php esc_html_e('Open in new tab', 'standard'); ?>
                        </span>
                        <?php icon('external-link', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                    </a>
                </div>
            <?php endif; ?>

            <section aria-labelledby="single-compat-heading" class="grid gap-4 border-t border-blue-200 pt-8">
                <header class="section-header-left">
                    <p class="section-eyebrow"><?php echo esc_html((string) $args['compat_eyebrow']); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="single-compat-heading"
                        class="font-sans font-semibold text-heading-sm text-blue-900 leading-tight tracking-tight">
                        <?php echo esc_html((string) $args['compat_heading']); ?>
                    </h2>
                </header>

                <?php if (is_array($machine_tags) && !empty($machine_tags)) : ?>
                    <ul class="grid gap-2 list-none p-0 m-0">
                        <?php foreach ($machine_tags as $machine_tag) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_tag_link($machine_tag->term_id)); ?>"
                                   class="group grid gap-1 px-4 py-3 bg-white border border-blue-200 no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                    <span class="font-sans font-semibold text-blue-900 leading-snug tracking-tight group-hover:text-blue-500 transition-colors">
                                        <?php echo esc_html($machine_tag->name); ?>
                                    </span>
                                    <span class="font-mono uppercase tracking-widest text-caption text-blue-400">
                                        <?php echo esc_html(sprintf(
                                            _n(
                                                (string) $args['tag_count_singular'],
                                                (string) $args['tag_count_plural'],
                                                (int) $machine_tag->count,
                                                'standard'
                                            ),
                                            (int) $machine_tag->count
                                        )); ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="font-sans text-blue-600"
                       style="font-size: var(--text-body); line-height: 1.6;">
                        <?php echo esc_html((string) $args['compat_empty']); ?>
                    </p>
                <?php endif; ?>
            </section>

            <?php if ($archive_url !== '') : ?>
                <a href="<?php echo esc_url($archive_url); ?>"
                   class="inline-flex items-center gap-1.5 font-sans text-sm text-blue-500 hover:text-blue-900 no-underline w-fit">
                    <?php icon('arrow-left', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                    <?php echo esc_html((string) $args['back_label']); ?>
                </a>
            <?php endif; ?>
        </aside>

        <section aria-labelledby="single-spec-heading" class="grid gap-4 min-w-0">
            <h2 id="single-spec-heading" class="section-eyebrow">
                <?php echo esc_html((string) $args['spec_heading']); ?>
            </h2>
            <div class="prose max-w-full min-w-0">
                <?php the_content(); ?>
            </div>
            <?php if (!$pdf_url) : ?>
                <p class="font-mono text-caption uppercase tracking-widest text-blue-400">
                    <?php echo esc_html((string) $args['pdf_missing_copy']); ?>
                </p>
            <?php endif; ?>
        </section>

    </div>

</article>
