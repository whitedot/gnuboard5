<?php
$menu['menu100'] = array(
    array('100000', '환경설정', G5_ADMIN_URL . '/config_form.php',   'config'),
    array('100100', '기본환경설정', G5_ADMIN_URL . '/config_form.php',   'cf_basic'),
    array('100200', '관리권한설정', G5_ADMIN_URL . '/auth_list.php',     'cf_auth'),
    array('100300', '메일 테스트', G5_ADMIN_URL . '/sendmail_test.php', 'cf_mailtest'),
    array('100800', '세션파일 일괄삭제', G5_ADMIN_URL . '/session_file_delete.php', 'cf_session', 1),
    array('100910', '캡챠파일 일괄삭제', G5_ADMIN_URL . '/captcha_file_delete.php',   'cf_captcha', 1),
    array('100930', '회원관리파일 일괄삭제', G5_ADMIN_URL . '/member_list_file_delete.php',   'cf_memberlist', 1),
    array('100500', 'phpinfo()',        G5_ADMIN_URL . '/phpinfo.php',       'cf_phpinfo'),
    array('100400', '부가서비스', G5_ADMIN_URL . '/service.php', 'cf_service')
);
