<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 테마가 지원하는 장치 설정 pc, mobile
// 선언하지 않거나 값을 지정하지 않으면 그누보드5의 설정을 따른다.
// G5_SET_DEVICE 상수 설정 보다 우선 적용됨
if(! defined('G5_THEME_DEVICE')) define('G5_THEME_DEVICE', '');

$theme_config = array();

if(! defined('G5_COMMUNITY_USE')) define('G5_COMMUNITY_USE', true);

$theme_config = array(
    'set_default_skin'              => false,
    'cf_member_skin'                => 'theme/basic',
);
