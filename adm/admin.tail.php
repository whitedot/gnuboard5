<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$print_version = ($is_admin == 'super') ? 'Version ' . G5_GNUBOARD_VER : '';
?>

<noscript>
    <p class="mx-3 mb-4 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/50 dark:bg-amber-950/40 dark:text-amber-200 sm:mx-5 lg:mx-8">
        이 페이지는 JavaScript가 활성화되어야 일부 기능이 정상 동작합니다.
    </p>
</noscript>
</div>
<footer id="ft" class="px-3 pb-6 sm:px-5 lg:px-8">
    <p class="rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm text-slate-600 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
        Copyright &copy; <?php echo $_SERVER['HTTP_HOST']; ?>. All rights reserved. <?php echo $print_version; ?><br>
        <button type="button" class="scroll_top mt-2 inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"><span class="top_txt">TOP</span></button>
    </p>
</footer>

<div id="adminPopupContainer">
    <div id="popupOverlay" class="popup-overlay is-hidden fixed inset-0 z-[120] hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm" onclick="PopupManager.close('popupOverlay')">
        <div class="popup-content w-full max-w-2xl rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900" onclick="event.stopPropagation()">
            <div class="popup-header flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <strong id="popupTitle" class="popup-title text-lg font-semibold text-slate-900 dark:text-slate-100"></strong>
                <button type="button" class="popup-close-btn inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" onclick="PopupManager.close('popupOverlay')">
                    <i class="fa fa-close"></i><span class="sr-only">팝업 닫기</span>
                </button>
            </div>
            <div class="popup-body max-h-[70vh] overflow-y-auto p-5" id="popupBody"></div>
            <div class="popup-footer flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4 dark:border-slate-700" id="popupFooter"></div>
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

<script>
    $(function() {

        var hide_menu = false;
        var mouse_event = false;
        var oldX = oldY = 0;

        $(document).mousemove(function(e) {
            if (oldX == 0) {
                oldX = e.pageX;
                oldY = e.pageY;
            }

            if (oldX != e.pageX || oldY != e.pageY) {
                mouse_event = true;
            }
        });

        var $gnb = $(".gnb_1dli > a");
        $gnb.mouseover(function() {
            if (mouse_event) {
                $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
                $(this).parent().addClass("gnb_1dli_over gnb_1dli_on");
                menu_rearrange($(this).parent());
                hide_menu = false;
            }
        });

        $gnb.mouseout(function() {
            hide_menu = true;
        });

        $(".gnb_2dli").mouseover(function() {
            hide_menu = false;
        });

        $(".gnb_2dli").mouseout(function() {
            hide_menu = true;
        });

        $gnb.focusin(function() {
            $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
            $(this).parent().addClass("gnb_1dli_over gnb_1dli_on");
            menu_rearrange($(this).parent());
            hide_menu = false;
        });

        $gnb.focusout(function() {
            hide_menu = true;
        });

        $(".gnb_2da").focusin(function() {
            $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
            $(this).closest(".gnb_1dli").addClass("gnb_1dli_over gnb_1dli_on");
            menu_rearrange($(this).closest(".gnb_1dli"));
            hide_menu = false;
        });

        $(".gnb_2da").focusout(function() {
            hide_menu = true;
        });

        $('#gnb_1dul>li').bind('mouseleave', function() {
            submenu_hide();
        });

        $(document).bind('click focusin', function() {
            if (hide_menu) {
                submenu_hide();
            }
        });

        var font_resize_act = get_cookie("ck_font_resize_act");
        if (font_resize_act != "") {
            font_resize("container", font_resize_act);
        }
    });

    function submenu_hide() {
        $(".gnb_1dli").removeClass("gnb_1dli_over gnb_1dli_over2 gnb_1dli_on");
    }

    function menu_rearrange(el) {
        var width = $("#gnb_1dul").width();
        var left = w1 = w2 = 0;
        var idx = $(".gnb_1dli").index(el);

        for (i = 0; i <= idx; i++) {
            w1 = $(".gnb_1dli:eq(" + i + ")").outerWidth();
            w2 = $(".gnb_2dli > a:eq(" + i + ")").outerWidth(true);

            if ((left + w2) > width) {
                el.removeClass("gnb_1dli_over").addClass("gnb_1dli_over2");
            }

            left += w1;
        }
    }
</script>

<?php
require_once G5_PATH . '/tail.sub.php';
