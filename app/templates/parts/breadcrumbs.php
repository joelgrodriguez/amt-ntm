<?php
/**
 * Breadcrumb trail — thin chrome strip under the site header.
 *
 * Travels with the header in #site-header's scroll behavior because it
 * lives outside <main>. Desktop renders the full trail; mobile collapses
 * to a single parent-back link per the design brief.
 *
 * Renders nothing when there's no parent to surface.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$trail = \Standard\Breadcrumbs\for_current_post();

if ($trail === null) {
    return;
}

$items   = $trail['items'];
$parent  = $trail['parent'];
$current = $trail['current'];
?>
<div class="breadcrumbs border-b border-blue-200 bg-white">
    <div class="container flex items-center h-10">
        <nav aria-label="<?php esc_attr_e('Breadcrumb', 'standard'); ?>" class="min-w-0 w-full">

            <!-- Mobile: parent-only back link -->
            <a
                href="<?php echo esc_url($parent['url']); ?>"
                class="breadcrumbs__parent lg:hidden inline-flex items-center font-mono font-medium uppercase tracking-widest text-caption text-blue-700 no-underline whitespace-nowrap overflow-hidden"
            >
                <span class="breadcrumbs__chevron text-blue-400" aria-hidden="true">&lsaquo;</span>
                <span class="breadcrumbs__parent-label"><?php echo esc_html($parent['label']); ?></span>
            </a>

            <!-- Desktop: full trail -->
            <ol class="breadcrumbs__trail hidden lg:block m-0 p-0 list-none whitespace-nowrap overflow-hidden font-mono uppercase tracking-widest text-caption">
                <?php foreach ($items as $i => $item) : ?>
                    <?php if ($i > 0) : ?>
                        <li class="breadcrumbs__sep text-blue-300" aria-hidden="true">&rsaquo;</li>
                    <?php endif; ?>
                    <li class="inline-block">
                        <a
                            href="<?php echo esc_url($item['url']); ?>"
                            class="breadcrumbs__link text-blue-400 no-underline transition-colors duration-150 hover:text-blue-500"
                        >
                            <?php echo esc_html($item['label']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="breadcrumbs__sep text-blue-300" aria-hidden="true">&rsaquo;</li>
                <li class="breadcrumbs__current inline-block text-blue-700" aria-current="page"><?php echo esc_html($current); ?></li>
            </ol>

        </nav>
    </div>
</div>
