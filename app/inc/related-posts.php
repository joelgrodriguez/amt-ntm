<?php
/**
 * Related Posts functionality.
 *
 * Picks "related" posts using a small ranked scoring model rather than
 * raw recency:
 *
 *  1. Posts that share the editor-chosen *primary* category get a strong
 *     boost. Yoast and Rank Math both store this in post meta, and the
 *     Standard\PostTypes\get_primary_category() helper resolves it.
 *  2. Shared-category count: every additional category a candidate
 *     shares with the current post adds to its score. A post sharing
 *     3 categories beats a post sharing 1.
 *  3. Recency: newer posts win ties.
 *
 * If category matching returns fewer than $count results, the query is
 * topped up with posts that share at least one tag. Tag-fallback posts
 * are appended after category matches and scored separately so they
 * never outrank a real category match.
 *
 * Limited to post, video, download, and resource post types.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\PostTypes\get_primary_category;

/**
 * Allowed post types for related-post inclusion.
 */
const RELATED_POST_TYPES = ['post', 'video', 'download', 'resource'];

/**
 * How many candidates to fetch per pool before scoring.
 * We over-fetch so scoring has something to choose from.
 */
const RELATED_POOL_MULTIPLIER = 4;

/**
 * Transient cache for the final ranked ID list, keyed per post + count.
 * Same idiom as Standard\Woo\Cache (prefix + TTL + LIKE-based flush).
 */
const RELATED_CACHE_PREFIX = 'std_rel_';
const RELATED_CACHE_TTL = 12 * HOUR_IN_SECONDS;

/**
 * Get related posts for the current post.
 *
 * Returns a WP_Query whose results are ordered by the scoring model
 * described in the file docblock. The query is hydrated with explicit
 * post IDs (`post__in` + `orderby => post__in`) so the WP_Loop respects
 * the ranked order.
 *
 * @param int $count Number of posts to return. Default 4.
 * @return \WP_Query Query object with related posts in ranked order.
 */
function get_related_posts(int $count = 4): \WP_Query
{
    $post_id = (int) get_the_ID();
    if (!$post_id) {
        return new \WP_Query();
    }

    $cache_key   = RELATED_CACHE_PREFIX . $post_id . '_' . $count;
    $ordered_ids = get_transient($cache_key);

    if (!is_array($ordered_ids)) {
        $ordered_ids = rank_related_post_ids($post_id, $count);
        set_transient($cache_key, $ordered_ids, RELATED_CACHE_TTL);
    }

    if (empty($ordered_ids)) {
        return new \WP_Query();
    }

    return new \WP_Query([
        'post_type'           => RELATED_POST_TYPES,
        'posts_per_page'      => $count,
        'post__in'            => $ordered_ids,
        'orderby'             => 'post__in',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ]);
}

/**
 * Run the full scoring model and return ranked related post IDs.
 *
 * @param int $post_id Current post ID.
 * @param int $count   Number of IDs to return.
 * @return int[] Ranked post IDs, best match first.
 */
function rank_related_post_ids(int $post_id, int $count): array
{
    $categories       = get_the_category($post_id);
    $category_ids     = $categories ? array_map(fn($cat) => (int) $cat->term_id, $categories) : [];
    $primary_category = $category_ids ? get_primary_category($post_id) : null;
    $primary_id       = $primary_category ? (int) $primary_category->term_id : 0;

    $ranked = [];

    if ($category_ids) {
        $ranked = score_category_candidates(
            $post_id,
            $category_ids,
            $primary_id,
            $count * RELATED_POOL_MULTIPLIER
        );
    }
    if (count($ranked) < $count) {
        $tag_ids = wp_get_post_tags($post_id, ['fields' => 'ids']);
        if (!empty($tag_ids)) {
            $needed       = $count - count($ranked);
            $already_seen = array_keys($ranked);
            $tag_ranked   = score_tag_candidates(
                $post_id,
                $tag_ids,
                array_merge($already_seen, [$post_id]),
                $needed * RELATED_POOL_MULTIPLIER
            );
            $ranked = $ranked + $tag_ranked;
        }
    }

    if (empty($ranked)) {
        return [];
    }
    uasort($ranked, function ($a, $b) {
        if ($a['score'] === $b['score']) {
            return $b['date'] <=> $a['date'];
        }
        return $b['score'] <=> $a['score'];
    });

    return array_slice(array_keys($ranked), 0, $count);
}

/**
 * Get a candidate's term IDs from the object term cache.
 *
 * The pool queries prime the term cache for every candidate in one
 * query (`update_post_term_cache`), and get_the_terms() reads that
 * cache — unlike wp_get_post_categories()/wp_get_post_tags(), which
 * hit the DB per post.
 *
 * @param \WP_Post $candidate Candidate post.
 * @param string   $taxonomy  Taxonomy name.
 * @return int[] Term IDs.
 */
