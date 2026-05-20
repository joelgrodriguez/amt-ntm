<?php
/**
 * Template part for displaying related posts.
 *
 * Editorial pattern: one prominent "Read next" pick rendered as a
 * horizontal hero card (image-left, title-right), followed by up to
 * three secondary text-only links underneath. Breaks the 4-up
 * identical-card grid in favor of a hierarchy that signals "we picked
 * these for you," not "the algorithm output four equal cells."
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\PostTypes\get_display_config;
use function Standard\PostTypes\get_primary_category;

$related = \Standard\get_related_posts(4);

if (!$related->have_posts()) {
    return;
}

$posts = $related->posts;
$hero  = array_shift($posts);
?>

<section class="related-posts pt-6 lg:pt-12 border-t border-blue-200">
    <div class="flex items-baseline justify-between gap-4 mb-6 lg:mb-8">
        <h2 class="font-mono font-medium text-heading-sm lg:text-heading text-blue-900 leading-tight tracking-tight m-0">
            <?php esc_html_e('Read next', 'standard'); ?>
        </h2>
        <span class="font-mono uppercase tracking-widest text-caption text-blue-500">
            <?php esc_html_e('Related', 'standard'); ?>
        </span>
    </div>

    <?php
    $hero_id       = (int) $hero->ID;
    $hero_type     = get_post_type($hero);
    $hero_config   = get_display_config((string) $hero_type);
    $hero_category = get_primary_category($hero_id);
    $hero_title    = get_the_title($hero);
    $hero_aria     = sprintf(
        /* translators: %1$s post title, %2$s post-type-specific verb (e.g. "Read full article"). */
        __('%1$s. %2$s.', 'standard'),
        wp_strip_all_tags($hero_title),
        $hero_config['label']
    );
    ?>

    <article class="group relative grid md:grid-cols-[42%_1fr] bg-white border border-blue-200 transition-colors duration-200 hover:border-blue-500">
        <?php if (has_post_thumbnail($hero)) : ?>
            <div class="aspect-video md:aspect-auto md:h-full overflow-hidden border-b md:border-b-0 md:border-r border-blue-200 transition-colors duration-200 group-hover:border-blue-500">
                <?php echo get_the_post_thumbnail($hero, 'card-thumbnail', [
                    'class'   => 'w-full h-full object-cover',
                    'loading' => 'lazy',
                    'alt'     => '',
                ]); ?>
            </div>
        <?php endif; ?>

        <div class="p-5 lg:p-8 grid gap-3 content-center">
            <div class="flex items-center gap-3 font-mono uppercase tracking-widest text-caption">
                <span class="text-blue-500"><?php esc_html_e('Up next', 'standard'); ?></span>
                <?php if ($hero_category) : ?>
                    <span class="text-blue-300" aria-hidden="true">/</span>
                    <span class="text-blue-700"><?php echo esc_html($hero_category->name); ?></span>
                <?php endif; ?>
            </div>

            <h3 class="font-mono font-medium text-lg lg:text-heading-sm text-blue-900 leading-snug tracking-tight m-0">
                <a
                    href="<?php echo esc_url(get_permalink($hero)); ?>"
                    class="text-inherit no-underline hover:no-underline after:absolute after:inset-0 after:content-[''] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 group-hover:text-blue-500 transition-colors"
                    aria-label="<?php echo esc_attr($hero_aria); ?>"
                ><?php echo esc_html($hero_title); ?></a>
            </h3>

            <span class="inline-flex items-center gap-2 text-sm font-mono font-medium text-blue-500 mt-1">
                <?php echo esc_html($hero_config['cta']); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </span>
        </div>
    </article>

    <?php if (!empty($posts)) : ?>
        <ul class="mt-6 lg:mt-8 grid divide-y divide-blue-200 border-t border-blue-200 list-none m-0 p-0">
            <?php foreach ($posts as $secondary) :
                $sec_id   = (int) $secondary->ID;
                $type     = get_post_type($secondary);
                $config   = get_display_config((string) $type);
                $category = get_primary_category($sec_id);
                $title    = get_the_title($secondary);
            ?>
                <li class="m-0 p-0">
                    <a
                        href="<?php echo esc_url(get_permalink($secondary)); ?>"
                        class="group flex items-center gap-4 py-4 lg:py-5 no-underline hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    >
                        <span class="hidden sm:block w-24 shrink-0 font-mono uppercase tracking-widest text-caption text-blue-500">
                            <?php echo $category ? esc_html($category->name) : esc_html($config['cta']); ?>
                        </span>
                        <span class="flex-1 font-sans text-base lg:text-lg text-blue-700 leading-snug group-hover:text-blue-500 transition-colors">
                            <?php echo esc_html($title); ?>
                        </span>
                        <span class="shrink-0 text-blue-400 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all" aria-hidden="true">
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php
wp_reset_postdata();
