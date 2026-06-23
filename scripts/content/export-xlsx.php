<?php
/**
 * export-xlsx.php — build a multi-tab Excel workbook of pillar/landing copy.
 *
 * Same data as export-copy.php (it shares extract.php), but written as a single
 * .xlsx with one worksheet per page plus an "All" tab. Nicer for the content
 * team than juggling 11 CSVs: a frozen header row, the reference columns (key,
 * current_content) locked read-only, and a roomy editable new_content column.
 *
 *     php scripts/content/export-xlsx.php          (or: npm run content:xlsx)
 *
 * Output (gitignored): scripts/content/exports/copy-review.xlsx
 *
 * No Composer / PhpSpreadsheet dependency: .xlsx is an Open-Packaging zip of
 * XML parts, and we write those parts directly with the bundled zip extension.
 * That keeps the toolchain as light as the CSV path (no new project deps).
 *
 * Columns: page, section, field, key, current_content, new_content
 *   - key + current_content are locked (sheet protection, no password). Only
 *     new_content is editable. The team fills new_content; everything else is
 *     the match anchor the apply step relies on.
 *
 * @package Standard
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "export-xlsx.php must be run from the command line.\n");
    exit(1);
}
if (!extension_loaded('zip')) {
    fwrite(STDERR, "The PHP 'zip' extension is required to write .xlsx files.\n");
    exit(1);
}

require __DIR__ . '/extract.php';

$outDir = __DIR__ . '/exports';
if (!is_dir($outDir) && !mkdir($outDir, 0775, true) && !is_dir($outDir)) {
    fwrite(STDERR, "Could not create output dir: {$outDir}\n");
    exit(1);
}

$data = collect_rows();

// Build the list of sheets: one per page, then a combined "All".
$sheets = [];
foreach ($data['perPage'] as $page) {
    $sheets[] = ['name' => sheet_name($page['label']), 'rows' => $page['rows']];
}
$sheets[] = ['name' => 'All', 'rows' => $data['all']];

$path = $outDir . '/copy-review.xlsx';
write_xlsx($path, $sheets);

$total = count($data['all']);
foreach ($data['perPage'] as $page) {
    printf("  %-26s %4d strings\n", $page['label'], count($page['rows']));
}
printf("\nDone. %d strings, %d tabs (%d pages + All).\n", $total, count($sheets), count($data['perPage']));
printf("Workbook: %s\n", str_replace($data['themeRoot'] . '/', '', $path));

/* ----------------------------------------------------------------------- */

/**
 * Excel sheet names: max 31 chars, and none of  : \ / ? * [ ]
 */
function sheet_name(string $label): string
{
    $name = preg_replace('/[:\\\\\/?*\[\]]/', ' ', $label);
    $name = trim((string) preg_replace('/\s+/', ' ', (string) $name));
    return mb_substr($name === '' ? 'Sheet' : $name, 0, 31);
}

/**
 * Write the whole workbook to $path as a valid .xlsx (OPC zip of XML parts).
 *
 * @param array<int, array{name: string, rows: array<int, array<string, string>>}> $sheets
 */
function write_xlsx(string $path, array $sheets): void
{
    // Ensure unique, non-empty sheet names (Excel rejects dupes).
    $used = [];
    foreach ($sheets as $i => $s) {
        $base = $s['name'];
        $name = $base;
        $n    = 2;
        while (isset($used[mb_strtolower($name)])) {
            $suffix = ' (' . $n++ . ')';
            $name   = mb_substr($base, 0, 31 - mb_strlen($suffix)) . $suffix;
        }
        $used[mb_strtolower($name)] = true;
        $sheets[$i]['name'] = $name;
    }

    $zip = new ZipArchive();
    if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        fwrite(STDERR, "Could not create {$path}\n");
        exit(1);
    }

    $zip->addFromString('[Content_Types].xml', content_types_xml(count($sheets)));
    $zip->addFromString('_rels/.rels', root_rels_xml());
    $zip->addFromString('xl/workbook.xml', workbook_xml($sheets));
    $zip->addFromString('xl/_rels/workbook.xml.rels', workbook_rels_xml(count($sheets)));
    $zip->addFromString('xl/styles.xml', styles_xml());

    foreach ($sheets as $i => $s) {
        $zip->addFromString('xl/worksheets/sheet' . ($i + 1) . '.xml', sheet_xml($s['rows']));
    }

    $zip->close();
}

/** [Content_Types].xml — declares the MIME type of every part. */
function content_types_xml(int $sheetCount): string
{
    $overrides = '';
    for ($i = 1; $i <= $sheetCount; $i++) {
        $overrides .= '<Override PartName="/xl/worksheets/sheet' . $i . '.xml" '
            . 'ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
    }
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
        . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
        . '<Default Extension="xml" ContentType="application/xml"/>'
        . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
        . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
        . $overrides
        . '</Types>';
}

/** _rels/.rels — points the package at the workbook. */
function root_rels_xml(): string
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
        . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
        . '</Relationships>';
}

/**
 * xl/workbook.xml — names the sheets and assigns each a relationship id.
 *
 * @param array<int, array{name: string, rows: array}> $sheets
 */
function workbook_xml(array $sheets): string
{
    $tabs = '';
    foreach ($sheets as $i => $s) {
        $tabs .= '<sheet name="' . xml_attr($s['name']) . '" sheetId="' . ($i + 1) . '" r:id="rId' . ($i + 1) . '"/>';
    }
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
        . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
        . '<sheets>' . $tabs . '</sheets>'
        . '</workbook>';
}

