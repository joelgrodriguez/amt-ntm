<?php
/**
 * The template for displaying single profile posts.
 *
 * Profile pages are a spec-sheet PDF plus the NTM machines that roll the
 * profile. Layout lives in templates/parts/single/spec-sheet-layout.php
 * (shared with single-footprint); this file just provides the
 * profile-specific eyebrow, copy, and back-link.
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

    $categories  = get_the_terms((int) get_the_ID(), 'category');
    $eyebrow     = is_array($categories) && !empty($categories)
        ? $categories[0]->name
        : __('Profile', 'standard');
    $archive_url = get_post_type_archive_link('profile');
    if (!is_string($archive_url) || $archive_url === '') {
        $archive_url = home_url('/profiles/');
    }
?>

<main id="primary" class="bg-white">

    <?php get_template_part('templates/parts/single/spec-sheet-layout', null, [
        'pdf_url'        => url_from_post(get_post()),
        'eyebrow'        => $eyebrow,
        'alt_template'   => __('Technical drawing of the %s profile', 'standard'),
        'compat_eyebrow' => __('Compatibility', 'standard'),
        'compat_heading' => __('Rolls On', 'standard'),
        'compat_empty'   => __('No machines tagged yet.', 'standard'),
        'archive_url'    => $archive_url,
        'back_label'     => __('Back to all profiles', 'standard'),
        'spec_heading'   => __('Spec Sheet', 'standard'),
    ]); ?>

</main>

<?php
endwhile;

get_footer();
