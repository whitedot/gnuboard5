<?php
if (!defined('_GNUBOARD_')) exit;

// DB 연결
function sql_connect($host, $user, $pass, $db=G5_MYSQL_DB)
{
    global $g5;

    if(function_exists('mysqli_connect') && G5_MYSQLI_USE) {
        mysqli_report(MYSQLI_REPORT_OFF);
        $link = @mysqli_connect($host, $user, $pass, $db) or die('MySQL Host, User, Password, DB 정보에 오류가 있습니다.');

        // 연결 오류 발생 시 스크립트 종료
        if (mysqli_connect_errno()) {
            die('Connect Error: '.mysqli_connect_error());
        }
    } else {
        if (!function_exists('mysql_connect')) {
            die('MySQL이 설치되지 않아 mysql_connect 함수를 사용할 수 없습니다.');
        }
        $link = mysql_connect($host, $user, $pass) or die('MySQL Host, User, Password 정보에 오류가 있습니다.');
    }

    return $link;
}

// DB 선택
function sql_select_db($db, $connect)
{
    global $g5;

    if(function_exists('mysqli_select_db') && G5_MYSQLI_USE)
        return @mysqli_select_db($connect, $db);
    else
        return @mysql_select_db($db, $connect);
}

function sql_set_charset($charset, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    if(function_exists('mysqli_set_charset') && G5_MYSQLI_USE)
        mysqli_set_charset($link, $charset);
    else
        mysql_query(" set names {$charset} ", $link);
}

function sql_data_seek($result, $offset=0)
{
    if ( ! $result ) return;

    if(function_exists('mysqli_set_charset') && G5_MYSQLI_USE)
        mysqli_data_seek($result, $offset);
    else
        mysql_data_seek($result, $offset);
}

// mysqli_query 와 mysqli_error 를 한꺼번에 처리
// mysql connect resource 지정 - 명랑폐인님 제안
function sql_query($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    global $g5, $g5_debug;

    if(!$link)
        $link = $g5['connect_db'];

    // Blind SQL Injection 취약점 해결
    $sql = trim($sql);
    // union의 사용을 허락하지 않습니다.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    // `information_schema` DB로의 접근을 허락하지 않습니다.
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    $is_debug = get_permission_debug_show();
    
    $start_time = ($is_debug || G5_COLLECT_QUERY) ? get_microtime() : 0;

    if(function_exists('mysqli_query') && G5_MYSQLI_USE) {
        if ($error) {
            $result = @mysqli_query($link, $sql) or die("<p>$sql<p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            try {
                $result = @mysqli_query($link, $sql);
            } catch (Exception $e) {
                $result = null;
            }
        }
    } else {
        if ($error) {
            $result = @mysql_query($sql, $link) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysql_query($sql, $link);
        }
    }

    $end_time = ($is_debug || G5_COLLECT_QUERY) ? get_microtime() : 0;

    $error = null;
    $source = array();
    if ($is_debug || G5_COLLECT_QUERY) {
        if(function_exists('mysqli_error') && G5_MYSQLI_USE) {
            $error = array(
                'error_code' => mysqli_errno($link),
                'error_message' => mysqli_error($link),
            );
        } else {
            $error = array(
                'error_code' => mysql_errno($link),
                'error_message' => mysql_error($link),
            );
        }

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $found = false;

        foreach ($stack as $index => $trace) {
            if ($trace['function'] === 'sql_query') {
                $found = true;
            }
            if (isset($stack[$index + 1]) && $stack[$index + 1]['function'] === 'sql_fetch') {
                continue;
            }

            if ($found) {
                $trace['file'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $trace['file']);
                $source['file'] = $trace['file'];
                $source['line'] = $trace['line'];

                $parent = (isset($stack[$index + 1])) ? $stack[$index + 1] : array();
                if (isset($parent['function'])) {
                    if (in_array($trace['function'], array('sql_query', 'sql_fetch')) && (isset($parent['function']) && !in_array($parent['function'], array('sql_fetch', 'include', 'include_once', 'require', 'require_once')))) {
                        if (isset($parent['class']) && $parent['class']) {
                            $source['class'] = $parent['class'];
                            $source['function'] = $parent['function'];
                            $source['type'] = $parent['type'];
                        } else {
                            $source['function'] = $parent['function'];
                        }
                    }
                }
                break;
            }
        }

        $g5_debug['sql'][] = array(
            'sql' => $sql,
            'result' => $result,
            'success' => !!$result,
            'source' => $source,
            'error_code' => $error['error_code'],
            'error_message' => $error['error_message'],
            'start_time' => $start_time,
            'end_time' => $end_time,
        );
    }

    run_event('sql_query_after', $result, $sql, $start_time, $end_time, $error, $source);

    return $result;
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    $result = sql_query($sql, $error, $link);
    //$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysqli_errno() . " : " .  mysqli_error() . "<p>error file : $_SERVER['SCRIPT_NAME']");
    $row = sql_fetch_array($result);
    return $row;
}

// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    if( ! $result) return array();

    if(function_exists('mysqli_fetch_assoc') && G5_MYSQLI_USE)
        try {
            $row = @mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            $row = null;
        }
    else
        $row = @mysql_fetch_assoc($result);

    return $row;
}

// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
function sql_free_result($result)
{
    if(!is_resource($result)) return;

    if(function_exists('mysqli_free_result') && G5_MYSQLI_USE)
        return mysqli_free_result($result);
    else
        return mysql_free_result($result);
}

function sql_insert_id($link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    if(function_exists('mysqli_insert_id') && G5_MYSQLI_USE)
        return mysqli_insert_id($link);
    else
        return mysql_insert_id($link);
}

function sql_num_rows($result)
{
    if(function_exists('mysqli_num_rows') && G5_MYSQLI_USE)
        return mysqli_num_rows($result);
    else
        return mysql_num_rows($result);
}

function sql_field_names($table, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    $columns = array();

    $sql = " select * from `$table` limit 1 ";
    $result = sql_query($sql, $link);

    if(function_exists('mysqli_fetch_field') && G5_MYSQLI_USE) {
        while($field = mysqli_fetch_field($result)) {
            $columns[] = $field->name;
        }
    } else {
        $i = 0;
        $cnt = mysql_num_fields($result);
        while($i < $cnt) {
            $field = mysql_fetch_field($result, $i);
            $columns[] = $field->name;
            $i++;
        }
    }

    return $columns;
}

function sql_error_info($link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];

    if(function_exists('mysqli_error') && G5_MYSQLI_USE) {
        return mysqli_errno($link) . ' : ' . mysqli_error($link);
    } else {
        return mysql_errno($link) . ' : ' . mysql_error($link);
    }
}

// PHPMyAdmin 참고
function get_table_define($table, $crlf="\n")
{
    global $g5;

    // For MySQL < 3.23.20
    $schema_create = 'CREATE TABLE ' . $table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $schema_create .= '    ' . $row['Field'] . ' ' . $row['Type'];
        if (isset($row['Default']) && $row['Default'] != '')
        {
            $schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
        }
        if ($row['Null'] != 'YES')
        {
            $schema_create .= ' NOT NULL';
        }
        if ($row['Extra'] != '')
        {
            $schema_create .= ' ' . $row['Extra'];
        }
        $schema_create     .= ',' . $crlf;
    } // end while
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $kname    = $row['Key_name'];
        $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if ($kname != 'PRIMARY' && $row['Non_unique'] == 0) {
            $kname = "UNIQUE|$kname";
        }
        if ($comment == 'FULLTEXT') {
            $kname = 'FULLTEXT|$kname';
        }
        if (!isset($index[$kname])) {
            $index[$kname] = array();
        }
        if ($sub_part > 1) {
            $index[$kname][] = $row['Column_name'] . '(' . $sub_part . ')';
        } else {
            $index[$kname][] = $row['Column_name'];
        }
    } // end while
    sql_free_result($result);

    foreach((array) $index as $x => $columns){
        $schema_create     .= ',' . $crlf;
        if ($x == 'PRIMARY') {
            $schema_create .= '    PRIMARY KEY (';
        } else if (substr($x, 0, 6) == 'UNIQUE') {
            $schema_create .= '    UNIQUE ' . substr($x, 7) . ' (';
        } else if (substr($x, 0, 8) == 'FULLTEXT') {
            $schema_create .= '    FULLTEXT ' . substr($x, 9) . ' (';
        } else {
            $schema_create .= '    KEY ' . $x . ' (';
        }
        $schema_create     .= implode(', ', $columns) . ')';
    } // end while

    $schema_create .= $crlf . ') ENGINE=MyISAM DEFAULT CHARSET=utf8';

    return get_db_create_replace($schema_create);
} // end of the 'PMA_getTableDef()' function

// 테이블에서 INDEX(키) 사용여부 검사
function explain($sql)
{
    if (preg_match("/^(select)/i", trim($sql))) {
        $q = "explain $sql";
        echo $q;
        $row = sql_fetch($q);
        if (!$row['key']) $row['key'] = "NULL";
        echo " <font color=blue>(type={$row['type']} , key={$row['key']})</font>";
    }
}

// mysqli_real_escape_string 의 alias 기능을 한다.
function sql_real_escape_string($str, $link=null)
{
    global $g5;

    if(!$link)
        $link = $g5['connect_db'];
    
    if(function_exists('mysqli_connect') && G5_MYSQLI_USE) {
        return mysqli_real_escape_string($link, $str);
    }

    return mysql_real_escape_string($str, $link);
}

function escape_trim($field)
{
    $str = call_user_func(G5_ESCAPE_FUNCTION, $field);
    return $str;
}
