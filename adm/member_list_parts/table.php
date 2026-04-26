<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" method="post" data-admin-member-list="true">
    <?php foreach ($member_list_view['hidden_fields'] as $name => $value) { ?>
        <input type="hidden" name="<?php echo $name; ?>" value="<?php echo get_sanitize_input($value); ?>">
    <?php } ?>
    <input type="hidden" name="token" value="<?php echo $member_list_view['admin_token'] ?>">

    <div class="member-table-card">
        <div class="table-wrapper">
            <table class="table">
                <caption><?php echo $member_list_view['caption']; ?></caption>
                <colgroup>
                    <col style="width: 3.5rem;">
                    <col style="width: 9rem;">
                    <col style="width: 8rem;">
                    <col style="width: 9rem;">
                    <col>
                    <col style="width: 6rem;">
                    <col style="width: 6rem;">
                    <col style="width: 10rem;">
                </colgroup>
                <thead class="border-default-300 bg-default-100 border-b font-semibold text-xs">
                    <tr>
                        <th scope="col" id="mb_list_chk">
                            <label for="chkall" class="sr-only">회원 전체</label>
                            <input type="checkbox" name="chkall" value="1" id="chkall">
                        </th>
                        <?php foreach ($member_list_view['table_columns'] as $column) { ?>
                            <th scope="col" id="<?php echo $column['id']; ?>"<?php echo $column['class'] !== '' ? ' class="' . $column['class'] . '"' : ''; ?>>
                                <?php if ($column['href'] !== '') { ?>
                                    <a href="<?php echo $column['href']; ?>"><?php echo $column['label']; ?></a>
                                <?php } else { ?>
                                    <?php echo $column['label']; ?>
                                <?php } ?>
                            </th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($member_list_view['items'] as $index => $item) { ?>
                        <tr>
                            <td headers="mb_list_chk" class="member-cell-fixed">
                                <input type="hidden" name="mb_id[<?php echo $index; ?>]" value="<?php echo $item['mb_id']; ?>" id="mb_id_<?php echo $index; ?>">
                                <label for="chk_<?php echo $index; ?>" class="sr-only"><?php echo $item['mb_name']; ?> <?php echo $item['mb_nick_text']; ?>님</label>
                                <input type="checkbox" name="chk[]" value="<?php echo $index; ?>" id="chk_<?php echo $index; ?>">
                            </td>
                            <td headers="mb_list_id" class="member-cell-fixed font-medium"><?php echo $item['display_mb_id']; ?></td>
                            <td headers="mb_list_name" class="member-cell-fixed"><?php echo $item['mb_name']; ?></td>
                            <td headers="mb_list_nick" class="member-cell-fixed"><?php echo $item['sideview_html']; ?></td>
                            <td headers="mb_list_email" class="member-cell-email"><?php echo $item['mb_email']; ?></td>
                            <td headers="mb_list_level" class="member-cell-fixed">
                                <span class="member-level">Lv.<?php echo $item['mb_level']; ?></span>
                            </td>
                            <td headers="mb_list_status" class="member-cell-fixed">
                                <span class="member-status <?php echo $item['status_class']; ?>"><?php echo $item['status_label']; ?></span>
                            </td>
                            <td headers="mb_list_mng" class="member-cell-manage text-end">
                                <div class="member-manage">
                                    <?php if (!empty($item['actions'])) { ?>
                                        <?php foreach ($item['actions'] as $action) { ?>
                                            <?php if ($action['type'] === 'link') { ?>
                                                <a href="<?php echo $action['href']; ?>" class="<?php echo $action['class']; ?>"><?php echo $action['label']; ?></a>
                                            <?php } elseif ($action['type'] === 'delete') { ?>
                                                <button type="button" class="<?php echo $action['class']; ?>" data-member-delete-id="<?php echo $action['mb_id']; ?>"><?php echo $action['label']; ?></button>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <span>-</span>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($member_list_view['items'])) { ?>
                        <tr><td colspan="<?php echo $member_list_view['colspan']; ?>"><?php echo $member_list_view['empty_message']; ?></td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="member-list-actions">
            <div class="flex items-center">
                <input type="submit" name="act_button" value="선택삭제" class="btn btn-outline-danger">
            </div>
            <?php if ($member_list_view['show_add_button']) { ?>
                <a href="<?php echo $member_list_view['add_member_url']; ?>" id="member_add" class="btn btn-surface-default-soft">회원추가</a>
            <?php } ?>
        </div>
    </div>
</form>

<form id="member_delete_form" method="post" action="./member_delete.php" class="hidden">
    <input type="hidden" name="token" value="<?php echo $member_list_view['admin_token']; ?>">
    <input type="hidden" name="mb_id" value="">
</form>

<?php echo $member_list_view['paging_html']; ?>
