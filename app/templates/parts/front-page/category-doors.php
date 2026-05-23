<?php
/**
 * Category Doors — Front Page
 *
 * Two image-led cells routing the buyer to the category landing pages:
 * Roof & Wall Panel Machines and Seamless Gutter Machines. Replaces
 * the earlier Explore Machines tabbed product scroller — the front
 * page asks "roof or gutter?" first; the SKU-level browse lives on
 * the category landings.
 *
 * Accessories are intentionally NOT a third door. They live in the
 * hero slider's accessories slide, which is enough surface for a
 * cross-sell category at the top of the funnel.
 *
 * Each cell:
 *   - Landscape photo, lazy-loaded.
 *   - Dark scrim (44% black, matches .hero-overlay) anchored bottom,
 *     carrying the mono category label + machine count + arrow.
 *   - Whole cell is a single <a> — the tap target is the cell, way
 *     over WCAG 2.5.5's 44x44 minimum.
 *
 * No visible eyebrow / heading / subtitle. The doors carry the
 * communication; an sr-only <h2> handles the landmark.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see   templates/parts/front-page/flagships.php (visual signature)
 * @see   resources/css/components/hero-overlay.css (shared scrim)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Woo\Catalog\get_products_by_category;

$doors = [
    [
        'slug'  => 'roof-wall-panel-machines',
        'label' => __('Roof & Wall Panel Machines', 'standard'),
        'url'   => '/machines/roof-wall-panel-machines/',
        'image' => content_url('/uploads/2026/05/ntm-q3-hero-placeholder-2.png'),
        'alt'   => __('Roof and wall panel machine running on a job site', 'standard'),
    ],
    [
        'slug'  => 'gutter-machines',
        'label' => __('Seamless Gutter Machines', 'standard'),
        'url'   => '/machines/gutter-machines/',
        'image' => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-mach2-gutter-install-abel-002.jpg',
        'alt'   => __('Seamless gutter machine running on a job site', 'standard'),
    ],
];
?>

<section class="bg-white" aria-labelledby="category-doors-title">
    <h2 id="category-doors-title" class="sr-only">
        <?php esc_html_e('Choose a machine category', 'standard'); ?>
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <?php foreach ($doors as $i => $door) :
            $machine_count = count(get_products_by_category($door['slug']));
            $is_first      = $i === 0;
            // Seam between cells: hairline only. Bottom border on the
            // first cell when stacked (mobile); right border on the
            // first cell when side-by-side (lg+).
            $seam_class = $is_first
                ? 'border-b border-blue-200 lg:border-b-0 lg:border-r'
                : '';
        ?>
            <a
                href="<?php echo esc_url(\Standard\Url\internal($door['url'])); ?>"
                class="category-door group relative block overflow-hidden <?php echo esc_attr($seam_class); ?>"
            >
                <!-- Photo. Aspect ratios: 16:9 on mobile, 3:2 on lg+ so
                     the cells stay visually weighted on a 50/50 split. -->
                <div class="aspect-video lg:aspect-[3/2]">
                    <img
                        src="<?php echo esc_url($door['image']); ?>"
                        alt="<?php echo esc_attr($door['alt']); ?>"
                        loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover block transition-transform duration-300"
                    >
                </div>

                <!-- Scrim: bottom-anchored, dark-to-transparent. The
                     hero-overlay sits at 42% black across the full
                     box; here we only need the bottom third tinted
                     for label legibility, hence a gradient instead of
                     the solid overlay class. -->
                <div
                    class="category-door__scrim absolute inset-0 pointer-events-none transition-opacity duration-300"
                    aria-hidden="true"
                ></div>

                <!-- Label rail: mono category + machine count + arrow.
                     Pinned bottom-left so the photo carries the upper
                     two-thirds without interference. -->
                <div class="absolute inset-x-0 bottom-0 z-10 p-6 lg:p-8 flex items-end justify-between gap-4 text-white">
                    <div class="grid gap-2">
                        <span class="font-mono text-[11px] uppercase tracking-[0.18em] text-blue-100">
                            <?php
                            if ($machine_count > 0) {
                                printf(
                                    /* translators: %d = number of machines in this category */
                                    esc_html(_n('%d Machine', '%d Machines', $machine_count, 'standard')),
                                    (int) $machine_count
                                );
                            } else {
                                esc_html_e('View Machines', 'standard');
                            }
                            ?>
                        </span>
                        <span class="font-sans font-medium text-2xl md:text-3xl lg:text-4xl leading-tight tracking-tight">
                            <?php echo esc_html($door['label']); ?>
                        </span>
                    </div>

                    <span class="shrink-0 inline-flex items-center justify-center w-12 h-12 border border-white/40 transition-transform duration-300 group-hover:translate-x-1">
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
