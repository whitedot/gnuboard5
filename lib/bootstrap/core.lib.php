<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if (defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
        $pattern = G5_ESCAPE_PATTERN;
        $replace = G5_ESCAPE_REPLACE;

        if ($pattern) {
            $str = preg_replace($pattern, $replace, $str);
        }
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

function g5_include_bootstrap_libraries()
{
    include_once(G5_LIB_PATH . '/hook.lib.php');
    include_once(G5_LIB_PATH . '/get_data.lib.php');
    include_once(G5_LIB_PATH . '/cache.lib.php');
    include_once(G5_LIB_PATH . '/uri.lib.php');
}

function g5_include_core_libraries()
{
    include_once(G5_LIB_PATH . '/common.lib.php');
    include_once(G5_LIB_PATH . '/domain/member/render.lib.php');
    include_once(G5_LIB_PATH . '/domain/member/request.lib.php');
    include_once(G5_LIB_PATH . '/domain/member/validation.lib.php');
    include_once(G5_LIB_PATH . '/domain/member/persist.lib.php');
    include_once(G5_LIB_PATH . '/domain/member/flow.lib.php');
    include_once(G5_LIB_PATH . '/domain/member/page.lib.php');
}

function g5_render_install_required()
{
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>오류! <?php echo G5_VERSION ?> 설치하기</title>
<link rel="stylesheet" href="install/install.css">
</head>
<body>

<div id="ins_bar">
    <span id="bar_img">GNUBOARD5</span>
    <span id="bar_txt">Message</span>
</div>
<h1>그누보드5를 먼저 설치해주십시오.</h1>
<div class="ins_inner">
    <p>다음 파일을 찾을 수 없습니다.</p>
    <ul>
        <li><strong><?php echo G5_DATA_DIR . '/' . G5_DBCONFIG_FILE ?></strong></li>
    </ul>
    <p>그누보드 설치 후 다시 실행하시기 바랍니다.</p>
    <div class="inner_btn">
        <a href="<?php echo G5_URL; ?>/install/"><?php echo G5_VERSION ?> 설치하기</a>
    </div>
</div>
<div id="ins_ft">
    <strong>GNUBOARD5</strong>
    <p>GPL! OPEN SOURCE GNUBOARD</p>
</div>

</body>
</html>

<?php
    exit;
}

function g5_bootstrap_database()
{
    global $g5;

    $dbconfig_file = G5_DATA_PATH . '/' . G5_DBCONFIG_FILE;
    if (!file_exists($dbconfig_file)) {
        g5_render_install_required();
    }

    include_once($dbconfig_file);
    g5_include_core_libraries();

    $connect_db = sql_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db = sql_select_db(G5_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    $g5['connect_db'] = $connect_db;

    sql_set_charset(G5_DB_CHARSET, $connect_db);
    if (defined('G5_MYSQL_SET_MODE') && G5_MYSQL_SET_MODE) {
        sql_reset_session_sql_mode($connect_db);
    }
    if (defined('G5_TIMEZONE')) {
        sql_set_time_zone(G5_TIMEZONE);
    }

    return $select_db;
}
