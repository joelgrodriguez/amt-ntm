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
    'image' => content_url('/uploads/2020/03/ssqii-updated.png'),
    'alt'   => __('NTM rollforming machine on a jobsite rooftop', 'standard'),
];
?>

<section class="relative" aria-label="<?php echo esc_attr($content['alt']); ?>">
    <img
        src="<?php echo esc_url($content['image']); ?>"
        alt="<?php echo esc_attr($content['alt']); ?>"
        class="w-full h-64 md:h-80 lg:h-96 object-cover"
        loading="lazy"
    >
</section>
