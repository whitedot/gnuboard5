<section id="anc_sitfrm_compact">
    <h2 class="h2_frm">상품요약정보</h2>
    <?php echo $pg_anchor; ?>
    <div class="local_desc02 local_desc">
        <p><strong>전자상거래 등에서의 상품 등의 정보제공에 관한 고시</strong>에 따라 총 35개 상품군에 대해 상품 특성 등을 양식에 따라 입력할 수 있습니다.</p>
    </div>

    <div id="sit_compact">
        <?php echo help("상품군을 선택하면 자동으로 항목이 변환됩니다."); ?>
        <select id="it_info_gubun" name="it_info_gubun">
            <option value="">상품군을 선택하세요.</option>
            <?php
            if(!$it['it_info_gubun']) $it['it_info_gubun'] = 'wear';
            foreach($item_info as $key=>$value) {
                $opt_value = $key;
                $opt_text  = $value['title'];
                echo '<option value="'.$opt_value.'" '.get_selected($opt_value, $it['it_info_gubun']).'>'.$opt_text.'</option>'.PHP_EOL;
            }
            ?>
        </select>
    </div>
    <div id="sit_compact_fields"><?php include_once(G5_ADMIN_PATH.'/shop_admin/iteminfo.php'); ?></div>
</section>


<script>
$(function(){
    $(document).on("change", "#it_info_gubun", function() {
        var gubun = $(this).val();
        $.post(
            "<?php echo G5_ADMIN_URL; ?>/shop_admin/iteminfo.php",
            { it_id: "<?php echo $it['it_id']; ?>", gubun: gubun },
            function(data) {
                $("#sit_compact_fields").empty().html(data);
            }
        );
    });
});
</script>
