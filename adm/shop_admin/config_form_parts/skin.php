<section id="anc_scf_skin">
    <h2 class="section-title">스킨설정</h2>
    <?php echo $pg_anchor; ?>
    <div class="hint-box">
        <p>상품 분류리스트, 상품상세보기 등 에서 사용할 스킨을 설정합니다.</p>
    </div>

    <div class="form-card table-shell">
        <table>
        <caption>스킨설정</caption>
        <colgroup>
            <col class="col-4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="de_shop_skin">PC용 스킨</label></th>
            <td>
                <?php echo get_skin_select('shop', 'de_shop_skin', 'de_shop_skin', $default['de_shop_skin'], 'required'); ?>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>

<button type="button" class="get_shop_skin">테마 스킨설정 가져오기</button>
