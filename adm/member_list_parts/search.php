<div class="member-search-card">
    <form id="fsearch" name="fsearch" method="get">
        <div class="member-search-fields">
            <div class="member-field">
                <label for="sfl">검색대상</label>
                <select name="sfl" id="sfl">
                    <?php foreach ($member_list_view['search_view']['field_options'] as $option) { ?>
                        <option value="<?php echo $option['value']; ?>"<?php echo $option['selected'] ? ' selected' : ''; ?>><?php echo $option['label']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="member-field">
                <label for="stx">검색어</label>
                <input type="text" name="stx" value="<?php echo $member_list_view['search_view']['stx_value']; ?>" id="stx" required class="required" placeholder="검색어를 입력하세요">
            </div>
            <button type="submit" class="btn btn-solid-primary member-search-submit">검색</button>
        </div>
    </form>
</div>
