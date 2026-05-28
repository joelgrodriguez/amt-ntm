<?php
/**
 * Template Name: NTM — Roof Panel vs Gutter (placeholder)
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

$page_slug  = 'roof-panel-vs-gutter';
$page_title = 'Roof Panel vs Gutter';

get_header();
?>

<main id="primary" class="site-main">
    <section class="mx-auto max-w-3xl px-6 py-24 text-center">
        <p class="mb-6 font-mono text-xs uppercase tracking-widest text-blue-400">
            <?php esc_html_e('In development', 'standard'); ?>
        </p>
        <p class="mb-4 text-5xl leading-none" aria-hidden="true">🚧</p>
        <h1 class="mb-6 text-4xl font-medium tracking-tight text-blue-900">
            <?php echo esc_html($page_title); ?>
        </h1>
        <p class="text-lg text-blue-600">
            <?php esc_html_e('This page is scaffolded but not yet built.', 'standard'); ?>
        </p>
        <p class="text-lg text-blue-600">
            <?php esc_html_e('Production content will be added in a follow-up pass.', 'standard'); ?>
        </p>
        <p class="mt-8 font-mono text-xs text-blue-400">
            <?php esc_html_e('Template:', 'standard'); ?> <code><?php echo esc_html(basename(__FILE__)); ?></code> ·
            <?php esc_html_e('Slug:', 'standard'); ?> <code>/<?php echo esc_html($page_slug); ?>/</code>
        </p>
    </section>
</main>

<?php
get_footer();
