<?php
if (!defined('_GNUBOARD_')) exit;

// 장바구니 건수 검사
function get_cart_count($cart_id)
{
    global $g5, $default;

    $sql = " select count(ct_id) as cnt from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' ";
    $row = sql_fetch($sql);
    $cnt = (int)$row['cnt'];
    return $cnt;
}

//장바구니 간소 데이터 가져오기
function get_boxcart_datas($is_cache=false)
{
    global $g5;
    
    $cart_id = get_session("ss_cart_id");

    if( !$cart_id ){
        return array();
    }

    static $cache = array();

    if( $is_cache && !empty($cache) ){
        return $cache;
    }

    $sql  = " select * from {$g5['g5_shop_cart_table']} ";
    $sql .= " where od_id = '".$cart_id."' group by it_id ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $key = $row['it_id'];
        $cache[$key] = $row;
    }

    return $cache;
}

//장바구니 간소 데이터 갯수 출력
function get_boxcart_datas_count()
{
    $cart_datas = get_boxcart_datas(true);

    return count($cart_datas);
}

// cart id 설정
function set_cart_id($direct)
{
    global $g5, $default, $member;

    if ($direct) {
        $tmp_cart_id = get_session('ss_cart_direct');
        if(!$tmp_cart_id) {
            $tmp_cart_id = get_uniqid();
            set_session('ss_cart_direct', $tmp_cart_id);
        }
    } else {
        // 비회원장바구니 cart id 쿠키설정
        if($default['de_guest_cart_use']) {
            $tmp_cart_id = preg_replace('/[^a-z0-9_\-]/i', '', get_cookie('ck_guest_cart_id'));
            if($tmp_cart_id) {
                set_session('ss_cart_id', $tmp_cart_id);
                //set_cookie('ck_guest_cart_id', $tmp_cart_id, ($default['de_cart_keep_term'] * 86400));
            } else {
                $tmp_cart_id = get_uniqid();
                set_session('ss_cart_id', $tmp_cart_id);
                set_cookie('ck_guest_cart_id', $tmp_cart_id, ($default['de_cart_keep_term'] * 86400));
            }
        } else {
            $tmp_cart_id = get_session('ss_cart_id');
            if(!$tmp_cart_id) {
                $tmp_cart_id = get_uniqid();
                set_session('ss_cart_id', $tmp_cart_id);
            }
        }

        // 보관된 회원장바구니 자료 cart id 변경
        if($member['mb_id'] && $tmp_cart_id) {
            $sql = " update {$g5['g5_shop_cart_table']}
                        set od_id = '$tmp_cart_id'
                        where mb_id = '{$member['mb_id']}'
                          and ct_direct = '0'
                          and ct_status = '쇼핑' ";
            sql_query($sql);
        }
    }
}

// 장바구니 상품삭제
function cart_item_clean()
{
    global $g5, $default;

    // 장바구니 보관일
    $keep_term = $default['de_cart_keep_term'];
    if(!$keep_term)
        $keep_term = 15; // 기본값 15일

    // ct_select_time이 기준시간 이상 경과된 경우 변경
    if(defined('G5_CART_STOCK_LIMIT'))
        $cart_stock_limit = G5_CART_STOCK_LIMIT;
    else
        $cart_stock_limit = 3;

    $stocktime = 0;
    if($cart_stock_limit > 0) {
        if($cart_stock_limit > $keep_term * 24)
            $cart_stock_limit = $keep_term * 24;

        $stocktime = G5_SERVER_TIME - (3600 * $cart_stock_limit);
        $sql = " update {$g5['g5_shop_cart_table']}
                    set ct_select = '0'
                    where ct_select = '1'
                      and ct_status = '쇼핑'
                      and UNIX_TIMESTAMP(ct_select_time) < '$stocktime' ";
        sql_query($sql);
    }

    // 설정 시간이상 경과된 상품 삭제
    $statustime = G5_SERVER_TIME - (86400 * $keep_term);

    $sql = " delete from {$g5['g5_shop_cart_table']}
                where ct_status = '쇼핑'
                  and UNIX_TIMESTAMP(ct_time) < '$statustime' ";
    sql_query($sql);
}

