<?php
$sub_menu = '300700';
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, "w");

$html_title = 'FAQ';

$fm_id = isset($_GET['fm_id']) ? strval(preg_replace('/[^0-9]/', '', $_GET['fm_id'])) : 0;

if ($w == "u") {
    $html_title .= ' 수정';

    $sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
    $fm = sql_fetch($sql);
    if (!$fm['fm_id']) {
        alert('등록된 자료가 없습니다.');
    }
} else {
    $html_title .= ' 입력';
    $fm = array('fm_order' => '', 'fm_subject' => '', 'fm_id' => 0, 'fm_head_html' => '', 'fm_tail_html' => '');
}

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-faq-master-form';
$admin_page_subtitle = 'FAQ 분류 기본정보와 상하단 노출 콘텐츠를 설정하세요.';

require_once G5_ADMIN_PATH . '/admin.head.php';
?>

<form name="frmfaqmasterform" id="frmfaqmasterform" action="./faqmasterformupdate.php" onsubmit="return frmfaqmasterform_check(this);" method="post" enctype="MULTIPART/FORM-DATA" class="admin-form-layout space-y-5">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
    <input type="hidden" name="token" value="">

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">FAQ 분류 설정</h2>
        </div>
        <div class="card-body">
            <p class="hint-text">분류 노출순서, 제목, 상하단 이미지 및 HTML 콘텐츠를 설정합니다.</p>

            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="fm_order" class="form-label">출력순서</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('숫자가 작을수록 FAQ 분류에서 먼저 출력됩니다.'); ?>
                        <input type="text" name="fm_order" value="<?php echo $fm['fm_order']; ?>" id="fm_order" maxlength="10" size="10" class="form-input af-input-xs">
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fm_subject" class="form-label">제목<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <div class="af-stack">
                            <input type="text" value="<?php echo get_text($fm['fm_subject']); ?>" name="fm_subject" id="fm_subject" required class="form-input required" size="70">
                        </div>
                        <?php if ($w == 'u') { ?>
                            <div class="af-inline">
                                <a href="<?php echo G5_BBS_URL; ?>/faq.php?fm_id=<?php echo $fm_id; ?>" class="btn btn-sm btn-surface-default-soft">보기</a>
                                <a href="./faqlist.php?fm_id=<?php echo $fm_id; ?>" class="btn btn-sm btn-surface-default-soft">상세보기</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fm_himg" class="form-label">상단이미지</label>
                    </div>
                    <div class="af-field">
                        <input type="file" name="fm_himg" id="fm_himg" class="form-input">
                        <?php
                        $himg = G5_DATA_PATH . '/faq/' . $fm['fm_id'] . '_h';
                        $himg_str = '';
                        $width = 0;
                        if (file_exists($himg)) {
                            $size = @getimagesize($himg);
                            if ($size) {
                                if ($size[0] && $size[0] > 750) {
                                    $width = 750;
                                } else {
                                    $width = $size[0];
                                }
                            }
                            echo '<label for="fm_himg_del" class="af-check form-label"><input type="checkbox" name="fm_himg_del" value="1" id="fm_himg_del" class="form-checkbox"><span class="form-label">삭제</span></label>';
                            $himg_str = '<img src="' . G5_DATA_URL . '/faq/' . $fm['fm_id'] . '_h" width="' . $width . '" alt="">';
                        }
                        if ($himg_str) {
                            echo '<div class="banner_or_img">';
                            echo $himg_str;
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fm_timg" class="form-label">하단이미지</label>
                    </div>
                    <div class="af-field">
                        <input type="file" name="fm_timg" id="fm_timg" class="form-input">
                        <?php
                        $timg = G5_DATA_PATH . '/faq/' . $fm['fm_id'] . '_t';
                        $timg_str = '';
                        $width = 0;
                        if (file_exists($timg)) {
                            $size = @getimagesize($timg);
                            if ($size) {
                                if ($size[0] && $size[0] > 750) {
                                    $width = 750;
                                } else {
                                    $width = $size[0];
                                }
                            }

                            echo '<label for="fm_timg_del" class="af-check form-label"><input type="checkbox" name="fm_timg_del" value="1" id="fm_timg_del" class="form-checkbox"><span class="form-label">삭제</span></label>';
                            $timg_str = '<img src="' . G5_DATA_URL . '/faq/' . $fm['fm_id'] . '_t" width="' . $width . '" alt="">';
                        }
                        if ($timg_str) {
                            echo '<div class="banner_or_img">';
                            echo $timg_str;
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fm_head_html" class="form-label">상단 내용</label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html('fm_head_html', get_text(html_purifier($fm['fm_head_html']), 0)); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fm_tail_html" class="form-label">하단 내용</label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html('fm_tail_html', get_text(html_purifier($fm['fm_tail_html']), 0)); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between border-default-300 border-t border-dashed pt-4">
        <a href="./faqmasterlist.php" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>
</form>

<script>
    function frmfaqmasterform_check(f) {
        <?php echo get_editor_js('fm_head_html'); ?>
        <?php echo get_editor_js('fm_tail_html'); ?>
        return true;
    }

    // document.frmfaqmasterform.fm_subject.focus();
</script>

<?php
require_once G5_ADMIN_PATH . '/admin.tail.php';


