<?php
/**
 * Machines — Trailer Callout
 *
 * Compact band on the /machines landing page pointing to the trailer landing
 * page. Keeps the trailer discoverable from the machines hub without touching
 * navigation. Two capacity tiers as a quick preview, one CTA to the full story.
 *
 * @package Standard
 *
 * @usage page-machines.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$trailer_url = \Standard\Url\internal('/machines/trailer/');
?>

<section class="section-compact bg-blue-900" aria-labelledby="machines-trailer-callout-title">
    <div class="container">
        <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center lg:gap-12">

            <div class="section-header-left max-w-2xl">
                <p class="section-eyebrow"><?php esc_html_e('NTM // Trailer', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="machines-trailer-callout-title" class="font-sans text-2xl lg:text-3xl font-semibold tracking-tight text-white text-balance">
                    <?php esc_html_e('A Trailer Engineered for the Machine', 'standard'); ?>
                </h2>
                <p class="section-subtitle text-blue-300 text-pretty">
                    <?php esc_html_e('Every machine needs a way to the jobsite. From a 12,000 lb tandem-axle deck to the 23,000 lb triple-reel-rack trailers — matched to your machine, sold separately.', 'standard'); ?>
                </p>
                <dl class="flex flex-wrap gap-x-8 gap-y-3">
                    <div class="grid gap-1">
                        <dt class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                            <?php esc_html_e('Single machine', 'standard'); ?>
                        </dt>
                        <dd class="font-mono font-medium text-sm text-white">
                            <?php esc_html_e('12,000 lb tandem', 'standard'); ?>
                        </dd>
                    </div>
                    <div class="grid gap-1">
                        <dt class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                            <?php esc_html_e('Triple reel rack', 'standard'); ?>
                        </dt>
                        <dd class="font-mono font-medium text-sm text-white">
                            <?php esc_html_e('23,000 lb 3-axle', 'standard'); ?>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="shrink-0">
                <a href="<?php echo esc_url($trailer_url); ?>" class="btn btn-primary">
                    <?php esc_html_e('Explore Trailers', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            </div>

        </div>
    </div>
</section>
