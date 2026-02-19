-- --------------------------------------------------------
-- G5AIF legacy mobile columns cleanup
-- 반응형 단일 스킨 체계 전환용 정리 스크립트
-- --------------------------------------------------------

ALTER TABLE `g5_config`
    DROP COLUMN `cf_mobile_new_skin`,
    DROP COLUMN `cf_mobile_search_skin`,
    DROP COLUMN `cf_mobile_connect_skin`,
    DROP COLUMN `cf_mobile_faq_skin`,
    DROP COLUMN `cf_mobile_member_skin`;

ALTER TABLE `g5_board`
    DROP COLUMN `bo_mobile_skin`,
    DROP COLUMN `bo_mobile_content_head`,
    DROP COLUMN `bo_mobile_content_tail`;

ALTER TABLE `g5_qa_config`
    DROP COLUMN `qa_mobile_skin`,
    DROP COLUMN `qa_mobile_content_head`,
    DROP COLUMN `qa_mobile_content_tail`;

ALTER TABLE `g5_content`
    DROP COLUMN `co_mobile_skin`,
    DROP COLUMN `co_mobile_content`;

ALTER TABLE `g5_faq_master`
    DROP COLUMN `fm_mobile_head_html`,
    DROP COLUMN `fm_mobile_tail_html`;

ALTER TABLE `g5_shop_default`
    DROP COLUMN `de_shop_mobile_skin`,
    DROP COLUMN `de_mobile_type1_list_use`,
    DROP COLUMN `de_mobile_type1_list_skin`,
    DROP COLUMN `de_mobile_type1_list_mod`,
    DROP COLUMN `de_mobile_type1_list_row`,
    DROP COLUMN `de_mobile_type1_img_width`,
    DROP COLUMN `de_mobile_type1_img_height`,
    DROP COLUMN `de_mobile_type2_list_use`,
    DROP COLUMN `de_mobile_type2_list_skin`,
    DROP COLUMN `de_mobile_type2_list_mod`,
    DROP COLUMN `de_mobile_type2_list_row`,
    DROP COLUMN `de_mobile_type2_img_width`,
    DROP COLUMN `de_mobile_type2_img_height`,
    DROP COLUMN `de_mobile_type3_list_use`,
    DROP COLUMN `de_mobile_type3_list_skin`,
    DROP COLUMN `de_mobile_type3_list_mod`,
    DROP COLUMN `de_mobile_type3_list_row`,
    DROP COLUMN `de_mobile_type3_img_width`,
    DROP COLUMN `de_mobile_type3_img_height`,
    DROP COLUMN `de_mobile_type4_list_use`,
    DROP COLUMN `de_mobile_type4_list_skin`,
    DROP COLUMN `de_mobile_type4_list_mod`,
    DROP COLUMN `de_mobile_type4_list_row`,
    DROP COLUMN `de_mobile_type4_img_width`,
    DROP COLUMN `de_mobile_type4_img_height`,
    DROP COLUMN `de_mobile_type5_list_use`,
    DROP COLUMN `de_mobile_type5_list_skin`,
    DROP COLUMN `de_mobile_type5_list_mod`,
    DROP COLUMN `de_mobile_type5_list_row`,
    DROP COLUMN `de_mobile_type5_img_width`,
    DROP COLUMN `de_mobile_type5_img_height`,
    DROP COLUMN `de_mobile_rel_list_use`,
    DROP COLUMN `de_mobile_rel_list_skin`,
    DROP COLUMN `de_mobile_rel_list_mod`,
    DROP COLUMN `de_mobile_rel_img_width`,
    DROP COLUMN `de_mobile_rel_img_height`,
    DROP COLUMN `de_mobile_search_list_skin`,
    DROP COLUMN `de_mobile_search_list_mod`,
    DROP COLUMN `de_mobile_search_list_row`,
    DROP COLUMN `de_mobile_search_img_width`,
    DROP COLUMN `de_mobile_search_img_height`,
    DROP COLUMN `de_mobile_listtype_list_skin`,
    DROP COLUMN `de_mobile_listtype_list_mod`,
    DROP COLUMN `de_mobile_listtype_list_row`,
    DROP COLUMN `de_mobile_listtype_img_width`,
    DROP COLUMN `de_mobile_listtype_img_height`;

ALTER TABLE `g5_shop_category`
    DROP COLUMN `ca_mobile_skin_dir`,
    DROP COLUMN `ca_mobile_skin`,
    DROP COLUMN `ca_mobile_img_width`,
    DROP COLUMN `ca_mobile_img_height`,
    DROP COLUMN `ca_mobile_list_mod`,
    DROP COLUMN `ca_mobile_list_row`,
    DROP COLUMN `ca_mobile_head_html`,
    DROP COLUMN `ca_mobile_tail_html`;

ALTER TABLE `g5_shop_item`
    DROP COLUMN `it_mobile_skin`,
    DROP COLUMN `it_mobile_explan`,
    DROP COLUMN `it_mobile_head_html`,
    DROP COLUMN `it_mobile_tail_html`;

ALTER TABLE `g5_shop_event`
    DROP COLUMN `ev_mobile_skin`,
    DROP COLUMN `ev_mobile_img_width`,
    DROP COLUMN `ev_mobile_img_height`,
    DROP COLUMN `ev_mobile_list_mod`,
    DROP COLUMN `ev_mobile_list_row`;
