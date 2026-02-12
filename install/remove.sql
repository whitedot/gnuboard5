-- --------------------------------------------------------
-- G5AIF 모바일 스킨 컬럼 제거 쿼리
-- 반응형 단일 스킨 체계 전환을 위한 정리
-- 실행 전 반드시 백업 권장
--
-- 주의: 이미 삭제된 컬럼은 오류 발생 (무시 가능)
-- MySQL 5.7 이하에서는 각 ALTER 문을 개별 실행 권장
-- --------------------------------------------------------

-- g5_config: 모바일 스킨 설정 컬럼 제거
ALTER TABLE `g5_config` DROP COLUMN `cf_mobile_new_skin`;
ALTER TABLE `g5_config` DROP COLUMN `cf_mobile_search_skin`;
ALTER TABLE `g5_config` DROP COLUMN `cf_mobile_connect_skin`;
ALTER TABLE `g5_config` DROP COLUMN `cf_mobile_faq_skin`;
ALTER TABLE `g5_config` DROP COLUMN `cf_mobile_member_skin`;

-- g5_board: 모바일 게시판 스킨/콘텐츠 컬럼 제거
ALTER TABLE `g5_board` DROP COLUMN `bo_mobile_skin`;
ALTER TABLE `g5_board` DROP COLUMN `bo_mobile_content_head`;
ALTER TABLE `g5_board` DROP COLUMN `bo_mobile_content_tail`;

-- g5_qa_config: 모바일 Q&A 스킨/콘텐츠 컬럼 제거
ALTER TABLE `g5_qa_config` DROP COLUMN `qa_mobile_skin`;
ALTER TABLE `g5_qa_config` DROP COLUMN `qa_mobile_content_head`;
ALTER TABLE `g5_qa_config` DROP COLUMN `qa_mobile_content_tail`;

-- g5_content: 모바일 내용관리 스킨/콘텐츠 컬럼 제거
ALTER TABLE `g5_content` DROP COLUMN `co_mobile_skin`;
ALTER TABLE `g5_content` DROP COLUMN `co_mobile_content`;

-- g5_faq_master: 모바일 FAQ HTML 컬럼 제거
ALTER TABLE `g5_faq_master` DROP COLUMN `fm_mobile_head_html`;
ALTER TABLE `g5_faq_master` DROP COLUMN `fm_mobile_tail_html`;

-- g5_shop_default: 모바일 쇼핑몰 기본설정 컬럼 제거
ALTER TABLE `g5_shop_default` DROP COLUMN `de_shop_mobile_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type1_list_use`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type1_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type1_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type1_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type1_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type1_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type2_list_use`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type2_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type2_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type2_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type2_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type2_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type3_list_use`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type3_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type3_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type3_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type3_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type3_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type4_list_use`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type4_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type4_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type4_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type4_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type4_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type5_list_use`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type5_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type5_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type5_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type5_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_type5_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_rel_list_use`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_rel_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_rel_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_rel_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_rel_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_search_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_search_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_search_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_search_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_search_img_height`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_listtype_list_skin`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_listtype_list_mod`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_listtype_list_row`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_listtype_img_width`;
ALTER TABLE `g5_shop_default` DROP COLUMN `de_mobile_listtype_img_height`;

-- g5_shop_category: 모바일 카테고리 스킨/설정 컬럼 제거
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_skin_dir`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_skin`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_img_width`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_img_height`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_list_mod`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_list_row`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_head_html`;
ALTER TABLE `g5_shop_category` DROP COLUMN `ca_mobile_tail_html`;

-- g5_shop_item: 모바일 상품 스킨/설명 컬럼 제거
ALTER TABLE `g5_shop_item` DROP COLUMN `it_mobile_skin`;
ALTER TABLE `g5_shop_item` DROP COLUMN `it_mobile_explan`;
ALTER TABLE `g5_shop_item` DROP COLUMN `it_mobile_head_html`;
ALTER TABLE `g5_shop_item` DROP COLUMN `it_mobile_tail_html`;

-- g5_shop_event: 모바일 이벤트 스킨/설정 컬럼 제거
ALTER TABLE `g5_shop_event` DROP COLUMN `ev_mobile_skin`;
ALTER TABLE `g5_shop_event` DROP COLUMN `ev_mobile_img_width`;
ALTER TABLE `g5_shop_event` DROP COLUMN `ev_mobile_img_height`;
ALTER TABLE `g5_shop_event` DROP COLUMN `ev_mobile_list_mod`;
ALTER TABLE `g5_shop_event` DROP COLUMN `ev_mobile_list_row`;
