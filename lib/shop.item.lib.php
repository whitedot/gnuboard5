<?php
if (!defined('_GNUBOARD_')) exit;

/*
간편 사용법 : 상품유형을 1~5 사이로 지정합니다.
$disp = new item_list(1);
echo $disp->run();


유형+분류별로 노출하는 경우 상세 사용법 : 상품유형을 지정하는 것은 동일합니다.
$disp = new item_list(1);
// 사용할 스킨을 바꿉니다.
$disp->set_list_skin("type_user.skin.php");
// 1단계분류를 20으로 시작되는 분류로 지정합니다.
$disp->set_category("20", 1);
echo $disp->run();


분류별로 노출하는 경우 상세 사용법
// type13.skin.php 스킨으로 3개씩 2줄을 폭 150 사이즈로 분류코드 30 으로 시작되는 상품을 노출합니다.
$disp = new item_list(0, "type13.skin.php", 3, 2, 150, 0, "30");
echo $disp->run();


이벤트로 노출하는 경우 상세 사용법
// type13.skin.php 스킨으로 3개씩 2줄을 폭 150 사이즈로 상품을 노출합니다.
// 스킨의 경로는 스킨 파일의 절대경로를 지정합니다.
$disp = new item_list(0, G5_SHOP_SKIN_PATH.'/list.10.skin.php', 3, 2, 150, 0);
// 이벤트번호를 설정합니다.
$disp->set_event("12345678");
echo $disp->run();

참고) 영카트4의 display_type 함수와 사용방법이 비슷한 class 입니다.
      display_category 나 display_event 로 사용하기 위해서는 $type 값만 넘기지 않으면 됩니다.
*/

class item_list
{
    // 상품유형 : 기본적으로 1~5 까지 사용할수 있으며 0 으로 설정하는 경우 상품유형별로 노출하지 않습니다.
    // 분류나 이벤트로 노출하는 경우 상품유형을 0 으로 설정하면 됩니다.
    protected $type;

    protected $list_skin;
    protected $list_mod;
    protected $list_row;
    protected $img_width;
    protected $img_height;

    // 상품상세보기 경로
    protected $href = "";

    // select 에 사용되는 필드
    protected $fields = "*";

    // 분류코드로만 사용하는 경우 상품유형($type)을 0 으로 설정하면 됩니다.
    protected $ca_id = "";
    protected $ca_id2 = "";
    protected $ca_id3 = "";

    // 노출순서
    protected $order_by = "it_order, it_id desc";

    // 상품의 이벤트번호를 저장합니다.
    protected $event = "";

    // 스킨의 기본 css 를 다른것으로 사용하고자 할 경우에 사용합니다.
    protected $css = "";

    // 상품의 사용여부를 따져 노출합니다. 0 인 경우 모든 상품을 노출합니다.
    protected $use = 1;

    // 모바일에서 노출하고자 할 경우에 true 로 설정합니다.
    protected $is_mobile = false;

    // 기본으로 보여지는 필드들
    protected $view_it_id    = false;       // 상품코드
    protected $view_it_img   = true;        // 상품이미지
    protected $view_it_name  = true;        // 상품명
    protected $view_it_basic = true;        // 기본설명
    protected $view_it_price = true;        // 판매가격
    protected $view_it_cust_price = false;  // 소비자가
    protected $view_it_icon = false;        // 아이콘
    protected $view_sns = false;            // SNS
    protected $view_star = false;           // 별점

    // 몇번째 class 호출인지를 저장합니다.
    protected $count = 0;

    // true 인 경우 페이지를 구한다.
    protected $is_page = false;

    // 페이지 표시를 위하여 총 상품수를 구합니다.
    public $total_count = 0;

    // sql limit 의 시작 레코드
    protected $from_record = 0;

    // 외부에서 쿼리문을 넘겨줄 경우에 담아두는 변수
    protected $query = "";

