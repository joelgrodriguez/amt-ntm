<?php
/**
 * Machine Product — Profile Carousel
 *
 * Horizontal scrollable carousel with prev/next navigation.
 * Same pattern as the accessories carousel.
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

$carousel_id = 'profiles-carousel';
?>

<section class="section bg-slate-50" aria-labelledby="profiles-title">
    <div class="container section-content">

        <div class="flex items-end justify-between gap-4 mb-10">
            <div class="section-header-left mb-0">
                <p class="section-eyebrow"><?php esc_html_e('Panel Profiles', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="profiles-title" class="section-title"><?php esc_html_e('Your Panels, Your Way', 'standard'); ?></h2>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="button"
                        data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                        class="w-10 h-10 border border-slate-300 flex items-center justify-center hover:bg-slate-100 transition-colors"
                        aria-label="<?php esc_attr_e('Previous profiles', 'standard'); ?>">
                    <span class="text-slate-600">&larr;</span>
                </button>
                <button type="button"
                        data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                        class="w-10 h-10 border border-slate-300 flex items-center justify-center hover:bg-slate-100 transition-colors"
                        aria-label="<?php esc_attr_e('Next profiles', 'standard'); ?>">
                    <span class="text-slate-600">&rarr;</span>
                </button>
            </div>
        </div>

        <div id="<?php echo esc_attr($carousel_id); ?>"
             class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4"
             style="scrollbar-width: none; -ms-overflow-style: none;">
            <?php foreach ($profiles as $profile) :
                $categories = get_the_terms($profile->ID, 'category');
                $cat_name   = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
            ?>
                <a href="<?php echo esc_url(get_permalink($profile)); ?>"
                   class="snap-start shrink-0 w-[200px] group border border-slate-200 bg-white p-4 grid gap-3 hover:border-slate-400 hover:shadow-md transition-all">
                    <div class="bg-slate-50 aspect-square flex items-center justify-center overflow-hidden rounded">
                        <?php if (has_post_thumbnail($profile)) : ?>
                            <?php echo get_the_post_thumbnail($profile, 'medium', ['class' => 'w-full h-full object-contain p-3 group-hover:scale-105 transition-transform']); ?>
                        <?php else : ?>
                            <span class="text-slate-400 text-sm font-mono"><?php esc_html_e('Profile', 'standard'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="grid gap-1">
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors leading-tight"><?php echo esc_html(get_the_title($profile)); ?></h3>
                        <?php if ($cat_name) : ?>
                            <p class="text-xs text-slate-500"><?php echo esc_html($cat_name); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<script>
(function() {
    const id = <?php echo wp_json_encode($carousel_id); ?>;
    const track = document.getElementById(id);
    if (!track) return;

    const scrollAmount = 220;

    document.querySelectorAll(`[data-carousel-prev="${id}"]`).forEach(btn => {
        btn.addEventListener('click', () => track.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    });
    document.querySelectorAll(`[data-carousel-next="${id}"]`).forEach(btn => {
        btn.addEventListener('click', () => track.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
    });
})();
</script>
