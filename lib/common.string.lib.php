<?php
if (!defined('_GNUBOARD_')) exit;

// way.co.kr 의 wayboard 참고
function url_auto_link($str)
{
    global $g5;
    global $config;
    
    if ($replace_str = run_replace('url_auto_link_before', '', $str)) {
        return $replace_str;
    }
    
    $ori_str = $str;
    
    // 140326 유창화님 제안코드로 수정
    // http://sir.kr/pg_lecture/461
    // http://sir.kr/pg_lecture/463
    $attr_nofollow = (function_exists('check_html_link_nofollow') && check_html_link_nofollow('url_auto_link')) ? ' rel="nofollow"' : '';
    $str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"), $str);
    //$str = preg_replace("`(?:(?:(?:href|src)\s*=\s*(?:\"|'|)){0})((http|https|ftp|telnet|news|mms)://[^\"'\s()]+)`", "<A HREF=\"\\1\" TARGET='{$config['cf_link_target']}'>\\1</A>", $str);
    $str = preg_replace("/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#!=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET=\"{$config['cf_link_target']}\" $attr_nofollow>\\2</A>", $str);
    $str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET=\"{$config['cf_link_target']}\" $attr_nofollow>\\2</A>", $str);
    $str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href=\"mailto:\\0\" $attr_nofollow>\\0</a>", $str);
    $str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"), array("&nbsp;", "&lt;", "&gt;", "&#039;"), $str);

    return run_replace('url_auto_link', $str, $ori_str);
}

// 제목을 변환
function conv_subject($subject, $len, $suffix='')
{
    return get_text(cut_str($subject, $len, $suffix));
}

// 내용을 변환
function conv_content($content, $html, $filter=true)
{
    global $config, $board;

    if ($html)
    {
        $source = array();
        $target = array();

        $source[] = "//";
        $target[] = "";

        if ($html == 2) { // 자동 줄바꿈
            $source[] = "/\n/";
            $target[] = "<br/>";
        }

        // 테이블 태그의 개수를 세어 테이블이 깨지지 않도록 한다.
        $table_begin_count = substr_count(strtolower($content), "<table");
        $table_end_count = substr_count(strtolower($content), "</table");
        for ($i=$table_end_count; $i<$table_begin_count; $i++)
        {
            $content .= "</table>";
        }

        $content = preg_replace($source, $target, $content);

        if($filter)
            $content = html_purifier($content);
    }
    else // text 이면
    {
        // & 처리 : &amp; &nbsp; 등의 코드를 정상 출력함
        $content = html_symbol($content);

        // 공백 처리
		//$content = preg_replace("/  /", "&nbsp; ", $content);
		$content = str_replace("  ", "&nbsp; ", $content);
		$content = str_replace("\n ", "\n&nbsp;", $content);

        $content = get_text($content, 1);
        $content = url_auto_link($content);
    }

    return $content;
}

function check_html_link_nofollow($type=''){
    return true;
}

/**
 * HTMLPurifier 필터를 거친 HTML 코드를 반환
 * 
 * http://htmlpurifier.org/
 * Standards-Compliant HTML Filtering
 * Safe  : HTML Purifier defeats XSS with an audited whitelist
 * Clean : HTML Purifier ensures standards-compliant output
 * Open  : HTML Purifier is open-source and highly customizable
 *
 * @param string $html
 * @return string
 */