    // $type        : 상품유형 (기본으로 1~5까지 사용)
    // $list_skin   : 상품리스트를 노출할 스킨을 설정합니다. 스킨위치는 skin/shop/쇼핑몰설정스킨/type??.skin.php
    // $list_mod    : 1줄에 몇개의 상품을 노출할지를 설정합니다.
    // $list_row    : 상품을 몇줄에 노출할지를 설정합니다.
    // $img_width   : 상품이미지의 폭을 설정합니다.
    // $img_height  : 상품이미지의 높이을 설정합니다. 0 으로 설정하는 경우 썸네일 이미지의 높이는 폭에 비례하여 생성합니다.
    //function __construct($type=0, $list_skin='', $list_mod='', $list_row='', $img_width='', $img_height=0, $ca_id='') {
    function __construct($list_skin='', $list_mod='', $list_row='', $img_width='', $img_height=0) {
        $this->list_skin  = $list_skin;
        $this->list_mod   = $list_mod;
        $this->list_row   = $list_row;
        $this->img_width  = $img_width;
        $this->img_height = $img_height;
        $this->set_href(G5_SHOP_URL.'/item.php?it_id=');
        $this->count++;
    }

    function set_type($type) {
        $this->type = $type;
        if ($type) {
            $this->set_list_skin($this->list_skin);
            $this->set_list_mod($this->list_mod);
            $this->set_list_row($this->list_row);
            $this->set_img_size($this->img_width, $this->img_height);
        }
    }

    // 분류코드로 검색을 하고자 하는 경우 아래와 같이 인수를 넘겨줍니다.
    // 1단계 분류는 (분류코드, 1)
    // 2단계 분류는 (분류코드, 2)
    // 3단계 분류는 (분류코드, 3)
    function set_category($ca_id, $level=1) {
        if ($level == 2) {
            $this->ca_id2 = $ca_id;
        } else if ($level == 3) {
            $this->ca_id3 = $ca_id;
        } else {
            $this->ca_id = $ca_id;
        }
    }

    // 이벤트코드를 인수로 넘기게 되면 해당 이벤트에 속한 상품을 노출합니다.
    function set_event($ev_id) {
        $this->event = $ev_id;
    }

    // 리스트 스킨을 바꾸고자 하는 경우에 사용합니다.
    // 리스트 스킨의 위치는 theme/skin/shop/스킨/type??.skin.php 입니다.
    // 특별히 설정하지 않는 경우 상품유형을 사용하는 경우는 쇼핑몰설정 값을 그대로 따릅니다.
    function set_list_skin($list_skin) {
        global $default;
        $this->list_skin = $list_skin ? $list_skin : G5_SHOP_SKIN_PATH.'/'.preg_replace('/[^A-Za-z0-9 _ .-]/', '', $default['de_type'.$this->type.'_list_skin']);
    }

    // 1줄에 몇개를 노출할지를 사용한다.
    // 특별히 설정하지 않는 경우 상품유형을 사용하는 경우는 쇼핑몰설정 값을 그대로 따릅니다.
    function set_list_mod($list_mod) {
        global $default;
        $this->list_mod = $list_mod ? $list_mod : $default['de_type'.$this->type.'_list_mod'];
    }

    // 몇줄을 노출할지를 사용한다.
    // 특별히 설정하지 않는 경우 상품유형을 사용하는 경우는 쇼핑몰설정 값을 그대로 따릅니다.
    function set_list_row($list_row) {
        global $default;
        $this->list_row = $list_row ? $list_row : $default['de_type'.$this->type.'_list_row'];
        if (!$this->list_row)
            $this->list_row = 1;
    }

    // 노출이미지(썸네일생성)의 폭, 높이를 설정합니다. 높이를 0 으로 설정하는 경우 쎰네일 비율에 따릅니다.
    // 특별히 설정하지 않는 경우 상품유형을 사용하는 경우는 쇼핑몰설정 값을 그대로 따릅니다.
    function set_img_size($img_width, $img_height=0) {
        global $default;
        $this->img_width = $img_width ? $img_width : $default['de_type'.$this->type.'_img_width'];
        $this->img_height = $img_height ? $img_height : $default['de_type'.$this->type.'_img_height'];
    }