// 상품명과 건수를 반환
function get_goods($cart_id)
{
    global $g5;

    // 상품명만들기
    $row = sql_fetch(" select a.it_id, b.it_name from {$g5['g5_shop_cart_table']} a, {$g5['g5_shop_item_table']} b where a.it_id = b.it_id and a.od_id = '$cart_id' order by ct_id limit 1 ");
    // 상품명에 "(쌍따옴표)가 들어가면 오류 발생함
    $goods['it_id'] = $row['it_id'];
    $goods['full_name']= $goods['name'] = addslashes($row['it_name']);
    // 특수문자제거
    $goods['full_name'] = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",  $goods['full_name']);

    // 상품건수
    $row = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_cart_table']} where od_id = '$cart_id' ");
    $cnt = $row['cnt'] - 1;
    if ($cnt)
        $goods['full_name'] .= ' 외 '.$cnt.'건';
    $goods['count'] = $row['cnt'];

    return $goods;
}

// 주문의 금액, 배송비 과세금액 등의 정보를 가져옴
function get_order_info($od_id)
{
    global $g5;

    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);

    if(!$od['od_id'])
        return false;

    $info = array();

    // 장바구니 주문금액정보
    $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                    SUM(cp_price) as coupon,
                    SUM( IF( ct_notax = 0, ( IF(io_type = 1, (io_price * ct_qty), ( (ct_price + io_price) * ct_qty) ) - cp_price ), 0 ) ) as tax_mny,
                    SUM( IF( ct_notax = 1, ( IF(io_type = 1, (io_price * ct_qty), ( (ct_price + io_price) * ct_qty) ) - cp_price ), 0 ) ) as free_mny
                from {$g5['g5_shop_cart_table']}
                where od_id = '$od_id'
                  and ct_status IN ( '주문', '입금', '준비', '배송', '완료' ) ";
    $sum = sql_fetch($sql);

    $cart_price = $sum['price'];
    $cart_coupon = $sum['coupon'];

    // 배송비
    $send_cost = get_sendcost($od_id);

    $od_coupon = $od_send_coupon = 0;

    if($od['mb_id']) {
        // 주문할인 쿠폰
        $sql = " select a.cp_id, a.cp_type, a.cp_price, a.cp_trunc, a.cp_minimum, a.cp_maximum
                    from {$g5['g5_shop_coupon_table']} a right join {$g5['g5_shop_coupon_log_table']} b on ( a.cp_id = b.cp_id )
                    where b.od_id = '$od_id'
                      and b.mb_id = '{$od['mb_id']}'
                      and a.cp_method = '2' ";
        $cp = sql_fetch($sql);

        $tot_od_price = $cart_price - $cart_coupon;

        if(isset($cp['cp_id']) && $cp['cp_id']) {
            $dc = 0;

            if($cp['cp_minimum'] <= $tot_od_price) {
                if($cp['cp_type']) {
                    $dc = floor(($tot_od_price * ($cp['cp_price'] / 100)) / $cp['cp_trunc']) * $cp['cp_trunc'];
                } else {
                    $dc = $cp['cp_price'];
                }

                if($cp['cp_maximum'] && $dc > $cp['cp_maximum'])
                    $dc = $cp['cp_maximum'];

                if($tot_od_price < $dc)
                    $dc = $tot_od_price;

                $tot_od_price -= $dc;
                $od_coupon = $dc;
            }
        }

        // 배송쿠폰 할인
        $sql = " select a.cp_id, a.cp_type, a.cp_price, a.cp_trunc, a.cp_minimum, a.cp_maximum
                    from {$g5['g5_shop_coupon_table']} a right join {$g5['g5_shop_coupon_log_table']} b on ( a.cp_id = b.cp_id )
                    where b.od_id = '$od_id'
                      and b.mb_id = '{$od['mb_id']}'
                      and a.cp_method = '3' ";
        $cp = sql_fetch($sql);

        if(isset($cp['cp_id']) && $cp['cp_id']) {
            $dc = 0;
            if($cp['cp_minimum'] <= $tot_od_price) {
                if($cp['cp_type']) {
                    $dc = floor(($send_cost * ($cp['cp_price'] / 100)) / $cp['cp_trunc']) * $cp['cp_trunc'];
                } else {
                    $dc = $cp['cp_price'];
                }

                if($cp['cp_maximum'] && $dc > $cp['cp_maximum'])
                    $dc = $cp['cp_maximum'];

                if($dc > $send_cost)
                    $dc = $send_cost;

                $od_send_coupon = $dc;
            }
        }
    }

    // 과세, 비과세 금액정보
    $tax_mny = $sum['tax_mny'];
    $free_mny = $sum['free_mny'];

    if($od['od_tax_flag']) {
        $tot_tax_mny = ( $tax_mny + $send_cost + $od['od_send_cost2'] )
                       - ( $od_coupon + $od_send_coupon + $od['od_receipt_point'] );
        if($tot_tax_mny < 0) {
            $free_mny += $tot_tax_mny;
            $tot_tax_mny = 0;
        }
    } else {
        $tot_tax_mny = ( $tax_mny + $free_mny + $send_cost + $od['od_send_cost2'] )
                       - ( $od_coupon + $od_send_coupon + $od['od_receipt_point'] );
        $free_mny = 0;
    }

    $od_tax_mny = round($tot_tax_mny / 1.1);
    $od_vat_mny = $tot_tax_mny - $od_tax_mny;
    $od_free_mny = $free_mny;

    // 장바구니 취소금액 정보
    $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price
                from {$g5['g5_shop_cart_table']}
                where od_id = '$od_id'
                  and ct_status IN ( '취소', '반품', '품절' ) ";
    $sum = sql_fetch($sql);
    $cancel_price = $sum['price'];

    // 미수금액
    $od_misu = ( $cart_price + $send_cost + $od['od_send_cost2'] )
               - ( $cart_coupon + $od_coupon + $od_send_coupon )
               - ( $od['od_receipt_price'] + $od['od_receipt_point'] - $od['od_refund_price'] );

    // 장바구니상품금액
    $od_cart_price = $cart_price + $cancel_price;

    // 결과처리
    $info['od_cart_price']      = $od_cart_price;
    $info['od_send_cost']       = $send_cost;
    $info['od_coupon']          = $od_coupon;
    $info['od_send_coupon']     = $od_send_coupon;
    $info['od_cart_coupon']     = $cart_coupon;
    $info['od_tax_mny']         = $od_tax_mny;
    $info['od_vat_mny']         = $od_vat_mny;
    $info['od_free_mny']        = $od_free_mny;
    $info['od_cancel_price']    = $cancel_price;
    $info['od_misu']            = $od_misu;

    return $info;
}

