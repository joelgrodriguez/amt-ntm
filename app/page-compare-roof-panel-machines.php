<?php
/**
 * Template Name: NTM — Compare Roof Panel Machines (placeholder)
 *
 * Placeholder template. Production content will be added in a later pass.
 * Part of the four-action IA rebuild — see docs/handoff/.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$page_slug  = 'compare-roof-panel-machines';
$page_title = 'Compare Roof Panel Machines';

get_header();
?>

<main id="primary" class="site-main">
    <section class="mx-auto max-w-3xl px-6 py-24 text-center">
        <p class="mb-4 font-mono text-xs uppercase tracking-widest text-blue-400">
            <?php esc_html_e('In development · NTM IA rebuild', 'standard'); ?>
        </p>
        <h1 class="mb-4 text-4xl font-bold tracking-tight text-blue-900">
            🚧 <?php echo esc_html($page_title); ?>
        </h1>
        <p class="text-lg text-blue-600">
            <?php esc_html_e('This page is scaffolded but not yet built. Production content will be added in a follow-up pass.', 'standard'); ?>
        </p>
        <p class="mt-8 font-mono text-xs text-blue-400">
            <?php esc_html_e('Template:', 'standard'); ?> <code><?php echo esc_html(basename(__FILE__)); ?></code> ·
            <?php esc_html_e('Slug:', 'standard'); ?> <code>/<?php echo esc_html($page_slug); ?>/</code>
        </p>
    </section>
</main>

<?php
get_footer();
