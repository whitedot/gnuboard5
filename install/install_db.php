<?php
@set_time_limit(0);
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0
@header('Content-Type: text/html; charset=utf-8');
@header('X-Robots-Tag: noindex');

$g5_path['path'] = '..';
include_once('install_common.php');
include_once('../config.php');
include_once('../lib/common.lib.php');
include_once('./install.function.php');    // 인스톨 과정 함수 모음

include_once('../lib/hook.lib.php');    // hook 함수 파일
include_once('../lib/get_data.lib.php');
include_once('../lib/uri.lib.php');    // URL 함수 파일
include_once('../lib/cache.lib.php');

$title = G5_VERSION." 설치 완료 3/3";
include_once('./install.inc.php');

$mysql_host  = isset($_POST['mysql_host']) ? safe_install_string_check($_POST['mysql_host']) : '';
$mysql_user  = isset($_POST['mysql_user']) ? safe_install_string_check($_POST['mysql_user']) : '';
$mysql_pass  = isset($_POST['mysql_pass']) ? safe_install_string_check($_POST['mysql_pass']) : '';
$mysql_db    = isset($_POST['mysql_db']) ? safe_install_string_check($_POST['mysql_db']) : '';
$table_prefix= isset($_POST['table_prefix']) ? safe_install_string_check($_POST['table_prefix']) : '';
$admin_id    = isset($_POST['admin_id']) ? $_POST['admin_id'] : '';
$admin_pass  = isset($_POST['admin_pass']) ? $_POST['admin_pass'] : '';
$admin_name  = isset($_POST['admin_name']) ? $_POST['admin_name'] : '';
$admin_email = isset($_POST['admin_email']) ? $_POST['admin_email'] : '';

if (preg_match("/[^0-9a-z_]+/i", $table_prefix) ) {
    die('<div class="ins_inner"><p>TABLE명 접두사는 영문자, 숫자, _ 만 입력하세요.</p><div class="inner_btn"><a href="./install_config.php">뒤로가기</a></div></div>');
}

if (preg_match("/[^0-9a-z_]+/i", $admin_id)) {
    die('<div class="ins_inner"><p>관리자 아이디는 영문자, 숫자, _ 만 입력하세요.</p><div class="inner_btn"><a href="./install_config.php">뒤로가기</a></div></div>');
}

$g5_install = isset($_POST['g5_install']) ? (int) $_POST['g5_install'] : 0;
$dblink = sql_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
if (!$dblink) {
?>

<div class="ins_inner">
    <p>MySQL Host, User, Password 를 확인해 주십시오.</p>
    <div class="inner_btn"><a href="./install_config.php">뒤로가기</a></div>
</div>

<?php
    include_once('./install.inc2.php');
    exit;
}

$g5['connect_db'] = $dblink;
$select_db = sql_select_db($mysql_db, $dblink);
if (!$select_db) {
?>

<div class="ins_inner">
    <p>MySQL DB 를 확인해 주십시오.</p>
    <div class="inner_btn"><a href="./install_config.php">뒤로가기</a></div>
</div>

<?php
    include_once('./install.inc2.php');
    exit;
}

$mysql_set_mode = 'false';
sql_set_charset(G5_DB_CHARSET, $dblink);
$result = sql_query(" SELECT @@sql_mode as mode ", true, $dblink);
$row = sql_fetch_array($result);
if($row['mode']) {
    sql_query("SET SESSION sql_mode = ''", true, $dblink);
    $mysql_set_mode = 'true';
}
unset($result);
unset($row);
?>

<div class="ins_inner">
    <h2><?php echo G5_VERSION ?> 설치가 시작되었습니다.</h2>

    <ol>
<?php
$sql = "SHOW TABLES LIKE '{$table_prefix}config'";
$is_install = sql_query($sql, false, $dblink)->num_rows > 0;

