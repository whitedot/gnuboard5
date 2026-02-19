<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$print_version = ($is_admin == 'super') ? 'Version ' . G5_GNUBOARD_VER : '';
?>

<noscript>
    <p>
        이 페이지는 JavaScript가 활성화되어야 일부 기능이 정상 동작합니다.
    </p>
</noscript>
</div>
<footer id="ft">
    <p>
        Copyright &copy; <?php echo $_SERVER['HTTP_HOST']; ?>. All rights reserved. <?php echo $print_version; ?><br>
        <button type="button" class="scroll_top"><span>TOP</span></button>
    </p>
</footer>

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
    $(".scroll_top").click(function() {
        $("body,html").animate({
            scrollTop: 0
        }, 400);
    })
</script>

<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>

<?php
require_once G5_PATH . '/tail.sub.php';