    // 특정 필드만 select 하는 경우에는 필드명을 , 로 구분하여 "field1, field2, field3, ... fieldn" 으로 인수를 넘겨줍니다.
    function set_fields($str) {
        $this->fields = $str;
    }

    // 특정 필드로 정렬을 하는 경우 필드와 정렬순서를 , 로 구분하여 "field1 desc, field2 asc, ... fieldn desc " 으로 인수를 넘겨줍니다.
    function set_order_by($str) {
        $this->order_by = $str;
    }

    // 사용하는 상품외에 모든 상품을 노출하려면 0 을 인수로 넘겨줍니다.
    function set_use($use) {
        $this->use = $use;
    }

    // 모바일로 사용하려는 경우 true 를 인수로 넘겨줍니다.
    function set_mobile($mobile=true) {
        $this->is_mobile = $mobile;
    }

    // 스킨에서 특정 필드를 노출하거나 하지 않게 할수 있습니다.
    // 가령 소비자가는 처음에 노출되지 않도록 설정되어 있지만 노출을 하려면
    // ("it_cust_price", true) 와 같이 인수를 넘겨줍니다.
    // 이때 인수로 넘겨주는 값은 스킨에 정의된 필드만 가능하다는 것입니다.
    function set_view($field, $view=true) {
        $this->{"view_".$field} = $view;
    }

    // anchor 태그에 하이퍼링크를 다른 주소로 걸거나 아예 링크를 걸지 않을 수 있습니다.
    // 인수를 "" 공백으로 넘기면 링크를 걸지 않습니다.
    function set_href($href) {
        $this->href = $href;
    }

    // ul 태그의 css 를 교체할수 있다. "sct sct_abc" 를 인수로 넘기게 되면
    // 기존의 ul 태그에 걸린 css 는 무시되며 인수로 넘긴 css 가 사용됩니다.
    function set_css($css) {
        $this->css = $css;
    }

    // 페이지를 노출하기 위해 true 로 설정할때 사용합니다.
    function set_is_page($is_page) {
        $this->is_page = $is_page;
    }

    // select ... limit 의 시작값
    function set_from_record($from_record) {
        $this->from_record = $from_record;
    }

    // 외부에서 쿼리문을 넘겨줄 경우에 담아둡니다.
    function set_query($query) {
        $this->query = $query;
    }

