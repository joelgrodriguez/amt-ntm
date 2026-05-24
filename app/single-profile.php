<?php
/**
 * The template for displaying single profile posts.
 *
 * Profile pages are a spec-sheet PDF plus the NTM machines that roll the
 * profile. The CMS body is almost always a single pdfjs-embed block; we
 * extract its attachment URL once so we can offer native download / open
 * links beside the JS viewer instead of trapping the buyer inside it.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Pull the first PDF attachment referenced by the post's pdfjs-embed
 * block. Returns null if the post body doesn't carry one.
 *
 * The pdfjsblock stores either an `attachment_id` shortcode arg, an
 * `imgID` block attribute, or a raw `url=` on the shortcode; we scan
 * for whichever shows up first.
 */
if (!function_exists('single_profile_pdf_url')) {
function single_profile_pdf_url(\WP_Post $post): ?string {
    $content = (string) $post->post_content;
    if ($content === '') {
        return null;
    }

    if (preg_match('/attachment_id=(\d+)/', $content, $m) === 1) {
        $url = wp_get_attachment_url((int) $m[1]);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    if (preg_match('/"imgID":(\d+)/', $content, $m) === 1) {
        $url = wp_get_attachment_url((int) $m[1]);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    if (preg_match('/url=([^\s\]"]+\.pdf)/i', $content, $m) === 1) {
        return $m[1];
    }

    return null;
}
}

get_header();

while (have_posts()) :
    the_post();

    $post_id      = (int) get_the_ID();
    $title        = get_the_title();
    $categories   = get_the_terms($post_id, 'category');
    $machine_tags = get_the_tags();
    $thumb_url    = get_the_post_thumbnail_url($post_id, 'large');
    $pdf_url      = single_profile_pdf_url(get_post());
    $eyebrow      = is_array($categories) && !empty($categories)
        ? $categories[0]->name
        : __('Profile', 'standard');
    $archive_url  = get_post_type_archive_link('profile');
    if (!is_string($archive_url) || $archive_url === '') {
        $archive_url = home_url('/profiles/');
    }
?>

<main id="primary" class="bg-white">

    <?php get_template_part('templates/parts/single/profile-style-hero', null, [
        'eyebrow' => $eyebrow,
        'title'   => $title,
    ]); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('container py-12 lg:py-16'); ?>>

        <div class="grid gap-10 lg:grid-cols-[360px_1fr] lg:gap-16">

            <aside class="grid gap-8 self-start">
                <figure class="border border-blue-200 m-0">
                    <?php if ($thumb_url) : ?>
                        <img src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr(sprintf(
                                 /* translators: %s: profile name. */
                                 __('Technical drawing of the %s profile', 'standard'),
                                 $title
                             )); ?>"
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

                <section aria-labelledby="profile-machines-heading" class="grid gap-4 border-t border-blue-200 pt-8">
                    <header class="section-header-left">
                        <p class="section-eyebrow"><?php esc_html_e('Compatibility', 'standard'); ?></p>
                        <div class="section-divider"></div>
                        <h2 id="profile-machines-heading"
                            class="font-sans font-semibold text-heading-sm text-blue-900 leading-tight tracking-tight">
                            <?php esc_html_e('Rolls On', 'standard'); ?>
                        </h2>
                    </header>

                    <?php if (is_array($machine_tags) && !empty($machine_tags)) : ?>
                        <ul class="grid gap-2 list-none p-0 m-0">
                            <?php foreach ($machine_tags as $machine_tag) :
                                /**
                                 * @todo Resolve machine_tag → WooCommerce product so the
                                 * tile can show the real machine image and link directly
                                 * to the product page. Until then we link to the tag
                                 * archive, which lists every profile this machine rolls.
                                 */
                            ?>
                                <li>
                                    <a href="<?php echo esc_url(get_tag_link($machine_tag->term_id)); ?>"
                                       class="group grid gap-1 px-4 py-3 bg-white border border-blue-200 no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                        <span class="font-sans font-semibold text-blue-900 leading-snug tracking-tight group-hover:text-blue-500 transition-colors">
                                            <?php echo esc_html($machine_tag->name); ?>
                                        </span>
                                        <span class="font-mono uppercase tracking-widest text-caption text-blue-400">
                                            <?php echo esc_html(sprintf(
                                                /* translators: %d: number of profiles this machine rolls. */
                                                _n('%d profile', '%d profiles', (int) $machine_tag->count, 'standard'),
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
                            <?php esc_html_e('No machines tagged yet.', 'standard'); ?>
                        </p>
                    <?php endif; ?>
                </section>

                <a href="<?php echo esc_url($archive_url); ?>"
                   class="inline-flex items-center gap-1.5 font-sans text-sm text-blue-500 hover:text-blue-900 no-underline w-fit">
                    <?php icon('arrow-left', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                    <?php esc_html_e('Back to all profiles', 'standard'); ?>
                </a>
            </aside>

            <section aria-labelledby="profile-spec-heading" class="grid gap-4 min-w-0">
                <h2 id="profile-spec-heading" class="section-eyebrow">
                    <?php esc_html_e('Spec Sheet', 'standard'); ?>
                </h2>
                <div class="prose max-w-full min-w-0">
                    <?php the_content(); ?>
                </div>
                <?php if (!$pdf_url) : ?>
                    <p class="font-mono text-caption uppercase tracking-widest text-blue-400">
                        <?php esc_html_e('PDF link unavailable. Try refreshing or contact NTM.', 'standard'); ?>
                    </p>
                <?php endif; ?>
            </section>

        </div>

    </article>

</main>

<?php
endwhile;

get_footer();
