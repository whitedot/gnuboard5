<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<p>메일인증을 받지 못한 경우 회원정보의 메일주소를 변경 할 수 있습니다.</p>

<form method="post" name="fregister_email" action="<?php echo $register_email_action_url; ?>" onsubmit="return fregister_email_submit(this);">
<input type="hidden" name="mb_id" value="<?php echo $member_id; ?>">

<div>
    <table>
    <caption>사이트 이용정보 입력</caption>
    <tr>
        <th scope="row"><label for="reg_mb_email">E-mail<strong>필수</strong></label></th>
        <td><input type="text" name="mb_email" id="reg_mb_email" required size="30" maxlength="100" value="<?php echo $member_email; ?>"></td>
    </tr>
    <tr>
        <th scope="row">자동등록방지</th>
        <td><?php echo captcha_html(); ?></td>
    </tr>
    </table>
</div>

<div>
    <input type="submit" id="btn_submit" value="<?php echo $submit_label; ?>">
    <a href="<?php echo $cancel_url; ?>">취소</a>
</div>

</form>

<script>
function fregister_email_submit(f)
{
    <?php echo chk_captcha_js(); ?>

    return true;
}
</script>