function get_candidate_term_ids(\WP_Post $candidate, string $taxonomy): array
{
    $terms = get_the_terms($candidate, $taxonomy);

    return is_array($terms)
        ? array_map(static fn(\WP_Term $term): int => (int) $term->term_id, $terms)
        : [];
}

/**
 * Score candidates pulled from shared-category pool.
 *
 * Each candidate scores 10 if it shares the primary category, plus
 * 1 per shared category overall (the primary category counts toward
 * both).
 *
 * @param int   $post_id         Current post ID.
 * @param int[] $category_ids    Categories of the current post.
 * @param int   $primary_id      Primary category ID, or 0 if none.
 * @param int   $pool_size       How many candidates to fetch before scoring.
 * @return array<int, array{score:int, date:string}> Map of post ID to score row.
 */
function score_category_candidates(int $post_id, array $category_ids, int $primary_id, int $pool_size): array
{
    $pool = get_posts([
        'post_type'           => RELATED_POST_TYPES,
        'posts_per_page'      => $pool_size,
        'post__not_in'        => [$post_id],
        'category__in'        => $category_ids,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters'    => false,
        'no_found_rows'       => true,
        // Prime term caches for the whole pool in one query so the
        // per-candidate term lookups below never hit the DB.
        'update_post_term_cache' => true,
    ]);

    $scored = [];
    foreach ($pool as $candidate) {
        $candidate_cats    = get_candidate_term_ids($candidate, 'category');
        $shared            = array_intersect($category_ids, $candidate_cats);
        $shared_count      = count($shared);

        if ($shared_count === 0) {
            continue;
        }

        $score = $shared_count;
        if ($primary_id && in_array($primary_id, $candidate_cats, true)) {
            $score += 10;
        }

        $scored[(int) $candidate->ID] = [
            'score' => $score,
            'date'  => $candidate->post_date,
        ];
    }

    return $scored;
}

/**
 * Score candidates pulled from shared-tag pool. Each tag overlap is
 * worth less than any category overlap (the highest tag score must
 * stay below the lowest possible category score of 1).
 *
 * Tag overlaps score in fractions so they always sort below the
 * smallest category match in the combined list.
 *
 * @param int   $post_id      Current post ID.
 * @param int[] $tag_ids      Tags of the current post.
 * @param int[] $exclude_ids  IDs to skip (already scored as category matches + current post).
 * @param int   $pool_size    Pool size to fetch before scoring.
 * @return array<int, array{score:float, date:string}>
 */
function score_tag_candidates(int $post_id, array $tag_ids, array $exclude_ids, int $pool_size): array
{
    $pool = get_posts([
        'post_type'           => RELATED_POST_TYPES,
        'posts_per_page'      => $pool_size,
        'post__not_in'        => $exclude_ids,
        'tag__in'             => $tag_ids,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
        // Prime term caches for the whole pool in one query so the
        // per-candidate term lookups below never hit the DB.
        'update_post_term_cache' => true,
    ]);

    $scored = [];
    foreach ($pool as $candidate) {
        $candidate_tags = get_candidate_term_ids($candidate, 'post_tag');
        $shared_count   = count(array_intersect($tag_ids, $candidate_tags));

        if ($shared_count === 0) {
            continue;
        }
        $scored[(int) $candidate->ID] = [
            'score' => min(0.99, $shared_count * 0.1),
            'date'  => $candidate->post_date,
        ];
    }

    return $scored;
}

/**
 * Flush all cached related-post ID lists.
 *
 * Same LIKE-based transient flush idiom as Standard\Woo\Cache\flush().
 * Hooked to save/delete of related post types and to category/tag
 * deletion, so editors see fresh relations immediately; the TTL is
 * the backstop for everything else.
 */
function flush_related_posts_cache(): void
{
    global $wpdb;
    $like = $wpdb->esc_like('_transient_' . RELATED_CACHE_PREFIX) . '%';
    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like)
    );
    $like_timeout = $wpdb->esc_like('_transient_timeout_' . RELATED_CACHE_PREFIX) . '%';
    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like_timeout)
    );
}

/**
 * Flush only when the changed post is a related-eligible type.
 */
function maybe_flush_related_posts_cache(int $post_id, \WP_Post $post): void
{
    if (in_array($post->post_type, RELATED_POST_TYPES, true)) {
        flush_related_posts_cache();
    }
}

add_action('save_post', __NAMESPACE__ . '\\maybe_flush_related_posts_cache', 10, 2);
add_action('deleted_post', __NAMESPACE__ . '\\maybe_flush_related_posts_cache', 10, 2);
add_action('delete_category', __NAMESPACE__ . '\\flush_related_posts_cache');
add_action('delete_post_tag', __NAMESPACE__ . '\\flush_related_posts_cache');
