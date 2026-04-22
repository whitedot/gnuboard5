<?php
/*************************************************************************
**
**  내보내기 관련 상수 정의
**
*************************************************************************/
define('MEMBER_EXPORT_PAGE_SIZE', 10000);       // 파일당 처리할 회원 수
define('MEMBER_EXPORT_MAX_SIZE', 300000);       // 최대 처리할 회원 수
define('MEMBER_BASE_DIR', "member_list");       // 엑셀 베이스 폴더
define('MEMBER_BASE_DATE', date('YmdHis'));     // 폴더/파일명용 날짜
define('MEMBER_EXPORT_DIR', G5_DATA_PATH . "/" . MEMBER_BASE_DIR . "/" . MEMBER_BASE_DATE); // 엑셀 파일 저장 경로
define('MEMBER_LOG_DIR', G5_DATA_PATH . "/" . MEMBER_BASE_DIR . "/" . "log");               // 로그 파일 저장 경로

/*************************************************************************
**
**  공통 함수 정의
**
*************************************************************************/
/**
 * 검색 옵션 설정
 */
function get_export_config($type = null)
{
    $config = [
        'sfl_list' => [
            'mb_id'=>'아이디',
            'mb_name'=>'이름',
            'mb_nick'=>'닉네임',
            'mb_email'=>'이메일',
            'mb_hp'=>'휴대폰번호',
        ],
        'intercept_list' => [
            'exclude'=>'차단회원 제외',
            'only'=>'차단회원만'
        ],
        'ad_range_list' => [
            'all'           => '수신동의 회원 전체',
            'mailling_only' => '이메일 수신동의 회원만',
            'month_confirm' => date('m월').' 수신동의 확인 대상만',
            'custom_period' => '수신동의 기간 직접 입력'
        ],
    ];

    return $type ? (isset($config[$type]) ? $config[$type] : []) : $config;
}

/**
 * 파라미터 수집 및 유효성 검사
 */
function get_member_export_params(?array $query = null) 
{
    if ($query === null) {
        $query = $_GET;
    }

    // 친구톡 양식 - 엑셀 양식에 포함할 항목
    $fieldArray = array_map('trim', explode(',', isset($query['fields']) ? $query['fields'] : ''));
    $vars = [];
    foreach ($fieldArray as $index => $field) {
        if(!empty($field)){
            $vars['var' . ($index + 1)] = $field;
        }
    }

    $params = [    
        'page'              => 1,
        'formatType'        => (int)(isset($query['formatType']) ? $query['formatType'] : 1),
        'use_stx'           => isset($query['use_stx']) ? $query['use_stx'] : 0,
        'stx_cond'          => clean_xss_tags(isset($query['stx_cond']) ? $query['stx_cond'] : 'like'),
        'sfl'               => clean_xss_tags(isset($query['sfl']) ? $query['sfl'] : ''),
        'stx'               => clean_xss_tags(isset($query['stx']) ? $query['stx'] : ''),
        'use_level'         => isset($query['use_level']) ? $query['use_level'] : 0,
        'level_start'       => (int)(isset($query['level_start']) ? $query['level_start'] : 1),
        'level_end'         => (int)(isset($query['level_end']) ? $query['level_end'] : 10),
        'use_date'          => isset($query['use_date']) ? $query['use_date'] : 0,
        'date_start'        => clean_xss_tags(isset($query['date_start']) ? $query['date_start'] : ''),
        'date_end'          => clean_xss_tags(isset($query['date_end']) ? $query['date_end'] : ''),
        'use_hp_exist'      => isset($query['use_hp_exist']) ? $query['use_hp_exist'] : 0,
        'ad_range_only'     => isset($query['ad_range_only']) ? $query['ad_range_only'] : 0,
        'ad_range_type'     => clean_xss_tags(isset($query['ad_range_type']) ? $query['ad_range_type'] : 'all'),
        'ad_mailling'       => isset($query['ad_mailling']) ? $query['ad_mailling'] : 0,
        'agree_date_start'  => clean_xss_tags(isset($query['agree_date_start']) ? $query['agree_date_start'] : ''),
        'agree_date_end'    => clean_xss_tags(isset($query['agree_date_end']) ? $query['agree_date_end'] : ''),
        'use_intercept'     => isset($query['use_intercept']) ? $query['use_intercept'] : 0,
        'intercept'         => clean_xss_tags(isset($query['intercept']) ? $query['intercept'] : 'exclude'),
        'vars'              => $vars,
    ];
    
    // 레벨 범위 검증
    if ($params['level_start'] > $params['level_end']) {
            [$params['level_start'] , $params['level_end']] = [$params['level_end'], $params['level_start']];
    }
    
    // 가입기간 - 날짜 범위 검증
    if ($params['use_date'] && $params['date_start'] && $params['date_end']) {
        if ($params['date_start'] > $params['date_end']) {
            [$params['date_start'] , $params['date_end']] = [$params['date_end'], $params['date_start']];
        }
    }
    
    // 수신동의기간 - 날짜 범위 검증
    if ($params['ad_range_type'] == 'custom_period' && $params['agree_date_start'] && $params['agree_date_end']) {
        if ($params['agree_date_start'] > $params['agree_date_end']) {
            [$params['agree_date_start'] , $params['agree_date_end']] = [$params['agree_date_end'], $params['agree_date_start']];
        }
    }
    
    return $params;
}

/**
 * 전체 데이터 개수 조회
 */
