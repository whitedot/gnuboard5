<section id="anc_sitfrm_cate">
    <h2 class="section-title">상품분류</h2>
    <?php echo $pg_anchor; ?>
    <div class="hint-box">
        <p>기본분류는 반드시 선택하셔야 합니다. 하나의 상품에 최대 3개의 다른 분류를 지정할 수 있습니다.</p>
    </div>

    <div class="form-card table-shell">
        <table>
        <caption>상품분류 입력</caption>
        <colgroup>
            <col class="col-4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="ca_id">기본분류</label></th>
            <td>
                <?php if ($w == "") echo help("기본분류를 선택하면, 판매/재고/HTML사용/판매자 E-mail 등을, 선택한 분류의 기본값으로 설정합니다."); ?>
                <select name="ca_id" id="ca_id" onchange="categorychange(this.form)">
                    <option value="">선택하세요</option>
                    <?php echo conv_selected_option($category_select, $it['ca_id']); ?>
                </select>
                <script>
                    var ca_use = new Array();
                    var ca_stock_qty = new Array();
                    //var ca_explan_html = new Array();
                    var ca_sell_email = new Array();
                    var ca_opt1_subject = new Array();
                    var ca_opt2_subject = new Array();
                    var ca_opt3_subject = new Array();
                    var ca_opt4_subject = new Array();
                    var ca_opt5_subject = new Array();
                    var ca_opt6_subject = new Array();
                    <?php echo "\n$script"; ?>
                </script>
            </td>
        </tr>
        <?php for ($i=2; $i<=3; $i++) { ?>
        <tr>
            <th scope="row"><label for="ca_id<?php echo $i; ?>"><?php echo $i; ?>차 분류</label></th>
            <td>
                <?php echo help($i.'차 분류는 기본 분류의 하위 분류 개념이 아니므로 기본 분류 선택시 해당 상품이 포함될 최하위 분류만 선택하시면 됩니다.'); ?>
                <select name="ca_id<?php echo $i; ?>" id="ca_id<?php echo $i; ?>">
                    <option value="">선택하세요</option>
                    <?php echo conv_selected_option($category_select, $it['ca_id'.$i]); ?>
                </select>
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>
</section>
