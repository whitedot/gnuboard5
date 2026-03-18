<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

    </div>
</div>

</div>
<!-- } 콘텐츠 끝 -->

<hr>

<!-- 하단 시작 { -->
<div id="ft">
    <div id="ft_copy">Copyright &copy; <b><?php echo get_text($config['cf_title']); ?></b> All rights reserved.</div>

    
    <button type="button" id="top_btn">
    	<i aria-hidden="true"></i><span>상단으로</span>
    </button>
    <script>
    $(function() {
        $("#top_btn").on("click", function() {
            $("html, body").animate({scrollTop:0}, '500');
            return false;
        });
    });
    </script>
</div>

<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
