<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<!-- 쪽지 목록 시작 { -->
<div id="memo_list">
    <h1 id="win_title">
    	<?php echo $g5['title'] ?>
    	<div>전체 <?php echo $kind_title ?>쪽지 <?php echo $total_count ?>통<br></div>
    </h1>
    <div>
        <ul>
            <li class="<?php if ($kind == 'recv') {  ?>selected<?php }  ?>"><a href="./memo.php?kind=recv">받은쪽지</a></li>
            <li class="<?php if ($kind == 'send') {  ?>selected<?php }  ?>"><a href="./memo.php?kind=send">보낸쪽지</a></li>
            <li><a href="./memo_form.php">쪽지쓰기</a></li>
        </ul>
        
        
            <ul>
	            <?php
                for ($i=0; $i<count($list); $i++) {
                $readed = (substr($list[$i]['me_read_datetime'],0,1) == 0) ? '' : 'read';
                $memo_preview = utf8_strcut(strip_tags($list[$i]['me_memo']), 30, '..');
                ?>
	            <li class="<?php echo $readed; ?>">
	            	
	            		<?php echo get_member_profile_img($list[$i]['mb_id']); ?>
	            		<?php if (! $readed){ ?><span>안 읽은 쪽지</span><?php } ?>
	            	
	                <div>
	                	<?php echo $list[$i]['name']; ?> <span><i aria-hidden="true"></i> <?php echo $list[$i]['send_datetime']; ?></span>
						
						    <a href="<?php echo $list[$i]['view_href']; ?>"><?php echo $memo_preview; ?></a>
                        
					</div>	
					<a href="<?php echo $list[$i]['del_href']; ?>" onclick="del(this.href); return false;"><i aria-hidden="true"></i> <span>삭제</span></a>
	            </li>
	            <?php } ?>
	            <?php if ($i==0) { echo '<li>자료가 없습니다.</li>'; }  ?>
            </ul>
        

        <!-- 페이지 -->
        <?php echo $write_pages; ?>

        <p><i aria-hidden="true"></i> 쪽지 보관일수는 최장 <strong><?php echo $config['cf_memo_del'] ?></strong>일 입니다.
        </p>

        
            <button type="button" onclick="window.close();">창닫기</button>
        
    </div>
</div>
<!-- } 쪽지 목록 끝 -->