<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_menu_find_by($call, $search_key)
{
    global $menu;

    static $cache_menu = array();

    if (empty($cache_menu)) {
        foreach ($menu as $arr1) {
            if (empty($arr1)) {
                continue;
            }
            foreach ($arr1 as $arr2) {
                if (empty($arr2)) {
                    continue;
                }

                $menu_key = isset($arr2[3]) ? $arr2[3] : '';
                if (empty($menu_key)) {
                    continue;
                }

                $cache_menu[$menu_key] = array(
                    'sub_menu' => $arr2[0],
                    'title' => $arr2[1],
                    'link' => $arr2[2],
                );
            }
        }
    }

    if (isset($cache_menu[$call]) && isset($cache_menu[$call][$search_key])) {
        return $cache_menu[$call][$search_key];
    }

    return '';
}

function admin_menu_icon_id($menu_code)
{
    $prefix = substr((string) $menu_code, 0, 3);
    $map = array(
        '100' => 'settings',
        '200' => 'users',
        '300' => 'content',
        '400' => 'folder',
        '500' => 'stats',
        '900' => 'message',
    );

    return isset($map[$prefix]) ? $map[$prefix] : 'folder';
}

function admin_menu_is_readable(array $auth, $is_admin, $menu_code)
{
    return $is_admin == 'super' || (array_key_exists($menu_code, $auth) && strstr($auth[$menu_code], 'r'));
}

function admin_build_head_submenu_items(array $menu_group, array $auth, $is_admin, $sub_menu)
{
    $submenu_items = array();

    foreach ($menu_group as $index => $menu_item) {
        if ($index === 0 || !isset($menu_item[0], $menu_item[1], $menu_item[2])) {
            continue;
        }

        if (!admin_menu_is_readable($auth, $is_admin, $menu_item[0])) {
            continue;
        }

        $submenu_items[] = array(
            'menu_code' => (string) $menu_item[0],
            'title' => (string) $menu_item[1],
            'href' => (string) $menu_item[2],
            'is_current' => ((string) $menu_item[0] === (string) $sub_menu),
            'item_class' => ((string) $menu_item[0] === (string) $sub_menu) ? ' is-current' : '',
        );
    }

    return $submenu_items;
}

function admin_build_head_navigation(array $amenu, array $menu, array $auth, $is_admin, $sub_menu)
{
    $navigation_items = array();

    foreach ($amenu as $key => $value) {
        $group_key = 'menu' . $key;
        if (!isset($menu[$group_key][0][0], $menu[$group_key][0][1], $menu[$group_key][0][2]) || !$menu[$group_key][0][2]) {
            continue;
        }

        $menu_group = $menu[$group_key];
        $menu_code = (string) $menu_group[0][0];
        $submenu_items = admin_build_head_submenu_items($menu_group, $auth, $is_admin, $sub_menu);

        $is_open = (substr((string) $sub_menu, 0, 3) === substr($menu_code, 0, 3));

        $navigation_items[] = array(
            'menu_key' => $group_key,
            'title' => (string) $menu_group[0][1],
            'icon_id' => admin_menu_icon_id($menu_code),
            'is_open' => $is_open,
            'item_class' => $is_open ? ' is-open' : '',
            'panel_class' => $is_open ? '' : ' hidden',
            'aria_expanded' => $is_open ? 'true' : 'false',
            'sub_items' => $submenu_items,
        );
    }

    return $navigation_items;
}

