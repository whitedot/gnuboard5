<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function option_selected($value, $selected, $text = '')
{
    if (!$text) {
        $text = $value;
    }

    if ($value == $selected) {
        return "<option value=\"$value\" selected=\"selected\">$text</option>\n";
    }

    return "<option value=\"$value\">$text</option>\n";
}

// '예', '아니오'를 SELECT 형식으로 얻음
function get_yn_select($name, $selected = '1', $event = '')
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
function date_select($date, $name = '')
{
    $s = '';
    if (substr($date, 0, 4) == "0000") {
        $date = G5_TIME_YMDHIS;
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    $s .= "<select name='{$name}_y'>";
    for ($i = $m['0'] - 3; $i <= $m['0'] + 3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>년 \n";

    $s .= "<select name='{$name}_m'>";
    for ($i = 1; $i <= 12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>월 \n";

    $s .= "<select name='{$name}_d'>";
    for ($i = 1; $i <= 31; $i++) {
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
function time_select($time, $name = "")
{
    preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $time, $m);

    $s = "<select name='{$name}_h'>";
    for ($i = 0; $i <= 23; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['0']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>시 \n";

    $s .= "<select name='{$name}_i'>";
    for ($i = 0; $i <= 59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['2']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>분 \n";

    $s .= "<select name='{$name}_s'>";
    for ($i = 0; $i <= 59; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m['3']) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>초 \n";

    return $s;
}

function option_array_checked($option, $arr = array())
{
    $checked = '';

    if (!is_array($arr)) {
        $arr = explode(',', $arr);
    }

    if (!empty($arr) && in_array($option, (array) $arr)) {
        $checked = 'checked="checked"';
    }

    return $checked;
}
