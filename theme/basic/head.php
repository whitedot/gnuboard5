<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
?>

<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <div id="tnb">
    	<div>
            <ul id="hd_define">
    			<li class="active"><a href="<?php echo G5_URL ?>/">회원 서비스</a></li>
    		</ul>
		</div>
    </div>
    <div id="hd_wrapper">

        <div id="logo">
            <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>"></a>
        </div>
    
        <div></div>
        <ul class="hd_login">        
            <?php if ($is_member) {  ?>
            <li><a href="<?php echo G5_MEMBER_URL ?>/member_confirm.php?url=<?php echo G5_MEMBER_URL ?>/register_form.php">정보수정</a></li>
            <li><a href="<?php echo G5_MEMBER_URL ?>/logout.php">로그아웃</a></li>
            <?php if ($is_admin) {  ?>
            <li><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">관리자</a></li>
            <?php }  ?>
            <?php } else {  ?>
            <li><a href="<?php echo G5_MEMBER_URL ?>/register.php">회원가입</a></li>
            <li><a href="<?php echo G5_MEMBER_URL ?>/login.php">로그인</a></li>
            <?php }  ?>

        </ul>
    </div>
    
    <nav id="gnb">
        <h2>메인메뉴</h2>
        <div>
            <ul id="gnb_1dul">
                <li><a href="<?php echo G5_URL ?>/">홈</a></li>
                <?php if ($is_member) { ?>
                <li><a href="<?php echo G5_MEMBER_URL ?>/member_confirm.php?url=<?php echo G5_MEMBER_URL ?>/register_form.php">내 정보</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</div>
<!-- } 상단 끝 -->


<hr>

<!-- 콘텐츠 시작 { -->
<div id="wrapper">
    <div id="container_wr">
   
    <div id="container">
        <?php if (!defined("_INDEX_")) { ?><h2 id="container_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2><?php }