function html_purifier($html)
{
    global $is_admin, $write;

    $f = file(G5_PLUGIN_PATH . '/htmlpurifier/safeiframe.txt');
    $domains = array();
    foreach ($f as $domain) {
        // 첫행이 # 이면 주석 처리
        if (!preg_match("/^#/", $domain)) {
            $domain = trim($domain);
            if ($domain) {
                array_push($domains, $domain);
            }
        }
    }
    // 글쓴이가 관리자인 경우에만 현재 사이트 도메인을 허용
    if (isset($write['mb_id']) && $write['mb_id'] && is_admin($write['mb_id'])) {
        array_push($domains, $_SERVER['HTTP_HOST'] . '/');
    }
    $safeiframe = implode('|', run_replace('html_purifier_safeiframes', $domains, $html));

    include_once(G5_PLUGIN_PATH . '/htmlpurifier/HTMLPurifier.standalone.php');
    include_once(G5_PLUGIN_PATH . '/htmlpurifier/extend.video.php');

    $config = HTMLPurifier_Config::createDefault();
    // data/cache 디렉토리에 CSS, HTML, URI 디렉토리 등을 만든다.
    $config->set('Cache.SerializerPath', G5_DATA_PATH . '/cache');
    $config->set('HTML.SafeEmbed', false);
    $config->set('HTML.SafeObject', false);
    $config->set('Output.FlashCompat', false);
    $config->set('HTML.SafeIframe', true);
    if ((function_exists('check_html_link_nofollow') && check_html_link_nofollow('html_purifier'))) {
        $config->set('HTML.Nofollow', true); // rel=nofollow 으로 스팸유입을 줄임
    }
    $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(' . preg_replace('/\\\?\./', '\.', $safeiframe) . ')%');
    $config->set('Attr.AllowedFrameTargets', array('_blank'));
    //유튜브, 비메오 전체화면 가능하게 하기
    $config->set('Filter.Custom', array(new HTMLPurifier_Filter_Iframevideo()));

    /*
     * HTMLPurifier 설정을 변경할 수 있는 Event hook
     * 리스너에서는 첫번째 인자($config)로 `HTMLPurifier_Config` 객체를 받을 수 있다
     */
    run_event('html_purifier_config', $config, array(
        'html' => $html,
        'write' => $write,
        'is_admin' => $is_admin
    )
    );

    // 커스텀 URI 필터 등록
    $def = $config->getDefinition('URI', true); // URI 정의 가져오기
    $def->addFilter(new HTMLPurifierContinueParamFilter(), $config); // 커스텀 필터 추가

    $purifier = new HTMLPurifier($config);

    return run_replace('html_purifier_result', $purifier->purify($html), $purifier, $html);
}

function cut_str($str, $len, $suffix="…")
{
    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    $str_len = count($arr_str);

    if ($str_len >= $len) {
        $slice_str = array_slice($arr_str, 0, $len);
        $str = join("", $slice_str);

        return $str . ($str_len > $len ? $suffix : '');
    } else {
        $str = join("", $arr_str);
        return $str;
    }
}

// TEXT 형식으로 변환
function get_text($str, $html=0, $restore=false)
{
    $source[] = "<";
    $target[] = "&lt;";
    $source[] = ">";
    $target[] = "&gt;";
    $source[] = "\"";
    $target[] = "&#034;";
    $source[] = "\'";
    $target[] = "&#039;";

    if($restore)
        $str = str_replace($target, $source, $str);

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) {
        $str = html_symbol($str);
    }

    if ($html) {
        $source[] = "\n";
        $target[] = "<br/>";
    }

    return str_replace($source, $target, $str);
}

// 3.31
// HTML SYMBOL 변환
// &nbsp; &amp; &middot; 등을 정상으로 출력
function html_symbol($str)
{
    return $str ? preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str) : "";
}

