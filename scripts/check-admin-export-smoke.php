<?php

declare(strict_types=1);

define('_GNUBOARD_', true);

require_once __DIR__ . '/../lib/domain/admin/xlsx.lib.php';

function fail($message)
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

if (!class_exists('ZipArchive')) {
    fail('ZipArchive 확장을 사용할 수 없어 admin export smoke check를 실행할 수 없습니다.');
}

$temp_dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'g5-admin-export-smoke';
if (!is_dir($temp_dir) && !mkdir($temp_dir, 0777, true) && !is_dir($temp_dir)) {
    fail('smoke check용 임시 디렉터리를 만들지 못했습니다.');
}

$file_path = $temp_dir . DIRECTORY_SEPARATOR . 'admin-export-smoke.xlsx';
if (file_exists($file_path) && !unlink($file_path)) {
    fail('기존 smoke check 산출물을 정리하지 못했습니다.');
}

$rows = array(
    array('회원관리파일(일반)', '', ''),
    array('아이디', '이름', '메모'),
    array('user01', '홍길동', '한글/ASCII <tag> & "quote"'),
    array('user02', 'Jane Doe', 'line 1' . "\n" . 'line 2'),
);
$widths = array(18, 16, 42);
$sheet_name = '회원관리파일:[테스트]?*';

try {
    admin_xlsx_write_file($file_path, $rows, $widths, $sheet_name, 2);
} catch (Exception $e) {
    fail('XLSX smoke 파일 생성 실패: ' . $e->getMessage());
}

if (!file_exists($file_path) || filesize($file_path) <= 0) {
    fail('XLSX smoke 파일이 정상적으로 생성되지 않았습니다.');
}

$zip = new ZipArchive();
if ($zip->open($file_path) !== true) {
    @unlink($file_path);
    fail('생성된 XLSX smoke 파일을 열 수 없습니다.');
}

$required_entries = array(
    '[Content_Types].xml',
    '_rels/.rels',
    'docProps/app.xml',
    'docProps/core.xml',
    'xl/workbook.xml',
    'xl/_rels/workbook.xml.rels',
    'xl/styles.xml',
    'xl/worksheets/sheet1.xml',
);

foreach ($required_entries as $entry) {
    if ($zip->locateName($entry) === false) {
        $zip->close();
        @unlink($file_path);
        fail('XLSX smoke 파일 내부 엔트리가 누락되었습니다: ' . $entry);
    }
}

$workbook_xml = (string) $zip->getFromName('xl/workbook.xml');
$sheet_xml = (string) $zip->getFromName('xl/worksheets/sheet1.xml');
$styles_xml = (string) $zip->getFromName('xl/styles.xml');
$zip->close();

@unlink($file_path);

$expected_sheet_name = admin_xlsx_normalize_sheet_name($sheet_name);
if (strpos($workbook_xml, 'name="' . admin_xlsx_escape_text($expected_sheet_name) . '"') === false) {
    fail('sheet name 정규화 결과가 workbook.xml에 반영되지 않았습니다.');
}

$expected_dimension = '<dimension ref="A1:C4"/>';
if (strpos($sheet_xml, $expected_dimension) === false) {
    fail('sheet dimension이 예상과 다릅니다.');
}

$expected_strings = array(
    '회원관리파일(일반)',
    '홍길동',
    '한글/ASCII &lt;tag&gt; &amp; &quot;quote&quot;',
    "line 1\nline 2",
);

foreach ($expected_strings as $expected) {
    if (strpos($sheet_xml, $expected) === false) {
        fail('sheet xml에서 예상 문자열을 찾지 못했습니다: ' . $expected);
    }
}

if (strpos($styles_xml, '<cellXfs count="3">') === false) {
    fail('styles.xml의 기본 스타일 구성이 예상과 다릅니다.');
}

fwrite(STDOUT, "Admin export smoke check passed.\n");
