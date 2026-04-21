<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
}
else {
    // 상태바에 표시될 제목
    $g5_head_title = implode(' | ', array_filter(array($g5['title'], $config['cf_title'])));
}

$g5['title'] = strip_tags($g5['title']);
$g5_head_title = strip_tags($g5_head_title);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<script>
(function () {
    var key = 'g5_admin_theme';
    var saved = null;
    try {
        saved = localStorage.getItem(key);
    } catch (e) {
        saved = null;
    }
    var dark = saved === 'dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
})();
</script>
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

$common_css_path = G5_PATH.'/css/common.css';
$common_css_ver = is_file($common_css_path) ? filemtime($common_css_path) : G5_CSS_VER;
echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_CSS_URL.'/common.css?ver='.$common_css_ver, G5_URL).'">'.PHP_EOL;

$admin_css_path = G5_ADMIN_PATH.'/css/admin.css';
$admin_css_ver = is_file($admin_css_path) ? filemtime($admin_css_path) : G5_CSS_VER;
$sticky_anchor_tabs_path = G5_PATH.'/js/ui-kit/ui-sticky-anchor-tabs.js';
$sticky_anchor_tabs_ver = is_file($sticky_anchor_tabs_path) ? filemtime($sticky_anchor_tabs_path) : G5_JS_VER;
echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_ADMIN_URL.'/css/admin.css?ver='.$admin_css_ver, G5_URL).'">'.PHP_EOL;
?>
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?php echo G5_URL ?>";
var g5_member_url = "<?php echo G5_MEMBER_URL ?>";
var g5_is_member = "<?php echo isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?php echo isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_sca       = "<?php echo isset($sca)?$sca:''; ?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
var g5_admin_url = "<?php echo G5_ADMIN_URL; ?>";
</script>
<?php
add_javascript('<script src="'.G5_JS_URL.'/common.js?ver='.G5_JS_VER.'"></script>', 0);
add_javascript('<script src="'.G5_JS_URL.'/ui-kit/ui-dropdown-menu.js?ver='.G5_JS_VER.'"></script>', 1);
add_javascript('<script src="'.G5_JS_URL.'/ui-kit/ui-sticky-anchor-tabs.js?ver='.$sticky_anchor_tabs_ver.'"></script>', 1);
add_javascript('<script src="'.G5_JS_URL.'/wrest.js?ver='.G5_JS_VER.'"></script>', 0);
?>
</head>
<body<?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
<?php
if ($is_member) { // 회원이라면 로그인 중이라는 메세지를 출력해준다.
    $sr_admin_msg = '';
    if ($is_admin == 'super') $sr_admin_msg = "최고관리자 ";
    echo '<div class="sr-only">'.$sr_admin_msg.get_text($member['mb_nick']).'님 로그인 중 ';
    echo '<a href="'.G5_MEMBER_URL.'/logout.php">로그아웃</a></div>';
}
