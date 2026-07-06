<?php
/**
 * Template part for displaying a content disclaimer.
 *
 * Compact aside, blue palette. Same layout as the original yellow
 * variant; color swap only.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

?>

<aside class="mt-6 lg:mt-12 p-3 bg-blue-50 border border-blue-200 flex items-center gap-4 text-sm text-blue-700">
    <span class="shrink-0">
        <?php icon('alert-triangle', ['class' => 'text-blue-500 w-6 h-6']); ?>
    </span>
    <p class="m-0">
        <strong class="text-blue-900"><?php esc_html_e('Disclaimer:', 'standard'); ?></strong>
        <?php esc_html_e('The information provided is for general guidance only. Please consult your account representative before making any changes to your machine configurations, warranties, or service agreements.', 'standard'); ?>
    </p>
</aside>
