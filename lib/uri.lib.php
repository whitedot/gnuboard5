<?php
if (!defined('_GNUBOARD_')) exit;

function get_content_page_url()
{
    return G5_URL.'/content.php';
}

function is_member_page_request($folder)
{
    if (!preg_match('/^[a-z0-9_.-]+$/i', (string) $folder)) {
        return false;
    }

    return is_file(G5_MEMBER_PATH.'/'.$folder.'.php');
}

function get_page_url($folder)
{
    if ($folder === 'content') {
        return get_content_page_url();
    }

    if (is_member_page_request($folder)) {
        return G5_MEMBER_URL.'/'.$folder.'.php';
    }

    $root_file = G5_PATH.'/'.$folder.'.php';

    if (is_file($root_file)) {
        return G5_URL.'/'.$folder.'.php';
    }

    return G5_URL;
}

// 짧은 주소 형식으로 만들어서 가져온다.
function get_pretty_url($folder, $no='', $query_string='', $action='')
{
    $segments = array();
    $url = $add_query = '';

    if( $url = run_replace('get_pretty_url', $url, $folder, $no, $query_string, $action) ){
        return $url;
    }

    if ($folder === 'content') {

        $segments[0] = G5_URL;
        $segments[1] = $folder;

        if ($no) {
            $get_content = get_content_db($no, true);
            $segments[2] = (isset($get_content['co_seo_title']) && $get_content['co_seo_title']) ? urlencode($get_content['co_seo_title']).'/' : urlencode($no);
        }

        if($query_string) {
            // If the first character of the query string is '&', replace it with '?'.
            if(substr($query_string, 0, 1) == '&') {
                $add_query = preg_replace("/\&amp;/", "?", $query_string, 1);
            } else {
                $add_query = '?'. $query_string;
            }
        }

    } else {
        $url = get_page_url($folder);

        if ($folder === 'content' && $no) {
            $url .= '?co_id='.$no;
        } else if ($no && $url !== G5_URL) {
            $url .= '?'.$no;
        }

        if ($query_string) {
            $url .= (!$no ? '?' : '&amp;').$query_string;
        }

        $segments[0] = $url;
    }

    return implode('/', $segments).$add_query;
}

function short_url_clean($string_url, $add_qry=''){

    $string_url = str_replace('&amp;', '&', $string_url);
    $url = parse_url($string_url);
    $page_name = isset($url['path']) ? basename($url['path'], ".php") : '';

    if ($page_name !== 'content') {
        return run_replace('false_short_url_clean', $string_url, $url, $page_name, array('content'));
    }

    $normalized_string_url = preg_replace('/^https?:/i', '', $string_url);
    $normalized_content_url = preg_replace('/^https?:/i', '', get_content_page_url());

    if (stripos($normalized_string_url, $normalized_content_url) === false) {
        return run_replace('false_short_url_clean', $string_url, $url, $page_name, array('content'));
    }

    parse_str(isset($url['query']) ? $url['query'] : '', $vars);
    $fragment = isset($url['fragment']) ? '#'.$url['fragment'] : '';
    $host = G5_URL;

    if (isset($url['host'])) {
        $array_file_paths = run_replace('url_clean_page_paths', array('/content.php'));
        $str_path = isset($url['path']) ? $url['path'] : '';
        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://';
        $port = (isset($url['port']) && ($url['port'] !== 80 && $url['port'] !== 443)) ? ':'.$url['port'] : '';
        $host = $http.$url['host'].$port.str_replace($array_file_paths, '', $str_path);
    }

    $return_url = '';

    if (!empty($vars['co_id'])) {
        $content = get_content_db($vars['co_id'], true);
        $return_url = '/'.((isset($content['co_seo_title']) && $content['co_seo_title']) ? urlencode($content['co_seo_title']).'/' : urlencode($vars['co_id']));
    }

    $add_param = '';
    $extra_vars = $vars;
    unset($extra_vars['co_id']);

    if ($extra_vars) {
        $add_param = '?'.http_build_query($extra_vars, '', '&amp;');
    }

    if ($add_qry) {
        $add_param .= $add_param ? '&amp;'.$add_qry : '?'.$add_qry;
    }

    return $host.'/content'.$return_url.$add_param.$fragment;
}

