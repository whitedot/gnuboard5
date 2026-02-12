<?php
if (!defined('_GNUBOARD_')) exit;

// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#(&amp;)?page=[0-9]*#', '', $url);
	$url .= substr($url, -1) === '?' ? 'page=' : '&amp;page=';
    $url = preg_replace('|[^\w\-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', clean_xss_tags($url));

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">처음</a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">이전</a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">다음</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

// 페이징 코드의 <nav><span> 태그 다음에 코드를 삽입
function page_insertbefore($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    return preg_replace("/^(<nav[^>]+><span[^>]+>)/", '$1'.$insert_html.PHP_EOL, $paging_html);
}

// 페이징 코드의 </span></nav> 태그 이전에 코드를 삽입
function page_insertafter($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    if(preg_match("#".PHP_EOL."</span></nav>#", $paging_html))
        $php_eol = '';
    else
        $php_eol = PHP_EOL;

    return preg_replace("#(</span></nav>)$#", $php_eol.$insert_html.'$1', $paging_html);
}

// 경고메세지를 경고창으로
function alert($msg='', $url='', $error=true, $post=false)
{
    global $g5, $config, $member, $is_member, $is_admin, $board;

    run_event('alert', $msg, $url, $error, $post);

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }

    $msg = $msg ? strip_tags($msg, '<br>') : '올바른 방법으로 이용해 주십시오.';

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/alert.php');
    exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg, $error=true)
{
    global $g5, $config, $member, $is_member, $is_admin, $board;
    
    run_event('alert_close', $msg, $error);

    $msg = strip_tags($msg, '<br>');

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/alert_close.php');
    exit;
}

// confirm 창
function confirm($msg, $url1='', $url2='', $url3='')
{
    global $g5, $config, $member, $is_member, $is_admin, $board;

    if (!$msg) {
        $msg = '올바른 방법으로 이용해 주십시오.';
        alert($msg);
    }

    if (function_exists('safe_filter_url_host')) {
        $url1 = safe_filter_url_host($url1);
        $url2 = safe_filter_url_host($url2);
        $url3 = safe_filter_url_host($url3);
    }

    if(!trim($url1) || !trim($url2)) {
        $msg = '$url1 과 $url2 를 지정해 주세요.';
        alert($msg);
    }

    if (!$url3) $url3 = clean_xss_tags($_SERVER['HTTP_REFERER']);

    $msg = str_replace("\\n", "<br>", $msg);

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/confirm.php');
    exit;
}

// 게시판 그룹을 SELECT 형식으로 얻음
function get_group_select($name, $selected='', $event='')
{
    global $g5, $is_admin, $member;

    $sql = " select gr_id, gr_subject from {$g5['group_table']} a ";
    if ($is_admin == "group") {
        $sql .= " left join {$g5['member_table']} b on (b.mb_id = a.gr_admin)
                  where b.mb_id = '{$member['mb_id']}' ";
    }
    $sql .= " order by a.gr_id ";

    $result = sql_query($sql);
    $str = "<select id=\"$name\" name=\"$name\" $event>\n";
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i == 0) $str .= "<option value=\"\">선택</option>";
        $str .= option_selected($row['gr_id'], $selected, $row['gr_subject']);
    }
    $str .= "</select>";
    return $str;
}

function option_selected($value, $selected, $text='')
{
    if (!$text) $text = $value;
    if ($value == $selected)
        return "<option value=\"$value\" selected=\"selected\">$text</option>\n";
    else
        return "<option value=\"$value\">$text</option>\n";
}

// 분류 옵션을 얻음
// 4.00 에서는 카테고리 테이블을 없애고 보드테이블에 있는 내용으로 대체
function get_category_option($bo_table='', $ca_name='')
{
    global $g5, $board, $is_admin;

    $categories = explode("|", $board['bo_category_list'].($is_admin?"|공지":"")); // 구분자가 | 로 되어 있음
    $str = "";
    for ($i=0; $i<count($categories); $i++) {
        $category = trim($categories[$i]);
        if (!$category) continue;

        $str .= "<option value=\"$categories[$i]\"";
        if ($category == $ca_name) {
            $str .= ' selected="selected"';
        }
        $str .= ">$categories[$i]</option>\n";
    }

    return $str;
}

