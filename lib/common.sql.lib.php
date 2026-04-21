<?php
if (!defined('_GNUBOARD_')) exit;

class G5PdoResult
{
    public $num_rows = 0;

    private $statement = null;
    private $rows = array();
    private $position = 0;
    private $field_index = 0;
    private $fields = array();
    private $loaded_all = false;

    public function __construct(PDOStatement $statement, array $fields = array())
    {
        $this->statement = $statement;
        $this->fields = array_values($fields);

        $row_count = (int) $statement->rowCount();
        if ($row_count > 0) {
            $this->num_rows = $row_count;
        }
    }

    private function loadNextRow()
    {
        if ($this->loaded_all || !$this->statement) {
            return null;
        }

        $row = $this->statement->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            $this->loaded_all = true;
            $this->num_rows = count($this->rows);
            $this->statement->closeCursor();
            return null;
        }

        $this->rows[] = $row;

        return $row;
    }

    private function ensureRowsLoadedUntil($offset)
    {
        while (!$this->loaded_all && count($this->rows) <= $offset) {
            if ($this->loadNextRow() === null) {
                break;
            }
        }
    }

    private function ensureAllRowsLoaded()
    {
        while (!$this->loaded_all) {
            if ($this->loadNextRow() === null) {
                break;
            }
        }
    }

    public function fetchAssoc()
    {
        if (isset($this->rows[$this->position])) {
            return $this->rows[$this->position++];
        }

        $this->ensureRowsLoadedUntil($this->position);

        if (!isset($this->rows[$this->position])) {
            return null;
        }

        return $this->rows[$this->position++];
    }

    public function dataSeek($offset = 0)
    {
        $offset = (int) $offset;

        if ($offset < 0) {
            $offset = 0;
        }

        $this->ensureRowsLoadedUntil($offset);

        $max_offset = $this->loaded_all ? count($this->rows) : max($offset, count($this->rows));
        if ($offset > $max_offset) {
            $offset = $max_offset;
        }

        $this->position = $offset;
    }

    public function fetchField()
    {
        if (!isset($this->fields[$this->field_index])) {
            return false;
        }

        return $this->fields[$this->field_index++];
    }

    public function free()
    {
        if ($this->statement instanceof PDOStatement) {
            $this->statement->closeCursor();
        }

        $this->statement = null;
        $this->rows = array();
        $this->fields = array();
        $this->num_rows = 0;
        $this->position = 0;
        $this->field_index = 0;
        $this->loaded_all = true;
    }

    public function getNumRows()
    {
        if (!$this->loaded_all && $this->num_rows === 0) {
            $this->ensureAllRowsLoaded();
        }

        return $this->loaded_all ? count($this->rows) : $this->num_rows;
    }
}

function sql_get_connection($link = null)
{
    global $g5;

    if ($link instanceof PDO) {
        return $link;
    }

    if (isset($g5['connect_db']) && $g5['connect_db'] instanceof PDO) {
        return $g5['connect_db'];
    }

    return null;
}

function sql_store_error($link = null, $statement = null, $exception = null)
{
    global $g5;

    $error = array(
        'error_code' => '',
        'error_message' => '',
    );

    if ($exception instanceof Exception) {
        $error['error_code'] = (string) $exception->getCode();
        $error['error_message'] = $exception->getMessage();
    } elseif ($statement instanceof PDOStatement) {
        $info = $statement->errorInfo();
        $error['error_code'] = isset($info[1]) ? (string) $info[1] : (isset($info[0]) ? (string) $info[0] : '');
        $error['error_message'] = isset($info[2]) ? $info[2] : '';
    } elseif ($link instanceof PDO) {
        $info = $link->errorInfo();
        $error['error_code'] = isset($info[1]) ? (string) $info[1] : (isset($info[0]) ? (string) $info[0] : '');
        $error['error_message'] = isset($info[2]) ? $info[2] : '';
    }

    if ($error['error_code'] === '00000') {
        $error['error_code'] = '';
    }
    if ($error['error_message'] === null) {
        $error['error_message'] = '';
    }

    $g5['pdo_last_error'] = $error;

    return $error;
}

function sql_get_last_error()
{
    global $g5;

    if (!isset($g5['pdo_last_error']) || !is_array($g5['pdo_last_error'])) {
        return array(
            'error_code' => '',
            'error_message' => '',
        );
    }

    return $g5['pdo_last_error'];
}

function sql_store_affected_rows($affected_rows = 0)
{
    global $g5;

    $g5['pdo_last_affected_rows'] = (int) $affected_rows;

    return $g5['pdo_last_affected_rows'];
}

function sql_get_last_affected_rows()
{
    global $g5;

    return isset($g5['pdo_last_affected_rows']) ? (int) $g5['pdo_last_affected_rows'] : 0;
}

