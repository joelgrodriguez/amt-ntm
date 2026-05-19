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
// featured image and its PDF attachment (embedded inside a
// pdfjs-embed block in the post content). Field returns an array of
// post IDs (or post objects) — use the first.
$footprint_url     = '';
$footprint_alt     = '';
$footprint_pdf_url = '';
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

        // Extract the PDF URL from the pdfjs-embed block in the post content.
        $footprint_post = get_post($footprint_post_id);
        if ($footprint_post && function_exists('parse_blocks')) {
            foreach (parse_blocks($footprint_post->post_content) as $block) {
                if (($block['blockName'] ?? '') === 'pdfjsblock/pdfjs-embed' && !empty($block['attrs']['imageURL'])) {
                    $footprint_pdf_url = (string) $block['attrs']['imageURL'];
                    break;
                }
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

<?php
// Two-cell composition: machine + trailer. Render only the cells that
// have data so a missing block doesn't leave an empty column.
$cells = [];
if (!empty($machine_dims)) {
    $cells[] = [
        'index' => '01',
        'label' => __('Machine', 'standard'),
        'dims'  => $machine_dims,
    ];
}
if (!empty($trailer_dims)) {
    $cells[] = [
        'index' => sprintf('%02d', count($cells) + 1),
        'label' => __('On Trailer', 'standard'),
        'dims'  => $trailer_dims,
    ];
}
?>

<section class="bg-white text-blue-600 border-y border-blue-200" aria-labelledby="blueprint-title">

    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span><?php esc_html_e('Engineering Specs', 'standard'); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php esc_html_e('Machine / On Trailer', 'standard'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Diagram + dimensions: image left, spec cells stacked right -->
    <div class="border-x border-blue-200 container">

        <h2 id="blueprint-title" class="sr-only">
            <?php esc_html_e('Machine Footprint', 'standard'); ?>
        </h2>

        <div class="grid md:grid-cols-2 md:items-stretch">

            <!-- Diagram column -->
            <?php if (!empty($footprint_url) || !empty($svg_name)) : ?>
                <div class="border-b border-blue-200 md:border-b-0 md:border-r p-6 lg:p-8 flex items-center justify-center">
                    <?php if (!empty($footprint_url)) : ?>
                        <?php if (!empty($footprint_pdf_url)) : ?>
                            <a
                                href="<?php echo esc_url($footprint_pdf_url); ?>"
                                target="_blank"
                                rel="noopener"
                                aria-label="<?php echo esc_attr(sprintf(__('Open %s PDF in a new tab', 'standard'), $footprint_alt)); ?>"
                                class="block transition-opacity hover:opacity-90 w-full"
                            >
                                <?php \Standard\Images\responsive_image($footprint_url, $footprint_alt, 'large', [
                                    'class' => 'block w-full h-auto',
                                ]); ?>
                            </a>
                        <?php else : ?>
                            <?php \Standard\Images\responsive_image($footprint_url, $footprint_alt, 'large', [
                                'class' => 'block w-full h-auto',
                            ]); ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="border border-blue-200 aspect-[16/7] flex items-center justify-center w-full">
                            <span class="text-blue-500 text-sm font-mono"><?php echo esc_html($svg_name . '.svg'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Dimension cells column -->
            <?php if (!empty($cells)) : ?>
                <div class="grid">
                    <?php foreach ($cells as $i => $cell) : ?>
                        <div class="grid gap-4 p-6 lg:p-8 <?php echo $i > 0 ? 'border-t border-blue-200' : ''; ?>">
                            <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-500">
                                <span><?php echo esc_html($cell['index']); ?></span>
                                <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                                <span><?php echo esc_html($cell['label']); ?></span>
                            </div>
                            <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
                                <?php foreach ($cell['dims'] as $label => $value) : ?>
                                    <div>
                                        <dt class="block text-xs text-blue-500 uppercase tracking-wider font-mono"><?php echo esc_html($label); ?></dt>
                                        <dd class="block text-lg font-medium text-blue-900 font-mono mt-1"><?php echo esc_html($value); ?></dd>
                                    </div>
                                <?php endforeach; ?>
                            </dl>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <?php icon('file-text', ['class' => 'w-3 h-3 text-red']); ?>
                    <span class="text-blue-900"><?php esc_html_e('Machine Footprint', 'standard'); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <?php if (!empty($footprint_pdf_url)) : ?>
                        <span class="hidden md:inline"><?php esc_html_e('Open', 'standard'); ?></span>
                        <a
                            href="<?php echo esc_url($footprint_pdf_url); ?>"
                            target="_blank"
                            rel="noopener"
                            class="text-blue-900 hover:text-blue-500"
                        >
                            <?php esc_html_e('Full PDF', 'standard'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="hidden md:flex gap-1" aria-hidden="true">
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
