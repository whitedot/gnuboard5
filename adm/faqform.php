<?php
$sub_menu = '300700';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check_menu($auth, $sub_menu, "w");

$fm_id = isset($_GET['fm_id']) ? (int) $_GET['fm_id'] : 0;
$fa_id = isset($_GET['fa_id']) ? (int) $_GET['fa_id'] : 0;

$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);
if (empty($fm['fm_id'])) {
    alert('등록된 FAQ 분류가 없습니다.');
}

$html_title = 'FAQ '.$fm['fm_subject'];

$fa = array('fa_id'=>0, 'fm_id'=>0, 'fa_subject'=>'', 'fa_content'=>'', 'fa_order'=>0);

if ($w == "u") {
    $html_title .= " 수정";

    $sql = " select * from {$g5['faq_table']} where fa_id = '$fa_id' ";
    $fa = sql_fetch($sql);
    if (!$fa['fa_id']) {
        alert("등록된 자료가 없습니다.");
    }
} else {
    $html_title .= ' 항목 입력';
}

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-faq-form';
$admin_page_subtitle = 'FAQ 항목의 질문과 답변을 에디터로 정교하게 작성하세요.';

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="frmfaqform" id="frmfaqform" action="./faqformupdate.php" onsubmit="return frmfaqform_check(this);" method="post" class="admin-form-layout space-y-5">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
    <input type="hidden" name="fa_id" value="<?php echo $fa_id; ?>">
    <input type="hidden" name="token" value="">

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">FAQ 항목 설정</h2>
        </div>
        <div class="card-body">
            <p class="hint-text">질문과 답변은 에디터로 작성되며, 출력순서가 작을수록 상단에 노출됩니다.</p>

            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="fa_order" class="form-label">출력순서</label>
                    </div>
                    <div class="af-field">
                        <?php echo help('숫자가 작을수록 FAQ 페이지에서 먼저 출력됩니다.'); ?>
                        <div class="af-inline">
                            <input type="text" name="fa_order" value="<?php echo $fa['fa_order']; ?>" id="fa_order" maxlength="10" size="10" class="form-input af-input-xs">
                            <?php if ($w == 'u') { ?>
                                <a href="<?php echo G5_BBS_URL; ?>/faq.php?fm_id=<?php echo $fm_id; ?>" class="btn btn-sm btn-surface-default-soft">내용보기</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fa_subject" class="form-label">질문</label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html('fa_subject', get_text(html_purifier($fa['fa_subject']), 0)); ?>
                    </div>
                </div>

                <div class="af-row">
                    <div class="af-label">
                        <label for="fa_content" class="form-label">답변</label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html('fa_content', get_text(html_purifier($fa['fa_content']), 0)); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between border-default-300 border-t border-dashed pt-4">
        <a href="./faqlist.php?fm_id=<?php echo $fm_id; ?>" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>
</form>

<script>
function frmfaqform_check(f)
{
    errmsg = "";
    errfld = "";

    //check_field(f.fa_subject, "제목을 입력하세요.");
    //check_field(f.fa_content, "내용을 입력하세요.");

    if (errmsg != "")
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    <?php echo get_editor_js('fa_subject'); ?>
    <?php echo get_editor_js('fa_content'); ?>

    return true;
}

// document.getElementById('fa_order').focus(); 포커스 해제
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');

