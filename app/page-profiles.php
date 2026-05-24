<?php
/**
 * Template Name: Profiles
 *
 * Catalog landing for the panel and gutter profile library.
 *
 * Layout mirrors single-profile.php: a sticky filter sidebar on the
 * left (Type + Machine), with the catalog stacked into three labelled
 * sections on the right (Roof & Wall, Gutter, Clip Relief / Rib Rollers).
 *
 * Canonical category term IDs were resolved against the live post set:
 *   599 = Metal roof & wall panel profiles (22 published)
 *   598 = Gutter profiles                  ( 8 published)
 *   603 = Clip Relief / Rib Rollers        ( 5 published)
 * All three are children of parent category 597 ("Profiles").
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\ContentTaxonomy\get_terms_for_post_type;

$sidebar_copy = [
    'filter_type'    => __('Filter by Type', 'standard'),
    'filter_machine' => __('Filter by Machine', 'standard'),
];

$sections = [
    [
        'category_id' => 599,
        'eyebrow'     => __('Roof & Wall Panels', 'standard'),
        'title'       => __('Roof & Wall Panel Profiles', 'standard'),
        'section_id'  => 'profiles-roof-wall',
    ],
    [
        'category_id' => 598,
        'eyebrow'     => __('Seamless Gutter', 'standard'),
        'title'       => __('Gutter Profiles', 'standard'),
        'section_id'  => 'profiles-gutter',
    ],
    [
        'category_id' => 603,
        'eyebrow'     => __('Accessories', 'standard'),
        'title'       => __('Clip Relief & Rib Rollers', 'standard'),
        'section_id'  => 'profiles-clip-relief',
    ],
];

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/profiles/hero'); ?>

    <section class="bg-white pt-12 pb-24 lg:pt-16 lg:pb-32">
        <div class="container layout-with-rail">

            <?php
            get_template_part('templates/parts/taxonomy-filter-sidebar', null, [
                'post_type' => 'profile',
                'sections' => [
                    [
                        'title'         => $sidebar_copy['filter_type'],
                        'icon'          => 'filter',
                        'terms'         => get_terms_for_post_type('profile', 'category'),
                        'current_terms' => [],
                    ],
                    [
                        'title'         => $sidebar_copy['filter_machine'],
                        'icon'          => 'settings',
                        'terms'         => get_terms_for_post_type('profile', 'post_tag'),
                        'current_terms' => [],
                    ],
                ],
            ]);
            ?>

            <div>
                <?php
                $last_index = count($sections) - 1;
                foreach ($sections as $index => $section) :
                    $section['is_first'] = $index === 0;
                    $section['is_last']  = $index === $last_index;
                ?>
                    <?php get_template_part('templates/pages/profiles/catalog-section', null, $section); ?>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
