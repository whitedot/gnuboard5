<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

admin_enqueue_extend_stylesheets();

require_once G5_ADMIN_PATH . '/head.sub.admin.php';

$admin_head_view = admin_build_head_view(
    $member,
    $config,
    $_COOKIE,
    isset($admin_container_class) ? $admin_container_class : '',
    isset($admin_page_subtitle) ? $admin_page_subtitle : '',
    isset($amenu) && is_array($amenu) ? $amenu : array(),
    isset($menu) && is_array($menu) ? $menu : array(),
    isset($auth) && is_array($auth) ? $auth : array(),
    isset($is_admin) ? $is_admin : '',
    isset($sub_menu) ? $sub_menu : ''
);
$adm_menu_cookie = $admin_head_view['adm_menu_cookie'];
$admin_sidebar_collapsed = $admin_head_view['admin_sidebar_collapsed'];
$admin_profile_name = $admin_head_view['admin_profile_name'];
$admin_profile_id = $admin_head_view['admin_profile_id'];
$admin_profile_mail = $admin_head_view['admin_profile_mail'];
$admin_profile_initial = $admin_head_view['admin_profile_initial'];
$admin_site_title = $admin_head_view['admin_site_title'];
$admin_navigation_items = $admin_head_view['admin_navigation_items'];
$admin_container_class_attr = $admin_head_view['admin_container_class_attr'];
$admin_page_subtitle_text = $admin_head_view['admin_page_subtitle_text'];
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
            <symbol id="admin-menu-icon-admin-mode" viewBox="0 0 24 24">
                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"></path>
                <path d="M11 11a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                <path d="M12 12l0 2.5"></path>
            </symbol>
            <symbol id="admin-menu-icon-users" viewBox="0 0 24 24">
                <path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
            </symbol>
            <symbol id="admin-menu-icon-user" viewBox="0 0 24 24">
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
            </symbol>
            <symbol id="admin-menu-icon-content" viewBox="0 0 24 24">
                <path d="M5 4h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1"></path>
                <path d="M5 16h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1"></path>
                <path d="M15 12h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1"></path>
                <path d="M15 4h4a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1"></path>
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
            <symbol id="admin-menu-icon-article" viewBox="0 0 24 24">
                <path d="M3 6a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2l0 -12"></path>
                <path d="M7 8h10"></path>
                <path d="M7 12h10"></path>
                <path d="M7 16h10"></path>
            </symbol>
            <symbol id="admin-menu-icon-home" viewBox="0 0 24 24">
                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                <path d="M5 12v7a2 2 0 0 0 2 2h3m4 0h3a2 2 0 0 0 2 -2v-7"></path>
                <path d="M10 12h4v9h-4z"></path>
            </symbol>
            <symbol id="admin-menu-icon-logout" viewBox="0 0 24 24">
                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-5a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5a2 2 0 0 0 2 -2v-2"></path>
                <path d="M9 12h12l-3 -3"></path>
                <path d="M18 15l3 -3"></path>
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
            <symbol id="admin-menu-icon-moon-stars" viewBox="0 0 24 24">
                <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454l0 .008"></path>
                <path d="M17 4a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2"></path>
                <path d="M19 11h2m-1 -1v2"></path>
            </symbol>
            <symbol id="admin-menu-icon-sun" viewBox="0 0 24 24">
                <path d="M8 12a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path>
            </symbol>
            <symbol id="admin-menu-icon-chevron-down" viewBox="0 0 24 24">
                <path d="M6 9l6 6l6 -6"></path>
            </symbol>
        </svg>

        <h2>
            <a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">
                <span aria-hidden="true">
                    <svg class="admin-shell-control-icon" focusable="false" viewBox="0 0 24 24">
                        <use href="#admin-menu-icon-admin-mode"></use>
                    </svg>
                </span>
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
                    foreach ($admin_navigation_items as $nav_item) {
                        $current_class = $nav_item['is_open'] ? ' is-open' : '';
                        $opened_utility = $nav_item['is_open'] ? '' : ' hidden';
                        $expanded = $nav_item['is_open'] ? 'true' : 'false';
                    ?>
                        <li class="admin-nav-item<?php echo $current_class; ?>">
                            <button type="button" class="admin-nav-trigger" title="<?php echo $nav_item['title']; ?>" aria-expanded="<?php echo $expanded; ?>">
                                <span class="admin-nav-trigger-main">
                                    <svg class="admin-nav-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                        <use href="#admin-menu-icon-<?php echo $nav_item['icon_id']; ?>"></use>
                                    </svg>
                                    <span class="admin-nav-trigger-label"><?php echo $nav_item['title']; ?></span>
                                </span>
                                <span class="admin-nav-caret" aria-hidden="true">
                                    <svg class="admin-nav-caret-icon" focusable="false" viewBox="0 0 24 24">
                                        <use href="#admin-menu-icon-chevron-down"></use>
                                    </svg>
                                </span>
                            </button>
                            <div class="admin-nav-panel<?php echo $opened_utility; ?>">
                                <ul class="admin-nav-sub-list">
                                    <?php foreach ($nav_item['sub_items'] as $sub_item) { ?>
                                        <li class="admin-nav-sub-item<?php echo $sub_item['is_current'] ? ' is-current' : ''; ?>" data-menu="<?php echo $sub_item['menu_code']; ?>">
                                            <a href="<?php echo $sub_item['href']; ?>"><?php echo $sub_item['title']; ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
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
            <a class="gnb_profile_logout" href="<?php echo G5_MEMBER_URL; ?>/logout.php" title="로그아웃" aria-label="로그아웃">
                <svg class="admin-shell-control-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                    <use href="#admin-menu-icon-logout"></use>
                </svg>
            </a>
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
            <div id="tnb">
                <ul>
                    <li class="tnb_li">
                        <button type="button" id="admin_theme_toggle" class="tnb_icon_btn" aria-pressed="false" aria-label="다크 모드 전환" title="다크 모드 전환">
                            <svg class="admin-shell-control-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                <use id="admin_theme_toggle_icon_use" href="#admin-menu-icon-moon-stars"></use>
                            </svg>
                        </button>
                    </li>
                    <li class="tnb_li">
                        <a class="tnb_icon_btn" href="<?php echo G5_URL; ?>/" target="_blank" title="메인" aria-label="메인">
                            <svg class="admin-shell-control-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                <use href="#admin-menu-icon-home"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="tnb_li relative">
                        <button type="button" class="tnb_mb_btn tnb_icon_btn" aria-label="관리자 메뉴" title="관리자 메뉴">
                            <svg class="admin-shell-control-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24">
                                <use href="#admin-menu-icon-user"></use>
                            </svg>
                        </button>
                        <ul class="tnb_mb_area hidden">
                            <li><a href="<?php echo G5_ADMIN_URL; ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id']; ?>">관리자정보</a></li>
                            <li id="tnb_logout"><a href="<?php echo G5_MEMBER_URL; ?>/logout.php">로그아웃</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="container" class="<?php echo $admin_container_class_attr; ?>">
        <h1 id="container_title"><?php echo $g5['title']; ?></h1>
        <p id="container_subtitle"><?php echo $admin_page_subtitle_text; ?></p>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var menu_cookie_key = "g5_admin_btn_gnb";
        var mobileQuery = window.matchMedia("(max-width: 1023px)");
        var body = document.body;
        var gnb = document.getElementById("gnb");
        var container = document.getElementById("container");
        var desktopToggle = document.getElementById("btn_gnb");
        var mobileToggle = document.getElementById("btn_gnb_mobile");
        var sidebarBackdrop = document.getElementById("adminSidebarBackdrop");
        var profileButton = document.querySelector(".tnb_mb_btn");
        var profileMenu = document.querySelector(".tnb_mb_area");
        var scrollWrap = document.querySelector("#gnb .gnb_menu_scroll_wrap");
        var menuScroll = document.getElementById("gnbMenuScroll");
        var scrollbar = scrollWrap ? scrollWrap.querySelector(".gnb_scrollbar") : null;
        var scrollThumb = scrollWrap ? scrollWrap.querySelector(".gnb_scrollbar_thumb") : null;
        var themeToggle = document.getElementById("admin_theme_toggle");
        var themeToggleIconUse = document.getElementById("admin_theme_toggle_icon_use");
        var navRoot = document.getElementById("adminNavList");
        var hideScrollbarTimer = null;

        function isMobileViewport() {
            return mobileQuery.matches;
        }

        if (profileButton && profileMenu) {
            profileButton.addEventListener("click", function() {
                profileMenu.classList.toggle("hidden");
            });

            document.addEventListener("click", function(event) {
                if (!event.target.closest(".tnb_li.relative")) {
                    profileMenu.classList.add("hidden");
                }
            });
        }

        function syncDesktopSidebarState() {
            if (!gnb || !container || !desktopToggle) {
                return;
            }

            var collapsed = gnb.classList.contains("gnb_small");
            var desktopCollapsed = !isMobileViewport() && collapsed;
            body.classList.toggle("admin-sidebar-condensed", desktopCollapsed);
            container.classList.toggle("container-small", desktopCollapsed);
            desktopToggle.classList.toggle("btn_gnb_open", desktopCollapsed);
            desktopToggle.setAttribute("aria-pressed", desktopCollapsed ? "true" : "false");
        }

        function setDesktopCollapsed(nextCollapsed) {
            try {
                if (nextCollapsed) {
                    set_cookie(menu_cookie_key, 1, 60 * 60 * 24 * 365);
                } else {
                    delete_cookie(menu_cookie_key);
                }
            } catch (err) {}

            if (gnb) {
                gnb.classList.toggle("gnb_small", nextCollapsed);
            }
            syncDesktopSidebarState();
        }

        function setMobileSidebar(opened) {
            if (!isMobileViewport()) {
                return;
            }

            body.classList.toggle("admin-sidebar-open", opened);
            body.classList.toggle("overflow-hidden", opened);

            if (mobileToggle) {
                mobileToggle.setAttribute("aria-expanded", opened ? "true" : "false");
            }

            if (sidebarBackdrop) {
                sidebarBackdrop.classList.toggle("hidden", !opened);
            }
        }

        if (desktopToggle) {
            desktopToggle.addEventListener("click", function() {
                var nextCollapsed = !(gnb && gnb.classList.contains("gnb_small"));
                setDesktopCollapsed(nextCollapsed);
            });
        }

        if (mobileToggle) {
            mobileToggle.addEventListener("click", function(event) {
                event.preventDefault();
                event.stopPropagation();

                if (isMobileViewport()) {
                    setMobileSidebar(!body.classList.contains("admin-sidebar-open"));
                    return;
                }

                if (gnb && gnb.classList.contains("gnb_small")) {
                    setDesktopCollapsed(false);
                }
            });
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener("click", function() {
                setMobileSidebar(false);
            });
        }

        window.addEventListener("resize", function() {
            if (!isMobileViewport()) {
                body.classList.remove("admin-sidebar-open", "overflow-hidden");
                if (mobileToggle) {
                    mobileToggle.setAttribute("aria-expanded", "false");
                }
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.add("hidden");
                }
            }

            syncDesktopSidebarState();
            updateMenuScrollbar();
        });

        if (gnb) {
            gnb.addEventListener("click", function(event) {
                if (isMobileViewport() && event.target.closest("a")) {
                    setMobileSidebar(false);
                }
            });
        }

        function updateMenuScrollbar() {
            if (!scrollWrap || !menuScroll || !scrollbar || !scrollThumb) {
                return;
            }

            var scrollHeight = menuScroll.scrollHeight;
            var clientHeight = menuScroll.clientHeight;
            var canScroll = scrollHeight > clientHeight + 1;

            scrollWrap.classList.toggle("is-scrollable", canScroll);

            if (!canScroll) {
                scrollThumb.style.height = "0";
                scrollThumb.style.transform = "translateY(0)";
                return;
            }

            var trackHeight = scrollbar.getBoundingClientRect().height;
            var thumbHeight = Math.max(28, Math.round(trackHeight * (clientHeight / scrollHeight)));
            var maxThumbTop = Math.max(0, trackHeight - thumbHeight);
            var maxScrollTop = Math.max(1, scrollHeight - clientHeight);
            var thumbTop = Math.round((menuScroll.scrollTop / maxScrollTop) * maxThumbTop);

            scrollThumb.style.height = thumbHeight + "px";
            scrollThumb.style.transform = "translateY(" + thumbTop + "px)";
        }

        function showMenuScrollbar() {
            if (!scrollWrap || !scrollWrap.classList.contains("is-scrollable")) {
                return;
            }

            clearTimeout(hideScrollbarTimer);
            scrollWrap.classList.add("is-scrollbar-visible");
        }

        function hideMenuScrollbar(delay) {
            if (!scrollWrap) {
                return;
            }

            clearTimeout(hideScrollbarTimer);
            hideScrollbarTimer = window.setTimeout(function() {
                scrollWrap.classList.remove("is-scrollbar-visible");
            }, delay || 140);
        }

        if (menuScroll) {
            menuScroll.addEventListener("scroll", function() {
                updateMenuScrollbar();
                showMenuScrollbar();
                hideMenuScrollbar(420);
            });
        }

        if (scrollWrap) {
            scrollWrap.addEventListener("mouseenter", function() {
                updateMenuScrollbar();
                showMenuScrollbar();
            });

            scrollWrap.addEventListener("mouseleave", function() {
                hideMenuScrollbar(120);
            });

            scrollWrap.addEventListener("focusin", function() {
                updateMenuScrollbar();
                showMenuScrollbar();
            });

            scrollWrap.addEventListener("focusout", function() {
                window.setTimeout(function() {
                    if (!scrollWrap.contains(document.activeElement)) {
                        hideMenuScrollbar(120);
                    }
                }, 0);
            });
        }

        var themeKey = "g5_admin_theme";
        function syncThemeUI() {
            if (!themeToggle || !themeToggleIconUse) {
                return;
            }

            var dark = document.documentElement.getAttribute("data-theme") === "dark";
            var nextModeLabel = dark ? "라이트 모드" : "다크 모드";
            var iconHref = dark ? "#admin-menu-icon-sun" : "#admin-menu-icon-moon-stars";
            themeToggle.setAttribute("aria-pressed", dark ? "true" : "false");
            themeToggle.setAttribute("aria-label", nextModeLabel + " 전환");
            themeToggle.setAttribute("title", nextModeLabel + " 전환");
            themeToggleIconUse.setAttribute("href", iconHref);
            themeToggleIconUse.setAttribute("xlink:href", iconHref);
        }

        if (themeToggle) {
            themeToggle.addEventListener("click", function() {
                var dark = document.documentElement.getAttribute("data-theme") === "dark";
                var next = dark ? "light" : "dark";
                document.documentElement.setAttribute("data-theme", next);
                try {
                    localStorage.setItem(themeKey, next);
                } catch (e) {}
                syncThemeUI();
            });
        }

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

        syncDesktopSidebarState();
        syncThemeUI();
        updateMenuScrollbar();
        window.requestAnimationFrame(updateMenuScrollbar);
    });
</script>
