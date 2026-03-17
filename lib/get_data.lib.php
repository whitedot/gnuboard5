<?php
if (!defined('_GNUBOARD_')) exit;

function get_config($is_cache=false){
    global $g5;

    static $cache = array();

    $cache = run_replace('get_config_cache', $cache, $is_cache);

    if( $is_cache && !empty($cache) ){
        return $cache;
    }

    $sql = " select * from {$g5['config_table']} ";
    $cache = run_replace('get_config', sql_fetch($sql));

    return $cache;
}

function get_content_db($co_id, $is_cache=false){
    global $g5, $g5_object;

    static $cache = array();
    
    $type = 'content';

    $co_id = preg_replace('/[^a-z0-9_]/i', '', $co_id);
    $co = $g5_object->get($type, $co_id, $type);

    if( !$co ){

        $cache_file_name = "{$type}-{$co_id}-".g5_cache_secret_key();
        $co = g5_get_cache($cache_file_name, 10800);
        
        if( $co === false ){
            $sql = " select * from {$g5['content_table']} where co_id = '$co_id' ";
            $co = sql_fetch($sql);
            
            g5_set_cache($cache_file_name, $co, 10800);
        }

        $g5_object->set($type, $co_id, $co, $type);
    }

    return $co;
}

function get_menu_db($use_mobile=0, $is_cache=false){
    global $g5;

    static $cache = array();

    $cache = run_replace('get_menu_db_cache', $cache, $use_mobile, $is_cache);

    $key = md5($use_mobile);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    $where = $use_mobile ? "me_mobile_use = '1'" : "me_use = '1'";

    if( !($cache[$key] = run_replace('get_menu_db', array(), $use_mobile)) ){
        $sql = " select *
                from {$g5['menu_table']}
                where $where
                and length(me_code) = '2'
                order by me_order, me_id ";
        $result = sql_query($sql, false);

        for ($i=0; $row=sql_fetch_array($result); $i++) {

            $row['ori_me_link'] = $row['me_link'];
            $row['me_link'] = short_url_clean($row['me_link']);
            $row['sub'] = isset($row['sub']) ? $row['sub'] : array();
            $cache[$key][$i] = $row;

            $sql2 = " select *
                    from {$g5['menu_table']}
                    where $where
                    and length(me_code) = '4'
                    and substring(me_code, 1, 2) = '{$row['me_code']}'
                    order by me_order, me_id ";
            $result2 = sql_query($sql2);
            for ($k=0; $row2=sql_fetch_array($result2); $k++) {
                $row2['ori_me_link'] = $row2['me_link'];
                $row2['me_link'] = short_url_clean($row2['me_link']);
                $cache[$key][$i]['sub'][$k] = $row2;
            }
        }
    }

    return $cache[$key];
}

function get_point_db($po_id, $is_cache=false){
    global $g5;

    static $cache = array();

    $po_id = (int) $po_id;
    $key = md5($po_id);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    $sql = " select * from {$g5['point_table']} where po_id = '{$po_id}' ";

    $cache[$key] = sql_fetch($sql);

    return $cache[$key];
}

function get_mail_content_db($ma_id, $is_cache=false){
    global $g5;

    static $cache = array();

    $ma_id = (int) $ma_id;
    $key = md5($ma_id);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    $sql = " select * from {$g5['mail_table']} where ma_id = '{$ma_id}' ";

    $cache[$key] = sql_fetch($sql);

    return $cache[$key];
}

function get_db_charset($charset){

    $add_charset = $charset;

    if ( 'utf8mb4' === $charset ) {
        $add_charset .= ' COLLATE utf8mb4_unicode_ci';
    }

    return run_replace('get_db_charset', $add_charset, $charset);
}

function get_db_create_replace($sql_str){

    if( in_array(strtolower(G5_DB_ENGINE), array('innodb', 'myisam')) ){
        $sql_str = preg_replace('/ENGINE=MyISAM/', 'ENGINE='.G5_DB_ENGINE, $sql_str);
    } else {
        $sql_str = preg_replace('/ENGINE=MyISAM/', '', $sql_str);
    }

    if( G5_DB_CHARSET !== 'utf8' ){
        $sql_str = preg_replace('/CHARSET=utf8/', 'CHARACTER SET '.get_db_charset(G5_DB_CHARSET), $sql_str);
    }

    return $sql_str;
}

function get_class_encrypt(){
    static $cache;

    if( $cache && is_object($cache) ){
        return $cache;
    }

    $cache = run_replace('get_class_encrypt', new str_encrypt());

    return $cache;
}

