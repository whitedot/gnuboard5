<?php
if (!defined('_GNUBOARD_')) exit;

// ?쒗럹?댁???蹂댁뿬以??? ?꾩옱?섏씠吏, 珥앺럹?댁??? URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#(&amp;)?page=[0-9]*#', '', $url);
	$url .= substr($url, -1) === '?' ? 'page=' : '&amp;page=';
    $url = preg_replace('|[^\w\-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', clean_xss_tags($url));

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">泥섏쓬</a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">?댁쟾</a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'<span class="sr-only">?섏씠吏</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sr-only">?대┛</span><strong class="pg_current">'.$k.'</strong><span class="sr-only">?섏씠吏</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">?ㅼ쓬</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">留⑤걹</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

// ?섏씠吏?肄붾뱶??<nav><span> ?쒓렇 ?ㅼ쓬??肄붾뱶瑜??쎌엯
function page_insertbefore($paging_html, $insert_html)
{
    if(!$paging_html)
        $paging_html = '<nav class="pg_wrap"><span class="pg"></span></nav>';

    return preg_replace("/^(<nav[^>]+><span[^>]+>)/", '$1'.$insert_html.PHP_EOL, $paging_html);
}

// ?섏씠吏?肄붾뱶??</span></nav> ?쒓렇 ?댁쟾??肄붾뱶瑜??쎌엯
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

// 寃쎄퀬硫붿꽭吏瑜?寃쎄퀬李쎌쑝濡?
function alert($msg='', $url='', $error=true, $post=false)
{
    global $g5, $config, $member, $is_member, $is_admin, $board;

    run_event('alert', $msg, $url, $error, $post);

    if (function_exists('safe_filter_url_host')) {
        $url = safe_filter_url_host($url);
    }

    $msg = $msg ? strip_tags($msg, '<br>') : '?щ컮瑜?諛⑸쾿?쇰줈 ?댁슜??二쇱떗?쒖삤.';

    $header = '';
    if (isset($g5['title'])) {
        $header = $g5['title'];
    }
    include_once(G5_BBS_PATH.'/alert.php');
    exit;
}

// 寃쎄퀬硫붿꽭吏 異쒕젰??李쎌쓣 ?レ쓬
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

