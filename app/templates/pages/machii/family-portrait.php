<?php
/**
 * MACH II Family — Family Portrait
 *
 * Dark full-bleed section. Four MACH II machines laid out on one
 * "wall" with mono labels underneath. Tapping a tile scrolls down to
 * the variant-matrix section pre-targeted at that machine via the
 * hash. Hover dims the other tiles to focus attention; CSS-only base
 * via :has(.tile:hover) so no JS is required.
 *
 * The grid is 2 cols on mobile, 4 on lg+. Each tile is a square
 * silhouette panel with the machine name in mono and the descriptor
 * underneath. The page-wide single red moment lives elsewhere; the
 * family portrait stays in blue.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_machines;

$machines = get_gutter_machines();

if (empty($machines)) {
    return;
}
?>

<section
    id="machii-family-portrait"
    class="section bg-blue-900 text-white border-t border-blue-800 scroll-mt-20"
    aria-labelledby="machii-family-title"
>
    <div class="container section-content">
        <div class="section-header-left">
            <p class="section-eyebrow text-red flex items-center gap-2">
                <span aria-hidden="true" class="inline-block w-1 h-1 bg-red"></span>
                <?php esc_html_e('The Family', 'standard'); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="machii-family-title" class="section-title text-white">
                <?php esc_html_e('Three K-style. One box gutter.', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-200 max-w-2xl">
                <?php esc_html_e('Three K-style configurations and a commercial box-gutter sibling. Every MACH II runs polyurethane drive rollers, ships in 1 to 2 weeks, and is built for crews who treat their gutter machine like a member of payroll.', 'standard'); ?>
            </p>
        </div>

        <div class="machii-portrait grid grid-cols-2 gap-px bg-blue-700 border border-blue-700 lg:grid-cols-4">
            <?php foreach ($machines as $index => $machine) :
                $slug    = $machine['slug'] ?? '';
                $name    = $machine['short_name'] ?? ($machine['name'] ?? '');
                $is_box  = $slug === 'bg7-box-gutter';
                $kind    = $is_box ? __('Box Gutter', 'standard') : __('K-Style', 'standard');
                $ordinal = sprintf('%02d / %s', $index + 1, $name);
            ?>
                <a
                    href="#machii-variant-<?php echo esc_attr($slug); ?>"
                    class="machii-portrait__tile group relative flex flex-col bg-blue-900 transition-colors duration-200 hover:bg-blue-800 focus-visible:bg-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px]"
                    data-machine-slug="<?php echo esc_attr($slug); ?>"
                >
                    <div class="relative aspect-square overflow-hidden bg-blue-800">
                        <?php \Standard\Images\responsive_image(
                            $machine['image'] ?? '',
                            $name,
                            'product-card',
                            [
                                'class'   => 'absolute inset-0 w-full h-full object-contain p-6 transition-transform duration-300 ease-out group-hover:scale-[1.02] group-focus-visible:scale-[1.02] lg:p-10',
                                'loading' => 'lazy',
                            ]
                        ); ?>
                        <?php if (!empty($machine['featured']) || !empty($machine['badge'])) : ?>
                            <span class="absolute top-3 left-3 badge badge-emphasis">
                                <?php echo esc_html(!empty($machine['badge']) ? $machine['badge'] : __('Featured', 'standard')); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="grid gap-2 border-t border-blue-700 p-5 lg:p-6">
                        <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-300">
                            <?php echo esc_html($ordinal); ?>
                        </p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-500">
                            <?php echo esc_html($kind); ?>
                        </p>
                        <p class="text-sm text-blue-200 leading-snug">
                            <?php echo esc_html($machine['descriptor'] ?? ''); ?>
                        </p>
                        <p class="font-mono text-xs text-blue-500 mt-1 flex items-center gap-1">
                            <?php esc_html_e('See specs', 'standard'); ?>
                            <?php icon('arrow-down', ['class' => 'w-3.5 h-3.5']); ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
