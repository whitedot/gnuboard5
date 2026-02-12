-- ============================================================
-- G5AIF 모바일 스킨 관련 컬럼 제거 스크립트
-- 실행 전 반드시 DB 백업 필수!
-- 생성일: 2026-02-12
-- ============================================================

-- --------------------------
-- g5_config 테이블
-- --------------------------
ALTER TABLE `g5_config`
  DROP COLUMN IF EXISTS `cf_mobile_new_skin`,
  DROP COLUMN IF EXISTS `cf_mobile_search_skin`,
  DROP COLUMN IF EXISTS `cf_mobile_connect_skin`,
  DROP COLUMN IF EXISTS `cf_mobile_faq_skin`,
  DROP COLUMN IF EXISTS `cf_mobile_member_skin`;

-- --------------------------
-- g5_board 테이블
-- --------------------------
ALTER TABLE `g5_board`
  DROP COLUMN IF EXISTS `bo_mobile_skin`,
  DROP COLUMN IF EXISTS `bo_mobile_content_head`,
  DROP COLUMN IF EXISTS `bo_mobile_content_tail`;

-- --------------------------
-- g5_qa_config 테이블
-- --------------------------
ALTER TABLE `g5_qa_config`
  DROP COLUMN IF EXISTS `qa_mobile_skin`,
  DROP COLUMN IF EXISTS `qa_mobile_content_head`,
  DROP COLUMN IF EXISTS `qa_mobile_content_tail`;

-- --------------------------
-- g5_content 테이블
-- --------------------------
ALTER TABLE `g5_content`
  DROP COLUMN IF EXISTS `co_mobile_skin`,
  DROP COLUMN IF EXISTS `co_mobile_content`;

-- --------------------------
-- g5_faq_master 테이블
-- --------------------------
ALTER TABLE `g5_faq_master`
  DROP COLUMN IF EXISTS `fm_mobile_head_html`,
  DROP COLUMN IF EXISTS `fm_mobile_tail_html`;

-- --------------------------
-- g5_shop_default 테이블
-- --------------------------
ALTER TABLE `g5_shop_default`
  DROP COLUMN IF EXISTS `de_shop_mobile_skin`,
  DROP COLUMN IF EXISTS `de_mobile_type1_list_use`,
  DROP COLUMN IF EXISTS `de_mobile_type1_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_type1_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_type1_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_type1_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_type1_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_type2_list_use`,
  DROP COLUMN IF EXISTS `de_mobile_type2_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_type2_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_type2_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_type2_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_type2_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_type3_list_use`,
  DROP COLUMN IF EXISTS `de_mobile_type3_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_type3_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_type3_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_type3_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_type3_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_type4_list_use`,
  DROP COLUMN IF EXISTS `de_mobile_type4_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_type4_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_type4_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_type4_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_type4_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_type5_list_use`,
  DROP COLUMN IF EXISTS `de_mobile_type5_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_type5_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_type5_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_type5_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_type5_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_rel_list_use`,
  DROP COLUMN IF EXISTS `de_mobile_rel_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_rel_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_rel_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_rel_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_search_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_search_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_search_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_search_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_search_img_height`,
  DROP COLUMN IF EXISTS `de_mobile_listtype_list_skin`,
  DROP COLUMN IF EXISTS `de_mobile_listtype_list_mod`,
  DROP COLUMN IF EXISTS `de_mobile_listtype_list_row`,
  DROP COLUMN IF EXISTS `de_mobile_listtype_img_width`,
  DROP COLUMN IF EXISTS `de_mobile_listtype_img_height`;

-- --------------------------
-- g5_shop_category 테이블
-- --------------------------
ALTER TABLE `g5_shop_category`
  DROP COLUMN IF EXISTS `ca_mobile_skin_dir`,
  DROP COLUMN IF EXISTS `ca_mobile_skin`,
  DROP COLUMN IF EXISTS `ca_mobile_img_width`,
  DROP COLUMN IF EXISTS `ca_mobile_img_height`,
  DROP COLUMN IF EXISTS `ca_mobile_list_mod`,
  DROP COLUMN IF EXISTS `ca_mobile_list_row`,
  DROP COLUMN IF EXISTS `ca_mobile_head_html`,
  DROP COLUMN IF EXISTS `ca_mobile_tail_html`;

