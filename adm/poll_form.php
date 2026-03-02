<?php
$sub_menu = "200900";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$po_id = isset($po_id) ? (int) $po_id : 0;
$po = array(
    'po_subject' => '',
    'po_etc' => '',
    'po_level' => '',
    'po_point' => '',
);

$html_title = '투표';
if ($w == '') {
    $html_title .= ' 생성';
} elseif ($w == 'u') {
    $html_title .= ' 수정';
    $sql = " select * from {$g5['poll_table']} where po_id = '{$po_id}' ";
    $po = sql_fetch($sql);
} else {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-poll-form';
$admin_page_subtitle = '투표 제목, 항목, 참여 조건을 한 화면에서 구성하세요.';
require_once './admin.head.php';
?>

<form name="fpoll" id="fpoll" action="./poll_form_update.php" method="post" enctype="multipart/form-data" class="admin-form-layout space-y-5">
    <input type="hidden" name="po_id" value="<?php echo $po_id ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">투표 기본 설정</h2>
        </div>
        <div class="card-body">
            <p class="hint-text">항목 1, 항목 2는 필수 입력입니다. 기존 투표를 수정할 때는 항목별 투표수도 함께 조정할 수 있습니다.</p>

            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="po_subject" class="form-label">투표 제목<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <input type="text" name="po_subject" value="<?php echo get_sanitize_input($po['po_subject']); ?>" id="po_subject" required class="form-input required" size="80" maxlength="125">
                    </div>
                </div>

                <?php
                for ($i = 1; $i <= 9; $i++) {
                    $required = '';
                    $sound_only = '';
                    if ($i == 1 || $i == 2) {
                        $required = 'required';
                        $sound_only = '<strong>필수</strong>';
                    }

                    $po_poll = isset($po['po_poll' . $i]) ? get_text($po['po_poll' . $i]) : '';
                    $po_cnt = isset($po['po_cnt' . $i]) ? get_text($po['po_cnt' . $i]) : 0;
                    ?>

                    <div class="af-row">
                        <div class="af-label">
                            <label for="po_poll<?php echo $i ?>" class="form-label">항목 <?php echo $i ?><?php echo $sound_only ?></label>
                        </div>
                        <div class="af-field">
                            <div class="af-stack">
                                <input type="text" name="po_poll<?php echo $i ?>" value="<?php echo $po_poll ?>" id="po_poll<?php echo $i ?>" <?php echo $required ?> class="form-input <?php echo $required ?>" maxlength="125">
                                <div class="af-inline">
                                    <label for="po_cnt<?php echo $i ?>" class="form-label">항목 <?php echo $i ?> 투표수</label>
                                    <input type="text" name="po_cnt<?php echo $i ?>" value="<?php echo $po_cnt; ?>" id="po_cnt<?php echo $i ?>" size="3" class="form-input">
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

                <div class="af-row">
                    <div class="af-label">
                        <label for="po_etc" class="form-label">기타의견</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('기타 의견을 남길 수 있도록 하려면, 간단한 질문을 입력하세요.') ?>
                        <input type="text" name="po_etc" value="<?php echo get_text($po['po_etc']) ?>" id="po_etc" size="80" maxlength="125" class="form-input">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="po_level" class="form-label">투표가능 회원레벨</label>
                    </div>
                    <div class="af-field">
                        <?php echo help("레벨을 1로 설정하면 손님도 투표할 수 있습니다.") ?>
                        <div class="af-inline">
                            <?php echo get_member_level_select('po_level', 1, 10, $po['po_level'], 'class="form-select"') ?>
                            <span class="form-label">이상 투표할 수 있음</span>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="po_point" class="form-label">포인트</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('투표에 참여한 회원에게 포인트를 부여합니다.') ?>
                        <div class="af-inline">
                            <input type="text" name="po_point" value="<?php echo $po['po_point'] ?>" id="po_point" class="form-input">
                            <span class="form-label">점</span>
                        </div>
                    </div>
                </div>

                <?php if ($w == 'u') { ?>
                    <div class="af-row">
                        <div class="af-label">
                            <label for="po_use" class="form-label">투표사용</label>
                        </div>
                        <div class="af-field">
                            <label for="po_use" class="af-check form-label">
                                <input type="checkbox" name="po_use" id="po_use" value="1" <?php if ($po['po_use']) { echo 'checked="checked"'; } ?> class="form-checkbox">
                                <span class="form-label">사용</span>
                            </label>
                        </div>
                    </div>

                    <div class="af-row">
                        <div class="af-label">
                            <label class="form-label">투표등록일</label>
                        </div>
                        <div class="af-field">
                            <p class="hint-text"><?php echo $po['po_date']; ?></p>
                        </div>
                    </div>

                    <div class="af-row">
                        <div class="af-label">
                            <label for="po_ips" class="form-label">투표참가 IP</label>
                        </div>
                        <div class="af-field">
                            <textarea name="po_ips" id="po_ips" readonly rows="10" class="form-textarea"><?php echo html_purifier(preg_replace("/\n/", " / ", $po['po_ips'])); ?></textarea>
                        </div>
                    </div>

                    <div class="af-row">
                        <div class="af-label">
                            <label for="mb_ids" class="form-label">투표참가 회원</label>
                        </div>
                        <div class="af-field">
                            <textarea name="mb_ids" id="mb_ids" readonly rows="10" class="form-textarea"><?php echo html_purifier(preg_replace("/\n/", " / ", $po['mb_ids'])); ?></textarea>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between border-default-300 border-t border-dashed pt-4">
        <a href="./poll_list.php?<?php echo $qstr ?>" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>
</form>

<?php
require_once './admin.tail.php';
