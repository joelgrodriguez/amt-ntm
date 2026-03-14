<?php
/**
 * Machine Product — Profile Selector
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
            <p class="section-eyebrow">Panel Profiles</p>
            <div class="section-divider-center"></div>
            <h2 id="profiles-title" class="section-title">Your Panels, Your Way</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <?php foreach ($profiles as $profile) : ?>
                <a href="<?php echo esc_url(get_permalink($profile)); ?>" class="border border-slate-200 bg-white p-4 text-center grid gap-2 hover:border-slate-400 transition-colors">
                    <div class="bg-slate-100 h-20 flex items-center justify-center overflow-hidden">
                        <?php if (has_post_thumbnail($profile)) : ?>
                            <?php echo get_the_post_thumbnail($profile, 'medium', ['class' => 'w-full h-full object-contain']); ?>
                        <?php else : ?>
                            <span class="text-slate-400 text-xs font-mono">Profile</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-sm font-semibold text-slate-900"><?php echo esc_html(get_the_title($profile)); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
