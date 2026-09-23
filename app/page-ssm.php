<?php
/**
 * SSM Portable Siding Machine — coming-soon sign-up page.
 *
 * WordPress loads this file for the page with slug `ssm`.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

// Placeholder machine image. Swap for the approved SSM render when it lands.
// The path resolves to the media-library attachment (for srcset); the URL is
// the fallback if that attachment is missing in this environment.
$ssm_image_path = 'uploads/2026/08/Covered-machine-tradeshow-image.png';
$ssm_config = [
    'image_id'  => attachment_url_to_postid(content_url($ssm_image_path)),
    'image_url' => 'https://newtechmachinery.com/wp-content/' . $ssm_image_path,
];

get_header();
?>

<main id="primary">
    <?php get_template_part('templates/pages/ssm/hero', null, ['config' => $ssm_config]); ?>

    <?php get_template_part('templates/pages/ssm/signup'); ?>
</main>

<?php
get_footer();
