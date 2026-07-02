<?php
/**
 * Trailer Page — Engineered for the Job (the seven reasons)
 *
 * The detail beat after the vs-traditional hook. The seven engineering
 * points from the source article (#7688), presented as a numbered blueprint
 * list, with three real supporting photos (NATM sticker, stabilizing jack,
 * trailer in the field) breaking the rhythm so it never reads as a flat
 * card grid.
 *
 * Numbers earn their place here: this is genuinely an ordered set of seven
 * named reasons from the article, not decorative section scaffolding.
 *
 * NOTE: these specs (12,000 lb class, 750 lb tongue weight) describe NTM's
 * trailer engineering philosophy and the article's reference trailer. The
 * sellable TR23/TR23G models (23,000 lb) live in the models section with
 * their own real specs; do not conflate the two.
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$reasons = [
    [
        'spec'  => __('Capacity', 'standard'),
        'title' => __('Tandem axles, sized for the load', 'standard'),
        'text'  => __('A 12,000 lb capacity on tandem axles, with strategic reinforcement points that support and balance the machine for safe transport.', 'standard'),
    ],
    [
        'spec'  => __('Roof access', 'standard'),
        'title' => __('Crane lifting eyes at every corner', 'standard'),
        'text'  => __('Lift the machine straight from the trailer to the roof line, which saves real time when you\'re running long panel lengths up high.', 'standard'),
    ],
    [
        'spec'  => __('Braking', 'standard'),
        'title' => __('Electronic brakes and a breakaway kit', 'standard'),
        'text'  => __('Safety first in transit: electronic brakes plus a breakaway kit keep the machine secure if the worst happens on the road.', 'standard'),
    ],
    [
        'spec'  => __('Compliance', 'standard'),
        'title' => __('NATM compliant', 'standard'),
        'text'  => __('Built in compliance with National Association of Trailer Manufacturers (NATM) standards, the quality and safety benchmark business owners look for.', 'standard'),
    ],
    [
        'spec'  => __('Cleanup', 'standard'),
        'title' => __('Metal scrap trays at the exit ends', 'standard'),
        'text'  => __('Shearing scrap drops into trays instead of onto the jobsite, which keeps the work area clean and safe.', 'standard'),
    ],
    [
        'spec'  => __('Balance', 'standard'),
        'title' => __('A light 750 lb loaded tongue weight', 'standard'),
        'text'  => __('Engineered for road stability so the loaded trailer tracks straight behind the truck instead of fighting you.', 'standard'),
    ],
    [
        'spec'  => __('Stability', 'standard'),
        'title' => __('Four drop-foot stabilizing jacks', 'standard'),
        'text'  => __('One at each corner steadies the trailer during loading and unloading, when a shifting deck is most dangerous.', 'standard'),
    ],
];

$photos = [
    [
        'src' => content_url('/uploads/2023/09/NATM-Compliant-trailer-sticker.png'),
        'alt' => __('NATM compliance sticker on an NTM trailer', 'standard'),
    ],
    [
        'src' => content_url('/uploads/2023/09/trailer-stabilizing-jack.png'),
        'alt' => __('A drop-foot stabilizing jack on the corner of an NTM trailer', 'standard'),
    ],
];
?>

<section id="trailer-engineering" class="section scroll-mt-24" aria-labelledby="trailer-engineering-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Seven reasons it costs what it costs', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="trailer-engineering-title" class="section-title text-balance">
                <?php esc_html_e('Engineering You Pay for Once and Use Every Haul', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-pretty">
                <?php esc_html_e('Every line item below is a deliberate choice, not a generic trailer\'s default. Together they are why the trailer is a line on the quote instead of an afterthought.', 'standard'); ?>
            </p>
        </div>

        <div class="grid gap-10 lg:grid-cols-[1.4fr_1fr] lg:gap-16 lg:items-start">

            <!-- The seven reasons, numbered blueprint list -->
            <ol role="list" class="border-t border-blue-200 stagger">
                <?php foreach ($reasons as $i => $reason) : ?>
                    <li class="grid grid-cols-[auto_1fr] gap-x-5 gap-y-1 border-b border-blue-200 py-6">
                        <span class="row-span-2 font-mono text-sm text-red" aria-hidden="true">
                            <?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?>
                        </span>
                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                            <h3 class="font-sans font-medium text-lg text-blue-900 leading-tight">
                                <?php echo esc_html($reason['title']); ?>
                            </h3>
                            <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                                <?php echo esc_html($reason['spec']); ?>
                            </span>
                        </div>
                        <p class="font-sans text-[15px] leading-relaxed text-blue-600 max-w-prose">
                            <?php echo esc_html($reason['text']); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ol>

            <!-- Supporting photography in light blueprint wells -->
            <div class="grid gap-px bg-blue-200 border border-blue-200 lg:sticky lg:top-24">
                <?php foreach ($photos as $photo) : ?>
                    <figure class="bg-blue-50 overflow-hidden" data-reveal="image">
                        <img
                            src="<?php echo esc_url($photo['src']); ?>"
                            alt="<?php echo esc_attr($photo['alt']); ?>"
                            loading="lazy"
                            decoding="async"
                            class="w-full aspect-[4/3] object-cover"
                        >
                    </figure>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</section>
