<?php
define('G5_IS_ADMIN', true);
include_once ('../../common.php');
include_once(G5_ADMIN_PATH.'/admin.lib.php');
include_once(G5_SMS5_PATH.'/sms5.lib.php');

if (!strstr($_SERVER['SCRIPT_NAME'], 'install.php')) {
    // SMS5 테이블 G5_TABLE_PREFIX 적용
    if($g5['sms5_prefix'] != 'sms5_' && sql_num_rows(sql_query("show tables like 'sms5_config'")))
    {
        $tables = array('config','write','history','book','book_group','form','form_group');

        foreach ($tables as $name) {
            $old_table = 'sms5_' . $name;
            $new_table = $g5['sms5_prefix'] . $name;

            if (sql_num_rows(sql_query("SHOW TABLES LIKE '{$old_table}' "))) {
                if (!sql_num_rows(sql_query("SHOW TABLES LIKE '{$new_table}' "))) {
                    sql_query("RENAME TABLE {$old_table} TO {$new_table}", false);
                }
            }
        }
    }

    if(!sql_num_rows(sql_query(" show tables like '{$g5['sms5_config_table']}' ")))
        goto_url('install.php');

    // SMS 설정값 배열변수
    //$sms5 = sql_fetch("select * from ".$g5['sms5_config_table'] );
}

$sv = isset($_REQUEST['sv']) ? get_search_string($_REQUEST['sv']) : '';
$st = (isset($_REQUEST['st']) && $st) ? substr(get_search_string($_REQUEST['st']), 0, 12) : '';

if( isset($token) ){
    $token = @htmlspecialchars(strip_tags($token), ENT_QUOTES);
}

add_stylesheet('<link rel="stylesheet" href="'.G5_SMS5_ADMIN_URL.'/css/sms5.css">', 0);
