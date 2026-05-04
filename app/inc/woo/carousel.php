<?php
/**
 * Woo product carousel card data.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Build carousel cards from a product or post query.
 *
 * @param array<string, mixed> $args
 * @return array<int, array{url: string, image_url: string, title: string, subtitle: string|null}>
 */
function get_cards(array $args): array {
    return match ($args['query_type'] ?? '') {
        'product' => get_product_cards((string) ($args['product_tag'] ?? ''), (int) ($args['limit'] ?? 12)),
        'post'    => get_post_cards($args),
        default   => [],
    };
}

/**
 * @return array<int, array{url: string, image_url: string, title: string, subtitle: string|null}>
 */
function get_product_cards(string $product_tag, int $limit = 12): array {
    if ($product_tag === '') {
        return [];
    }

    $products = \Standard\Woo\Cache\get_products([
        'tag'    => [$product_tag],
        'limit'  => $limit,
        'status' => 'publish',
    ]);

    $cards = [];
    foreach ($products as $product) {
        /** @var \WC_Product $product */
        $cards[] = [
            'url'       => $product->get_permalink(),
            'image_url' => $product->get_image_id()
                ? (string) \wp_get_attachment_image_url($product->get_image_id(), 'product-card')
                : '',
            'title'     => $product->get_name(),
            'subtitle'  => $product->get_price_html() ?: null,
        ];
    }

    return $cards;
}

/**
 * @param array<string, mixed> $args
 * @return array<int, array{url: string, image_url: string, title: string, subtitle: string|null}>
 */
function get_post_cards(array $args): array {
    $post_type    = (string) ($args['post_type'] ?? 'post');
    $tag_slugs    = $args['tag_slugs'] ?? [];
    $taxonomy     = (string) ($args['taxonomy'] ?? 'post_tag');
    $subtitle_tax = (string) ($args['subtitle_tax'] ?? 'category');
    $limit        = max(1, min(48, (int) ($args['limit'] ?? 24)));

    if (!is_array($tag_slugs) || empty($tag_slugs)) {
        return [];
    }

    $posts = \get_posts([
        'post_type'           => $post_type,
        'post_status'         => 'publish',
        'posts_per_page'      => $limit,
        'orderby'             => 'menu_order title',
        'order'               => 'ASC',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        'tax_query'           => [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $tag_slugs,
            ],
        ],
    ]);

    $cards = [];
    foreach ($posts as $post) {
        $terms     = \get_the_terms($post->ID, $subtitle_tax);
        $subtitle  = (!empty($terms) && !\is_wp_error($terms)) ? $terms[0]->name : '';
        $image_url = \has_post_thumbnail($post)
            ? (string) \get_the_post_thumbnail_url($post, 'product-card')
            : '';

        $cards[] = [
            'url'       => \get_permalink($post),
            'image_url' => $image_url,
            'title'     => \get_the_title($post),
            'subtitle'  => $subtitle ?: null,
        ];
    }

    return $cards;
}
