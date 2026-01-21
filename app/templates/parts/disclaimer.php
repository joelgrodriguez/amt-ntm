<?php
/**
 * Template part for displaying a content disclaimer.
 *
 * Displays a warning notice about consulting account representatives
 * before making changes to machine configurations.
 *
 * @package Standard
 */

?>

<aside class="mt-6 lg:mt-12 p-6 bg-yellow-50 border border-yellow-400 flex items-center gap-4 text-sm text-yellow-600">
    <span class="shrink-0">
        <?php icon('warning--outline', ['class' => 'text-yellow-600 w-6 h-6']); ?>
    </span>
    <p class="m-0">
        <strong class="text-yellow-600"><?php esc_html_e('Disclaimer:', 'standard'); ?></strong>
        <?php esc_html_e('The information provided is for general guidance only. Please consult your account representative before making any changes to your machine configurations, warranties, or service agreements.', 'standard'); ?>
    </p>
</aside>
