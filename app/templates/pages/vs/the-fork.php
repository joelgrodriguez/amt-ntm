<?php
/**
 * Roof Panel vs Gutter — The Fork
 *
 * The decisive section. Two routing panels side by side: roof & wall
 * panel machines vs seamless gutter machines. Each panel answers the
 * three questions a confused first-timer actually has — what does it
 * make, who runs it, what does it cost — names the lead machines, then
 * routes to that category page.
 *
 * A single full-height hairline divides the two panels on desktop (the
 * blueprint "fork"); they stack with a horizontal hairline on mobile.
 * Content copy is grounded in the live catalog and the two category
 * pages this routes to.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Entry prices resolve from each family's entry machine data file
// (schema.low_price) so the fork can never drift from the product pages.
$roof_from   = \Standard\MachinesData\get_from_price('ssr-multipro-jr');
$gutter_from = \Standard\MachinesData\get_from_price('mach-ii-5-gutter');

$lanes = [
    [
        'eyebrow'   => __('Lane A', 'standard'),
        'title'     => __('Roof &amp; Wall Panel Machines', 'standard'),
        'makes'     => __('Form long metal panels for roofs and walls: standing seam roofing, flush wall, soffit, and board &amp; batten siding. Coil feeds into the machine, then finished panels come out cut to the exact job length.', 'standard'),
        'for'       => __('Roofers, metal panel installers, and exterior contractors who want to stop buying panels from a supplier and start fabricating their own.', 'standard'),
        'makes_list' => [
            __('Standing seam metal roof panels', 'standard'),
            __('Flush wall &amp; soffit panels', 'standard'),
            __('Board &amp; batten siding', 'standard'),
        ],
        'machines'  => __('SSQ3™ MultiPro, SSH™, SSR™, 5V Crimp, WAV™', 'standard'),
        'price'     => sprintf(/* translators: %s: roof-family entry price, e.g. $44,900 */ __('From %s', 'standard'), $roof_from),
        'price_note' => __('SSR™ entry machine · up to 16 profiles', 'standard'),
        'url'       => '/roof-wall-panel-machines/',
        'cta'       => __('View roof &amp; wall machines', 'standard'),
        'image'     => content_url('/uploads/2021/03/rollforming-machine-on-roof.jpg'),
        'image_alt' => __('A portable roof panel machine forming panels on a rooftop jobsite', 'standard'),
    ],
    [
        'eyebrow'   => __('Lane B', 'standard'),
        'title'     => __('Seamless Gutter Machines', 'standard'),
        'makes'     => __('Form continuous seamless gutters for each building: K-style, box gutters, and combo 5&Prime;/6&Prime; runs. Coil feeds into the machine, then finished gutter comes out cut to the exact job length for each edge.', 'standard'),
        'for'       => __('Gutter contractors, exterior crews, and roofers adding a gutter line. Lower entry cost makes it a common first machine.', 'standard'),
        'makes_list' => [
            __('Seamless K-style gutters (5&Prime;, 6&Prime;)', 'standard'),
            __('5&Prime;/6&Prime; combo runs from one machine', 'standard'),
            __('Box gutters (BG7)', 'standard'),
        ],
        'machines'  => __('MACH II™ 5&Prime;, MACH II™ 6&Prime;, MACH II™ Combo, BG7', 'standard'),
        'price'     => sprintf(/* translators: %s: gutter-family entry price, e.g. $9,800 */ __('From %s', 'standard'), $gutter_from),
        'price_note' => __('MACH II™ 5&Prime; · benchmark since 1994', 'standard'),
        'url'       => '/seamless-gutter-machines/',
        'cta'       => __('View seamless gutter machines', 'standard'),
        'image'     => content_url('/uploads/2021/03/nasser-miter-saw.png'),
        'image_alt' => __('A gutter machine setup with a miter saw for jobsite gutter work', 'standard'),
    ],
];
?>

<section id="the-fork" class="section scroll-mt-24" aria-labelledby="the-fork-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Two machines, two jobs', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="the-fork-title" class="section-title">
                <?php esc_html_e('Start with What You Make', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('The fastest way to know which machine is yours is to look at the product, not the spec sheet. Find the lane that matches the work you do, then step into the catalog for that family.', 'standard'); ?>
            </p>
        </div>

        <div class="grid border border-blue-200 md:grid-cols-2">
            <?php foreach ($lanes as $i => $lane) :
                // Left/top panel gets no divider; the right/bottom panel
                // carries the single hairline so the two never double-fence.
                $divider = $i === 1 ? 'border-t border-blue-200 md:border-t-0 md:border-l' : '';
            ?>
                <div class="flex flex-col gap-6 p-6 md:p-8 lg:p-10 <?php echo esc_attr($divider); ?>">

                    <div class="aspect-video overflow-hidden border border-blue-200 bg-blue-100">
                        <?php
                        \Standard\Images\responsive_image(
                            $lane['image'],
                            $lane['image_alt'],
                            'large',
                            ['class' => 'h-full w-full object-cover']
                        );
                        ?>
                    </div>

                    <div class="grid gap-3">
                        <p class="font-mono text-xs uppercase tracking-mono-label text-blue-500">
                            <?php echo esc_html($lane['eyebrow']); ?>
                        </p>
                        <h3 class="font-sans text-2xl font-medium tracking-tight text-balance text-blue-900 lg:text-3xl">
                            <?php echo wp_kses($lane['title'], ['br' => []]); ?>
                        </h3>
                    </div>

                    <div class="grid gap-1">
                        <p class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-400">
                            <?php esc_html_e('What it makes', 'standard'); ?>
                        </p>
                        <p class="text-base text-blue-600 text-pretty">
                            <?php echo wp_kses_post($lane['makes']); ?>
                        </p>
                    </div>

                    <ul class="grid gap-2">
                        <?php foreach ($lane['makes_list'] as $item) : ?>
                            <li class="flex items-start gap-2 text-sm text-blue-700">
                                <span class="mt-0.5 shrink-0 text-blue-500" aria-hidden="true">
                                    <?php icon('check', ['class' => 'w-4 h-4']); ?>
                                </span>
                                <span><?php echo wp_kses_post($item); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="grid gap-1 border-t border-blue-200 pt-5">
                        <p class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-400">
                            <?php esc_html_e('Who runs it', 'standard'); ?>
                        </p>
                        <p class="text-sm text-blue-600 text-pretty">
                            <?php echo esc_html($lane['for']); ?>
                        </p>
                    </div>

                    <dl class="grid grid-cols-2 gap-x-6 gap-y-4 border-t border-blue-200 pt-5">
                        <div class="grid gap-1 min-w-0">
                            <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-400">
                                <?php esc_html_e('Starting price', 'standard'); ?>
                            </dt>
                            <dd class="font-mono text-lg text-blue-900"><?php echo esc_html($lane['price']); ?></dd>
                            <dd class="font-mono text-[11px] text-blue-400"><?php echo wp_kses_post($lane['price_note']); ?></dd>
                        </div>
                        <div class="grid gap-1 min-w-0">
                            <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-400">
                                <?php esc_html_e('NTM machines', 'standard'); ?>
                            </dt>
                            <dd class="font-mono text-sm text-blue-700 break-words"><?php echo wp_kses_post($lane['machines']); ?></dd>
                        </div>
                    </dl>

                    <div class="mt-auto pt-2">
                        <a href="<?php echo esc_url(\Standard\Url\internal($lane['url'])); ?>" class="btn btn-primary w-full justify-center sm:w-auto">
                            <?php echo wp_kses_post($lane['cta']); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
