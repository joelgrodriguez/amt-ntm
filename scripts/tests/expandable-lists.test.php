<?php
/**
 * Regression checks for shared expandable card lists and SSQ3 accessories.
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$failures = [];

function expandable_lists_source(string $relative_path): string
{
    global $root, $failures;

    $source = file_get_contents($root . '/' . $relative_path);
    if ($source === false) {
        $failures[] = "Could not read {$relative_path}.";
        return '';
    }

    return $source;
}

function expandable_lists_assert(bool $condition, string $message): void
{
    global $failures;

    if (!$condition) {
        $failures[] = $message;
    }
}

$machine_data = expandable_lists_source('app/data/machines/ssq3-multipro.php');
expandable_lists_assert(
    str_contains($machine_data, "'product_tag'   => 'SSQIII'")
        && str_contains($machine_data, "'product_slugs' => ['remote-4-button-start']"),
    'SSQ3 must use its current accessory tag and include the Wireless Remote.'
);

$fitment_map = expandable_lists_source('app/inc/woo/accessories.php');
expandable_lists_assert(
    preg_match("/'remote-4-button-start'[^\n]+ssq3-multipro/", $fitment_map) === 1,
    'Wireless Remote fitment must include SSQ3 on accessory detail pages.'
);

$shared_toggle = 'templates/parts/expandable-list-toggle';
$templates = [
    'app/templates/parts/profile-expandable-list.php',
    'app/templates/woo/product/parts/accessories.php',
    'app/templates/woo/product/parts/default-accessories.php',
    'app/templates/template-service-hub-machine.php',
];

foreach ($templates as $template) {
    $source = expandable_lists_source($template);
    $expanded_position = strpos($source, 'data-expandable-list-expanded');
    $toggle_position = strpos($source, $shared_toggle);

    expandable_lists_assert(
        $expanded_position !== false && $toggle_position !== false && $toggle_position > $expanded_position,
        "{$template} must render the shared toggle after its expandable content."
    );

    expandable_lists_assert(
        str_contains($source, 'data-expandable-list-no-js-visible'),
        "{$template} must keep overflow content available without JavaScript."
    );
}

$module = expandable_lists_source('app/resources/js/modules/ExpandableList.js');
expandable_lists_assert(
    str_contains($module, "matchMedia('(prefers-reduced-motion: reduce)')")
        && str_contains($module, 'content.getBoundingClientRect().height')
        && str_contains($module, 'content.scrollHeight')
        && str_contains($module, 'timers.forEach'),
    'ExpandableList must measure both heights, respect reduced motion, and clean up timers.'
);

expandable_lists_assert(
    !file_exists($root . '/app/resources/js/modules/ProfileExpand.js')
        && !file_exists($root . '/app/resources/js/modules/RevealMore.js'),
    'Legacy profile and Service Hub reveal modules must not compete with ExpandableList.'
);

$service_queries = expandable_lists_source('app/inc/service-hub-machines.php');
expandable_lists_assert(
    str_contains($service_queries, "'posts_per_page'      => -1"),
    'Service Hub must render every item represented by its displayed total.'
);

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, array_map(
        static fn(string $failure): string => 'FAIL: ' . $failure,
        $failures
    )) . PHP_EOL);
    exit(1);
}

echo "Expandable-list checks passed.\n";
