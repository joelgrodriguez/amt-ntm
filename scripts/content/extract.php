<?php
/**
 * extract.php — shared copy-extraction engine for the content-review exports.
 *
 * Both export-copy.php (CSV) and export-xlsx.php (Excel workbook) require this
 * file and call collect_rows(); the only difference between them is the writer.
 * Keeping the tokenizer here means the CSV and XLSX outputs can never drift.
 *
 * Why token_get_all() and not regex: PHP's tokenizer reads the source the way
 * PHP itself does, so apostrophes ("don't"), "$2M+", commas, and both quote
 * styles inside a __() call are captured correctly. Regex would mangle them.
 *
 * Read-only: parses theme files, never bootstraps WordPress, never touches the DB.
 *
 * @package Standard
 */

declare(strict_types=1);

/** The CSV/XLSX column order, single source of truth for both writers. */
const CONTENT_REVIEW_HEADER = ['page', 'section', 'field', 'key', 'current_content', 'new_content'];

/**
 * Keys whose values are never user-facing copy (URLs, icon names, enums, slugs,
 * image refs). Filters array-pair extraction in data functions so the output
 * holds prose, not plumbing.
 */
const NON_COPY_KEYS = [
    'url', 'kind', 'icon', 'slug', 'id', 'image', 'img', 'video', 'href',
    'tag', 'tags', 'tag_slugs', 'class', 'svg', 'name', 'type', 'key',
    'product_tag', 'value',
];

/**
 * Walk the manifest and extract all copy rows.
 *
 * @return array{themeRoot: string, perPage: array<string, array{label: string, rows: array<int, array<string, string>>}>, all: array<int, array<string, string>>}
 */
function collect_rows(): array
{
    $themeRoot = dirname(__DIR__, 2);
    $manifest  = require __DIR__ . '/manifest.php';
    $dataFile  = $themeRoot . '/app/inc/machines-data.php';

    $perPage  = [];
    $all      = [];
    $seenKeys = []; // keep keys globally unique across the combined output

    foreach ($manifest as $pageSlug => $page) {
        $rows = [];

        // 1. Files (templates + parts), expanding globs in manifest order.
        foreach ($page['paths'] as $relPath) {
            $matches = glob($themeRoot . '/' . $relPath, GLOB_BRACE) ?: [];
            sort($matches);
            foreach ($matches as $absFile) {
                $rel = ltrim(str_replace($themeRoot, '', $absFile), '/');
                foreach (extract_strings_from_file($absFile) as $hit) {
                    $rows[] = build_row($pageSlug, $rel, $hit, $seenKeys);
                }
            }
        }

        // 2. Named data functions in machines-data.php — pure content arrays.
        //    Some entries are __()-wrapped (pillars), most are plain
        //    'key' => 'literal' pairs (FAQs, UNIQ, ROI). Harvest both, skipping
        //    non-copy keys (url, icon, kind).
        if (!empty($page['functions']) && is_file($dataFile)) {
            $fnRanges   = function_token_ranges($dataFile);
            $dataTokens = token_get_all((string) file_get_contents($dataFile));
            foreach ($page['functions'] as $fnName) {
                if (!isset($fnRanges[$fnName])) {
                    fwrite(STDERR, "  warning: function {$fnName}() not found in machines-data.php (page: {$pageSlug})\n");
                    continue;
                }
                foreach (extract_array_pairs($dataTokens, $fnRanges[$fnName]) as $hit) {
                    $rows[] = build_data_row($pageSlug, 'app/inc/machines-data.php', $fnName, $hit, $seenKeys);
                }
            }
        }

        $perPage[$pageSlug] = ['label' => $page['label'], 'rows' => $rows];
        foreach ($rows as $r) {
            $all[] = $r;
        }
    }

    return ['themeRoot' => $themeRoot, 'perPage' => $perPage, 'all' => $all];
}

/**
 * Build a row from a template __() hit.
 *
 * @param array{n: int, text: string, field: string} $hit
 * @param array<string, int> $seenKeys passed by reference for de-dup safety
 */
function build_row(string $pageSlug, string $rel, array $hit, array &$seenKeys): array
{
    return finish_row($pageSlug, $rel, null, $hit, $rel . '::__#' . $hit['n'], $seenKeys);
}

/**
 * Build a row from a data-function array-pair hit.
 *
 * @param array{n: int, text: string, field: string} $hit
 * @param array<string, int> $seenKeys passed by reference for de-dup safety
 */
