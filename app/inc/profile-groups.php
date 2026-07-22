<?php
/**
 * Machine profile grouping helpers.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ProfileGroups;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Profile posts that represent rib-roller tooling rather than panel profiles.
 *
 * Slugs are stable across environments; database IDs are not.
 *
 * @var list<string>
 */
const RIB_ROLLER_SLUGS = [
    'bead-ribs-standard-or-wide',
    'pencil-ribs-small-or-large',
    'striation-ribs',
    'v-ribs-small-or-large',
];

/**
 * Separate panel profiles from rib-roller tooling while preserving order.
 *
 * Unknown or missing posts stay in the profile list so bad content data does
 * not silently disappear from the page.
 *
 * @param array<int, \WP_Post|int|string> $items
 * @return array{profiles: array<int, \WP_Post|int|string>, rib_rollers: array<int, \WP_Post|int|string>}
 */
function partition_machine_profiles(array $items): array
{
    $groups = [
        'profiles'    => [],
        'rib_rollers' => [],
    ];

    foreach ($items as $item) {
        if ($item instanceof \WP_Post) {
            $post = $item;
        } elseif (is_numeric($item) && (int) $item > 0) {
            $post = get_post((int) $item);
        } else {
            $post = null;
        }

        $group = $post instanceof \WP_Post && in_array($post->post_name, RIB_ROLLER_SLUGS, true)
            ? 'rib_rollers'
            : 'profiles';

        $groups[$group][] = $item;
    }

    return $groups;
}