function admin_build_head_view(array $member, array $config, array $cookies, $admin_container_class = '', $admin_page_subtitle = '', array $amenu = array(), array $menu = array(), array $auth = array(), $is_admin = '', $sub_menu = '')
{
    $adm_menu_cookie = array(
        'container' => '',
        'gnb' => '',
        'btn_gnb' => '',
    );
    $admin_sidebar_collapsed = false;

    if (!empty($cookies['g5_admin_btn_gnb'])) {
        $admin_sidebar_collapsed = true;
        $adm_menu_cookie['container'] = 'container-small';
        $adm_menu_cookie['gnb'] = 'gnb_small';
        $adm_menu_cookie['btn_gnb'] = 'btn_gnb_open';
    }

    $admin_profile_raw_id = (string) (isset($member['mb_id']) ? $member['mb_id'] : '');
    $admin_profile_name = get_text((string) (isset($member['mb_nick']) ? $member['mb_nick'] : ''));
    $admin_profile_id = get_text($admin_profile_raw_id);
    $admin_profile_mail = !empty($member['mb_email']) ? get_text((string) $member['mb_email']) : ($admin_profile_id . '@admin');
    $admin_profile_seed = $admin_profile_name ?: $admin_profile_id;
    $admin_profile_initial = 'A';
    if ($admin_profile_seed !== '') {
        $admin_profile_initial = function_exists('mb_substr')
            ? mb_substr($admin_profile_seed, 0, 1, 'UTF-8')
            : substr($admin_profile_seed, 0, 1);
    }

    $admin_site_title = get_text((string) (isset($config['cf_title']) ? $config['cf_title'] : ''));
    if ($admin_site_title === '') {
        $admin_site_title = 'G5 AIF';
    }

    return array(
        'adm_menu_cookie' => $adm_menu_cookie,
        'admin_sidebar_collapsed' => $admin_sidebar_collapsed,
        'admin_profile_name' => $admin_profile_name,
        'admin_profile_id' => $admin_profile_id,
        'admin_profile_mail' => $admin_profile_mail,
        'admin_profile_initial' => $admin_profile_initial,
        'admin_profile_manage_url' => G5_ADMIN_URL . '/member_form.php?w=u&amp;mb_id=' . rawurlencode($admin_profile_raw_id),
        'admin_logout_url' => G5_MEMBER_URL . '/logout.php',
        'admin_home_url' => G5_URL . '/',
        'admin_site_title' => $admin_site_title,
        'admin_csrf_token_key' => function_exists('admin_csrf_token_key') ? admin_csrf_token_key() : '',
        'admin_navigation_items' => admin_build_head_navigation($amenu, $menu, $auth, $is_admin, $sub_menu),
        'admin_container_class_attr' => trim($adm_menu_cookie['container'] . ' ' . $admin_container_class),
        'admin_page_subtitle_text' => $admin_page_subtitle !== '' ? $admin_page_subtitle : '사이트 운영과 설정을 한 곳에서 관리하세요.',
    );
}

function admin_build_tail_view($is_admin)
{
    $admin_js_path = G5_ADMIN_PATH . '/admin.js';
    $server_input = function_exists('g5_get_runtime_server_input') ? g5_get_runtime_server_input() : array();
    $host = isset($server_input['HTTP_HOST']) ? get_text((string) $server_input['HTTP_HOST']) : '';

    return array(
        'copyright_host' => $host,
        'print_version' => ($is_admin == 'super') ? 'Version ' . G5_GNUBOARD_VER : '',
        'admin_js_src' => G5_ADMIN_URL . '/admin.js?ver=' . (is_file($admin_js_path) ? filemtime($admin_js_path) : G5_JS_VER),
    );
}

function admin_build_head_sub_view(array $g5, array $config, $is_member, $is_admin, array $member)
{
    $page_title = isset($g5['title']) ? $g5['title'] : '';
    if ($page_title === '') {
        $page_title = $config['cf_title'];
        $head_title = $page_title;
    } else {
        $head_title = implode(' | ', array_filter(array($page_title, $config['cf_title'])));
    }

    $page_title = strip_tags($page_title);
    $head_title = strip_tags($head_title);
    $pretendard_font_href = 'https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css';
    $common_css_ver = admin_file_version(G5_PATH . '/css/common.css', G5_CSS_VER);
    $admin_css_ver = admin_file_version(G5_ADMIN_PATH . '/css/admin.css', G5_CSS_VER);

    $login_status_text = '';
    if ($is_member) {
        $sr_admin_msg = $is_admin == 'super' ? '최고관리자 ' : '';
        $login_status_text = $sr_admin_msg . get_text($member['mb_nick']) . '님 로그인 중 ';
    }

    $g5_sca = '';
    if (isset($g5['request_context']['query_state']['sca']) && !is_array($g5['request_context']['query_state']['sca'])) {
        $g5_sca = (string) $g5['request_context']['query_state']['sca'];
    }

    return array(
        'page_title' => $page_title,
        'head_title' => $head_title,
        'pretendard_font_href' => $pretendard_font_href,
        'common_css_href' => run_replace('head_css_url', G5_CSS_URL . '/common.css?ver=' . $common_css_ver, G5_URL),
        'admin_css_href' => run_replace('head_css_url', G5_ADMIN_URL . '/css/admin.css?ver=' . $admin_css_ver, G5_URL),
        'sticky_anchor_tabs_ver' => admin_file_version(G5_PATH . '/js/ui-kit/ui-sticky-anchor-tabs.js', G5_JS_VER),
        'login_status_text' => $login_status_text,
        'member_logout_url' => G5_MEMBER_URL . '/logout.php',
        'g5_sca' => $g5_sca,
        'body_script' => isset($g5['body_script']) ? $g5['body_script'] : '',
        'js_globals' => array(
            'g5_url' => G5_URL,
            'g5_member_url' => G5_MEMBER_URL,
            'g5_is_member' => isset($is_member) ? $is_member : '',
            'g5_is_admin' => isset($is_admin) ? $is_admin : '',
            'g5_is_mobile' => G5_IS_MOBILE,
            'g5_sca' => $g5_sca,
            'g5_cookie_domain' => G5_COOKIE_DOMAIN,
            'g5_admin_url' => G5_ADMIN_URL,
        ),
    );
}
