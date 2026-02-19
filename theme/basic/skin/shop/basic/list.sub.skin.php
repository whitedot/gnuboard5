<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨

?>
<?php if(isset($ca['ca_skin']) && $ca['ca_skin'] === 'list.10.skin.php'){ ?>
<ul id="sct_lst">
    <li><button type="button" class="sct_lst_view sct_lst_list"><i aria-hidden="true"></i><span>리스트뷰</span></button></li>
    <li><button type="button" class="sct_lst_view sct_lst_gallery"><i aria-hidden="true"></i><span>갤러리뷰</span></button></li>
</ul>
<?php }