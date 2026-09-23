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

$image_id  = (int) ($config['image_id'] ?? 0);
$image_url = (string) $config['image_url'];
$image_alt = (string) $config['image_alt'];
$image_attrs = [
    'alt'           => $image_alt,
    'class'         => 'aspect-video h-auto w-full object-cover',
    'loading'       => 'eager',
    'fetchpriority' => 'high',
    'decoding'      => 'async',
    // Left grid column: full width below lg, about half the 1440px container above it.
    'sizes'         => '(min-width: 1440px) 740px, (min-width: 1024px) 52vw, 100vw',
];
?>

<section id="ssm-signup" class="relative isolate overflow-hidden bg-blue-900 text-white" aria-labelledby="ssm-title">
    <?php // Source order is text, form, image so the form comes second on mobile; the grid places the image under the text on desktop. ?>
    <div class="relative container grid grid-cols-1 gap-10 py-12 md:py-20 lg:grid-cols-[minmax(0,1.1fr)_minmax(420px,0.9fr)] lg:gap-x-16 lg:gap-y-10 lg:py-24">
        <div class="grid content-start gap-5 lg:col-start-1 lg:row-start-1 lg:gap-8">
            <p class="font-mono text-xs font-medium uppercase tracking-widest text-blue-300">
                <?php esc_html_e('Coming soon · New from NTM', 'standard'); ?>
            </p>

            <h1 id="ssm-title" class="text-balance text-3xl font-medium leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                <?php esc_html_e('I want to learn more about the SSM Portable Siding Machine', 'standard'); ?>
            </h1>

            <p class="max-w-xl text-lg leading-relaxed text-blue-200 lg:text-xl">
                <?php esc_html_e('A portable rollformer built for wall panels. Tell us about your business and we will send you SSM details as soon as they are ready.', 'standard'); ?>
            </p>
        </div>

        <div class="border-t-4 border-blue-500 bg-white p-6 text-blue-900 md:p-8 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:self-start">
            <h2 class="mb-6 text-2xl font-medium tracking-tight text-blue-900">
                <?php esc_html_e('Send me SSM details', 'standard'); ?>
            </h2>
            <?php
            echo HubSpot\render_form([
                'form_id'   => HubSpot\SSM_FORM_ID,
                'target_id' => 'ssm-signup-form',
                // Measured rendered form height (Sep 2026) so the page does not jump when it loads.
                // Re-measure if fields are added or removed in HubSpot.
                'class'     => 'min-h-[76rem] md:min-h-[55rem]',
            ]);
            ?>
            <p class="mt-6 border-t border-blue-100 pt-4 text-sm leading-relaxed text-blue-600">
                <?php esc_html_e('We will follow up by email or phone when SSM details are ready.', 'standard'); ?>
            </p>
        </div>

        <figure class="border border-blue-700 bg-blue-800 lg:col-start-1 lg:row-start-2 lg:self-start">
            <?php if ($image_id > 0) : ?>
                <?php echo wp_get_attachment_image($image_id, 'full', false, $image_attrs); ?>
            <?php else : ?>
                <img
                    src="<?php echo esc_url($image_url); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    class="<?php echo esc_attr($image_attrs['class']); ?>"
                    width="1200"
                    height="675"
                    loading="eager"
                    fetchpriority="high"
                    decoding="async"
                >
            <?php endif; ?>
        </figure>
    </div>
</section>
