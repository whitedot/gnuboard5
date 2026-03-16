<?php
$sub_menu = "100280";
define('_THEME_PREVIEW_', true);
include_once('./_common.php');

$theme_dir = get_theme_dir();

if (!$theme || !in_array($theme, $theme_dir)) {
    alert_close('테마가 존재하지 않거나 올바르지 않습니다.');
}

$info = get_theme_info($theme);
$mode = 'index';
$qstr_index = '&amp;mode=index';
$qstr_device = '&amp;mode='.$mode.'&amp;device='.(G5_IS_MOBILE ? 'pc' : 'mobile');

$conf = sql_fetch(" select cf_theme from {$g5['config_table']} ");
$name = get_text($info['theme_name']);
if ($conf['cf_theme'] != $theme) {
    $tconfig = get_theme_config_value($theme);
    $set_default_skin = !empty($tconfig['set_default_skin']) ? 'true' : 'false';
    $btn_active = '<li><button type="button" data-theme="'.$theme.'" data-name="'.$name.'" data-set_default_skin="'.$set_default_skin.'">테마적용</button></li>';
} else {
    $btn_active = '';
}

$g5['title'] = get_text($info['theme_name']).' 테마 미리보기';
require_once(G5_ADMIN_PATH.'/head.sub.admin.php');
?>

<link rel="stylesheet" href="<?php echo G5_ADMIN_URL; ?>/css/theme.css">
<script src="<?php echo G5_ADMIN_URL; ?>/theme.js"></script>

<section id="preview_item">
    <ul>
        <li><a href="./theme_preview.php?theme=<?php echo $theme.$qstr_index; ?>">인덱스 화면</a></li>
        <li><a href="./theme_preview.php?theme=<?php echo $theme.$qstr_device; ?>"><?php echo (G5_IS_MOBILE ? 'PC 버전' : '모바일 버전'); ?></a></li>
        <?php echo $btn_active; ?>
    </ul>
</section>

<section id="preview_content">
    <?php include(G5_PATH.'/index.php'); ?>
</section>

<?php
require_once(G5_PATH.'/tail.sub.php');
