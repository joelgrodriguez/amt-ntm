<?php
/**
 * Editorial post hero — shared across single article, download, and
 * resource templates.
 *
 * Two-column layout: eyebrow + title + excerpt + meta on the left,
 * featured image on the right. Sits on `pattern-dot-grid bg-blue-50`
 * with a `border-b border-blue-200` so the seam is structural, not
 * gradient.
 *
 * Args (all optional):
 *
 *   eyebrow_kind  string  'categories' | 'post-type' | 'none'. Drives the
 *                         small uppercase tag row above the title.
 *                         Default 'categories'.
 *   meta_items    array   Pre-rendered meta items. Each entry is one of:
 *                         ['type' => 'date']                            → date pill
 *                         ['type' => 'author']                          → author pill
 *                         ['type' => 'read-time']                       → reading-time pill
 *                         ['type' => 'separator']                       → "/" divider
 *                         ['type' => 'text', 'text' => '…', 'icon'=>…]  → custom pill
 *                         Default for posts: date, author, separator, read-time.
 *                         Default for non-post types: date only.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'eyebrow_kind' => 'categories',
    'meta_items'   => null,
];

$args = wp_parse_args($args ?? [], $defaults);

$is_article = get_post_type() === 'post';

if ($args['meta_items'] === null) {
    $args['meta_items'] = $is_article
        ? [
            ['type' => 'date'],
            ['type' => 'author'],
            ['type' => 'separator'],
            ['type' => 'read-time'],
        ]
        : [
            ['type' => 'date'],
        ];
}

$has_image = has_post_thumbnail();
?>

<header class="pattern-dot-grid pattern-dot-grid--surface bg-blue-50 border-b border-blue-200">
    <div class="container grid gap-8 lg:gap-12 lg:grid-cols-2 lg:items-center pt-6 lg:pt-12 pb-6 lg:pb-12">
        <div class="grid gap-5 lg:gap-6">
            <?php if ($args['eyebrow_kind'] === 'categories' && has_category()) : ?>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-2 font-mono uppercase tracking-widest text-caption">
                    <?php
                    $categories = array_slice(get_the_category(), 0, 3);
                    foreach ($categories as $i => $category) :
                        if ($i > 0) : ?>
                            <span class="text-blue-300" aria-hidden="true">/</span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                           class="text-blue-700 no-underline hover:text-blue-500 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($args['eyebrow_kind'] === 'post-type') : ?>
                <?php
                $type_object = get_post_type_object((string) get_post_type());
                $singular    = $type_object?->labels->singular_name ?? ucfirst((string) get_post_type());
                ?>
                <div class="font-mono uppercase tracking-widest text-caption text-blue-700">
                    <?php echo esc_html($singular); ?>
                </div>
            <?php endif; ?>

            <?php the_title('<h1 class="font-mono font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight m-0">', '</h1>'); ?>

            <?php if (has_excerpt()) : ?>
                <p class="text-blue-600 text-base lg:text-lg leading-relaxed m-0">
                    <?php echo esc_html(get_the_excerpt()); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($args['meta_items'])) : ?>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-blue-600 font-mono text-sm pt-1">
                    <?php
                    $word_count   = str_word_count(wp_strip_all_tags(get_the_content()));
                    $reading_time = max(1, (int) ceil($word_count / 220));

                    foreach ($args['meta_items'] as $item) :
                        switch ($item['type'] ?? '') :
                            case 'date': ?>
                                <span class="flex items-center gap-2">
                                    <?php icon('calendar', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('j F Y')); ?>
                                    </time>
                                </span>
                                <?php break;
                            case 'author': ?>
                                <span class="flex items-center gap-2">
                                    <?php icon('user', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                    <span><?php echo esc_html(get_the_author_meta('display_name')); ?></span>
                                </span>
                                <?php break;
                            case 'read-time': ?>
                                <span><?php
                                    /* translators: %d minutes of reading time. */
                                    printf(esc_html(_n('%d min read', '%d min read', $reading_time, 'standard')), $reading_time);
                                ?></span>
                                <?php break;
                            case 'separator': ?>
                                <span class="text-blue-400" aria-hidden="true">/</span>
                                <?php break;
                            case 'text': ?>
                                <span class="flex items-center gap-2">
                                    <?php if (!empty($item['icon'])) : ?>
                                        <?php icon($item['icon'], ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                    <?php endif; ?>
                                    <span><?php echo esc_html($item['text'] ?? ''); ?></span>
                                </span>
                                <?php break;
                        endswitch;
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($has_image) : ?>
            <figure class="featured-image m-0">
                <?php the_post_thumbnail('large', [
                    'class'         => 'w-full h-auto block',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                    'sizes'         => '(min-width: 1024px) 640px, 100vw',
                    'alt'           => esc_attr(get_the_title()),
                ]); ?>
            </figure>
        <?php endif; ?>
    </div>
</header>
