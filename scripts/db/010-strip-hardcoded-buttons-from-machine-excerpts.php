<?php

/**
 * Remove trailing hard-coded CTA button containers from machine product excerpts.
 *
 * This file is executed by wp eval-file from the shell migration. Keep the
 * target query and HTML guard here so multiline excerpts never cross a shell
 * quoting boundary.
 */

$dry = getenv('NTM_DRY_RUN') !== '0';
$category_slugs = ['gutter-machines', 'roof-wall-panel-machines'];

/**
 * @return list<int>
 */
function ntm_010_machine_excerpt_product_ids(array $category_slugs): array
{
    $ids = get_posts([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'ID',
        'order'          => 'ASC',
        'fields'         => 'ids',
        'tax_query'      => [
            [
                'taxonomy'         => 'product_cat',
                'field'            => 'slug',
                'terms'            => $category_slugs,
                'operator'         => 'IN',
                'include_children' => false,
            ],
        ],
    ]);

    return array_values(array_map('intval', $ids));
}

function ntm_010_anchor_has_btn_class(string $anchor): bool
{
    if (!preg_match('/\sclass\s*=\s*(["\'])(.*?)\1/is', $anchor, $match)) {
        return false;
    }

    $class_value = html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $classes = preg_split('/\s+/', trim($class_value), -1, PREG_SPLIT_NO_EMPTY);

    return is_array($classes) && in_array('btn', $classes, true);
}

function ntm_010_strip_trailing_button_container(string $excerpt): string
{
    if (trim($excerpt) === '') {
        return $excerpt;
    }

    if (!preg_match_all('/<div\b[^>]*>.*?<\/div>/is', $excerpt, $div_matches, PREG_OFFSET_CAPTURE)) {
        return $excerpt;
    }

    $last_div = end($div_matches[0]);
    if (!is_array($last_div)) {
        return $excerpt;
    }

    [$container, $offset] = $last_div;
    $after_container = substr($excerpt, $offset + strlen($container));

    if (trim($after_container) !== '') {
        return $excerpt;
    }

    if (!preg_match('/^<div\b[^>]*>(.*)<\/div>$/is', $container, $inner_match)) {
        return $excerpt;
    }

    $inner = $inner_match[1];

    if (!preg_match_all('/<a\b[^>]*>.*?<\/a>/is', $inner, $anchor_matches)) {
        return $excerpt;
    }

    $anchors = $anchor_matches[0];
    $anchor_count = count($anchors);

    if ($anchor_count < 1 || $anchor_count > 3) {
        return $excerpt;
    }

    foreach ($anchors as $anchor) {
        if (!ntm_010_anchor_has_btn_class($anchor)) {
            return $excerpt;
        }
    }

    $remainder = str_replace($anchors, '', $inner);
    if (trim($remainder) !== '') {
        return $excerpt;
    }

    return rtrim(substr($excerpt, 0, $offset));
}

if (getenv('NTM_010_LOAD_ONLY') === '1') {
    return;
}

$ids = ntm_010_machine_excerpt_product_ids($category_slugs);

if ($ids === []) {
    echo "    no published machine products found in target categories.\n";
    return;
}

$changed = 0;
$unchanged = 0;
$errors = 0;

foreach ($ids as $id) {
    $post = get_post($id);
    if (!$post instanceof WP_Post) {
        echo "    skip: product {$id} not found.\n";
        continue;
    }

    $excerpt = (string) $post->post_excerpt;
    $cleaned = ntm_010_strip_trailing_button_container($excerpt);

    if ($cleaned === $excerpt) {
        $unchanged++;
        continue;
    }

    if ($dry) {
        echo "    [dry-run] would strip trailing excerpt CTA from product {$id} ({$post->post_name}).\n";
        $changed++;
        continue;
    }

    $result = wp_update_post([
        'ID'           => $id,
        'post_excerpt' => $cleaned,
    ], true);

    if (is_wp_error($result)) {
        echo "    error: product {$id} ({$post->post_name}) update failed: " . $result->get_error_message() . "\n";
        $errors++;
        continue;
    }

    echo "    stripped trailing excerpt CTA from product {$id} ({$post->post_name}).\n";
    $changed++;
}

echo $dry
    ? "    dry-run summary: {$changed} would change, {$unchanged} already clean, " . count($ids) . " inspected.\n"
    : "    summary: {$changed} changed, {$unchanged} already clean, " . count($ids) . " inspected.\n";

if ($errors > 0) {
    exit(1);
}
