<?php
/**
 * MACH II Family — Family Portrait
 *
 * Light section. Three MACH II machines (5", 6", 5"/6" Combo) laid
 * out on a single grid with mono labels under each silhouette.
 * Tapping a tile scrolls to that machine's spread in variant-matrix
 * via the hash. Hover gives a subtle border shift, the CSS-only
 * affordance from DESIGN.md §8.8.
 *
 * Grid is 1 col mobile, 3 cols lg+ (three machines), with 1px
 * hairline dividers via gap-px on a blue-200 grid background. The
 * page-wide single red ignite moment lives on final-cta.php only.
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

// MACH II family only. BG7 lives on /seamless-gutter-machines/.
$machines = array_values(array_filter(
    get_gutter_machines(),
    static fn (array $m): bool => str_starts_with((string) ($m['slug'] ?? ''), 'mach-ii-')
));

// Featured (Combo) first; stable sort preserves the rest of the
// machines-data.php order behind it.
usort($machines, static function (array $a, array $b): int {
    return ((int) !empty($b['featured'])) <=> ((int) !empty($a['featured']));
});

if (empty($machines)) {
    return;
}
?>

<section
    class="section bg-blue-50 border-t border-blue-200 hidden md:block"
    aria-labelledby="machii-family-title"
>
    <div class="container section-content">
        <div class="section-header">
            <p class="section-eyebrow">
                <?php esc_html_e('The Family', 'standard'); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="machii-family-title" class="section-title">
                <?php esc_html_e('Three K-style configurations.', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-600 max-w-2xl mx-auto">
                <?php esc_html_e('5", 6", and the 5"/6" Combo. Every MACH II runs polyurethane drive rollers and is built for crews who treat their gutter machine like a member of payroll.', 'standard'); ?>
            </p>
        </div>

        <div class="machii-portrait grid grid-cols-1 gap-px bg-blue-200 border border-blue-200 sm:grid-cols-3">
            <?php foreach ($machines as $index => $machine) :
                $slug    = $machine['slug'] ?? '';
                $name    = $machine['short_name'] ?? ($machine['name'] ?? '');
                $kind    = __('K-Style', 'standard');
                $ordinal = sprintf('%02d / %s', $index + 1, $name);
            ?>
                <a
                    href="#machii-variant-<?php echo esc_attr($slug); ?>"
                    class="machii-portrait__tile group relative flex flex-col bg-white transition-colors duration-200 hover:bg-blue-50 focus-visible:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px]"
                    data-machine-slug="<?php echo esc_attr($slug); ?>"
                >
                    <div class="relative aspect-square overflow-hidden bg-blue-50">
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
                            <span class="absolute top-3 right-3 badge badge-emphasis">
                                <?php echo esc_html(!empty($machine['badge']) ? $machine['badge'] : __('Featured', 'standard')); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="grid gap-2 border-t border-blue-200 p-5 lg:p-6">
                        <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-600">
                            <?php echo esc_html($ordinal); ?>
                        </p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-blue-500">
                            <?php echo esc_html($kind); ?>
                        </p>
                        <p class="text-sm text-blue-700 leading-snug">
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
