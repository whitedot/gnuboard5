<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$admin_tail_view = admin_build_tail_view($is_admin);
$copyright_host = $admin_tail_view['copyright_host'];
$print_version = $admin_tail_view['print_version'];
$admin_js_src = $admin_tail_view['admin_js_src'];
?>

<noscript>
    <p>
        이 페이지는 JavaScript가 활성화되어야 일부 기능이 정상 동작합니다.
    </p>
</noscript>
    </div>

    <footer id="ft">
        <p>
            <span>Copyright &copy; <?php echo $copyright_host; ?>. All rights reserved. <?php echo $print_version; ?></span>
            <button type="button" class="scroll_top"><span>TOP</span></button>
        </p>
    </footer>
</div>

<div id="adminPopupContainer">
    <div id="popupOverlay" class="is-hidden hidden">
        <div>
            <div>
                <strong id="popupTitle"></strong>
                <button type="button" class="popup-close-btn" data-popup-close="popupOverlay">
                    <i></i><span>팝업 닫기</span>
                </button>
            </div>
            <div id="popupBody"></div>
            <div id="popupFooter"></div>
        </div>
    </div>
</div>

<script src="<?php echo $admin_js_src; ?>"></script>

<?php
require_once G5_PATH . '/tail.sub.php';
