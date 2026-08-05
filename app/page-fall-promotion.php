<?php
/**
 * Shared Fall 2026 promotion landing-page renderer.
 *
 * WordPress loads this file through the two page-slug wrappers. Keeping the
 * layout shared makes the campaign feel related while each offer keeps its
 * own dates, proof points, eligible products, and legal copy.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$promotion_key = isset($fall_promotion_key) ? sanitize_key((string) $fall_promotion_key) : '';
$sales_url     = 'https://outlook.office.com/book/NTMSales@mazzellacompanies0.onmicrosoft.com/';
$configurator  = Url\internal('/configurator/');

$configurator_callout = [
    'kicker' => __('NTM online configurator', 'standard'),
    'title'  => __('Build, price, and finance your machine in one simple flow.', 'standard'),
    'copy'   => __('Choose the machine and options you need, see itemized pricing, and start the Corbel financing application.', 'standard'),
    'points' => [
        __('Choose your machine, profiles, and accessories', 'standard'),
        __('See clear, itemized pricing as you build', 'standard'),
        __('Move from your quote into the financing application', 'standard'),
    ],
];

$promotions = [
    'no-payments' => [
        'kicker'      => __('Fall 2026 financing offer · August 7–September 30', 'standard'),
        'title_lines' => [
            __('Get the machine now.', 'standard'),
            __('No payments until 2027.', 'standard'),
        ],
        'subtitle'    => __('Purchase a portable roof panel or seamless gutter machine from August 7 to September 30, 2026, and make no payments for up to six months—with no down payment required.', 'standard'),
        'financing_partner' => [
            'logo' => content_url('/uploads/2026/07/corbel-preferred-lender-300x158.png'),
            'name' => __('Corbel', 'standard'),
            'text' => __('Any machine purchases financed through our partner, Corbel, will be eligible for no payments until 2027.', 'standard'),
        ],
        'notice'      => __('Offer starts August 7 and ends September 30, 2026.', 'standard'),
        'primary'     => __('Build and finance your machine', 'standard'),
        'image'        => content_url('/uploads/2026/05/ntm-customer-onsite-001.jpg'),
        'image_alt'    => __('NTM customer operating a portable roof panel machine on a jobsite', 'standard'),
        'image_frame'  => 'aspect-video overflow-hidden bg-blue-800',
        'image_class'  => 'h-full w-full object-cover',
        'image_width'  => 2560,
        'image_height' => 1440,
        'meta'        => [
            ['label' => __('Payments', 'standard'), 'value' => __('Deferred up to 6 months', 'standard')],
            ['label' => __('Down payment', 'standard'), 'value' => __('None required', 'standard')],
            ['label' => __('Offer ends', 'standard'), 'value' => __('September 30', 'standard')],
        ],
        'benefit_eyebrow' => __('Keep cash working', 'standard'),
        'benefit_title'   => __('Put the machine to work before the first payment.', 'standard'),
        'benefit_copy'    => __('Add production capacity now while keeping more cash available for material, labor, and the next job.', 'standard'),
        'benefits'        => [
            [
                'icon'  => 'dollar-sign',
                'title' => __('Free up cash flow', 'standard'),
                'text'  => __('Preserve working capital while the new machine starts producing.', 'standard'),
            ],
            [
                'icon'  => 'calendar',
                'title' => __('Take on more jobs now', 'standard'),
                'text'  => __('Add capacity during the busy season without waiting for a later budget cycle.', 'standard'),
            ],
            [
                'icon'  => 'clock',
                'title' => __('Increase uptime', 'standard'),
                'text'  => __('Get production running sooner and keep the machine working on revenue-producing jobs.', 'standard'),
            ],
            [
                'icon'  => 'trending-up',
                'title' => __('Maximize ROI', 'standard'),
                'text'  => __('Use the payment-free window to put the machine into service and generate revenue sooner.', 'standard'),
            ],
        ],
        'steps' => [
            [
                'title' => __('Configure your machine', 'standard'),
                'text'  => __('Choose a portable roof panel or seamless gutter machine and the options your work requires.', 'standard'),
            ],
            [
                'title' => __('Apply through Corbel', 'standard'),
                'text'  => __('Submit the financing application from the configurator. Approval and final terms come from Corbel.', 'standard'),
            ],
            [
                'title' => __('Start producing', 'standard'),
                'text'  => __('Put the machine to work now and defer payments for up to six months.', 'standard'),
            ],
        ],
        'steps_title_lines' => [
            __('Three steps.', 'standard'),
            __('No fine-print maze.', 'standard'),
        ],
        'steps_sales_cta' => true,
        'eligible_eyebrow' => __('Eligible equipment', 'standard'),
        'eligible_title'   => __('Choose the machine that fits your business.', 'standard'),
        'eligible_copy'    => __('The offer applies to new NTM portable roof panel and seamless gutter machine orders financed through Corbel.', 'standard'),
        'roof_product' => [
            'product_slug' => 'ssq3-multipro',
            'eyebrow'      => __('NTM flagship · Class of 2026', 'standard'),
            'headline'     => __('The Most Advanced Portable Rollformer Ever Built', 'standard'),
            'subhead'      => __('SSQ3™ MultiPro is the next generation of portable rollforming.', 'standard'),
            'bullets'      => [
                __('16 panel profiles', 'standard'),
                __('Gas or electric power', 'standard'),
                __('On-board RFID profile recognition', 'standard'),
                __('Changeovers in half the time', 'standard'),
            ],
        ],
        'gutter_family' => true,
        'configurator' => $configurator_callout,
        'closing_title' => __('Secure the machine. Keep your cash through the rest of 2026.', 'standard'),
        'closing_copy'  => __('Build your machine and apply for financing using our configurator, or speak with an account manager today to take advantage of this special limited-time offer.', 'standard'),
        'disclaimer'    => __('Offer available to qualified U.S. and Canada buyers only. Subject to credit approval. Limited to NTM portable rollforming machine orders placed between August 7, 2026 and September 30, 2026. Financing is provided by Corbel. Final payment terms are set by the lender.', 'standard'),
    ],
    'second-profile' => [
        'kicker'      => __('Fall 2026 profile offer · August 5–September 7', 'standard'),
        'title'       => __('Add a second profile for 50% off.', 'standard'),
        'subtitle'    => __('Customers who purchase a roofing machine from August 5 through Labor Day, September 7, 2026, will receive 50% off a second profile for that machine.', 'standard'),
        'hero_note'   => __('Choose from residential or commercial, depending on your machine package.', 'standard'),
        'notice'      => __('Limited-time offer: August 5 through Labor Day, September 7, 2026.', 'standard'),
        'primary'     => __('Build your roof panel machine', 'standard'),
        'image'        => content_url('/uploads/2026/03/SSQ3_OL_0226-product.png'),
        'image_alt'    => __('NTM SSQ3 MultiPro roof and wall panel machine', 'standard'),
        'image_frame'  => 'aspect-square overflow-hidden bg-white p-5 sm:p-8',
        'image_class'  => 'h-full w-full object-contain',
        'image_width'  => 1000,
        'image_height' => 1000,
        'meta'        => [
            ['label' => __('Second profile', 'standard'), 'value' => __('50% off', 'standard')],
            ['label' => __('Eligible machines', 'standard'), 'value' => '3'],
            ['label' => __('Offer ends', 'standard'), 'value' => __('September 7', 'standard')],
        ],
        'benefit_eyebrow' => __('One machine. More capability.', 'standard'),
        'benefit_title'   => __('Take more panel work without buying another machine.', 'standard'),
        'benefit_copy'    => __('A second profile gives one machine more ways to serve your market. Add the compatible profile at half price during the offer window.', 'standard'),
        'benefits'        => [
            [
                'icon'  => 'dollar-sign',
                'title' => __('Save money on a second profile', 'standard'),
                'text'  => __('Get a compatible residential or commercial profile for 50% off.', 'standard'),
            ],
            [
                'icon'  => 'settings',
                'title' => __('Expand your market with more profile options', 'standard'),
                'text'  => __('Run more residential or commercial panel styles from the same portable machine platform.', 'standard'),
            ],
            [
                'icon'  => 'trending-up',
                'title' => __('Be the metal panel leader in your area with the best machine and profiles', 'standard'),
                'text'  => __('Pair an NTM machine with more profile options to serve a wider range of metal panel projects.', 'standard'),
            ],
        ],
        'steps' => [
            [
                'title' => __('Choose an eligible machine', 'standard'),
                'text'  => __('Choose an SSR MultiPro Jr. Roof Panel Machine, SSH MultiPro Roof Panel Machine, or SSQ3 MultiPro Roof & Wall Panel Machine.', 'standard'),
            ],
            [
                'title' => __('Select your profiles', 'standard'),
                'text'  => __('Choose a primary profile, then add a compatible residential or commercial second profile.', 'standard'),
            ],
            [
                'title' => __('Get 50% off the second', 'standard'),
                'text'  => __('Place the new machine order by September 7, 2026, to receive the promotion.', 'standard'),
            ],
        ],
        'eligible_eyebrow' => __('Eligible machines', 'standard'),
        'eligible_title'   => __('Three MultiPro machines. More ways to grow.', 'standard'),
        'eligible_copy'    => __('Profile compatibility depends on the machine package. An NTM account manager can confirm the right combination for your work.', 'standard'),
        'products'         => [
            'ssr-multipro-jr-roof-panel-machine',
            'ssh-roof-panel-machine',
            'ssq3-multipro',
        ],
        'configurator' => $configurator_callout,
        'closing_title' => __('Add more capability for half the usual profile cost.', 'standard'),
        'closing_copy'  => __('Build an eligible machine or talk through profile compatibility with an NTM account manager before Labor Day.', 'standard'),
        'disclaimer'    => __('Select an additional profile compatible with the machine being ordered (residential or commercial). Offer available to qualified buyers only. May be subject to credit approval. Limited to new NTM portable panel machine orders placed between August 5, 2026 and September 7, 2026.', 'standard'),
    ],
];

if (!isset($promotions[$promotion_key])) {
    status_header(404);
    nocache_headers();
    include get_query_template('404');
    return;
}

$promotion = $promotions[$promotion_key];

get_header();

while (have_posts()) :
    the_post();
    ?>

    <main id="primary" class="fall-promotion">
        <aside class="border-b border-blue-400 bg-blue-500 px-4 py-3 text-center text-sm font-medium text-white" aria-label="<?php esc_attr_e('Promotion dates', 'standard'); ?>">
            <?php echo esc_html($promotion['notice']); ?>
        </aside>

        <section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="promotion-hero-title">
            <div class="container py-14 md:py-20 lg:py-24">
                <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
                    <div class="grid content-start gap-6 lg:col-span-7 lg:gap-8">
                        <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                            <?php echo esc_html($promotion['kicker']); ?>
                        </p>
                        <h1 id="promotion-hero-title" class="max-w-4xl text-balance text-4xl font-semibold leading-tight tracking-tight text-white md:text-5xl lg:text-6xl">
                            <?php if (!empty($promotion['title_lines'])) : ?>
                                <?php foreach ($promotion['title_lines'] as $line) : ?>
                                    <span class="block"><?php echo esc_html($line); ?></span>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php echo esc_html($promotion['title']); ?>
                            <?php endif; ?>
                        </h1>
                        <p class="max-w-2xl text-lg leading-relaxed text-blue-200 lg:text-xl">
                            <?php echo esc_html($promotion['subtitle']); ?>
                        </p>
                        <?php if (!empty($promotion['hero_note'])) : ?>
                            <p class="max-w-2xl border-l-2 border-blue-400 pl-4 text-base leading-relaxed text-blue-100">
                                <?php echo esc_html($promotion['hero_note']); ?>
                            </p>
                        <?php endif; ?>
                        <div class="flex flex-col gap-4 sm:flex-row">
                            <a href="<?php echo esc_url($configurator); ?>" class="btn btn-primary w-full sm:w-auto" target="_blank" rel="noopener">
                                <?php echo esc_html($promotion['primary']); ?>
                                <?php icon('arrow-right', ['class' => 'h-5 w-5']); ?>
                            </a>
                            <a href="<?php echo esc_url($sales_url); ?>" class="btn btn-outline-light w-full sm:w-auto" target="_blank" rel="noopener">
                                <?php esc_html_e('Talk to an account manager', 'standard'); ?>
                            </a>
                        </div>
                        <dl class="grid max-w-2xl grid-cols-3 gap-px border border-white/15 bg-white/15">
                            <?php foreach ($promotion['meta'] as $item) : ?>
                                <div class="grid content-start gap-1 bg-blue-900/90 p-3 sm:p-4">
                                    <dt class="font-mono text-[10px] uppercase tracking-widest text-blue-300"><?php echo esc_html($item['label']); ?></dt>
                                    <dd class="text-sm font-medium leading-tight text-white sm:text-base"><?php echo esc_html($item['value']); ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>

                    <figure class="relative m-0 lg:col-span-5">
                        <div class="absolute -inset-3 border border-blue-400/30" aria-hidden="true"></div>
                        <div class="relative <?php echo esc_attr($promotion['image_frame']); ?>">
                            <img
                                src="<?php echo esc_url($promotion['image']); ?>"
                                alt="<?php echo esc_attr($promotion['image_alt']); ?>"
                                class="<?php echo esc_attr($promotion['image_class']); ?>"
                                width="<?php echo esc_attr((string) $promotion['image_width']); ?>"
                                height="<?php echo esc_attr((string) $promotion['image_height']); ?>"
                                loading="eager"
                                fetchpriority="high"
                                decoding="async"
                            >
                        </div>
                    </figure>
                </div>
            </div>
        </section>

        <section class="section border-b border-blue-200 bg-blue-50" aria-labelledby="promotion-benefits-title">
            <div class="container section-content">
                <header class="section-header-left max-w-3xl">
                    <p class="section-eyebrow"><?php echo esc_html($promotion['benefit_eyebrow']); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="promotion-benefits-title" class="section-title"><?php echo esc_html($promotion['benefit_title']); ?></h2>
                    <p class="section-subtitle max-w-2xl"><?php echo esc_html($promotion['benefit_copy']); ?></p>
                </header>

                <?php $benefit_grid = count($promotion['benefits']) === 4 ? 'md:grid-cols-2 lg:grid-cols-4' : 'md:grid-cols-3'; ?>
                <ul class="grid gap-px border border-blue-200 bg-blue-200 <?php echo esc_attr($benefit_grid); ?>" role="list">
                    <?php foreach ($promotion['benefits'] as $benefit) : ?>
                        <li class="grid content-start gap-4 bg-white p-6 lg:p-8">
                            <span class="flex h-12 w-12 items-center justify-center bg-blue-100 text-blue-600" aria-hidden="true">
                                <?php icon($benefit['icon'], ['class' => 'h-6 w-6']); ?>
                            </span>
                            <h3 class="text-xl font-medium leading-tight text-blue-900"><?php echo esc_html($benefit['title']); ?></h3>
                            <p class="text-base leading-relaxed text-blue-600"><?php echo esc_html($benefit['text']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <section class="section border-b border-blue-800 bg-blue-900 text-white" aria-labelledby="promotion-steps-title">
            <div class="container">
                <div class="grid gap-12 lg:grid-cols-[minmax(0,0.75fr)_minmax(0,1.25fr)] lg:gap-16">
                    <header class="section-header-left max-w-xl content-start">
                        <p class="section-eyebrow text-blue-300"><?php esc_html_e('How the offer works', 'standard'); ?></p>
                        <div class="section-divider"></div>
                        <h2 id="promotion-steps-title" class="section-title text-white">
                            <?php if (!empty($promotion['steps_title_lines'])) : ?>
                                <?php foreach ($promotion['steps_title_lines'] as $line) : ?>
                                    <span class="block"><?php echo esc_html($line); ?></span>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php esc_html_e('Three steps. No fine-print maze.', 'standard'); ?>
                            <?php endif; ?>
                        </h2>
                        <p class="text-lg leading-relaxed text-blue-200"><?php esc_html_e('Start online, then get help from a real account manager if you need it.', 'standard'); ?></p>
                        <?php if (!empty($promotion['steps_sales_cta'])) : ?>
                            <div>
                                <a href="<?php echo esc_url($sales_url); ?>" class="btn btn-outline-light w-full sm:w-auto" target="_blank" rel="noopener">
                                    <?php esc_html_e('Talk to an account manager', 'standard'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </header>

                    <ol class="grid gap-px border border-white/15 bg-white/15" role="list">
                        <?php foreach ($promotion['steps'] as $index => $step) : ?>
                            <li class="grid grid-cols-[auto_1fr] gap-5 bg-blue-900 p-5 sm:p-6">
                                <span class="font-mono text-sm font-medium text-blue-300" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                                <div class="grid gap-2">
                                    <h3 class="text-xl font-medium text-white"><?php echo esc_html($step['title']); ?></h3>
                                    <p class="text-base leading-relaxed text-blue-200"><?php echo esc_html($step['text']); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section border-b border-blue-200 bg-white" aria-labelledby="promotion-eligible-title">
            <div class="container section-content">
                <header class="section-header-left max-w-3xl">
                    <p class="section-eyebrow"><?php echo esc_html($promotion['eligible_eyebrow']); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="promotion-eligible-title" class="section-title"><?php echo esc_html($promotion['eligible_title']); ?></h2>
                    <p class="section-subtitle max-w-2xl"><?php echo esc_html($promotion['eligible_copy']); ?></p>
                </header>

                <?php if (!empty($promotion['roof_product']) && !empty($promotion['gutter_family'])) : ?>
                    <?php
                    $roof_product = get_page_by_path($promotion['roof_product']['product_slug'], OBJECT, 'product');
                    $roof_detail  = MachineProductData\get_machine_product_data($promotion['roof_product']['product_slug']);
                    $roof_image   = $roof_detail['hero']['flagship_image']
                        ?? $roof_detail['hero']['hero_image']
                        ?? $roof_detail['hero']['image']
                        ?? ($roof_product instanceof \WP_Post ? get_the_post_thumbnail_url($roof_product, 'large') : '');
                    $roof_stats   = array_slice($roof_detail['stats'] ?? [], 0, 3);

                    $gutter_machines = array_values(array_filter(
                        MachinesData\get_gutter_machines(),
                        static fn (array $machine): bool => str_starts_with((string) ($machine['slug'] ?? ''), 'mach-ii-')
                    ));
                    usort($gutter_machines, static function (array $a, array $b): int {
                        return ((int) !empty($b['featured'])) <=> ((int) !empty($a['featured']));
                    });
                    ?>

                    <div class="grid gap-10 lg:gap-12">
                        <?php if ($roof_product instanceof \WP_Post) : ?>
                            <div class="grid gap-4">
                                <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-600">
                                    <?php esc_html_e('Featured roof and wall machine', 'standard'); ?>
                                </p>
                                <article class="grid overflow-hidden bg-blue-900 text-white lg:grid-cols-2 lg:items-center">
                                    <div class="grid gap-4 bg-blue-800">
                                        <div class="relative aspect-video overflow-hidden lg:aspect-[4/3]">
                                            <span class="absolute left-0 top-0 z-10 inline-flex bg-red px-3 py-2 font-mono text-xs font-medium uppercase tracking-wider text-white">
                                                <?php esc_html_e('Flagship', 'standard'); ?>
                                            </span>
                                        <?php if ($roof_image) : ?>
                                                <?php Images\responsive_image(
                                                    $roof_image,
                                                    __('SSQ3 MultiPro flagship roof and wall panel machine', 'standard'),
                                                    'large',
                                                    [
                                                        'class'   => 'absolute inset-0 h-full w-full object-cover',
                                                        'loading' => 'lazy',
                                                    ]
                                                ); ?>
                                        <?php endif; ?>
                                        </div>

                                        <?php if (!empty($roof_stats)) : ?>
                                            <dl class="grid grid-cols-3 border-y border-blue-700" aria-label="<?php esc_attr_e('SSQ3 key specifications', 'standard'); ?>">
                                                <?php foreach ($roof_stats as $index => $stat) : ?>
                                                    <div class="px-3 py-3 <?php echo $index > 0 ? 'border-l border-blue-700' : ''; ?>">
                                                        <dt class="mb-1 font-mono text-[10px] uppercase tracking-wider text-blue-300"><?php echo esc_html($stat['label']); ?></dt>
                                                        <dd class="font-mono text-sm font-medium text-white lg:text-base"><?php echo esc_html($stat['value']); ?></dd>
                                                    </div>
                                                <?php endforeach; ?>
                                            </dl>
                                        <?php endif; ?>
                                    </div>

                                    <div class="grid content-center gap-6 p-7 lg:p-10 xl:p-12">
                                        <p class="font-mono text-xs uppercase tracking-wider text-blue-300"><?php echo esc_html($promotion['roof_product']['eyebrow']); ?></p>
                                        <h3 class="text-3xl font-medium leading-tight tracking-tight text-white md:text-4xl lg:text-5xl"><?php echo esc_html($promotion['roof_product']['headline']); ?></h3>
                                        <p class="text-lg font-medium leading-snug text-blue-100"><?php echo esc_html($promotion['roof_product']['subhead']); ?></p>
                                        <ul class="grid gap-3" role="list">
                                            <?php foreach ($promotion['roof_product']['bullets'] as $bullet) : ?>
                                                <li class="flex items-start gap-3 text-base leading-relaxed text-blue-200">
                                                    <span class="shrink-0 text-blue-400" aria-hidden="true">&bull;</span>
                                                    <span><?php echo esc_html($bullet); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div>
                                            <a href="<?php echo esc_url(get_permalink($roof_product)); ?>" class="btn btn-primary">
                                                <?php esc_html_e('Explore the SSQ3', 'standard'); ?>
                                                <?php icon('arrow-right', ['class' => 'h-5 w-5']); ?>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($gutter_machines)) : ?>
                            <div class="grid gap-4">
                                <div class="grid gap-2">
                                    <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-600">
                                        <?php esc_html_e('Featured seamless gutter machines', 'standard'); ?>
                                    </p>
                                    <h3 class="text-2xl font-medium tracking-tight text-blue-900">
                                        <?php esc_html_e('Three MACH II K-style configurations.', 'standard'); ?>
                                    </h3>
                                </div>

                                <div class="machii-portrait grid grid-cols-1 gap-px border border-blue-200 bg-blue-200 sm:grid-cols-3">
                                    <?php foreach ($gutter_machines as $index => $machine) : ?>
                                        <?php
                                        $slug    = (string) ($machine['slug'] ?? '');
                                        $name    = (string) ($machine['short_name'] ?? ($machine['name'] ?? ''));
                                        $ordinal = sprintf('%02d', $index + 1);
                                        ?>
                                        <a
                                            href="<?php echo esc_url((string) ($machine['url'] ?? '')); ?>"
                                            class="machii-portrait__tile group relative flex flex-col bg-white transition-colors duration-200 hover:bg-blue-50 focus-visible:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px]"
                                            data-machine-slug="<?php echo esc_attr($slug); ?>"
                                        >
                                            <div class="relative aspect-square overflow-hidden bg-blue-50">
                                                <?php Images\responsive_image(
                                                    $machine['image'] ?? '',
                                                    $name,
                                                    'product-card',
                                                    [
                                                        'class'   => 'absolute inset-0 w-full h-full object-contain p-6 transition-transform duration-300 ease-out group-hover:scale-[1.02] group-focus-visible:scale-[1.02] lg:p-10',
                                                        'loading' => 'lazy',
                                                    ]
                                                ); ?>
                                                <?php if (!empty($machine['featured']) || !empty($machine['badge'])) : ?>
                                                    <span class="absolute left-0 top-0 inline-flex bg-red px-3 py-2 font-mono text-xs font-medium uppercase tracking-wider text-white">
                                                        <?php esc_html_e('Featured', 'standard'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="grid gap-3 border-t border-blue-200 p-5 lg:p-6">
                                                <div class="flex items-center gap-3 font-mono text-[10px] uppercase tracking-[0.18em] text-blue-500">
                                                    <span class="text-blue-600"><?php echo esc_html($ordinal); ?></span>
                                                    <span class="h-px w-6 bg-blue-300" aria-hidden="true"></span>
                                                    <span><?php esc_html_e('K-Style', 'standard'); ?></span>
                                                </div>
                                                <h4 class="text-xl font-medium leading-tight tracking-tight text-blue-900 lg:text-2xl"><?php echo esc_html($name); ?></h4>
                                                <p class="text-sm leading-snug text-blue-700"><?php echo esc_html($machine['descriptor'] ?? ''); ?></p>
                                                <p class="mt-1 flex items-center gap-1 font-mono text-xs text-blue-500">
                                                    <?php esc_html_e('View machine', 'standard'); ?>
                                                    <?php icon('arrow-right', ['class' => 'h-3.5 w-3.5']); ?>
                                                </p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif (!empty($promotion['categories'])) : ?>
                    <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-2">
                        <?php foreach ($promotion['categories'] as $category) : ?>
                            <?php
                            $featured_product = get_page_by_path($category['product_slug'], OBJECT, 'product');
                            if (!$featured_product instanceof \WP_Post) {
                                continue;
                            }
                            $featured_image = get_the_post_thumbnail_url($featured_product, 'large');
                            ?>
                            <article class="grid content-between gap-6 bg-white p-5 lg:p-6">
                                <div class="grid gap-5">
                                    <div class="aspect-video overflow-hidden bg-blue-50 p-4 sm:p-6">
                                        <?php if ($featured_image) : ?>
                                            <img src="<?php echo esc_url($featured_image); ?>" alt="" class="h-full w-full object-contain" width="800" height="450" loading="lazy" decoding="async">
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="text-2xl font-medium tracking-tight text-blue-900"><?php echo esc_html($category['title']); ?></h3>
                                    <p class="text-base leading-relaxed text-blue-600"><?php echo esc_html($category['text']); ?></p>
                                </div>
                                <a href="<?php echo esc_url(get_permalink($featured_product)); ?>" class="inline-flex items-center gap-2 font-mono text-sm font-medium uppercase tracking-wider text-blue-600 no-underline hover:text-blue-500">
                                    <?php esc_html_e('View featured machine', 'standard'); ?>
                                    <?php icon('arrow-right', ['class' => 'h-4 w-4']); ?>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (!empty($promotion['products'])) : ?>
                    <div class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
                        <?php foreach ($promotion['products'] as $product_slug) : ?>
                            <?php
                            $product_post = get_page_by_path($product_slug, OBJECT, 'product');
                            if (!$product_post instanceof \WP_Post) {
                                continue;
                            }
                            $product_image = get_the_post_thumbnail_url($product_post, 'large');
                            ?>
                            <article class="grid content-between gap-6 bg-white p-5 lg:p-6">
                                <div class="grid gap-5">
                                    <div class="aspect-square overflow-hidden bg-blue-50 p-4">
                                        <?php if ($product_image) : ?>
                                            <img src="<?php echo esc_url($product_image); ?>" alt="" class="h-full w-full object-contain" width="600" height="600" loading="lazy" decoding="async">
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="text-xl font-medium leading-tight text-blue-900"><?php echo esc_html(get_the_title($product_post)); ?></h3>
                                </div>
                                <a href="<?php echo esc_url(get_permalink($product_post)); ?>" class="inline-flex items-center gap-2 font-mono text-sm font-medium uppercase tracking-wider text-blue-600 no-underline hover:text-blue-500">
                                    <?php esc_html_e('View machine', 'standard'); ?>
                                    <?php icon('arrow-right', ['class' => 'h-4 w-4']); ?>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php if (!empty($promotion['configurator'])) : ?>
            <section class="section border-b border-blue-800 bg-blue-900 text-white" aria-labelledby="promotion-configurator-title">
                <div class="container">
                    <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-16">
                        <div class="grid content-start gap-6 lg:col-span-6">
                            <p class="section-eyebrow text-blue-300"><?php echo esc_html($promotion['configurator']['kicker']); ?></p>
                            <div class="section-divider"></div>
                            <h2 id="promotion-configurator-title" class="section-title text-white"><?php echo esc_html($promotion['configurator']['title']); ?></h2>
                            <p class="text-lg leading-relaxed text-blue-200"><?php echo esc_html($promotion['configurator']['copy']); ?></p>
                            <?php if (!empty($promotion['financing_partner'])) : ?>
                                <div class="grid grid-cols-[auto_1fr] items-center gap-4 border border-white/15 bg-white/5 p-3 sm:p-4">
                                    <img
                                        src="<?php echo esc_url($promotion['financing_partner']['logo']); ?>"
                                        alt="<?php echo esc_attr($promotion['financing_partner']['name']); ?>"
                                        class="h-auto w-24 sm:w-28"
                                        width="300"
                                        height="158"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <p class="text-sm leading-relaxed text-blue-100">
                                        <?php echo esc_html($promotion['financing_partner']['text']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <ul class="grid gap-3" role="list">
                                <?php foreach ($promotion['configurator']['points'] as $point) : ?>
                                    <li class="grid grid-cols-[auto_1fr] items-start gap-3 text-base text-blue-100">
                                        <span class="mt-0.5 text-blue-300" aria-hidden="true"><?php icon('check', ['class' => 'h-5 w-5']); ?></span>
                                        <span><?php echo esc_html($point); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div>
                                <a href="<?php echo esc_url($configurator); ?>" class="btn btn-primary btn--commit w-full sm:w-auto" target="_blank" rel="noopener">
                                    <?php esc_html_e('Open the configurator', 'standard'); ?>
                                    <?php icon('arrow-right', ['class' => 'h-5 w-5']); ?>
                                </a>
                            </div>
                        </div>

                        <figure class="m-0 lg:col-span-6">
                            <div class="border border-white/15 bg-blue-800 p-3 sm:p-4">
                                <img
                                    src="<?php echo esc_url(THEME_URI . '/assets/images/config-mockup.png'); ?>"
                                    alt="<?php esc_attr_e('NTM online machine configurator showing machine options and itemized pricing', 'standard'); ?>"
                                    class="h-auto w-full"
                                    width="2613"
                                    height="1634"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                            <figcaption class="mt-3 font-mono text-xs uppercase tracking-widest text-blue-300">
                                <?php esc_html_e('Build · Quote · Finance', 'standard'); ?>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="section bg-blue-50" aria-labelledby="promotion-close-title">
            <div class="container">
                <div class="grid gap-8 border-t-4 border-blue-500 bg-white p-6 md:p-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end lg:gap-12">
                    <div class="grid max-w-3xl gap-4">
                        <p class="section-eyebrow"><?php esc_html_e('Limited time', 'standard'); ?></p>
                        <h2 id="promotion-close-title" class="text-3xl font-medium leading-tight tracking-tight text-blue-900 lg:text-4xl"><?php echo esc_html($promotion['closing_title']); ?></h2>
                        <p class="text-lg leading-relaxed text-blue-600"><?php echo esc_html($promotion['closing_copy']); ?></p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                        <a href="<?php echo esc_url($configurator); ?>" class="btn btn-primary w-full whitespace-nowrap" target="_blank" rel="noopener">
                            <?php echo esc_html($promotion['primary']); ?>
                            <?php icon('arrow-right', ['class' => 'h-5 w-5']); ?>
                        </a>
                        <a href="<?php echo esc_url($sales_url); ?>" class="btn btn-outline w-full whitespace-nowrap" target="_blank" rel="noopener">
                            <?php esc_html_e('Talk to an account manager', 'standard'); ?>
                        </a>
                    </div>
                </div>

                <p class="mx-auto mt-8 max-w-5xl text-center text-xs leading-relaxed text-blue-600">
                    <strong><?php esc_html_e('Offer terms:', 'standard'); ?></strong>
                    <?php echo esc_html($promotion['disclaimer']); ?>
                </p>
            </div>
        </section>
    </main>

    <?php
endwhile;

get_footer();