//주문데이터 또는 개인결제 주문데이터 가져오기
function get_shop_order_data($od_id, $type='item')
{
    global $g5;
    
    $od_id = preg_replace('/[^0-9a-z_-]/i', '', clean_xss_tags($od_id));

    if( $type == 'personal' ){
        $row = sql_fetch("select * from {$g5['g5_shop_personalpay_table']} where pp_id = $od_id ", false);
    } else {
        $row = sql_fetch("select * from {$g5['g5_shop_order_table']} where od_id = $od_id ", false);
    }

    return $row;
}

// 주문서 번호를 얻는다.
function get_new_od_id()
{
    global $g5;

    // 주문서 테이블 Lock 걸고
    sql_query(" LOCK TABLES {$g5['g5_shop_order_table']} READ, {$g5['g5_shop_order_table']} WRITE ", FALSE);
    // 주문서 번호를 만든다.
    $date = date("ymd", time());    // 2002년 3월 7일 일경우 020307
    $sql = " select max(od_id) as max_od_id from {$g5['g5_shop_order_table']} where SUBSTRING(od_id, 1, 6) = '$date' ";
    $row = sql_fetch($sql);
    $od_id = $row['max_od_id'];
    if ($od_id == 0)
        $od_id = 1;
    else
    {
        $od_id = (int)substr($od_id, -4);
        $od_id++;
    }
    $od_id = $date . substr("0000" . $od_id, -4);
    // 주문서 테이블 Lock 풀고
    sql_query(" UNLOCK TABLES ", FALSE);

    return $od_id;
}

