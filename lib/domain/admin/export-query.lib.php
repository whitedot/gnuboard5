<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function admin_get_member_export_sheet_config()
{
    $type = 1;
    $configs = array(
        1 => array(
            'title' => array('회원관리파일(일반)'),
            'headers' => array('아이디', '이름', '닉네임', '휴대폰번호', '이메일', '회원권한', '가입일', '차단', '광고성 이메일 수신동의', '광고성 이메일 동의일자', '마케팅목적의개인정보수집및이용동의', '마케팅목적의개인정보수집및이용동의일자'),
            'fields' => array('mb_id', 'mb_name', 'mb_nick', 'mb_hp', 'mb_email', 'mb_level', 'mb_datetime', 'mb_intercept_date', 'mb_mailling', 'mb_mailling_date', 'mb_marketing_agree', 'mb_marketing_date'),
            'widths' => array(20, 20, 20, 20, 30, 10, 25, 10, 20, 25, 20, 25),
        ),
    );

    return isset($configs[$type]) ? $configs[$type] : $configs[1];
}

function admin_open_member_export_statement($params, $member_table, &$fields = array())
{
    $config = admin_get_member_export_sheet_config();
    $fields = array_unique($config['fields']);

    $sql_transform_map = array(
        'mb_datetime' => "IF(mb_datetime = '0000-00-00 00:00:00', '', mb_datetime) AS mb_datetime",
        'mb_intercept_date' => "IF(mb_intercept_date != '', '차단됨', '정상') AS mb_intercept_date",
        'mb_mailling' => "IF(mb_mailling = '1', '동의', '미동의') AS mb_mailling",
        'mb_mailling_date' => "IF(mb_mailling != '1' OR mb_mailling_date = '0000-00-00 00:00:00', '', mb_mailling_date) AS mb_mailling_date",
        'mb_marketing_agree' => "IF(mb_marketing_agree = '1', '동의', '미동의') AS mb_marketing_agree",
        'mb_marketing_date' => "IF(mb_marketing_agree != '1' OR mb_marketing_date = '0000-00-00 00:00:00', '', mb_marketing_date) AS mb_marketing_date",
    );

    $sql_fields = array();
    foreach ($fields as $field) {
        $sql_fields[] = isset($sql_transform_map[$field]) ? $sql_transform_map[$field] : $field;
    }
    $field_list = implode(', ', $sql_fields);

    $where_data = admin_build_member_export_where($params);

    $page = (int) (isset($params['page']) ? $params['page'] : 1);
    if ($page < 1) {
        $page = 1;
    }

    $query_params = $where_data['params'];
    $query_params['offset'] = (int) (($page - 1) * MEMBER_EXPORT_PAGE_SIZE);
    $query_params['page_size'] = (int) MEMBER_EXPORT_PAGE_SIZE;

    $sql = "SELECT {$field_list} FROM {$member_table} {$where_data['clause']} ORDER BY mb_no DESC LIMIT :offset, :page_size";
    $statement = sql_statement_prepared($sql, $query_params, false);
    if (!$statement) {
        throw new Exception('데이터 조회에 실패하였습니다');
    }

    return $statement;
}

function admin_fetch_member_export_sheet_rows($params, array $fields, $member_table)
{
    $statement = admin_open_member_export_statement($params, $member_table, $fields);
    $rows = array();

    try {
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $row_data = array();
            foreach ($fields as $field) {
                $row_data[] = isset($row[$field]) ? (string) $row[$field] : '';
            }
            $rows[] = $row_data;
        }
    } finally {
        $statement->closeCursor();
    }

    return $rows;
}
