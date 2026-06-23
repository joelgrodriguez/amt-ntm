<?php
/**
 * export-copy.php — extract pillar/landing page copy from PHP into review CSVs.
 *
 * The marketing copy on NTM's pillar/landing pages is hardcoded in PHP (the
 * pages have empty post_content; nothing is editable in WP admin). This script
 * pulls every translatable __('...', 'standard') string out of the files listed
 * in manifest.php and writes one CSV row per string, so the content team can
 * review and revise the copy in Excel / Google Sheets, then hand it back for a
 * human-reviewed re-apply to the code.
 *
 * Why token_get_all() and not regex: PHP's tokenizer reads the source the way
 * PHP itself does, so apostrophes ("don't"), "$2M+", commas, and both quote
 * styles inside a __() call are captured correctly. Regex would mangle them.
 *
 * Read-only: it parses theme files, it does not bootstrap WordPress and does not
 * touch the database. Run it with any PHP 8 CLI:
 *
 *     php scripts/content/export-copy.php          (or: npm run content:export)
 *
 * Output (gitignored): scripts/content/exports/
 *     copy-review-<page>.csv   one file per page, focused
 *     copy-review-all.csv      every row, combined
 *
 * CSV columns: page, section, field, key, current_content, new_content
 *   - key is the match anchor for re-apply: "<relpath>::__#<N>" (the Nth __()
 *     call in that file). The team must NOT edit key or current_content; those
 *     are how the apply step finds the exact string to change.
 *
 * @package Standard
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "export-copy.php must be run from the command line.\n");
    exit(1);
}

// Theme root is two levels up from scripts/content/.
$themeRoot = dirname(__DIR__, 2);
$manifest  = require __DIR__ . '/manifest.php';
$outDir    = __DIR__ . '/exports';

if (!is_dir($outDir) && !mkdir($outDir, 0775, true) && !is_dir($outDir)) {
    fwrite(STDERR, "Could not create output dir: {$outDir}\n");
    exit(1);
}

$dataFile = $themeRoot . '/app/inc/machines-data.php';

/**
 * Keys whose values are never user-facing copy (URLs, icon names, enums, slugs,
 * image refs). Used to filter array-pair extraction in data functions so the
 * CSV holds prose, not plumbing.
 */
const NON_COPY_KEYS = [
    'url', 'kind', 'icon', 'slug', 'id', 'image', 'img', 'video', 'href',
    'tag', 'tags', 'tag_slugs', 'class', 'svg', 'name', 'type', 'key',
    'product_tag', 'value',
];

$header   = ['page', 'section', 'field', 'key', 'current_content', 'new_content'];
$allRows  = [];
$perPage  = [];
$seenKeys = []; // guard against duplicate keys within a single file

foreach ($manifest as $pageSlug => $page) {
    $rows = [];

    // 1. Files (templates + parts), expanding globs in manifest order.
    foreach ($page['paths'] as $relPath) {
        $matches = glob($themeRoot . '/' . $relPath, GLOB_BRACE) ?: [];
        sort($matches); // deterministic ordering across runs
        foreach ($matches as $absFile) {
            $rel = ltrim(str_replace($themeRoot, '', $absFile), '/');
            foreach (extract_strings_from_file($absFile) as $hit) {
                $rows[] = build_row($pageSlug, $rel, $hit, $seenKeys);
            }
        }
    }

    // 2. Data functions in machines-data.php — extract only the named functions'
    //    bodies, so a page that uses get_faq_items() gets those FAQ strings.
    //    These are pure content arrays: some entries are wrapped in __()
    //    (ironclad/portability/safety), most are plain 'key' => 'literal' pairs
    //    (FAQs, UNIQ resources, ROI, differentiators). We harvest BOTH — any
    //    'copy-key' => 'string' pair — and skip non-copy keys (url, icon, kind).
    if (!empty($page['functions']) && is_file($dataFile)) {
        $fnRanges = function_token_ranges($dataFile);
        $dataTokens = token_get_all((string) file_get_contents($dataFile));
        foreach ($page['functions'] as $fnName) {
            if (!isset($fnRanges[$fnName])) {
                fwrite(STDERR, "  warning: function {$fnName}() not found in machines-data.php (page: {$pageSlug})\n");
                continue;
            }
            $rel = 'app/inc/machines-data.php';
            foreach (extract_array_pairs($dataTokens, $fnRanges[$fnName]) as $hit) {
                $rows[] = build_data_row($pageSlug, $rel, $fnName, $hit, $seenKeys);
            }
        }
    }

    $perPage[$pageSlug] = $rows;
    foreach ($rows as $r) {
        $allRows[] = $r;
    }

    // Write per-page CSV.
    $pagePath = $outDir . '/copy-review-' . $pageSlug . '.csv';
    write_csv($pagePath, $header, $rows);
    printf("  %-26s %4d strings -> %s\n", $page['label'], count($rows), basename($pagePath));
}