// 악성태그 변환
function bad_tag_convert($code)
{
    global $view;
    global $member, $is_admin;

    if ($is_admin && $member['mb_id'] !== $view['mb_id']) {
        //$code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>(\<\/(embed|object)\>)?#i",
        // embed 또는 object 태그를 막지 않는 경우 필터링이 되도록 수정
        $code = preg_replace_callback("#(\<(embed|object)[^\>]*)\>?(\<\/(embed|object)\>)?#i", '_callback_bad_tag_convert', $code);
    }

    return preg_replace("/\<([\/]?)(script|iframe|form)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
}

function _callback_bad_tag_convert($matches){
    return "<div class=\"embedx\">보안문제로 인하여 관리자 아이디로는 embed 또는 object 태그를 볼 수 없습니다. 확인하시려면 관리권한이 없는 다른 아이디로 접속하세요.</div>";
}

function normalize_utf8_string($string) {
    // utf8mb4 환경과 mb_ord 함수가 지원되지 않는 환경에서는 제외한다.
    if (G5_DB_CHARSET === 'utf8mb4' || !function_exists('mb_ord')) {
        return $string;
    }
    
    // Unicode 특수 문자를 일반 문자로 변환
    $normalized = preg_replace_callback('/[\x{1D400}-\x{1D7FF}]/u', '_callback_normalizeString', $string);

    return $normalized;
}

function _callback_normalizeString($matches){
    $charCode = mb_ord($matches[0], 'UTF-8');
    // 변환 테이블에서 일반 문자로 매핑
    return chr(($charCode - 0x1D400) % 26 + ord('A'));
}

// 문자열에 utf8 문자가 들어 있는지 검사하는 함수
// 코드 : http://in2.php.net/manual/en/function.mb-check-encoding.php#95289
function is_utf8($str)
{
    $len = strlen($str);
    for($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
}

// UTF-8 문자열 자르기
// 출처 : https://www.google.co.kr/search?q=utf8_strcut&aq=f&oq=utf8_strcut&aqs=chrome.0.57j0l3.826j0&sourceid=chrome&ie=UTF-8
function utf8_strcut( $str, $size, $suffix='...' )
{
    if( function_exists('mb_strlen') && function_exists('mb_substr') ){
        
        if(mb_strlen($str)<=$size) {
            return $str;
        } else {
            $str = mb_substr($str, 0, $size, 'utf-8');
            $str .= $suffix;
        }

    } else {
        $substr = substr( $str, 0, $size * 2 );
        $multi_size = preg_match_all( '/[\x80-\xff]/', $substr, $multi_chars );

        if ( $multi_size > 0 )
            $size = $size + intval( $multi_size / 3 ) - 1;

        if ( strlen( $str ) > $size ) {
            $str = substr( $str, 0, $size );
            $str = preg_replace( '/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str );
            $str .= $suffix;
        }
    }

    return $str;
}

/*
-----------------------------------------------------------
    Charset 을 변환하는 함수
-----------------------------------------------------------
iconv 함수가 있으면 iconv 로 변환하고
없으면 mb_convert_encoding 함수를 사용한다.
둘다 없으면 사용할 수 없다.
*/
function convert_charset($from_charset, $to_charset, $str)
{

    if( function_exists('iconv') )
        return iconv($from_charset, $to_charset, $str);
    elseif( function_exists('mb_convert_encoding') )
        return mb_convert_encoding($str, $to_charset, $from_charset);
    else
        die("Not found 'iconv' or 'mbstring' library in server.");
}

// CHARSET 변경 : euc-kr -> utf-8
function iconv_utf8($str)
{
    return iconv('euc-kr', 'utf-8', $str);
}

// CHARSET 변경 : utf-8 -> euc-kr
function iconv_euckr($str)
{
    return iconv('utf-8', 'euc-kr', $str);
}

// HTML 특수문자 변환 htmlspecialchars
function htmlspecialchars2($str)
{
    $trans = array("\"" => "&#034;", "'" => "&#039;", "<"=>"&#060;", ">"=>"&#062;");
    $str = strtr($str, $trans);
    return $str;
}

// unescape nl 얻기
function conv_unescape_nl($str)
{
    $search = array('\\r', '\r', '\\n', '\n');
    $replace = array('', '', "\n", "\n");

    return str_replace($search, $replace, $str);
}

// 검색어 특수문자 제거
function get_search_string($stx)
{
    $stx_pattern = array();
    $stx_pattern[] = '#\.*/+#';
    $stx_pattern[] = '#\\\*#';
    $stx_pattern[] = '#\.{2,}#';
    $stx_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';

    $stx_replace = array();
    $stx_replace[] = '';
    $stx_replace[] = '';
    $stx_replace[] = '.';
    $stx_replace[] = '';

    $stx = preg_replace($stx_pattern, $stx_replace, $stx);

    return $stx;
}

// XSS 관련 태그 제거
function clean_xss_tags($str, $check_entities=0, $is_remove_tags=0, $cur_str_len=0, $is_trim_both=1)
{
    if( $is_trim_both ) {
        // tab('\t'), formfeed('\f'), vertical tab('\v'), newline('\n'), carriage return('\r') 를 제거한다.
        $str = preg_replace("#[\t\f\v\n\r]#", '', $str);
    }

    if( $is_remove_tags ){
        $str = strip_tags($str);
    }

    if( $cur_str_len ){
        $str = utf8_strcut($str, $cur_str_len, '');
    }

    $str_len = strlen($str);
    
    $i = 0;
    while($i <= $str_len){
        $result = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
        
        if( $check_entities ){
            $result = str_replace(array('&colon;', '&lpar;', '&rpar;', '&NewLine;', '&Tab;'), '', $result);
        }
        
        $result = preg_replace('#([^\p{L}]|^)(?:javascript|jar|applescript|vbscript|vbs|wscript|jscript|behavior|mocha|livescript|view-source)\s*:(?:.*?([/\\\;()\'">]|$))#ius',
                '$1$2', $result);

        // 따옴표 + 속성으로 강제 진입 차단 (예: "style=..., 'onerror=...)
        $result = preg_replace('/["\']\s*(?:on\w+|style)\s*=\s*/i', '', $result);
        
        if((string)$result === (string)$str) break;

        $str = $result;
        $i++;
    }

    return $str;
}

// XSS 어트리뷰트 태그 제거
function clean_xss_attributes($str)
{
    $xss_attributes_string = 'onAbort|onActivate|onAttribute|onAfterPrint|onAfterScriptExecute|onAfterUpdate|onAnimationCancel|onAnimationEnd|onAnimationIteration|onAnimationStart|onAriaRequest|onAutoComplete|onAutoCompleteError|onAuxClick|onBeforeActivate|onBeforeCopy|onBeforeCut|onBeforeDeactivate|onBeforeEditFocus|onBeforePaste|onBeforePrint|onBeforeScriptExecute|onBeforeUnload|onBeforeUpdate|onBegin|onBlur|onBounce|onCancel|onCanPlay|onCanPlayThrough|onCellChange|onChange|onClick|onClose|onCommand|onCompassNeedsCalibration|onContextMenu|onControlSelect|onCopy|onCueChange|onCut|onDataAvailable|onDataSetChanged|onDataSetComplete|onDblClick|onDeactivate|onDeviceLight|onDeviceMotion|onDeviceOrientation|onDeviceProximity|onDrag|onDragDrop|onDragEnd|onDragEnter|onDragLeave|onDragOver|onDragStart|onDrop|onDurationChange|onEmptied|onEnd|onEnded|onError|onErrorUpdate|onExit|onFilterChange|onFinish|onFocus|onFocusIn|onFocusOut|onFormChange|onFormInput|onFullScreenChange|onFullScreenError|onGotPointerCapture|onHashChange|onHelp|onInput|onInvalid|onKeyDown|onKeyPress|onKeyUp|onLanguageChange|onLayoutComplete|onLoad|onLoadedData|onLoadedMetaData|onLoadStart|onLoseCapture|onLostPointerCapture|onMediaComplete|onMediaError|onMessage|onMouseDown|onMouseEnter|onMouseLeave|onMouseMove|onMouseOut|onMouseOver|onMouseUp|onMouseWheel|onMove|onMoveEnd|onMoveStart|onMozFullScreenChange|onMozFullScreenError|onMozPointerLockChange|onMozPointerLockError|onMsContentZoom|onMsFullScreenChange|onMsFullScreenError|onMsGestureChange|onMsGestureDoubleTap|onMsGestureEnd|onMsGestureHold|onMsGestureStart|onMsGestureTap|onMsGotPointerCapture|onMsInertiaStart|onMsLostPointerCapture|onMsManipulationStateChanged|onMsPointerCancel|onMsPointerDown|onMsPointerEnter|onMsPointerLeave|onMsPointerMove|onMsPointerOut|onMsPointerOver|onMsPointerUp|onMsSiteModeJumpListItemRemoved|onMsThumbnailClick|onOffline|onOnline|onOutOfSync|onPage|onPageHide|onPageShow|onPaste|onPause|onPlay|onPlaying|onPointerCancel|onPointerDown|onPointerEnter|onPointerLeave|onPointerLockChange|onPointerLockError|onPointerMove|onPointerOut|onPointerOver|onPointerUp|onPopState|onProgress|onPropertyChange|onqt_error|onRateChange|onReadyStateChange|onReceived|onRepeat|onReset|onResize|onResizeEnd|onResizeStart|onResume|onReverse|onRowDelete|onRowEnter|onRowExit|onRowInserted|onRowsDelete|onRowsEnter|onRowsExit|onRowsInserted|onScroll|onSearch|onSeek|onSeeked|onSeeking|onSelect|onSelectionChange|onSelectStart|onStalled|onStorage|onStorageCommit|onStart|onStop|onShow|onSyncRestored|onSubmit|onSuspend|onSynchRestored|onTimeError|onTimeUpdate|onTimer|onTrackChange|onTransitionEnd|onToggle|onTouchCancel|onTouchEnd|onTouchLeave|onTouchMove|onTouchStart|onTransitionCancel|onTransitionEnd|onUnload|onURLFlip|onUserProximity|onVolumeChange|onWaiting|onWebKitAnimationEnd|onWebKitAnimationIteration|onWebKitAnimationStart|onWebKitFullScreenChange|onWebKitFullScreenError|onWebKitTransitionEnd|onWheel';
    
    do {
        $count = $temp_count = 0;

        $str = preg_replace(
            '/(.*)(?:' . $xss_attributes_string . ')(?:\s*=\s*)(?:\'(?:.*?)\'|"(?:.*?)")(.*)/ius',
            '$1-$2-$3-$4',
            $str,
            -1,
            $temp_count
        );
        $count += $temp_count;

        $str = preg_replace(
            '/(.*)(?:' . $xss_attributes_string . ')\s*=\s*(?:[^\s>]*)(.*)/ius',
            '$1$2',
            $str,
            -1,
            $temp_count
        );
        $count += $temp_count;

    } while ($count);

    return $str;
}

// QUERY STRING 에 포함된 XSS 태그 제거
function clean_query_string($query, $amp=true)
{
    $qstr = trim($query);

    parse_str($qstr, $out);

    if(is_array($out)) {
        $q = array();

        foreach($out as $key=>$val) {
            if(($key && is_array($key)) || ($val && is_array($val))){
                $q[$key] = $val;
                continue;
            }

            $key = strip_tags(trim($key));
            $val = trim($val);

            switch($key) {
                case 'wr_id':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'sca':
                    $val = clean_xss_tags($val);
                    $q[$key] = $val;
                    break;
                case 'sfl':
                    $val = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $val);
                    $q[$key] = $val;
                    break;
                case 'stx':
                    $val = get_search_string($val);
                    $q[$key] = $val;
                    break;
                case 'sst':
                    $val = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $val);
                    $q[$key] = $val;
                    break;
                case 'sod':
                    $val = preg_match("/^(asc|desc)$/i", $val) ? $val : '';
                    $q[$key] = $val;
                    break;
                case 'sop':
                    $val = preg_match("/^(or|and)$/i", $val) ? $val : '';
                    $q[$key] = $val;
                    break;
                case 'spt':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'page':
                    $val = (int)preg_replace('/[^0-9]/', '', $val);
                    $q[$key] = $val;
                    break;
                case 'w':
                    $val = substr($val, 0, 2);
                    $q[$key] = $val;
                    break;
                case 'bo_table':
                    $val = preg_replace('/[^a-z0-9_]/i', '', $val);
                    $val = substr($val, 0, 20);
                    $q[$key] = $val;
                    break;
                case 'gr_id':
                    $val = preg_replace('/[^a-z0-9_]/i', '', $val);
                    $q[$key] = $val;
                    break;
                default:
                    $val = clean_xss_tags($val);
                    $q[$key] = $val;
                    break;
            }
        }

        if($amp)
            $sep = '&amp;';
        else
            $sep ='&';

        $str = http_build_query($q, '', $sep);
    } else {
        $str = clean_xss_tags($qstr);
    }

    return $str;
}
