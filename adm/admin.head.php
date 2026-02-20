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

function admin_menu_icon_id($menu_code)
{
    $prefix = substr((string) $menu_code, 0, 3);
    $map = array(
        '100' => 'settings',
        '200' => 'users',
        '300' => 'board',
        '400' => 'shop',
        '500' => 'stats',
        '900' => 'message',
    );

    return isset($map[$prefix]) ? $map[$prefix] : 'folder';
}

function print_menu2($key, $no = '')
{
    global $menu, $auth_menu, $is_admin, $auth, $sub_menu;

    $str = '<ul class="admin-nav-sub-list">';

    for ($i = 1; $i < count($menu[$key]); $i++) {
        if (!isset($menu[$key][$i])) {
            continue;
        }

        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0], $auth) || !strstr($auth[$menu[$key][$i][0]], 'r'))) {
            continue;
        }

        $current_class = '';
        if ($menu[$key][$i][0] == $sub_menu) {
            $current_class = ' is-current';
        }

        $str .= '<li class="admin-nav-sub-item' . $current_class . '" data-menu="' . $menu[$key][$i][0] . '"><a href="' . $menu[$key][$i][2] . '">' . $menu[$key][$i][1] . '</a></li>';

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
$admin_sidebar_collapsed = false;

if (!empty($_COOKIE['g5_admin_btn_gnb'])) {
    $admin_sidebar_collapsed = true;
    $adm_menu_cookie['container'] = 'container-small';
    $adm_menu_cookie['gnb'] = 'gnb_small';
    $adm_menu_cookie['btn_gnb'] = 'btn_gnb_open';
}

$admin_profile_name = get_text((string) $member['mb_nick']);
$admin_profile_id = get_text((string) $member['mb_id']);
$admin_profile_mail = !empty($member['mb_email']) ? get_text((string) $member['mb_email']) : ($admin_profile_id . '@admin');
$admin_profile_seed = $admin_profile_name ?: $admin_profile_id;
$admin_profile_initial = 'A';
if ($admin_profile_seed !== '') {
    if (function_exists('mb_substr')) {
        $admin_profile_initial = mb_substr($admin_profile_seed, 0, 1, 'UTF-8');
    } else {
        $admin_profile_initial = substr($admin_profile_seed, 0, 1);
    }
}

