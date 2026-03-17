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


