<?php
$sub_menu = "300100";
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, 'w');

$sql = " select count(*) as cnt from {$g5['group_table']} ";
$row = sql_fetch($sql);
if (!$row['cnt']) {
    alert('게시판그룹이 한개 이상 생성되어야 합니다.', './boardgroup_form.php');
}

$html_title = '게시판';

$board_default = array(
'bo_device'=>'',
'bo_use_category'=>0,
'bo_category_list'=>'',
'bo_admin'=>'',
'bo_list_level'=>0,
'bo_read_level'=>0,
'bo_write_level'=>0,
'bo_reply_level'=>0,
'bo_comment_level'=>0,
'bo_link_level'=>0,
'bo_upload_level'=>0,
'bo_download_level'=>0,
'bo_html_level'=>0,
'bo_use_sideview'=>0,
'bo_select_editor'=>'',
'bo_use_rss_view'=>0,
'bo_use_good'=>0,
'bo_use_nogood'=>0,
'bo_use_name'=>0,
'bo_use_signature'=>0,
'bo_use_ip_view'=>0,
'bo_use_list_content'=>0,
'bo_use_list_file'=>0,
'bo_use_list_view'=>0,
'bo_use_email'=>0,
'bo_use_file_content'=>0,
'bo_use_cert'=>'',
'bo_write_min'=>0,
'bo_write_min'=>0,
'bo_write_max'=>0,
'bo_comment_min'=>0,
'bo_comment_max'=>0,
'bo_use_sns'=>0,
'bo_order'=>0,
'bo_use_captcha'=>0,
'bo_content_head'=>'',
'bo_content_tail'=>'',
'bo_insert_content'=>'',
'bo_sort_field'=>'',
);

for ($i = 0; $i <= 10; $i++) {
    $board_default['bo_'.$i.'_subj'] = '';
    $board_default['bo_'.$i] = '';
}

$board = array_merge($board_default, $board);

run_event('adm_board_form_before', $board, $w);

$required = "";
$readonly = "";
$sound_only = "";
$required_valid = "";
if ($w == '') {
    $html_title .= ' 생성';

    $required = 'required';
    $required_valid = 'alnum_';
    $sound_only = '<strong>필수</strong>';

    $board['bo_count_delete'] = 1;
    $board['bo_count_modify'] = 1;
    $board['bo_read_point'] = $config['cf_read_point'];
    $board['bo_write_point'] = $config['cf_write_point'];
    $board['bo_comment_point'] = $config['cf_comment_point'];
    $board['bo_download_point'] = $config['cf_download_point'];

    $board['bo_gallery_cols'] = 4;
    $board['bo_gallery_width'] = 202;
    $board['bo_gallery_height'] = 150;
    $board['bo_table_width'] = 100;
    $board['bo_page_rows'] = $config['cf_page_rows'];
    $board['bo_subject_len'] = 60;
    $board['bo_new'] = 24;
    $board['bo_hot'] = 100;
    $board['bo_image_width'] = 600;
    $board['bo_upload_count'] = 2;
    $board['bo_upload_size'] = 1048576;
    $board['bo_reply_order'] = 1;
    $board['bo_use_search'] = 1;
    $board['bo_skin'] = 'theme/basic';
    $board['gr_id'] = $gr_id;
    $board['bo_use_secret'] = 0;
    $board['bo_include_head'] = '_head.php';
    $board['bo_include_tail'] = '_tail.php';
} elseif ($w == 'u') {
    $html_title .= ' 수정';

    if (!$board['bo_table']) {
        alert('존재하지 않은 게시판 입니다.');
    }

    if ($is_admin == 'group') {
        if ($member['mb_id'] != $group['gr_admin']) {
            alert('그룹이 틀립니다.');
        }
    }

    $readonly = 'readonly';
}

if ($is_admin != 'super') {
    $group = get_group($board['gr_id']);
    $is_admin = is_admin($member['mb_id']);
}

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-board-form';
$admin_page_subtitle = '게시판 기본, 권한, 기능, 디자인, 포인트 설정을 탭으로 빠르게 관리하세요.';
require_once './admin.head.php';

$board_tabs = array(
    array('id' => 'anc_bo_basic', 'label' => '기본 설정'),
    array('id' => 'anc_bo_auth', 'label' => '권한 설정'),
    array('id' => 'anc_bo_function', 'label' => '기능 설정'),
    array('id' => 'anc_bo_design', 'label' => '디자인/양식'),
    array('id' => 'anc_bo_point', 'label' => '포인트 설정'),
);

$pg_anchor_menu = admin_build_anchor_menu($board_tabs, array(
    'nav_id' => 'board_tabs_nav',
    'nav_class' => 'tab-nav',
    'nav_aria_label' => '게시판 설정 탭',
    'link_class' => 'tab-trigger-line-primary js-board-tab-link',
    'active_class' => 'active',
    'as_tabs' => true,
    'link_id_prefix' => 'board_tab_',
));

// 레거시 파트 템플릿 내 출력되는 상단 앵커는 사용하지 않음
$pg_anchor = '';

?>

<div id="board_tabs_bar">
    <?php echo $pg_anchor_menu; ?>
</div>

<form name="fboardform" id="fboardform" action="./board_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data" class="admin-form-layout space-y-5">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<?php
// 기본 설정
include_once G5_ADMIN_PATH.'/board_form_parts/basic.php';

// 권한 설정
include_once G5_ADMIN_PATH.'/board_form_parts/auth.php';

// 기능 설정
include_once G5_ADMIN_PATH.'/board_form_parts/function.php';

// 디자인/양식
include_once G5_ADMIN_PATH.'/board_form_parts/design.php';

// 포인트 설정
include_once G5_ADMIN_PATH.'/board_form_parts/point.php';
?>

<div class="flex items-center justify-between border-default-300 border-t border-dashed pt-4">
    <?php if ($bo_table && $w) { ?>
        <div class="af-inline">
            <a href="./board_copy.php?bo_table=<?php echo $board['bo_table']; ?>" id="board_copy" target="win_board_copy" class="btn btn-surface-default-soft">게시판복사</a>
            <a href="<?php echo get_pretty_url($board['bo_table']); ?>" class="btn btn-surface-default-soft">게시판 바로가기</a>
            <a href="./board_thumbnail_delete.php?bo_table=<?php echo $board['bo_table'].'&amp;'.$qstr;?>" onclick="return delete_confirm2('게시판 썸네일 파일을 삭제하시겠습니까?');" class="btn btn-surface-default-soft">게시판 썸네일 삭제</a>
        </div>
    <?php } else { ?>
        <div></div>
    <?php } ?>
    <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
</div>

</form>

<?php
// 자바스크립트
include_once G5_ADMIN_PATH.'/board_form_parts/script.php';

require_once './admin.tail.php';
?>
