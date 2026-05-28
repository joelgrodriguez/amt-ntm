<?php
/**
 * Learning Center filtered-results view.
 *
 * Renders when any of lc_category / lc_type / lc_machine is active.
 * Replaces the segmented home.php sections with a single mixed grid
 * across all LC post types.
 *
 * Args
 * ----
 *  filters    : array{category: string, machine: string, type: string}
 *  base_url   : string  the LC landing URL (used for clear / chip removal)
 *  paged      : int     current page number
 *
 * @package Standard
 *
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\filtered_post_type;
use function Standard\LearningCenter\get_category_filter_options;
use function Standard\LearningCenter\get_machine_filter_options;
use function Standard\LearningCenter\get_type_filter_options;

$filters  = isset($args['filters']) && is_array($args['filters']) ? $args['filters'] : [];
$base_url = isset($args['base_url']) ? (string) $args['base_url'] : '';
$paged    = isset($args['paged']) ? max(1, (int) $args['paged']) : 1;

$active = [
    'category' => (string) ($filters['category'] ?? ''),
    'machine'  => (string) ($filters['machine'] ?? ''),
    'type'     => (string) ($filters['type'] ?? ''),
];

// Build remove-URL helper: drop the named filter, keep the others.
$remove_url = static function (string $drop_key) use ($active, $base_url): string {
    $query = [];
    foreach (['category' => 'lc_category', 'machine' => 'lc_machine', 'type' => 'lc_type'] as $key => $param) {
        if ($key === $drop_key) {
            continue;
        }
        $value = $active[$key];
        if ($value !== '') {
            $query[$param] = $value;
        }
    }

    return $query === [] ? $base_url : add_query_arg($query, $base_url);
};

// Label resolvers — fall back to the slug if the option isn't in the list.
$category_options = get_category_filter_options(false);
$type_options     = get_type_filter_options(false);
$machine_options  = get_machine_filter_options(false);

$chips = [];
if ($active['type'] !== '') {
    $chips[] = [
        'label'      => (string) ($type_options[$active['type']] ?? $active['type']),
        'remove_url' => $remove_url('type'),
    ];
}
if ($active['category'] !== '') {
    $chips[] = [
        'label'      => (string) ($category_options[$active['category']] ?? $active['category']),
        'remove_url' => $remove_url('category'),
    ];
}
if ($active['machine'] !== '') {
    $chips[] = [
        'label'      => (string) ($machine_options[$active['machine']] ?? $active['machine']),
        'remove_url' => $remove_url('machine'),
    ];
}

// One mixed query across all LC post types (or just the selected type).
$query_args = [
    'post_type'           => filtered_post_type($active),
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'post_status'         => 'publish',
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
];
if ($active['category'] !== '') {
    $query_args['category_name'] = $active['category'];
}
if ($active['machine'] !== '') {
    $query_args['tag'] = $active['machine'];
}

$results = new \WP_Query($query_args);
$total   = (int) $results->found_posts;
?>

<header class="grid gap-4 mb-6 lg:mb-8">
    <div class="flex flex-wrap items-baseline gap-x-4 gap-y-2">
        <h2 class="font-sans font-semibold text-heading-sm lg:text-heading text-blue-900 leading-tight tracking-tight m-0">
            <?php
            echo esc_html(sprintf(
                /* translators: %d total result count. */
                _n('%d result', '%d results', $total, 'standard'),
                $total
            ));
            ?>
        </h2>
        <span class="font-mono uppercase tracking-widest text-caption text-blue-500">
            <?php esc_html_e('Filtered view', 'standard'); ?>
        </span>
    </div>

    <?php
    get_template_part('templates/parts/filter-chips', null, [
        'chips'     => $chips,
        'clear_url' => $base_url,
        'label'     => __('Active Learning Center filters', 'standard'),
    ]);
    ?>
</header>

<?php if ($results->have_posts()) : ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php while ($results->have_posts()) : $results->the_post(); ?>
            <?php get_template_part('templates/parts/card-post'); ?>
        <?php endwhile; ?>
    </div>

    <?php
    $total_pages = (int) $results->max_num_pages;
    if ($total_pages > 1) :
        $pagination = paginate_links([
            'base'      => add_query_arg('paged', '%#%', $base_url) . '&' . http_build_query(array_filter([
                'lc_category' => $active['category'],
                'lc_type'     => $active['type'],
                'lc_machine'  => $active['machine'],
            ])),
            'format'    => '',
            'current'   => $paged,
            'total'     => $total_pages,
            'prev_text' => __('Previous', 'standard'),
            'next_text' => __('Next', 'standard'),
            'type'      => 'list',
            'end_size'  => 1,
            'mid_size'  => 1,
        ]);
    ?>
        <nav class="mt-10 lg:mt-12" aria-label="<?php esc_attr_e('Results pagination', 'standard'); ?>">
            <?php echo wp_kses_post((string) $pagination); ?>
        </nav>
    <?php endif; ?>

<?php else : ?>
    <div class="bg-blue-50 border border-blue-200 p-8 lg:p-10 text-center grid gap-4 justify-items-center">
        <p class="font-mono uppercase tracking-widest text-caption text-blue-500 m-0">
            <?php esc_html_e('No results', 'standard'); ?>
        </p>
        <p class="text-blue-700 max-w-prose m-0">
            <?php esc_html_e('Nothing in the Learning Center matches every active filter. Try clearing one to widen the search.', 'standard'); ?>
        </p>
        <a href="<?php echo esc_url($base_url); ?>"
           class="inline-flex items-center gap-2 px-5 py-3 border border-blue-900 text-blue-900 font-mono font-medium text-sm uppercase tracking-widest no-underline hover:bg-blue-900 hover:text-white transition-colors">
            <?php esc_html_e('Clear filters', 'standard'); ?>
        </a>
    </div>
<?php endif;

wp_reset_postdata();
