<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가



include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
?>

<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?>
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
                <li><button type="button" class="gnb_menu_btn" title="전체메뉴"><i aria-hidden="true"></i><span>전체메뉴열기</span></button></li>
                <?php
				$menu_datas = get_menu_db(0, true);
				$gnb_zindex = 999; // gnb_1dli z-index 값 설정용
                $i = 0;
                foreach( $menu_datas as $row ){
                    if( empty($row) ) continue;
                    $add_class = (isset($row['sub']) && $row['sub']) ? 'gnb_al_li_plus' : '';
                ?>
                <li class="<?php echo $add_class; ?>">
                    <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>"><?php echo $row['me_name'] ?></a>
                    <?php
                    $k = 0;
                    foreach( (array) $row['sub'] as $row2 ){

                        if( empty($row2) ) continue; 

                        if($k == 0)
                            echo '<span>하위분류</span><div><ul>'.PHP_EOL;
                    ?>
                        <li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a></li>
                    <?php
                    $k++;
                    }   //end foreach $row2

                    if($k > 0)
                        echo '</ul></div>'.PHP_EOL;
                    ?>
                </li>
                <?php
                $i++;
                }   //end foreach $row

                if ($i == 0) {  ?>
                    <li>메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                <?php } ?>
            </ul>
            <div id="gnb_all">
                <h2>전체메뉴</h2>
                <ul>
                    <?php
                    
                    $i = 0;
                    foreach( $menu_datas as $row ){
                    ?>
                    <li>
                        <a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>"><?php echo $row['me_name'] ?></a>
                        <?php
                        $k = 0;
                        foreach( (array) $row['sub'] as $row2 ){
                            if($k == 0)
                                echo '<ul>'.PHP_EOL;
                        ?>
                            <li><a href="<?php echo $row2['me_link']; ?>" target="_<?php echo $row2['me_target']; ?>"><?php echo $row2['me_name'] ?></a></li>
                        <?php
                        $k++;
                        }   //end foreach $row2

                        if($k > 0)
                            echo '</ul>'.PHP_EOL;
                        ?>
                    </li>
                    <?php
                    $i++;
                    }   //end foreach $row

                    if ($i == 0) {  ?>
                        <li>메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                    <?php } ?>
                </ul>
                <button type="button" class="gnb_close_btn"><i aria-hidden="true"></i></button>
            </div>
            <div id="gnb_all_bg"></div>
        </div>
    </nav>
    <script>
    
    $(function(){
        $(".gnb_menu_btn").click(function(){
            $("#gnb_all, #gnb_all_bg").show();
        });
        $(".gnb_close_btn, #gnb_all_bg").click(function(){
            $("#gnb_all, #gnb_all_bg").hide();
        });
    });

    </script>
</div>
<!-- } 상단 끝 -->


<hr>

<!-- 콘텐츠 시작 { -->
<div id="wrapper">
    <div id="container_wr">
   
    <div id="container">
        <?php if (!defined("_INDEX_")) { ?><h2 id="container_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2><?php }
