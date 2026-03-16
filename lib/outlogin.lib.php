<?php
if (!defined('_GNUBOARD_')) exit;

// 외부로그인
function outlogin($skin_dir='basic')
{
    global $config, $member, $g5, $urlencode, $is_admin, $is_member;
    
    $is_auth = false;

    if (array_key_exists('mb_nick', $member)) {
        $nick  = get_text(cut_str($member['mb_nick'], $config['cf_cut_name']));
    }
    // 반응형: theme/skin/outlogin/ 경로 사용
    if(preg_match('#^theme/(.+)$#', $skin_dir, $match)) {
        $outlogin_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/outlogin/'.$match[1];
        $outlogin_skin_url = str_replace(G5_PATH, G5_URL, $outlogin_skin_path);
        $skin_dir = $match[1];
    } else {
        // 레거시 호환
        $outlogin_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/outlogin/'.$skin_dir;
        $outlogin_skin_url = str_replace(G5_PATH, G5_URL, $outlogin_skin_path);
    }

    if ($is_member) {
        $sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if ($row['cnt'])
            $is_auth = true;
    }

    $outlogin_url        = login_url($urlencode);
    $outlogin_action_url = G5_HTTPS_MEMBER_URL.'/login_check.php';
    
    ob_start();
    if ($is_member)
        include_once ($outlogin_skin_path.'/outlogin.skin.2.php');
    else // 로그인 전이라면
        include_once ($outlogin_skin_path.'/outlogin.skin.1.php');
    $content = ob_get_contents();
    ob_end_clean();

    return run_replace('outlogin_content', $content, $is_auth, $outlogin_url, $outlogin_action_url);
}
