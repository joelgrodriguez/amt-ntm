<?php

declare(strict_types=1);

define('ABSPATH', __DIR__);

final class WP_Post
{
    public function __construct(
        public int $ID,
        public string $post_name
    ) {
    }
}

/** @var array<int, WP_Post> */
$ntm_profile_posts = [];

function get_post(int $post_id): ?WP_Post
{
    return $GLOBALS['ntm_profile_posts'][$post_id] ?? null;
}

require __DIR__ . '/../../app/inc/profile-groups.php';

function ntm_profile_groups_assert_same(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            $message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.'
        );
    }
}

$panel = new WP_Post(1003, 'ss100');
$clip_relief = new WP_Post(1045, 'clip-relief');
$bead_ribs = new WP_Post(1046, 'bead-ribs-standard-or-wide');
$pencil_ribs = new WP_Post(1047, 'pencil-ribs-small-or-large');
$striation_ribs = new WP_Post(1049, 'striation-ribs');
$v_ribs = new WP_Post(1048, 'v-ribs-small-or-large');
$additional_profiles = [
    new WP_Post(1005, 'ss150'),
    new WP_Post(1008, 'ss450'),
    new WP_Post(1009, 'ss450sl'),
    new WP_Post(1012, 'ff100'),
    new WP_Post(1013, 'ff150'),
    new WP_Post(1014, 't-panel'),
];

$groups = \Standard\ProfileGroups\partition_machine_profiles([
    $panel,
    $clip_relief,
    ...$additional_profiles,
    $bead_ribs,
    $pencil_ribs,
    $striation_ribs,
    $v_ribs,
]);

ntm_profile_groups_assert_same(
    [$panel, $clip_relief, ...$additional_profiles],
    $groups['profiles'],
    'SSH should retain its eight profiles, including Clip Relief, in the profile group.'
);
ntm_profile_groups_assert_same(
    [$bead_ribs, $pencil_ribs, $striation_ribs, $v_ribs],
    $groups['rib_rollers'],
    'Known rib-roller posts should move to the rib-roller group in source order.'
);

$GLOBALS['ntm_profile_posts'][1046] = $bead_ribs;
$id_groups = \Standard\ProfileGroups\partition_machine_profiles([1003, 1046]);
ntm_profile_groups_assert_same([1003], $id_groups['profiles'], 'Missing IDs should remain visible as profiles.');
ntm_profile_groups_assert_same([1046], $id_groups['rib_rollers'], 'Numeric IDs should resolve before grouping.');

echo "Profile grouping tests passed.\n";
