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
    document.addEventListener("DOMContentLoaded", function() {
        var topButton = document.getElementById("top_btn");
        if (!topButton) {
            return;
        }

        topButton.addEventListener("click", function(event) {
            event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    });
    </script>
</div>
<!-- } 하단 끝 -->

<?php
include_once(G5_PATH.'/tail.sub.php');
