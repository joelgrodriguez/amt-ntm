<?php
/**
 * METALCON 2026 — booth highlights.
 *
 * @package Standard
 * @usage page-metalcon-2026.php
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

$highlights = [
    [
        'title' => __('SSM siding machine, running live', 'standard'),
        'text'  => __('See the machine form siding panels on the show floor and get close to the full run cycle.', 'standard'),
    ],
    [
        'title' => __('MACH II iPad kiosk', 'standard'),
        'text'  => __('Explore the MACH II control experience at the booth kiosk and bring your workflow questions.', 'standard'),
    ],
    [
        'title' => __('Siding wall build', 'standard'),
        'text'  => __('See formed siding installed as a finished wall assembly, not just a loose sample on a table.', 'standard'),
    ],
];
?>

<section class="section bg-white" aria-labelledby="metalcon-see-title">
    <div class="container section-content">
        <header class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Inside the booth', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="metalcon-see-title" class="section-title">
                <?php esc_html_e('Three things worth seeing in person', 'standard'); ?>
            </h2>
        </header>

        <ol class="grid gap-px border border-blue-200 bg-blue-200 md:grid-cols-3" role="list">
            <?php foreach ($highlights as $index => $highlight) : ?>
                <li class="grid content-start gap-4 bg-white p-6 lg:p-8">
                    <div class="flex items-center gap-3 font-mono text-xs font-medium uppercase tracking-widest text-blue-500">
                        <span><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <span class="h-px w-8 bg-blue-300" aria-hidden="true"></span>
                    </div>
                    <h3 class="text-xl font-medium leading-tight text-blue-900">
                        <?php echo esc_html($highlight['title']); ?>
                    </h3>
                    <p class="text-base leading-relaxed text-blue-600">
                        <?php echo esc_html($highlight['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
