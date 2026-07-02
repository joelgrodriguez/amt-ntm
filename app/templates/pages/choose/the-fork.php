<?php
/**
 * Choose Your Machine — The Fork
 *
 * Two routing panels that split the lineup the way the buyer's work does:
 * roof & wall panel machines vs seamless gutter machines. Unlike the vs
 * page (which routes OUT to category pages), this fork routes DOWN the same
 * page to that family's fit ledger, because the chooser keeps the decision
 * on one surface. Each lane's entry price is derived from the catalog by the
 * page template (roof_from / gutter_from in choose/data.php) and passed in,
 * so it can never drift from the ledger below or the product pages.
 *
 * Built from the vs/the-fork cell grammar: one hairline-fenced grid, the
 * second panel carries the single divider so the two never double-fence.
 *
 * @package Standard
 *
 * @param string $roof_from   Lowest roof-family "From" price (e.g. "$44,900").
 * @param string $gutter_from Lowest gutter-family "From" price (e.g. "$9,800").
 *
 * @usage Choose Your Machine (page-choose-your-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Derived in the page template from the assembled catalog; the fallback
// resolves the entry machine's data-file price (schema.low_price) so even
// the last resort can't drift from the product pages.
$roof_from   = !empty($args['roof_from']) ? $args['roof_from'] : \Standard\MachinesData\get_from_price('ssr-multipro-jr');
$gutter_from = !empty($args['gutter_from']) ? $args['gutter_from'] : \Standard\MachinesData\get_from_price('mach-ii-5-gutter');

$lanes = [
    [
        'eyebrow'    => __('Roof & wall panel', 'standard'),
        'title'      => __('You make metal roofs &amp; walls', 'standard'),
        'makes'      => __('Standing seam roofing, flush wall and soffit, board &amp; batten siding, 5V crimp, and WAV panels. Coil feeds in, finished panels come out cut to job length.', 'standard'),
        'count'      => __('6 machines', 'standard'),
        'price'      => $roof_from,
        'price_note' => __('SSR™ MultiPro Jr. · up to 16 profiles on the flagship', 'standard'),
        'target'     => '#roof-ledger',
        'cta'        => __('See the 6 roof &amp; wall machines', 'standard'),
    ],
    [
        'eyebrow'    => __('Seamless gutter', 'standard'),
        'title'      => __('You make seamless gutter', 'standard'),
        'makes'      => __('Seamless K-style gutter in 5&Prime; and 6&Prime;, combo runs from one machine, and commercial box gutter. Coil feeds in, finished gutter comes out cut to each edge.', 'standard'),
        'count'      => __('4 machines', 'standard'),
        'price'      => $gutter_from,
        'price_note' => __('MACH II™ 5&Prime; · the benchmark since 1994', 'standard'),
        'target'     => '#gutter-ledger',
        'cta'        => __('See the 4 gutter machines', 'standard'),
    ],
];
?>

<section id="the-fork" class="section scroll-mt-24" aria-labelledby="the-fork-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Start with the product', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="the-fork-title" class="section-title text-balance">
                <?php esc_html_e('What Do You Make?', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Every NTM machine forms one of two things. Pick the side that matches your jobsite and jump to that family, then read each machine by the work it suits.', 'standard'); ?>
            </p>
        </div>

        <div class="grid border border-blue-200 md:grid-cols-2">
            <?php foreach ($lanes as $i => $lane) :
                $divider = $i === 1 ? 'border-t border-blue-200 md:border-t-0 md:border-l' : '';
            ?>
                <div class="flex flex-col gap-6 p-6 md:p-8 lg:p-10 <?php echo esc_attr($divider); ?>">

                    <div class="grid gap-3">
                        <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                            <?php echo esc_html($lane['eyebrow']); ?>
                        </p>
                        <h3 class="font-sans text-2xl font-medium tracking-tight text-balance text-blue-900 lg:text-3xl">
                            <?php echo wp_kses($lane['title'], ['br' => []]); ?>
                        </h3>
                    </div>

                    <p class="text-base text-blue-600 text-pretty">
                        <?php echo wp_kses_post($lane['makes']); ?>
                    </p>

                    <dl class="grid grid-cols-2 gap-x-6 gap-y-4 border-t border-blue-200 pt-5">
                        <div class="grid gap-1 min-w-0">
                            <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-600">
                                <?php esc_html_e('In this family', 'standard'); ?>
                            </dt>
                            <dd class="font-mono text-lg text-blue-900"><?php echo esc_html($lane['count']); ?></dd>
                        </div>
                        <div class="grid gap-1 min-w-0">
                            <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-600">
                                <?php esc_html_e('Starting price', 'standard'); ?>
                            </dt>
                            <dd class="font-mono text-lg text-blue-900">
                                <?php
                                /* translators: %s: lowest "from" price, e.g. $9,800. */
                                printf(esc_html__('From %s', 'standard'), esc_html($lane['price']));
                                ?>
                            </dd>
                            <dd class="font-mono text-[11px] text-blue-600"><?php echo wp_kses_post($lane['price_note']); ?></dd>
                        </div>
                    </dl>

                    <div class="mt-auto pt-2">
                        <a href="<?php echo esc_attr($lane['target']); ?>" class="btn btn-primary w-full justify-center sm:w-auto">
                            <?php echo wp_kses_post($lane['cta']); ?>
                            <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