// 장바구니 금액 체크 $is_price_update 가 true 이면 장바구니 가격 업데이트한다. 
function before_check_cart_price($s_cart_id, $is_ct_select_condition=false, $is_price_update=false, $is_item_cache=false){
    global $g5, $default, $config;

    if( !$s_cart_id ){
        return;
    }

    $select_where_add = '';

    if( $is_ct_select_condition ){
        $select_where_add = " and ct_select = '0' ";
    }

    $sql = " select * from `{$g5['g5_shop_cart_table']}` where od_id = '$s_cart_id' {$select_where_add} ";

    $result = sql_query($sql);
    $check_need_update = false;
    
    for ($i=0; $row=sql_fetch_array($result); $i++){
        if( ! $row['it_id'] ) continue;

        $it_id = $row['it_id'];
        $it = get_shop_item($it_id, $is_item_cache);
        
        $update_querys = array();

        if(!$it['it_id'])
            continue;
        
        if( $it['it_price'] !== $row['ct_price'] ){
            // 장바구니 테이블 상품 가격과 상품 테이블의 상품 가격이 다를경우
            $update_querys['ct_price'] = $it['it_price'];
        }

        if( $row['io_id'] ){
            $io_sql = " select * from {$g5['g5_shop_item_option_table']} where it_id = '{$it['it_id']}' and io_id = '{$row['io_id']}' ";
            $io_infos = sql_fetch( $io_sql );

            if( $io_infos['io_type'] ){
                $this_io_type = $io_infos['io_type'];
            }
            if( $io_infos['io_id'] && $io_infos['io_price'] !== $row['io_price'] ){
                // 장바구니 테이블 옵션 가격과 상품 옵션테이블의 옵션 가격이 다를경우
                $update_querys['io_price'] = $io_infos['io_price'];
            }
        }

        // 포인트
        $compare_point = 0;
        if($config['cf_use_point']) {

            // DB 에 io_type 이 1이면 상품추가옵션이며, 0이면 상품선택옵션이다
            if($row['io_type'] == 0) {
                $compare_point = get_item_point($it, $row['io_id']);
            } else {
                $compare_point = $it['it_supply_point'];
            }

            if($compare_point < 0)
                $compare_point = 0;
        }
        
        if((int) $row['ct_point'] !== (int) $compare_point){
            // 장바구니 테이블 적립 포인트와 상품 테이블의 적립 포인트가 다를경우
            $update_querys['ct_point'] = $compare_point;
        }

        if( $update_querys ){
            $check_need_update = true;
        }

        // 장바구니에 담긴 금액과 실제 상품 금액에 차이가 있고, $is_price_update 가 true 인 경우 장바구니 금액을 업데이트 합니다. 
        if( $is_price_update && $update_querys ){
            $conditions = array();

            foreach ($update_querys as $column => $value) {
                $conditions[] = "`{$column}` = '{$value}'";
            }

            if( $col_querys = implode(',', $conditions) ) {
                $sql_query = "update `{$g5['g5_shop_cart_table']}` set {$col_querys} where it_id = '{$it['it_id']}' and od_id = '$s_cart_id' and ct_id =  '{$row['ct_id']}' ";
                sql_query($sql_query, false);
            }
        }
    }

    // 장바구니에 담긴 금액과 실제 상품 금액에 차이가 있다면
    if( $check_need_update ){
        return false;
    }

    return true;
}

