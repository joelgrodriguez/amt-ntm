<?php
/**
 * Mega Menu — Profile card.
 *
 * Thin wrapper over card-profile.php (context='mega'). Kept as a
 * separate file so the mega-menu rendering pipeline can call it
 * directly without context-flag knowledge.
 *
 * Args:
 *   profile (WP_Post): the profile post object
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$profile = $args['profile'] ?? null;
if (!$profile instanceof \WP_Post) {
    return;
}

get_template_part('templates/parts/card-profile', null, [
    'profile' => $profile,
    'context' => 'mega',
]);
