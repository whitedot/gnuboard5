<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_xlsx_column_name($index)
{
    $index = (int) $index;
    $name = '';

    while ($index >= 0) {
        $name = chr(65 + ($index % 26)) . $name;
        $index = intdiv($index, 26) - 1;
    }

    return $name;
}

function admin_xlsx_escape_text($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function admin_xlsx_normalize_sheet_name($sheet_name)
{
    $sheet_name = preg_replace('/[\[\]\*\/\\\\:\?]/', '_', trim((string) $sheet_name));
    if ($sheet_name === '') {
        return 'Sheet1';
    }

    if (function_exists('mb_substr')) {
        return mb_substr($sheet_name, 0, 31, 'UTF-8');
    }

    return substr($sheet_name, 0, 31);
}

function admin_xlsx_build_cols_xml(array $widths)
{
    if (empty($widths)) {
        return '';
    }

    $parts = array('<cols>');
    foreach ($widths as $index => $width) {
        $width = (float) $width;
        if ($width <= 0) {
            continue;
        }

        $column_number = $index + 1;
        $parts[] = '<col min="' . $column_number . '" max="' . $column_number . '" width="' . sprintf('%.2F', $width) . '" customWidth="1"/>';
    }
    $parts[] = '</cols>';

    return implode('', $parts);
}

function admin_xlsx_build_cell_xml($cell_ref, $value, $style_id)
{
    return '<c r="' . $cell_ref . '" s="' . (int) $style_id . '" t="inlineStr"><is><t xml:space="preserve">' . admin_xlsx_escape_text($value) . '</t></is></c>';
}

function admin_xlsx_build_sheet_rows_xml(array $rows, $header_row_index)
{
    $parts = array();
    $header_row_index = (int) $header_row_index;

    foreach ($rows as $row_index => $cells) {
        $excel_row_index = $row_index + 1;
        $style_id = ($excel_row_index === $header_row_index) ? 1 : 2;

        $parts[] = '<row r="' . $excel_row_index . '">';
        foreach ($cells as $column_index => $value) {
            $cell_ref = admin_xlsx_column_name($column_index) . $excel_row_index;
            $parts[] = admin_xlsx_build_cell_xml($cell_ref, $value, $style_id);
        }
        $parts[] = '</row>';
    }

    return implode('', $parts);
}

function admin_xlsx_build_sheet_xml(array $rows, array $widths, $header_row_index)
{
    $row_count = max(1, count($rows));
    $column_count = 1;

    foreach ($rows as $cells) {
        $column_count = max($column_count, count($cells));
    }

    $last_cell_ref = admin_xlsx_column_name($column_count - 1) . $row_count;

    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
        . '<dimension ref="A1:' . $last_cell_ref . '"/>'
        . '<sheetViews><sheetView workbookViewId="0"/></sheetViews>'
        . '<sheetFormatPr defaultRowHeight="15"/>'
        . admin_xlsx_build_cols_xml($widths)
        . '<sheetData>'
        . admin_xlsx_build_sheet_rows_xml($rows, $header_row_index)
        . '</sheetData>'
        . '<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>'
        . '</worksheet>';
}

function admin_xlsx_styles_xml()
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
        . '<fonts count="1"><font><sz val="11"/><name val="Arial"/><family val="2"/></font></fonts>'
        . '<fills count="3">'
        . '<fill><patternFill patternType="none"/></fill>'
        . '<fill><patternFill patternType="gray125"/></fill>'
        . '<fill><patternFill patternType="solid"><fgColor rgb="FFD9E1F2"/><bgColor indexed="64"/></patternFill></fill>'
        . '</fills>'
        . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
        . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
        . '<cellXfs count="3">'
        . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
        . '<xf numFmtId="0" fontId="0" fillId="2" borderId="0" xfId="0" applyFill="1" applyAlignment="1"><alignment vertical="center" wrapText="1"/></xf>'
        . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="center" wrapText="1"/></xf>'
        . '</cellXfs>'
        . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
        . '</styleSheet>';
}

function admin_xlsx_content_types_xml()
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
        . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
        . '<Default Extension="xml" ContentType="application/xml"/>'
        . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
        . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
        . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
        . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
        . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
        . '</Types>';
}

function admin_xlsx_root_rels_xml()
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
        . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
        . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
        . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
        . '</Relationships>';
}

function admin_xlsx_workbook_xml($sheet_name)
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
        . '<bookViews><workbookView xWindow="0" yWindow="0" windowWidth="24000" windowHeight="12000"/></bookViews>'
        . '<sheets><sheet name="' . admin_xlsx_escape_text($sheet_name) . '" sheetId="1" r:id="rId1"/></sheets>'
        . '</workbook>';
}

function admin_xlsx_workbook_rels_xml()
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
        . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
        . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
        . '</Relationships>';
}

function admin_xlsx_app_props_xml()
{
    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
        . '<Application>G5 AIF</Application>'
        . '</Properties>';
}

function admin_xlsx_core_props_xml()
{
    $timestamp = gmdate('Y-m-d\TH:i:s\Z');

    return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
        . '<dc:creator>G5 AIF</dc:creator>'
        . '<cp:lastModifiedBy>G5 AIF</cp:lastModifiedBy>'
        . '<dcterms:created xsi:type="dcterms:W3CDTF">' . $timestamp . '</dcterms:created>'
        . '<dcterms:modified xsi:type="dcterms:W3CDTF">' . $timestamp . '</dcterms:modified>'
        . '</cp:coreProperties>';
}

