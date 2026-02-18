<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

$files = glob(G5_ADMIN_PATH . '/css/admin_extend_*');
if (is_array($files)) {
    foreach ((array) $files as $k => $css_file) {
        $fileinfo = pathinfo($css_file);
        $ext = $fileinfo['extension'];

        if ($ext !== 'css') {
            continue;
        }

        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="' . $css_file . '">', $k);
    }
}

require_once G5_ADMIN_PATH . '/head.sub.admin.php';

function print_menu1($key, $no = '')
{
    return print_menu2($key, $no);
}

function print_menu2($key, $no = '')
{
    global $menu, $auth_menu, $is_admin, $auth, $sub_menu;

    $str = '<ul class="space-y-1">';

    for ($i = 1; $i < count($menu[$key]); $i++) {
        if (!isset($menu[$key][$i])) {
            continue;
        }

        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0], $auth) || !strstr($auth[$menu[$key][$i][0]], 'r'))) {
            continue;
        }

        $current_class = '';
        $active_utility = '';
        if ($menu[$key][$i][0] == $sub_menu) {
            $current_class = ' on';
            $active_utility = ' bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900';
        }

        $str .= '<li data-menu="' . $menu[$key][$i][0] . '"><a href="' . $menu[$key][$i][2] . '" class="gnb_2da block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800/70 dark:hover:text-white' . $active_utility . $current_class . '">' . $menu[$key][$i][1] . '</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }

    $str .= '</ul>';

    return $str;
}

$adm_menu_cookie = array(
    'container' => '',
    'gnb'       => '',
    'btn_gnb'   => '',
);

if (!empty($_COOKIE['g5_admin_btn_gnb'])) {
    $adm_menu_cookie['container'] = 'container-small';
    $adm_menu_cookie['gnb'] = 'gnb_small';
    $adm_menu_cookie['btn_gnb'] = 'btn_gnb_open';
}
?>

<script>
    var g5_admin_csrf_token_key = "<?php echo (function_exists('admin_csrf_token_key')) ? admin_csrf_token_key() : ''; ?>";
    var tempX = 0;
    var tempY = 0;

    function imageview(id, w, h) {
        menu(id);

        var el_id = document.getElementById(id);
        submenu = el_id.style;
        submenu.left = tempX - (w + 11);
        submenu.top = tempY - (h / 2);

        selectBoxVisible();

        if (el_id.style.display != 'none')
            selectBoxHidden(id);
    }
</script>

<div id="to_content" class="sr-only"><a href="#container">본문 바로가기</a></div>

