<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * 마이크로타임을 반환
 * @return float
 * @deprecated use `microtime(true)`
 */
function get_microtime()
{
    return microtime(true);
}

// 변수 또는 배열의 이름과 값을 얻어냄. print_r() 함수의 변형
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span>$str</span>");
}

// 메타태그를 이용한 URL 이동
// header("location:URL") 을 대체
function goto_url($url)
{
    run_event('goto_url', $url);

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }

    $url = str_replace("&amp;", "&", $url);
    //echo "<script> location.replace('$url'); </script>";

    if (!headers_sent())
        header('Location: '.$url);
    else {
        echo '<script>';
        echo 'location.replace("'.$url.'");';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
    }
    exit;
}

// 리퍼러 체크
function referer_check($url='')
{
    /*
    // 제대로 체크를 하지 못하여 주석 처리함
    global $g5;

    if (!$url)
        $url = G5_URL;

    if (!preg_match("/^http['s']?:\/\/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER']))
        alert("제대로 된 접근이 아닌것 같습니다.", $url);
    */
}

// 한글 요일
function get_yoil($date, $full=0)
{
    $arr_yoil = array ('일', '월', '화', '수', '목', '금', '토');

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= '요일';
    }
    return $str;
}

// DEMO 라는 파일이 있으면 데모 화면으로 인식함
function check_demo()
{
    global $is_admin;
    if ($is_admin != 'super' && file_exists(G5_PATH.'/DEMO'))
        alert('데모 화면에서는 하실(보실) 수 없는 작업입니다.');
}

// 문자열이 한글, 영문, 숫자, 특수문자로 구성되어 있는지 검사
function check_string($str, $options)
{
    global $g5;

    $s = '';
    for($i=0;$i<strlen($str);$i++) {
        $c = $str[$i];
        $oc = ord($c);

        // 한글
        if ($oc >= 0xA0 && $oc <= 0xFF) {
            if ($options & G5_HANGUL) {
                $s .= $c . $str[$i+1] . $str[$i+2];
            }
            $i+=2;
        }
        // 숫자
        else if ($oc >= 0x30 && $oc <= 0x39) {
            if ($options & G5_NUMERIC) {
                $s .= $c;
            }
        }
        // 영대문자
        else if ($oc >= 0x41 && $oc <= 0x5A) {
            if (($options & G5_ALPHABETIC) || ($options & G5_ALPHAUPPER)) {
                $s .= $c;
            }
        }
        // 영소문자
        else if ($oc >= 0x61 && $oc <= 0x7A) {
            if (($options & G5_ALPHABETIC) || ($options & G5_ALPHALOWER)) {
                $s .= $c;
            }
        }
        // 공백
        else if ($oc == 0x20) {
            if ($options & G5_SPACE) {
                $s .= $c;
            }
        }
        else {
            if ($options & G5_SPECIAL) {
                $s .= $c;
            }
        }
    }

    // 넘어온 값과 비교하여 같으면 참, 틀리면 거짓
    return ($str == $s);
}

// 한글(2bytes)에서 마지막 글자가 1byte로 끝나는 경우
// 출력시 깨지는 현상이 발생하므로 마지막 완전하지 않은 글자(1byte)를 하나 없앰
function cut_hangul_last($hangul)
{
    global $g5;

    // 한글이 반쪽나면 ?로 표시되는 현상을 막음
    $cnt = 0;
    for($i=0;$i<strlen($hangul);$i++) {
        // 한글만 센다
        if (ord($hangul[$i]) >= 0xA0) {
            $cnt++;
        }
    }

    return $hangul;
}

// $_POST 형식에서 checkbox 엘리먼트의 checked 속성에서 checked 가 되어 넘어 왔는지를 검사
function is_checked($field)
{
    return !empty($_POST[$field]);
}

function abs_ip2long($ip='')
{
    $ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
    return abs(ip2long($ip));
}

function get_selected($field, $value)
{
    if( is_int($value) ){
        return ((int) $field===$value) ? ' selected="selected"' : '';
    }

    return ($field===$value) ? ' selected="selected"' : '';
}

function get_checked($field, $value)
{
    if( is_int($value) ){
        return ((int) $field===$value) ? ' checked="checked"' : '';
    }

    return ($field===$value) ? ' checked="checked"' : '';
}