function sql_collect_field_meta(PDOStatement $statement)
{
    $fields = array();
    $count = $statement->columnCount();

    for ($i = 0; $i < $count; $i++) {
        $meta = $statement->getColumnMeta($i);
        $field = new stdClass();
        $field->name = isset($meta['name']) ? $meta['name'] : '';
        $fields[] = $field;
    }

    return $fields;
}

function sql_validate_database_name($db)
{
    $db = trim((string) $db);
    if ($db === '') {
        return '';
    }

    if (preg_match('/[\x00-\x1F\x7F]/', $db)) {
        return '';
    }

    return $db;
}

function sql_validate_charset($charset)
{
    $charset = trim((string) $charset);

    return ($charset !== '' && preg_match('/^[A-Za-z0-9_]+$/', $charset)) ? $charset : '';
}

// DB 연결
function sql_connect($host, $user, $pass, $db=G5_MYSQL_DB)
{
    $charset = sql_validate_charset(defined('G5_DB_CHARSET') ? G5_DB_CHARSET : 'utf8');
    if (!$charset) {
        $charset = 'utf8';
    }
    $dsn = 'mysql:host=' . $host;

    if ($db !== null && $db !== '') {
        $validated_db = sql_validate_database_name($db);
        if (!$validated_db) {
            die('MySQL DB 정보에 오류가 있습니다.');
        }

        $dsn .= ';dbname=' . $validated_db;
    }

    $dsn .= ';charset=' . $charset;

    try {
        $link = new PDO($dsn, $user, $pass, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        ));
    } catch (PDOException $e) {
        die('MySQL Host, User, Password, DB 정보에 오류가 있습니다.');
    }

    sql_store_error($link);

    return $link;
}

// DB 선택
function sql_select_db($db, $connect)
{
    $connect = sql_get_connection($connect);

    if (!$connect) {
        return false;
    }

    try {
        $validated_db = sql_validate_database_name($db);
        if (!$validated_db) {
            return false;
        }

        return $connect->exec('USE `' . str_replace('`', '``', $validated_db) . '`') !== false;
    } catch (PDOException $e) {
        sql_store_error($connect, null, $e);
        return false;
    }
}

function sql_set_charset($charset, $link=null)
{
    $link = sql_get_connection($link);

    if (!$link) {
        return false;
    }

    try {
        $validated_charset = sql_validate_charset($charset);
        if (!$validated_charset) {
            return false;
        }

        return $link->exec("SET NAMES {$validated_charset}") !== false;
    } catch (PDOException $e) {
        sql_store_error($link, null, $e);
        return false;
    }
}

function sql_data_seek($result, $offset=0)
{
    if ($result instanceof G5PdoResult) {
        $result->dataSeek($offset);
    }
}

function sql_normalize_params($params)
{
    $normalized = array();

    foreach ((array) $params as $key => $value) {
        if (is_int($key)) {
            $normalized[$key + 1] = $value;
            continue;
        }

        $key = (string) $key;
        if ($key !== '' && $key[0] !== ':') {
            $key = ':' . $key;
        }

        $normalized[$key] = $value;
    }

    return $normalized;
}

function sql_bind_prepared_params(PDOStatement $statement, $params)
{
    foreach (sql_normalize_params($params) as $key => $value) {
        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        } elseif ($value === null) {
            $type = PDO::PARAM_NULL;
        } else {
            $type = PDO::PARAM_STR;
        }

        $statement->bindValue($key, $value, $type);
    }
}

function sql_quote_identifier($identifier)
{
    $identifier = trim((string) $identifier);
    if ($identifier === '') {
        return '';
    }

    $parts = explode('.', $identifier);
    $quoted = array();

    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '' || !preg_match('/^[A-Za-z0-9_$]+$/', $part)) {
            return '';
        }

        $quoted[] = '`' . $part . '`';
    }

    return implode('.', $quoted);
}

