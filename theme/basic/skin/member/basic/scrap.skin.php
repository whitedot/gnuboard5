<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>

<!-- 스크랩 목록 시작 { -->
<div id="scrap">
    <h1 id="win_title"><?php echo $g5['title'] ?></h1>
    <ul>
        <?php for ($i=0; $i<count($list); $i++) {  ?>
        <li>
            <a href="<?php echo $list[$i]['opener_href_wr_id'] ?>" target="_blank" onclick="opener.document.location.href='<?php echo $list[$i]['opener_href_wr_id'] ?>'; return false;"><?php echo $list[$i]['subject'] ?></a>
            <a href="<?php echo $list[$i]['opener_href'] ?>" target="_blank" onclick="opener.document.location.href='<?php echo $list[$i]['opener_href'] ?>'; return false;"><?php echo $list[$i]['bo_subject'] ?></a>
            <span><i aria-hidden="true"></i> <?php echo $list[$i]['ms_datetime'] ?></span>
            <a href="<?php echo $list[$i]['del_href'];  ?>" onclick="del(this.href); return false;"><i aria-hidden="true"></i><span>삭제</span></a>
        </li>
        <?php }  ?>

        <?php if ($i == 0) echo "<li class=\"empty_li\">자료가 없습니다.</li>";  ?>
    </ul>
    <?php echo get_paging($config['cf_write_pages'], $page, $total_page, "?$qstr&amp;page="); ?>

    <div>
        <button type="button" onclick="window.close();">창닫기</button>
    </div>
</div>
<!-- } 스크랩 목록 끝 -->