    // class 에 설정된 값으로 최종 실행합니다.
    function run() {

        global $g5, $config, $member, $default;
        
        $list = array();

        if ($this->query) {

            $sql = $this->query;
            $result = sql_query($sql);
            $this->total_count = @sql_num_rows($result);

        } else {

            $where = array();
            if ($this->use) {
                $where[] = " it_use = '1' ";
            }

            if ($this->type) {
                $where[] = " it_type{$this->type} = '1' ";
            }

            if ($this->ca_id || $this->ca_id2 || $this->ca_id3) {
                $where_ca_id = array();
                if ($this->ca_id) {
                    $where_ca_id[] = " ca_id like '{$this->ca_id}%' ";
                }
                if ($this->ca_id2) {
                    $where_ca_id[] = " ca_id2 like '{$this->ca_id2}%' ";
                }
                if ($this->ca_id3) {
                    $where_ca_id[] = " ca_id3 like '{$this->ca_id3}%' ";
                }
                $where[] = " ( " . implode(" or ", $where_ca_id) . " ) ";
            }

            if ($this->order_by) {
                $sql_order = " order by {$this->order_by} ";
            }

            if ($this->event) {
                $sql_select = " select {$this->fields} ";
                $sql_common = " from `{$g5['g5_shop_event_item_table']}` a left join `{$g5['g5_shop_item_table']}` b on (a.it_id = b.it_id) ";
                $where[] = " a.ev_id = '{$this->event}' ";
            } else {
                $sql_select = " select {$this->fields} ";
                $sql_common = " from `{$g5['g5_shop_item_table']}` ";
            }
            $sql_where = " where " . implode(" and ", $where);
            $sql_limit = " limit " . $this->from_record . " , " . ($this->list_mod * $this->list_row);

            $sql = $sql_select . $sql_common . $sql_where . $sql_order . $sql_limit;
            $result = sql_query($sql);

            if ($this->is_page) {
                $sql2 = " select count(*) as cnt " . $sql_common . $sql_where;
                $row2 = sql_fetch($sql2);
                $this->total_count = $row2['cnt'];
            }
        }

        if( isset($result) && $result ){
            while ($row=sql_fetch_array($result)) {
                
                if( isset($row['it_seo_title']) && ! $row['it_seo_title'] ){
                    shop_seo_title_update($row['it_id']);
                }
                
                $row['it_basic'] = conv_content($row['it_basic'], 1);
                $list[] = $row;
            }

            if(function_exists('sql_data_seek')){
                sql_data_seek($result, 0);
            }
        }

        $file = $this->list_skin;

        if ($this->list_skin == "") {
            return $this->count."번 item_list() 의 스킨파일이 지정되지 않았습니다.";
        } else if (!file_exists($file)) {
            return $file." 파일을 찾을 수 없습니다.";
        } else {
            ob_start();
            $list_mod = $this->list_mod;
            include($file);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }
}


// 상품의 재고 (창고재고수량 - 주문대기수량)
function get_it_stock_qty($it_id)
{
    global $g5;

    $sql = " select it_stock_qty from {$g5['g5_shop_item_table']} where it_id = '$it_id' ";
    $row = sql_fetch($sql);
    $jaego = (int)$row['it_stock_qty'];

    // 재고에서 빼지 않았고 주문인것만
    $sql = " select SUM(ct_qty) as sum_qty
               from {$g5['g5_shop_cart_table']}
              where it_id = '$it_id'
                and io_id = ''
                and ct_stock_use = 0
                and ct_status in ('주문', '입금', '준비') ";
    $row = sql_fetch($sql);
    $daegi = (int)$row['sum_qty'];

    return $jaego - $daegi;
}


// 옵션의 재고 (창고재고수량 - 주문대기수량)
function get_option_stock_qty($it_id, $io_id, $type)
{
    global $g5;

    $sql = " select io_stock_qty
                from {$g5['g5_shop_item_option_table']}
                where it_id = '$it_id' and io_id = '$io_id' and io_type = '$type' and io_use = '1' ";
    $row = sql_fetch($sql);
    $jaego = (int)$row['io_stock_qty'];

    // 재고에서 빼지 않았고 주문인것만
    $sql = " select SUM(ct_qty) as sum_qty
               from {$g5['g5_shop_cart_table']}
              where it_id = '$it_id'
                and io_id = '$io_id'
                and io_type = '$type'
                and ct_stock_use = 0
                and ct_status in ('주문', '입금', '준비') ";
    $row = sql_fetch($sql);
    $daegi = (int)$row['sum_qty'];

    return $jaego - $daegi;
}


// 출력유형, 스킨파일, 1라인이미지수, 총라인수, 이미지폭, 이미지높이
// 1.02.01 $ca_id 추가
//function display_type($type, $skin_file, $list_mod, $list_row, $img_width, $img_height, $ca_id="")
function display_type($type, $list_skin='', $list_mod='', $list_row='', $img_width='', $img_height='', $ca_id='')
{
    global $member, $g5, $config, $default;

    if (!$default["de_type{$type}_list_use"]) return "";

    $list_skin  = $list_skin  ? $list_skin  : $default["de_type{$type}_list_skin"];
    $list_mod   = $list_mod   ? $list_mod   : $default["de_type{$type}_list_mod"];
    $list_row   = $list_row   ? $list_row   : $default["de_type{$type}_list_row"];
    $img_width  = $img_width  ? $img_width  : $default["de_type{$type}_img_width"];
    $img_height = $img_height ? $img_height : $default["de_type{$type}_img_height"];

    // 상품수
    $items = $list_mod * $list_row;

    // 1.02.00
    // it_order 추가
    $sql = " select * from {$g5['g5_shop_item_table']} where it_use = '1' and it_type{$type} = '1' ";
    if ($ca_id) $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " order by it_order, it_id desc limit $items ";
    $result = sql_query($sql);
    /*
    if (!sql_num_rows($result)) {
        return false;
    }
    */

    //$file = G5_SHOP_PATH.'/'.$skin_file;
    $file = G5_SHOP_SKIN_PATH.'/'.$list_skin;
    if (!file_exists($file)) {
        return G5_SHOP_SKIN_URL.'/'.$list_skin.' 파일을 찾을 수 없습니다.';
    } else {
        $td_width = (int)(100 / $list_mod);
        ob_start();
        include $file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}


// 모바일 유형별 상품 출력
function mobile_display_type($type, $skin_file, $list_row, $img_width, $img_height, $ca_id="")
{
    global $member, $g5, $config;

    // 상품수
    $items = $list_row;

    // 1.02.00
    // it_order 추가
    $sql = " select * from {$g5['g5_shop_item_table']} where it_use = '1' and it_type{$type} = '1' ";
    if ($ca_id) $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " order by it_order, it_id desc limit $items ";
    $result = sql_query($sql);
    /*
    if (!sql_num_rows($result)) {
        return false;
    }
    */

    $file = G5_MSHOP_PATH.'/'.$skin_file;
    if (!file_exists($file)) {
        echo $file.' 파일을 찾을 수 없습니다.';
    } else {
        //$td_width = (int)(100 / $list_mod);
        include $file;
    }
}


// 분류별 출력
// 스킨파일번호, 1라인이미지수, 총라인수, 이미지폭, 이미지높이 , 분류번호
function display_category($no, $list_mod, $list_row, $img_width, $img_height, $ca_id="")
{
    global $member, $g5;

    // 상품수
    $items = $list_mod * $list_row;

    $sql = " select * from {$g5['g5_shop_item_table']} where it_use = '1'";
    if ($ca_id)
        $sql .= " and ca_id LIKE '{$ca_id}%' ";
    $sql .= " order by it_order, it_id desc limit $items ";
    $result = sql_query($sql);
    if (!sql_num_rows($result)) {
        return false;
    }

    $file = G5_SHOP_PATH.'/maintype'.$no.'.inc.php';
    if (!file_exists($file)) {
        echo $file.' 파일을 찾을 수 없습니다.';
    } else {
        $td_width = (int)(100 / $list_mod);
        include $file;
    }
}


// 별
function get_star($score)
{
    $star = round($score);
    if ($star > 5) $star = 5;
    else if ($star < 0) $star = 0;

    return $star;
}


// 별 이미지
function get_star_image($it_id)
{
    global $g5;

    $sql = "select (SUM(is_score) / COUNT(*)) as score from {$g5['g5_shop_item_use_table']} where it_id = '$it_id' and is_confirm = 1 ";
    $row = sql_fetch($sql);

    return (int)get_star($row['score']);
}


// 상품 선택옵션
function get_item_options($it_id, $subject, $is_div='', $is_first_option_title='')
{
    global $g5;

    if(!$it_id || !$subject)
        return '';

    $sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '0' and it_id = '$it_id' and io_use = '1' order by io_no asc ";
    $result = sql_query($sql);
    if(!sql_num_rows($result))
        return '';

    $str = '';
    $subj = explode(',', $subject);
    $subj_count = count($subj);

    if($subj_count > 1) {
        $options = array();

        // 옵션항목 배열에 저장
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $opt_id = explode(chr(30), $row['io_id']);

            for($k=0; $k<$subj_count; $k++) {
                if(! (isset($options[$k]) && is_array($options[$k])))
                    $options[$k] = array();

                if(isset($opt_id[$k]) && $opt_id[$k] && !in_array($opt_id[$k], $options[$k]))
                    $options[$k][] = $opt_id[$k];
            }
        }

        // 옵션선택목록 만들기
        for($i=0; $i<$subj_count; $i++) {
            $opt = $options[$i];
            $opt_count = count($opt);
            $disabled = '';
            if($opt_count) {
                $seq = $i + 1;
                if($i > 0)
                    $disabled = ' disabled="disabled"';

                if($is_div === 'div') {
                    $str .= '<div class="get_item_options">'.PHP_EOL;
                    $str .= '<label for="it_option_'.$seq.'">'.$subj[$i].'</label>'.PHP_EOL;
                } else {
                    $str .= '<tr>'.PHP_EOL;
                    $str .= '<th><label for="it_option_'.$seq.'">'.$subj[$i].'</label></th>'.PHP_EOL;
                }

                $select = '<select id="it_option_'.$seq.'" class="it_option"'.$disabled.'>'.PHP_EOL;

                $first_option_title = $is_first_option_title ? $subj[$i] : '선택';

                $select .= '<option value="">'.$first_option_title.'</option>'.PHP_EOL;
                for($k=0; $k<$opt_count; $k++) {
                    $opt_val = $opt[$k];
                    if(strlen($opt_val)) {
                        $select .= '<option value="'.get_text($opt_val).'">'.get_text($opt_val).'</option>'.PHP_EOL;
                    }
                }
                $select .= '</select>'.PHP_EOL;

                if($is_div === 'div') {
                    $str .= '<span>'.$select.'</span>'.PHP_EOL;
                    $str .= '</div>'.PHP_EOL;
                } else {
                    $str .= '<td>'.$select.'</td>'.PHP_EOL;
                    $str .= '</tr>'.PHP_EOL;
                }
            }
        }
    } else {
        if($is_div === 'div') {
            $str .= '<div class="get_item_options">'.PHP_EOL;
            $str .= '<label for="it_option_1">'.$subj[0].'</label>'.PHP_EOL;
        } else {
            $str .= '<tr>'.PHP_EOL;
            $str .= '<th><label for="it_option_1">'.$subj[0].'</label></th>'.PHP_EOL;
        }

        $select = '<select id="it_option_1" class="it_option">'.PHP_EOL;
        $select .= '<option value="">선택</option>'.PHP_EOL;
        for($i=0; $row=sql_fetch_array($result); $i++) {
            if($row['io_price'] >= 0)
                $price = '&nbsp;&nbsp;+ '.number_format($row['io_price']).'원';
            else
                $price = '&nbsp;&nbsp; '.number_format($row['io_price']).'원';

            if($row['io_stock_qty'] < 1)
                $soldout = '&nbsp;&nbsp;[품절]';
            else
                $soldout = '';

            $select .= '<option value="'.get_text($row['io_id']).','.$row['io_price'].','.$row['io_stock_qty'].'">'.get_text($row['io_id']).$price.$soldout.'</option>'.PHP_EOL;
        }
        $select .= '</select>'.PHP_EOL;
        
        if($is_div === 'div') {
            $str .= '<span>'.$select.'</span>'.PHP_EOL;
            $str .= '</div>'.PHP_EOL;
        } else {
            $str .= '<td>'.$select.'</td>'.PHP_EOL;
            $str .= '</tr>'.PHP_EOL;
        }

    }

    return $str;
}

// 상품 추가옵션
function get_item_supply($it_id, $subject, $is_div='', $is_first_option_title='')
{
    global $g5;

    if(!$it_id || !$subject)
        return '';

    $sql = " select * from {$g5['g5_shop_item_option_table']} where io_type = '1' and it_id = '$it_id' and io_use = '1' order by io_no asc ";
    $result = sql_query($sql);
    if(!sql_num_rows($result))
        return '';

    $str = '';

    $subj = explode(',', $subject);
    $subj_count = count($subj);
    $options = array();

    // 옵션항목 배열에 저장
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $opt_id = explode(chr(30), $row['io_id']);

        if($opt_id[0] && !array_key_exists($opt_id[0], $options))
            $options[$opt_id[0]] = array();

        if(strlen($opt_id[1])) {
            if($row['io_price'] >= 0)
                $price = '&nbsp;&nbsp;+ '.number_format($row['io_price']).'원';
            else
                $price = '&nbsp;&nbsp; '.number_format($row['io_price']).'원';
            $io_stock_qty = get_option_stock_qty($it_id, $row['io_id'], $row['io_type']);

            if($io_stock_qty < 1)
                $soldout = '&nbsp;&nbsp;[품절]';
            else
                $soldout = '';

            $options[$opt_id[0]][] = '<option value="'.get_text($opt_id[1]).','.$row['io_price'].','.$io_stock_qty.'">'.get_text($opt_id[1]).$price.$soldout.'</option>';
        }
    }

