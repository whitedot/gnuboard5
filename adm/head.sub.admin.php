<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

$head_sub_view = admin_build_head_sub_view($g5, $config, $is_member, $is_admin, $member);
$page_title = $head_sub_view['page_title'];
$head_title = $head_sub_view['head_title'];
$pretendard_font_href = $head_sub_view['pretendard_font_href'];
$common_css_ver = $head_sub_view['common_css_ver'];
$admin_css_ver = $head_sub_view['admin_css_ver'];
$sticky_anchor_tabs_ver = $head_sub_view['sticky_anchor_tabs_ver'];
$login_status_text = $head_sub_view['login_status_text'];
$g5['title'] = $page_title;
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
<title><?php echo $head_title; ?></title>
<?php
echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>'.PHP_EOL;
echo '<link rel="preload" as="style" href="'.$pretendard_font_href.'" crossorigin>'.PHP_EOL;
echo '<link rel="stylesheet" href="'.$pretendard_font_href.'" crossorigin>'.PHP_EOL;

echo '<link rel="stylesheet" href="'.run_replace('head_css_url', G5_CSS_URL.'/common.css?ver='.$common_css_ver, G5_URL).'">'.PHP_EOL;

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
    echo '<div class="sr-only">'.$login_status_text;
    echo '<a href="'.G5_MEMBER_URL.'/logout.php">로그아웃</a></div>';
}
