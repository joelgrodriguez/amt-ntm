<?php
/**
 * Template part for displaying mixed result cards.
 *
 * Global search and scoped taxonomy archives can return different post
 * types. Dispatch each result to its native card so products feel like
 * products, profiles feel like profiles, and editorial content keeps the
 * standard post-card treatment.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$post_type = (string) get_post_type();

if ($post_type === 'product') {
    get_template_part('templates/parts/card-product', null, [
        'product' => \Standard\Search\get_product_card_data((int) get_the_ID()),
    ]);
    return;
}

if ($post_type === 'profile') {
    get_template_part('templates/parts/card-profile', null, [
        'profile' => get_post(),
        'context' => 'grid',
    ]);
    return;
}

if ($post_type === 'manual') {
    get_template_part('templates/parts/card-manual', null, [
        'manual'  => get_post(),
        'context' => 'grid',
    ]);
    return;
}

get_template_part('templates/parts/card-post');