/** xl/_rels/workbook.xml.rels — maps rIdN to each worksheet part + styles. */
function workbook_rels_xml(int $sheetCount): string
{
    $rels = '';
    for ($i = 1; $i <= $sheetCount; $i++) {
        $rels .= '<Relationship Id="rId' . $i . '" '
            . 'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" '
            . 'Target="worksheets/sheet' . $i . '.xml"/>';
    }
    // styles gets the next free rId.
    $styleId = $sheetCount + 1;
    $rels .= '<Relationship Id="rId' . $styleId . '" '
        . 'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" '
        . 'Target="styles.xml"/>';

    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
        . $rels
        . '</Relationships>';
}

/**
 * xl/styles.xml — four cellXfs:
 *   s=0 default (locked, wrap)             — key column
 *   s=1 header (bold, locked, wrap)
 *   s=2 locked + wrap + top-align          — current_content / labels
 *   s=3 UNLOCKED + wrap + top-align        — new_content (the editable column)
 */
function styles_xml(): string
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
        . '<fonts count="2">'
        .   '<font><sz val="11"/><name val="Calibri"/></font>'
        .   '<font><b/><sz val="11"/><name val="Calibri"/></font>'
        . '</fonts>'
        . '<fills count="3">'
        .   '<fill><patternFill patternType="none"/></fill>'
        .   '<fill><patternFill patternType="gray125"/></fill>'
        .   '<fill><patternFill patternType="solid"><fgColor rgb="FFEFEFEF"/><bgColor indexed="64"/></patternFill></fill>'
        . '</fills>'
        . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
        . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
        . '<cellXfs count="4">'
        .   '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" vertical="top"/></xf>'
        .   '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment wrapText="1" vertical="top"/></xf>'
        .   '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment wrapText="1" vertical="top"/><protection locked="1"/></xf>'
        .   '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1" applyProtection="1"><alignment wrapText="1" vertical="top"/><protection locked="0"/></xf>'
        . '</cellXfs>'
        . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
        . '</styleSheet>';
}

/**
 * One worksheet: frozen header row, column widths, sheet protection (locked
 * reference columns, editable new_content), inline-string cells.
 *
 * Columns A..F = page, section, field, key, current_content, new_content.
 * Style ids per column: A/B/C -> 2 (locked), D key -> 2, E current -> 2,
 * F new_content -> 3 (unlocked). Header row uses style 1.
 *
 * @param array<int, array<string, string>> $rows
 */
function sheet_xml(array $rows): string
{
    $colStyle = [
        'A' => 2, 'B' => 2, 'C' => 2, // page, section, field — locked
        'D' => 2,                     // key — locked
        'E' => 2,                     // current_content — locked
        'F' => 3,                     // new_content — UNLOCKED
    ];
    $headers = ['page', 'section', 'field', 'key', 'current_content', 'new_content'];

    // Header row (row 1), all style 1.
    $body = '<row r="1">';
    foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $ci => $col) {
        $body .= inline_cell($col . '1', $headers[$ci], 1);
    }
    $body .= '</row>';

    // Data rows start at row 2.
    $r = 2;
    foreach ($rows as $row) {
        $vals = [$row['page'], $row['section'], $row['field'], $row['key'], $row['current_content'], $row['new_content']];
        $body .= '<row r="' . $r . '">';
        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $ci => $col) {
            $body .= inline_cell($col . $r, $vals[$ci], $colStyle[$col]);
        }
        $body .= '</row>';
        $r++;
    }

    $lastRow = max(1, count($rows) + 1);

    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
        . '<dimension ref="A1:F' . $lastRow . '"/>'
        . '<sheetViews><sheetView workbookViewId="0">'
        .   '<pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
        .   '<selection pane="bottomLeft" activeCell="F2" sqref="F2"/>'
        . '</sheetView></sheetViews>'
        . '<sheetFormatPr defaultRowHeight="15"/>'
        . '<cols>'
        .   '<col min="1" max="1" width="14" customWidth="1"/>'   // page
        .   '<col min="2" max="2" width="20" customWidth="1"/>'   // section
        .   '<col min="3" max="3" width="14" customWidth="1"/>'   // field
        .   '<col min="4" max="4" width="40" customWidth="1"/>'   // key
        .   '<col min="5" max="5" width="60" customWidth="1"/>'   // current_content
        .   '<col min="6" max="6" width="60" customWidth="1"/>'   // new_content
        . '</cols>'
        . '<sheetData>' . $body . '</sheetData>'
        // Protect locked cells but let users select & edit unlocked (new_content).
        . '<sheetProtection sheet="1" objects="1" scenarios="1" '
        .   'selectLockedCells="1" selectUnlockedCells="1" '
        .   'formatColumns="0" formatRows="0" insertRows="0" deleteRows="0" sort="0" autoFilter="0"/>'
        . '<autoFilter ref="A1:F' . $lastRow . '"/>'
        . '</worksheet>';
}

/**
 * An inline-string cell (t="inlineStr"), so we don't need a sharedStrings part.
 * Numbers are still written as text — fine here, every value is copy.
 */
function inline_cell(string $ref, string $value, int $styleId): string
{
    return '<c r="' . $ref . '" s="' . $styleId . '" t="inlineStr"><is><t xml:space="preserve">'
        . xml_text($value) . '</t></is></c>';
}

/** Escape text node content; strip control chars Excel rejects. */
function xml_text(string $s): string
{
    $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $s) ?? $s;
    return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

/** Escape an attribute value. */
function xml_attr(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
}
