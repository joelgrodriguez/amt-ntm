<?php
/**
 * Guard the flat NTM visual system against decorative shadows.
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$failures = [];

function contains_nonapproved_shadow(string $line): bool
{
    $has_tailwind_apply = preg_match(
        '/@apply[^;]*\b(?:drop-)?shadow(?:-(?!none\b)[^\s;]+)?(?=\s|;)/',
        $line
    ) === 1;
    $has_tailwind_class = preg_match(
        '/\bclass\s*=\s*["\'][^"\']*(?<![\w-])(?:drop-)?shadow(?:-(?!none\b)[^\s"\']+)?(?=\s|["\'])/',
        $line
    ) === 1;

    return preg_match('/\bbox-shadow\s*:/', $line) === 1
        || preg_match('/\btext-shadow\s*:/', $line) === 1
        || preg_match('/\bdrop-shadow\s*\(/', $line) === 1
        || preg_match('/\bshadow(?:Color|Blur|OffsetX|OffsetY)\b/', $line) === 1
        || $has_tailwind_apply
        || $has_tailwind_class;
}

$allowed_lines = [
    'app/resources/css/components/filters.css' => [
        'box-shadow: inset 0 0 0 2px var(--color-blue-500);',
        'box-shadow: 0 0 0 2px var(--color-white), 0 0 0 4px var(--color-blue-500);',
        'box-shadow: inset 0 0 0 2px var(--color-red);',
    ],
    'app/resources/css/components/forms.css' => [
        'box-shadow: 0 0 0 2px var(--color-white), 0 0 0 4px var(--color-blue-500);',
        'box-shadow: 0 0 0 2px var(--color-blue-900), 0 0 0 4px var(--color-blue-500);',
    ],
    'app/resources/css/components/hero-overlay.css' => [
        'text-shadow: 0 1px 2px color-mix(in srgb, var(--color-blue-900) 20%, transparent);',
    ],
    'app/resources/css/components/hubspot-form.css' => [
        'box-shadow: none !important;',
    ],
    'app/resources/css/components/search-modal.css' => [
        'box-shadow: inset 0 0 0 2px var(--color-blue-500);',
    ],
    'app/resources/css/pages/roi-calculator.css' => [
        'box-shadow: inset 0 0 0 1px var(--color-blue-500); /* reads as 2px border, no layout shift */',
        'box-shadow: inset 0 0 0 1px var(--color-blue-500);',
    ],
    'app/resources/css/pages/single.css' => [
        'box-shadow: inset 0 0 0 1px var(--color-blue-500, #2563eb);',
        'box-shadow: none;',
    ],
];

foreach (
    [
        '@apply border shadow;',
        '@apply drop-shadow-lg;',
        '<div class="shadow">',
        '<div class="md:shadow-lg">',
        '<div class="drop-shadow">',
    ] as $fixture
) {
    if (!contains_nonapproved_shadow($fixture)) {
        $failures[] = "Shadow guard missed test fixture: {$fixture}";
    }
}

foreach (['@apply shadow-none;', '<div class="shadow-none md:shadow-none">'] as $fixture) {
    if (contains_nonapproved_shadow($fixture)) {
        $failures[] = "Shadow guard rejected approved reset fixture: {$fixture}";
    }
}

$source_root = $root . '/app';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source_root));

foreach ($iterator as $file) {
    if (!$file->isFile() || !in_array($file->getExtension(), ['css', 'js', 'php'], true)) {
        continue;
    }

    $path = $file->getPathname();
    if (str_contains($path, DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR)) {
        continue;
    }

    $relative = str_replace($root . DIRECTORY_SEPARATOR, '', $path);
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        $failures[] = "Could not read {$relative}.";
        continue;
    }

    foreach ($lines as $index => $line) {
        $trimmed = trim($line);

        if (!contains_nonapproved_shadow($line) || in_array($trimmed, $allowed_lines[$relative] ?? [], true)) {
            continue;
        }

        $failures[] = sprintf('%s:%d contains a non-approved shadow: %s', $relative, $index + 1, $trimmed);
    }
}

$theme_json = json_decode((string) file_get_contents($root . '/app/theme.json'), true);
$default_shadow_presets = $theme_json['settings']['shadow']['defaultPresets'] ?? null;
if ($default_shadow_presets !== false) {
    $failures[] = 'app/theme.json must set settings.shadow.defaultPresets to false.';
}

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, array_map(static fn(string $failure): string => 'FAIL: ' . $failure, $failures)) . PHP_EOL);
    exit(1);
}

echo "Design-system shadow checks passed.\n";
