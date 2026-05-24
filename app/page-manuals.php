<?php
/**
 * Template Name: Manuals
 *
 * Catalog landing for the manual library.
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
        'category_id' => 601,
        'eyebrow'     => __('Seamless Gutter', 'standard'),
        'title'       => __('Gutter Machine Manuals', 'standard'),
        'section_id'  => 'manuals-gutter',
    ],
    [
        'category_id' => 602,
        'eyebrow'     => __('Roof & Wall Panels', 'standard'),
        'title'       => __('Roof & Wall Panel Machine Manuals', 'standard'),
        'section_id'  => 'manuals-roof-wall',
    ],
];

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/manuals/hero'); ?>

    <section class="bg-white pt-12 pb-24 lg:pt-16 lg:pb-32">
        <div class="container layout-with-rail">

            <?php
            get_template_part('templates/parts/taxonomy-filter-sidebar', null, [
                'post_type'   => 'manual',
                'collapsible' => false,
                'sections' => [
                    [
                        'title'         => $sidebar_copy['filter_type'],
                        'icon'          => 'filter',
                        'terms'         => get_terms_for_post_type('manual', 'category'),
                        'current_terms' => [],
                    ],
                    [
                        'title'         => $sidebar_copy['filter_machine'],
                        'icon'          => 'settings',
                        'terms'         => get_terms_for_post_type('manual', 'post_tag'),
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
                    <?php get_template_part('templates/pages/manuals/catalog-section', null, $section); ?>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
