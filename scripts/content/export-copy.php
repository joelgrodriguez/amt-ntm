<?php
/**
 * export-copy.php — extract pillar/landing page copy from PHP into review CSVs.
 *
 * The marketing copy on NTM's pillar/landing pages is hardcoded in PHP (the
 * pages have empty post_content; nothing is editable in WP admin). This script
 * pulls every reviewable string out of the files in manifest.php and writes one
 * CSV row per string, so the content team can review and revise the copy in
 * Excel / Google Sheets, then hand it back for a human-reviewed re-apply.
 *
 * The heavy lifting (tokenizing, extraction) lives in extract.php and is shared
 * with export-xlsx.php, so the CSV and XLSX outputs can never drift. This file
 * is just the CSV writer.
 *
 *     php scripts/content/export-copy.php          (or: npm run content:export)
 *
 * Output (gitignored): scripts/content/exports/
 *     copy-review-<page>.csv   one file per page, focused
 *     copy-review-all.csv      every row, combined
 *
 * Columns: page, section, field, key, current_content, new_content
 *   - key is the match anchor for re-apply. The team must NOT edit key or
 *     current_content; those are how the apply step finds the string to change.
 *
 * @package Standard
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "export-copy.php must be run from the command line.\n");
    exit(1);
}

require __DIR__ . '/extract.php';

$outDir = __DIR__ . '/exports';
if (!is_dir($outDir) && !mkdir($outDir, 0775, true) && !is_dir($outDir)) {
    fwrite(STDERR, "Could not create output dir: {$outDir}\n");
    exit(1);
}

$data = collect_rows();

foreach ($data['perPage'] as $pageSlug => $page) {
    $path = $outDir . '/copy-review-' . $pageSlug . '.csv';
    write_csv($path, CONTENT_REVIEW_HEADER, $page['rows']);
    printf("  %-26s %4d strings -> %s\n", $page['label'], count($page['rows']), basename($path));
}

$allPath = $outDir . '/copy-review-all.csv';
write_csv($allPath, CONTENT_REVIEW_HEADER, $data['all']);

printf("\nDone. %d total strings across %d pages.\n", count($data['all']), count($data['perPage']));
printf("Combined: %s\n", str_replace($data['themeRoot'] . '/', '', $allPath));

/**
 * Write rows to a CSV. fputcsv handles quoting on commas/quotes/newlines; a
 * UTF-8 BOM makes Excel on Windows read accented characters correctly.
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
    fwrite($fh, "\xEF\xBB\xBF");
    fputcsv($fh, $header);
    foreach ($rows as $row) {
        fputcsv($fh, [
            $row['page'], $row['section'], $row['field'],
            $row['key'], $row['current_content'], $row['new_content'],
        ]);
    }
    fclose($fh);
}
