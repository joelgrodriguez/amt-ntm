<?php
/**
 * Reusable Trailer Strip
 *
 * Compact industrial spec-sheet band shown on compatible machine product pages,
 * directly after the accessories section. Surfaces the trailer(s) that fit this
 * machine and routes to the full /machines/trailer/ story. Data-driven and
 * gated: it renders nothing for machines with no compatible trailer (MACH II),
 * so callers can include it unconditionally.
 *
 * Mobile-first: a single-column stack of trailer rows; at md: rows lay out as a
 * spec-sheet grid. Every touch target clears 44px.
 *
 * @package Standard
 *
 * @var array{product?: \WC_Product, slug?: string} $args
 *
 * @usage single-machine.php, single-machine-default.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Resolve the machine slug from either an explicit slug or the product object.
$slug = $args['slug'] ?? '';
if ($slug === '' && isset($args['product']) && $args['product'] instanceof \WC_Product) {
    $slug = $args['product']->get_slug();
}

if ($slug === '' || !function_exists('Standard\\TrailerData\\get_trailers_for_machine')) {
    return;
}

$trailers = \Standard\TrailerData\get_trailers_for_machine($slug);

// Empty for MACH II and anything else off the compatibility map — the gate.
if (empty($trailers)) {
    return;
}

$multiple = count($trailers) > 1;
?>

<section class="section-compact bg-blue-900" aria-labelledby="machine-trailer-strip-title">
    <div class="container">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,20rem)_1fr] lg:gap-12 lg:items-start">

            <div class="section-header-left">
                <p class="section-eyebrow"><?php esc_html_e('Get it to the jobsite', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="machine-trailer-strip-title" class="font-sans text-2xl lg:text-3xl font-semibold tracking-tight text-white text-balance">
                    <?php echo $multiple
                        ? esc_html__('Trailers Built for This Machine', 'standard')
                        : esc_html__('The Trailer Built for This Machine', 'standard'); ?>
                </h2>
                <p class="section-subtitle text-blue-300 text-pretty">
                    <?php esc_html_e('The trailer is engineered around the machine, not adapted to it. Sold separately.', 'standard'); ?>
                </p>
                <a href="<?php echo esc_url(\Standard\Url\internal('/machines/trailer/')); ?>" class="font-mono text-xs font-medium uppercase tracking-wider text-blue-200 hover:text-white inline-flex items-center gap-1 w-fit">
                    <?php esc_html_e('See the trailer lineup', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                </a>
            </div>

            <div class="grid gap-px border border-blue-700 bg-blue-700">
                <?php foreach ($trailers as $trailer) : ?>
                    <div class="grid gap-4 bg-blue-900 p-5 md:grid-cols-[1fr_auto] md:items-center md:gap-6 md:p-6">

                        <div class="grid gap-2 min-w-0">
                            <div class="flex items-baseline gap-3">
                                <span class="font-mono font-medium text-sm uppercase tracking-mono-label text-white">
                                    <?php echo esc_html($trailer['model']); ?>
                                </span>
                                <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                                    <?php echo esc_html($trailer['capacity']); ?> &middot; <?php echo esc_html($trailer['axle']); ?>
                                </span>
                            </div>
                            <p class="font-sans text-sm leading-relaxed text-blue-200 max-w-prose">
                                <?php echo esc_html($trailer['summary']); ?>
                            </p>
                        </div>

                        <div class="flex items-center justify-between gap-4 md:flex-col md:items-end md:justify-center md:text-right">
                            <div class="flex items-baseline gap-2 md:flex-col md:items-end md:gap-0">
                                <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                                    <?php esc_html_e('Starting at', 'standard'); ?>
                                </span>
                                <span class="font-sans font-medium text-lg text-white">
                                    <?php echo esc_html($trailer['price']); ?>
                                </span>
                            </div>
                            <a href="<?php echo esc_url($trailer['url']); ?>" class="btn btn-outline-light shrink-0">
                                <?php esc_html_e('View', 'standard'); ?>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>
