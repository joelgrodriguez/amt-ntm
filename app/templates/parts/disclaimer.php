<?php
/**
 * Template part for displaying a content disclaimer.
 *
 * Hairline aside, blue palette. Per DESIGN.md §1 the system uses only
 * blue + red; the disclaimer is informational, not an error, so it sits
 * on blue-50 with a mono uppercase label and a single caution icon.
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<aside class="mt-6 lg:mt-12 p-5 lg:p-6 bg-blue-50 border border-blue-200 grid grid-cols-[auto_1fr] gap-4 items-start">
    <span class="shrink-0 text-blue-500 mt-0.5">
        <?php icon('alert-triangle', ['class' => 'w-5 h-5']); ?>
    </span>
    <div class="grid gap-2">
        <span class="font-mono uppercase tracking-widest text-caption text-blue-500">
            <?php esc_html_e('Disclaimer', 'standard'); ?>
        </span>
        <p class="m-0 text-sm leading-relaxed text-blue-700">
            <?php esc_html_e('The information provided is for general guidance only. Consult your account representative before making any changes to your machine configurations, warranties, or service agreements.', 'standard'); ?>
        </p>
    </div>
</aside>