// '예', '아니오'를 SELECT 형식으로 얻음
function get_yn_select($name, $selected='1', $event='')
{
    $str = "<select name=\"$name\" $event>\n";
    if ($selected) {
        $str .= "<option value=\"1\" selected>예</option>\n";
        $str .= "<option value=\"0\">아니오</option>\n";
    } else {
        $str .= "<option value=\"1\">예</option>\n";
        $str .= "<option value=\"0\" selected>아니오</option>\n";
    }
    $str .= "</select>";
    return $str;
}

// 날짜를 select 박스 형식으로 얻는다
function date_select($date, $name='')
{
    global $g5;

    $s = '';
    if (substr($date, 0, 4) == "0000") {
        $date = G5_TIME_YMDHIS;
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    // 년
    $s .= "<select name='{$name}_y'>";
    for ($i=$m['0']-3; $i<=$m['0']+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>년 \n";

    // 월
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>월 \n";

    // 일
    $s .= "<select name='{$name}_d'>";
    for ($i=1; $i<=31; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>일 \n";

    return $s;
}

// 시간을 select 박스 형식으로 얻는다
// 1.04.00
// 경매에 시간 설정이 가능하게 되면서 추가함
function time_select($time, $name="")
{
    preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $m);

    // 시
    $s = "<select name='{$name}_h'>";
    for ($i=0; $i<=23; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>시 \n";

    // 분
    $s .= "<select name='{$name}_i'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>분 \n";

    // 초
    $s .= "<select name='{$name}_s'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>초 \n";

    return $s;
}

// 회원 레이어
function get_sideview($mb_id, $name, $email='', $homepage='', $bo_table='')
{
    global $g5;

    $email = base64_encode($email);
    $homepage = set_http(clean_xss_tags($homepage));

    $name = clean_xss_tags($name);
    if (!$mb_id) {
        return $name;
    }

    $tmp_name = "";
    if ($mb_id) {
        $tmp_name = "<span class=\"sv_wrap\">
            <a href=\"".G5_BBS_URL."/profile.php?mb_id=".$mb_id."\" class=\"sv_member\" title=\"$name 자기소개\" target=\"_blank\" onclick=\"return false;\">$name</a>
            <span class=\"sv\">
                <a href=\"".G5_BBS_URL."/memo_form.php?me_recv_mb_id=".$mb_id."\" onclick=\"win_memo(this.href); return false;\">쪽지보내기</a>";

        if ($email)
            $tmp_name .= "<a href=\"".G5_BBS_URL."/formmail.php?mb_id=".$mb_id."&name=".urlencode($name)."&email=".$email."\" onclick=\"win_email(this.href); return false;\">메일보내기</a>";
        if ($homepage)
            $tmp_name .= "<a href=\"$homepage\" target=\"_blank\">홈페이지</a>";
        if ($mb_id)
            $tmp_name .= "<a href=\"".G5_BBS_URL."/profile.php?mb_id=".$mb_id."\" onclick=\"win_profile(this.href); return false;\">자기소개</a>";
        if ($bo_table) {
            $tmp_name .= "<a href=\"".G5_BBS_URL."/board.php?bo_table=".$bo_table."&sca=&sfl=mb_id,1&stx=".$mb_id."\">전체게시물</a>";
        }

        if (is_admin($mb_id)) {
            $tmp_name .= "<a href=\"".G5_ADMIN_URL."/member_form.php?w=u&mb_id=".$mb_id."\" target=\"_blank\">회원정보변경</a>";
            $tmp_name .= "<a href=\"".G5_ADMIN_URL."/point_list.php?sfl=mb_id&stx=".$mb_id."\" target=\"_blank\">포인트내역</a>";
        }

        $tmp_name .= "</span></span>";
    } else {
        $tmp_name = $name;
    }

    return $tmp_name;
}

// 스킨 style sheet 파일 얻기
function get_skin_stylesheet($skin_path, $dir='')
{
    if(!$skin_path)
        return "";

    $str = "";
    $files = array();

    if($dir)
        $skin_path .= '/'.$dir;

    $skin_url = G5_URL.str_replace("\\", "/", str_replace(G5_PATH, "", $skin_path));

    if(is_dir($skin_path)) {
        if($dh = opendir($skin_path)) {
            while(($file = readdir($dh)) !== false) {
                if($file == "." || $file == "..")
                    continue;

                if(is_dir($skin_path.'/'.$file))
                    continue;

                if(preg_match("/\.(css)$/i", $file))
                    $files[] = $file;
            }
            closedir($dh);
        }
    }

    if(!empty($files)) {
        sort($files);

        foreach($files as $file) {
            $str .= '<link rel="stylesheet" href="'.$skin_url.'/'.$file.'?='.date("md").'">'."\n";
        }
    }

    return $str;
}

// 스킨 javascript 파일 얻기
function get_skin_javascript($skin_path, $dir='')
{
    if(!$skin_path)
        return "";

    $str = "";
    $files = array();

    if($dir)
        $skin_path .= '/'.$dir;

    $skin_url = G5_URL.str_replace("\\", "/", str_replace(G5_PATH, "", $skin_path));

    if(is_dir($skin_path)) {
        if($dh = opendir($skin_path)) {
            while(($file = readdir($dh)) !== false) {
                if($file == "." || $file == "..")
                    continue;

                if(is_dir($skin_path.'/'.$file))
                    continue;

                if(preg_match("/\.(js)$/i", $file))
                    $files[] = $file;
            }
            closedir($dh);
        }
    }

    if(!empty($files)) {
        sort($files);

        foreach($files as $file) {
            $str .= '<script src="'.$skin_url.'/'.$file.'"></script>'."\n";
        }
    }

    return $str;
}

if (!function_exists('get_called_class')) {
    function get_called_class() {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        preg_match(
            '/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/',
            $lines[$bt[1]['line']-1],
            $matches
        );
        return $matches[1];
    }
}

function get_html_process_cls() {
    return html_process::getInstance();
}

// HTML 마지막 처리
function html_end()
{
    return get_html_process_cls()->run();
}

function add_stylesheet($stylesheet, $order=0)
{
    if(trim($stylesheet))
        get_html_process_cls()->merge_stylesheet($stylesheet, $order);
}

function add_javascript($javascript, $order=0)
{
    if(trim($javascript))
        get_html_process_cls()->merge_javascript($javascript, $order);
}

class html_process {
    protected static $id = '0';
    private static $instances = array();
    protected static $is_end = '0';
    protected static $css = array();
    protected static $js  = array();

    public static function getInstance($id = '0')
    {
        self::$id = $id;
        if (isset(self::$instances[self::$id])) {
            return self::$instances[self::$id];
        }
        $calledClass = get_called_class();
        return self::$instances[self::$id] = new $calledClass;
    }

    public static function merge_stylesheet($stylesheet, $order)
    {
        $links = self::$css;
        $is_merge = true;

        foreach($links as $link) {
            if($link[1] == $stylesheet) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            self::$css[] = array($order, $stylesheet);
    }

    public static function merge_javascript($javascript, $order)
    {
        $scripts = self::$js;
        $is_merge = true;

        foreach($scripts as $script) {
            if($script[1] == $javascript) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            self::$js[] = array($order, $javascript);
    }

    public static function run()
    {
        global $config, $g5, $member;

        if (self::$is_end) return;  // 여러번 호출해도 한번만 실행되게 합니다.

        self::$is_end = 1;

        // 현재접속자 처리
        $tmp_sql = " select count(*) as cnt from {$g5['login_table']} where lo_ip = '{$_SERVER['REMOTE_ADDR']}' ";
        $tmp_row = sql_fetch($tmp_sql);
        $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']; 

        $lo_location = isset($g5['lo_location']) ? $g5['lo_location'] : '';
        $lo_url = isset($g5['lo_url']) ? $g5['lo_url'] : '';

        if ($tmp_row['cnt']) {
            $tmp_sql = " update {$g5['login_table']} set mb_id = '{$member['mb_id']}', lo_datetime = '".G5_TIME_YMDHIS."', lo_location = '{$lo_location}', lo_url = '{$lo_url}' where lo_ip = '{$_SERVER['REMOTE_ADDR']}' ";
            sql_query($tmp_sql, FALSE);
        } else {
            $tmp_sql = " insert into {$g5['login_table']} ( lo_ip, mb_id, lo_datetime, lo_location, lo_url ) values ( '{$_SERVER['REMOTE_ADDR']}', '{$member['mb_id']}', '".G5_TIME_YMDHIS."', '{$lo_location}',  '{$lo_url}' ) ";
            sql_query($tmp_sql, FALSE);

            // 시간이 지난 접속은 삭제한다
            sql_query(" delete from {$g5['login_table']} where lo_datetime < '".date("Y-m-d H:i:s", G5_SERVER_TIME - (60 * $config['cf_login_minutes']))."' ");

            // 부담(overhead)이 있다면 테이블 최적화
            //$row = sql_fetch(" SHOW TABLE STATUS FROM `$mysql_db` LIKE '$g5['login_table']' ");
            //if ($row['Data_free'] > 0) sql_query(" OPTIMIZE TABLE $g5['login_table'] ");
        }

        $buffer = ob_get_contents();
        ob_end_clean();

        $stylesheet = '';
        $links = self::$css;

        if(!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);
            
            $links = run_replace('html_process_css_files', $links);

            foreach($links as $link) {
                if(!trim($link[1]))
                    continue;

                $link[1] = preg_replace('#\.css([\'\"]?>)$#i', '.css?ver='.G5_CSS_VER.'$1', $link[1]);

                $stylesheet .= PHP_EOL.$link[1];
            }
        }

        $javascript = '';
        $scripts = self::$js;
        $php_eol = '';

        unset($order);
        unset($index);

        if(!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);
            
            $scripts = run_replace('html_process_script_files', $scripts);

            foreach($scripts as $js) {
                if(!trim($js[1]))
                    continue;
                
                $add_version_str = (stripos($js[1], $http_host) !== false) ? '?ver='.G5_JS_VER : '';
                $js[1] = preg_replace('#\.js([\'\"]?>)<\/script>$#i', '.js'.$add_version_str.'$1</script>', $js[1]);

                $javascript .= $php_eol.$js[1];
                $php_eol = PHP_EOL;
            }
        }

        /*
        </title>
        <link rel="stylesheet" href="default.css">
        밑으로 스킨의 스타일시트가 위치하도록 하게 한다.
        */
        $title_find_pattern = '#(</title>[^<]*<link[^>]+>)#';
        if (preg_match($title_find_pattern, $buffer)) {
            $buffer = preg_replace($title_find_pattern, "$1$stylesheet", $buffer);
        } else {    // 패턴이 없다면 자바스크립트 코드 위에 위치하게 합니다.
            $javascript = $stylesheet. PHP_EOL. $javascript;
        }

        /*
        </head>
        <body>
        전에 스킨의 자바스크립트가 위치하도록 하게 한다.
        */
        $nl = '';
        if($javascript)
            $nl = "\n";
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);
        
        $meta_tag = run_replace('html_process_add_meta', '');
        
        if( $meta_tag ){
            /*
            </title>content<body>
            전에 메타태그가 위치 하도록 하게 한다.
            */
            $nl = "\n";
            $buffer = preg_replace('#(<title[^>]*>.*?</title>)#', "$meta_tag{$nl}$1", $buffer);
        }

        $buffer = run_replace('html_process_buffer', $buffer);

        return $buffer;
    }
}

function get_head_title($title){
    global $g5;

    if( isset($g5['board_title']) && $g5['board_title'] ){
        $title = strip_tags($g5['board_title']);
    }

    return $title;
}

function option_array_checked($option, $arr=array()){
    $checked = '';

    if( !is_array($arr) ){
        $arr = explode(',', $arr);
    }

    if ( !empty($arr) && in_array($option, (array) $arr) ){
        $checked = 'checked="checked"';
    }

    return $checked;
}
