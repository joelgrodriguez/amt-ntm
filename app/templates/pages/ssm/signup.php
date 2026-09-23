<?php
/**
 * SSM Portable Siding Machine — headline, machine image, and HubSpot sign-up form.
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

if (!isset($config['image_url'], $config['image_alt'])) {
    return;
}

$image_url = (string) $config['image_url'];
$image_alt = (string) $config['image_alt'];
?>

<section id="ssm-signup" class="relative isolate overflow-hidden bg-blue-900 text-white" aria-labelledby="ssm-title">
    <div class="relative container grid grid-cols-1 gap-10 py-16 md:py-20 lg:grid-cols-[minmax(0,1.1fr)_minmax(420px,0.9fr)] lg:items-start lg:gap-16 lg:py-24">
        <div class="grid content-start gap-6 lg:gap-8">
            <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                <?php esc_html_e('Coming soon · New from NTM', 'standard'); ?>
            </p>

            <h1 id="ssm-title" class="text-balance text-4xl font-semibold leading-tight tracking-tight text-white md:text-5xl lg:text-6xl">
                <?php esc_html_e('I want to learn more about the SSM Portable Siding Machine', 'standard'); ?>
            </h1>

            <p class="max-w-xl text-lg leading-relaxed text-blue-200 lg:text-xl">
                <?php esc_html_e('A portable rollformer built for wall panels. Fill out the form and we will send you details as soon as they are ready.', 'standard'); ?>
            </p>

            <figure class="border border-blue-700 bg-blue-800">
                <img
                    src="<?php echo esc_url($image_url); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    class="aspect-video h-auto w-full object-cover"
                    width="1200"
                    height="675"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                >
            </figure>
        </div>

        <div class="border-t-4 border-blue-500 bg-white p-6 text-blue-900 md:p-8 lg:sticky lg:top-28">
            <h2 class="mb-6 text-2xl font-semibold tracking-tight text-blue-900">
                <?php esc_html_e('Get SSM updates', 'standard'); ?>
            </h2>
            <?php
            echo HubSpot\render_form([
                'form_id'   => HubSpot\SSM_FORM_ID,
                'target_id' => 'ssm-signup-form',
            ]);
            ?>
        </div>
    </div>
</section>