// 그누보드5 재설치에 체크하였거나 그누보드5가 설치되어 있지 않다면
if ($g5_install || $is_install === false) {
    // 테이블 생성 ------------------------------------
    $file = implode('', file('./gnuboard5.sql'));
    eval("\$file = \"$file\";");

    $file = preg_replace('/^--.*$/m', '', $file);
    $file = preg_replace('/`g5_([^`]+`)/', '`'.$table_prefix.'$1', $file);
    $f = explode(';', $file);
    for ($i=0; $i<count($f); $i++) {
        if (trim($f[$i]) == '') {
            continue;
        }

        $sql = get_db_create_replace($f[$i]);
        sql_query($sql, true, $dblink);
    }
}

// 테이블 생성 ------------------------------------
?>

        <li>전체 테이블 생성 완료</li>

<?php
$read_point = 0;
$write_point = 0;
$comment_point = 0;
$download_point = 0;

//-------------------------------------------------------------------------------------------------
// config 테이블 설정
if ($g5_install || $is_install === false) {
    // 기본 이미지 확장자를 설정하고
    $image_extension = "gif|jpg|jpeg|png";
    // 서버에서 webp 를 지원하면 확장자를 추가한다.
    if (function_exists("imagewebp")) {
        $image_extension .= "|webp";
    }

    $sql = " insert into `{$table_prefix}config`
                set cf_title = '".G5_VERSION."',
                    cf_theme = 'basic',
                    cf_admin = '$admin_id',
                    cf_admin_email = '$admin_email',
                    cf_admin_email_name = '".G5_VERSION.'_'.substr(base_convert(mt_rand(), 10, 36), 0, 6)."',
                    cf_use_point = '0',
                    cf_use_copy_log = '1',
                    cf_login_point = '0',
                    cf_memo_send_point = '0',
                    cf_cut_name = '15',
                    cf_nick_modify = '60',
                    cf_new_rows = '15',
                    cf_read_point = '$read_point',
                    cf_write_point = '$write_point',
                    cf_comment_point = '$comment_point',
                    cf_download_point = '$download_point',
                    cf_write_pages = '10',
                    cf_link_target = '_blank',
                    cf_delay_sec = '30',
                    cf_filter = '18아,18놈,18새끼,18뇬,18노,18것,18넘,개년,개놈,개뇬,개새,개색끼,개세끼,개세이,개쉐이,개쉑,개쉽,개시키,개자식,개좆,게색기,게색끼,광뇬,뇬,눈깔,뉘미럴,니귀미,니기미,니미,도촬,되질래,뒈져라,뒈진다,디져라,디진다,디질래,병쉰,병신,뻐큐,뻑큐,뽁큐,삐리넷,새꺄,쉬발,쉬밸,쉬팔,쉽알,스패킹,스팽,시벌,시부랄,시부럴,시부리,시불,시브랄,시팍,시팔,시펄,실밸,십8,십쌔,십창,싶알,쌉년,썅놈,쌔끼,쌩쑈,썅,써벌,썩을년,쎄꺄,쎄엑,쓰바,쓰발,쓰벌,쓰팔,씨8,씨댕,씨바,씨발,씨뱅,씨봉알,씨부랄,씨부럴,씨부렁,씨부리,씨불,씨브랄,씨빠,씨빨,씨뽀랄,씨팍,씨팔,씨펄,씹,아가리,아갈이,엄창,접년,잡놈,재랄,저주글,조까,조빠,조쟁이,조지냐,조진다,조질래,존나,존니,좀물,좁년,좃,좆,좇,쥐랄,쥐롤,쥬디,지랄,지럴,지롤,지미랄,쫍빱,凸,퍽큐,뻑큐,빠큐,ㅅㅂㄹㅁ',
                    cf_possible_ip = '',
                    cf_intercept_ip = '',
                    cf_member_skin = 'theme/basic',
                    cf_editor = 'smarteditor2',
                    cf_captcha_mp3 = 'basic',
                    cf_register_level = '2',
                    cf_register_point = '0',
                    cf_icon_level = '2',
                    cf_leave_day = '30',
                    cf_search_part = '10000',
                    cf_email_use = '1',
                    cf_prohibit_id = 'admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객',
                    cf_prohibit_email = '',
                    cf_new_del = '30',
                    cf_memo_del = '180',
                    cf_visit_del = '180',
                    cf_popular_del = '180',
                    cf_use_member_icon = '2',
                    cf_member_icon_size = '5000',
                    cf_member_icon_width = '22',
                    cf_member_icon_height = '22',
                    cf_member_img_size = '50000',
                    cf_member_img_width = '60',
                    cf_member_img_height = '60',
                    cf_login_minutes = '10',
                    cf_image_extension = '{$image_extension}',
                    cf_flash_extension = 'swf',
                    cf_movie_extension = 'asx|asf|wmv|wma|mpg|mpeg|mov|avi|mp3',
                    cf_formmail_is_member = '1',
                    cf_page_rows = '15',
                    cf_cert_limit = '2',
                    cf_stipulation = '해당 홈페이지에 맞는 회원가입약관을 입력합니다.',
                    cf_privacy = '해당 홈페이지에 맞는 개인정보처리방침을 입력합니다.'
                    ";
    sql_query($sql, true, $dblink);

    // 관리자 회원가입
    $sql = " insert into `{$table_prefix}member`
                set mb_id = '$admin_id',
                     mb_password = '".get_encrypt_string($admin_pass)."',
                     mb_name = '$admin_name',
                     mb_nick = '$admin_name',
                     mb_email = '$admin_email',
                     mb_level = '10',
                     mb_mailling = '1',
                     mb_open = '1',
                     mb_nick_date = '".G5_TIME_YMDHIS."',
                     mb_email_certify = '".G5_TIME_YMDHIS."',
                     mb_datetime = '".G5_TIME_YMDHIS."',
                     mb_ip = '{$_SERVER['REMOTE_ADDR']}'
                     ";
    sql_query($sql, true, $dblink);

    // 내용관리 생성
    sql_query(" insert into `{$table_prefix}content` set co_id = 'company', co_html = '1', co_subject = '회사소개', co_content= '<p align=center><b>회사소개에 대한 내용을 입력하십시오.</b></p>', co_skin = 'theme/basic' ", true, $dblink);
    sql_query(" insert into `{$table_prefix}content` set co_id = 'privacy', co_html = '1', co_subject = '개인정보 처리방침', co_content= '<p align=center><b>개인정보 처리방침에 대한 내용을 입력하십시오.</b></p>', co_skin = 'theme/basic' ", true, $dblink);
    sql_query(" insert into `{$table_prefix}content` set co_id = 'provision', co_html = '1', co_subject = '서비스 이용약관', co_content= '<p align=center><b>서비스 이용약관에 대한 내용을 입력하십시오.</b></p>', co_skin = 'theme/basic' ", true, $dblink);

}

