<?php
$sub_menu = '500120';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = '주문내역출력';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div>

    <div>
        <form name="forderprint" action="./orderprintresult.php" onsubmit="return forderprintcheck(this);" autocomplete="off">
        <input type="hidden" name="case" value="1">

        <strong>기간별 출력</strong>
        <input type="radio" name="csv" value="xls" id="xls1">
        <label for="xls1">MS엑셀 XLS 데이터</label>
        <input type="radio" name="csv" value="csv" id="csv1">
        <label for="csv1">MS엑셀 CSV 데이터</label>
        <label for="ct_status_p">출력대상</label>
        <select name="ct_status" id="ct_status_p">
            <option value="주문">주문</option>
            <option value="입금">입금</option>
            <option value="준비">준비</option>
            <option value="배송">배송</option>
            <option value="완료">완료</option>
            <option value="취소">취소</option>
            <option value="반품">반품</option>
            <option value="품절">품절</option>
            <option value="">전체</option>
        </select>
        <label for="fr_date">기간 시작일</label>
        <input type="text" name="fr_date" value="<?php echo date("Ymd"); ?>" id="fr_date" required class="required" size="10" maxlength="8">
        ~
        <label for="to_date">기간 종료일</label>
        <input type="text" name="to_date" value="<?php echo date("Ymd"); ?>" id="to_date" required class="required" size="10" maxlength="8">
        <input type="submit" value="출력 (새창)">

        </form>
    </div>

    <div>

        <form name="forderprint" action="./orderprintresult.php" onsubmit="return forderprintcheck(this);" autocomplete="off" >
        <input type="hidden" name="case" value="2">
        <strong>주문번호구간별 출력</strong>

        <input type="radio" name="csv" value="xls" id="xls2">
        <label for="xls2">MS엑셀 XLS 데이터</label>
        <input type="radio" name="csv" value="csv" id="csv2">
        <label for="csv2">MS엑셀 CSV 데이터</label>
        <label for="ct_status_n">출력대상</label>
        <select name="ct_status" id="ct_status_n">
            <option value="주문">주문</option>
            <option value="입금">입금</option>
            <option value="준비">준비</option>
            <option value="배송">배송</option>
            <option value="완료">완료</option>
            <option value="취소">취소</option>
            <option value="반품">반품</option>
            <option value="품절">품절</option>
            <option value="">전체</option>
        </select>
        <label for="fr_od_id">주문번호 구간 시작</label>
        <input type="text" name="fr_od_id" id="fr_od_id" required class="required" size="10" maxlength="20">
        ~
        <label for="fr_od_id">주문번호 구간 종료</label>
        <input type="text" name="to_od_id" id="to_od_id" required class="required" size="10" maxlength="20">
        <input type="submit" value="출력 (새창)">

        </form>
    </div>

</div>

<div>
    <a href="./orderlist.php">주문내역</a>
</div>

<div>
    <p>기간별 혹은 주문번호구간별 주문내역을 새창으로 출력할 수 있습니다.</p>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yymmdd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function forderprintcheck(f)
{
    if (f.csv[0].checked || f.csv[1].checked)
    {
        f.target = "_top";
    }
    else
    {
        var win = window.open("", "winprint", "left=10,top=10,width=670,height=800,menubar=yes,toolbar=yes,scrollbars=yes");
        f.target = "winprint";
    }

    f.submit();
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');