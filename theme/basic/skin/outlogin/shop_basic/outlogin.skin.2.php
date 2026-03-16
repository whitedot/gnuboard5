<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>


<button class="btn_member_mn">
	<span><?php echo get_member_profile_img($member['mb_id'], 30, 30); ?></span>
    <span><?php echo $nick ?></span>님
    <i aria-hidden="true"></i>
</button>

<!-- 로그인 후 아웃로그인 시작 { -->
<section id="ol_after" class="member_mn">
    <h2>회원정보</h2>
    <ul id="ol_after_private">
    	<li><a href="<?php echo G5_MEMBER_URL ?>/member_confirm.php?url=register_form.php" >정보수정</a></li>
        <li>
        	<?php if ($is_admin == 'super' || $is_auth) {  ?>
        	<a href="<?php echo G5_ADMIN_URL ?>">관리자</a>
			<?php }  ?>
        </li>
    </ul>
    <footer id="ol_after_ft">
        <a href="<?php echo G5_MEMBER_URL ?>/logout.php" id="ol_after_logout">로그아웃</a>
    </footer>
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
