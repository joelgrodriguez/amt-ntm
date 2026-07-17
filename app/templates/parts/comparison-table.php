<?php
/**
 * Shared Template Part — Comparison Table
 *
 * Single responsive specs table. Fits naturally on desktop (no forced
 * min-width); horizontal scroll only kicks in below `md`. Sticky left
 * column on mobile so the spec label stays anchored as the user scrolls
 * machine columns.
 *
 * @package Standard
 *
 * @param array{eyebrow: string, title: string} $content
 * @param array $machines  List of machine entries (slug, short_name, badge, url, specs)
 * @param array $rows      spec key => label
 * @param string $section_id
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content    = $args['content'] ?? [];
$machines   = $args['machines'] ?? [];
$rows       = $args['rows'] ?? [];
$section_id = $args['section_id'] ?? 'comparison-title';

if (empty($machines) || empty($rows)) {
    return;
}
?>

<section class="section border-t border-blue-200" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container section-content">

        <!-- Lighter opener than the rest of the page: the table is its
             own self-evident artifact (every column header is already a
             machine name), so an eyebrow + divider + title stack on top
             reads as a redundant masthead. One title, one mono kicker
             at the right edge, no divider line. -->
        <?php
        // Tighter gap when there's no subtitle (the bare title+table reads as
        // a cramped pair, per Evita); a supplied subtitle fills the space
        // instead and keeps the looser rhythm. Only the machii caller passes
        // one today — the other 4 tables stay as the lighter masthead.
        $has_subtitle = !empty($content['subtitle']);
        ?>
        <div class="flex flex-wrap items-end justify-between gap-4 <?php echo $has_subtitle ? 'mb-4' : 'mb-6'; ?>">
            <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title m-0">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <?php if (!empty($content['eyebrow'])) : ?>
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-500 m-0">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php if ($has_subtitle) : ?>
            <p class="section-subtitle max-w-2xl -mt-4 mb-8"><?php echo esc_html($content['subtitle']); ?></p>
        <?php endif; ?>
        <div class="grid gap-3">
            <p class="md:hidden font-mono text-xs uppercase tracking-[0.18em] text-blue-500 m-0">
                <?php esc_html_e('Swipe to compare', 'standard'); ?>
            </p>
            <div class="overflow-x-auto md:overflow-visible" tabindex="0" aria-label="<?php esc_attr_e('Machine comparison table. Swipe horizontally to compare machine columns.', 'standard'); ?>">
                <table class="w-full text-sm border-collapse border border-blue-200 md:table-fixed" aria-labelledby="<?php echo esc_attr($section_id); ?>">
                    <caption class="sr-only"><?php echo esc_html($content['title']); ?></caption>
                    <thead>
                        <tr>
                            <th scope="col" class="bg-blue-800 text-white py-4 px-4 text-left font-medium text-base border-r border-blue-700 sticky left-0 z-10 shadow-[6px_0_8px_-6px_rgba(10,19,34,0.18)] md:static md:shadow-none">
                                <?php esc_html_e('Machine', 'standard'); ?>
                            </th>
                            <?php foreach ($machines as $machine) :
                                $is_flagship = !empty($machine['badge']) || !empty($machine['featured']);
                                $badge_text  = $machine['badge'] ?? '';
                                if ($badge_text === '' && !empty($machine['featured'])) {
                                    $badge_text = __('Featured', 'standard');
                                }
                            ?>
                                <th scope="col" class="<?php echo $is_flagship ? 'bg-blue-500' : 'bg-blue-800'; ?> text-white py-4 px-3 text-center border-r border-blue-700 min-w-[130px] md:min-w-0 break-words">
                                    <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="text-white no-underline hover:text-blue-200 transition-colors font-medium text-sm">
                                        <?php echo esc_html($machine['short_name'] ?? $machine['name']); ?>
                                    </a>
                                    <?php if ($is_flagship && $badge_text !== '') : ?>
                                        <span class="block text-xs font-medium uppercase tracking-wider text-white/70 mt-0.5">
                                            <?php echo esc_html($badge_text); ?>
                                        </span>
                                    <?php endif; ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $key => $label) : ?>
                            <tr class="border-b border-blue-200">
                                <th scope="row" class="py-3 px-4 font-medium text-blue-800 text-left border-r border-blue-200 sticky left-0 bg-white z-10 shadow-[6px_0_8px_-6px_rgba(10,19,34,0.12)] md:static md:shadow-none">
                                    <?php echo esc_html($label); ?>
                                </th>
                                <?php foreach ($machines as $machine) :
                                    $is_flagship = !empty($machine['badge']) || !empty($machine['featured']);
                                    $value       = $machine['specs'][$key] ?? '';
                                ?>
                                    <td class="py-3 px-3 text-center text-blue-600 border-r border-blue-200 break-words hyphens-auto <?php echo $is_flagship ? 'bg-blue-500/5' : ''; ?>">
                                        <?php echo $value !== '' ? esc_html($value) : '<span class="text-blue-300" aria-hidden="true">—</span>'; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="py-4 px-4 border-r border-blue-200 sticky left-0 bg-white z-10 shadow-[6px_0_8px_-6px_rgba(10,19,34,0.12)] md:static md:shadow-none"></td>
                            <?php foreach ($machines as $machine) :
                                $is_flagship = !empty($machine['badge']) || !empty($machine['featured']);
                            ?>
                                <td class="py-4 px-3 text-center border-r border-blue-200 <?php echo $is_flagship ? 'bg-blue-500/5' : ''; ?>">
                                    <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="btn <?php echo $is_flagship ? 'btn-primary' : 'btn-outline-dark'; ?> btn-sm">
                                        <?php esc_html_e('Explore', 'standard'); ?>
                                    </a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