/*******************************************************************************
    유일한 키를 얻는다.

    결과 :

        년월일시분초00 ~ 년월일시분초99
        년(4) 월(2) 일(2) 시(2) 분(2) 초(2) 100분의1초(2)
        총 16자리이며 년도는 2자리로 끊어서 사용해도 됩니다.
        예) 2008062611570199 또는 08062611570199 (2100년까지만 유일키)

    사용하는 곳 :
    1. 파일 업로드 전에 미리 유일키를 얻어 업로드 필드에 넣는다.
    2. 별도의 고유 식별자가 필요한 값 생성에 사용한다.
    3. 기타 유일키가 필요한 곳에서 사용한다.
*******************************************************************************/
// 기존의 get_unique_id() 함수를 사용하지 않고 get_uniqid() 를 사용한다.
function get_uniqid()
{
    global $g5;

    if ($get_uniqid_key = run_replace('get_uniqid_key', '')) {
        return $get_uniqid_key;
    }
    
    if (!sql_lock_tables_write($g5['uniqid_table'])) {
        return '';
    }
    while (1) {
        // 년월일시분초에 100분의 1초 두자리를 추가함 (1/100 초 앞에 자리가 모자르면 0으로 채움)
        $key = date('YmdHis', time()) . str_pad((int)((float)microtime()*100), 2, "0", STR_PAD_LEFT);

        $result = sql_query_prepared(" insert into {$g5['uniqid_table']} set uq_id = :uq_id, uq_ip = :uq_ip ", array(
            'uq_id' => $key,
            'uq_ip' => $_SERVER['REMOTE_ADDR'],
        ), false);
        if ($result) break; // 쿼리가 정상이면 빠진다.

        // insert 하지 못했으면 일정시간 쉰다음 다시 유일키를 만든다.
        usleep(10000); // 100분의 1초를 쉰다
    }
    sql_unlock_tables();

    return $key;
}

// 휴대폰번호의 숫자만 취한 후 중간에 하이픈(-)을 넣는다.
function hyphen_hp_number($hp)
{
    $hp = preg_replace("/[^0-9]/", "", $hp);
    return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
}

// 로그인 후 이동할 URL
function login_url($url='')
{
    if (!$url) $url = G5_URL;

    return urlencode(clean_xss_tags(urldecode($url)));
}

// $dir 을 포함하여 https 또는 http 주소를 반환한다.
function https_url($dir, $https=true)
{
    if ($https) {
        if (G5_HTTPS_DOMAIN) {
            $url = G5_HTTPS_DOMAIN.'/'.$dir;
        } else {
            $url = G5_URL.'/'.$dir;
        }
    } else {
        if (G5_DOMAIN) {
            $url = G5_DOMAIN.'/'.$dir;
        } else {
            $url = G5_URL.'/'.$dir;
        }
    }

    return $url;
}

// input vars 체크
function check_input_vars()
{
    $max_input_vars = ini_get('max_input_vars');

    if($max_input_vars) {
        $post_vars = count($_POST, COUNT_RECURSIVE);
        $get_vars = count($_GET, COUNT_RECURSIVE);
        $cookie_vars = count($_COOKIE, COUNT_RECURSIVE);

        $input_vars = $post_vars + $get_vars + $cookie_vars;

        if($input_vars > $max_input_vars) {
            alert('폼에서 전송된 변수의 개수가 max_input_vars 값보다 큽니다.\\n전송된 값중 일부는 유실되어 DB에 기록될 수 있습니다.\\n\\n문제를 해결하기 위해서는 서버 php.ini의 max_input_vars 값을 변경하십시오.');
        }
    }
}

// date 형식 변환
function conv_date_format($format, $date, $add='')
{
    if($add)
        $timestamp = strtotime($add, strtotime($date));
    else
        $timestamp = strtotime($date);

    return date($format, $timestamp);
}

function clean_relative_paths($path){
    $path_len = strlen($path);
    
    $i = 0;
    while($i <= $path_len){
        $result = str_replace('../', '', str_replace('\\', '/', $path));

        if((string)$result === (string)$path) break;

        $path = $result;
        $i++;
    }

    return $path;
}

