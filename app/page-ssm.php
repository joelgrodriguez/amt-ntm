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
$ssm_config = [
    'image_url' => 'https://newtechmachinery.com/wp-content/uploads/2026/08/Covered-machine-tradeshow-image.png',
    'image_alt' => __('A new NTM machine under a cover, waiting for its reveal', 'standard'),
];

get_header();
?>

<main id="primary">
    <?php get_template_part('templates/pages/ssm/signup', null, ['config' => $ssm_config]); ?>
</main>

<?php
get_footer();