// Combined CSV.
$allPath = $outDir . '/copy-review-all.csv';
write_csv($allPath, $header, $allRows);

printf("\nDone. %d total strings across %d pages.\n", count($allRows), count($manifest));
printf("Combined: %s\n", str_replace($themeRoot . '/', '', $allPath));

/* ----------------------------------------------------------------------- */

/**
 * Build one CSV row from an extracted hit.
 *
 * @param array{n: int, text: string, field: string} $hit
 * @param array<string, int> $seenKeys passed by reference for de-dup safety
 */
function build_row(string $pageSlug, string $rel, array $hit, array &$seenKeys, ?string $fnSuffix = null): array
{
    $keyBase = $rel . ($fnSuffix !== null ? '::' . $fnSuffix . '()' : '');
    $key     = $keyBase . '::__#' . $hit['n'];

    // Extremely unlikely, but keep keys unique if a future change collides.
    if (isset($seenKeys[$key])) {
        $key .= '.' . (++$seenKeys[$key]);
    } else {
        $seenKeys[$key] = 0;
    }

    return [
        'page'            => $pageSlug,
        'section'         => section_label($rel, $fnSuffix),
        'field'           => $hit['field'],
        'key'             => $key,
        'current_content' => $hit['text'],
        'new_content'     => '',
    ];
}

/**
 * Build a CSV row from a data-function array-pair hit.
 *
 * @param array{n: int, text: string, field: string} $hit
 * @param array<string, int> $seenKeys passed by reference for de-dup safety
 */
function build_data_row(string $pageSlug, string $rel, string $fnName, array $hit, array &$seenKeys): array
{
    $key = $rel . '::' . $fnName . '()::pair#' . $hit['n'];

    if (isset($seenKeys[$key])) {
        $key .= '.' . (++$seenKeys[$key]);
    } else {
        $seenKeys[$key] = 0;
    }

    return [
        'page'            => $pageSlug,
        'section'         => section_label($rel, $fnName),
        'field'           => $hit['field'],
        'key'             => $key,
        'current_content' => $hit['text'],
        'new_content'     => '',
    ];
}

/**
 * Humanize a file path (or function name) into a section label for reviewers.
 * e.g. app/templates/parts/about/manifesto.php -> "Manifesto"
 *      get_faq_items                            -> "Faq Items"
 *      app/front-page.php                       -> "Front Page"
 */
function section_label(string $rel, ?string $fnSuffix): string
{
    if ($fnSuffix !== null) {
        $name = preg_replace('/^get_/', '', $fnSuffix);
        return ucwords(str_replace(['_'], ' ', (string) $name));
    }

    $base = basename($rel, '.php');
    $base = preg_replace('/^page-/', '', $base);
    return ucwords(str_replace(['-', '_'], ' ', (string) $base));
}

/**
 * Extract every __('literal', 'standard') string from a file (optionally
 * limited to a token-index range, used to scope to one function body).
 *
 * Returns hits in source order, each: ['n' => Nth __() in file, 'text' => msg,
 * 'field' => inferred role from the preceding array key, else 'text'].
 *
 * The N counter is per-file across the WHOLE file (not per-range), so a key is
 * stable whether or not we later scope by function. That keeps "__#7" meaning
 * the same call regardless of how the manifest references the file.
 *
 * @param array{0: int, 1: int}|null $tokenRange [startIdx, endIdx] inclusive
 * @return array<int, array{n: int, text: string, field: string}>
 */
