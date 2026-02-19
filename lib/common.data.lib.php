<?php
if (!defined('_GNUBOARD_')) exit;

// 게시물 정보($write_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
function get_list($write_row, $board, $skin_url, $subject_len=40)
{
    global $g5, $config, $g5_object;
    global $qstr, $page;

    //$t = get_microtime();

    $g5_object->set('bbs', $write_row['wr_id'], $write_row, $board['bo_table']);

    // 배열전체를 복사
    $list = $write_row;
    unset($write_row);

    $board_notice = array_map('trim', explode(',', $board['bo_notice']));
    $list['is_notice'] = in_array($list['wr_id'], $board_notice);

    if ($subject_len)
        $list['subject'] = conv_subject($list['wr_subject'], $subject_len, '…');
    else
        $list['subject'] = conv_subject($list['wr_subject'], $board['bo_subject_len'], '…');

    if( ! (isset($list['wr_seo_title']) && $list['wr_seo_title']) && $list['wr_id'] ){
        seo_title_update(get_write_table_name($board['bo_table']), $list['wr_id'], 'bbs');
    }

    // 목록에서 내용 미리보기 사용한 게시판만 내용을 변환함 (속도 향상) : kkal3(커피)님께서 알려주셨습니다.
    if ($board['bo_use_list_content'])
	{
		$html = 0;
		if (strstr($list['wr_option'], 'html1'))
			$html = 1;
		else if (strstr($list['wr_option'], 'html2'))
			$html = 2;

        $list['content'] = conv_content($list['wr_content'], $html);
	}

    $list['comment_cnt'] = '';
    if ($list['wr_comment'])
        $list['comment_cnt'] = "<span>".$list['wr_comment']."</span>";

    // 당일인 경우 시간으로 표시함
    $list['datetime'] = substr($list['wr_datetime'],0,10);
    $list['datetime2'] = $list['wr_datetime'];
    if ($list['datetime'] == G5_TIME_YMD)
        $list['datetime2'] = substr($list['datetime2'],11,5);
    else
        $list['datetime2'] = substr($list['datetime2'],5,5);
    // 4.1
    $list['last'] = substr($list['wr_last'],0,10);
    $list['last2'] = $list['wr_last'];
    if ($list['last'] == G5_TIME_YMD)
        $list['last2'] = substr($list['last2'],11,5);
    else
        $list['last2'] = substr($list['last2'],5,5);

    $list['wr_homepage'] = get_text($list['wr_homepage']);

    $tmp_name = get_text(cut_str($list['wr_name'], $config['cf_cut_name'])); // 설정된 자리수 만큼만 이름 출력
    $tmp_name2 = cut_str($list['wr_name'], $config['cf_cut_name']); // 설정된 자리수 만큼만 이름 출력
    if ($board['bo_use_sideview'])
        $list['name'] = get_sideview($list['mb_id'], $tmp_name2, $list['wr_email'], $list['wr_homepage']);
    else
        $list['name'] = '<span>'.$tmp_name.'</span>';

    $reply = $list['wr_reply'];

    $list['reply'] = strlen($reply)*20;

    $list['icon_reply'] = '';
    if ($list['reply'])
        $list['icon_reply'] = '<img src="'.$skin_url.'/img/icon_reply.gif" alt="답변글">';

    $list['icon_link'] = '';
    if ($list['wr_link1'] || $list['wr_link2'])
        $list['icon_link'] = '<i aria-hidden="true"></i> ';

    // 분류명 링크
    $list['ca_name_href'] = get_pretty_url($board['bo_table'], '', 'sca='.urlencode($list['ca_name']));

    $list['href'] = get_pretty_url($board['bo_table'], $list['wr_id'], $qstr);
    $list['comment_href'] = $list['href'];

    $list['icon_new'] = '';
    if ($board['bo_new'] && $list['wr_datetime'] >= date("Y-m-d H:i:s", G5_SERVER_TIME - ($board['bo_new'] * 3600)))
        $list['icon_new'] = '<img src="'.$skin_url.'/img/icon_new.gif" alt="새글"> ';

    $list['icon_hot'] = '';
    if ($board['bo_hot'] && $list['wr_hit'] >= $board['bo_hot'])
        $list['icon_hot'] = '<i aria-hidden="true"></i> ';

    $list['icon_secret'] = '';
    if (strstr($list['wr_option'], 'secret'))
        $list['icon_secret'] = '<i aria-hidden="true"></i> ';

    // 링크
    for ($i=1; $i<=G5_LINK_COUNT; $i++) {
        $list['link'][$i] = set_http(get_text($list["wr_link{$i}"]));
        $list['link_href'][$i] = G5_BBS_URL.'/link.php?bo_table='.$board['bo_table'].'&amp;wr_id='.$list['wr_id'].'&amp;no='.$i.$qstr;
        $list['link_hit'][$i] = (int)$list["wr_link{$i}_hit"];
    }

    // 가변 파일
    if ($board['bo_use_list_file'] || ($list['wr_file'] && $subject_len == 255) /* view 인 경우 */) {
        $list['file'] = get_file($board['bo_table'], $list['wr_id']);
    } else {
        $list['file']['count'] = $list['wr_file'];
    }

    if ($list['file']['count'])
        $list['icon_file'] = '<i aria-hidden="true"></i> ';

    return $list;
}

