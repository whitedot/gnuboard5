<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_create_member_export_xlsx($params, $file_name, $index = 0, $member_table = '')
{
    $config = admin_get_member_export_sheet_config();
    $fields = array_unique($config['fields']);

    try {
        $rows = array_merge(
            array($config['title'], $config['headers']),
            admin_fetch_member_export_sheet_rows($params, $fields, $member_table)
        );

        admin_ensure_member_export_directory(ADMIN_MEMBER_EXPORT_DIR);

        $sub_name = $index == 0 ? 'all' : sprintf('%02d', $index);
        $filename = $file_name . '_' . $sub_name . '.xlsx';
        $file_path = ADMIN_MEMBER_EXPORT_DIR . '/' . $filename;

        admin_xlsx_write_file($file_path, $rows, $config['widths'], '회원관리파일', 2);
    } catch (Exception $e) {
        throw new Exception('XLSX 파일 생성에 실패하였습니다: ' . $e->getMessage());
    }

    return $filename;
}

function admin_create_member_export_zip($files, $zip_file_name)
{
    if (!admin_archive_supports_zip()) {
        error_log('[Admin Member Export Error] ZIP archive 지원을 사용할 수 없습니다.');
        return array('error' => '파일을 압축하는 중 문제가 발생했습니다. 개별 파일로 제공됩니다.<br>: ZipArchive 또는 PharData 지원을 사용할 수 없습니다.');
    }

    admin_ensure_member_export_directory(ADMIN_MEMBER_EXPORT_DIR);
    $destination_zip_path = rtrim(ADMIN_MEMBER_EXPORT_DIR, '/') . '/' . $zip_file_name . '.zip';
    $archive_files = array();
    foreach ($files as $file) {
        $file_path = ADMIN_MEMBER_EXPORT_DIR . '/' . $file;
        if (file_exists($file_path)) {
            $archive_files[$file_path] = basename($file_path);
        }
    }

    try {
        admin_archive_write_from_files($destination_zip_path, $archive_files);
        $result = true;
    } catch (Exception $e) {
        return array('error' => '파일을 압축하는 중 문제가 발생했습니다. 개별 파일로 제공됩니다.<br>: ' . $e->getMessage());
    }

    return array(
        'result' => $result,
        'zipFile' => $zip_file_name . '.zip',
        'zipPath' => $destination_zip_path,
        'error' => '',
    );
}
