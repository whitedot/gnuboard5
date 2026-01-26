<section id="anc_sitfrm_skin">
    <h2 class="h2_frm">스킨설정</h2>
    <?php echo $pg_anchor; ?>
    <div class="local_desc02 local_desc">
        <p>상품상세보기에서 사용할 스킨을 설정합니다.</p>
    </div>

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스킨설정</caption>
      
        <tbody>
        <tr>
            <th scope="row"><label for="it_skin">PC용 스킨</label></th>
            <td>
                <?php echo get_skin_select('shop', 'it_skin', 'it_skin', $it['it_skin']); ?>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_skin" value="1" id="chk_ca_it_skin">
                <label for="chk_ca_it_skin">분류적용</label>
                <input type="checkbox" name="chk_all_it_skin" value="1" id="chk_all_it_skin">
                <label for="chk_all_it_skin">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="it_mobile_skin">모바일용 스킨</label></th>
            <td>
                <?php echo get_mobile_skin_select('shop', 'it_mobile_skin', 'it_mobile_skin', $it['it_mobile_skin']); ?>
            </td>
            <td class="td_grpset">
                <input type="checkbox" name="chk_ca_it_mobile_skin" value="1" id="chk_ca_it_mobile_skin">
                <label for="chk_ca_it_mobile_skin">분류적용</label>
                <input type="checkbox" name="chk_all_it_mobile_skin" value="1" id="chk_all_it_mobile_skin">
                <label for="chk_all_it_mobile_skin">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
