<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$member_list_request = admin_read_member_list_request($_GET, $config);
$member_form_request = admin_read_member_form_request($_GET);
$member_form_view = admin_build_member_form_view($member_form_request, $member, $is_admin, $config);
$page_view = admin_build_member_form_page_view($member_form_view, $config);
$member_form_page_state = array(
    'list_url' => './member_list.php?' . admin_bootstrap_build_qstr($member_list_request),
);
$basic_view = $page_view['sections']['basic'];
$contact_view = $page_view['sections']['contact'];
$consent_view = $page_view['sections']['consent'];
$profile_view = $page_view['sections']['profile'];
$history_view = $page_view['sections']['history'];

$g5['title'] = $page_view['title'];
$admin_container_class = $page_view['admin_container_class'];
$admin_page_subtitle = $page_view['admin_page_subtitle'];
require_once './admin.head.php';
?>

<div data-admin-member-form>
<?php echo admin_render_anchor_menu($page_view['pg_anchor_menu_view']); ?>

<form name="fmember" id="fmember" action="./member_form_update.php" method="post" class="admin-form-layout ui-form-theme ui-form-showcase space-y-5" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $member_form_request['w'] ?>">
    <input type="hidden" name="sfl" value="<?php echo $member_list_request['sfl'] ?>">
    <input type="hidden" name="stx" value="<?php echo $member_list_request['stx'] ?>">
    <input type="hidden" name="sst" value="<?php echo $member_list_request['sst'] ?>">
    <input type="hidden" name="sod" value="<?php echo $member_list_request['sod'] ?>">
    <input type="hidden" name="page" value="<?php echo $member_list_request['page'] ?>">
    <input type="hidden" name="token" value="<?php echo get_admin_token(); ?>" id="token">
    <div class="sr-only" aria-hidden="true">
        <label for="member_form_fake_username">자동완성 방지 아이디</label>
        <input type="text" id="member_form_fake_username" name="member_form_fake_username" tabindex="-1" autocomplete="username">
        <label for="member_form_fake_password">자동완성 방지 비밀번호</label>
        <input type="password" id="member_form_fake_password" name="member_form_fake_password" tabindex="-1" autocomplete="current-password">
    </div>

    <?php // 기본 정보
    include_once G5_ADMIN_PATH.'/member_form_parts/basic.php';

    // 연락처 및 주소
    include_once G5_ADMIN_PATH.'/member_form_parts/contact.php';

    // 수신 및 공개 설정
    include_once G5_ADMIN_PATH.'/member_form_parts/consent.php';

    // 프로필 및 메모
    include_once G5_ADMIN_PATH.'/member_form_parts/profile.php';

    // 인증 및 활동 내역
    include_once G5_ADMIN_PATH.'/member_form_parts/history.php';
    ?>

    <div class="admin-form-sticky-actions flex items-center justify-between border-default-300 border-t border-dashed pt-4">
        <a href="<?php echo $member_form_page_state['list_url']; ?>" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" class="btn btn-solid-primary" accesskey="s">저장</button>
    </div>
</form>
</div>

<?php

run_event('admin_member_form_after', $member_form_view['mb'], $member_form_request['w']);

require_once './admin.tail.php';
