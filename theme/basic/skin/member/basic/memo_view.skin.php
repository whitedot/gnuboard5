<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$nick = get_sideview($mb['mb_id'], $mb['mb_nick'], $mb['mb_email'], $mb['mb_homepage']);
if($kind == "recv") {
    $kind_str = "보낸";
    $kind_date = "받은";
}
else {
    $kind_str = "받는";
    $kind_date = "보낸";
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<!-- 쪽지보기 시작 { -->
<div id="memo_view">
    <h1 id="win_title"><?php echo $g5['title'] ?></h1>
    <div>
        <!-- 쪽지함 선택 시작 { -->
        <ul>
            <li class="<?php if ($kind == 'recv') {  ?>selected<?php }  ?>"><a href="./memo.php?kind=recv">받은쪽지</a></li>
            <li class="<?php if ($kind == 'send') {  ?>selected<?php }  ?>"><a href="./memo.php?kind=send">보낸쪽지</a></li>
            <li><a href="./memo_form.php">쪽지쓰기</a></li>
        </ul>
        <!-- } 쪽지함 선택 끝 -->

        <article id="memo_view_contents">
            <header>
                <h2>쪽지 내용</h2>
            </header>
            <div id="memo_view_ul">
                <div>
                	<ul>
                		<li>
				            <?php echo get_member_profile_img($mb['mb_id']); ?>
				        </li>
						<li><?php echo $nick ?></li>
						<li><span><?php echo $kind_date ?>시간</span><i aria-hidden="true"></i> <?php echo $memo['me_send_datetime'] ?></li> 
						<li><a href="<?php echo $list_link ?>"><i aria-hidden="true"></i><span>목록</span></a></li>
						<li><a href="<?php echo $del_link; ?>" onclick="del(this.href); return false;"><i aria-hidden="true"></i> <span>삭제</span></a></li>	
					</ul>
                    <div>
                    	<?php if($prev_link) {  ?>
			            <a href="<?php echo $prev_link ?>"><i aria-hidden="true"></i> 이전쪽지</a>
			            <?php }  ?>
			            <?php if($next_link) {  ?>
			            <a href="<?php echo $next_link ?>">다음쪽지 <i aria-hidden="true"></i></a>
			            <?php }  ?>  
                    </div>
                </div>
            </div>
            <p>
                <?php echo conv_content($memo['me_memo'], 0) ?>
            </p>
        </article>
		<div>
			<?php if ($kind == 'recv') {  ?><a href="./memo_form.php?me_recv_mb_id=<?php echo $mb['mb_id'] ?>&amp;me_id=<?php echo $memo['me_id'] ?>">답장</a><?php }  ?>
			<button type="button" onclick="window.close();">창닫기</button>
    	</div>
    </div>
</div>
<!-- } 쪽지보기 끝 -->