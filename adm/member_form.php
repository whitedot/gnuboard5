<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$mb = array(
    'mb_certify' => null,
    'mb_adult' => null,
    'mb_intercept_date' => null,
    'mb_id' => null,
    'mb_name' => null,
    'mb_nick' => null,
    'mb_email' => null,
    'mb_hp' => null,
    'mb_memo' => null,
    'mb_leave_date' => null,
);

$sound_only = '';
$required_mb_id = '';
$required_mb_id_class = '';
$required_mb_password = '';
$html_title = '';

if ($w == '') {
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="caption-sr-only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $mb['mb_marketing_agree'] = 0;
    $html_title = '추가';
} elseif ($w == 'u') {
    $mb = get_member($mb_id);
    if (!$mb['mb_id']) {
        alert('존재하지 않는 회원자료입니다.');
    }

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
    }

    $required_mb_id = 'readonly';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

// 본인확인방법
switch ($mb['mb_certify']) {
    case 'simple':
        $mb_certify_case = '간편인증';
        $mb_certify_val = 'simple';
        break;
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

// 마케팅 목적의 개인정보 수집 및 이용
$mb_marketing_agree_yes     =  $mb['mb_marketing_agree'] ? 'checked="checked"' : '';
$mb_marketing_agree_no      = !$mb['mb_marketing_agree'] ? 'checked="checked"' : '';

$mb_cert_history = '';
if (isset($mb_id) && $mb_id) {
    $sql = "select * from {$g5['member_cert_history_table']} where mb_id = :mb_id order by ch_id asc";
    $mb_cert_history = sql_query_prepared($sql, array(
        'mb_id' => $mb_id,
    ));
}

if ($mb['mb_intercept_date']) {
    $g5['title'] = "차단된 ";
} else {
    $g5['title'] = "";
}
$g5['title'] .= '회원 ' . $html_title;
$admin_container_class = 'admin-page-member-form';
$admin_page_subtitle = '기본정보, 인증 연락처, 동의 상태, 활동 이력을 탭에서 빠르게 관리하세요.';
require_once './admin.head.php';

$member_tabs = array(
    array('id' => 'anc_mb_basic', 'label' => '기본 정보'),
    array('id' => 'anc_mb_contact', 'label' => '인증 연락처'),
    array('id' => 'anc_mb_consent', 'label' => '수신 및 공개 설정'),
    array('id' => 'anc_mb_profile', 'label' => '관리 메모'),
    array('id' => 'anc_mb_history', 'label' => '인증 및 활동 내역'),
);

$pg_anchor_menu = admin_build_anchor_menu($member_tabs, array(
    'nav_id' => 'member_tabs_nav',
    'nav_class' => 'tab-nav-justified',
    'nav_aria_label' => '회원 등록/수정 탭',
    'link_class' => 'tab-trigger-underline-justified js-member-tab-link',
    'active_class' => 'active',
    'as_tabs' => true,
    'link_id_prefix' => 'member_tab_',
));
?>

<?php echo $pg_anchor_menu; ?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" class="admin-form-layout ui-form-theme ui-form-showcase space-y-5" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
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
        <a href="./member_list.php?<?php echo $qstr ?>" class="btn btn-surface-default-soft">목록</a>
        <button type="submit" class="btn btn-solid-primary" accesskey="s">저장</button>
    </div>
</form>

<?php
// 자바스크립트
include_once G5_ADMIN_PATH.'/member_form_parts/script.php';

run_event('admin_member_form_after', $mb, $w);

require_once './admin.tail.php';
