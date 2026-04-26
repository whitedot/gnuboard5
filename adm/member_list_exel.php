<?php
$sub_menu = "200400";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$page_request = admin_build_member_export_page_request(g5_get_runtime_get_input(), $config, $g5, isset($member) && is_array($member) ? $member : array());
$member_export_view = $page_request['view'];
$member_export_filter_view = $member_export_view['filter_view'];

$g5['title'] = $member_export_view['title'];
$admin_container_class = $member_export_view['admin_container_class'];
$admin_page_subtitle = $member_export_view['admin_page_subtitle'];
require_once './admin.head.php';
?>
<div
    data-admin-member-export
    data-environment-ready="<?php echo $member_export_view['environment_ready'] ? '1' : '0'; ?>"
    data-environment-error="<?php echo htmlspecialchars($member_export_view['environment_error'], ENT_QUOTES, 'UTF-8'); ?>"
    <?php foreach ($member_export_view['client_config_attrs'] as $name => $value) { ?>
        data-<?php echo $name; ?>="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
    <?php } ?>
>
    <?php include_once G5_ADMIN_PATH . '/member_list_exel_parts/intro.php'; ?>
    <?php include_once G5_ADMIN_PATH . '/member_list_exel_parts/filter.php'; ?>
</div>

<?php
require_once './admin.tail.php';
