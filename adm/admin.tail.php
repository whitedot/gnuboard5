<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$admin_tail_view = admin_build_tail_view($is_admin);
extract($admin_tail_view, EXTR_SKIP);
?>

<noscript>
    <p>
        이 페이지는 JavaScript가 활성화되어야 일부 기능이 정상 동작합니다.
    </p>
</noscript>
    </div>

    <footer id="ft">
        <p>
            <span>Copyright &copy; <?php echo $_SERVER['HTTP_HOST']; ?>. All rights reserved. <?php echo $print_version; ?></span>
            <button type="button" class="scroll_top"><span>TOP</span></button>
        </p>
    </footer>
</div>

<div id="adminPopupContainer">
    <div id="popupOverlay" class="is-hidden hidden" onclick="PopupManager.close('popupOverlay')">
        <div onclick="event.stopPropagation()">
            <div>
                <strong id="popupTitle"></strong>
                <button type="button" class="popup-close-btn" onclick="PopupManager.close('popupOverlay')">
                    <i></i><span>팝업 닫기</span>
                </button>
            </div>
            <div id="popupBody"></div>
            <div id="popupFooter"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var scrollTopButton = document.querySelector(".scroll_top");
        if (!scrollTopButton) {
            return;
        }

        scrollTopButton.addEventListener("click", function(event) {
            event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    });
</script>

<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo $admin_js_ver; ?>"></script>

<?php
require_once G5_PATH . '/tail.sub.php';
