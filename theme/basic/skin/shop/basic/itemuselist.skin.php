<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 전체 상품 사용후기 목록 시작 { -->
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<div id="sps_sch">
	<label for="sfl">검색항목 필수</label>
    <select name="sfl" id="sfl" required>
        <option value="">선택</option>
        <option value="b.it_name"   <?php echo get_selected($sfl, "b.it_name"); ?>>상품명</option>
        <option value="a.it_id"     <?php echo get_selected($sfl, "a.it_id"); ?>>상품코드</option>
        <option value="a.is_subject"<?php echo get_selected($sfl, "a.is_subject"); ?>>후기제목</option>
        <option value="a.is_content"<?php echo get_selected($sfl, "a.is_content"); ?>>후기내용</option>
        <option value="a.is_name"   <?php echo get_selected($sfl, "a.is_name"); ?>>작성자명</option>
        <option value="a.mb_id"     <?php echo get_selected($sfl, "a.mb_id"); ?>>작성자아이디</option>
    </select>
    <div>
	    <label for="stx">검색어<strong> 필수</strong></label>
	    <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" required>
	    <button type="submit" value="검색"><i aria-hidden="true"></i><span>검색</span></button>
    </div>
    <a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">전체보기</a>
</div>
</form>

<div id="sps">
    <!-- <p><?php echo $config['cf_title']; ?> 전체 사용후기 목록입니다.</p> -->
    <?php
    $thumbnail_width = 500;

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $num = $total_count - ($page - 1) * $rows - $i;
        $star = get_star($row['is_score']);

        $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);

        $row2 = get_shop_item($row['it_id'], true);
        $it_href = shop_item_url($row['it_id']);

        if ($i == 0) echo '<ol>';
    ?>
    <li>
        <div>
        	<div>
	            <a href="<?php echo $it_href; ?>">
	                <?php echo get_it_image($row['it_id'], 100, 100); ?>
	                <span><?php echo $row2['it_name']; ?></span>
	            </a>
	            <button class="prd_detail" data-url="<?php echo G5_SHOP_URL.'/largeimage.php?it_id='.$row['it_id']; ?>"><i aria-hidden="true"></i><span>상품 이미지보기</span></button>
			</div>            
		</div>

        <div>
        	<span>평가점수</span>
            <span><img src="<?php echo G5_URL; ?>/shop/img/s_star<?php echo $star; ?>.png" alt="별<?php echo $star; ?>개" width="80"></span>
                
            <span><?php echo get_text($row2['it_name']); ?></span>
            <span><?php echo get_text($row['is_subject']); ?></span>
            <span><?php echo get_itemuse_thumb($row['is_content'], 60, 60); ?></span>

	        <div>
	        	<dl>
	                <dt>작성자</dt>
	                <dd><i aria-hidden="true"></i> <?php echo $row['is_name']; ?></dd>
	                <dt>작성일</dt>
	                <dd><i aria-hidden="true"></i> <?php echo substr($row['is_time'],0,10); ?></dd>
	            </dl>
	            
	        	<button class="<?php echo $i; ?> review_detail">내용보기</button>
	        	
	        	<!-- 사용후기 자세히 시작 -->
	            <div class="review_detail_cnt">
	            	<div>
	            		<h3>사용후기</h3>
	            		<div>
	            			<div>
	            				<span><?php echo get_text($row['is_subject']); ?></span>
	            				<dl>
					                <dt>작성자</dt>
					                <dd><i aria-hidden="true"></i> <?php echo $row['is_name']; ?></dd>
					                <dt>작성일</dt>
					                <dd><i aria-hidden="true"></i> <?php echo substr($row['is_time'],0,10); ?></dd>
					            </dl>
	            			</div>
	            			<div>
	            				<?php echo get_it_image($row['it_id'], 50, 50); ?>
	            				<span><?php echo get_text($row2['it_name']); ?></span>
	            				<span>평가점수</span><img src="<?php echo G5_URL; ?>/shop/img/s_star<?php echo $star; ?>.png" alt="별<?php echo $star; ?>개" width="80">
	            			</div>
	            			
	            			<div id="sps_con_<?php echo $i; ?>">
				                <?php echo $is_content; // 사용후기 내용 ?>
				                <?php
				                if( !empty($row['is_reply_subject']) ){     //사용후기 답변이 있다면
				                    $is_reply_content = get_view_thumbnail(conv_content($row['is_reply_content'], 1), $thumbnail_width);
				                ?>
				                <div>
				                    <section>
				                        <h2><?php echo get_text($row['is_reply_subject']); ?></h2>
				                        <div>
				                            <i aria-hidden="true"></i> <?php echo $row['is_reply_name']; ?>
				                        </div>
				                        <div id="sps_con_<?php echo $i; ?>_reply">
				                            <?php echo $is_reply_content; // 사용후기 답변 내용 ?>
				                        </div>
				                    </section>
				                </div>
				                <?php } //end if ?>
				            </div>
	            		</div>
	            		<button class="rd_cls"><span>후기 상세보기 팝업 닫기</span><i aria-hidden="true"></i></button>
	            	</div>
	            </div>
	            <!-- 사용후기 자세히 끝 -->
	        </div>
        </div>
    </li>
    <?php }
    if ($i > 0) echo '</ol>';
    if ($i == 0) echo '<p id="sps_empty">자료가 없습니다.</p>';
    ?>
</div>

<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
jQuery(function($){
    // 사용후기 열기
    $(".review_detail").on("click", function(){
        $(this).parent("div").children(".review_detail_cnt").show();
    });
		
    // 사용후기 닫기
    $(document).mouseup(function (e){
        var container = $(".review_detail_cnt");
        if( container.has(e.target).length === 0)
        container.hide();
    });

    // 후기 상세 글쓰기 닫기
    $('.rd_cls').click(function(){
        $('.review_detail_cnt').hide();
    });

    // 상품이미지 크게보기
    $(".prd_detail").click(function() {
        var url = $(this).attr("data-url");
        var top = 10;
        var left = 10;
        var opt = 'scrollbars=yes,top='+top+',left='+left;
        popup_window(url, "largeimage", opt);

        return false;
    });
});
				
</script>
<!-- } 전체 상품 사용후기 목록 끝 -->