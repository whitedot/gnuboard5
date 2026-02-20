<section id="anc_sitfrm_cost">
    <h2>가격 및 재고</h2>
    <?php echo $pg_anchor; ?>

    <div>
        <table>
        <caption>가격 및 재고 입력</caption>
        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="it_price">판매가격</label></th>
            <td>
                <input type="text" name="it_price" value="<?php echo $it['it_price']; ?>" id="it_price" size="8"> 원
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_price" value="1" id="chk_ca_it_price">
                <label for="chk_ca_it_price">분류적용</label>
                <input type="checkbox" name="chk_all_it_price" value="1" id="chk_all_it_price">
                <label for="chk_all_it_price">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_cust_price">시중가격</label></th>
            <td>
                <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                <input type="text" name="it_cust_price" value="<?php echo $it['it_cust_price']; ?>" id="it_cust_price" size="8"> 원
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_cust_price" value="1" id="chk_ca_it_cust_price">
                <label for="chk_ca_it_cust_price">분류적용</label>
                <input type="checkbox" name="chk_all_it_cust_price" value="1" id="chk_all_it_cust_price">
                <label for="chk_all_it_cust_price">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_point_type">포인트 유형</label></th>
            <td>
                <?php echo help("포인트 유형을 설정할 수 있습니다. 비율로 설정했을 경우 설정 기준금액의 %비율로 포인트가 지급됩니다."); ?>
                <select name="it_point_type" id="it_point_type">
                    <option value="0"<?php echo get_selected('0', $it['it_point_type']); ?>>설정금액</option>
                    <option value="1"<?php echo get_selected('1', $it['it_point_type']); ?>>판매가기준 설정비율</option>
                    <option value="2"<?php echo get_selected('2', $it['it_point_type']); ?>>구매가기준 설정비율</option>
                </select>
                <script>
                $(function() {
                    $("#it_point_type").change(function() {
                        if(parseInt($(this).val()) > 0)
                            $("#it_point_unit").text("%");
                        else
                            $("#it_point_unit").text("점");
                    });
                });
                </script>
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_point_type" value="1" id="chk_ca_it_point_type">
                <label for="chk_ca_it_point_type">분류적용</label>
                <input type="checkbox" name="chk_all_it_point_type" value="1" id="chk_all_it_point_type">
                <label for="chk_all_it_point_type">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_point">포인트</label></th>
            <td>
                <?php echo help("주문완료후 환경설정에서 설정한 주문완료 설정일 후 회원에게 부여하는 포인트입니다.\n또, 포인트부여를 '아니오'로 설정한 경우 신용카드, 계좌이체로 주문하는 회원께는 부여하지 않습니다."); ?>
                <input type="text" name="it_point" value="<?php echo $it['it_point']; ?>" id="it_point" size="8"> <span id="it_point_unit"><?php if($it['it_point_type']) echo '%'; else echo '점'; ?></span>
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_point" value="1" id="chk_ca_it_point">
                <label for="chk_ca_it_point">분류적용</label>
                <input type="checkbox" name="chk_all_it_point" value="1" id="chk_all_it_point">
                <label for="chk_all_it_point">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_supply_point">추가옵션상품 포인트</label></th>
            <td>
                <?php echo help("상품의 추가옵션상품 구매에 일괄적으로 지급하는 포인트입니다. 0으로 설정하시면 구매포인트를 지급하지 않습니다.\n주문완료후 환경설정에서 설정한 주문완료 설정일 후 회원에게 부여하는 포인트입니다.\n또, 포인트부여를 '아니오'로 설정한 경우 신용카드, 계좌이체로 주문하는 회원께는 부여하지 않습니다."); ?>
                <input type="text" name="it_supply_point" value="<?php echo $it['it_supply_point']; ?>" id="it_supply_point" size="8"> 점
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_supply_point" value="1" id="chk_ca_it_supply_point">
                <label for="chk_ca_it_supply_point">분류적용</label>
                <input type="checkbox" name="chk_all_it_supply_point" value="1" id="chk_all_it_supply_point">
                <label for="chk_all_it_supply_point">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_soldout">상품품절</label></th>
            <td>
                <?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 체크해 놓으면 품절상품으로 표시됩니다."); ?>
                <input type="checkbox" name="it_soldout" value="1" id="it_soldout" <?php echo ($it['it_soldout']) ? "checked" : ""; ?>> 예
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_soldout" value="1" id="chk_ca_it_soldout">
                <label for="chk_ca_it_soldout">분류적용</label>
                <input type="checkbox" name="chk_all_it_soldout" value="1" id="chk_all_it_soldout">
                <label for="chk_all_it_soldout">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_stock_sms">재입고SMS 알림</label></th>
            <td colspan="2">
                <?php echo help("상품이 품절인 경우에 체크해 놓으면 상품상세보기에서 고객이 재입고SMS 알림을 신청할 수 있게 됩니다."); ?>
                <input type="checkbox" name="it_stock_sms" value="1" id="it_stock_sms" <?php echo ($it['it_stock_sms']) ? "checked" : ""; ?>> 예
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_stock_qty">재고수량</label></th>
            <td>
                <?php echo help("<b>주문관리에서 상품별 상태 변경에 따라 자동으로 재고를 가감합니다.</b> 재고는 규격/색상별이 아닌, 상품별로만 관리됩니다.<br>재고수량을 0으로 설정하시면 품절상품으로 표시됩니다."); ?>
                <input type="text" name="it_stock_qty" value="<?php echo $it['it_stock_qty']; ?>" id="it_stock_qty" size="8"> 개
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_stock_qty" value="1" id="chk_ca_it_stock_qty">
                <label for="chk_ca_it_stock_qty">분류적용</label>
                <input type="checkbox" name="chk_all_it_stock_qty" value="1" id="chk_all_it_stock_qty">
                <label for="chk_all_it_stock_qty">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_noti_qty">재고 통보수량</label></th>
            <td>
                <?php echo help("상품의 재고가 통보수량보다 작을 때 쇼핑몰관리 메인화면의 재고현황에 재고부족 상품으로 표시됩니다.<br>옵션이 있는 상품은 개별 옵션의 통보수량이 적용됩니다."); ?>
                <input type="text" name="it_noti_qty" value="<?php echo $it['it_noti_qty']; ?>" id="it_noti_qty" size="8"> 개
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_noti_qty" value="1" id="chk_ca_it_noti_qty">
                <label for="chk_ca_it_noti_qty">분류적용</label>
                <input type="checkbox" name="chk_all_it_noti_qty" value="1" id="chk_all_it_noti_qty">
                <label for="chk_all_it_noti_qty">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_buy_min_qty">최소구매수량</label></th>
            <td>
                <?php echo help("상품 구매시 최소 구매 수량을 설정합니다."); ?>
                <input type="text" name="it_buy_min_qty" value="<?php echo $it['it_buy_min_qty']; ?>" id="it_buy_min_qty" size="8"> 개
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_buy_min_qty" value="1" id="chk_ca_it_buy_min_qty">
                <label for="chk_ca_it_buy_min_qty">분류적용</label>
                <input type="checkbox" name="chk_all_it_buy_min_qty" value="1" id="chk_all_it_buy_min_qty">
                <label for="chk_all_it_buy_min_qty">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_buy_max_qty">최대구매수량</label></th>
            <td>
                <?php echo help("상품 구매시 최대 구매 수량을 설정합니다."); ?>
                <input type="text" name="it_buy_max_qty" value="<?php echo $it['it_buy_max_qty']; ?>" id="it_buy_max_qty" size="8"> 개
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_buy_max_qty" value="1" id="chk_ca_it_buy_max_qty">
                <label for="chk_ca_it_buy_max_qty">분류적용</label>
                <input type="checkbox" name="chk_all_it_buy_max_qty" value="1" id="chk_all_it_buy_max_qty">
                <label for="chk_all_it_buy_max_qty">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_notax">상품과세 유형</label></th>
            <td>
                <?php echo help("상품의 과세유형(과세, 비과세)을 설정합니다."); ?>
                <select name="it_notax" id="it_notax">
                    <option value="0"<?php echo get_selected('0', $it['it_notax']); ?>>과세</option>
                    <option value="1"<?php echo get_selected('1', $it['it_notax']); ?>>비과세</option>
                </select>
            </td>
            <td>
                <input type="checkbox" name="chk_ca_it_notax" value="1" id="chk_ca_it_notax">
                <label for="chk_ca_it_notax">분류적용</label>
                <input type="checkbox" name="chk_all_it_notax" value="1" id="chk_all_it_notax">
                <label for="chk_all_it_notax">전체적용</label>
            </td>
        </tr>
        <?php
        $opt_subject = explode(',', $it['it_option_subject']);
        ?>
        <tr>
            <th scope="row">상품선택옵션</th>
            <td colspan="2">
                <div class="sit_option">
                    <?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 옷을 예로 들어 [옵션1 : 사이즈 , 옵션1 항목 : XXL,XL,L,M,S] , [옵션2 : 색상 , 옵션2 항목 : 빨,파,노]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
                    <table>
                    <caption>상품선택옵션 입력</caption>
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th scope="row">
                            <label for="opt1_subject">옵션1</label>
                            <input type="text" name="opt1_subject" value="<?php echo isset($opt_subject[0]) ? $opt_subject[0] : ''; ?>" id="opt1_subject" size="15">
                        </th>
                        <td>
                            <label for="opt1"><b>옵션1 항목</b></label>
                            <input type="text" name="opt1" value="" id="opt1" size="50">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="opt2_subject">옵션2</label>
                            <input type="text" name="opt2_subject" value="<?php echo isset($opt_subject[1]) ? $opt_subject[1] : ''; ?>" id="opt2_subject" size="15">
                        </th>
                        <td>
                            <label for="opt2"><b>옵션2 항목</b></label>
                            <input type="text" name="opt2" value="" id="opt2" size="50">
                        </td>
                    </tr>
                     <tr>
                        <th scope="row">
                            <label for="opt3_subject">옵션3</label>
                            <input type="text" name="opt3_subject" value="<?php echo isset($opt_subject[2]) ? $opt_subject[2] : ''; ?>" id="opt3_subject" size="15">
                        </th>
                        <td>
                            <label for="opt3"><b>옵션3 항목</b></label>
                            <input type="text" name="opt3" value="" id="opt3" size="50">
                        </td>
                    </tr>
                    </tbody>
                    </table>
                    
                        <button type="button" id="option_table_create">옵션목록생성</button>
                    
                </div>
                <div id="sit_option_frm"><?php include_once(G5_ADMIN_PATH.'/shop_admin/itemoption.php'); ?></div>

                <script>
                $(function() {
                    <?php if($it['it_id'] && $po_run) { ?>
                    //옵션항목설정
                    var arr_opt1 = new Array();
                    var arr_opt2 = new Array();
                    var arr_opt3 = new Array();
                    var opt1 = opt2 = opt3 = '';
                    var opt_val;

                    $(".opt-cell").each(function() {
                        opt_val = $(this).text().split(" > ");
                        opt1 = opt_val[0];
                        opt2 = opt_val[1];
                        opt3 = opt_val[2];

                        if(opt1 && $.inArray(opt1, arr_opt1) == -1)
                            arr_opt1.push(opt1);

                        if(opt2 && $.inArray(opt2, arr_opt2) == -1)
                            arr_opt2.push(opt2);

                        if(opt3 && $.inArray(opt3, arr_opt3) == -1)
                            arr_opt3.push(opt3);
                    });


                    $("input[name=opt1]").val(arr_opt1.join());
                    $("input[name=opt2]").val(arr_opt2.join());
                    $("input[name=opt3]").val(arr_opt3.join());
                    <?php } ?>
                    // 옵션목록생성
                    $("#option_table_create").click(function() {
                        var it_id = $.trim($("input[name=it_id]").val());
                        var opt1_subject = $.trim($("#opt1_subject").val());
                        var opt2_subject = $.trim($("#opt2_subject").val());
                        var opt3_subject = $.trim($("#opt3_subject").val());
                        var opt1 = $.trim($("#opt1").val());
                        var opt2 = $.trim($("#opt2").val());
                        var opt3 = $.trim($("#opt3").val());
                        var $option_table = $("#sit_option_frm");

                        if(!opt1_subject || !opt1) {
                            alert("옵션명과 옵션항목을 입력해 주십시오.");
                            return false;
                        }

                        $.post(
                            "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemoption.php",
                            { it_id: it_id, w: "<?php echo $w; ?>", opt1_subject: opt1_subject, opt2_subject: opt2_subject, opt3_subject: opt3_subject, opt1: opt1, opt2: opt2, opt3: opt3 },
                            function(data) {
                                $option_table.empty().html(data);
                            }
                        );
                    });

                    // 모두선택
                    $(document).on("click", "input[name=opt_chk_all]", function() {
                        if($(this).is(":checked")) {
                            $("input[name='opt_chk[]']").attr("checked", true);
                        } else {
                            $("input[name='opt_chk[]']").attr("checked", false);
                        }
                    });

                    // 선택삭제
                    $(document).on("click", "#sel_option_delete", function() {
                        var $el = $("input[name='opt_chk[]']:checked");
                        if($el.length < 1) {
                            alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                            return false;
                        }

                        $el.closest("tr").remove();
                    });

                    // 일괄적용
                    $(document).on("click", "#opt_value_apply", function() {
                        if($(".opt_com_chk:checked").length < 1) {
                            alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                            return false;
                        }

                        var opt_price = $.trim($("#opt_com_price").val());
                        var opt_stock = $.trim($("#opt_com_stock").val());
                        var opt_noti = $.trim($("#opt_com_noti").val());
                        var opt_use = $("#opt_com_use").val();
                        var $el = $("input[name='opt_chk[]']:checked");

                        // 체크된 옵션이 있으면 체크된 것만 적용
                        if($el.length > 0) {
                            var $tr;
                            $el.each(function() {
                                $tr = $(this).closest("tr");

                                if($("#opt_com_price_chk").is(":checked"))
                                    $tr.find("input[name='opt_price[]']").val(opt_price);

                                if($("#opt_com_stock_chk").is(":checked"))
                                    $tr.find("input[name='opt_stock_qty[]']").val(opt_stock);

                                if($("#opt_com_noti_chk").is(":checked"))
                                    $tr.find("input[name='opt_noti_qty[]']").val(opt_noti);

                                if($("#opt_com_use_chk").is(":checked"))
                                    $tr.find("select[name='opt_use[]']").val(opt_use);
                            });
                        } else {
                            if($("#opt_com_price_chk").is(":checked"))
                                $("input[name='opt_price[]']").val(opt_price);

                            if($("#opt_com_stock_chk").is(":checked"))
                                $("input[name='opt_stock_qty[]']").val(opt_stock);

                            if($("#opt_com_noti_chk").is(":checked"))
                                $("input[name='opt_noti_qty[]']").val(opt_noti);

                            if($("#opt_com_use_chk").is(":checked"))
                                $("select[name='opt_use[]']").val(opt_use);
                        }
                    });
                });
                </script>
            </td>
        </tr>
        <?php
        $spl_subject = explode(',', $it['it_supply_subject']);
        $spl_count = count($spl_subject);
        ?>
        <tr>
            <th scope="row">상품추가옵션</th>
            <td colspan="2">
                <div id="sit_supply_frm" class="sit_option">
                    <?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 스마트폰을 예로 들어 [추가1 : 추가구성상품 , 추가1 항목 : 액정보호필름,케이스,충전기]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
                    <table>
                    <caption>상품추가옵션 입력</caption>
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                    <?php
                    $i = 0;
                    do {
                        $seq = $i + 1;
                    ?>
                    <tr>
                        <th scope="row">
                            <label for="spl_subject_<?php echo $seq; ?>">추가<?php echo $seq; ?></label>
                            <input type="text" name="spl_subject[]" id="spl_subject_<?php echo $seq; ?>" value="<?php echo $spl_subject[$i]; ?>" size="15">
                        </th>
                        <td>
                            <label for="spl_item_<?php echo $seq; ?>"><b>추가<?php echo $seq; ?> 항목</b></label>
                            <input type="text" name="spl[]" id="spl_item_<?php echo $seq; ?>" value="" size="40">
                            <?php
                            if($i > 0)
                                echo '<button type="button" id="del_supply_row">삭제</button>';
                            ?>
                        </td>
                    </tr>
                    <?php
                        $i++;
                    } while($i < $spl_count);
                    ?>
                    </tbody>
                    </table>
                    <div id="sit_option_addfrm_btn"><button type="button" id="add_supply_row">옵션추가</button></div>
                    
                        <button type="button" id="supply_table_create">옵션목록생성</button>
                    
                </div>
                <div id="sit_option_addfrm"><?php include_once(G5_ADMIN_PATH.'/shop_admin/itemsupply.php'); ?></div>

                <script>
                $(function() {
                    <?php if($it['it_id'] && $ps_run) { ?>
                    // 추가옵션의 항목 설정
                    var arr_subj = new Array();
                    var subj, spl;

                    $("input[name='spl_subject[]']").each(function() {
                        subj = $.trim($(this).val());
                        if(subj && $.inArray(subj, arr_subj) == -1)
                            arr_subj.push(subj);
                    });

                    for(i=0; i<arr_subj.length; i++) {
                        var arr_spl = new Array();
                        $(".spl-subject-cell").each(function(index) {
                            subj = $(this).text();
                            if(subj == arr_subj[i]) {
                                spl = $(".spl-cell:eq("+index+")").text();
                                arr_spl.push(spl);
                            }
                        });

                        $("input[name='spl[]']:eq("+i+")").val(arr_spl.join());
                    }
                    <?php } ?>
                    // 입력필드추가
                    $("#add_supply_row").click(function() {
                        var $el = $("#sit_supply_frm tr:last");
                        var fld = "<tr>\n";
                        fld += "<th scope=\"row\">\n";
                        fld += "<label for=\"\">추가</label>\n";
                        fld += "<input type=\"text\" name=\"spl_subject[]\" value=\"\" class=\"form-input\" size=\"15\">\n";
                        fld += "</th>\n";
                        fld += "<td>\n";
                        fld += "<label for=\"\"><b>추가 항목</b></label>\n";
                        fld += "<input type=\"text\" name=\"spl[]\" value=\"\" class=\"form-input\" size=\"40\">\n";
                        fld += "<button type=\"button\" id=\"del_supply_row\" class=\"btn-inline\">삭제</button>\n";
                        fld += "</td>\n";
                        fld += "</tr>";

                        $el.after(fld);

                        supply_sequence();
                    });

                    // 입력필드삭제
                    $(document).on("click", "#del_supply_row", function() {
                        $(this).closest("tr").remove();

                        supply_sequence();
                    });

                    // 옵션목록생성
                    $("#supply_table_create").click(function() {
                        var it_id = $.trim($("input[name=it_id]").val());
                        var subject = new Array();
                        var supply = new Array();
                        var subj, spl;
                        var count = 0;
                        var $el_subj = $("input[name='spl_subject[]']");
                        var $el_spl = $("input[name='spl[]']");
                        var $supply_table = $("#sit_option_addfrm");

                        $el_subj.each(function(index) {
                            subj = $.trim($(this).val());
                            spl = $.trim($el_spl.eq(index).val());

                            if(subj && spl) {
                                subject.push(subj);
                                supply.push(spl);
                                count++;
                            }
                        });

                        if(!count) {
                            alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
                            return false;
                        }

                        $.post(
                            "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsupply.php",
                            { it_id: it_id, w: "<?php echo $w; ?>", 'subject[]': subject, 'supply[]': supply },
                            function(data) {
                                $supply_table.empty().html(data);
                            }
                        );
                    });

                    // 모두선택
                    $(document).on("click", "input[name=spl_chk_all]", function() {
                        if($(this).is(":checked")) {
                            $("input[name='spl_chk[]']").attr("checked", true);
                        } else {
                            $("input[name='spl_chk[]']").attr("checked", false);
                        }
                    });

                    // 선택삭제
                    $(document).on("click", "#sel_supply_delete", function() {
                        var $el = $("input[name='spl_chk[]']:checked");
                        if($el.length < 1) {
                            alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                            return false;
                        }

                        $el.closest("tr").remove();
                    });

                    // 일괄적용
                    $(document).on("click", "#spl_value_apply", function() {
                        if($(".spl_com_chk:checked").length < 1) {
                            alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                            return false;
                        }

                        var spl_price = $.trim($("#spl_com_price").val());
                        var spl_stock = $.trim($("#spl_com_stock").val());
                        var spl_noti = $.trim($("#spl_com_noti").val());
                        var spl_use = $("#spl_com_use").val();
                        var $el = $("input[name='spl_chk[]']:checked");

                        // 체크된 옵션이 있으면 체크된 것만 적용
                        if($el.length > 0) {
                            var $tr;
                            $el.each(function() {
                                $tr = $(this).closest("tr");

                                if($("#spl_com_price_chk").is(":checked"))
                                    $tr.find("input[name='spl_price[]']").val(spl_price);

                                if($("#spl_com_stock_chk").is(":checked"))
                                    $tr.find("input[name='spl_stock_qty[]']").val(spl_stock);

                                if($("#spl_com_noti_chk").is(":checked"))
                                    $tr.find("input[name='spl_noti_qty[]']").val(spl_noti);

                                if($("#spl_com_use_chk").is(":checked"))
                                    $tr.find("select[name='spl_use[]']").val(spl_use);
                            });
                        } else {
                            if($("#spl_com_price_chk").is(":checked"))
                                $("input[name='spl_price[]']").val(spl_price);

                            if($("#spl_com_stock_chk").is(":checked"))
                                $("input[name='spl_stock_qty[]']").val(spl_stock);

                            if($("#spl_com_noti_chk").is(":checked"))
                                $("input[name='spl_noti_qty[]']").val(spl_noti);

                            if($("#spl_com_use_chk").is(":checked"))
                                $("select[name='spl_use[]']").val(spl_use);
                        }
                    });
                });

                function supply_sequence()
                {
                    var $tr = $("#sit_supply_frm tr");
                    var seq;
                    var th_label, cell-label;

                    $tr.each(function(index) {
                        seq = index + 1;
                        $(this).find("th label").attr("for", "spl_subject_"+seq).text("추가"+seq);
                        $(this).find("th input").attr("id", "spl_subject_"+seq);
                        $(this).find("td label").attr("for", "spl_item_"+seq);
                        $(this).find("td label b").text("추가"+seq+" 항목");
                        $(this).find("td input").attr("id", "spl_item_"+seq);
                    });
                }
                </script>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
