<?php
/**
 * Archive — Page hero (category, tag, and other taxonomy archives).
 *
 * Matches the manuals landing hero: dot-grid surface, section eyebrow,
 * divider, display heading, and optional description.
 *
 * @package Standard
 *
 * @var array{
 *   eyebrow?: string,
 *   title: string,
 *   description?: string
 * } $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$eyebrow     = trim((string) ($args['eyebrow'] ?? ''));
$title       = trim((string) ($args['title'] ?? ''));
$description = trim((string) ($args['description'] ?? ''));

if ($title === '') {
    return;
}
?>

<section class="pattern-dot-grid pattern-dot-grid--surface border-b border-blue-200 bg-blue-50 pt-6 pb-6 lg:pt-12 lg:pb-12">
    <div class="container">
        <div class="section-header-left max-w-3xl">
            <?php if ($eyebrow !== '') : ?>
                <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <div class="section-divider"></div>
            <?php endif; ?>
            <h1 class="font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                <?php echo esc_html($title); ?>
            </h1>
            <?php if ($description !== '') : ?>
                <p class="font-sans text-blue-600 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php echo esc_html($description); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>