<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
} else {
    $g5_head_title = implode(' | ', array_filter(array($g5['title'], $config['cf_title'])));
}

$g5['title'] = strip_tags($g5['title']);
$g5_head_title = strip_tags($g5_head_title);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
if (G5_IS_MOBILE) {
    echo '<meta name="viewport" id="meta_viewport" content="width=device-width,initial-scale=1.0">'.PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
}
?>
<title><?php echo $g5_head_title; ?></title>
<?php
$pretendard_font_href = 'https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css';
echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>'.PHP_EOL;
echo '<link rel="preload" as="style" href="'.$pretendard_font_href.'" crossorigin>'.PHP_EOL;
echo '<link rel="stylesheet" href="'.$pretendard_font_href.'" crossorigin>'.PHP_EOL;

$common_css_path = G5_CSS_PATH.'/common.css';
$common_css_ver = is_file($common_css_path) ? filemtime($common_css_path) : G5_CSS_VER;
echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_CSS_URL.'/common.css?ver='.$common_css_ver, G5_URL).'">'.PHP_EOL;

$theme_css_path = G5_CSS_PATH.'/theme.css';
$theme_css_ver = is_file($theme_css_path) ? filemtime($theme_css_path) : G5_CSS_VER;
echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_CSS_URL.'/theme.css?ver='.$theme_css_ver, G5_URL).'">'.PHP_EOL;
?>
<script>
var g5_url = "<?php echo G5_URL ?>";
var g5_member_url = "<?php echo G5_MEMBER_URL ?>";
var g5_is_member = "<?php echo isset($is_member) ? $is_member : ''; ?>";
var g5_is_admin = "<?php echo isset($is_admin) ? $is_admin : ''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_sca = "<?php echo isset($sca) ? $sca : ''; ?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
</script>
<?php
add_javascript('<script src="'.G5_JS_URL.'/jquery-1.12.4.min.js"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/jquery-migrate-1.4.1.min.js"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/common.js?ver='.G5_JS_VER.'"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/ui-kit/ui-overlay.js?ver='.G5_JS_VER.'"></script>', 1);
add_javascript('<script src="'.G5_JS_URL.'/ui-kit/ui-dropdown-menu.js?ver='.G5_JS_VER.'"></script>', 1);
add_javascript('<script src="'.G5_JS_URL.'/wrest.js?ver='.G5_JS_VER.'"></script>', 0);
?>
</head>
<body<?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
<?php
if ($is_member) {
    $sr_admin_msg = '';
    if ($is_admin == 'super') {
        $sr_admin_msg = '최고관리자 ';
    } else if ($is_admin == 'group') {
        $sr_admin_msg = '그룹관리자 ';
    }
    echo '<div class="sr-only">'.$sr_admin_msg.get_text($member['mb_nick']).'님 로그인 중 ';
    echo '<a href="'.G5_MEMBER_URL.'/logout.php">로그아웃</a></div>';
}
