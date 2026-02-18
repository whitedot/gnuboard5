<section id="anc_sitfrm_sendcost">
    <h2 class="section-title">배송비</h2>
    <?php echo $pg_anchor; ?>
    <div class="hint-box">
        <p>쇼핑몰설정 &gt; 배송비유형 설정보다 <strong>개별상품 배송비설정이 우선</strong> 적용됩니다.</p>
    </div>

    <div class="form-card table-shell">
        <table>
        <caption>배송비 입력</caption>
        <colgroup>
            <col class="col-4">
            <col>
            <col class="col-3">
        </colgroup>
        <tbody>
            <tr>
                <th scope="row"><label for="it_sc_type">배송비 유형</label></th>
                <td>
                    <?php echo help("배송비 유형을 선택하면 자동으로 항목이 변환됩니다."); ?>
                    <select name="it_sc_type" id="it_sc_type">
                        <option value="0"<?php echo get_selected('0', $it['it_sc_type']); ?>>쇼핑몰 기본설정 사용</option>
                        <option value="1"<?php echo get_selected('1', $it['it_sc_type']); ?>>무료배송</option>
                        <option value="2"<?php echo get_selected('2', $it['it_sc_type']); ?>>조건부 무료배송</option>
                        <option value="3"<?php echo get_selected('3', $it['it_sc_type']); ?>>유료배송</option>
                        <option value="4"<?php echo get_selected('4', $it['it_sc_type']); ?>>수량별 부과</option>
                    </select>
                </td>
                <td rowspan="4" id="sc_grp" class="cell-grpset">
                    <input type="checkbox" name="chk_ca_it_sendcost" value="1" id="chk_ca_it_sendcost">
                    <label for="chk_ca_it_sendcost">분류적용</label>
                    <input type="checkbox" name="chk_all_it_sendcost" value="1" id="chk_all_it_sendcost">
                    <label for="chk_all_it_sendcost">전체적용</label>
                </td>
            </tr>
            <tr id="sc_con_method">
                <th scope="row"><label for="it_sc_method">배송비 결제</label></th>
                <td>
                    <select name="it_sc_method" id="it_sc_method">
                        <option value="0"<?php echo get_selected('0', $it['it_sc_method']); ?>>선불</option>
                        <option value="1"<?php echo get_selected('1', $it['it_sc_method']); ?>>착불</option>
                        <option value="2"<?php echo get_selected('2', $it['it_sc_method']); ?>>사용자선택</option>
                    </select>
                </td>
            </tr>
            <tr id="sc_con_basic">
                <th scope="row"><label for="it_sc_price">기본배송비</label></th>
                <td>
                    <?php echo help("무료배송 이외의 설정에 적용되는 배송비 금액입니다."); ?>
                    <input type="text" name="it_sc_price" value="<?php echo $it['it_sc_price']; ?>" id="it_sc_price" class="form-input" size="8"> 원
                </td>
            </tr>
            <tr id="sc_con_minimum">
                <th scope="row"><label for="it_sc_minimum">배송비 상세조건</label></th>
                <td>
                    주문금액 <input type="text" name="it_sc_minimum" value="<?php echo $it['it_sc_minimum']; ?>" id="it_sc_minimum" class="form-input" size="8"> 이상 무료 배송
                </td>
            </tr>
            <tr id="sc_con_qty">
                <th scope="row"><label for="it_sc_qty">배송비 상세조건</label></th>
                <td>
                    <?php echo help("상품의 주문 수량에 따라 배송비가 부과됩니다. 예를 들어 기본배송비가 3,000원 수량을 3으로 설정했을 경우 상품의 주문수량이 5개이면 6,000원 배송비가 부과됩니다."); ?>
                    주문수량 <input type="text" name="it_sc_qty" value="<?php echo $it['it_sc_qty']; ?>" id="it_sc_qty" class="form-input" size="8"> 마다 배송비 부과
                </td>
            </tr>
        </tbody>
        </table>
    </div>

    <script>
    $(function() {
        <?php
        switch($it['it_sc_type']) {
            case 1:
                echo '$("#sc_con_method").hide();'.PHP_EOL;
                echo '$("#sc_con_basic").hide();'.PHP_EOL;
                echo '$("#sc_con_minimum").hide();'.PHP_EOL;
                echo '$("#sc_con_qty").hide();'.PHP_EOL;
                echo '$("#sc_grp").attr("rowspan","1");'.PHP_EOL;
                break;
            case 2:
                echo '$("#sc_con_method").show();'.PHP_EOL;
                echo '$("#sc_con_basic").show();'.PHP_EOL;
                echo '$("#sc_con_minimum").show();'.PHP_EOL;
                echo '$("#sc_con_qty").hide();'.PHP_EOL;
                echo '$("#sc_grp").attr("rowspan","4");'.PHP_EOL;
                break;
            case 3:
                echo '$("#sc_con_method").show();'.PHP_EOL;
                echo '$("#sc_con_basic").show();'.PHP_EOL;
                echo '$("#sc_con_minimum").hide();'.PHP_EOL;
                echo '$("#sc_con_qty").hide();'.PHP_EOL;
                echo '$("#sc_grp").attr("rowspan","3");'.PHP_EOL;
                break;
            case 4:
                echo '$("#sc_con_method").show();'.PHP_EOL;
                echo '$("#sc_con_basic").show();'.PHP_EOL;
                echo '$("#sc_con_minimum").hide();'.PHP_EOL;
                echo '$("#sc_con_qty").show();'.PHP_EOL;
                echo '$("#sc_grp").attr("rowspan","4");'.PHP_EOL;
                break;
            default:
                echo '$("#sc_con_method").hide();'.PHP_EOL;
                echo '$("#sc_con_basic").hide();'.PHP_EOL;
                echo '$("#sc_con_minimum").hide();'.PHP_EOL;
                echo '$("#sc_con_qty").hide();'.PHP_EOL;
                echo '$("#sc_grp").attr("rowspan","2");'.PHP_EOL;
                break;
        }
        ?>
        $("#it_sc_type").change(function() {
            var type = $(this).val();

            switch(type) {
                case "1":
                    $("#sc_con_method").hide();
                    $("#sc_con_basic").hide();
                    $("#sc_con_minimum").hide();
                    $("#sc_con_qty").hide();
                    $("#sc_grp").attr("rowspan","1");
                    break;
                case "2":
                    $("#sc_con_method").show();
                    $("#sc_con_basic").show();
                    $("#sc_con_minimum").show();
                    $("#sc_con_qty").hide();
                    $("#sc_grp").attr("rowspan","4");
                    break;
                case "3":
                    $("#sc_con_method").show();
                    $("#sc_con_basic").show();
                    $("#sc_con_minimum").hide();
                    $("#sc_con_qty").hide();
                    $("#sc_grp").attr("rowspan","3");
                    break;
                case "4":
                    $("#sc_con_method").show();
                    $("#sc_con_basic").show();
                    $("#sc_con_minimum").hide();
                    $("#sc_con_qty").show();
                    $("#sc_grp").attr("rowspan","4");
                    break;
                default:
                    $("#sc_con_method").hide();
                    $("#sc_con_basic").hide();
                    $("#sc_con_minimum").hide();
                    $("#sc_con_qty").hide();
                    $("#sc_grp").attr("rowspan","1");
                    break;
            }
        });
    });
    </script>
</section>