?>

        <li>DB설정 완료</li>

<?php
//-------------------------------------------------------------------------------------------------

// 디렉토리 생성
$dir_arr = array (
    $data_path.'/cache',
    $data_path.'/editor',
    $data_path.'/file',
    $data_path.'/log',
    $data_path.'/member',
    $data_path.'/member_image',
    $data_path.'/session',
    $data_path.'/content',
    $data_path.'/tmp'
);

for ($i=0; $i<count($dir_arr); $i++) {
    @mkdir($dir_arr[$i], G5_DIR_PERMISSION);
    @chmod($dir_arr[$i], G5_DIR_PERMISSION);
}

?>

        <li>데이터 디렉토리 생성 완료</li>

<?php
//-------------------------------------------------------------------------------------------------

// DB 설정 파일 생성
$file = '../'.G5_DATA_DIR.'/'.G5_DBCONFIG_FILE;
$f = @fopen($file, 'a');

fwrite($f, "<?php\n");
fwrite($f, "if (!defined('_GNUBOARD_')) exit;\n");
fwrite($f, "define('G5_MYSQL_HOST', '".addcslashes($mysql_host, "\\'")."');\n");
fwrite($f, "define('G5_MYSQL_USER', '".addcslashes($mysql_user, "\\'")."');\n");
fwrite($f, "define('G5_MYSQL_PASSWORD', '".addcslashes($mysql_pass, "\\'")."');\n");
fwrite($f, "define('G5_MYSQL_DB', '".addcslashes($mysql_db, "\\'")."');\n");
fwrite($f, "define('G5_MYSQL_SET_MODE', {$mysql_set_mode});\n\n");
fwrite($f, "define('G5_TABLE_PREFIX', '{$table_prefix}');\n\n");
fwrite($f, "define('G5_TOKEN_ENCRYPTION_KEY', '".get_random_token_string(16)."'); // 토큰 암호화에 사용할 키\n\n");
fwrite($f, "\$g5['auth_table'] = G5_TABLE_PREFIX.'auth'; // 관리권한 설정 테이블\n");
fwrite($f, "\$g5['config_table'] = G5_TABLE_PREFIX.'config'; // 기본환경 설정 테이블\n");
fwrite($f, "\$g5['login_table'] = G5_TABLE_PREFIX.'login'; // 로그인 테이블 (접속자수)\n");
fwrite($f, "\$g5['mail_table'] = G5_TABLE_PREFIX.'mail'; // 회원메일 테이블\n");
fwrite($f, "\$g5['member_table'] = G5_TABLE_PREFIX.'member'; // 회원 테이블\n");
fwrite($f, "\$g5['memo_table'] = G5_TABLE_PREFIX.'memo'; // 메모 테이블\n");
fwrite($f, "\$g5['point_table'] = G5_TABLE_PREFIX.'point'; // 포인트 테이블\n");
fwrite($f, "\$g5['visit_table'] = G5_TABLE_PREFIX.'visit'; // 방문자 테이블\n");
fwrite($f, "\$g5['visit_sum_table'] = G5_TABLE_PREFIX.'visit_sum'; // 방문자 합계 테이블\n");
fwrite($f, "\$g5['uniqid_table'] = G5_TABLE_PREFIX.'uniqid'; // 유니크한 값을 만드는 테이블\n");
fwrite($f, "\$g5['cert_history_table'] = G5_TABLE_PREFIX.'cert_history'; // 인증내역 테이블\n");
fwrite($f, "\$g5['content_table'] = G5_TABLE_PREFIX.'content'; // 내용(컨텐츠)정보 테이블\n");
fwrite($f, "\$g5['new_win_table'] = G5_TABLE_PREFIX.'new_win'; // 새창 테이블\n");
fwrite($f, "\$g5['menu_table'] = G5_TABLE_PREFIX.'menu'; // 메뉴관리 테이블\n");
fwrite($f, "\$g5['social_profile_table'] = G5_TABLE_PREFIX.'member_social_profiles'; // 소셜 로그인 테이블\n");
fwrite($f, "\$g5['member_cert_history_table'] = G5_TABLE_PREFIX.'member_cert_history'; // 본인인증 변경내역 테이블\n");