    // 옵션항목 만들기
    for($i=0; $i<$subj_count; $i++) {
        $opt = (isset($subj[$i]) && isset($options[$subj[$i]])) ? $options[$subj[$i]] : array();
        $opt_count = count($opt);
        if($opt_count) {
            $seq = $i + 1;
            if($is_div === 'div') {
                $str .= '<div class="get_item_supply">'.PHP_EOL;
                $str .= '<label for="it_supply_'.$seq.'">'.$subj[$i].'</label>'.PHP_EOL;
            } else {
                $str .= '<tr>'.PHP_EOL;
                $str .= '<th><label for="it_supply_'.$seq.'">'.$subj[$i].'</label></th>'.PHP_EOL;
            }
            
            $first_option_title = $is_first_option_title ? $subj[$i] : '선택';

            $select = '<select id="it_supply_'.$seq.'" class="it_supply">'.PHP_EOL;
            $select .= '<option value="">'.get_text($first_option_title).'</option>'.PHP_EOL;
            for($k=0; $k<$opt_count; $k++) {
                $opt_val = $opt[$k];
                if($opt_val) {
                    $select .= $opt_val.PHP_EOL;
                }
            }
            $select .= '</select>'.PHP_EOL;
            
            if($is_div === 'div') {
                $str .= '<span>'.$select.'</span>'.PHP_EOL;
                $str .= '</div>'.PHP_EOL;
            } else {
                $str .= '<td>'.$select.'</td>'.PHP_EOL;
                $str .= '</tr>'.PHP_EOL;
            }
        }
    }

