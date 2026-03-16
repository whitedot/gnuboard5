<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
?>

<!-- 로그인 후 아웃로그인 시작 { -->
<section id="s_ol_after">
    <header id="s_ol_after_hd">
        <h2>나의 회원정보</h2>
        <span>
            <?php echo get_member_profile_img($member['mb_id']); ?>
            <?php if ($is_admin == 'super' || $is_auth) {  ?><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>"><i></i><span>관리자</span></a><?php }  ?>
        </span>
        <strong><?php echo $nick ?>님</strong>
        <a href="<?php echo G5_MEMBER_URL ?>/member_confirm.php?url=register_form.php" id="s_ol_after_info">정보수정</a>
        <a href="<?php echo G5_MEMBER_URL ?>/logout.php" id="s_ol_after_logout">로그아웃</a>
    </header>
</section>

<script>
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave()
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?"))
        location.href = "<?php echo G5_MEMBER_URL ?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- } 로그인 후 아웃로그인 끝 -->
