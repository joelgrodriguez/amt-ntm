<?php
/**
 * Seamless Gutter Machines — MACH II Family Callout
 *
 * Inline deep-link to the dedicated /machines/machii/ family landing.
 * Sits between Abel's customer story and the "which machine" decision
 * helper — the buyer just heard a MACH II testimonial and is the most
 * likely to want a closer look at the whole family (5", 6", 5"/6"
 * Combo) before they pick a model. Dark mode by design: the rest of
 * the seamless-gutter page is light, so this section reads as the
 * page's one heritage punctuation — the "this is the line behind the
 * recommendation" moment — without competing with surrounding
 * sections. Single primary CTA, no secondary.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$image     = content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-001.jpg');
$image_alt = __('NTM MACH II seamless gutter machine on a jobsite', 'standard');

$receipts = [
    ['year' => '1994', 'note' => __('First MACH II shipped.', 'standard')],
    ['year' => '3',    'note' => __('Models: 5", 6", 5"/6" Combo.', 'standard')],
    ['year' => '30+',  'note' => __('Years on the road, same backbone.', 'standard')],
];
?>

<section class="section bg-blue-900 text-white" aria-labelledby="machii-callout-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div class="grid gap-7 order-2 lg:order-1">
                <div class="section-header-left">
                    <p class="section-eyebrow text-blue-300"><?php esc_html_e('The MACH II · Since 1994', 'standard'); ?></p>
                    <div class="section-divider bg-blue-500"></div>
                    <h2 id="machii-callout-title" class="section-title text-white">
                        <?php esc_html_e('Run every gutter job with the machine built to make you the hero.', 'standard'); ?>
                    </h2>
                    <p class="section-subtitle text-blue-200 max-w-xl">
                        <?php esc_html_e('NTM invented the portable seamless gutter machine in 1994 and has refined the same backbone ever since. Pick the K-style that fits your work: the 5", the 6", or the 5"/6" Combo. Every one runs polyurethane drive rollers and is built to help you win more jobs.', 'standard'); ?>
                    </p>
                </div>

                <dl class="grid divide-y divide-blue-800 border-t border-blue-800">
                    <?php foreach ($receipts as $row) : ?>
                        <div class="grid grid-cols-[5rem_1fr] gap-x-6 py-3 items-baseline">
                            <dt class="font-mono text-sm font-medium text-white tracking-wider">
                                <?php echo esc_html($row['year']); ?>
                            </dt>
                            <dd class="text-blue-200 text-sm">
                                <?php echo esc_html($row['note']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/machines/machii/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Meet the MACH II', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

            <figure class="m-0 order-1 lg:order-2">
                <img
                    src="<?php echo esc_url($image); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    class="w-full h-auto object-cover aspect-video border border-blue-800"
                    loading="lazy"
                    decoding="async"
                >
            </figure>

        </div>
    </div>
</section>