    return $str;
}

function print_item_options($it_id, $cart_id)
{
    global $g5;

    $sql = " select ct_option, ct_qty, io_price
                from {$g5['g5_shop_cart_table']} where it_id = '$it_id' and od_id = '$cart_id' order by io_type asc, ct_id asc ";
    $result = sql_query($sql);

    $str = '';
    for($i=0; $row=sql_fetch_array($result); $i++) {
        if($i == 0)
            $str .= '<ul>'.PHP_EOL;
        $price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';
        $str .= '<li>'.get_text($row['ct_option']).' '.$row['ct_qty'].'개 ('.$price_plus.display_price($row['io_price']).')</li>'.PHP_EOL;
    }

    if($i > 0)
        $str .= '</ul>';

    return $str;
}

// 상품 목록 : 관련 상품 출력
function relation_item($it_id, $width, $height, $rows=3)
{
    global $g5;

    $str = '';

    if(!$it_id)
        return $str;

    $sql = " select b.it_id, b.it_name, b.it_price, b.it_tel_inq from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id2 = b.it_id ) where a.it_id = '$it_id' order by ir_no asc limit 0, $rows ";
    $result = sql_query($sql);

    for($i=0; $row=sql_fetch_array($result); $i++) {
        if($i == 0) {
            $str .= '<span>관련 상품 시작</span>';
            $str .= '<ul>';
        }

        $it_name = get_text($row['it_name']); // 상품명
        $it_price = get_price($row); // 상품가격
        if(!$row['it_tel_inq'])
            $it_price = display_price($it_price);

        $img = get_it_image($row['it_id'], $width, $height);

        $str .= '<li><a href="'.get_pretty_url('shop', $row['it_id']).'">'.$img.'</a></li>';
    }

    if($i > 0)
        $str .= '</ul><span>관련 상품 끝</span>';

    return $str;
}