<header id="hd" class="bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100">
    <h1 class="sr-only"><?php echo $config['cf_title']; ?></h1>

    <nav id="gnb" class="gnb_large fixed inset-y-0 left-0 z-50 w-72 -translate-x-full overflow-y-auto border-r border-slate-200 bg-white px-4 py-6 transition-transform duration-300 lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900 <?php echo $adm_menu_cookie['gnb']; ?>">
        <h2 class="mb-6 flex items-center gap-3 px-2 text-lg font-semibold text-slate-900 dark:text-white">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-white dark:bg-slate-200 dark:text-slate-900">M</span>
            <span>Modulix Admin</span>
        </h2>

        <ul class="gnb_ul space-y-2">
            <?php
            $jj = 1;
            foreach ($amenu as $key => $value) {
                if (!isset($menu['menu' . $key][0][2]) || !$menu['menu' . $key][0][2]) {
                    continue;
                }

                $current_class = '';
                $opened_utility = ' hidden';
                $mark = '+';
                if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu' . $key][0][0], 0, 3))) {
                    $current_class = ' on';
                    $opened_utility = '';
                    $mark = '-';
                }

                $button_title = $menu['menu' . $key][0][1];
            ?>
                <li class="gnb_li rounded-2xl border border-slate-200 bg-white/80 p-2 dark:border-slate-800 dark:bg-slate-900/70<?php echo $current_class; ?>">
                    <button type="button" class="btn_op menu-<?php echo $key; ?> menu-order-<?php echo $jj; ?> flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800" title="<?php echo $button_title; ?>">
                        <span><?php echo $button_title; ?></span>
                        <span class="menu-mark text-slate-400"><?php echo $mark; ?></span>
                    </button>
                    <div class="gnb_oparea_wr mt-1<?php echo $opened_utility; ?>">
                        <div class="gnb_oparea">
                            <h3 class="sr-only"><?php echo $menu['menu' . $key][0][1]; ?></h3>
                            <?php echo print_menu1('menu' . $key, 1); ?>
                        </div>
                    </div>
                </li>
            <?php
                $jj++;
            }
            ?>
        </ul>
    </nav>

    <div id="adminSidebarBackdrop" class="admin-sidebar-backdrop fixed inset-0 z-40 hidden bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>

    <div id="hd_top" class="fixed inset-x-0 top-0 z-40 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur lg:left-72 dark:border-slate-800 dark:bg-slate-900/95">
        <button type="button" id="btn_gnb_mobile" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 lg:hidden dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200" aria-controls="gnb" aria-expanded="false">☰</button>
        <button type="button" id="btn_gnb" class="btn_gnb_close hidden h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 lg:inline-flex dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 <?php echo $adm_menu_cookie['btn_gnb']; ?>">메뉴</button>

        <div id="logo" class="hidden lg:block">
            <a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>" class="text-sm font-semibold text-slate-500 dark:text-slate-400">Dashboard</a>
        </div>

        <div id="tnb" class="ml-auto">
            <ul class="flex items-center gap-2">
                <li class="tnb_li"><button type="button" id="admin_theme_toggle" class="inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200" aria-pressed="false">Dark</button></li>
                <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
                    <li class="tnb_li"><a href="<?php echo G5_SHOP_URL; ?>/" class="tnb_shop inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" target="_blank" title="쇼핑몰 바로가기">쇼핑몰</a></li>
                <?php } ?>
                <li class="tnb_li hidden sm:block"><a href="<?php echo G5_URL; ?>/" class="tnb_community inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" target="_blank" title="커뮤니티 바로가기">커뮤니티</a></li>
                <li class="tnb_li hidden sm:block"><a href="<?php echo G5_ADMIN_URL; ?>/service.php" class="tnb_service inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">부가서비스</a></li>
                <li class="tnb_li relative">
                    <button type="button" class="tnb_mb_btn inline-flex h-10 items-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">관리자</button>
                    <ul class="tnb_mb_area absolute right-0 top-12 z-50 hidden min-w-36 rounded-xl border border-slate-200 bg-white p-1 shadow-lg dark:border-slate-700 dark:bg-slate-800">
                        <li><a class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700" href="<?php echo G5_ADMIN_URL; ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id']; ?>">관리자정보</a></li>
                        <li id="tnb_logout"><a class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-700" href="<?php echo G5_BBS_URL; ?>/logout.php">로그아웃</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
    jQuery(function($) {
        var isEnvMenuPage = <?php echo (isset($sub_menu) && preg_match('/^100/', (string) $sub_menu)) ? 'true' : 'false'; ?>;
        var isMemberMenuPage = <?php echo (isset($sub_menu) && preg_match('/^200/', (string) $sub_menu)) ? 'true' : 'false'; ?>;
        var menu_cookie_key = 'g5_admin_btn_gnb';

        $(".tnb_mb_btn").click(function() {
            $(".tnb_mb_area").toggleClass("hidden");
        });

        $(document).on("click", function(e) {
            if (!$(e.target).closest(".tnb_li.relative").length) {
                $(".tnb_mb_area").addClass("hidden");
            }
        });

        $("#btn_gnb").click(function() {
            var $this = $(this);

            try {
                if (!$this.hasClass("btn_gnb_open")) {
                    set_cookie(menu_cookie_key, 1, 60 * 60 * 24 * 365);
                } else {
                    delete_cookie(menu_cookie_key);
                }
            } catch (err) {}

            $("#container").toggleClass("container-small");
            $("#gnb").toggleClass("gnb_small");
            $this.toggleClass("btn_gnb_open");
        });

        function setMobileSidebar(opened) {
            $("body").toggleClass("admin-sidebar-open", opened);
            $("#btn_gnb_mobile").attr("aria-expanded", opened ? "true" : "false");
            $("#gnb").toggleClass("-translate-x-full", !opened && window.innerWidth < 1024);
            $("#adminSidebarBackdrop").toggleClass("hidden", !opened);
        }

        $("#btn_gnb_mobile").click(function() {
            var opened = !$("body").hasClass("admin-sidebar-open");
            setMobileSidebar(opened);
        });

        $("#adminSidebarBackdrop").click(function() {
            setMobileSidebar(false);
        });

        $(window).on("resize", function() {
            if (window.innerWidth >= 1024) {
                $("body").removeClass("admin-sidebar-open");
                $("#btn_gnb_mobile").attr("aria-expanded", "false");
                $("#adminSidebarBackdrop").addClass("hidden");
                $("#gnb").removeClass("-translate-x-full");
            } else if (!$("body").hasClass("admin-sidebar-open")) {
                $("#gnb").addClass("-translate-x-full");
            }
        }).trigger("resize");

        var themeKey = "g5_admin_theme";
        function syncThemeUI() {
            var dark = document.documentElement.getAttribute("data-theme") === "dark";
            $("#admin_theme_toggle")
                .text(dark ? "Light" : "Dark")
                .attr("aria-pressed", dark ? "true" : "false");
        }
        syncThemeUI();

        $("#admin_theme_toggle").click(function() {
            var dark = document.documentElement.getAttribute("data-theme") === "dark";
            var next = dark ? "light" : "dark";
            document.documentElement.setAttribute("data-theme", next);
            try {
                localStorage.setItem(themeKey, next);
            } catch (e) {}
            syncThemeUI();
        });

        $(".gnb_ul li .btn_op").click(function() {
            var $li = $(this).closest(".gnb_li");
            var isOpened = !$li.find(".gnb_oparea_wr").hasClass("hidden");

            $li.siblings().removeClass("on").find(".gnb_oparea_wr").addClass("hidden");
            $li.siblings().find(".menu-mark").text("+");

            $li.toggleClass("on", !isOpened);
            $li.find(".gnb_oparea_wr").toggleClass("hidden", isOpened);
            $li.find(".menu-mark").text(isOpened ? "+" : "-");
        });

        function applyEnvGuideRefactor() {
            var $scope = $("#container .container_wr");
            if (!$scope.length) {
                return;
            }

            $scope.find("section").each(function() {
                var $section = $(this);
                if ($section.attr("data-env-refactored") === "1" || $section.hasClass("card")) {
                    return;
                }

                var $title = $section.children("h2").first();
                $section.addClass("card").attr("data-env-refactored", "1");

                if ($title.length) {
                    var titleText = $.trim($title.text());
                    $title.addClass("card-header").html('<span class="card-title">' + titleText + '</span>');
                }

                var $body = $('<div class="card-body space-y-base"></div>');
                $section.children().not("h2").appendTo($body);
                $section.append($body);
            });

            $scope.find("label").addClass("form-label");
            $scope.find('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="url"], input[type="search"], input[type="tel"], input[type="date"], input[type="time"], input[type="week"], input[type="month"], input[type="file"]').not('.btn, [type="hidden"], [type="radio"], [type="checkbox"], [type="submit"]').addClass("form-input");
            $scope.find("select").addClass("form-select");
            $scope.find("textarea").addClass("form-textarea min-h-24");
            $scope.find('input[type="checkbox"]').addClass("form-checkbox");
            $scope.find('input[type="radio"]').addClass("form-radio rounded-full!");
            $scope.find("a.btn_frmline, .server_config_views button, .btn_01, .btn_02, .btn_03, .btn_submit").addClass("btn btn-sm border-default-300").removeClass("btn_01 btn_02 btn_03");
        }

        function applyCommonFormElements($scope) {
            if (!$scope || !$scope.length) {
                return;
            }

            $scope.find("label").addClass("form-label");
            $scope.find("input.frm_input, input.tbl_input, input[type='text'], input[type='password'], input[type='email'], input[type='number'], input[type='url'], input[type='search'], input[type='tel'], input[type='date'], input[type='time'], input[type='week'], input[type='month'], input[type='file']")
                .not(".btn, [type='hidden'], [type='radio'], [type='checkbox'], [type='submit']")
                .addClass("form-input");
            $scope.find("select").addClass("form-select");
            $scope.find("textarea").addClass("form-textarea");
            $scope.find("input[type='checkbox']").addClass("form-checkbox");
            $scope.find("input[type='radio']").addClass("form-radio rounded-full!");
            $scope.find("a.btn, button.btn, .btn_submit, .btn_01, .btn_02, .btn_03, a.btn_frmline, .server_config_views button")
                .addClass("btn btn-sm border-default-300")
                .removeClass("btn_01 btn_02 btn_03");

            // On narrow screens, select text in table cells can be clipped.
            // Keep text readable for td > .form-select while preserving compact level selects.
            if (window.matchMedia("(max-width: 768px)").matches) {
                $scope.find("td > select.form-select, td select.form-select")
                    .not("select[id^='mb_level['], select[name^='mb_level[']")
                    .each(function() {
                        this.style.setProperty("width", "auto", "important");
                        this.style.setProperty("min-width", "7.25rem", "important");
                        this.style.setProperty("max-width", "100%", "important");
                        this.style.setProperty("padding-inline-end", "2rem", "important");
                        this.style.setProperty("text-overflow", "clip", "important");
                        this.style.setProperty("white-space", "nowrap", "important");
                    });
            }
        }

        function convertFormTablesToDiv($scope) {
            if (!$scope || !$scope.length) {
                return;
            }

            function copyCellMeta($from, $to) {
                var id = $from.attr("id");
                var cls = $from.attr("class");
                var style = $from.attr("style");

                if (id) {
                    $to.attr("id", id);
                }
                if (cls) {
                    $to.addClass(cls);
                }
                if (style) {
                    $to.attr("style", style);
                }
            }

            function buildPairRow($th, $td) {
                var $row = $('<div class="grid grid-cols-1 gap-1.5 lg:grid-cols-3 lg:gap-9 py-3 border-b border-default-300"></div>');
                var $labelCol = $('<div class="break-keep"></div>');
                var $valueCol = $('<div class="lg:col-span-2 text-sm text-default-700"></div>');

                copyCellMeta($th, $labelCol);
                copyCellMeta($td, $valueCol);

                $labelCol.append($th.contents());
                $valueCol.append($td.contents());

                if (!$labelCol.find("label").length) {
                    var $labelNode = $labelCol.find("strong, span, p, div").first();
                    if ($labelNode.length) {
                        $labelNode.addClass("form-label py-2 mb-0!");
                    } else {
                        $labelCol.wrapInner('<span class="form-label py-2 mb-0!"></span>');
                    }
                } else {
                    $labelCol.find("label").first().addClass("py-2 mb-0!");
                }

                $row.append($labelCol, $valueCol);
                return $row;
            }

            function buildWideRow($td) {
                var $row = $('<div class="grid grid-cols-1 gap-1.5 py-3 border-b border-default-300"></div>');
                var $valueCol = $('<div class="text-sm text-default-700"></div>');

                copyCellMeta($td, $valueCol);
                $valueCol.append($td.contents());
                $row.append($valueCol);

                return $row;
            }

            $scope.find(".tbl_frm01 table, .tbl_frm02 table").each(function() {
                var $table = $(this);
                if ($table.attr("data-form-div-converted") === "1") {
                    return;
                }
                if ($table.closest(".no-auto-div-form").length) {
                    return;
                }
                if (!$table.find("input, select, textarea, .btn_confirm, .btn_submit").length) {
                    return;
                }

                var $tbody = $table.children("tbody");
                var $rows = $tbody.length ? $tbody.children("tr") : $table.find("tr");
                if (!$rows.length) {
                    return;
                }

                var $formGrid = $('<div class="admin-form-grid space-y-0" data-form-div-converted="1"></div>');
                var $caption = $table.children("caption").first();
                if ($caption.length) {
                    $formGrid.append('<div class="sr-only">' + $caption.text() + '</div>');
                }

                $rows.each(function() {
                    var $tr = $(this);
                    var $cells = $tr.children("th, td");
                    if (!$cells.length) {
                        return;
                    }

                    var $pendingTh = null;
                    $cells.each(function() {
                        var $cell = $(this);
                        if ($cell.is("th")) {
                            $pendingTh = $cell;
                            return;
                        }

                        if ($pendingTh) {
                            $formGrid.append(buildPairRow($pendingTh, $cell));
                            $pendingTh = null;
                        } else {
                            $formGrid.append(buildWideRow($cell));
                        }
                    });

                    if ($pendingTh) {
                        var $labelOnlyRow = $('<div class="grid grid-cols-1 gap-1.5 py-3 border-b border-default-300"></div>');
                        var $labelOnlyCol = $('<div class="break-keep"></div>');
                        copyCellMeta($pendingTh, $labelOnlyCol);
                        $labelOnlyCol.append($pendingTh.contents());
                        if ($labelOnlyCol.find("label").length) {
                            $labelOnlyCol.find("label").addClass("form-label py-2 mb-0!");
                        } else {
                            var $labelOnlyNode = $labelOnlyCol.find("strong, span, p, div").first();
                            if ($labelOnlyNode.length) {
                                $labelOnlyNode.addClass("form-label py-2 mb-0!");
                            } else {
                                $labelOnlyCol.wrapInner('<span class="form-label py-2 mb-0!"></span>');
                            }
                        }
                        $labelOnlyRow.append($labelOnlyCol);
                        $formGrid.append($labelOnlyRow);
                    }
                });

                if ($formGrid.children().length) {
                    $table.attr("data-form-div-converted", "1");
                    $table.closest(".tbl_wrap").addClass("card p-4");
                    $table.replaceWith($formGrid);
                }
            });
        }

        function applyMemberGuideRefactor() {
            var $scope = $("#container .container_wr");
            if (!$scope.length) {
                return;
            }

            $scope.find(".local_ov").addClass("card p-4 mb-base flex flex-wrap items-center gap-2.5");
            $scope.find(".local_desc").addClass("card p-4 mb-base text-sm");
            $scope.find(".local_sch").addClass("card p-4 mb-base flex flex-wrap items-center gap-2.5");

            $scope.find(".ov_listall, .btn_ov01, .btn_ov02").addClass("btn btn-sm border-default-300");

            $scope.find("input.frm_input, input.tbl_input").not('.btn, [type="hidden"], [type="radio"], [type="checkbox"], [type="submit"]').addClass("form-input");
            $scope.find("select").addClass("form-select");
            $scope.find("select[id^='mb_level['], select[name^='mb_level[']").each(function() {
                this.style.setProperty("padding-inline-end", "1.5rem", "important");
                this.style.setProperty("padding-inline-start", "0.625rem", "important");
                this.style.minWidth = "4.5rem";
                this.style.width = "4.5rem";
            });
            $scope.find("textarea").addClass("form-textarea");
            $scope.find('input[type="checkbox"]').addClass("form-checkbox");
            $scope.find('input[type="radio"]').addClass("form-radio rounded-full!");

            $scope.find("a.btn, button.btn, .btn_submit, .btn_01, .btn_02, .btn_03").addClass("btn btn-sm border-default-300").removeClass("btn_01 btn_02 btn_03");

            $scope.find(".tbl_head01, .tbl_head02").addClass("card");
            $scope.find(".tbl_head01 > table, .tbl_head02 > table").addClass("w-full text-sm");
            $scope.find(".tbl_head01 caption, .tbl_head02 caption").addClass("sr-only");
            $scope.find(".tbl_head01 thead th, .tbl_head02 thead th").addClass("px-3 py-2 text-left text-xs font-semibold text-default-700 bg-default-100");
            $scope.find(".tbl_head01 tbody td, .tbl_head02 tbody td").addClass("px-3 py-2 align-top text-sm text-default-700");
            $scope.find(".tbl_head01 tbody tr, .tbl_head02 tbody tr").addClass("border-b border-default-300");
            $scope.find(".tbl_head01.tbl_wrap, .tbl_head02.tbl_wrap").addClass("overflow-x-auto");

            // Keep member tables readable on narrow screens: horizontal scroll + stable cell width.
            $scope.find(".tbl_head01, .tbl_head02").css("overflow", "visible");
            $scope.find(".tbl_head01.tbl_wrap, .tbl_head02.tbl_wrap").css({
                "overflow-x": "auto",
                "overflow-y": "hidden",
                "-webkit-overflow-scrolling": "touch"
            });
            $scope.find(".tbl_head01 > table, .tbl_head02 > table").css({
                "min-width": "980px",
                "width": "100%"
            });
            $scope.find(".tbl_head01 thead th, .tbl_head02 thead th, .tbl_head01 tbody td, .tbl_head02 tbody td").css("white-space", "nowrap");

            // Member certify column: keep radio + text on one line per option.
            $scope.find(".td_mbcert input[type='radio']").css("vertical-align", "middle");
            $scope.find(".td_mbcert label").css({
                "display": "inline-block",
                "margin-left": "6px",
                "margin-bottom": "0",
                "font-weight": "400"
            });
        }

        if (isEnvMenuPage) {
            applyEnvGuideRefactor();
        }
        if (isMemberMenuPage) {
            applyMemberGuideRefactor();
        }

        var $containerScope = $("#container .container_wr");
        applyCommonFormElements($containerScope);
        convertFormTablesToDiv($containerScope);
    });
</script>

<div id="wrapper" class="min-h-screen bg-slate-100 pt-16 lg:pl-72 dark:bg-slate-950">
    <div id="container" class="<?php echo $adm_menu_cookie['container']; ?> px-3 py-4 sm:px-5 lg:px-8">
        <h1 id="container_title" class="mb-4 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-2xl font-semibold text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-white"><?php echo $g5['title']; ?></h1>
        <div class="container_wr">
