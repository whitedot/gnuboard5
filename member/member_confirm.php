<?php
include_once('./_common.php');

if ($is_guest)
    alert('로그인 한 회원만 접근하실 수 있습니다.', G5_MEMBER_URL.'/login.php');

while (1) {
    $tmp = preg_replace('/&#[^;]+;/', '', $url);
    if ($tmp == $url) break;
    $url = $tmp;
}

$url = run_replace('member_confirm_next_url', $url);

// url 체크
check_url_host($url, '', G5_URL, true);

if($url){
    $url = preg_replace('#^/\\\{1,}#', '/', $url);

    if( preg_match('#^/{3,}#', $url) ){
        $url = preg_replace('#^/{3,}#', '/', $url);
    }

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }
}

$url = get_text($url);
MemberPageController::render('회원 비밀번호 확인', 'member_confirm.skin.php', array(
    'url' => $url,
), array(
    'sub' => true,
));
