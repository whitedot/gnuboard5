<?php
$sub_menu = "200300";
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, 'r');

$html_title = '회원메일';

$ma_id = isset($_GET['ma_id']) ? (int) $_GET['ma_id'] : 0;
$ma = array('ma_id' => 0, 'ma_subject' => '', 'ma_content' => '');

if ($w == 'u') {
    $html_title .= '수정';
    $readonly = ' readonly';

    $sql = " select * from {$g5['mail_table']} where ma_id = '{$ma_id}' ";
    $ma = sql_fetch($sql);
    if (!$ma['ma_id']) {
        alert('등록된 자료가 없습니다.');
    }
} else {
    $html_title .= '입력';
}

$g5['title'] = $html_title;
$admin_container_class = 'admin-page-mail-form';
$admin_page_subtitle = '회원메일 템플릿 제목과 내용을 작성하고 관리하세요.';
require_once './admin.head.php';
?>

<form name="fmailform" id="fmailform" action="./mail_update.php" onsubmit="return fmailform_check(this);" method="post" class="admin-form-layout space-y-5">
    <input type="hidden" name="w" value="<?php echo $w ?>" id="w">
    <input type="hidden" name="ma_id" value="<?php echo $ma['ma_id'] ?>" id="ma_id">
    <input type="hidden" name="token" value="" id="token">

    <section class="card">
        <div class="card-header">
            <h2 class="card-title">회원메일 템플릿</h2>
        </div>
        <div class="card-body">
            <p class="hint-text">메일 내용에 {이름}, {닉네임}, {회원아이디}, {이메일} 값을 넣으면 대상 회원 정보로 치환되어 발송됩니다.</p>

            <div class="af-grid">
                <div class="af-row">
                    <div class="af-label">
                        <label for="ma_subject" class="form-label">메일 제목<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <input type="text" name="ma_subject" value="<?php echo get_sanitize_input($ma['ma_subject']); ?>" id="ma_subject" required class="form-input required" size="100">
                    </div>
                </div>
                <div class="af-row">
                    <div class="af-label">
                        <label for="ma_content" class="form-label">메일 내용<strong>필수</strong></label>
                    </div>
                    <div class="af-field">
                        <?php echo editor_html("ma_content", get_text(html_purifier($ma['ma_content']), 0)); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-end border-default-300 border-t border-dashed pt-4">
        <button type="submit" accesskey="s" class="btn btn-solid-primary">저장</button>
    </div>
</form>

<script>
    function fmailform_check(f) {
        errmsg = "";
        errfld = "";

        check_field(f.ma_subject, "제목을 입력하세요.");
        //check_field(f.ma_content, "내용을 입력하세요.");

        if (errmsg != "") {
            alert(errmsg);
            errfld.focus();
            return false;
        }

        <?php echo get_editor_js("ma_content"); ?>
        <?php echo chk_editor_js("ma_content"); ?>

        return true;
    }

    document.fmailform.ma_subject.focus();
</script>

<?php
require_once './admin.tail.php';