$admin_site_title = get_text((string) ($config['cf_title'] ?? ''));
if ($admin_site_title === '') {
    $admin_site_title = 'G5 AIF';
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

<?php if ($admin_sidebar_collapsed) { ?>
<script>
    (function() {
        if (window.matchMedia("(max-width: 1023px)").matches) {
            return;
        }
        if (document.body) {
            document.body.classList.add("admin-sidebar-condensed");
        }
    })();
</script>
<?php } ?>

<div id="to_content"><a href="#container">본문 바로가기</a></div>

<header id="hd">
    <h1 class="sr-only"><?php echo $config['cf_title']; ?></h1>

    <nav id="gnb" class="<?php echo $adm_menu_cookie['gnb']; ?>" aria-label="관리자 메뉴">
        <svg class="admin-nav-icon-sprite" aria-hidden="true" focusable="false">
            <symbol id="admin-menu-icon-settings" viewBox="0 0 24 24">
                <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065"></path>
                <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
            </symbol>
            <symbol id="admin-menu-icon-users" viewBox="0 0 24 24">
                <path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
            </symbol>
            <symbol id="admin-menu-icon-board" viewBox="0 0 24 24">
                <path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1"></path>
                <path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1"></path>
                <path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1"></path>
                <path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1"></path>
            </symbol>
            <symbol id="admin-menu-icon-shop" viewBox="0 0 24 24">
                <path d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304"></path>
                <path d="M9 11v-5a3 3 0 0 1 6 0v5"></path>
            </symbol>
            <symbol id="admin-menu-icon-stats" viewBox="0 0 24 24">
                <path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -6"></path>
                <path d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -10"></path>
                <path d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -14"></path>
                <path d="M4 20h14"></path>
            </symbol>
            <symbol id="admin-menu-icon-message" viewBox="0 0 24 24">
                <path d="M3 20l1.3 -3.9c-2.324 -3.437 -1.426 -7.872 2.1 -10.374c3.526 -2.501 8.59 -2.296 11.845 .48c3.255 2.777 3.695 7.266 1.029 10.501c-2.666 3.235 -7.615 4.215 -11.574 2.293l-4.7 1"></path>
            </symbol>
            <symbol id="admin-menu-icon-folder" viewBox="0 0 24 24">
                <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path>
            </symbol>
            <symbol id="admin-menu-icon-sidebar-toggle" viewBox="0 0 24 24">
                <path d="M4 6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12"></path>
                <path d="M9 4v16"></path>
                <path d="M15 10l-2 2l2 2"></path>
            </symbol>
            <symbol id="admin-menu-icon-menu" viewBox="0 0 24 24">
                <path d="M4 6l16 0"></path>
                <path d="M4 12l16 0"></path>
                <path d="M4 18l16 0"></path>
            </symbol>
            <symbol id="admin-menu-icon-chevron-down" viewBox="0 0 24 24">
                <path d="M6 9l6 6l6 -6"></path>
            </symbol>
        </svg>

        <h2>
            <a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">
                <span><?php echo $admin_profile_initial; ?></span>
                <span><?php echo $admin_site_title; ?></span>
            </a>
            <button type="button" id="btn_gnb" class="<?php echo $adm_menu_cookie['btn_gnb']; ?>" aria-label="사이드바 축소/확장" aria-pressed="false">
                <span aria-hidden="true">
                    <svg class="admin-shell-control-icon" focusable="false" viewBox="0 0 24 24">
                        <use href="#admin-menu-icon-sidebar-toggle"></use>
                    </svg>
                </span>
            </button>
        </h2>

        <div class="gnb_menu_scroll_wrap">
            <div class="gnb_menu_scroll" id="gnbMenuScroll">
                <ul class="admin-nav-list" id="adminNavList">
                    <?php
                    foreach ($amenu as $key => $value) {
                        if (!isset($menu['menu' . $key][0][2]) || !$menu['menu' . $key][0][2]) {
                            continue;
                        }

                        $current_class = '';
                        $opened_utility = ' hidden';
                        $expanded = 'false';
                        if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu' . $key][0][0], 0, 3))) {
                            $current_class = ' is-open';
                            $opened_utility = '';
                            $expanded = 'true';
                        }

                        $button_title = $menu['menu' . $key][0][1];
                        $menu_code = (string) $menu['menu' . $key][0][0];
                        $menu_icon_id = admin_menu_icon_id($menu_code);
                    ?>
                        <li class="admin-nav-item<?php echo $current_class; ?>">
                            <button type="button" class="admin-nav-trigger" title="<?php echo $button_title; ?>" aria-expanded="<?php echo $expanded; ?>">
                                <span class="admin-nav-trigger-main">
                                    <svg class="admin-nav-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                        <use href="#admin-menu-icon-<?php echo $menu_icon_id; ?>"></use>
                                    </svg>
                                    <span class="admin-nav-trigger-label"><?php echo $button_title; ?></span>
                                </span>
                                <span class="admin-nav-caret" aria-hidden="true">
                                    <svg class="admin-nav-caret-icon" focusable="false" viewBox="0 0 24 24">
                                        <use href="#admin-menu-icon-chevron-down"></use>
                                    </svg>
                                </span>
                            </button>
                            <div class="admin-nav-panel<?php echo $opened_utility; ?>">
                                <?php echo print_menu1('menu' . $key, 1); ?>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="gnb_scrollbar" aria-hidden="true">
                <div class="gnb_scrollbar_thumb"></div>
            </div>
        </div>

        <div class="gnb_profile">
            <div class="gnb_profile_avatar"><?php echo $admin_profile_initial; ?></div>
            <div class="gnb_profile_meta">
                <strong><?php echo $admin_profile_name ?: $admin_profile_id; ?></strong>
                <span><?php echo $admin_profile_mail; ?></span>
            </div>
            <a class="gnb_profile_logout" href="<?php echo G5_BBS_URL; ?>/logout.php" title="로그아웃">↗</a>
        </div>
    </nav>

    <div id="adminSidebarBackdrop" class="hidden"></div>
</header>

<div id="wrapper">
    <div id="hd_top">
        <div class="hd_top_left">
            <button type="button" id="btn_gnb_mobile" aria-controls="gnb" aria-expanded="false" aria-label="메뉴 열기">
                <svg class="admin-shell-control-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                    <use href="#admin-menu-icon-menu"></use>
                </svg>
            </button>
            <div class="hd_breadcrumb">
                <span>대시보드</span>
                <span>/</span>
                <strong><?php echo $g5['title']; ?></strong>
            </div>
        </div>

        <div class="hd_top_right">
            <label class="hd_search">
                <span>⌕</span>
                <input type="search" placeholder="Search anything..." aria-label="관리자 메뉴 검색">
            </label>

            <div id="tnb">
                <ul>
                    <li class="tnb_li"><button type="button" id="admin_theme_toggle" aria-pressed="false">Dark</button></li>
                    <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
                        <li class="tnb_li"><a href="<?php echo G5_SHOP_URL; ?>/" target="_blank" title="쇼핑몰 바로가기">쇼핑몰</a></li>
                    <?php } ?>
                    <li class="tnb_li"><a href="<?php echo G5_URL; ?>/" target="_blank" title="커뮤니티 바로가기">커뮤니티</a></li>
                    <li class="tnb_li"><a href="<?php echo G5_ADMIN_URL; ?>/service.php">부가서비스</a></li>
                    <li class="tnb_li relative">
                        <button type="button" class="tnb_mb_btn">관리자</button>
                        <ul class="tnb_mb_area hidden">
                            <li><a href="<?php echo G5_ADMIN_URL; ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id']; ?>">관리자정보</a></li>
                            <li id="tnb_logout"><a href="<?php echo G5_BBS_URL; ?>/logout.php">로그아웃</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="container" class="<?php echo $adm_menu_cookie['container']; ?>">
        <h1 id="container_title"><?php echo $g5['title']; ?></h1>
        <p id="container_subtitle">사이트 운영과 설정을 한 곳에서 관리하세요.</p>
        <div class="admin-content-shell">