function extract_strings_from_file(string $absFile, ?array $tokenRange = null): array
{
    $src    = file_get_contents($absFile);
    if ($src === false) {
        return [];
    }
    $tokens = token_get_all($src);
    $count  = count($tokens);

    $hits      = [];
    $callIndex = 0; // Nth __() call seen in this file, 1-based.

    for ($i = 0; $i < $count; $i++) {
        $tok = $tokens[$i];

        // We only care about T_STRING "__" immediately followed by "(".
        if (!is_array($tok) || $tok[0] !== T_STRING || $tok[1] !== '__') {
            continue;
        }
        // Next significant token must be "(".
        $j = next_significant($tokens, $i + 1);
        if ($j === null || $tokens[$j] !== '(') {
            continue;
        }

        $callIndex++;

        // First argument: must be a single string literal (skip __(VAR) etc.).
        $k = next_significant($tokens, $j + 1);
        if ($k === null || !is_array($tokens[$k]) || $tokens[$k][0] !== T_CONSTANT_ENCAPSED_STRING) {
            continue;
        }
        $msg = decode_php_string($tokens[$k][1]);

        // In-range filter (when scoping to a function body).
        if ($tokenRange !== null && ($k < $tokenRange[0] || $k > $tokenRange[1])) {
            continue;
        }

        $hits[] = [
            'n'     => $callIndex,
            'text'  => $msg,
            'field' => infer_field($tokens, $i),
        ];
    }

    return $hits;
}

/**
 * Extract 'copy-key' => 'string' pairs from a token range (a function body).
 *
 * Handles both  'title' => 'Plain string'  and  'title' => __('Wrapped', 'standard').
 * This is for the data functions in machines-data.php, which are pure content
 * arrays — most FAQ/UNIQ/ROI entries are plain literals, the newer pillars are
 * __()-wrapped. We harvest both, in source order, and skip NON_COPY_KEYS and
 * values that look like paths/URLs. 'n' is the Nth copy pair in the range
 * (1-based), the stable anchor for re-apply.
 *
 * @param array<int, mixed> $tokens  full token stream for the file
 * @param array{0: int, 1: int} $range  [startIdx, endIdx] inclusive
 * @return array<int, array{n: int, text: string, field: string}>
 */
function extract_array_pairs(array $tokens, array $range): array
{
    [$start, $end] = $range;
    $hits = [];
    $n    = 0;

    for ($i = $start; $i <= $end; $i++) {
        // Look for a string-literal array key followed by "=>".
        if (!is_array($tokens[$i]) || $tokens[$i][0] !== T_CONSTANT_ENCAPSED_STRING) {
            continue;
        }
        $arrow = next_significant($tokens, $i + 1);
        if ($arrow === null || !is_array($tokens[$arrow]) || $tokens[$arrow][0] !== T_DOUBLE_ARROW) {
            continue;
        }

        $field = decode_php_string($tokens[$i][1]);
        if ($field === '' || in_array(strtolower($field), NON_COPY_KEYS, true)) {
            continue;
        }

        // Value: either a string literal, or __('...').
        $v = next_significant($tokens, $arrow + 1);
        if ($v === null) {
            continue;
        }

        $text = null;
        if (is_array($tokens[$v]) && $tokens[$v][0] === T_CONSTANT_ENCAPSED_STRING) {
            $text = decode_php_string($tokens[$v][1]);
        } elseif (is_array($tokens[$v]) && $tokens[$v][0] === T_STRING && $tokens[$v][1] === '__') {
            $paren = next_significant($tokens, $v + 1);
            $arg   = $paren !== null ? next_significant($tokens, $paren + 1) : null;
            if ($arg !== null && is_array($tokens[$arg]) && $tokens[$arg][0] === T_CONSTANT_ENCAPSED_STRING) {
                $text = decode_php_string($tokens[$arg][1]);
            }
        }

        if ($text === null || $text === '') {
            continue;
        }
        // Backstop: skip path/URL/anchor-shaped values even under a copy key.
        if (preg_match('~^(/|https?://|#)~', $text)) {
            continue;
        }

        $n++;
        $hits[] = ['n' => $n, 'text' => $text, 'field' => $field];
    }

    return $hits;
}

/**
 * Look backward from a __() token to infer the field role: the array key the
 * call is assigned to. Matches  'title' => __(  /  "body" => __(  patterns.
 * Falls back to 'text' for loose calls (e.g. inline in get_template_part args
 * with a named key handled the same way, or bare echo __()).
 */
function infer_field(array $tokens, int $callTokenIdx): string
{
    // Pattern we want:  'field' => __( ... )
    // Step back from the "__" token: the token before it should be "=>"
    // (T_DOUBLE_ARROW), and the token before THAT should be the string key.
    $arrow = prev_significant($tokens, $callTokenIdx - 1);
    if ($arrow === null || !is_array($tokens[$arrow]) || $tokens[$arrow][0] !== T_DOUBLE_ARROW) {
        return 'text';
    }

    $keyTok = prev_significant($tokens, $arrow - 1);
    if ($keyTok !== null && is_array($tokens[$keyTok]) && $tokens[$keyTok][0] === T_CONSTANT_ENCAPSED_STRING) {
        $field = decode_php_string($tokens[$keyTok][1]);
        return $field !== '' ? $field : 'text';
    }

    return 'text';
}

