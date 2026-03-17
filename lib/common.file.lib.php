<?php
if (!defined('_GNUBOARD_')) exit;

// 파일의 용량을 구한다.
//function get_filesize($file)
function get_filesize($size)
{
    //$size = @filesize(addslashes($file));
    if ($size >= 1048576) {
        $size = number_format($size/1048576, 1) . "M";
    } else if ($size >= 1024) {
        $size = number_format($size/1024, 1) . "K";
    } else {
        $size = number_format($size, 0) . "byte";
    }
    return $size;
}

// 폴더의 용량 ($dir는 / 없이 넘기세요)
function get_dirsize($dir)
{
    $size = 0;
    $d = dir($dir);
    while ($entry = $d->read()) {
        if ($entry != '.' && $entry != '..') {
            $size += filesize($dir.'/'.$entry);
        }
    }
    $d->close();
    return $size;
}

// 파일명에서 특수문자 제거
function get_safe_filename($name)
{
    $pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
    $name = preg_replace($pattern, '', $name);

    return $name;
}

// 파일명 치환
function replace_filename($name)
{
    @session_start();
    $ss_id = session_id();
    $usec = get_microtime();
    $file_path = pathinfo($name);
    $ext = $file_path['extension'];
    $return_filename = sha1($ss_id.$_SERVER['REMOTE_ADDR'].$usec); 
    if( $ext )
        $return_filename .= '.'.$ext;

    return $return_filename;
}

// 에디터 이미지 얻기
function get_editor_image($contents, $view=true)
{
    if(!$contents)
        return false;

    // $contents 중 img 태그 추출
    if ($view)
        $pattern = "/<img([^>]*)>/iS";
    else
        $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
    preg_match_all($pattern, $contents, $matchs);

    return $matchs;
}

// 에디터 썸네일 삭제
function delete_editor_thumbnail($contents)
{
    if(!$contents)
        return;
    
    run_event('delete_editor_thumbnail_before', $contents);

    // $contents 중 img 태그 추출
    $matchs = get_editor_image($contents, false);

    if(!$matchs)
        return;

    for($i=0; $i<count($matchs[1]); $i++) {
        // 이미지 path 구함
        $imgurl = @parse_url($matchs[1][$i]);
        // $srcfile = dirname(G5_PATH).$imgurl['path'];
        $srcfile = (G5_PATH).$imgurl['path'];
        if(!preg_match('/(\.jpe?g|\.gif|\.png|\.webp)$/i', $srcfile)) continue;
        $filename = preg_replace("/\.[^\.]+$/i", "", basename($srcfile));
        $filepath = dirname($srcfile);
        $files = glob($filepath.'/thumb-'.$filename.'*');
        if (is_array($files)) {
            foreach($files as $filename)
                unlink($filename);
        }
    }

    run_event('delete_editor_thumbnail_after', $contents, $matchs);
}