<script>
    jQuery(function($) {
        var menu_cookie_key = 'g5_admin_btn_gnb';
        var mobileQuery = window.matchMedia("(max-width: 1023px)");

        function isMobileViewport() {
            return mobileQuery.matches;
        }

        $(".tnb_mb_btn").click(function() {
            $(".tnb_mb_area").toggleClass("hidden");
        });

        $(document).on("click", function(e) {
            if (!$(e.target).closest(".tnb_li.relative").length) {
                $(".tnb_mb_area").addClass("hidden");
            }
        });

        function syncDesktopSidebarState() {
            var collapsed = $("#gnb").hasClass("gnb_small");
            var desktopCollapsed = !isMobileViewport() && collapsed;
            $("body").toggleClass("admin-sidebar-condensed", desktopCollapsed);
            $("#container").toggleClass("container-small", desktopCollapsed);
            $("#btn_gnb")
                .toggleClass("btn_gnb_open", desktopCollapsed)
                .attr("aria-pressed", desktopCollapsed ? "true" : "false");
        }

        function setDesktopCollapsed(nextCollapsed) {
            try {
                if (nextCollapsed) {
                    set_cookie(menu_cookie_key, 1, 60 * 60 * 24 * 365);
                } else {
                    delete_cookie(menu_cookie_key);
                }
            } catch (err) {}

            $("#gnb").toggleClass("gnb_small", nextCollapsed);
            syncDesktopSidebarState();
        }

        syncDesktopSidebarState();

        $("#btn_gnb").click(function() {
            var nextCollapsed = !$("#gnb").hasClass("gnb_small");
            setDesktopCollapsed(nextCollapsed);
        });

        function setMobileSidebar(opened) {
            if (!isMobileViewport()) {
                return;
            }

            $("body").toggleClass("admin-sidebar-open", opened);
            $("body").toggleClass("overflow-hidden", opened);
            $("#btn_gnb_mobile").attr("aria-expanded", opened ? "true" : "false");
            $("#adminSidebarBackdrop").toggleClass("hidden", !opened);
        }

        $("#btn_gnb_mobile").click(function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (isMobileViewport()) {
                var opened = !$("body").hasClass("admin-sidebar-open");
                setMobileSidebar(opened);
                return;
            }

            if ($("#gnb").hasClass("gnb_small")) {
                setDesktopCollapsed(false);
            }
        });

        $("#adminSidebarBackdrop").click(function() {
            setMobileSidebar(false);
        });

        $(window).on("resize", function() {
            if (!isMobileViewport()) {
                $("body").removeClass("admin-sidebar-open overflow-hidden");
                $("#btn_gnb_mobile").attr("aria-expanded", "false");
                $("#adminSidebarBackdrop").addClass("hidden");
            }

            syncDesktopSidebarState();
        }).trigger("resize");

        $("#gnb").on("click", "a", function() {
            if (isMobileViewport()) {
                setMobileSidebar(false);
            }
        });

        var $scrollWrap = $("#gnb .gnb_menu_scroll_wrap");
        var $menuScroll = $("#gnbMenuScroll");
        var $scrollbar = $scrollWrap.find(".gnb_scrollbar");
        var $scrollThumb = $scrollWrap.find(".gnb_scrollbar_thumb");
        var hideScrollbarTimer = null;

        function updateMenuScrollbar() {
            if (!$menuScroll.length || !$scrollbar.length || !$scrollThumb.length) {
                return;
            }

            var el = $menuScroll.get(0);
            var scrollHeight = el.scrollHeight;
            var clientHeight = el.clientHeight;
            var canScroll = scrollHeight > clientHeight + 1;

            $scrollWrap.toggleClass("is-scrollable", canScroll);

            if (!canScroll) {
                $scrollThumb.css({
                    height: 0,
                    transform: "translateY(0)"
                });
                return;
            }

            var trackHeight = $scrollbar.innerHeight();
            var thumbHeight = Math.max(28, Math.round(trackHeight * (clientHeight / scrollHeight)));
            var maxThumbTop = Math.max(0, trackHeight - thumbHeight);
            var maxScrollTop = Math.max(1, scrollHeight - clientHeight);
            var thumbTop = Math.round((el.scrollTop / maxScrollTop) * maxThumbTop);

            $scrollThumb.css({
                height: thumbHeight + "px",
                transform: "translateY(" + thumbTop + "px)"
            });
        }

        function showMenuScrollbar() {
            if (!$scrollWrap.hasClass("is-scrollable")) {
                return;
            }

            clearTimeout(hideScrollbarTimer);
            $scrollWrap.addClass("is-scrollbar-visible");
        }

        function hideMenuScrollbar(delay) {
            clearTimeout(hideScrollbarTimer);
            hideScrollbarTimer = setTimeout(function() {
                $scrollWrap.removeClass("is-scrollbar-visible");
            }, delay || 140);
        }

        $menuScroll.on("scroll", function() {
            updateMenuScrollbar();
            showMenuScrollbar();
            hideMenuScrollbar(420);
        });

        $scrollWrap.on("mouseenter", function() {
            updateMenuScrollbar();
            showMenuScrollbar();
        });

        $scrollWrap.on("mouseleave", function() {
            hideMenuScrollbar(120);
        });

        $scrollWrap.on("focusin", function() {
            updateMenuScrollbar();
            showMenuScrollbar();
        });

        $scrollWrap.on("focusout", function() {
            setTimeout(function() {
                if ($scrollWrap.find(":focus").length === 0) {
                    hideMenuScrollbar(120);
                }
            }, 0);
        });

        $(window).on("resize", updateMenuScrollbar);
        window.requestAnimationFrame(updateMenuScrollbar);

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

        var navRoot = document.getElementById("adminNavList");
        function setNavItemState(item, opened) {
            if (!item) {
                return;
            }

            item.classList.toggle("is-open", opened);

            var panel = item.querySelector(".admin-nav-panel");
            if (panel) {
                panel.classList.toggle("hidden", !opened);
            }

            var trigger = item.querySelector(".admin-nav-trigger");
            if (trigger) {
                trigger.setAttribute("aria-expanded", opened ? "true" : "false");
            }
        }

        if (navRoot) {
            var navItems = Array.prototype.slice.call(navRoot.querySelectorAll(".admin-nav-item"));
            navItems.forEach(function(item) {
                setNavItemState(item, item.classList.contains("is-open"));
            });

            navRoot.addEventListener("click", function(event) {
                var trigger = event.target.closest(".admin-nav-trigger");
                if (!trigger || !navRoot.contains(trigger)) {
                    return;
                }

                var activeItem = trigger.closest(".admin-nav-item");
                if (!activeItem) {
                    return;
                }

                var willOpen = !activeItem.classList.contains("is-open");
                navItems.forEach(function(item) {
                    setNavItemState(item, item === activeItem ? willOpen : false);
                });

                updateMenuScrollbar();
            });
        }

    });
</script>