// get_list 의 alias
function get_view($write_row, $board, $skin_url)
{
    return get_list($write_row, $board, $skin_url, 255);
}

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

// 검색 구문을 얻는다.
function get_sql_search($search_ca_name, $search_field, $search_text, $search_operator='and')
{
    global $g5;

    $str = "";
    if ($search_ca_name)
        $str = " ca_name = '$search_ca_name' ";

    $search_text = strip_tags(($search_text));
    $search_text = trim(stripslashes($search_text));

    if (!$search_text && $search_text !== '0') {
        if ($search_ca_name) {
            return $str;
        } else {
            return '0';
        }
    }

    if ($str)
        $str .= " and ";

    // 쿼리의 속도를 높이기 위하여 ( ) 는 최소화 한다.
    $op1 = "";

    // 검색어를 구분자로 나눈다. 여기서는 공백
    $s = array();
    $s = explode(" ", $search_text);

    // 검색필드를 구분자로 나눈다. 여기서는 +
    $tmp = array();
    $tmp = explode(",", trim($search_field));
    $field = explode("||", $tmp[0]);
    $not_comment = "";
    if (isset($tmp[1]))
        $not_comment = $tmp[1];

    $str .= "(";
    for ($i=0; $i<count($s); $i++) {
        // 검색어
        $search_str = trim($s[$i]);
        if ($search_str == "") continue;

        // 인기검색어
        insert_popular($field, $search_str);

        $str .= $op1;
        $str .= "(";

        $op2 = "";
        for ($k=0; $k<count($field); $k++) { // 필드의 수만큼 다중 필드 검색 가능 (필드1+필드2...)

            // SQL Injection 방지
            // 필드값에 a-z A-Z 0-9 _ , | 이외의 값이 있다면 검색필드를 wr_subject 로 설정한다.
            $field[$k] = preg_match("/^[\w\,\|]+$/", $field[$k]) ? strtolower($field[$k]) : "wr_subject";

            $str .= $op2;
            switch ($field[$k]) {
                case "mb_id" :
                case "wr_name" :
                    $str .= " $field[$k] = '$s[$i]' ";
                    break;
                case "wr_hit" :
                case "wr_good" :
                case "wr_nogood" :
                    $str .= " $field[$k] >= '$s[$i]' ";
                    break;
                // 번호는 해당 검색어에 -1 을 곱함
                case "wr_num" :
                    $str .= "$field[$k] = ".((-1)*$s[$i]);
                    break;
                case "wr_ip" :
                case "wr_password" :
                    $str .= "1=0"; // 항상 거짓
                    break;
                // LIKE 보다 INSTR 속도가 빠름
                default :
                    if (preg_match("/[a-zA-Z]/", $search_str))
                        $str .= "INSTR(LOWER($field[$k]), LOWER('$search_str'))";
                    else
                        $str .= "INSTR($field[$k], '$search_str')";
                    break;
            }
            $op2 = " or ";
        }
        $str .= ")";

        $op1 = " $search_operator ";
    }
    $str .= " ) ";
    if ($not_comment === '1') {
        $str .= " and wr_is_comment = '0' ";
    } else if ($not_comment === '0') {
        $str .= " and wr_is_comment = '1' ";
    }

    return $str;
}

// 게시판 테이블에서 하나의 행을 읽음
function get_write($write_table, $wr_id, $is_cache=false)
{
    global $g5, $g5_object;

    $wr_bo_table = preg_replace('/^'.preg_quote($g5['write_prefix']).'/i', '', $write_table);

    $write = $g5_object->get('bbs', $wr_id, $wr_bo_table);

    if( !$write || $is_cache == false ){
        $sql = " select * from {$write_table} where wr_id = '{$wr_id}' ";
        $write = sql_fetch($sql);

        $g5_object->set('bbs', $wr_id, $write, $wr_bo_table);
    }

    return $write;
}

// 게시판의 다음글 번호를 얻는다.
function get_next_num($table)
{
    // 가장 작은 번호를 얻어
    $sql = " select min(wr_num) as min_wr_num from $table ";
    $row = sql_fetch($sql);
    // 가장 작은 번호에 1을 빼서 넘겨줌
    return isset($row['min_wr_num']) ? (int)($row['min_wr_num'] - 1) : -1;
}