// confirm 李?
function confirm($msg, $url1='', $url2='', $url3='')
{
    global $g5, $config, $member, $is_member, $is_admin, $board;

    if (!$msg) {
        $msg = '?щ컮瑜?諛⑸쾿?쇰줈 ?댁슜??二쇱떗?쒖삤.';
        alert($msg);
    }

    if (function_exists('safe_filter_url_host')) {
        $url1 = safe_filter_url_host($url1);
        $url2 = safe_filter_url_host($url2);
        $url3 = safe_filter_url_host($url3);
    }

    if(!trim($url1) || !trim($url2)) {
        $msg = '$url1 怨?$url2 瑜?吏?뺥빐 二쇱꽭??';
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

// 寃뚯떆??洹몃９??SELECT ?뺤떇?쇰줈 ?살쓬
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
        if ($i == 0) $str .= "<option value=\"\">?좏깮</option>";
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

// 遺꾨쪟 ?듭뀡???살쓬
// 4.00 ?먯꽌??移댄뀒怨좊━ ?뚯씠釉붿쓣 ?놁븷怨?蹂대뱶?뚯씠釉붿뿉 ?덈뒗 ?댁슜?쇰줈 ?泥?
function get_category_option($bo_table='', $ca_name='')
{
    global $g5, $board, $is_admin;

    $categories = explode("|", $board['bo_category_list'].($is_admin?"|怨듭?":"")); // 援щ텇?먭? | 濡??섏뼱 ?덉쓬
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

// '??, '?꾨땲??瑜?SELECT ?뺤떇?쇰줈 ?살쓬
function get_yn_select($name, $selected='1', $event='')
{
    $str = "<select name=\"$name\" $event>\n";
    if ($selected) {
        $str .= "<option value=\"1\" selected>??/option>\n";
        $str .= "<option value=\"0\">?꾨땲??/option>\n";
    } else {
        $str .= "<option value=\"1\">??/option>\n";
        $str .= "<option value=\"0\" selected>?꾨땲??/option>\n";
    }
    $str .= "</select>";
    return $str;
}

// ?좎쭨瑜?select 諛뺤뒪 ?뺤떇?쇰줈 ?삳뒗??
function date_select($date, $name='')
{
    global $g5;

    $s = '';
    if (substr($date, 0, 4) == "0000") {
        $date = G5_TIME_YMDHIS;
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    // ??
    $s .= "<select name='{$name}_y'>";
    for ($i=$m['0']-3; $i<=$m['0']+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>??\n";

    // ??
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>??\n";

    // ??
    $s .= "<select name='{$name}_d'>";
    for ($i=1; $i<=31; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>??\n";

    return $s;
}

// ?쒓컙??select 諛뺤뒪 ?뺤떇?쇰줈 ?삳뒗??
// 1.04.00
// 寃쎈ℓ???쒓컙 ?ㅼ젙??媛?ν븯寃??섎㈃??異붽???
function time_select($time, $name="")
{
    preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $m);

    // ??
    $s = "<select name='{$name}_h'>";
    for ($i=0; $i<=23; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>??\n";

    // 遺?
    $s .= "<select name='{$name}_i'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>遺?\n";

    // 珥?
    $s .= "<select name='{$name}_s'>";
    for ($i=0; $i<=59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>珥?\n";

    return $s;
}

// ?뚯썝 ?덉씠??
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
        $tmp_name = '<span class="sv_wrap hs-dropdown relative inline-flex">';
        $tmp_name .= '<a href="'.G5_BBS_URL.'/profile.php?mb_id='.$mb_id.'" class="sv_member hs-dropdown-toggle inline-flex items-center gap-1 text-default-800 hover:text-primary" style="color:var(--color-body-color,#313a46);font-weight:500;" title="'.$name.' 자기소개" target="_blank" onclick="return false;" aria-haspopup="menu" aria-expanded="false">'.$name.'</a>';
        $tmp_name .= '<span class="sv hs-dropdown-menu" role="menu" aria-orientation="vertical" style="display:none;">';
        $tmp_name .= '<a class="dropdown-item" href="'.G5_BBS_URL.'/memo_form.php?me_recv_mb_id='.$mb_id.'" onclick="win_memo(this.href); return false;">쪽지보내기</a>';

        if ($email) {
            $tmp_name .= '<a class="dropdown-item" href="'.G5_BBS_URL.'/formmail.php?mb_id='.$mb_id.'&name='.urlencode($name).'&email='.$email.'" onclick="win_email(this.href); return false;">메일보내기</a>';
        }

        if ($homepage) {
            $tmp_name .= '<a class="dropdown-item" href="'.$homepage.'" target="_blank">홈페이지</a>';
        }

        $tmp_name .= '<a class="dropdown-item" href="'.G5_BBS_URL.'/profile.php?mb_id='.$mb_id.'" onclick="win_profile(this.href); return false;">자기소개</a>';

        if ($bo_table) {
            $tmp_name .= '<a class="dropdown-item" href="'.G5_BBS_URL.'/board.php?bo_table='.$bo_table.'&sca=&sfl=mb_id,1&stx='.$mb_id.'">전체게시물</a>';
        }

        if (is_admin($mb_id)) {
            $tmp_name .= '<a class="dropdown-item" href="'.G5_ADMIN_URL.'/member_form.php?w=u&mb_id='.$mb_id.'" target="_blank">회원정보변경</a>';
            $tmp_name .= '<a class="dropdown-item" href="'.G5_ADMIN_URL.'/point_list.php?sfl=mb_id&stx='.$mb_id.'" target="_blank">포인트내역</a>';
        }

        $tmp_name .= '</span></span>';
    } else {
        $tmp_name = $name;
    }

    return $tmp_name;
}
// ?ㅽ궓 style sheet ?뚯씪 ?산린
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

// ?ㅽ궓 javascript ?뚯씪 ?산린
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

// HTML 留덉?留?泥섎━
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

        if (self::$is_end) return;  // ?щ윭踰??몄텧?대룄 ?쒕쾲留??ㅽ뻾?섍쾶 ?⑸땲??

        self::$is_end = 1;

        // ?꾩옱?묒냽??泥섎━
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

            // ?쒓컙??吏???묒냽? ??젣?쒕떎
            sql_query(" delete from {$g5['login_table']} where lo_datetime < '".date("Y-m-d H:i:s", G5_SERVER_TIME - (60 * $config['cf_login_minutes']))."' ");

            // 遺??overhead)???덈떎硫??뚯씠釉?理쒖쟻??
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
        諛묒쑝濡??ㅽ궓???ㅽ??쇱떆?멸? ?꾩튂?섎룄濡??섍쾶 ?쒕떎.
        */
        $title_find_pattern = '#(</title>[^<]*<link[^>]+>)#';
        if (preg_match($title_find_pattern, $buffer)) {
            $buffer = preg_replace($title_find_pattern, "$1$stylesheet", $buffer);
        } else {    // ?⑦꽩???녿떎硫??먮컮?ㅽ겕由쏀듃 肄붾뱶 ?꾩뿉 ?꾩튂?섍쾶 ?⑸땲??
            $javascript = $stylesheet. PHP_EOL. $javascript;
        }

        /*
        </head>
        <body>
        ?꾩뿉 ?ㅽ궓???먮컮?ㅽ겕由쏀듃媛 ?꾩튂?섎룄濡??섍쾶 ?쒕떎.
        */
        $nl = '';
        if($javascript)
            $nl = "\n";
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);
        
        $meta_tag = run_replace('html_process_add_meta', '');
        
        if( $meta_tag ){
            /*
            </title>content<body>
            ?꾩뿉 硫뷀??쒓렇媛 ?꾩튂 ?섎룄濡??섍쾶 ?쒕떎.
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
