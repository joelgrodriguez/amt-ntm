<?php
/**
 * Machines Page — Image Break
 *
 * Full-bleed lifestyle/jobsite photo for visual breathing room.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'image' => content_url('/uploads/2026/05/ntm-ssq2-frame-overhead-006.jpg'),
    'alt'   => __('NTM rollforming machine on a jobsite rooftop', 'standard'),
];
?>

<section class="relative" aria-label="<?php echo esc_attr($content['alt']); ?>">
    <?php \Standard\Images\responsive_image($content['image'], $content['alt'], 'full', [
        'class'   => 'w-full h-64 md:h-80 lg:h-96 object-cover',
        'loading' => 'lazy',
        'sizes'   => '100vw',
    ]); ?>
</section>