/** Index of the next non-whitespace/non-comment token, or null. */
function next_significant(array $tokens, int $from): ?int
{
    $n = count($tokens);
    for ($i = $from; $i < $n; $i++) {
        if (is_array($tokens[$i]) && in_array($tokens[$i][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
            continue;
        }
        return $i;
    }
    return null;
}

/** Index of the previous non-whitespace/non-comment token, or null. */
function prev_significant(array $tokens, int $from): ?int
{
    for ($i = $from; $i >= 0; $i--) {
        if (is_array($tokens[$i]) && in_array($tokens[$i][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
            continue;
        }
        return $i;
    }
    return null;
}

/**
 * Map each top-level function name in a file to its [startTokenIdx, endTokenIdx]
 * (inclusive), so we can scope extraction to one function body. Tracks brace
 * depth from the function's opening "{" to its matching "}".
 *
 * @return array<string, array{0: int, 1: int}>
 */
function function_token_ranges(string $absFile): array
{
    $src    = file_get_contents($absFile);
    if ($src === false) {
        return [];
    }
    $tokens = token_get_all($src);
    $n      = count($tokens);
    $ranges = [];

    for ($i = 0; $i < $n; $i++) {
        if (!is_array($tokens[$i]) || $tokens[$i][0] !== T_FUNCTION) {
            continue;
        }
        $nameIdx = next_significant($tokens, $i + 1);
        if ($nameIdx === null || !is_array($tokens[$nameIdx]) || $tokens[$nameIdx][0] !== T_STRING) {
            continue; // closure / anonymous
        }
        $fnName = $tokens[$nameIdx][1];

        // Find the opening brace of the body.
        $braceOpen = null;
        for ($j = $nameIdx + 1; $j < $n; $j++) {
            if ($tokens[$j] === '{') {
                $braceOpen = $j;
                break;
            }
            if ($tokens[$j] === ';') {
                break; // forward declaration / interface, no body
            }
        }
        if ($braceOpen === null) {
            continue;
        }

        // Walk to the matching close brace.
        $depth = 0;
        $braceClose = null;
        for ($j = $braceOpen; $j < $n; $j++) {
            if ($tokens[$j] === '{') {
                $depth++;
            } elseif ($tokens[$j] === '}') {
                $depth--;
                if ($depth === 0) {
                    $braceClose = $j;
                    break;
                }
            }
        }
        if ($braceClose !== null) {
            $ranges[$fnName] = [$braceOpen, $braceClose];
        }
    }

    return $ranges;
}

/**
 * Turn a PHP source string literal token (with its surrounding quotes and
 * escapes) into the actual string value. Handles both ' and " literals.
 */
function decode_php_string(string $raw): string
{
    if (strlen($raw) < 2) {
        return $raw;
    }
    $quote = $raw[0];
    $inner = substr($raw, 1, -1);

    if ($quote === "'") {
        // Single-quoted: only \\ and \' are escapes.
        return str_replace(['\\\\', "\\'"], ['\\', "'"], $inner);
    }

    // Double-quoted: decode common escapes. No variable interpolation expected
    // in these copy strings; if one ever appears, it round-trips literally.
    return stripcslashes($inner);
}

/**
 * Write rows to a CSV with a header. Uses fputcsv so Excel/Sheets get correct
 * quoting on commas, quotes, and newlines. Prepends a UTF-8 BOM so Excel on
 * Windows reads accented characters correctly.
 *
 * @param array<int, string> $header
 * @param array<int, array<string, string>> $rows
 */
function write_csv(string $path, array $header, array $rows): void
{
    $fh = fopen($path, 'wb');
    if ($fh === false) {
        fwrite(STDERR, "Could not write {$path}\n");
        exit(1);
    }
    fwrite($fh, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
    fputcsv($fh, $header);
    foreach ($rows as $row) {
        fputcsv($fh, [
            $row['page'],
            $row['section'],
            $row['field'],
            $row['key'],
            $row['current_content'],
            $row['new_content'],
        ]);
    }
    fclose($fh);
}
