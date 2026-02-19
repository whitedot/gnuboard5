<section id="anc_sitfrm_optional">
    <h2>상세설명설정</h2>
    <?php echo $pg_anchor; ?>

    <div>
        <table>
        <caption>상세설명설정</caption>
        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">상품상단내용</th>
            <td><?php echo help("상품상세설명 페이지 상단에 출력하는 HTML 내용입니다."); ?><?php echo editor_html('it_head_html', get_text(html_purifier($it['it_head_html']), 0)); ?></td>
            <td>
                <input type="checkbox" name="chk_ca_it_head_html" value="1" id="chk_ca_it_head_html">
                <label for="chk_ca_it_head_html">분류적용</label>
                <input type="checkbox" name="chk_all_it_head_html" value="1" id="chk_all_it_head_html">
                <label for="chk_all_it_head_html">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row">상품하단내용</th>
            <td><?php echo help("상품상세설명 페이지 하단에 출력하는 HTML 내용입니다."); ?><?php echo editor_html('it_tail_html', get_text(html_purifier($it['it_tail_html']), 0)); ?></td>
            <td>
                <input type="checkbox" name="chk_ca_it_tail_html" value="1" id="chk_ca_it_tail_html">
                <label for="chk_ca_it_tail_html">분류적용</label>
                <input type="checkbox" name="chk_all_it_tail_html" value="1" id="chk_all_it_tail_html">
                <label for="chk_all_it_tail_html">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row">모바일 상품상단내용</th>
            <td><?php echo help("모바일 상품상세설명 페이지 상단에 출력하는 HTML 내용입니다."); ?><?php echo editor_html('it_mobile_head_html', get_text(html_purifier($it['it_mobile_head_html']), 0)); ?></td>
            <td>
                <input type="checkbox" name="chk_ca_it_mobile_head_html" value="1" id="chk_ca_it_mobile_head_html">
                <label for="chk_ca_it_mobile_head_html">분류적용</label>
                <input type="checkbox" name="chk_all_it_mobile_head_html" value="1" id="chk_all_it_mobile_head_html">
                <label for="chk_all_it_mobile_head_html">전체적용</label>
            </td>
        </tr>
        <tr>
            <th scope="row">모바일 상품하단내용</th>
            <td><?php echo help("모바일 상품상세설명 페이지 하단에 출력하는 HTML 내용입니다."); ?><?php echo editor_html('it_mobile_tail_html', get_text(html_purifier($it['it_mobile_tail_html']), 0)); ?></td>
            <td>
                <input type="checkbox" name="chk_ca_it_mobile_tail_html" value="1" id="chk_ca_it_mobile_tail_html">
                <label for="chk_ca_it_mobile_tail_html">분류적용</label>
                <input type="checkbox" name="chk_all_it_mobile_tail_html" value="1" id="chk_all_it_mobile_tail_html">
                <label for="chk_all_it_mobile_tail_html">전체적용</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</section>