// 그룹 설정 테이블에서 하나의 행을 읽음
function get_group($gr_id, $is_cache=false)
{
    global $g5;
    
    if( is_array($gr_id) ){
        return array();
    }

    static $cache = array();

    $gr_id = preg_replace('/[^a-z0-9_]/i', '', $gr_id);
    $cache = run_replace('get_group_db_cache', $cache, $gr_id, $is_cache);
    $key = md5($gr_id);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    $sql = " select * from {$g5['group_table']} where gr_id = '$gr_id' ";

    $group = run_replace('get_group', sql_fetch($sql), $gr_id, $is_cache);
    $cache[$key] = array_merge(array('gr_device'=>'', 'gr_subject'=>''), (array) $group);

    return $cache[$key];
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

    $sql = " SELECT {$fields} from {$g5['member_table']} where mb_id = '{$mb_id}' ";

    $cache[$mb_id][$key] = run_replace('get_member', sql_fetch($sql), $mb_id, $fields, $is_cache);

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
    global $config, $group, $board;
    global $g5;

    $is = false;
    if ($admin == 'board') {
        $mb = sql_fetch("select {$fields} from {$g5['member_table']} where mb_id in ('{$board['bo_admin']}') limit 1 ");
        $is = true;
    }

    // if (($is && !$mb['mb_id']) || $admin == 'group') {
    if (($is && !isset($mb['mb_id'])) || $admin == 'group') {
        $mb = sql_fetch("select {$fields} from {$g5['member_table']} where mb_id in ('{$group['gr_admin']}') limit 1 ");
        $is = true;
    }

    // if (($is && !$mb['mb_id']) || $admin == 'super') {
    if (($is && !isset($mb['mb_id'])) || $admin == 'super') {
        $mb = sql_fetch("select {$fields} from {$g5['member_table']} where mb_id in ('{$config['cf_admin']}') limit 1 ");
    }

    return $mb;
}

// 관리자인가?
function is_admin($mb_id)
{
    global $config, $group, $board;

    if (!$mb_id) return '';

    $is_authority = '';

    if ($config['cf_admin'] == $mb_id){
        $is_authority = 'super';
    } else if (isset($group['gr_admin']) && ($group['gr_admin'] == $mb_id)){
        $is_authority = 'group';
    } else if (isset($board['bo_admin']) && ($board['bo_admin'] == $mb_id)){
        $is_authority = 'board';
    }

    return run_replace('is_admin', $is_authority, $mb_id);
}

// 게시판의 공지사항을 , 로 구분하여 업데이트 한다.
function board_notice($bo_notice, $wr_id, $insert=false)
{
    $notice_array = explode(",", trim($bo_notice));

    if($insert && in_array($wr_id, $notice_array))
        return $bo_notice;

    $notice_array = array_merge(array($wr_id), $notice_array);
    $notice_array = array_unique($notice_array);
    foreach ($notice_array as $key=>$value) {
        if (!trim($value))
            unset($notice_array[$key]);
    }
    if (!$insert) {
        foreach ($notice_array as $key=>$value) {
            if ((int)$value == (int)$wr_id)
                unset($notice_array[$key]);
        }
    }
    return implode(",", $notice_array);
}

// 스킨 path (단일 반응형 - 테마 스킨만 사용)
function get_skin_path($dir, $skin)
{
    global $config;

    $cf_theme = trim($config['cf_theme']) ?: 'basic';
    $theme_path = G5_PATH.'/'.G5_THEME_DIR.'/'.$cf_theme;

    if(preg_match('#^theme/(.+)$#', $skin, $match)) { // 테마에 포함된 스킨이라면
        $skin_path = $theme_path.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$match[1];
    } else {
        // 레거시 호환: 'basic' -> theme/basic/skin/ 경로로 변환
        $skin_name = $skin ?: 'basic';
        $skin_path = $theme_path.'/'.G5_SKIN_DIR.'/'.$dir.'/'.$skin_name;
    }

    return $skin_path;
}

// 스킨 url
function get_skin_url($dir, $skin)
{
    $skin_path = get_skin_path($dir, $skin);

    return str_replace(G5_PATH, G5_URL, $skin_path);
}

function get_member_profile_img($mb_id='', $width='', $height='', $alt='profile_image', $title=''){
    global $member;

    static $no_profile_cache = '';
    static $member_cache = array();
    
    $src = '';

    if( $mb_id ){
        if( isset($member_cache[$mb_id]) ){
            $src = $member_cache[$mb_id];
        } else {
            $member_img = G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.get_mb_icon_name($mb_id).'.gif';
            if (is_file($member_img)) {
                if(defined('G5_USE_MEMBER_IMAGE_FILETIME') && G5_USE_MEMBER_IMAGE_FILETIME) {
                    $member_img .= '?'.filemtime($member_img);
                }
                $member_cache[$mb_id] = $src = str_replace(G5_DATA_PATH, G5_DATA_URL, $member_img);
            }
        }
    }

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
