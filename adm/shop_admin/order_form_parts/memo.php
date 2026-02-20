<section id="anc_sodr_memo">
    <h2>상점메모</h2>
    <?php echo $pg_anchor; ?>
    
        <p>
            현재 열람 중인 주문에 대한 내용을 메모하는곳입니다.<br>
            입금, 배송 내역을 메일로 발송할 경우 함께 기록됩니다.
        </p>
    

    <form name="frmorderform2" action="./orderformupdate.php" method="post">
    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="mod_type" value="memo">

    <div>
        <label for="od_shop_memo">상점메모</label>
        <textarea name="od_shop_memo" id="od_shop_memo" rows="8"><?php echo html_purifier(stripslashes($od['od_shop_memo'])); ?></textarea>
    </div>

    
        <input type="submit" value="메모 수정">
    

    </form>
</section>
