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
'bo_mobile_subject'=>'',
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
'bo_mobile_content_head'=>'',
'bo_mobile_content_tail'=>'',
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
    $sound_only = '<strong class="sound_only">필수</strong>';

    $board['bo_count_delete'] = 1;
    $board['bo_count_modify'] = 1;
    $board['bo_read_point'] = $config['cf_read_point'];
    $board['bo_write_point'] = $config['cf_write_point'];
    $board['bo_comment_point'] = $config['cf_comment_point'];
    $board['bo_download_point'] = $config['cf_download_point'];

    $board['bo_gallery_cols'] = 4;
    $board['bo_gallery_width'] = 202;
    $board['bo_gallery_height'] = 150;
    $board['bo_mobile_gallery_width'] = 125;
    $board['bo_mobile_gallery_height'] = 100;
    $board['bo_table_width'] = 100;
    $board['bo_page_rows'] = $config['cf_page_rows'];
    $board['bo_mobile_page_rows'] = $config['cf_page_rows'];
    $board['bo_subject_len'] = 60;
    $board['bo_mobile_subject_len'] = 30;
    $board['bo_new'] = 24;
    $board['bo_hot'] = 100;
    $board['bo_image_width'] = 600;
    $board['bo_upload_count'] = 2;
    $board['bo_upload_size'] = 1048576;
    $board['bo_reply_order'] = 1;
    $board['bo_use_search'] = 1;
    $board['bo_skin'] = 'basic';
    $board['bo_mobile_skin'] = 'basic';
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
require_once './admin.head.php';

$pg_anchor = '<ul class="anchor">
    <li><a href="#anc_bo_basic">기본 설정</a></li>
    <li><a href="#anc_bo_auth">권한 설정</a></li>
    <li><a href="#anc_bo_function">기능 설정</a></li>
    <li><a href="#anc_bo_design">디자인/양식</a></li>
    <li><a href="#anc_bo_point">포인트 설정</a></li>
    <li><a href="#anc_bo_extra">여분필드</a></li>
</ul>';

?>

<form name="fboardform" id="fboardform" action="./board_form_update.php" onsubmit="return fboardform_submit(this)" method="post" enctype="multipart/form-data">
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

// 여분필드 설정
include_once G5_ADMIN_PATH.'/board_form_parts/extra.php';
?>

<div class="btn_fixed_top">
    <?php if ($bo_table && $w) { ?>
        <a href="./board_copy.php?bo_table=<?php echo $board['bo_table']; ?>" id="board_copy" target="win_board_copy" class=" btn_02 btn">게시판복사</a>
        <a href="<?php echo get_pretty_url($board['bo_table']); ?>" class=" btn_02 btn">게시판 바로가기</a>
        <a href="./board_thumbnail_delete.php?bo_table=<?php echo $board['bo_table'].'&amp;'.$qstr;?>" onclick="return delete_confirm2('게시판 썸네일 파일을 삭제하시겠습니까?');" class="btn_02 btn">게시판 썸네일 삭제</a>
    <?php } ?>
    <input type="submit" value="확인" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>

<?php
// 자바스크립트
include_once G5_ADMIN_PATH.'/board_form_parts/script.php';

require_once './admin.tail.php';
?>