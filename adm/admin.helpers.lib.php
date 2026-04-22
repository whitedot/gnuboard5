<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function get_skin_dir($skin, $skin_path = G5_SKIN_PATH)
{
    global $g5;

    $result_array = array();

    $dirname = $skin_path . '/' . $skin . '/';
    if (!is_dir($dirname)) {
        return array();
    }

    $handle = opendir($dirname);
    while ($file = readdir($handle)) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        if (is_dir($dirname . $file)) {
            $result_array[] = $file;
        }
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}

function get_member_level_select($name, $start_id = 0, $end_id = 10, $selected = "", $event = "")
{
    global $g5;

    $attr = trim((string) $event);
    if (strpos($attr, 'class=') === false) {
        $attr = trim('class="form-select" ' . $attr);
    } else {
        $attr = preg_replace('/class=("|\')(.*?)(\1)/', 'class=$1form-select $2$1', $attr, 1);
    }
    $str = "\n<select id=\"{$name}\" name=\"{$name}\"";
    if ($attr !== '') {
        $str .= " {$attr}";
    }
    $str .= ">\n";
    for ($i = $start_id; $i <= $end_id; $i++) {
        $str .= '<option value="' . $i . '"';
        if ($i == $selected) {
            $str .= ' selected="selected"';
        }
        $str .= ">{$i}</option>\n";
    }
    $str .= "</select>\n";
    return $str;
}

function get_member_id_select($name, $level, $selected = "", $event = "")
{
    global $g5;

    $sql = " select mb_id from {$g5['member_table']} where mb_level >= :mb_level ";
    $result = sql_query_prepared($sql, array(
        'mb_level' => (int) $level,
    ));
    $attr = trim((string) $event);
    if (strpos($attr, 'class=') === false) {
        $attr = trim('class="form-select" ' . $attr);
    } else {
        $attr = preg_replace('/class=("|\')(.*?)(\1)/', 'class=$1form-select $2$1', $attr, 1);
    }
    $str = '<select id="' . $name . '" name="' . $name . '"';
    if ($attr !== '') {
        $str .= ' ' . $attr;
    }
    $str .= '><option value="">선택안함</option>';
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $str .= '<option value="' . $row['mb_id'] . '"';
        if ($row['mb_id'] == $selected) {
            $str .= ' selected';
        }
        $str .= '>' . $row['mb_id'] . '</option>';
    }
    $str .= '</select>';
    return $str;
}

function admin_build_anchor_menu($tabs, $options = array())
{
    if (!is_array($tabs) || empty($tabs)) {
        return '';
    }

    $nav_id = isset($options['nav_id']) ? trim((string) $options['nav_id']) : '';
    $nav_class = isset($options['nav_class']) ? trim((string) $options['nav_class']) : 'tab-nav';
    $nav_aria_label = isset($options['nav_aria_label']) ? trim((string) $options['nav_aria_label']) : '탭 메뉴';
    $link_class = isset($options['link_class']) ? trim((string) $options['link_class']) : 'tab-trigger-line-primary';
    $active_class = isset($options['active_class']) ? trim((string) $options['active_class']) : 'active';
    $as_tabs = !empty($options['as_tabs']);
    $link_id_prefix = isset($options['link_id_prefix']) ? trim((string) $options['link_id_prefix']) : 'admin_tab_';

    $nav_attr = array();
    if ($nav_id !== '') {
        $nav_attr[] = 'id="' . htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8') . '"';
    }
    if ($nav_class !== '') {
        $nav_attr[] = 'class="' . htmlspecialchars($nav_class, ENT_QUOTES, 'UTF-8') . '"';
    }
    if ($nav_aria_label !== '') {
        $nav_attr[] = 'aria-label="' . htmlspecialchars($nav_aria_label, ENT_QUOTES, 'UTF-8') . '"';
    }
    if ($as_tabs) {
        $nav_attr[] = 'role="tablist"';
    }

    $menu = array();
    $menu[] = '<nav ' . implode(' ', $nav_attr) . '>';

    foreach ($tabs as $index => $tab) {
        if (!is_array($tab)) {
            continue;
        }

        $id = isset($tab['id']) ? trim((string) $tab['id']) : '';
        $label = isset($tab['label']) ? trim((string) $tab['label']) : '';

        if ($label === '') {
            continue;
        }

        $href = isset($tab['href']) ? trim((string) $tab['href']) : ($id !== '' ? '#' . $id : '#');
        $is_active = isset($tab['active']) ? (bool) $tab['active'] : ($index === 0);
        $item_class = $link_class;
        $link_attr = array();

        if ($is_active && $active_class !== '') {
            $item_class .= ' ' . $active_class;
        }

        $link_attr[] = 'class="' . htmlspecialchars(trim($item_class), ENT_QUOTES, 'UTF-8') . '"';
        $link_attr[] = 'href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"';
        $link_attr[] = 'aria-selected="' . ($is_active ? 'true' : 'false') . '"';

        if ($as_tabs) {
            $panel_id = ($href !== '' && $href[0] === '#') ? substr($href, 1) : ('panel_' . $index);
            $tab_id = $link_id_prefix . $panel_id;

            $link_attr[] = 'id="' . htmlspecialchars($tab_id, ENT_QUOTES, 'UTF-8') . '"';
            $link_attr[] = 'role="tab"';
            $link_attr[] = 'aria-controls="' . htmlspecialchars($panel_id, ENT_QUOTES, 'UTF-8') . '"';
            $link_attr[] = 'tabindex="' . ($is_active ? '0' : '-1') . '"';
        }

        $menu[] = '<a ' . implode(' ', $link_attr) . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
    }

    $menu[] = '</nav>';

    return implode(PHP_EOL, $menu);
}

