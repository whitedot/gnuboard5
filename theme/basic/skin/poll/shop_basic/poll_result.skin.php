<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<!-- 설문조사 결과 시작 { -->
<div id="poll_result">
    <h1 id="win_title"><?php echo $g5['title'] ?></h1>
    <div>
        <!-- 설문조사 결과 그래프 시작 { -->
        <span>전체 <?php echo $nf_total_po_cnt ?>표</span>
        <section id="poll_result_list">
            <h2><?php echo $po_subject ?> 결과</h2>
            <ol>
            	<!-- *** 투표수가 가장 많은 것은 li이에 클래스 poll_1st를 붙여주세요 / 수정후 삭제 -->
            	<li>
                    <span>현재 가장 높은 득표율</span>   
                    
                        <span></span>
                    
                    <div>
                    	<strong>500 표</strong>
                    	<span>90 %</span>
                    </div>
                </li>
                <!-- *** 투표수가 가장 많은 것은 li이에 클래스 poll_1st를 붙여주세요 / 수정후 삭제 -->
                
            	<?php for ($i=1; $i<=count($list); $i++) {  ?>
                <li>
                    <span><?php echo $list[$i]['content'] ?></span>   
                    
                        <span></span>
                    
                    <div>
                    	<strong><?php echo $list[$i]['cnt'] ?> 표</strong>
                    	<span><?php echo number_format($list[$i]['rate'], 1) ?> %</span>
                    </div>
                </li>
            	<?php }  ?>
            </ol>
        </section>
        <!-- } 설문조사 결과 그래프 끝 -->

        <!-- 설문조사 기타의견 시작 { -->
        <?php if ($is_etc) {  ?>
        <section id="poll_result_cmt">
            <h2>이 설문에 대한 기타의견</h2>

            <?php for ($i=0; $i<count($list2); $i++) {  ?>
            <article>
                <header>
                    <h2><?php echo $list2[$i]['pc_name'] ?><span>님의 의견</span></h2>
                    <?php echo $list2[$i]['name'] ?>
                    <span><i aria-hidden="true"></i> <?php echo $list2[$i]['datetime'] ?></span>
                    <span><?php if ($list2[$i]['del']) { echo $list2[$i]['del']."<i class=\"fa fa-trash-o\" aria-hidden=\"true\"></i><span class=\"sr-only\">삭제</span></a>"; }  ?></span>
                </header>
                <p>
                    <?php echo $list2[$i]['idea'] ?>
                </p>
            </article>
            <?php }  ?>

            <?php if ($member['mb_level'] >= $po['po_level']) {  ?>
            <form name="fpollresult" action="./poll_etc_update.php" onsubmit="return fpollresult_submit(this);" method="post" autocomplete="off" id="poll_other_q">
            <input type="hidden" name="po_id" value="<?php echo $po_id ?>">
            <input type="hidden" name="w" value="">
            <input type="hidden" name="skin_dir" value="<?php echo urlencode($skin_dir); ?>">
            <?php if ($is_member) {  ?><input type="hidden" name="pc_name" value="<?php echo get_text(cut_str($member['mb_nick'],255)) ?>"><?php }  ?>
            <div id="poll_result_wcmt">
            	<h3><span>기타의견</span><?php echo $po_etc ?></h3>
                <div>
                    <label for="pc_idea">의견<strong>필수</strong></label>
                    <input type="text" id="pc_idea" name="pc_idea" required size="47" maxlength="100" placeholder="의견을 입력해주세요">
                </div>
            </div>
            <?php if ($is_guest) {  ?>
            <div>
                <label for="pc_name">이름<strong>필수</strong></label>
                <input type="text" name="pc_name" id="pc_name" required size="20" placeholder="이름">
            </div>
            <?php echo captcha_html(); ?>
        	<?php } ?>
			<button type="submit">의견남기기</button>           
            </form>
            <?php }  ?>

        </section>
        <?php }  ?>
        <!-- } 설문조사 기타의견 끝 -->

        <!-- 설문조사 다른 결과 보기 시작 { -->
        <aside id="poll_result_oth">
            <h2>다른 투표 결과 보기</h2>
            <ul>
                <?php for ($i=0; $i<count($list3); $i++) {  ?>
                <li><a href="./poll_result.php?po_id=<?php echo $list3[$i]['po_id'] ?>&amp;skin_dir=<?php echo urlencode($skin_dir); ?>"> <?php echo $list3[$i]['subject'] ?> </a><span><i aria-hidden="true"></i> <?php echo $list3[$i]['date'] ?></span></li>
                <?php }  ?>
            </ul>
        </aside>
        <!-- } 설문조사 다른 결과 보기 끝 -->

        
            <button type="button" onclick="window.close();">창닫기</button>
        
    </div>
</div>

<script>
$(function() {
    $(".poll_delete").click(function() {
        if(!confirm("해당 기타의견을 삭제하시겠습니까?"))
            return false;
    });
});

function fpollresult_submit(f)
{
    <?php if ($is_guest) { echo chk_captcha_js(); }  ?>

    return true;
}
</script>
<!-- } 설문조사 결과 끝 -->