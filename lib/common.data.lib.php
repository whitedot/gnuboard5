<?php
if (!defined('_GNUBOARD_')) exit;

// set_search_font(), get_search_font() 함수를 search_font() 함수로 대체
function search_font($stx, $str)
{
    global $config;

    // 문자앞에 \ 를 붙입니다.
    $src = array('/', '|');
    $dst = array('\/', '\|');

    if (!trim($stx) && $stx !== '0') return $str;

    // 검색어 전체를 공란으로 나눈다
    $s = explode(' ', $stx);

    // "/(검색1|검색2)/i" 와 같은 패턴을 만듬
    $pattern = '';
    $bar = '';
    for ($m=0; $m<count($s); $m++) {
        if (trim($s[$m]) == '') continue;
        // 태그는 포함하지 않아야 하는데 잘 안되는군. ㅡㅡa
        //$pattern .= $bar . '([^<])(' . quotemeta($s[$m]) . ')';
        //$pattern .= $bar . quotemeta($s[$m]);
        //$pattern .= $bar . str_replace("/", "\/", quotemeta($s[$m]));
        $tmp_str = quotemeta($s[$m]);
        $tmp_str = str_replace($src, $dst, $tmp_str);
        $pattern .= $bar . $tmp_str . "(?![^<]*>)";
        $bar = "|";
    }

    // 지정된 검색 폰트의 색상, 배경색상으로 대체
    $replace = "<b>\\1</b>";

    return preg_replace("/($pattern)/i", $replace, $str);
}

/**
 * 회원 정보를 얻는다
 * 
 * @param string $mb_id
 * @param string $fields
 * @param bool $is_cache
 * 
 * @return array
 */
function get_member_select_fields($fields = '*')
{
    global $g5;

    static $member_columns = null;

    $fields = trim((string) $fields);
    if ($fields === '' || $fields === '*') {
        return '*';
    }

    if ($member_columns === null) {
        $member_columns = sql_field_names($g5['member_table']);
    }

    if (empty($member_columns)) {
        return '*';
    }

    $resolved = array();
    $parts = explode(',', $fields);

    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') {
            return '*';
        }

        if (!preg_match('/^([A-Za-z0-9_]+)(?:\s+(?:as\s+)?([A-Za-z0-9_]+))?$/i', $part, $matches)) {
            return '*';
        }

        $column = $matches[1];
        if (!in_array($column, $member_columns, true)) {
            return '*';
        }

        $quoted_column = sql_quote_identifier($column);
        if (!$quoted_column) {
            return '*';
        }

        if (!empty($matches[2])) {
            $quoted_alias = sql_quote_identifier($matches[2]);
            if (!$quoted_alias) {
                return '*';
            }

            $resolved[] = $quoted_column . ' as ' . $quoted_alias;
        } else {
            $resolved[] = $quoted_column;
        }
    }

    return empty($resolved) ? '*' : implode(', ', $resolved);
}

function get_member($mb_id, $fields = '*', $is_cache = false)
{
    global $g5;

    $mb_id = trim($mb_id);
    if (preg_match("/[^0-9a-z_]+/i", $mb_id)) {
        return array();
    }

    static $cache = array();

    $key = md5($fields);

    if ($is_cache && isset($cache[$mb_id]) && isset($cache[$mb_id][$key])) {
        return $cache[$mb_id][$key];
    }

    $select_fields = get_member_select_fields($fields);
    $sql = " SELECT {$select_fields} from {$g5['member_table']} where mb_id = :mb_id ";

    $cache[$mb_id][$key] = run_replace('get_member', sql_fetch_prepared($sql, array(
        'mb_id' => $mb_id,
    )), $mb_id, $fields, $is_cache);

    return $cache[$mb_id][$key];
}

