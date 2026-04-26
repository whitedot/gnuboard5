<?php
if (!defined('_GNUBOARD_')) {
    exit;
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
