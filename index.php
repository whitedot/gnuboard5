<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}



include_once(G5_PATH.'/head.php');
?>
<section class="member-home">
    <h2>회원 서비스</h2>
    <?php if ($is_member) { ?>
    <p><?php echo get_text($member['mb_nick']); ?>님 계정 관리가 가능합니다.</p>
    <p>
        <a href="<?php echo G5_MEMBER_URL; ?>/member_confirm.php?url=<?php echo urlencode(G5_MEMBER_URL.'/register_form.php'); ?>">회원정보 수정</a>
        <span>|</span>
        <a href="<?php echo G5_MEMBER_URL; ?>/logout.php">로그아웃</a>
    </p>
    <?php } else { ?>
    <p>로그인, 회원가입, 비밀번호 재설정만 제공하는 회원 전용 서비스입니다.</p>
    <p>
        <a href="<?php echo G5_MEMBER_URL; ?>/login.php">로그인</a>
        <span>|</span>
        <a href="<?php echo G5_MEMBER_URL; ?>/register.php">회원가입</a>
    </p>
    <?php } ?>
</section>
<?php
include_once(G5_PATH.'/tail.php');