// 상품이미지에 유형 아이콘 출력
function item_icon($it)
{
    global $g5;

    $icon = '<span>';

    if ($it['it_type1'])
        $icon .= '<span>히트</span>';

    if ($it['it_type2'])
        $icon .= '<span>추천</span>';

    if ($it['it_type3'])
        $icon .= '<span>최신</span>';

    if ($it['it_type4'])
        $icon .= '<span>인기</span>';

    if ($it['it_type5'])
        $icon .= '<span>할인</span>';


    // 쿠폰상품
    $sql = " select count(*) as cnt
                from {$g5['g5_shop_coupon_table']}
                where cp_start <= '".G5_TIME_YMD."'
                  and cp_end >= '".G5_TIME_YMD."'
                  and (
                        ( cp_method = '0' and cp_target = '{$it['it_id']}' )
                        OR
                        ( cp_method = '1' and ( cp_target IN ( '{$it['ca_id']}', '{$it['ca_id2']}', '{$it['ca_id3']}' ) ) )
                      ) ";
    $row = sql_fetch($sql);
    if($row['cnt'])
        $icon .= '<span>쿠폰</span>';

    $icon .= '</span>';

    return $icon;
}

// 품절상품인지 체크
function is_soldout($it_id, $is_cache=false)
{
    global $g5;

    static $cache = array();

    $it_id = preg_replace('/[^a-z0-9_\-]/i', '', $it_id);
    $key = md5($it_id);

    if( $is_cache && isset($cache[$key]) ){
        return $cache[$key];
    }

    // 상품정보
    $it = get_shop_item($it_id, $is_cache);

    if ($it['it_soldout']) {
        return true;
    }

    $count = 0;
    $soldout = false;

    // 상품에 선택옵션 있으면..
    $sql = " select count(*) as cnt from {$g5['g5_shop_item_option_table']} where it_id = '$it_id' and io_type = '0' ";
    $row = sql_fetch($sql);

    if (isset($row['cnt']) && $row['cnt']) {
        $sql = " select io_id, io_type, io_stock_qty
                    from {$g5['g5_shop_item_option_table']}
                    where it_id = '$it_id'
                      and io_type = '0'
                      and io_use = '1' ";
        $result = sql_query($sql);

        for($i=0; $row=sql_fetch_array($result); $i++) {
            // 옵션 재고수량
            $stock_qty = get_option_stock_qty($it_id, $row['io_id'], $row['io_type']);

            if($stock_qty <= 0)
                $count++;
        }

        // 모든 선택옵션 품절이면 상품 품절
        if($i == $count)
            $soldout = true;

    } else {

        if ($it['it_stock_qty'] <= 0) {
            $soldout = true;
        } else {
            // 상품 재고수량
            $stock_qty = get_it_stock_qty($it_id);

            if($stock_qty <= 0)
                $soldout = true;
        }
    }
    
    $cache[$key] = $soldout;

    return $soldout;
}