function icon($act, $link = '', $target = '_parent')
{
    global $g5;

    $img = array('입력' => 'insert', '추가' => 'insert', '생성' => 'insert', '수정' => 'modify', '삭제' => 'delete', '이동' => 'move', '그룹' => 'move', '보기' => 'view', '미리보기' => 'view', '복사' => 'copy');
    $icon = '<img src="' . G5_ADMIN_PATH . '/img/icon_' . $img[$act] . '.gif" title="' . $act . '">';
    if ($link) {
        $s = '<a href="' . $link . '">' . $icon . '</a>';
    } else {
        $s = $icon;
    }
    return $s;
}

function rm_rf($file)
{
    if (file_exists($file)) {
        if (is_dir($file)) {
            $handle = opendir($file);
            while ($filename = readdir($handle)) {
                if ($filename != '.' && $filename != '..') {
                    rm_rf($file . '/' . $filename);
                }
            }
            closedir($handle);

            @chmod($file, G5_DIR_PERMISSION);
            @rmdir($file);
        } else {
            @chmod($file, G5_FILE_PERMISSION);
            @unlink($file);
        }
    }
}

function help($help = "")
{
    global $g5;

    $str = '<span class="hint-text">' . str_replace("\n", "<br>", $help) . '</span>';

    return $str;
}

function order_select($fld, $sel = '')
{
    $s = '<select name="' . $fld . '" id="' . $fld . '">';
    for ($i = 1; $i <= 100; $i++) {
        $s .= '<option value="' . $i . '" ';
        if ($sel) {
            if ($i == $sel) {
                $s .= 'selected';
            }
        } else {
            if ($i == 50) {
                $s .= 'selected';
            }
        }
        $s .= '>' . $i . '</option>';
    }
    $s .= '</select>';

    return $s;
}

function domain_mail_host($is_at = true)
{
    list($domain_host,) = explode(':', $_SERVER['HTTP_HOST']);

    if ('www.' === substr($domain_host, 0, 4)) {
        $domain_host = substr($domain_host, 4);
    }

    return $is_at ? '@' . $domain_host : $domain_host;
}

function check_log_folder($log_path, $is_delete = true)
{
    if (is_writable($log_path)) {
        $htaccess_file = $log_path . '/.htaccess';
        if (!file_exists($htaccess_file)) {
            if ($handle = @fopen($htaccess_file, 'w')) {
                fwrite($handle, 'Order deny,allow' . "\n");
                fwrite($handle, 'Deny from all' . "\n");
                fclose($handle);
            }
        }

        $index_file = $log_path . '/index.php';
        if (!file_exists($index_file)) {
            if ($handle = @fopen($index_file, 'w')) {
                fwrite($handle, '');
                fclose($handle);
            }
        }
    }

    if ($is_delete) {
        try {
            $txt_files = glob($log_path . '/*.txt');
            $log_files = glob($log_path . '/*.log');

            $del_files = array_merge($txt_files, $log_files);

            if ($del_files && is_array($del_files)) {
                foreach ($del_files as $del_file) {
                    $filetime = filemtime($del_file);
                    if ($filetime && $filetime < (G5_SERVER_TIME - 2592000)) {
                        @unlink($del_file);
                    }
                }
            }
        } catch (Exception $e) {
        }
    }
}

function admin_menu_find_by($call, $search_key)
{
    global $menu;

    static $cache_menu = array();

    if (empty($cache_menu)) {
        foreach ($menu as $k1 => $arr1) {
            if (empty($arr1)) {
                continue;
            }
            foreach ($arr1 as $k2 => $arr2) {
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
