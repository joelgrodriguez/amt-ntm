<?php
/**
 * Regression checks for the Corbel website plugin integration.
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$failures = [];

function check(bool $condition, string $message): void
{
    global $failures;

    if (!$condition) {
        $failures[] = $message;
    }
}

function read_theme_file(string $relative): string
{
    global $root;

    $contents = @file_get_contents($root . '/' . $relative);

    return is_string($contents) ? $contents : '';
}

$integration = read_theme_file('app/inc/corbel.php');
$functions = read_theme_file('app/functions.php');
$configurator_shell = read_theme_file('app/templates/template-configurator-shell.php');
$corbel_template = read_theme_file('app/templates/template-corbel.php');

check(
    str_contains($integration, "const SCRIPT_URL = 'https://plugins.corbelpay.com/newtechmachinery/corbel.js';"),
    'Corbel must use the production New Tech Machinery loader URL.'
);
check(
    str_contains($integration, "add_action('wp_head', __NAMESPACE__ . '\\\\render_loader', 1);"),
    'Configurator pages must emit the Corbel loader from the document head.'
);
check(
    str_contains($integration, 'function is_configurator_request(): bool'),
    'The eager Corbel loader must be limited to configurator requests.'
);
check(
    str_contains($integration, "\\has_block('corbel/configurator')"),
    'Corbel configurator blocks must retain their loader outside route templates.'
);
check(
    str_contains($integration, 'function take_frontend_loader_ownership(): void')
        && str_contains($integration, "'assistant_policy'] = 'never'"),
    'The installed plugin must not load its floating assistant.'
);
check(
    str_contains($integration, 'id="corbelConfigurator"'),
    'Configurator target must use the exact Corbel ID.'
);
check(
    !str_contains($integration, 'function render_assistant_placeholder'),
    'The theme must not render the floating assistant target.'
);
check(
    str_contains($integration, 'data-corbel-configurator-url'),
    'Configurator targets must preserve campaign parameters through an explicit URL.'
);
check(
    str_contains($functions, "'inc/corbel.php'"),
    'Corbel integration must be loaded by the theme bootstrap.'
);
check(
    str_contains($configurator_shell, '\\Standard\\Corbel\\render_configurator_placeholder();'),
    'The configurator page tree must render the Corbel target.'
);
check(
    str_contains($corbel_template, '\\Standard\\Corbel\\render_configurator_placeholder();'),
    'The legacy Corbel template must render the Corbel target.'
);

foreach ([
    "'5vc'           => '5vc-crimp-panel'",
    "'wav'           => 'wav-wall-panel'",
    "'ssh'           => 'ssh-multipro-panel'",
    "'ssr'           => 'ssr-multipro-panel'",
    "'ssqii'         => 'ssq2-multipro-panel'",
    "'ssq3-multi-pro' => 'ssq3'",
    "'machii'        => 'mach2-gutter'",
] as $mapping) {
    check(
        str_contains($integration, $mapping),
        'Missing Corbel product mapping: ' . $mapping
    );
}

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, array_map(static fn(string $failure): string => 'FAIL: ' . $failure, $failures)) . PHP_EOL);
    exit(1);
}

echo "Corbel plugin checks passed.\n";
