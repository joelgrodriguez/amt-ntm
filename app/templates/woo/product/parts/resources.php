<?php
/**
 * Machine Product — Resources & Support
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? [];
$resources = $machine['resources'] ?? null;

if (!$resources) {
    return;
}

$cards = [];
if (!empty($resources['manual'])) {
    $cards[] = ['url' => $resources['manual'], 'title' => 'Machine Manual', 'cta' => 'View Manual'];
}
if (!empty($resources['brochure'])) {
    $cards[] = ['url' => $resources['brochure'], 'title' => 'Product Brochure', 'cta' => 'View Brochure'];
}
if (!empty($resources['service_training_url'])) {
    $cards[] = ['url' => $resources['service_training_url'], 'title' => 'Service & Training', 'cta' => 'Learn More'];
}

if (empty($cards)) {
    return;
}
?>

<section class="section bg-slate-50" aria-labelledby="resources-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">Resources</p>
            <div class="section-divider-center"></div>
            <h2 id="resources-title" class="section-title">Downloads &amp; Support</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <?php foreach ($cards as $card) : ?>
                <a href="<?php echo esc_url($card['url']); ?>"
                   class="border border-slate-200 bg-white p-6 text-center grid gap-2 hover:border-slate-400 transition-colors"
                   target="_blank"
                   rel="noopener">
                    <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($card['title']); ?></span>
                    <span class="text-xs text-slate-500"><?php echo esc_html($card['cta']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