function member_export_get_total_count($params) 
{
    global $g5;
    
    $where_data = member_export_build_where($params);
    $sql = "SELECT COUNT(*) as cnt FROM {$g5['member_table']} {$where_data['clause']}";
    
    $result = sql_query_prepared($sql, $where_data['params']);
    if (!$result) {
        throw new Exception("데이터 조회에 실패하였습니다. 다시 시도해주세요.");
    }
    
    $row = sql_fetch_array($result);
    return (int)$row['cnt'];
}

/**
 * WHERE 조건절 생성
 */
function member_export_build_where($params) 
{
    global $config;
    $conditions = [];
    $query_params = [];
    
    // 기본 조건 - 탈퇴하지 않은 사용자
    $conditions[] = "mb_leave_date = ''";
    
    // 검색어 조건
    if (!empty($params['use_stx']) && $params['use_stx'] === '1') {
        $sfl_list = get_export_config('sfl_list');
        $sfl = in_array($params['sfl'], array_keys($sfl_list)) ? $params['sfl'] : '';
        $stx = isset($params['stx']) ? trim((string) $params['stx']) : '';

        if(!empty($sfl) && !empty($stx)){
            if ($params['stx_cond'] === 'like') {
                $conditions[] = "{$sfl} LIKE :search_like";
                $query_params['search_like'] = '%' . $stx . '%';
            } else {
                $conditions[] = "{$sfl} = :search_exact";
                $query_params['search_exact'] = $stx;
            }
        }
    }
    
    // 권한 조건
    if (!empty($params['use_level']) && $params['use_level'] === '1') {
        $level_start = max(1, (int)$params['level_start']);
        $level_end = min(10, (int)$params['level_end']);

        $conditions[] = "(mb_level BETWEEN :level_start AND :level_end)";
        $query_params['level_start'] = $level_start;
        $query_params['level_end'] = $level_end;
    }
    
    // 가입기간 조건
    if (!empty($params['use_date']) && $params['use_date'] === '1') {
        $date_start = isset($params['date_start']) ? trim($params['date_start']) : '';
        $date_end = isset($params['date_end']) ? trim($params['date_end']) : '';

        if ($date_start && $date_end) {
            $conditions[] = "mb_datetime BETWEEN :date_start_range AND :date_end_range";
            $query_params['date_start_range'] = $date_start . ' 00:00:00';
            $query_params['date_end_range'] = $date_end . ' 23:59:59';
        } elseif ($date_start) {
            $conditions[] = "mb_datetime >= :date_start_only";
            $query_params['date_start_only'] = $date_start . ' 00:00:00';
        } elseif ($date_end) {
            $conditions[] = "mb_datetime <= :date_end_only";
            $query_params['date_end_only'] = $date_end . ' 23:59:59';
        }
    }
    
    // 휴대폰 번호 존재 조건
    if (!empty($params['use_hp_exist']) && $params['use_hp_exist'] === '1') {
        $conditions[] = "(mb_hp is not null and mb_hp != '')";
    }
    
    // 정보수신동의 조건
    if (!empty($params['ad_range_only']) && $params['ad_range_only'] === '1') {
        $range = isset($params['ad_range_type']) ? $params['ad_range_type'] : '';

        $base_marketing = "mb_marketing_agree = 1";

        if ($range === 'all') {        
            $conditions[] = "({$base_marketing} AND mb_mailling = 1)";
        } elseif ($range === 'mailling_only') {        
            $conditions[] = "({$base_marketing} AND mb_mailling = 1)";
        } elseif ($range === 'month_confirm' || $range === 'custom_period') {
            $useEmail = !empty($params['ad_mailling']);
        
            if ($range === 'month_confirm') {
                $start = date('Y-m-01 00:00:00', strtotime('-23 months'));
                $end   = date('Y-m-t 23:59:59', strtotime('-23 months'));
                $emailDateCond = "mb_mailling_date BETWEEN :mailling_month_start AND :mailling_month_end";
                $query_params['mailling_month_start'] = $start;
                $query_params['mailling_month_end'] = $end;
            } else {
                $date_start = isset($params['agree_date_start']) ? $params['agree_date_start'] : '';
                $date_end   = isset($params['agree_date_end']) ? $params['agree_date_end'] : '';

                if ($date_start && $date_end) {
                    $emailDateCond = "mb_mailling_date BETWEEN :agree_date_start_range AND :agree_date_end_range";
                    $query_params['agree_date_start_range'] = $date_start . ' 00:00:00';
                    $query_params['agree_date_end_range'] = $date_end . ' 23:59:59';
                } elseif ($date_start) {
                    $emailDateCond = "mb_mailling_date >= :agree_date_start_only";
                    $query_params['agree_date_start_only'] = $date_start . ' 00:00:00';
                } elseif ($date_end) {
                    $emailDateCond = "mb_mailling_date <= :agree_date_end_only";
                    $query_params['agree_date_end_only'] = $date_end . ' 23:59:59';
                } else {
                    $emailDateCond = "mb_mailling_date <> '0000-00-00 00:00:00'";
                }
            }
            
            if (!$useEmail) {
                $conditions[] = "0=1";
            } else {
                $conditions[] = "(mb_mailling = 1 AND {$emailDateCond})";
            }
        }
    }
    
    // 차단 회원 조건
    if (!empty($params['use_intercept']) && $params['use_intercept'] === '1') {
        switch ($params['intercept']) {
            case 'exclude':
                $conditions[] = "mb_intercept_date = ''";
                break;
            case 'only':
                $conditions[] = "mb_intercept_date != ''";
                break;
        }
    } 

    return array(
        'clause' => empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions),
        'params' => $query_params,
    );
}
