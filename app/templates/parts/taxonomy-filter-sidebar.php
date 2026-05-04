<?php
/**
 * Reusable taxonomy filter sidebar.
 *
 * @package Standard
 *
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$sections   = $args['sections'] ?? [];
$back_url   = $args['back_url'] ?? '';
$back_label = $args['back_label'] ?? '';
?>

<aside class="hidden lg:block border-r border-blue-200 pr-8">
    <nav class="sticky top-16 grid gap-8">
        <?php foreach ($sections as $section) : ?>
            <?php
            $terms = $section['terms'] ?? [];
            if (empty($terms)) {
                continue;
            }

            $current_terms = $section['current_terms'] ?? [];
            $current_ids = (!empty($current_terms) && !is_wp_error($current_terms))
                ? array_map('intval', wp_list_pluck($current_terms, 'term_id'))
                : [];
            ?>
            <div>
                <h3 class="text-sm font-medium text-blue-900 mb-4 flex items-center gap-2">
                    <?php icon((string) ($section['icon'] ?? 'filter'), ['class' => 'w-4 h-4']); ?>
                    <?php echo esc_html((string) ($section['title'] ?? '')); ?>
                </h3>
                <ul class="grid gap-1 border-l border-blue-200">
                    <?php foreach ($terms as $term) : ?>
                        <?php
                        if (!$term instanceof WP_Term) {
                            continue;
                        }

                        $term_link = get_term_link($term);
                        if (is_wp_error($term_link)) {
                            continue;
                        }

                        $is_active = in_array((int) $term->term_id, $current_ids, true);
                        ?>
                        <li>
                            <a href="<?php echo esc_url($term_link); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $is_active ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                <span><?php echo esc_html($term->name); ?></span>
                                <span class="text-xs text-blue-400"><?php echo esc_html((string) $term->count); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <?php if ($back_url !== '' && $back_label !== '') : ?>
            <a href="<?php echo esc_url($back_url); ?>" class="flex items-center gap-2 text-sm font-medium text-blue-500 hover:underline">
                <?php icon('arrow-left', ['class' => 'w-4 h-4']); ?>
                <?php echo esc_html($back_label); ?>
            </a>
        <?php endif; ?>
    </nav>
</aside>