function build_data_row(string $pageSlug, string $rel, string $fnName, array $hit, array &$seenKeys): array
{
    return finish_row($pageSlug, $rel, $fnName, $hit, $rel . '::' . $fnName . '()::pair#' . $hit['n'], $seenKeys);
}

/**
 * Assemble the final associative row and ensure the key is globally unique.
 *
 * @param array{n: int, text: string, field: string} $hit
 * @param array<string, int> $seenKeys passed by reference
 */
function finish_row(string $pageSlug, string $rel, ?string $fnName, array $hit, string $key, array &$seenKeys): array
{
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
 */
function section_label(string $rel, ?string $fnSuffix): string
{
    if ($fnSuffix !== null) {
        $name = preg_replace('/^get_/', '', $fnSuffix);
        return ucwords(str_replace(['_'], ' ', (string) $name));
    }
    $base = preg_replace('/^page-/', '', basename($rel, '.php'));
    return ucwords(str_replace(['-', '_'], ' ', (string) $base));
}

/**
 * Extract every __('literal', 'standard') string from a file (optionally limited
 * to a token-index range). 'n' is the Nth __() call in the WHOLE file (1-based),
 * the stable anchor for re-apply.
 *
 * @param array{0: int, 1: int}|null $tokenRange [startIdx, endIdx] inclusive
 * @return array<int, array{n: int, text: string, field: string}>
 */
function extract_strings_from_file(string $absFile, ?array $tokenRange = null): array
{
    $src = file_get_contents($absFile);
    if ($src === false) {
        return [];
    }
    $tokens = token_get_all($src);
    $count  = count($tokens);

    $hits      = [];
    $callIndex = 0;

    for ($i = 0; $i < $count; $i++) {
        $tok = $tokens[$i];
        if (!is_array($tok) || $tok[0] !== T_STRING || $tok[1] !== '__') {
            continue;
        }
        $j = next_significant($tokens, $i + 1);
        if ($j === null || $tokens[$j] !== '(') {
            continue;
        }
        $callIndex++;

        $k = next_significant($tokens, $j + 1);
        if ($k === null || !is_array($tokens[$k]) || $tokens[$k][0] !== T_CONSTANT_ENCAPSED_STRING) {
            continue;
        }
        if ($tokenRange !== null && ($k < $tokenRange[0] || $k > $tokenRange[1])) {
            continue;
        }

        $hits[] = [
            'n'     => $callIndex,
            'text'  => decode_php_string($tokens[$k][1]),
            'field' => infer_field($tokens, $i),
        ];
    }

    return $hits;
}

/**
 * Extract 'copy-key' => 'string' (or => __('...')) pairs from a token range
 * (a function body). 'n' is the Nth copy pair in the range (1-based).
 *
 * @param array<int, mixed> $tokens full token stream for the file
 * @param array{0: int, 1: int} $range [startIdx, endIdx] inclusive
 * @return array<int, array{n: int, text: string, field: string}>
 */
function extract_array_pairs(array $tokens, array $range): array
{
    [$start, $end] = $range;
    $hits = [];
    $n    = 0;

    for ($i = $start; $i <= $end; $i++) {
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
        if (preg_match('~^(/|https?://|#)~', $text)) {
            continue;
        }

        $n++;
        $hits[] = ['n' => $n, 'text' => $text, 'field' => $field];
    }

    return $hits;
}

/**
 * Infer the field role by looking back from a __() token for  'field' => __( .
 * Falls back to 'text' for loose calls.
 */
function infer_field(array $tokens, int $callTokenIdx): string
{
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
 * (inclusive). Tracks brace depth from the opening "{" to its matching "}".
 *
 * @return array<string, array{0: int, 1: int}>
 */
function function_token_ranges(string $absFile): array
{
    $src = file_get_contents($absFile);
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
            continue;
        }
        $fnName = $tokens[$nameIdx][1];

        $braceOpen = null;
        for ($j = $nameIdx + 1; $j < $n; $j++) {
            if ($tokens[$j] === '{') {
                $braceOpen = $j;
                break;
            }
            if ($tokens[$j] === ';') {
                break;
            }
        }
        if ($braceOpen === null) {
            continue;
        }

        $depth      = 0;
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
 * Turn a PHP source string literal token into its actual value. Handles ' and ".
 */
function decode_php_string(string $raw): string
{
    if (strlen($raw) < 2) {
        return $raw;
    }
    $quote = $raw[0];
    $inner = substr($raw, 1, -1);
    if ($quote === "'") {
        return str_replace(['\\\\', "\\'"], ['\\', "'"], $inner);
    }
    return stripcslashes($inner);
}
