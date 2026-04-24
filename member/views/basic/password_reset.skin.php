<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<div id="pw_reset">
    
        <form name="fpasswordreset" action="<?php echo $action_url; ?>" onsubmit="return fpasswordreset_submit(this);" method="post" autocomplete="off">
            <fieldset id="info_fs">
                <p><?php echo $page_description_text; ?></p>
                <label for="mb_id">아이디</label>
                <br>
                <b>회원 아이디 : <?php echo $member_id_text; ?></b>
                <label for="mb_pw">새 비밀번호<strong>필수</strong></label>
                <input type="password" name="mb_password" id="mb_pw" required size="30" placeholder="새 비밀번호">
                <label for="mb_pw2">새 비밀번호 확인<strong>필수</strong></label>
                <input type="password" name="mb_password_re" id="mb_pw2" required size="30" placeholder="새 비밀번호 확인">
            </fieldset>
            
                <button type="submit"><?php echo $submit_label; ?></button>
            
        </form>
    
</div>

<script>
function fpasswordreset_submit(f) {
    var passwordField = document.getElementById("mb_pw");
    var passwordConfirmField = document.getElementById("mb_pw2");

    if (passwordField && passwordConfirmField && passwordField.value == passwordConfirmField.value) {
        alert(<?php echo json_encode($password_reset_success_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
    } else {
        alert(<?php echo json_encode($password_reset_mismatch_message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
        return false;
    }
}
</script>
