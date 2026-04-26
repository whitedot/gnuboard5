<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_build_anchor_menu_view($tabs, $options = array())
{
    if (!is_array($tabs) || empty($tabs)) {
        return array(
            'tabs' => array(),
            'options' => array(),
        );
    }

    return array(
        'tabs' => $tabs,
        'options' => array(
            'nav_id' => isset($options['nav_id']) ? trim((string) $options['nav_id']) : '',
            'nav_class' => isset($options['nav_class']) ? trim((string) $options['nav_class']) : 'tab-nav',
            'nav_aria_label' => isset($options['nav_aria_label']) ? trim((string) $options['nav_aria_label']) : '탭 메뉴',
            'link_class' => isset($options['link_class']) ? trim((string) $options['link_class']) : 'tab-trigger-line-primary',
            'active_class' => isset($options['active_class']) ? trim((string) $options['active_class']) : 'active',
            'as_tabs' => !empty($options['as_tabs']),
            'link_id_prefix' => isset($options['link_id_prefix']) ? trim((string) $options['link_id_prefix']) : 'admin_tab_',
        ),
    );
}

function admin_render_anchor_menu(array $menu_view)
{
    $tabs = isset($menu_view['tabs']) && is_array($menu_view['tabs']) ? $menu_view['tabs'] : array();
    if (empty($tabs)) {
        return '';
    }

    $options = isset($menu_view['options']) && is_array($menu_view['options']) ? $menu_view['options'] : array();
    $nav_id = isset($options['nav_id']) ? $options['nav_id'] : '';
    $nav_class = isset($options['nav_class']) ? $options['nav_class'] : 'tab-nav';
    $nav_aria_label = isset($options['nav_aria_label']) ? $options['nav_aria_label'] : '탭 메뉴';
    $link_class = isset($options['link_class']) ? $options['link_class'] : 'tab-trigger-line-primary';
    $active_class = isset($options['active_class']) ? $options['active_class'] : 'active';
    $as_tabs = !empty($options['as_tabs']);
    $link_id_prefix = isset($options['link_id_prefix']) ? $options['link_id_prefix'] : 'admin_tab_';

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

function admin_build_anchor_menu($tabs, $options = array())
{
    return admin_render_anchor_menu(admin_build_anchor_menu_view($tabs, $options));
}
