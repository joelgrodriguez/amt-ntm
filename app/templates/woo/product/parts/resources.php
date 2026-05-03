<?php
/**
 * Machine Product — Resources & Support
 *
 * Larger cards with image placeholders for visual weight.
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine   = $args['machine'] ?? [];
$resources = $machine['resources'] ?? null;

if (!$resources) {
    return;
}

$cards = [];
if (!empty($resources['manual'])) {
    $cards[] = ['url' => $resources['manual'], 'title' => __('Machine Manual', 'standard'), 'desc' => __('Operator manual with setup, maintenance, and troubleshooting guides.', 'standard'), 'cta' => __('View Manual', 'standard')];
}
if (!empty($resources['brochure'])) {
    $cards[] = ['url' => $resources['brochure'], 'title' => __('Product Brochure', 'standard'), 'desc' => __('Full specifications, features, and configuration options.', 'standard'), 'cta' => __('View Brochure', 'standard')];
}
if (!empty($resources['service_training_url'])) {
    $cards[] = ['url' => $resources['service_training_url'], 'title' => __('Service & Training', 'standard'), 'desc' => __('Hands-on training, technical support, and replacement parts.', 'standard'), 'cta' => __('Learn More', 'standard')];
}

if (empty($cards)) {
    return;
}
?>

<section class="section pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="resources-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Resources', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="resources-title" class="section-title"><?php esc_html_e('Downloads & Support', 'standard'); ?></h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <?php foreach ($cards as $card) : ?>
                <a href="<?php echo esc_url($card['url']); ?>"
                   class="group border border-blue-200 bg-white overflow-hidden hover:border-blue-400 transition-all"
                   target="_blank"
                   rel="noopener">
                    <div class="bg-blue-100 aspect-[4/3] flex flex-col items-center justify-center gap-3 p-6">
                        <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span class="text-blue-400 text-xs font-mono"><?php echo esc_html($card['title']); ?></span>
                    </div>
                    <div class="p-6 grid gap-3">
                        <h3 class="text-lg font-medium text-blue-900 group-hover:text-blue-500 transition-colors"><?php echo esc_html($card['title']); ?></h3>
                        <p class="text-sm text-blue-600 leading-relaxed"><?php echo esc_html($card['desc']); ?></p>
                        <span class="text-sm font-medium text-blue-500"><?php echo esc_html($card['cta']); ?> &rarr;</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
