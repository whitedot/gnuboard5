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

// 파일다운로드 링크 생성시 nonce 키 추가, 7200은 2시간동안 유효
function download_file_nonce_key($bo_table, $wr_id, $timeoutSeconds=7200)
{
    $secret = get_token_encryption_key(sha1($bo_table.session_id().$wr_id));
    $salt = get_random_token_string(10);
    $maxTime = G5_SERVER_TIME + $timeoutSeconds;
    $nonce = $salt . '|' . $maxTime . '|' . sha1($salt . $secret . $maxTime);

    return $nonce;
}

// 파일다운로드시 nonce key를 체크한다.
function download_file_nonce_is_valid($nonce, $bo_table, $wr_id)
{
    if (! is_string($nonce)) return false;
    $secret = get_token_encryption_key(sha1($bo_table.session_id().$wr_id));
    $a = explode('|', $nonce);
    if (count($a) !== 3) return false;
    list($salt, $maxTime, $hash) = $a;
    if (sha1($salt . $secret . $maxTime) !== $hash) return false;
    if (G5_SERVER_TIME > (int) $maxTime) return false;

    return true;
}

// 게시글에 첨부된 파일을 얻는다. (배열로 반환)
function get_file($bo_table, $wr_id)
{
    global $g5, $qstr, $board;

    $file['count'] = 0;
    $sql = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' order by bf_no ";
    $result = sql_query($sql);
    $nonce = download_file_nonce_key($bo_table, $wr_id);
    while ($row = sql_fetch_array($result))
    {
        $no = (int) $row['bf_no'];
        $bf_content = $row['bf_content'] ? html_purifier($row['bf_content']) : '';
        $file[$no]['href'] = G5_BBS_URL."/download.php?bo_table=$bo_table&amp;wr_id=$wr_id&amp;no=$no&amp;nonce=$nonce" . $qstr;
        $file[$no]['download'] = $row['bf_download'];
        // 4.00.11 - 파일 path 추가
        $file[$no]['path'] = G5_DATA_URL.'/file/'.$bo_table;
        $file[$no]['size'] = get_filesize($row['bf_filesize']);
        $file[$no]['datetime'] = $row['bf_datetime'];
        $file[$no]['source'] = addslashes($row['bf_source']);
        $file[$no]['bf_content'] = $bf_content;
        $file[$no]['content'] = get_text($bf_content);
        //$file[$no]['view'] = view_file_link($row['bf_file'], $file[$no]['content']);
        $file[$no]['view'] = view_file_link($row['bf_file'], $row['bf_width'], $row['bf_height'], $file[$no]['content']);
        $file[$no]['file'] = $row['bf_file'];
        $file[$no]['image_width'] = $row['bf_width'] ? $row['bf_width'] : 640;
        $file[$no]['image_height'] = $row['bf_height'] ? $row['bf_height'] : 480;
        $file[$no]['image_type'] = $row['bf_type'];
        $file[$no]['bf_fileurl'] = $row['bf_fileurl'];
        $file[$no]['bf_thumburl'] = $row['bf_thumburl'];
        $file[$no]['bf_storage'] = $row['bf_storage'];
        $file['count']++;
    }

    return run_replace('get_files', $file, $bo_table, $wr_id);
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

// 파일을 보이게 하는 링크 (이미지, 플래쉬, 동영상)
function view_file_link($file, $width, $height, $content='')
{
    global $config, $board;
    global $g5;
    static $ids;

    if (!$file) return;

    $ids++;

    // 파일의 폭이 게시판설정의 이미지폭 보다 크다면 게시판설정 폭으로 맞추고 비율에 따라 높이를 계산
    if ($board && $width > $board['bo_image_width'] && $board['bo_image_width'])
    {
        $rate = $board['bo_image_width'] / $width;
        $width = $board['bo_image_width'];
        $height = (int)($height * $rate);
    }

    // 폭이 있는 경우 폭과 높이의 속성을 주고, 없으면 자동 계산되도록 코드를 만들지 않는다.
    if ($width)
        $attr = ' width="'.$width.'" height="'.$height.'" ';
    else
        $attr = '';

    if (preg_match("/\.({$config['cf_image_extension']})$/i", $file) && isset($board['bo_table'])) {
        $attr_href = run_replace('thumb_view_image_href', G5_BBS_URL.'/view_image.php?bo_table='.$board['bo_table'].'&amp;fn='.urlencode($file), $file, $board['bo_table'], $width, $height, $content);
        $img = '<a href="'.$attr_href.'" target="_blank">';
        $img .= '<img src="'.G5_DATA_URL.'/file/'.$board['bo_table'].'/'.urlencode($file).'" alt="'.$content.'" '.$attr.'>';
        $img .= '</a>';

        return $img;
    }
}

// view_file_link() 함수에서 넘겨진 이미지를 보이게 합니다.
// {img:0} ... {img:n} 과 같은 형식
function view_image($view, $number, $attribute)
{
    if ($view['file'][$number]['view'])
        return preg_replace("/>$/", " $attribute>", $view['file'][$number]['view']);
    else
        //return "{".$number."번 이미지 없음}";
        return "";
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

/**
 * 게시판 최신글 캐시 파일 삭제
 * @param string $bo_table 게시판 ID
 */
function delete_cache_latest($bo_table)
{
    if (!preg_match("/^([A-Za-z0-9_]{1,20})$/", $bo_table)) {
        return;
    }

    run_event('delete_cache_latest', $bo_table);

    g5_delete_cache_by_prefix('latest-' . $bo_table . '-');
}

// 게시판 첨부파일 썸네일 삭제
function delete_board_thumbnail($bo_table, $file)
{
    if(!$bo_table || !$file)
        return;

    $fn = preg_replace("/\.[^\.]+$/i", "", basename($file));
    $files = glob(G5_DATA_PATH.'/file/'.$bo_table.'/thumb-'.$fn.'*');
    if (is_array($files)) {
        foreach ($files as $filename)
            unlink($filename);
    }
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

// 1:1문의 첨부파일 썸네일 삭제
function delete_qa_thumbnail($file)
{
    if(!$file)
        return;

    $fn = preg_replace("/\.[^\.]+$/i", "", basename($file));
    $files = glob(G5_DATA_PATH.'/qa/thumb-'.$fn.'*');
    if (is_array($files)) {
        foreach ($files as $filename)
            unlink($filename);
    }
}