// 회원 삭제
function member_delete($mb_id)
{
    global $config;
    global $g5;

    $sql = " select mb_name, mb_nick, mb_ip, mb_memo, mb_level from {$g5['member_table']} where mb_id = :mb_id ";
    $mb = sql_fetch_prepared($sql, array(
        'mb_id' => $mb_id,
    ));

    // 이미 삭제된 회원은 제외
    if(preg_match('#^[0-9]{8}.*삭제함#', $mb['mb_memo']))
        return;

    // 회원자료는 정보만 없앤 후 아이디는 보관하여 다른 사람이 사용하지 못하도록 함 : 061025
    $delete_memo = date('Ymd', G5_SERVER_TIME) . " 삭제함\n" . $mb['mb_memo'];
    sql_query_prepared(" update {$g5['member_table']} set mb_password = '', mb_level = 1, mb_email = '', mb_hp = '', mb_birth = '', mb_sex = '', mb_memo = :mb_memo, mb_certify = '', mb_adult = 0, mb_dupinfo = '' where mb_id = :mb_id ", array(
        'mb_memo' => $delete_memo,
        'mb_id' => $mb_id,
    ));

    // 관리권한 삭제
    sql_query_prepared(" delete from {$g5['auth_table']} where mb_id = :mb_id ", array(
        'mb_id' => $mb_id,
    ));

    // 아이콘 삭제
    @unlink(G5_DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif');

    run_event('member_delete_after', $mb_id);
}

// 이메일 주소 추출
function get_email_address($email)
{
    preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", $email, $matches);

    return isset($matches[0]) ? $matches[0] : '';
}

function safe_filter_url_host($url) {

    $regex = run_replace('safe_filter_url_regex', '\\', $url);

    return $regex ? preg_replace('#'. preg_quote($regex, '#') .'#iu', '', $url) : '';
}

// 동일한 host url 인지
function check_url_host($url, $msg='', $return_url=G5_URL, $is_redirect=false)
{
    if(!$msg)
        $msg = 'url에 타 도메인을 지정할 수 없습니다.';

    if(run_replace('check_url_host_before', '', $url, $msg, $return_url, $is_redirect) === 'is_checked'){
        return;
    }

    // KVE-2021-1277 Open Redirect 취약점 해결
    if (preg_match('#\\\0#', $url) || preg_match('/^\/{1,}\\\/', $url)) {
        alert('url 에 올바르지 않은 값이 포함되어 있습니다.');
    }

    if (preg_match('#//[^/@]+@#', $url)) {
        alert('url에 사용자 정보가 포함되어 있어 접근할 수 없습니다.');
    }

    while ( ( $replace_url = preg_replace(array('/\/{2,}/', '/\\@/'), array('//', ''), urldecode($url)) ) != $url ) {
        $url = $replace_url;
    }

    $p = @parse_url(trim(str_replace('\\', '', $url)));
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $is_host_check = false;
    
    // url을 urlencode 를 2번이상하면 parse_url 에서 scheme와 host 값을 가져올수 없는 취약점이 존재함
    if ( $is_redirect && !isset($p['host']) && urldecode($url) != $url ){
        $i = 0;
        while($i <= 3){
            $url = urldecode($url);
            if( urldecode($url) == $url ) break;
            $i++;
        }

        if( urldecode($url) == $url ){
            $p = @parse_url($url);
        } else {
            $is_host_check = true;
        }
    }

    // if(stripos($url, 'http:') !== false) {
    //     if(!isset($p['scheme']) || !$p['scheme'] || !isset($p['host']) || !$p['host'])
    //         alert('url 정보가 올바르지 않습니다.', $return_url);
    // }

    //php 5.6.29 이하 버전에서는 parse_url 버그가 존재함
    //php 7.0.1 ~ 7.0.5 버전에서는 parse_url 버그가 존재함
    if ( $is_redirect && (isset($p['host']) && $p['host']) ) {
        $bool_ch = false;
        foreach( array('user','host') as $key) {
            if ( isset( $p[ $key ] ) && strpbrk( $p[ $key ], ':/?#@' ) ) {
                $bool_ch = true;
            }
        }
        if( $bool_ch ){
            $regex = '/https?\:\/\/'.$host.'/i';
            if( ! preg_match($regex, $url) ){
                $is_host_check = true;
            }
        }
    }

    if ((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host']) || $is_host_check) {
        //if ($p['host'].(isset($p['port']) ? ':'.$p['port'] : '') != $_SERVER['HTTP_HOST']) {
        if (run_replace('check_same_url_host', (($p['host'] != $host) || $is_host_check), $p, $host, $is_host_check, $return_url, $is_redirect)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("url에 타 도메인을 지정할 수 없습니다.");'.PHP_EOL;
            echo 'document.location.href = "'.$return_url.'";'.PHP_EOL;
            echo '</script>'.PHP_EOL;
            echo '<noscript>'.PHP_EOL;
            echo '<p>'.$msg.'</p>'.PHP_EOL;
            echo '<p><a href="'.$return_url.'">돌아가기</a></p>'.PHP_EOL;
            echo '</noscript>'.PHP_EOL;
            exit;
        }
    }
}

function get_params_merge_url($params, $url=''){
    $str_url = $url ? $url : G5_URL;
    $p = @parse_url($str_url);
    $href = (isset($p['scheme']) ? "{$p['scheme']}://" : '')
        . (isset($p['user']) ? $p['user']
        . (isset($p['pass']) ? ":{$p['pass']}" : '').'@' : '')
        . (isset($p['host']) ? $p['host'] : '')
        . ((isset($p['path']) && $url) ? $p['path'] : '')
        . ((isset($p['port']) && $p['port']) ? ":{$p['port']}" : '');
    
    $ori_params = '';
    if( $url ){
        $ori_params = !empty($p['query']) ? $p['query'] : '';
    } else if( $tmp = explode('?', $_SERVER['REQUEST_URI']) ){
        if( isset($tmp[0]) && $tmp[0] ) {
            $href .= $tmp[0];
            $ori_params = isset($tmp[1]) ? $tmp[1] : '';
        }
        if( $freg = strstr($ori_params, '#') ) {
            $p['fragment'] = preg_replace('/^#/', '', $freg);
        }
    }
    
    $q = array();
    if( $ori_params ){
        parse_str( $ori_params, $q );
    }
    
    if( is_array($params) && $params ){
        $q = array_merge($q, $params);
    }

    $query = http_build_query($q, '', '&amp;');
    $qc = (strpos( $href, '?' ) !== false) ? '&amp;' : '?';
    $href .= $qc.$query.(isset($p['fragment']) ? "#{$p['fragment']}" : '');

    return $href;
}

// 발신번호 유효성 체크
function check_vaild_callback($callback){
   $_callback = preg_replace('/[^0-9]/','', $callback);

   /**
   * 1588 로시작하면 총8자리인데 7자리라 차단
   * 02 로시작하면 총9자리 또는 10자리인데 11자리라차단
   * 1366은 그자체가 원번호이기에 다른게 붙으면 차단
   * 030으로 시작하면 총10자리 또는 11자리인데 9자리라차단
   */

   if( substr($_callback,0,4) == '1588') if( strlen($_callback) != 8) return false;
   if( substr($_callback,0,2) == '02')   if( strlen($_callback) != 9  && strlen($_callback) != 10 ) return false;
   if( substr($_callback,0,3) == '030')  if( strlen($_callback) != 10 && strlen($_callback) != 11 ) return false;

   if( !preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/",$_callback) &&
       !preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/",$_callback) ){
             return false;
   } else if( preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/",$_callback )) {
             return false;
   } else {
             return true;
   }
}

function is_use_email_certify(){
    global $config;

    return $config['cf_use_email_certify'];
}

function safe_replace_regex($str, $str_case=''){

    if($str_case === 'time'){
        return preg_replace('/[^0-9 _\-:]/i', '', $str);
    }

    return preg_replace('/[^0-9a-z_\-]/i', '', $str);
}

function get_real_client_ip() {
    
    return run_replace('get_real_client_ip', $_SERVER['REMOTE_ADDR']);
}

function check_mail_bot($ip=''){

    //아이피를 체크하여 메일 크롤링을 방지합니다.
    $check_ips = array('211.249.40.');
    $bot_message = 'bot 으로 판단되어 중지합니다.';
    
    if($ip){
        foreach( $check_ips as $c_ip ){
            if( preg_match('/^'.preg_quote($c_ip).'/', $ip) ) {
                die($bot_message);
            }
        }
    }

    // user agent를 체크하여 메일 크롤링을 방지합니다.
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    if ($user_agent === 'Carbon' || strpos($user_agent, 'BingPreview') !== false || strpos($user_agent, 'Slackbot') !== false) { 
        die($bot_message);
    } 
}

function get_call_func_cache($func, $args=array()){
    
    static $cache = array();

    $key = md5(serialize($args));

    if( isset($cache[$func]) && isset($cache[$func][$key]) ){
        return $cache[$func][$key];
    }

    $result = null;

    try{
        $cache[$func][$key] = $result = call_user_func_array($func, $args);
    } catch (Exception $e) {
        return null;
    }
    
    return $result;
}

// include 하는 경로에 data file 경로나 안전하지 않은 경로가 있는지 체크합니다.
function is_include_path_check($path='', $is_input='')
{
    if( $path ){

        if( strlen($path) > 255 ){
            return false;
        }

        if ($is_input){
            // 장태진 @jtjisgod <jtjisgod@gmail.com> 추가
            // 보안 목적 : rar wrapper 차단

            if( stripos($path, 'rar:') !== false || stripos($path, 'php:') !== false || stripos($path, 'zlib:') !== false || stripos($path, 'bzip2:') !== false || stripos($path, 'zip:') !== false || stripos($path, 'data:') !== false || stripos($path, 'phar:') !== false || stripos($path, 'file:') !== false || stripos($path, '://') !== false ){
                return false;
            }

            $replace_path = str_replace('\\', '/', $path);
            $slash_count = substr_count(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']), '/');
            $peer_count = substr_count($replace_path, '../');

            if ( $peer_count && $peer_count > $slash_count ){
                return false;
            }
            
            $dirname_doc_root = !empty($_SERVER['DOCUMENT_ROOT']) ? dirname($_SERVER['DOCUMENT_ROOT']) : dirname(dirname(dirname(__DIR__)));
            
            // 웹서버 폴더만 허용
            if ($dirname_doc_root && file_exists($path) && strpos(realpath($path), realpath($dirname_doc_root)) !== 0) {
                return false;
            }
            
            try {
                // whether $path is unix or not
                $unipath = strlen($path)==0 || substr($path, 0, 1) != '/';
                $unc = substr($path,0,2)=='\\\\'?true:false;
                // attempts to detect if path is relative in which case, add cwd
                if(strpos($path,':') === false && $unipath && !$unc){
                    $path=getcwd().DIRECTORY_SEPARATOR.$path;
                    if(substr($path, 0, 1) == '/'){
                        $unipath = false;
                    }
                }

                // resolve path parts (single dot, double dot and double delimiters)
                $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
                $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
                $absolutes = array();
                foreach ($parts as $part) {
                    if ('.'  == $part){
                        continue;
                    }
                    if ('..' == $part) {
                        array_pop($absolutes);
                    } else {
                        $absolutes[] = $part;
                    }
                }
                $path = implode(DIRECTORY_SEPARATOR, $absolutes);
                // resolve any symlinks
                // put initial separator that could have been lost
                $path = !$unipath ? '/'.$path : $path;
                $path = $unc ? '\\\\'.$path : $path;
            } catch (Exception $e) {
                //echo 'Caught exception: ',  $e->getMessage(), "\n";
                return false;
            }
            
            if (preg_match('/\/data\/(file|editor|cache|member|member_image|session|tmp)\/[A-Za-z0-9_]{1,20}\//i', $replace_path) || preg_match('/pe(?:ar|cl)(?:cmd)?\.php/i', $replace_path)){
                return false;
            }
            if( preg_match('/'.G5_PLUGIN_DIR.'\//i', $replace_path) && preg_match('/'.G5_KCPCERT_DIR.'\//i', $replace_path) || (preg_match('/search\.skin\.php/i', $replace_path) ) ){
                return false;
            }
            if( substr_count($replace_path, './') > 5 ){
                return false;
            }
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if($extension && preg_match('/(jpg|jpeg|png|gif|bmp|conf|php\-x)$/i', $extension)) {
            return false;
        }
    }

    return true;
}

function is_inicis_url_return($url){
    $url_data = parse_url($url);

    // KG 이니시스 url이 맞는지 체크하여 맞으면 url을 리턴하고 틀리면 '' 빈값을 리턴합니다.
    if (isset($url_data['host']) && preg_match('#\.inicis\.com$#i', $url_data['host'])) {
        return $url;
    }
    return '';
}

function filter_input_include_path($path){
    return str_replace('//', '/', strip_tags($path));
}
