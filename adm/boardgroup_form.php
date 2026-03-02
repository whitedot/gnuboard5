<?php
$sub_menu = "300200";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

if ($is_admin != 'super' && $w == '') {
    alert('최고관리자만 접근 가능합니다.');
}

$html_title = '게시판그룹';
$gr_id_attr = '';
$sound_only = '';

if (!isset($group['gr_id'])) {
    $group['gr_id'] = '';
    $group['gr_subject'] = '';
    $group['gr_device'] = '';
}

$gr = array('gr_use_access' => 0, 'gr_admin' => '');
if ($w == '') {
    $gr_id_attr = 'required';
    $sound_only = '<strong> 필수</strong>';
    $html_title .= ' 생성';
} elseif ($w == 'u') {
    $gr_id_attr = 'readonly';
    $gr = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id' ");
    $html_title .= ' 수정';
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

// 접근회원수
$sql1 = " select count(*) as cnt from {$g5['group_member_table']} where gr_id = '{$gr_id}' ";
$row1 = sql_fetch($sql1);
$group_member_count = $row1['cnt'];

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-boardgroup-form';
$admin_page_subtitle = '그룹 기본 정보와 접근 제어를 일관된 폼 레이아웃으로 관리하세요.';
require_once './admin.head.php';
?>

<form name="fboardgroup" id="fboardgroup" action="./boardgroup_form_update.php" onsubmit="return fboardgroup_check(this);" method="post" autocomplete="off" class="admin-form-layout space-y-5">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">게시판그룹 기본 설정</h2>
        </div>
        <div class="card-body">
            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="gr_id" class="form-label">그룹 ID<?php echo $sound_only ?></label>
                    </div>
                    <div class="af-field">
                        <div class="af-stack">
                            <input type="text" name="gr_id" value="<?php echo $group['gr_id'] ?>" id="gr_id" <?php echo $gr_id_attr; ?> class="form-input <?php echo $w == '' ? 'required' : ''; ?>" maxlength="10">
                            <?php if ($w == '') { ?>
                                <p class="hint-text">영문자, 숫자, _ 만 가능 (공백없이)</p>
                            <?php } else { ?>
                                <div>
                                    <a href="<?php echo G5_BBS_URL . '/group.php?gr_id=' . $group['gr_id']; ?>" class="btn btn-sm btn-surface-default-soft">게시판그룹 바로가기</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="gr_subject" class="form-label">그룹 제목<strong> 필수</strong></label>
                    </div>
                    <div class="af-field">
                        <div class="af-stack">
                            <input type="text" name="gr_subject" value="<?php echo get_text($group['gr_subject']) ?>" id="gr_subject" required class="form-input required" size="80">
                            <?php if ($w == 'u') { ?>
                                <div>
                                    <a href="<?php echo './board_form.php?gr_id=' . $gr_id; ?>" class="btn btn-sm btn-surface-default-soft">게시판생성</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="gr_device" class="form-label">접속기기</label>
                    </div>
                    <div class="af-field">
                        <?php echo help("PC 와 모바일 사용을 구분합니다.") ?>
                        <select id="gr_device" name="gr_device" class="form-select">
                            <option value="both" <?php echo get_selected($group['gr_device'], 'both', true); ?>>PC와 모바일에서 모두 사용</option>
                            <option value="pc" <?php echo get_selected($group['gr_device'], 'pc'); ?>>PC 전용</option>
                            <option value="mobile" <?php echo get_selected($group['gr_device'], 'mobile'); ?>>모바일 전용</option>
                        </select>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <?php if ($is_admin == 'super') { ?>
                            <label for="gr_admin" class="form-label">그룹 관리자</label>
                        <?php } else { ?>
                            <label class="form-label">그룹 관리자</label>
                        <?php } ?>
                    </div>
                    <div class="af-field">
                        <?php if ($is_admin == 'super') { ?>
                            <input type="text" id="gr_admin" name="gr_admin" value="<?php echo $gr['gr_admin']; ?>" maxlength="20" class="form-input">
                        <?php } else { ?>
                            <input type="hidden" id="gr_admin" name="gr_admin" value="<?php echo $gr['gr_admin']; ?>">
                            <p class="hint-text"><?php echo get_text($gr['gr_admin']); ?></p>
                        <?php } ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="gr_use_access" class="form-label">접근회원사용</label>
                    </div>
                    <div class="af-field">
                        <?php echo help("사용에 체크하시면 이 그룹에 속한 게시판은 접근가능한 회원만 접근이 가능합니다.") ?>
                        <label for="gr_use_access" class="af-check form-label">
                            <input type="checkbox" name="gr_use_access" value="1" id="gr_use_access" <?php echo $gr['gr_use_access'] ? 'checked' : ''; ?> class="form-checkbox">
                            <span class="form-label">사용</span>
                        </label>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label class="form-label">접근회원수</label>
                    </div>
                    <div class="af-field">
                        <a href="<?php echo './boardgroupmember_list.php?gr_id=' . $gr_id; ?>" class="btn btn-sm btn-surface-default-soft"><?php echo number_format($group_member_count); ?>명</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between border-default-300 border-t border-dashed pt-4">
        <a href="./boardgroup_list.php?<?php echo $qstr; ?>" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>
</form>

<div class="hint-box">
    <p>
        게시판을 생성하시려면 1개 이상의 게시판그룹이 필요합니다.<br>
        게시판그룹을 이용하시면 더 효과적으로 게시판을 관리할 수 있습니다.
    </p>
</div>
<script>
    function fboardgroup_check(f) {
        f.action = './boardgroup_form_update.php';
        return true;
    }
</script>

<?php
require_once './admin.tail.php';
