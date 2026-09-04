<?php
/**
 * METALCON 2026 — reasons to request a meeting before the show.
 *
 * @package Standard
 * @usage page-metalcon-2026.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$reasons = [
    [
        'title' => __('Rollformer deep-dive', 'standard'),
        'text'  => __('NTM techs will be on hand to show you the new machine features in detail.', 'standard'),
    ],
    [
        'title' => __('Market opportunities', 'standard'),
        'text'  => __('Get information on how the portable rollformer can open emerging markets for your business.', 'standard'),
    ],
    [
        'title' => __('Business growth', 'standard'),
        'text'  => __('Speak with an NTM account manager about how the new machine can help grow your operation.', 'standard'),
    ],
];?>

<section class="section border-b border-blue-200 bg-blue-50" aria-labelledby="metalcon-book-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:gap-16">
            <header class="section-header-left max-w-xl content-start">
                <p class="section-eyebrow"><?php esc_html_e('Why book ahead', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="metalcon-book-title" class="section-title">
                    <?php esc_html_e('Make the booth time count', 'standard'); ?>
                </h2>
                <p class="section-subtitle text-pretty">
                    <?php esc_html_e('The show floor is built for quick conversations. A requested meeting gives your questions and your operation room to breathe.', 'standard'); ?>
                </p>
            </header>

            <ul class="grid gap-4" role="list">
                <?php foreach ($reasons as $reason) : ?>
                    <li class="grid grid-cols-[auto_1fr] gap-4 border border-blue-200 bg-white p-5 md:p-6">
                        <span class="flex h-11 w-11 items-center justify-center bg-blue-100 text-blue-600" aria-hidden="true">
                            <?php icon('check', ['class' => 'h-5 w-5']); ?>
                        </span>
                        <div class="grid gap-2">
                            <h3 class="text-lg font-medium text-blue-900"><?php echo esc_html($reason['title']); ?></h3>
                            <p class="text-base leading-relaxed text-blue-600"><?php echo esc_html($reason['text']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