// 임시주문 데이터로 주문 필드 생성
function make_order_field($data, $exclude)
{
    $field = '';

    foreach($data as $key=>$value) {
        if(!empty($exclude) && in_array($key, $exclude))
            continue;

        if(is_array($value)) {
            foreach($value as $k=>$v) {
                $field .= '<input type="hidden" name="'.get_text($key.'['.$k.']').'" value="'.get_text($v).'">'.PHP_EOL;
            }
        } else {
            $field .= '<input type="hidden" name="'.get_text($key).'" value="'.get_text($value).'">'.PHP_EOL;
        }
    }

    return $field;
}

function shop_order_data_fields($is_personal=0) {

    if ($is_personal){
        return array('pp_name', 'pp_email', 'pp_hp', 'pp_settle_case');
    }

    return array('od_price', 'od_name', 'od_tel', 'od_hp', 'od_email', 'od_memo', 'od_settle_case', 'max_temp_point', 'od_temp_point', 'od_bank_account', 'od_deposit_name', 'od_test', 'od_ip', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon', 'od_b_zip', 'od_send_cost', 'od_send_cost2', 'od_hope_date');
}

// 주문요청기록 로그를 남깁니다.
function add_order_post_log($msg='', $code='error'){
    global $g5, $member;
    
    if( empty($_POST) ) return;

    $post_data = base64_encode(serialize($_POST));
    $od_id = get_session('ss_order_id');

    if( $code === 'delete' ){
        sql_query(" delete from {$g5['g5_shop_post_log_table']} where (oid = '$od_id' and mb_id = '{$member['mb_id']}' and ol_code != 'error') OR ol_datetime < '".date('Y-m-d H:i:s', strtotime('-15 day', G5_SERVER_TIME))."' ", false);
        return;
    }

    if ( $code === 'error' ) {
        $result = sql_query("describe `{$g5['g5_shop_post_log_table']}`");
        while ($row = sql_fetch_array($result)){
            if( $row['Field'] === 'ol_msg' && $row['Type'] === 'varchar(255)' ){
                sql_query("ALTER TABLE `{$g5['g5_shop_post_log_table']}` MODIFY ol_msg TEXT NOT NULL;", false);
                sql_query("ALTER TABLE `{$g5['g5_shop_post_log_table']}` DROP PRIMARY KEY;", false);
                sql_query("ALTER TABLE `{$g5['g5_shop_post_log_table']}` ADD `log_id` int(11) NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`log_id`);", false);
                break;
            }
        }
    }

    $sql = "insert into `{$g5['g5_shop_post_log_table']}`
            set oid = '$od_id',
            mb_id = '{$member['mb_id']}',
            post_data = '$post_data',
            ol_code = '$code',
            ol_msg = '".addslashes($msg)."',
            ol_datetime = '".G5_TIME_YMDHIS."',
            ol_ip = '{$_SERVER['REMOTE_ADDR']}'";

    if( $result = sql_query($sql, false) ){
        sql_query(" delete from {$g5['g5_shop_post_log_table']} where ol_datetime < '".date('Y-m-d H:i:s', strtotime('-15 day', G5_SERVER_TIME))."' ", false);
    } else {
        if(!sql_query(" DESC {$g5['g5_shop_post_log_table']} ", false)) {
            sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['g5_shop_post_log_table']}` (
                          `log_id` int(11) NOT NULL AUTO_INCREMENT,
                          `oid` bigint(20) unsigned NOT NULL,
                          `mb_id` varchar(255) NOT NULL DEFAULT '',
                          `post_data` text NOT NULL,
                          `ol_code` varchar(255) NOT NULL DEFAULT '',
                          `ol_msg` text NOT NULL,
                          `ol_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                          `ol_ip` varchar(25) NOT NULL DEFAULT '',
                          PRIMARY KEY (`log_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8; ", false);
        }
    }
}
