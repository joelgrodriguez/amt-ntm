<?php
/**
 * SSM Portable Siding Machine — full-bleed reveal hero.
 *
 * @package Standard
 * @usage page-ssm.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$config = isset($args['config']) && is_array($args['config']) ? $args['config'] : [];

if (!isset($config['image_url'])) {
    return;
}

$image_id  = (int) ($config['image_id'] ?? 0);
$image_url = (string) $config['image_url'];
$image_attrs = [
    'alt'           => '',
    'aria-hidden'   => 'true',
    'class'         => 'absolute inset-0 h-full w-full object-cover object-[70%_center]',
    'loading'       => 'eager',
    'fetchpriority' => 'high',
    'decoding'      => 'async',
    'sizes'         => '100vw',
];
?>

<section class="relative isolate overflow-hidden bg-blue-900 text-white" aria-labelledby="ssm-title">
    <?php if ($image_id > 0) : ?>
        <?php echo wp_get_attachment_image($image_id, 'full', false, $image_attrs); ?>
    <?php else : ?>
        <img
            src="<?php echo esc_url($image_url); ?>"
            alt=""
            aria-hidden="true"
            class="<?php echo esc_attr($image_attrs['class']); ?>"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        >
    <?php endif; ?>
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900 via-blue-900/85 to-blue-900/20" aria-hidden="true"></div>
    <?php // Vertical ribs echo a run of wall panels, the one thing this machine makes. ?>
    <div class="absolute inset-y-0 right-0 hidden w-1/3 bg-[repeating-linear-gradient(90deg,transparent_0_46px,rgb(255_255_255/0.06)_46px_48px)] lg:block" aria-hidden="true"></div>

    <div class="relative container flex min-h-[460px] items-center py-14 md:min-h-[540px] md:py-20 lg:min-h-[620px]">
        <div class="grid max-w-2xl content-start gap-6 lg:gap-8">
            <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                <?php esc_html_e('New from New Tech Machinery · Coming soon', 'standard'); ?>
            </p>

            <h1 id="ssm-title" class="text-balance text-4xl font-medium leading-tight tracking-tight text-white md:text-5xl">
                <?php esc_html_e('SSM - Portable Siding Machine', 'standard'); ?>
            </h1>

            <p class="max-w-xl text-lg leading-relaxed text-blue-200 lg:text-xl">
                <?php esc_html_e('A portable rollformer built for wall panels. Sign up and we will send you SSM details as soon as they are ready.', 'standard'); ?>
            </p>

            <div>
                <a href="#ssm-signup" class="btn btn-primary w-full whitespace-nowrap sm:w-auto">
                    <?php esc_html_e('Send me SSM details', 'standard'); ?>
                    <?php icon('arrow-down', ['class' => 'h-5 w-5']); ?>
                </a>
            </div>
        </div>
    </div>
</section>
