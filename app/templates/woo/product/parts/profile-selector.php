<?php
/**
 * Machine Product — Profile Selector
 *
 * Flex-wrap layout with fixed-width cards so it adapts
 * whether a machine has 2 profiles or 16.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? [];
$tag_slugs = $machine['profiles']['tag_slugs'] ?? [];

if (empty($tag_slugs)) {
    return;
}

$profiles = get_posts([
    'post_type'      => 'profile',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'tax_query'      => [
        [
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => $tag_slugs,
        ],
    ],
]);

if (empty($profiles)) {
    return;
}
?>

<section class="section bg-slate-50" aria-labelledby="profiles-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Panel Profiles', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="profiles-title" class="section-title"><?php esc_html_e('Your Panels, Your Way', 'standard'); ?></h2>
        </div>

        <div class="flex flex-wrap justify-center gap-6">
            <?php foreach ($profiles as $profile) :
                $categories = get_the_terms($profile->ID, 'category');
                $cat_name   = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
            ?>
                <a href="<?php echo esc_url(get_permalink($profile)); ?>" class="group w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] border border-slate-200 bg-white p-6 grid gap-4 hover:border-slate-400 hover:shadow-md transition-all">
                    <div class="bg-slate-50 aspect-[4/3] flex items-center justify-center overflow-hidden rounded">
                        <?php if (has_post_thumbnail($profile)) : ?>
                            <?php echo get_the_post_thumbnail($profile, 'medium', ['class' => 'w-full h-full object-contain p-4 group-hover:scale-105 transition-transform']); ?>
                        <?php else : ?>
                            <span class="text-slate-400 text-sm font-mono"><?php esc_html_e('Profile', 'standard'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="grid gap-1">
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-primary transition-colors"><?php echo esc_html(get_the_title($profile)); ?></h3>
                        <?php if ($cat_name) : ?>
                            <p class="text-sm text-slate-500"><?php echo esc_html($cat_name); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