-- --------------------------
-- g5_shop_item 테이블
-- --------------------------
ALTER TABLE `g5_shop_item`
  DROP COLUMN IF EXISTS `it_mobile_skin`,
  DROP COLUMN IF EXISTS `it_mobile_explan`,
  DROP COLUMN IF EXISTS `it_mobile_head_html`,
  DROP COLUMN IF EXISTS `it_mobile_tail_html`;

-- --------------------------
-- g5_shop_event 테이블
-- --------------------------
ALTER TABLE `g5_shop_event`
  DROP COLUMN IF EXISTS `ev_mobile_skin`,
  DROP COLUMN IF EXISTS `ev_mobile_img_width`,
  DROP COLUMN IF EXISTS `ev_mobile_img_height`,
  DROP COLUMN IF EXISTS `ev_mobile_list_mod`,
  DROP COLUMN IF EXISTS `ev_mobile_list_row`;

-- ============================================================
-- 여분필드 제거 스크립트
-- ============================================================

-- --------------------------
-- g5_config 여분필드 (cf_1~10, cf_1_subj~10_subj)
-- --------------------------
ALTER TABLE `g5_config`
  DROP COLUMN IF EXISTS `cf_1_subj`,
  DROP COLUMN IF EXISTS `cf_2_subj`,
  DROP COLUMN IF EXISTS `cf_3_subj`,
  DROP COLUMN IF EXISTS `cf_4_subj`,
  DROP COLUMN IF EXISTS `cf_5_subj`,
  DROP COLUMN IF EXISTS `cf_6_subj`,
  DROP COLUMN IF EXISTS `cf_7_subj`,
  DROP COLUMN IF EXISTS `cf_8_subj`,
  DROP COLUMN IF EXISTS `cf_9_subj`,
  DROP COLUMN IF EXISTS `cf_10_subj`,
  DROP COLUMN IF EXISTS `cf_1`,
  DROP COLUMN IF EXISTS `cf_2`,
  DROP COLUMN IF EXISTS `cf_3`,
  DROP COLUMN IF EXISTS `cf_4`,
  DROP COLUMN IF EXISTS `cf_5`,
  DROP COLUMN IF EXISTS `cf_6`,
  DROP COLUMN IF EXISTS `cf_7`,
  DROP COLUMN IF EXISTS `cf_8`,
  DROP COLUMN IF EXISTS `cf_9`,
  DROP COLUMN IF EXISTS `cf_10`;

-- --------------------------
-- g5_member 여분필드 (mb_1~10)
-- --------------------------
ALTER TABLE `g5_member`
  DROP COLUMN IF EXISTS `mb_1`,
  DROP COLUMN IF EXISTS `mb_2`,
  DROP COLUMN IF EXISTS `mb_3`,
  DROP COLUMN IF EXISTS `mb_4`,
  DROP COLUMN IF EXISTS `mb_5`,
  DROP COLUMN IF EXISTS `mb_6`,
  DROP COLUMN IF EXISTS `mb_7`,
  DROP COLUMN IF EXISTS `mb_8`,
  DROP COLUMN IF EXISTS `mb_9`,
  DROP COLUMN IF EXISTS `mb_10`;

-- --------------------------
-- g5_qa_config 여분필드 (qa_1~5, qa_1_subj~5_subj)
-- --------------------------
ALTER TABLE `g5_qa_config`
  DROP COLUMN IF EXISTS `qa_1_subj`,
  DROP COLUMN IF EXISTS `qa_2_subj`,
  DROP COLUMN IF EXISTS `qa_3_subj`,
  DROP COLUMN IF EXISTS `qa_4_subj`,
  DROP COLUMN IF EXISTS `qa_5_subj`,
  DROP COLUMN IF EXISTS `qa_1`,
  DROP COLUMN IF EXISTS `qa_2`,
  DROP COLUMN IF EXISTS `qa_3`,
  DROP COLUMN IF EXISTS `qa_4`,
  DROP COLUMN IF EXISTS `qa_5`;

-- --------------------------
-- g5_qa_content 여분필드 (qa_1~5)
-- --------------------------
ALTER TABLE `g5_qa_content`
  DROP COLUMN IF EXISTS `qa_1`,
  DROP COLUMN IF EXISTS `qa_2`,
  DROP COLUMN IF EXISTS `qa_3`,
  DROP COLUMN IF EXISTS `qa_4`,
  DROP COLUMN IF EXISTS `qa_5`;
