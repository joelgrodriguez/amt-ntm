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

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>
        <div class="overflow-x-auto md:overflow-visible">
            <table class="w-full text-sm border-collapse border border-blue-200 md:table-fixed" aria-labelledby="<?php echo esc_attr($section_id); ?>">
                <caption class="sr-only"><?php echo esc_html($content['title']); ?></caption>
                <thead>
                    <tr>
                        <th class="bg-blue-800 text-white py-4 px-4 text-left font-medium text-base border-r border-blue-700 sticky left-0 z-10 md:static">
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </th>
                        <?php foreach ($machines as $machine) :
                            $is_flagship = !empty($machine['badge']);
                        ?>
                            <th class="<?php echo $is_flagship ? 'bg-blue-500' : 'bg-blue-800'; ?> text-white py-4 px-3 text-center border-r border-blue-700 min-w-[130px] md:min-w-0 break-words">
                                <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="text-white no-underline hover:underline font-medium text-sm">
                                    <?php echo esc_html($machine['short_name'] ?? $machine['name']); ?>
                                </a>
                                <?php if ($is_flagship) : ?>
                                    <span class="block text-xs font-medium uppercase tracking-wider text-white/70 mt-0.5">
                                        <?php echo esc_html($machine['badge']); ?>
                                    </span>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $key => $label) : ?>
                        <tr class="border-b border-blue-200">
                            <td class="py-3 px-4 font-medium text-blue-800 border-r border-blue-200 sticky left-0 bg-white z-10 md:static">
                                <?php echo esc_html($label); ?>
                            </td>
                            <?php foreach ($machines as $machine) :
                                $is_flagship = !empty($machine['badge']);
                                $value       = $machine['specs'][$key] ?? '';
                            ?>
                                <td class="py-3 px-3 text-center text-blue-600 border-r border-blue-200 <?php echo $is_flagship ? 'bg-blue-500/5' : ''; ?>">
                                    <?php echo $value !== '' ? esc_html($value) : '<span class="text-blue-300" aria-hidden="true">—</span>'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="py-4 px-4 border-r border-blue-200 sticky left-0 bg-white z-10 md:static"></td>
                        <?php foreach ($machines as $machine) :
                            $is_flagship = !empty($machine['badge']);
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
</section>
