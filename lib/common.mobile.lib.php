<?php
if (!defined('_GNUBOARD_')) exit;

function is_mobile()
{
    if (isset($_SERVER['HTTP_USER_AGENT']))
        return  preg_match('/'.G5_MOBILE_AGENT.'/i', $_SERVER['HTTP_USER_AGENT']);
    else
        return '';
}

// PC 또는 모바일 사용인지를 검사
function check_device($device)
{
    global $is_admin;

    if ($is_admin) return;

    if ($device=='pc' && G5_IS_MOBILE) {
        alert('PC 전용 게시판입니다.', G5_URL);
    } else if ($device=='mobile' && !G5_IS_MOBILE) {
        alert('모바일 전용 게시판입니다.', G5_URL);
    }
}

function get_device_change_url()
{
    $q = array();
    $device = (G5_IS_MOBILE ? 'pc' : 'mobile');
    $q['device'] = $device;

    return get_params_merge_url($q);
}