// 날짜, 조회수의 경우 높은 순서대로 보여져야 하므로 $flag 를 추가
// $flag : asc 낮은 순서 , desc 높은 순서
// 제목별로 컬럼 정렬하는 QUERY STRING
function subject_sort_link($col, $query_string='', $flag='asc')
{
    global $sst, $sod, $sfl, $stx, $page, $sca;

    $q1 = "sst=$col";
    if ($flag == 'asc')
    {
        $q2 = 'sod=asc';
        if ($sst == $col)
        {
            if ($sod == 'asc')
            {
                $q2 = 'sod=desc';
            }
        }
    }
    else
    {
        $q2 = 'sod=desc';
        if ($sst == $col)
        {
            if ($sod == 'desc')
            {
                $q2 = 'sod=asc';
            }
        }
    }

    $arr_query = array();
    $arr_query[] = $query_string;
    $arr_query[] = $q1;
    $arr_query[] = $q2;
    $arr_query[] = 'sfl='.$sfl;
    $arr_query[] = 'stx='.$stx;
    $arr_query[] = 'sca='.$sca;
    $arr_query[] = 'page='.$page;
    $qstr = implode("&amp;", $arr_query);

    parse_str(html_entity_decode($qstr), $qstr_array);
    $url = short_url_clean(get_params_merge_url($qstr_array));

    return '<a href="'.$url.'">';
}

// 관리자 정보를 얻음
function get_admin($admin='super', $fields='*')
{
    global $config;
    global $g5;

    $select_fields = get_member_select_fields($fields);
    $mb = sql_fetch_prepared("select {$select_fields} from {$g5['member_table']} where mb_id = :cf_admin limit 1 ", array(
        'cf_admin' => $config['cf_admin'],
    ));

    return $mb;
}

// 관리자인가?
function is_admin($mb_id)
{
    global $config;

    if (!$mb_id) return '';

    $is_authority = '';

    if ($config['cf_admin'] == $mb_id){
        $is_authority = 'super';
    }

    return run_replace('is_admin', $is_authority, $mb_id);
}

// 스킨 path (단일 반응형 - 테마 스킨만 사용)
function get_skin_path($dir, $skin)
{
    $skin_name = $skin ?: 'basic';
    $candidates = array();

    if (preg_match('#^theme/(.+)$#', $skin_name, $match)) {
        $skin_name = $match[1];
        if (defined('G5_THEME_PATH')) {
            $candidates[] = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$skin_name;
        }
    }

    $candidates[] = G5_SKIN_PATH.'/'.$dir.'/'.$skin_name;

    if (!preg_match('#^theme/(.+)$#', $skin ?: '')) {
        if (defined('G5_THEME_PATH')) {
            $candidates[] = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$skin_name;
        }
    }

    foreach ($candidates as $candidate) {
        if (is_dir($candidate)) {
            return $candidate;
        }
    }

    return $candidates[0];
}

// 스킨 url
function get_skin_url($dir, $skin)
{
    $skin_path = get_skin_path($dir, $skin);

    return str_replace(G5_PATH, G5_URL, $skin_path);
}

function get_member_profile_img($mb_id='', $width='', $height='', $alt='profile_image', $title=''){
    static $no_profile_cache = '';
    $src = '';

    if( !$src ){
        if( !empty($no_profile_cache) ){
            $src = $no_profile_cache;
        } else {
            // 프로필 이미지가 없을때 기본 이미지
            $no_profile_img = (defined('G5_THEME_NO_PROFILE_IMG') && G5_THEME_NO_PROFILE_IMG) ? G5_THEME_NO_PROFILE_IMG : G5_NO_PROFILE_IMG;
            $tmp = array();
            preg_match( '/src="([^"]*)"/i', $no_profile_img, $tmp );
            $no_profile_cache = $src = isset($tmp[1]) ? $tmp[1] : G5_IMG_URL.'/no_profile.gif';
        }
    }

    if( $src ){
        $attributes = array('src'=>$src, 'width'=>$width, 'height'=>$height, 'alt'=>$alt, 'title'=>$title);

        $output = '<img';
        foreach ($attributes as $name => $value) {
            if (!empty($value)) {
                $output .= sprintf(' %s="%s"', $name, $value);
            }
        }
        $output .= '>';

        return $output;
    }

    return '';
}
