<?php
/**
 * Roof Panel vs Gutter — Side-by-Side Comparison
 *
 * A category-level comparison (roof & wall vs gutter), not a
 * machine-vs-machine spec table — the shared comparison-table part
 * compares machines within one family, which would misframe this page.
 *
 * Renders as a real <table> for SEO and screen readers: two machine
 * families as columns, the buyer's actual questions as rows. On mobile
 * it stays a two-column table (only two columns, so it fits) with a
 * sticky label rail.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$columns = [
    [
        'label' => __('Roof &amp; Wall Panel', 'standard'),
        'url'   => '/roof-wall-panel-machines/',
    ],
    [
        'label' => __('Seamless Gutter', 'standard'),
        'url'   => '/seamless-gutter-machines/',
    ],
];

// Each row: label, then a value per column (in column order).
$rows = [
    [
        'label'  => __('What it makes', 'standard'),
        'values' => [
            __('Standing seam roof, flush wall &amp; board &amp; batten panels', 'standard'),
            __('Seamless K-style &amp; box gutters', 'standard'),
        ],
    ],
    [
        'label'  => __('Best for', 'standard'),
        'values' => [
            __('Roofers &amp; metal panel installers', 'standard'),
            __('Gutter &amp; exterior contractors', 'standard'),
        ],
    ],
    [
        'label'  => __('Profiles / sizes', 'standard'),
        'values' => [
            __('Up to 16 panel profiles', 'standard'),
            __('5&Prime;, 6&Prime;, 5&Prime;/6&Prime; combo, box', 'standard'),
        ],
    ],
    [
        'label'  => __('NTM machines', 'standard'),
        'values' => [
            __('SSQ3™ · SSH™ · SSR™ · 5V Crimp · WAV™', 'standard'),
            __('MACH II™ 5&Prime; · 6&Prime; · Combo · BG7', 'standard'),
        ],
    ],
    [
        'label'  => __('Starting price', 'standard'),
        'values' => [
            __('$44,900', 'standard'),
            __('$9,800', 'standard'),
        ],
    ],
    [
        'label'  => __('Typical payback', 'standard'),
        'values' => [
            __('1–2 years', 'standard'),
            __('Within the first year', 'standard'),
        ],
    ],
];
?>

<section class="section bg-blue-50 border-t border-blue-200" aria-labelledby="vs-comparison-title">
    <div class="container section-content">

        <div class="flex flex-wrap items-end justify-between gap-4">
            <h2 id="vs-comparison-title" class="section-title m-0">
                <?php esc_html_e('The Two Families, Side by Side', 'standard'); ?>
            </h2>
            <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-600 m-0">
                <?php esc_html_e('At a glance', 'standard'); ?>
            </p>
        </div>

        <table class="w-full table-fixed border-collapse border border-blue-200 bg-white text-sm" aria-labelledby="vs-comparison-title">
            <caption class="sr-only">
                <?php esc_html_e('Roof &amp; wall panel machines compared with seamless gutter machines.', 'standard'); ?>
            </caption>
            <thead>
                <tr>
                    <th class="w-24 border-r border-blue-700 bg-blue-800 px-3 py-4 text-left align-bottom text-xs font-medium uppercase tracking-mono-meta text-blue-200 sm:w-44 sm:px-4">
                        <?php esc_html_e('Compare', 'standard'); ?>
                    </th>
                    <?php foreach ($columns as $i => $col) : ?>
                        <th scope="col" class="border-blue-700 bg-blue-800 px-3 py-4 text-left align-bottom font-medium text-white sm:px-4 <?php echo $i === 0 ? 'border-r' : ''; ?>">
                            <a href="<?php echo esc_url(\Standard\Url\internal($col['url'])); ?>" class="text-white no-underline transition-colors hover:text-blue-200">
                                <?php echo wp_kses_post($col['label']); ?>
                            </a>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) : ?>
                    <tr class="border-t border-blue-200">
                        <th scope="row" class="border-r border-blue-200 bg-white px-3 py-4 text-left align-top font-mono text-[11px] uppercase tracking-normal text-blue-500 sm:tracking-mono-meta sm:px-4">
                            <?php echo wp_kses_post($row['label']); ?>
                        </th>
                        <?php foreach ($row['values'] as $i => $value) : ?>
                            <td class="px-3 py-4 align-top text-blue-700 sm:px-4 <?php echo $i === 0 ? 'border-r border-blue-200' : ''; ?>">
                                <?php echo wp_kses_post($value); ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                <tr class="border-t border-blue-200">
                    <td class="border-r border-blue-200 bg-white px-3 py-4 sm:px-4"></td>
                    <?php foreach ($columns as $i => $col) : ?>
                        <td class="px-3 py-4 align-top sm:px-4 <?php echo $i === 0 ? 'border-r border-blue-200' : ''; ?>">
                            <?php
                            // Disambiguate for screen readers: every CTA reads
                            // "Explore" otherwise. Decode entities (&amp;, &Prime;)
                            // to plain text for the accessible name.
                            $family = html_entity_decode(wp_strip_all_tags($col['label']), ENT_QUOTES, 'UTF-8');
                            ?>
                            <a
                                href="<?php echo esc_url(\Standard\Url\internal($col['url'])); ?>"
                                class="btn btn-outline-dark w-full justify-center"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: machine family name */ __('Explore %s machines', 'standard'), $family)); ?>"
                            >
                                <?php esc_html_e('Explore', 'standard'); ?>
                            </a>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>

    </div>
</section>