function get_string_encrypt($str){

    $new = get_class_encrypt();

    $encrypt_str = $new->encrypt($str);

    return $encrypt_str;
}

function get_string_decrypt($str){

    $new = get_class_encrypt();

    $decrypt_str = $new->decrypt($str);

    return $decrypt_str;
}

function get_permission_debug_show(){
    global $member;

    $bool = false;
    if ( defined('G5_DEBUG') && G5_DEBUG ){
        $bool = true;
    }

    return run_replace('get_permission_debug_show', $bool, $member);
}

function get_check_mod_rewrite(){

    if( function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) )
        $mod_rewrite = 1;
    elseif( isset($_SERVER['IIS_UrlRewriteModule']) )
        $mod_rewrite = 1;
    else
        $mod_rewrite = 0;

    return $mod_rewrite;
}

function get_mb_icon_name($mb_id){

    if( $icon_name = run_replace('get_mb_icon_name', '', $mb_id) ){
        return $icon_name;
    }

    return $mb_id;
}

// 생성되면 안되는 게시판명
function get_bo_table_banned_word(){

    $folders = array(G5_CONTENT_DIR, 'rss');

    foreach(glob(G5_PATH.'/*', GLOB_ONLYDIR) as $dir) {
        $folders[] = basename($dir);
    }

    return run_replace('get_bo_table_banned_word', $folders);
}

function get_board_sort_fields($board=array(), $make_key_return=''){
    $bo_sort_fields = run_replace('get_board_sort_fields', array(
        array('wr_num, wr_reply', '기본'),
        array('wr_datetime asc', '날짜 이전것 부터'),
        array('wr_datetime desc', '날짜 최근것 부터'),
        array('wr_hit asc, wr_num, wr_reply', '조회수 낮은것 부터'),
        array('wr_hit desc, wr_num, wr_reply', '조회수 높은것 부터'),
        array('wr_last asc', '최근글 이전것 부터'),
        array('wr_last desc', '최근글 최근것 부터'),
        array('wr_comment asc, wr_num, wr_reply', '댓글수 낮은것 부터'),
        array('wr_comment desc, wr_num, wr_reply', '댓글수 높은것 부터'),
        array('wr_good asc, wr_num, wr_reply', '추천수 낮은것 부터'),
        array('wr_good desc, wr_num, wr_reply', '추천수 높은것 부터'),
        array('wr_nogood asc, wr_num, wr_reply', '비추천수 낮은것 부터'),
        array('wr_nogood desc, wr_num, wr_reply', '비추천수 높은것 부터'),
        array('wr_subject asc, wr_num, wr_reply', '제목 오름차순'),
        array('wr_subject desc, wr_num, wr_reply', '제목 내림차순'),
        array('wr_name asc, wr_num, wr_reply', '글쓴이 오름차순'),
        array('wr_name desc, wr_num, wr_reply', '글쓴이 내림차순'),
        array('ca_name asc, wr_num, wr_reply', '분류명 오름차순'),
        array('ca_name desc, wr_num, wr_reply', '분류명 내림차순'),
    ), $board, $make_key_return);

    if( $make_key_return ){
        
        $returns = array();
        foreach( $bo_sort_fields as $v ){
            $key = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $v[0]);
            $returns[$key] = $v[0];
        }
        
        return $returns;
    }
    return $bo_sort_fields;
}

function get_board_sfl_select_options($sfl){

    global $is_admin;

    $str = '';
    $str .= '<option value="wr_subject" '.get_selected($sfl, 'wr_subject', true).'>제목</option>';
    $str .= '<option value="wr_content" '.get_selected($sfl, 'wr_content').'>내용</option>';
    $str .= '<option value="wr_subject||wr_content" '.get_selected($sfl, 'wr_subject||wr_content').'>제목+내용</option>';
    if ( $is_admin ){
        $str .= '<option value="mb_id,1" '.get_selected($sfl, 'mb_id,1').'>회원아이디</option>';
        $str .= '<option value="mb_id,0" '.get_selected($sfl, 'mb_id,0').'>회원아이디(코)</option>';
    }
    $str .= '<option value="wr_name,1" '.get_selected($sfl, 'wr_name,1').'>글쓴이</option>';
    $str .= '<option value="wr_name,0" '.get_selected($sfl, 'wr_name,0').'>글쓴이(코)</option>';

    return run_replace('get_board_sfl_select_options', $str, $sfl);
}

