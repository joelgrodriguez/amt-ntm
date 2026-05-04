<?php
/**
 * Machine Product — Final CTA
 *
 * Three-card bottom CTA: Build & Finance, Request a Quote, See It In Action.
 *
 * @package Standard
 *
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$product) {
    return;
}

$ctas = [
    [
        'title' => __('Build & Finance', 'standard'),
        'text'  => __('Configure your machine and explore payment options.', 'standard'),
        'label' => __('Start Building', 'standard'),
        'url'   => \Standard\Url\with_query('/build-finance/', ['machine' => $product->get_slug()]),
    ],
    [
        'title' => __('Request a Quote', 'standard'),
        'text'  => __('Talk to a specialist about your specific needs.', 'standard'),
        'label' => __('Get a Quote', 'standard'),
        'url'   => \Standard\Url\internal('/contact/'),
    ],
    [
        'title' => __('See It In Action', 'standard'),
        'text'  => __('Schedule a demo or watch the machine run.', 'standard'),
        'label' => __('Watch Video', 'standard'),
        'url'   => '#',
    ],
];
?>

<section id="machine-final-cta" class="section bg-blue-900" aria-labelledby="final-cta-title">
    <div class="container section-content">

        <div class="section-header">
            <h2 id="final-cta-title" class="section-title text-white"><?php esc_html_e('Ready to Get Started?', 'standard'); ?></h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <?php foreach ($ctas as $cta) : ?>
                <div class="border border-blue-700 p-8 grid gap-4 text-center">
                    <h3 class="text-lg font-medium text-white"><?php echo esc_html($cta['title']); ?></h3>
                    <p class="text-sm text-blue-400"><?php echo esc_html($cta['text']); ?></p>
                    <a href="<?php echo esc_url($cta['url']); ?>" class="btn btn-outline-light btn-sm mx-auto">
                        <?php echo esc_html($cta['label']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
