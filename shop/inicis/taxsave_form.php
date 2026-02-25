<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<script>
    // 현금영수증 MAIN FUNC
    function  jsf__pay_cash( form )
    {
        jsf__show_progress(true);

        if ( jsf__chk_cash( form ) == false )
        {
            jsf__show_progress(false);
            return;
        }

        form.submit();
    }

    // 진행 바
    function  jsf__show_progress( show )
    {
        if ( show == true )
        {
            window.show_pay_btn.style.display  = "none";
            window.show_progress.style.display = "inline";
        }
        else
        {
            window.show_pay_btn.style.display  = "inline";
            window.show_progress.style.display = "none";
        }
    }

    // 포맷 체크
    function  jsf__chk_cash( form )
    {
        if (  form.tr_code[0].checked )
        {
            if ( form.id_info.value.length != 10 &&
                 form.id_info.value.length != 11 &&
                 form.id_info.value.length != 13 )
            {
                alert("주민번호 또는 휴대폰번호를 정확히 입력해 주시기 바랍니다.");
                form.id_info.select();
                form.id_info.focus();
                return false;
            }
        }
        else if (  form.tr_code[1].checked )
        {
            if ( form.id_info.value.length != 10 )
            {
                alert("사업자번호를 정확히 입력해 주시기 바랍니다.");
                form.id_info.select();
                form.id_info.focus();
                return false;
            }
        }
        return true;
    }

    function  jsf__chk_tr_code( form )
    {
        var span_tr_code_0 = document.getElementById( "span_tr_code_0" );
        var span_tr_code_1 = document.getElementById( "span_tr_code_1" );

        if ( form.tr_code[0].checked )
        {
            span_tr_code_0.style.display = "inline";
            span_tr_code_1.style.display = "none";
        }
        else if (form.tr_code[1].checked )
        {
            span_tr_code_0.style.display = "none";
            span_tr_code_1.style.display = "inline";
        }
    }

</script>

<div id="scash" class="new_win">
    <h1 id="win_title"><?php echo $g5['title']; ?></h1>

    <section>
        <h2>주문정보</h2>

        <div class="ui-form-grid">

            <div class="ui-form-row"><div class="ui-form-label">주문 번호</div><div class="ui-form-field"><?php echo $od_id; ?></div></div>
            <div class="ui-form-row"><div class="ui-form-label">상품 정보</div><div class="ui-form-field"><?php echo $goods_name; ?></div></div>
            <div class="ui-form-row"><div class="ui-form-label">주문자 이름</div><div class="ui-form-field"><?php echo $od_name; ?></div></div>
            <div class="ui-form-row"><div class="ui-form-label">주문자 E-Mail</div><div class="ui-form-field"><?php echo $od_email; ?></div></div>
            <div class="ui-form-row"><div class="ui-form-label">주문자 전화번호</div><div class="ui-form-field"><?php echo $od_tel; ?></div></div>
            
</div>
    </section>

    <section>
        <h2>현금영수증 발급 정보</h2>

        <form method="post" action="<?php echo G5_SHOP_URL; ?>/inicis/taxsave_result.php">
        <input type="hidden" name="tx"       value="<?php echo $tx; ?>">
        <input type="hidden" name="od_id"    value="<?php echo $od_id; ?>">
        <div class="ui-form-grid">

            <div class="ui-form-row"><div class="ui-form-label">원 거래 시각</div><div class="ui-form-field"><?php echo $trad_time; ?></div></div>
            <div class="ui-form-row"><div class="ui-form-label">발행 용도</div><div class="ui-form-field">
                    <input type="radio" name="tr_code" value="0" id="tr_code1" onClick="jsf__chk_tr_code( this.form )" checked>
                    <label for="tr_code1">소득공제용</label>
                    <input type="radio" name="tr_code" value="1" id="tr_code2" onClick="jsf__chk_tr_code( this.form )">
                    <label for="tr_code2">지출증빙용</label>
                </div></div>
            <div class="ui-form-row"><div class="ui-form-label">
                    <label for="id_info">
                        <span id="span_tr_code_0" style="display:inline">주민(휴대폰)번호</span>
                        <span id="span_tr_code_1" style="display:none">사업자번호</span>
                    </label>
                </div><div class="ui-form-field">
                    <input type="text" name="id_info" id="id_info" class="frm_input" size="16" maxlength="13"> ("-" 생략)
                </div></div>
            <div class="ui-form-row"><div class="ui-form-label"><label for="buyeremail">이메일</label></div><div class="ui-form-field"><input type="text" name="buyeremail" id="buyeremail" value="<?php echo $od_email; ?>" required class="required frm_input" size="30"></div></div>
            <div class="ui-form-row"><div class="ui-form-label"><label for="buyertel">휴대폰</label></div><div class="ui-form-field"><input type="text" name="buyertel" id="buyertel" value="" required class="required frm_input" size="20"></div></div>
            <div class="ui-form-row"><div class="ui-form-label">거래금액 총합</div><div class="ui-form-field"><?php echo number_format($amt_tot); ?>원</div></div>
            <div class="ui-form-row"><div class="ui-form-label">공급가액</div><div class="ui-form-field"><?php echo number_format($amt_sup); ?>원<!-- ((거래금액 총합 * 10) / 11) --></div></div>
            <div class="ui-form-row"><div class="ui-form-label">봉사료</div><div class="ui-form-field"><?php echo number_format($amt_svc); ?>원</div></div>
            <div class="ui-form-row"><div class="ui-form-label">부가가치세</div><div class="ui-form-field"><?php echo number_format($amt_tax); ?>원<!-- 거래금액 총합 - 공급가액 - 봉사료 --></div></div>
            
</div>

        <div id="scash_apply">
            <span id="show_pay_btn">
                <button type="button" onclick="jsf__pay_cash( this.form )">등록요청</button>
            </span>
            <span id="show_progress" style="display:none">
                <b>등록 진행중입니다. 잠시만 기다려주십시오</b>
            </span>
        </div>

        </form>
    </section>

</div>