// 상품후기 작성가능한지 체크
function check_itemuse_write($it_id, $mb_id, $close=true)
{
    global $g5, $default, $is_admin;

    if(!$is_admin && $default['de_item_use_write'])
    {
        $sql = " select count(*) as cnt
                    from {$g5['g5_shop_cart_table']}
                    where it_id = '$it_id'
                      and mb_id = '$mb_id'
                      and ct_status = '완료' ";
        $row = sql_fetch($sql);

        if($row['cnt'] == 0)
        {
            if($close)
                alert_close('사용후기는 주문이 완료된 경우에만 작성하실 수 있습니다.');
            else
                alert('사용후기는 주문하신 상품의 상태가 완료인 경우에만 작성하실 수 있습니다.');
        }
    }
}

// 사용후기의 확인된 건수를 상품테이블에 저장합니다.
function update_use_cnt($it_id)
{
    global $g5;
    $row = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_use_table']} where it_id = '{$it_id}' and is_confirm = 1 ");
    return sql_query(" update {$g5['g5_shop_item_table']} set it_use_cnt = '{$row['cnt']}' where it_id = '{$it_id}' ");
}


// 사용후기의 선호도(별) 평균을 상품테이블에 저장합니다.
function update_use_avg($it_id)
{
    global $g5;
    $row = sql_fetch(" select count(*) as cnt, sum(is_score) as total from {$g5['g5_shop_item_use_table']} where it_id = '{$it_id}' and is_confirm = 1 ");
    $average = ($row['total'] && $row['cnt']) ? $row['total'] / $row['cnt'] : 0;
    return sql_query(" update {$g5['g5_shop_item_table']} set it_use_avg = '$average' where it_id = '{$it_id}' ");
}
