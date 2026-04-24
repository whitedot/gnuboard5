<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<div id="mb_confirm">
    <h1><?php echo $page_title ?></h1>

    <p>
        <strong>비밀번호를 한번 더 입력해주세요.</strong>
        <?php echo $confirm_description_text; ?>
    </p>

    <form name="fmemberconfirm" action="<?php echo $confirm_action_url ?>" onsubmit="return fmemberconfirm_submit(this);" method="post">
    <input type="hidden" name="mb_id" value="<?php echo $member_id ?>">
    <input type="hidden" name="w" value="u">

    <fieldset>
        <span>회원아이디</span>
        <span id="mb_confirm_id"><?php echo $member_id ?></span>
        <label for="confirm_mb_password">비밀번호<strong>필수</strong></label>
        <input type="password" name="mb_password" id="confirm_mb_password" required size="15" maxLength="20" placeholder="비밀번호">
        <input type="submit" value="<?php echo $submit_label ?>" id="btn_submit">
    </fieldset>

    </form>

</div>

<script>
function fmemberconfirm_submit(f)
{
    document.getElementById("btn_submit").disabled = true;

    return true;
}
</script>
