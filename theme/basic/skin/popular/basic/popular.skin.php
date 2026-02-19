<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨


?>

<!-- 인기검색어 시작 { -->
<section id="popular">
    <h2>인기검색어</h2>
    <div>
	    <ul>
	    <?php
	    if( isset($list) && is_array($list) ){
	        for ($i=0; $i<count($list); $i++) {
	        ?>
	        <li><a href="<?php echo G5_BBS_URL ?>/search.php?sfl=wr_subject&amp;sop=and&amp;stx=<?php echo urlencode($list[$i]['pp_word']) ?>"><?php echo get_text($list[$i]['pp_word']); ?></a></li>
	        <?php
	        }   //end for
	    }   //end if
	    ?>
	    </ul>
        <span>
            <a href="#"><i aria-hidden="true"></i></a>
            <a href="#"><i aria-hidden="true"></i></a>
        </span>
    </div>
</section>

<?php if (isset($list) && $list && is_array($list)) { //게시물이 있다면 ?>

<?php } ?>
<!-- } 인기검색어 끝 -->