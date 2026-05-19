<?php
/**
 * Machine Product — Blueprint / Footprint
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine    = $args['machine'] ?? [];
$dimensions = $machine['specs']['dimensions'] ?? [];
$svg_name   = $machine['blueprint']['svg'] ?? '';

// ACF 'footprint' field on the product page. Field is a relationship
// to a 'footprint' custom post type; resolve the linked post's
// featured image. Field returns an array of post IDs (or post objects)
// — use the first.
$footprint_url = '';
$footprint_alt = '';
if (function_exists('get_field')) {
    $footprint = get_field('footprint');
    $footprint_post_id = 0;

    if (is_array($footprint) && !empty($footprint)) {
        $first = reset($footprint);
        if (is_object($first) && isset($first->ID)) {
            $footprint_post_id = (int) $first->ID;
        } elseif (is_numeric($first)) {
            $footprint_post_id = (int) $first;
        }
    } elseif (is_object($footprint) && isset($footprint->ID)) {
        $footprint_post_id = (int) $footprint->ID;
    } elseif (is_numeric($footprint)) {
        $footprint_post_id = (int) $footprint;
    }

    if ($footprint_post_id > 0) {
        $thumb_id = (int) get_post_thumbnail_id($footprint_post_id);
        if ($thumb_id > 0) {
            $footprint_url = (string) (wp_get_attachment_image_url($thumb_id, 'large') ?: '');
            $footprint_alt = (string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
            if ($footprint_alt === '') {
                $footprint_alt = (string) get_the_title($footprint_post_id);
            }
        }
    }
}

if (empty($dimensions)) {
    return;
}

$machine_raw = $dimensions['machine'] ?? [];
$trailer_raw = $dimensions['on_trailer'] ?? [];

// Build machine dimensions associative array.
$machine_dims = [];
if (!empty($machine_raw['length']))         { $machine_dims['Length']       = $machine_raw['length']; }
if (!empty($machine_raw['width']))          { $machine_dims['Width']        = $machine_raw['width']; }
if (!empty($machine_raw['height']))         { $machine_dims['Height']       = $machine_raw['height']; }
if (!empty($machine_raw['weight']))         { $machine_dims['Weight']       = $machine_raw['weight']; }
if (!empty($machine_raw['length_slitter'])) { $machine_dims['w/ Slitter']   = $machine_raw['length_slitter']; }
if (!empty($machine_raw['height_no_rack'])) { $machine_dims['No Rack']      = $machine_raw['height_no_rack']; }

// Build trailer dimensions associative array.
$trailer_dims = [];
if (!empty($trailer_raw['length'])) { $trailer_dims['Length'] = $trailer_raw['length']; }
if (!empty($trailer_raw['width']))  { $trailer_dims['Width']  = $trailer_raw['width']; }
if (!empty($trailer_raw['height'])) { $trailer_dims['Height'] = $trailer_raw['height']; }
if (!empty($trailer_raw['weight'])) { $trailer_dims['Weight'] = $trailer_raw['weight']; }

?>

<section class="bg-blue-900 section" aria-labelledby="blueprint-title">
    <div class="max-w-7xl mx-auto px-4 md:px-8 lg:px-12 section-content">

        <div class="section-header">
            <p class="section-eyebrow text-red">Engineering Specs</p>
            <h2 id="blueprint-title" class="section-title text-white">Machine Footprint</h2>
        </div>

        <div class="grid gap-10 lg:grid-cols-2 lg:gap-12 lg:items-center">

            <!-- Diagram at natural proportions. -->
            <div class="lg:justify-self-end">
                <?php if (!empty($footprint_url)) : ?>
                    <?php \Standard\Images\responsive_image($footprint_url, $footprint_alt, 'large', [
                        'class' => 'block max-w-full h-auto',
                    ]); ?>
                <?php else : ?>
                    <div class="border border-blue-700 aspect-[16/7] flex items-center justify-center">
                        <span class="text-blue-400 text-sm font-mono"><?php echo esc_html(!empty($svg_name) ? $svg_name . '.svg' : 'Blueprint'); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dimensions: Machine over Trailer, hairline between -->
            <dl class="grid gap-8 w-full">
                <?php if (!empty($machine_dims)) : ?>
                    <div class="grid gap-4">
                        <p class="font-mono text-xs uppercase tracking-wider text-blue-400">
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                            <?php foreach ($machine_dims as $label => $value) : ?>
                                <div>
                                    <dt class="block text-xs text-blue-400 uppercase tracking-wider font-mono"><?php echo esc_html($label); ?></dt>
                                    <dd class="block text-lg font-medium text-white font-mono mt-1"><?php echo esc_html($value); ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($trailer_dims)) : ?>
                    <div class="grid gap-4 border-t border-blue-800 pt-6">
                        <p class="font-mono text-xs uppercase tracking-wider text-blue-400">
                            <?php esc_html_e('On Trailer', 'standard'); ?>
                        </p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                            <?php foreach ($trailer_dims as $label => $value) : ?>
                                <div>
                                    <dt class="block text-xs text-blue-400 uppercase tracking-wider font-mono"><?php echo esc_html($label); ?></dt>
                                    <dd class="block text-lg font-medium text-white font-mono mt-1"><?php echo esc_html($value); ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </dl>

        </div>

    </div>
</section>