function correct_goto_url($url){

    if( substr($url, -1) !== '/' ){
		return $url.'/';
	}

	return $url;
}

function generate_seo_title($string, $wordLimit=G5_SEO_TITLE_WORD_CUT){
    $separator = '-';
    
    if($wordLimit != 0){
        $wordArr = explode(' ', $string);
        $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
    }

    $quoteSeparator = preg_quote($separator, '#');

    $trans = array(
        '&.+?;'                    => '',
        '[^\w\d _-]'            => '',
        '\s+'                    => $separator,
        '('.$quoteSeparator.')+'=> $separator
    );

    $string = strip_tags($string);

    if( function_exists('mb_convert_encoding') ){
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    foreach ($trans as $key => $val){
        $string = preg_replace('#'.$key.'#iu', $val, $string);
    }

    $string = strtolower($string);

    return trim(trim($string, $separator));
}

function exist_seo_url($type, $seo_title, $write_table, $sql_id=0){
    global $g5;

    $exists_title = '';
    $sql_id = preg_replace('/[^a-z0-9_\-]/i', '', $sql_id);
	// 영카트 상품코드의 경우 - 하이픈이 들어가야 함

    if ($type === 'content') {

        $sql = "select co_seo_title FROM {$write_table} WHERE co_seo_title = '".sql_real_escape_string($seo_title)."' AND co_id <> '$sql_id' limit 1";
        $row = sql_fetch($sql);

        $exists_title = isset($row['co_seo_title']) ? $row['co_seo_title'] : '';

    } else {
        return run_replace('exist_check_seo_title', $seo_title, $type, $write_table, $sql_id);
    }

    return $exists_title ? 'is_exists' : '';
}

function check_case_exist_title($data, $case=G5_CONTENT_DIR, $is_redirect=false) {
    global $g5;

    $seo_title = '';
    $redirect_url = '';

    if ($case == G5_CONTENT_DIR && isset($data['co_seo_title'])) {
        $db_table = $g5['content_table'];

        if (exist_seo_url($case, $data['co_seo_title'], $db_table, $data['co_id'])) {
            $seo_title = $data['co_seo_title'].'-'.substr(get_random_token_string(4), 4);
            $sql = " update `{$db_table}` set co_seo_title = '".sql_real_escape_string($seo_title)."' where co_id = '{$data['co_id']}' ";
            sql_query($sql, false);
            
            get_content_db($data['co_id'], false);
            g5_delete_cache_by_prefix('content-' . $data['co_id'] . '-');
            $redirect_url = get_pretty_url($case, $data['co_id']);
        }
    }

    if ($is_redirect && $seo_title && $redirect_url) {
        goto_url($redirect_url);
    }
}

function exist_seo_title_recursive($type, $seo_title, $write_table, $sql_id=0){
    static $count = 0;

    $seo_title_add = ($count > 0) ? utf8_strcut($seo_title, 100000 - ($count+1), '')."-$count" : $seo_title;

    if( ! exist_seo_url($type, $seo_title_add, $write_table, $sql_id) ){
        return $seo_title_add;
    }
    
    $count++;

    if( $count > 99998 ){
        return $seo_title_add;
    }

    return exist_seo_title_recursive($type, $seo_title, $write_table, $sql_id);
}

function seo_title_update($db_table, $pk_id, $type='content'){
    $pk_id = (int) $pk_id;

    if ($type === 'content') {
        $co = get_content_db($pk_id, true);
        if( ! (isset($co['co_seo_title']) && $co['co_seo_title']) && (isset($co['co_subject']) && $co['co_subject']) ){
            $co_seo_title = exist_seo_title_recursive('content', generate_seo_title($co['co_subject']), $db_table, $pk_id);

            $sql = " update `{$db_table}` set co_seo_title = '{$co_seo_title}' where co_id = '{$pk_id}' ";
            sql_query($sql);
        }
    }
}

function get_nginx_conf_rules($return_string = false)
{
    $get_path_url = parse_url(G5_URL);
    $base_path = isset($get_path_url['path']) ? $get_path_url['path'] . '/' : '/';

    $rules = array();
    $rules[] = '#### ' . G5_VERSION . ' nginx rules BEGIN #####';

    if ($add_rules = run_replace('add_nginx_conf_pre_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = 'if (!-e $request_filename) {';

    if ($add_rules = run_replace('add_nginx_conf_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = "rewrite ^{$base_path}content/([0-9a-zA-Z_]+)$ {$base_path}content.php?co_id=$1&rewrite=1 break;";
    $rules[] = "rewrite ^{$base_path}content/([^/]+)/$ {$base_path}content.php?co_seo_title=$1&rewrite=1 break;";
    $rules[] = '}';
    $rules[] = '#### ' . G5_VERSION . ' nginx rules END #####';

    return $return_string ? implode("\n", $rules) : $rules;
}

function get_mod_rewrite_rules($return_string = false)
{
    $get_path_url = parse_url(G5_URL);
    $base_path = isset($get_path_url['path']) ? $get_path_url['path'] . '/' : '/';

    $rules = array();
    $rules[] = '#### ' . G5_VERSION . ' rewrite BEGIN #####';
    $rules[] = '<IfModule mod_rewrite.c>';
    $rules[] = 'RewriteEngine On';
    $rules[] = 'RewriteBase ' . $base_path;

    if ($add_rules = run_replace('add_mod_rewrite_pre_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = 'RewriteCond %{REQUEST_FILENAME} -f [OR]';
    $rules[] = 'RewriteCond %{REQUEST_FILENAME} -d';
    $rules[] = 'RewriteRule ^ - [L]';

    if ($add_rules = run_replace('add_mod_rewrite_rules', '', $get_path_url, $base_path, $return_string)) {
        $rules[] = $add_rules;
    }

    $rules[] = 'RewriteRule ^content/([0-9a-zA-Z_]+)$ content.php?co_id=$1&rewrite=1 [QSA,L]';
    $rules[] = 'RewriteRule ^content/([^/]+)/$ content.php?co_seo_title=$1&rewrite=1 [QSA,L]';
    $rules[] = '</IfModule>';
    $rules[] = '#### ' . G5_VERSION . ' rewrite END #####';

    return $return_string ? implode("\n", $rules) : $rules;
}

function check_need_rewrite_rules(){
    $is_apache = (stripos($_SERVER['SERVER_SOFTWARE'], 'apache') !== false);
    
    if($is_apache){
        $save_path = G5_PATH.'/.htaccess';

        if( !file_exists($save_path) ){
            return true;
        }

        $rules = get_mod_rewrite_rules();

        $bof_str = $rules[0];
        $eof_str = end($rules);

        $code = file_get_contents($save_path);
        
        if( strpos($code, $bof_str) === false || strpos($code, $eof_str) === false ){
            return true;
        }
    }

    return false;
}

function update_rewrite_rules(){

    $is_apache = (stripos($_SERVER['SERVER_SOFTWARE'], 'apache') !== false);

    if($is_apache){
        $save_path = G5_PATH.'/.htaccess';

        if( (!file_exists($save_path) && is_writable(G5_PATH)) || is_writable($save_path) ){

            $rules = get_mod_rewrite_rules();

            $bof_str = $rules[0];
            $eof_str = end($rules);

            if( file_exists($save_path) ){
                $code = file_get_contents($save_path);
                
                if( $code && strpos($code, $bof_str) !== false && strpos($code, $eof_str) !== false ){
                    return true;
                }
            }

            $fp = fopen($save_path, "ab");
            flock( $fp, LOCK_EX );
            
            $rewrite_str = implode("\n", $rules);
            
            fwrite( $fp, "\n" );
            fwrite( $fp, $rewrite_str );
            fwrite( $fp, "\n" );

            flock( $fp, LOCK_UN );
            fclose($fp);
            
            return true;
        }
    }

    return false;

}
