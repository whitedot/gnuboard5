<?php
$sub_menu = "200100";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

$mb = array(
    'mb_certify' => null,
    'mb_adult' => null,
    'mb_sms' => null,
    'mb_intercept_date' => null,
    'mb_id' => null,
    'mb_name' => null,
    'mb_nick' => null,
    'mb_point' => null,
    'mb_email' => null,
    'mb_homepage' => null,
    'mb_hp' => null,
    'mb_tel' => null,
    'mb_zip1' => null,
    'mb_zip2' => null,
    'mb_addr1' => null,
    'mb_addr2' => null,
    'mb_addr3' => null,
    'mb_addr_jibeon' => null,
    'mb_signature' => null,
    'mb_profile' => null,
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
    $sound_only = '<strong>필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_sms'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $mb['mb_marketing_agree'] = 0;
    $mb['mb_thirdparty_agree'] = 0;
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
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
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

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

// 마케팅 목적의 개인정보 수집 및 이용
$mb_marketing_agree_yes     =  $mb['mb_marketing_agree'] ? 'checked="checked"' : '';
$mb_marketing_agree_no      = !$mb['mb_marketing_agree'] ? 'checked="checked"' : '';

// 개인정보 제3자 제공 동의
$mb_thirdparty_agree_yes    =  $mb['mb_thirdparty_agree'] ? 'checked="checked"' : '';
$mb_thirdparty_agree_no     = !$mb['mb_thirdparty_agree'] ? 'checked="checked"' : '';

$mb_cert_history = '';
if (isset($mb_id) && $mb_id) {
    $sql = "select * from {$g5['member_cert_history_table']} where mb_id = '{$mb_id}' order by ch_id asc";
    $mb_cert_history = sql_query($sql);
}

if ($mb['mb_intercept_date']) {
    $g5['title'] = "차단된 ";
} else {
    $g5['title'] = "";
}
$g5['title'] .= '회원 ' . $html_title;
require_once './admin.head.php';

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$pg_anchor = '<ul>
    <li><a href="#anc_mb_basic">기본 정보</a></li>
    <li><a href="#anc_mb_contact">연락처 및 주소</a></li>
    <li><a href="#anc_mb_media">아이콘 및 이미지</a></li>
    <li><a href="#anc_mb_consent">수신 및 공개 설정</a></li>
    <li><a href="#anc_mb_profile">프로필 및 메모</a></li>
    <li><a href="#anc_mb_history">인증 및 활동 내역</a></li>
    <li><a href="#anc_mb_extra">여분 필드</a></li>
</ul>';
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <?php
    echo $pg_anchor;

    // 기본 정보
    include_once G5_ADMIN_PATH.'/member_form_parts/basic.php';

    // 연락처 및 주소
    include_once G5_ADMIN_PATH.'/member_form_parts/contact.php';

    // 아이콘 및 이미지
    include_once G5_ADMIN_PATH.'/member_form_parts/media.php';

    // 수신 및 공개 설정
    include_once G5_ADMIN_PATH.'/member_form_parts/consent.php';

    // 프로필 및 메모
    include_once G5_ADMIN_PATH.'/member_form_parts/profile.php';

    // 인증 및 활동 내역
    include_once G5_ADMIN_PATH.'/member_form_parts/history.php';
    ?>

    <div>
        <a href="./member_list.php?<?php echo $qstr ?>">목록</a>
        <input type="submit" value="확인" accesskey='s'>
    </div>
</form>

<?php
// 자바스크립트
include_once G5_ADMIN_PATH.'/member_form_parts/script.php';

run_event('admin_member_form_after', $mb, $w);

require_once './admin.tail.php';
