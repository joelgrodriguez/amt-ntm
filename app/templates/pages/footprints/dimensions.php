<?php
/**
 * Footprints — dimensions quick reference.
 *
 * Machine and on-trailer dimensions for the active lineup, grouped the
 * same way as /machines (Roof & Wall, then Gutter). Every value comes
 * straight from the per-machine spec files in app/data/machines/ — the
 * same source the product-page blueprint section renders — so this
 * section can never drift from the spec sheets. Machines without
 * dimension data are omitted rather than padded with placeholders;
 * dormant machines are excluded upstream by get_machine_categories().
 *
 * Mobile-first: single-column cards at base, two columns at md:,
 * three at xl:. Spec values are mono per the typography system.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_machine_categories;
use function Standard\MachineProductData\get_machine_product_data;

/**
 * Map a raw dimensions row to label => value display pairs.
 */
$build_dims = static function (array $raw): array {
    $out = [];
    if (!empty($raw['length'])) {
        $out[__('Length', 'standard')] = (string) $raw['length'];
    }
    if (!empty($raw['width'])) {
        $out[__('Width', 'standard')] = (string) $raw['width'];
    }
    if (!empty($raw['height'])) {
        $out[__('Height', 'standard')] = (string) $raw['height'];
    }
    if (!empty($raw['weight'])) {
        $out[__('Weight', 'standard')] = (string) $raw['weight'];
    }
    return $out;
};

$groups = [];

foreach (get_machine_categories() as $category) {
    $machines = [];

    foreach ($category['machines'] as $machine) {
        $slug = (string) ($machine['slug'] ?? '');
        $data = $slug !== '' ? get_machine_product_data($slug) : null;
        $dims = $data['specs']['dimensions'] ?? [];

        $machine_dims = $build_dims($dims['machine'] ?? []);
        if (empty($machine_dims)) {
            continue;
        }

        $blocks = [
            ['label' => __('Machine', 'standard'), 'dims' => $machine_dims],
        ];

        $trailer_dims = $build_dims($dims['on_trailer'] ?? []);
        if (!empty($trailer_dims)) {
            $blocks[] = ['label' => __('On Trailer', 'standard'), 'dims' => $trailer_dims];
        }

        $url = (string) ($machine['url'] ?? '');

        $machines[] = [
            'name'   => (string) ($machine['short_name'] ?? $machine['name'] ?? $slug),
            'url'    => ($url !== '' && $url !== '#') ? $url : '',
            'blocks' => $blocks,
        ];
    }

    if (!empty($machines)) {
        $groups[] = [
            'label'    => (string) ($category['label'] ?? ''),
            'machines' => $machines,
        ];
    }
}

if (empty($groups)) {
    return;
}
?>

<section id="footprint-dimensions" class="section bg-blue-50 border-t border-blue-200" aria-labelledby="footprint-dimensions-title">
    <div class="container section-content">

        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow"><?php esc_html_e('Quick Reference', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="footprint-dimensions-title" class="section-title">
                <?php esc_html_e('Dimensions at a Glance', 'standard'); ?>
            </h2>
            <p class="section-subtitle">
                <?php esc_html_e('Length, width, height, and weight for every machine in the current lineup — the machine alone, and loaded on the trailer where that applies. For the full plan view, open the footprint drawing above.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-12 lg:gap-16">
            <?php foreach ($groups as $group) : ?>
                <div class="grid gap-6 lg:gap-8">

                    <p class="m-0 flex items-center gap-3 font-mono text-xs font-medium uppercase tracking-widest text-blue-500">
                        <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                        <?php echo esc_html($group['label']); ?>
                    </p>

                    <ul class="grid gap-6 lg:gap-8 md:grid-cols-2 xl:grid-cols-3 list-none p-0 m-0">
                        <?php foreach ($group['machines'] as $row) : ?>
                            <li class="flex flex-col bg-white border border-blue-200">

                                <h3 class="m-0 border-b border-blue-200 p-5 lg:p-6 font-sans text-lg font-medium text-blue-900">
                                    <?php echo esc_html($row['name']); ?>
                                </h3>

                                <?php foreach ($row['blocks'] as $i => $block) : ?>
                                    <div class="grid gap-4 p-5 lg:p-6<?php echo $i > 0 ? ' border-t border-blue-200' : ''; ?>">
                                        <p class="m-0 flex items-baseline gap-2 font-mono text-xs font-medium uppercase tracking-wider text-blue-500">
                                            <span><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                                            <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                                            <span><?php echo esc_html($block['label']); ?></span>
                                        </p>
                                        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 m-0">
                                            <?php foreach ($block['dims'] as $label => $value) : ?>
                                                <div>
                                                    <dt class="font-mono text-xs uppercase tracking-wider text-blue-500"><?php echo esc_html($label); ?></dt>
                                                    <dd class="m-0 mt-1 font-mono text-base font-medium text-blue-900"><?php echo esc_html($value); ?></dd>
                                                </div>
                                            <?php endforeach; ?>
                                        </dl>
                                    </div>
                                <?php endforeach; ?>

                                <?php if ($row['url'] !== '') : ?>
                                    <a
                                        href="<?php echo esc_url($row['url']); ?>"
                                        class="mt-auto flex items-center justify-between gap-2 border-t border-blue-200 px-5 lg:px-6 py-3 font-mono text-xs font-medium uppercase tracking-wider text-blue-700 no-underline hover:text-blue-500 transition-colors duration-200"
                                    >
                                        <?php esc_html_e('View machine', 'standard'); ?>
                                        <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
                                    </a>
                                <?php endif; ?>

                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
