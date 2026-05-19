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

$machine_cols = count($machine_dims);
$trailer_cols = count($trailer_dims);
?>

<section class="bg-blue-950 section" aria-labelledby="blueprint-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow text-red">Engineering Specs</p>
            <h2 id="blueprint-title" class="section-title text-white">Machine Footprint</h2>
        </div>

        <div class="border border-blue-700 aspect-[16/7] flex items-center justify-center mx-auto max-w-4xl">
            <?php if (!empty($svg_name)) : ?>
                <span class="text-blue-500 text-sm font-mono"><?php echo esc_html($svg_name); ?>.svg</span>
            <?php else : ?>
                <span class="text-blue-500 text-sm font-mono">Blueprint</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($machine_dims)) : ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-<?php echo esc_attr((string) $machine_cols); ?> gap-6 max-w-4xl mx-auto">
                <?php foreach ($machine_dims as $label => $value) : ?>
                    <div class="text-center">
                        <span class="block text-lg font-medium text-white font-mono"><?php echo esc_html($value); ?></span>
                        <span class="block text-xs text-blue-500 uppercase tracking-wider mt-1"><?php echo esc_html($label); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($trailer_dims)) : ?>
            <div class="border-t border-blue-800 pt-8">
                <p class="text-sm font-medium text-blue-400 uppercase tracking-wider text-center mb-6">On Trailer</p>
                <div class="grid grid-cols-2 md:grid-cols-<?php echo esc_attr((string) $trailer_cols); ?> gap-6 max-w-3xl mx-auto">
                    <?php foreach ($trailer_dims as $label => $value) : ?>
                        <div class="text-center">
                            <span class="block text-lg font-medium text-white font-mono"><?php echo esc_html($value); ?></span>
                            <span class="block text-xs text-blue-500 uppercase tracking-wider mt-1"><?php echo esc_html($label); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