function sql_execute_query($sql, $params=array(), $error=G5_DISPLAY_SQL_ERROR, $link=null, $return_statement=false)
{
    global $g5, $g5_debug;

    if ($error instanceof PDO && $link === null) {
        $link = $error;
        $error = G5_DISPLAY_SQL_ERROR;
    }

    $link = sql_get_connection($link);

    if (!$link) {
        sql_store_affected_rows(0);
        if ($error) {
            die('DB 연결 정보가 올바르지 않습니다.');
        }

        sql_store_error();
        return false;
    }

    // Blind SQL Injection 취약점 해결
    $sql = trim($sql);
    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    $is_debug = get_permission_debug_show();
    $start_time = ($is_debug || G5_COLLECT_QUERY) ? get_microtime() : 0;

    $result = false;
    $statement = null;
    $source = array();

    try {
        $statement = $link->prepare($sql);
        sql_bind_prepared_params($statement, $params);
        $statement->execute();
        sql_store_error($link, $statement);
        sql_store_affected_rows($statement->rowCount());

        if ($return_statement) {
            $result = $statement;
        } elseif ($statement->columnCount() > 0) {
            $fields = sql_collect_field_meta($statement);
            $result = new G5PdoResult($statement, $fields);
        } else {
            $result = true;
        }
    } catch (Exception $e) {
        $result = false;
        sql_store_affected_rows(0);
        $error_info = sql_store_error($link, $statement, $e);

        if ($error) {
            die("<p>{$sql}<p>" . $error_info['error_code'] . " : " . $error_info['error_message'] . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        }
    }

    $end_time = ($is_debug || G5_COLLECT_QUERY) ? get_microtime() : 0;
    $error_info = sql_get_last_error();

    if ($is_debug || G5_COLLECT_QUERY) {
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $found = false;
        $sql_functions = array('sql_query', 'sql_query_prepared', 'sql_fetch', 'sql_fetch_prepared');

        foreach ($stack as $index => $trace) {
            if (isset($trace['function']) && in_array($trace['function'], $sql_functions, true)) {
                $found = true;
            }
            if (isset($stack[$index + 1]['function']) && in_array($stack[$index + 1]['function'], array('sql_fetch', 'sql_fetch_prepared'), true)) {
                continue;
            }

            if ($found) {
                if (isset($trace['file'])) {
                    $trace['file'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $trace['file']);
                    $source['file'] = $trace['file'];
                }
                if (isset($trace['line'])) {
                    $source['line'] = $trace['line'];
                }

                $parent = isset($stack[$index + 1]) ? $stack[$index + 1] : array();
                if (isset($parent['function'])) {
                    if (in_array($trace['function'], $sql_functions, true) && !in_array($parent['function'], array('sql_fetch', 'sql_fetch_prepared', 'include', 'include_once', 'require', 'require_once'), true)) {
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
            'error_code' => $error_info['error_code'],
            'error_message' => $error_info['error_message'],
            'start_time' => $start_time,
            'end_time' => $end_time,
        );
    }

    run_event('sql_query_after', $result, $sql, $start_time, $end_time, $error_info, $source);

    return $result;
}

// PDO 기반 쿼리 처리
function sql_query($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    return sql_execute_query($sql, array(), $error, $link);
}

function sql_query_prepared($sql, $params=array(), $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    return sql_execute_query($sql, $params, $error, $link);
}

function sql_statement($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    return sql_execute_query($sql, array(), $error, $link, true);
}

function sql_statement_prepared($sql, $params=array(), $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    return sql_execute_query($sql, $params, $error, $link, true);
}

function sql_table_exists($table_name, $link=null)
{
    $result = sql_query_prepared('SHOW TABLES LIKE :table_name', array(
        'table_name' => $table_name,
    ), false, $link);

    return $result && sql_num_rows($result) > 0;
}

function sql_set_time_zone($timezone, $link=null)
{
    return sql_query_prepared('SET time_zone = :time_zone', array(
        'time_zone' => $timezone,
    ), G5_DISPLAY_SQL_ERROR, $link);
}

function sql_reset_session_sql_mode($link=null)
{
    return sql_query("SET SESSION sql_mode = ''", G5_DISPLAY_SQL_ERROR, $link);
}

function sql_lock_tables_write($tables, $link=null)
{
    $tables = (array) $tables;
    $parts = array();

    foreach ($tables as $table) {
        $quoted_table = sql_quote_identifier($table);
        if (!$quoted_table) {
            return false;
        }

        $parts[] = $quoted_table . ' WRITE';
    }

    if (empty($parts)) {
        return false;
    }

    return sql_query('LOCK TABLE ' . implode(', ', $parts), G5_DISPLAY_SQL_ERROR, $link);
}

function sql_unlock_tables($link=null)
{
    return sql_query('UNLOCK TABLES', G5_DISPLAY_SQL_ERROR, $link);
}

function sql_describe_table($table_name, $error=false, $link=null)
{
    $quoted_table = sql_quote_identifier($table_name);
    if (!$quoted_table) {
        return false;
    }

    return sql_query('DESC ' . $quoted_table, $error, $link);
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    if ($error instanceof PDO && $link === null) {
        $link = $error;
        $error = G5_DISPLAY_SQL_ERROR;
    }

    $result = sql_query($sql, $error, $link);
    $row = sql_fetch_array($result);

    return $row;
}

function sql_fetch_prepared($sql, $params=array(), $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    $result = sql_query_prepared($sql, $params, $error, $link);
    $row = sql_fetch_array($result);

    return $row;
}

function sql_fetch_all($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    $result = sql_query($sql, $error, $link);
    $rows = array();

    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    sql_free_result($result);

    return $rows;
}

function sql_fetch_all_prepared($sql, $params=array(), $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    $result = sql_query_prepared($sql, $params, $error, $link);
    $rows = array();

    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    sql_free_result($result);

    return $rows;
}

function sql_fetch_value($sql, $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    $row = sql_fetch($sql, $error, $link);

    if (!is_array($row) || empty($row)) {
        return null;
    }

    return reset($row);
}

function sql_fetch_value_prepared($sql, $params=array(), $error=G5_DISPLAY_SQL_ERROR, $link=null)
{
    $row = sql_fetch_prepared($sql, $params, $error, $link);

    if (!is_array($row) || empty($row)) {
        return null;
    }

    return reset($row);
}

// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    if (!$result) {
        return array();
    }

    if ($result instanceof G5PdoResult) {
        $row = $result->fetchAssoc();
        return $row ? $row : null;
    }

    return array();
}

// 결과 메모리 해제
function sql_free_result($result)
{
    if ($result instanceof G5PdoResult) {
        $result->free();
    }

    return true;
}

function sql_insert_id($link=null)
{
    $link = sql_get_connection($link);

    if (!$link) {
        return 0;
    }

    return (int) $link->lastInsertId();
}

function sql_affected_rows($statement_or_result)
{
    if ($statement_or_result instanceof PDOStatement) {
        return (int) $statement_or_result->rowCount();
    }

    if ($statement_or_result instanceof G5PdoResult) {
        return $statement_or_result->getNumRows();
    }

    return sql_get_last_affected_rows();
}

function sql_num_rows($result)
{
    if ($result instanceof G5PdoResult) {
        return $result->getNumRows();
    }

    return 0;
}

function sql_field_names($table, $link=null)
{
    $columns = array();
    $quoted_table = sql_quote_identifier($table);

    if (!$quoted_table) {
        return $columns;
    }

    $sql = " select * from {$quoted_table} limit 1 ";
    $result = sql_query($sql, G5_DISPLAY_SQL_ERROR, $link);

    if ($result instanceof G5PdoResult) {
        while ($field = $result->fetchField()) {
            $columns[] = $field->name;
        }
    }

    return $columns;
}

function sql_error_info($link=null)
{
    $link = sql_get_connection($link);
    $error = sql_get_last_error();

    if ((!$error['error_code'] && !$error['error_message']) && $link instanceof PDO) {
        $error = sql_store_error($link);
    }

    return $error['error_code'] . ' : ' . $error['error_message'];
}

function sql_begin_transaction($link=null)
{
    $link = sql_get_connection($link);

    if (!$link) {
        return false;
    }

    try {
        if ($link->inTransaction()) {
            return true;
        }

        return $link->beginTransaction();
    } catch (PDOException $e) {
        sql_store_error($link, null, $e);
        return false;
    }
}

function sql_commit($link=null)
{
    $link = sql_get_connection($link);

    if (!$link) {
        return false;
    }

    try {
        if (!$link->inTransaction()) {
            return true;
        }

        return $link->commit();
    } catch (PDOException $e) {
        sql_store_error($link, null, $e);
        return false;
    }
}

function sql_rollback($link=null)
{
    $link = sql_get_connection($link);

    if (!$link) {
        return false;
    }

    try {
        if (!$link->inTransaction()) {
            return true;
        }

        return $link->rollBack();
    } catch (PDOException $e) {
        sql_store_error($link, null, $e);
        return false;
    }
}

function sql_in_transaction($link=null)
{
    $link = sql_get_connection($link);

    return $link ? $link->inTransaction() : false;
}

// PHPMyAdmin 참고
function get_table_define($table, $crlf="\n")
{
    $quoted_table = sql_quote_identifier($table);
    if (!$quoted_table) {
        return '';
    }

    // For MySQL < 3.23.20
    $schema_create = 'CREATE TABLE ' . $quoted_table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $quoted_table;
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
    }
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $quoted_table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        $kname    = $row['Key_name'];
        $comment  = isset($row['Comment']) ? $row['Comment'] : '';
        $sub_part = isset($row['Sub_part']) ? $row['Sub_part'] : '';

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
    }
    sql_free_result($result);

    foreach ((array) $index as $x => $columns) {
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
    }

    $schema_create .= $crlf . ') ENGINE=MyISAM DEFAULT CHARSET=utf8';

    return get_db_create_replace($schema_create);
}

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

// PDO::quote 의 alias 기능을 한다.
function sql_real_escape_string($str, $link=null)
{
    $link = sql_get_connection($link);

    if (!$link) {
        return addslashes($str);
    }

    $quoted = $link->quote($str);
    if ($quoted === false) {
        return addslashes($str);
    }

    return substr($quoted, 1, -1);
}

function escape_trim($field)
{
    $str = call_user_func(G5_ESCAPE_FUNCTION, $field);
    return $str;
}