fwrite($f, "?>");

fclose($f);
@chmod($file, G5_FILE_PERMISSION);
?>

        <li>DB설정 파일 생성 완료 (<?php echo $file ?>)</li>

<?php
// data 디렉토리 및 하위 디렉토리에서는 .htaccess .htpasswd .php .phtml .html .htm .inc .cgi .pl .phar 파일을 실행할수 없게함.
$f = fopen($data_path.'/.htaccess', 'w');
$str = <<<EOD
<FilesMatch "\.(htaccess|htpasswd|[Pp][Hh][Pp]|[Pp][Hh][Tt]|[Pp]?[Hh][Tt][Mm][Ll]?|[Ii][Nn][Cc]|[Cc][Gg][Ii]|[Pp][Ll]|[Pp][Hh][Aa][Rr])">
Order allow,deny
Deny from all
</FilesMatch>
RedirectMatch 403 /session/.*
EOD;
fwrite($f, $str);
fclose($f);

//-------------------------------------------------------------------------------------------------
?>
    </ol>

    <p>축하합니다. <?php echo G5_VERSION ?> 설치가 완료되었습니다.</p>

</div>

<div class="ins_inner">

    <h2>환경설정 변경은 다음의 과정을 따르십시오.</h2>

    <ol>
        <li>메인화면으로 이동</li>
        <li>관리자 로그인</li>
        <li>관리자 모드 접속</li>
        <li>환경설정 메뉴의 기본환경설정 페이지로 이동</li>
    </ol>

    <div class="inner_btn">
        <a href="../index.php">새로운 그누보드5로 이동</a>
    </div>

</div>

<?php
include_once ('./install.inc2.php');
