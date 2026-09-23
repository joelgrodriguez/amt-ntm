<?php
/**
 * SSM Portable Siding Machine — sign-up section (HubSpot form).
 *
 * @package Standard
 * @usage page-ssm.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

// Placeholder copy until the final SSM copy is approved.
$expectations = [
    __('Specs and pricing as soon as they are released', 'standard'),
    __('SSM updates', 'standard'),
    __('SSM order availability date', 'standard'),
];
?>

<section id="ssm-signup" class="scroll-mt-24 border-b border-blue-200 bg-blue-50 section" aria-labelledby="ssm-signup-title">
    <?php // Source order is header, form, list so the form comes right after the header on mobile. ?>
    <div class="container grid grid-cols-1 gap-10 lg:grid-cols-[minmax(0,0.8fr)_minmax(420px,1.2fr)] lg:grid-rows-[auto_1fr] lg:gap-x-16 lg:gap-y-10">
        <header class="section-header-left max-w-xl content-start lg:col-start-1 lg:row-start-1">
            <p class="section-eyebrow"><?php esc_html_e('Early access', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="ssm-signup-title" class="section-title">
                <?php esc_html_e('Find out more about the SSM', 'standard'); ?>
            </h2>
        </header>

        <div class="border-t-4 border-blue-500 bg-white p-6 md:p-8 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:self-start">
            <h3 class="mb-6 text-balance text-xl font-medium leading-snug text-blue-900 md:text-2xl">
                <?php esc_html_e('I want to learn more about the SSM Portable Siding Machine', 'standard'); ?>
            </h3>
            <?php
            echo HubSpot\render_form([
                'form_id'   => HubSpot\SSM_FORM_ID,
                'target_id' => 'ssm-signup-form',
                // Measured rendered form height (Sep 2026) so the page does not jump when it loads.
                // Re-measure if fields are added or removed in HubSpot.
                'class'     => 'min-h-[76rem] md:min-h-[55rem]',
            ]);
            ?>
        </div>

        <div class="grid content-start gap-6 lg:col-start-1 lg:row-start-2">
            <ul class="grid gap-3" role="list">
                <?php foreach ($expectations as $expectation) : ?>
                    <li class="grid grid-cols-[auto_1fr] items-center gap-4 border border-blue-200 bg-white p-4">
                        <span class="flex h-10 w-10 items-center justify-center bg-blue-100 text-blue-600" aria-hidden="true">
                            <?php icon('check', ['class' => 'h-5 w-5']); ?>
                        </span>
                        <span class="text-base leading-snug text-blue-900"><?php echo esc_html($expectation); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="flex items-center gap-3 text-sm leading-relaxed text-blue-600">
                <?php icon('phone', ['class' => 'h-5 w-5 shrink-0 text-blue-500']); ?>
                <span>
                    <?php esc_html_e('Prefer to talk now?', 'standard'); ?>
                    <?php echo wp_kses_post(HubSpot\call_sales_html()); ?>
                </span>
            </p>
        </div>
    </div>
</section>
