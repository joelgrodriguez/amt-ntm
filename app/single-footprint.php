<?php
/**
 * The template for displaying single footprint posts.
 *
 * Footprint pages are a spec-sheet PDF (machine plan view, dimensions,
 * shipping crate footprint) plus the NTM machines the drawing applies
 * to. Shares its surface with single-profile via the spec-sheet-layout
 * template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\PdfAttachment\url_from_post;

get_header();

while (have_posts()) :
    the_post();

    $archive_url = home_url('/machines/footprints/');
?>

<main id="primary" class="bg-white">

    <?php get_template_part('templates/parts/single/spec-sheet-layout', null, [
        'pdf_url'            => url_from_post(get_post()),
        'eyebrow'            => __('Footprint', 'standard'),
        'alt_template'       => __('Machine footprint diagram for %s', 'standard'),
        'compat_eyebrow'     => __('Compatibility', 'standard'),
        'compat_heading'     => __('Fits These Machines', 'standard'),
        'compat_empty'       => __('No machines tagged yet.', 'standard'),
        'tag_count_singular' => __('%d footprint', 'standard'),
        'tag_count_plural'   => __('%d footprints', 'standard'),
        'archive_url'        => $archive_url,
        'back_label'         => __('Back to all footprints', 'standard'),
        'spec_heading'       => __('Footprint Drawing', 'standard'),
    ]); ?>

</main>

<?php
endwhile;

get_footer();
