<?php
/**
 * MACH II Family — Variant Matrix
 *
 * White section. Each MACH II variant rendered as a self-contained
 * spec spread: photograph + descriptor + highlights + mini-spec
 * grid + dual CTAs. Spreads alternate image-left/image-right for
 * rhythm. No sticky nav: at three machines, anchor jumping isn't
 * worth the chrome and the family-portrait tiles above already act
 * as the picker. All variant primaries use btn-primary (blue); the
 * page's one red-CTA ignite moment lives on final-cta.php, per
 * PRODUCT.md's 10% rule.
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
use function Standard\MachinesData\get_product_price;

// MACH II family only. BG7 lives on /seamless-gutter-machines/.
$machines = array_values(array_filter(
    get_gutter_machines(),
    static fn (array $m): bool => str_starts_with((string) ($m['slug'] ?? ''), 'mach-ii-')
));

// Featured (Combo) first; the buyer's most-recommended pick leads.
usort($machines, static function (array $a, array $b): int {
    return ((int) !empty($b['featured'])) <=> ((int) !empty($a['featured']));
});

if (empty($machines)) {
    return;
}
?>

<section
    class="section bg-white border-t border-blue-200"
    aria-labelledby="machii-variants-title"
>
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php esc_html_e('Pick a Model', 'standard'); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="machii-variants-title" class="section-title">
                <?php esc_html_e('Three MACH II machines. Pick yours.', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-600 max-w-2xl">
                <?php esc_html_e('Specs, highlights, and price for each machine. Pick the one your crew runs most days, or talk to a specialist if you\'re between configurations.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-16 lg:gap-24">
            <?php foreach ($machines as $index => $machine) :
                $slug        = $machine['slug'] ?? '';
                $name        = $machine['name'] ?? '';
                $short       = $machine['short_name'] ?? $name;
                $descriptor  = $machine['descriptor'] ?? '';
                $description = $machine['description'] ?? '';
                $image       = $machine['image'] ?? '';
                $highlights  = $machine['highlights'] ?? [];
                $specs       = $machine['specs'] ?? [];
                $is_featured = !empty($machine['featured']);

                $price = !empty($machine['price'])
                    ? $machine['price']
                    : ($slug !== '' ? get_product_price($slug) : null);

                $configurator_slug = $machine['configurator_slug'] ?? '';
                $build_url = $configurator_slug !== ''
                    ? \Standard\Url\internal('/configurator/' . $configurator_slug . '/')
                    : \Standard\Url\with_query('/build-finance/', ['machine' => $slug]);
                $explore_url = $machine['url'] ?? '#';

                $spec_rows = [];
                if (!empty($specs['size']))      { $spec_rows[] = [__('Size',      'standard'), $specs['size']]; }
                if (!empty($specs['profiles'])) { $spec_rows[] = [__('Profile',   'standard'), $specs['profiles']]; }
                if (!empty($specs['speed']))    { $spec_rows[] = [__('Speed',     'standard'), $specs['speed']]; }
                if (!empty($specs['drive']))    { $spec_rows[] = [__('Drive',     'standard'), $specs['drive']]; }
                if (!empty($specs['lead_time'])) { $spec_rows[] = [__('Lead Time', 'standard'), $specs['lead_time']]; }
                if (!empty($specs['best_for'])) { $spec_rows[] = [__('Best For',  'standard'), $specs['best_for']]; }
            ?>
                <article
                    id="machii-variant-<?php echo esc_attr($slug); ?>"
                    class="machii-variant scroll-mt-20 grid gap-10 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center"
                >
                    <div class="<?php echo $index % 2 === 1 ? 'md:order-2' : ''; ?>">
                        <div class="relative aspect-square bg-blue-50 border border-blue-200">
                            <?php if ($image) : ?>
                                <?php \Standard\Images\responsive_image($image, $name, 'product-card', [
                                    'class'   => 'absolute inset-0 w-full h-full object-contain p-8 lg:p-12',
                                    'loading' => 'lazy',
                                ]); ?>
                            <?php endif; ?>
                            <span class="absolute top-4 left-4 font-mono text-[10px] uppercase tracking-[0.18em] text-blue-500">
                                <?php echo esc_html(sprintf('%02d / %s', $index + 1, $short)); ?>
                            </span>
                            <?php if ($is_featured) : ?>
                                <span class="absolute top-4 right-4 badge badge-emphasis">
                                    <?php esc_html_e('Featured', 'standard'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid gap-6 content-start <?php echo $index % 2 === 1 ? 'md:order-1' : ''; ?>">
                        <div class="grid gap-2">
                            <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-500">
                                <?php echo esc_html($descriptor); ?>
                            </p>
                            <h3 class="font-sans font-medium tracking-tight text-blue-900 text-3xl md:text-4xl">
                                <?php echo esc_html($short); ?>
                            </h3>
                        </div>

                        <?php if ($description !== '') : ?>
                            <p class="text-blue-600 text-base md:text-lg max-w-xl">
                                <?php echo esc_html($description); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($highlights)) : ?>
                            <ul class="grid gap-2.5" role="list">
                                <?php foreach ($highlights as $highlight) : ?>
                                    <li class="flex gap-3 text-blue-700">
                                        <span aria-hidden="true" class="font-mono text-xs text-blue-500 mt-1.5 shrink-0">→</span>
                                        <span><?php echo esc_html($highlight); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($spec_rows) || $price) : ?>
                            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 border-t border-blue-200 pt-5 mt-2 sm:grid-cols-3">
                                <?php if ($price) : ?>
                                    <div class="grid gap-0.5 col-span-2 sm:col-span-1">
                                        <dt class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-600">
                                            <?php esc_html_e('Starting at', 'standard'); ?>
                                        </dt>
                                        <dd class="font-medium text-blue-900 text-lg">
                                            <?php echo esc_html($price); ?>
                                        </dd>
                                    </div>
                                <?php endif; ?>
                                <?php foreach ($spec_rows as $row) : ?>
                                    <div class="grid gap-0.5">
                                        <dt class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-600">
                                            <?php echo esc_html($row[0]); ?>
                                        </dt>
                                        <dd class="font-mono text-sm text-blue-700">
                                            <?php echo esc_html($row[1]); ?>
                                        </dd>
                                    </div>
                                <?php endforeach; ?>
                            </dl>
                        <?php endif; ?>

                        <div class="flex flex-wrap gap-3 pt-2">
                            <a href="<?php echo esc_url($build_url); ?>" class="btn btn-primary">
                                <?php esc_html_e('Build & Finance', 'standard'); ?>
                                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                            </a>
                            <a href="<?php echo esc_url(\Standard\Url\internal($explore_url)); ?>" class="btn btn-outline-dark">
                                <?php esc_html_e('View Full Specs', 'standard'); ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
