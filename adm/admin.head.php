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

    $str = '<ul>';

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

        $str .= '<li data-menu="' . $menu[$key][$i][0] . '"><a href="' . $menu[$key][$i][2] . '">' . $menu[$key][$i][1] . '</a></li>';

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

<div id="to_content"><a href="#container">본문 바로가기</a></div>

<header id="hd">
    <h1><?php echo $config['cf_title']; ?></h1>

    <nav id="gnb" class="-translate-x-full <?php echo $adm_menu_cookie['gnb']; ?>">
        <h2>
            <span>G</span>
            <span>G5 AIF</span>
        </h2>

        <ul class="gnb_ul">
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
                <li class="gnb_li <?php echo $current_class; ?>">
                    <button type="button" class="btn_op <?php echo $key; ?> <?php echo $jj; ?>" title="<?php echo $button_title; ?>">
                        <span><?php echo $button_title; ?></span>
                        <span class="menu-mark"><?php echo $mark; ?></span>
                    </button>
                    <div class="gnb_oparea_wr <?php echo $opened_utility; ?>">
                        <div>
                            <h3><?php echo $menu['menu' . $key][0][1]; ?></h3>
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

    <div id="adminSidebarBackdrop" class="hidden"></div>

    <div id="hd_top">
        <button type="button" id="btn_gnb_mobile" aria-controls="gnb" aria-expanded="false">☰</button>
        <button type="button" id="btn_gnb" class="hidden <?php echo $adm_menu_cookie['btn_gnb']; ?>">메뉴</button>

        <div id="logo" class="hidden">
            <a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">Dashboard</a>
        </div>

        <div id="tnb">
            <ul>
                <li class="tnb_li"><button type="button" id="admin_theme_toggle" aria-pressed="false">Dark</button></li>
                <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
                    <li class="tnb_li"><a href="<?php echo G5_SHOP_URL; ?>/" target="_blank" title="쇼핑몰 바로가기">쇼핑몰</a></li>
                <?php } ?>
                <li class="tnb_li hidden"><a href="<?php echo G5_URL; ?>/" target="_blank" title="커뮤니티 바로가기">커뮤니티</a></li>
                <li class="tnb_li hidden"><a href="<?php echo G5_ADMIN_URL; ?>/service.php">부가서비스</a></li>
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
</header>

<script>
    jQuery(function($) {
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

    });
</script>

<div id="wrapper">
    <div id="container" class="<?php echo $adm_menu_cookie['container']; ?>">
        <h1 id="container_title"><?php echo $g5['title']; ?></h1>
        <div>