function admin_archive_supports_zip()
{
    return true;
}

function admin_archive_cleanup_file($file_path)
{
    if ($file_path && file_exists($file_path)) {
        @unlink($file_path);
    }
}

function admin_archive_write_from_strings_with_ziparchive($file_path, array $files)
{
    $zip = new ZipArchive();
    if ($zip->open($file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception('압축 파일을 생성할 수 없습니다.');
    }

    try {
        foreach ($files as $archive_path => $contents) {
            if (!$zip->addFromString($archive_path, $contents)) {
                throw new Exception('압축 파일 내부 구성을 저장하지 못했습니다.');
            }
        }
    } catch (Exception $e) {
        $zip->close();
        admin_archive_cleanup_file($file_path);
        throw $e;
    }

    if (!$zip->close()) {
        admin_archive_cleanup_file($file_path);
        throw new Exception('압축 파일 저장을 완료하지 못했습니다.');
    }
}

function admin_archive_dos_timestamp_parts($timestamp = null)
{
    $datetime = getdate($timestamp ?: time());
    $year = max(1980, (int) $datetime['year']);

    $dos_time = (($datetime['hours'] & 0x1F) << 11)
        | (($datetime['minutes'] & 0x3F) << 5)
        | (int) floor($datetime['seconds'] / 2);
    $dos_date = ((($year - 1980) & 0x7F) << 9)
        | (($datetime['mon'] & 0x0F) << 5)
        | ($datetime['mday'] & 0x1F);

    return array($dos_time, $dos_date);
}

function admin_archive_build_binary(array $files)
{
    $archive_binary = '';
    $central_directory = '';
    $offset = 0;
    list($dos_time, $dos_date) = admin_archive_dos_timestamp_parts();

    foreach ($files as $archive_path => $contents) {
        $archive_path = str_replace('\\', '/', (string) $archive_path);
        $contents = (string) $contents;
        $crc = crc32($contents);
        $size = strlen($contents);

        $local_header = pack(
            'VvvvvvVVVvv',
            0x04034b50,
            20,
            0,
            0,
            $dos_time,
            $dos_date,
            $crc,
            $size,
            $size,
            strlen($archive_path),
            0
        );

        $central_header = pack(
            'VvvvvvvVVVvvvvvVV',
            0x02014b50,
            20,
            20,
            0,
            0,
            $dos_time,
            $dos_date,
            $crc,
            $size,
            $size,
            strlen($archive_path),
            0,
            0,
            0,
            0,
            0,
            $offset
        );

        $archive_binary .= $local_header . $archive_path . $contents;
        $central_directory .= $central_header . $archive_path;
        $offset += strlen($local_header) + strlen($archive_path) + $size;
    }

    $end_of_central_directory = pack(
        'VvvvvVVv',
        0x06054b50,
        0,
        0,
        count($files),
        count($files),
        strlen($central_directory),
        strlen($archive_binary),
        0
    );

    return $archive_binary . $central_directory . $end_of_central_directory;
}

function admin_archive_write_from_strings_without_ziparchive($file_path, array $files)
{
    $binary = admin_archive_build_binary($files);
    if (@file_put_contents($file_path, $binary) === false) {
        admin_archive_cleanup_file($file_path);
        throw new Exception('압축 파일을 생성할 수 없습니다.');
    }

    clearstatcache(true, $file_path);
    if (!file_exists($file_path) || filesize($file_path) <= 0) {
        admin_archive_cleanup_file($file_path);
        throw new Exception('압축 파일 저장을 완료하지 못했습니다.');
    }
}

function admin_archive_write_from_strings($file_path, array $files)
{
    if (class_exists('ZipArchive')) {
        admin_archive_write_from_strings_with_ziparchive($file_path, $files);
        return;
    }

    admin_archive_write_from_strings_without_ziparchive($file_path, $files);
}

function admin_archive_write_from_files($file_path, array $files)
{
    $archive_files = array();
    foreach ($files as $source_path => $archive_path) {
        if (!file_exists($source_path)) {
            continue;
        }

        $contents = @file_get_contents($source_path);
        if ($contents === false) {
            throw new Exception('압축할 파일을 읽지 못했습니다.');
        }

        $archive_files[$archive_path] = $contents;
    }

    admin_archive_write_from_strings($file_path, $archive_files);
}

function admin_xlsx_write_file($file_path, array $rows, array $widths = array(), $sheet_name = 'Sheet1', $header_row_index = 2)
{
    $sheet_name = admin_xlsx_normalize_sheet_name($sheet_name);
    $files = array(
        '[Content_Types].xml' => admin_xlsx_content_types_xml(),
        '_rels/.rels' => admin_xlsx_root_rels_xml(),
        'docProps/app.xml' => admin_xlsx_app_props_xml(),
        'docProps/core.xml' => admin_xlsx_core_props_xml(),
        'xl/workbook.xml' => admin_xlsx_workbook_xml($sheet_name),
        'xl/_rels/workbook.xml.rels' => admin_xlsx_workbook_rels_xml(),
        'xl/styles.xml' => admin_xlsx_styles_xml(),
        'xl/worksheets/sheet1.xml' => admin_xlsx_build_sheet_xml($rows, $widths, $header_row_index),
    );

    try {
        admin_archive_write_from_strings($file_path, $files);
    } catch (Exception $e) {
        admin_archive_cleanup_file($file_path);
        throw $e;
    }
}
