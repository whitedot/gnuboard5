<?php
/**
 * IDE와 AI를 위한 가상 타입 정의
 * 이 파일은 실행되지 않으며, 오직 타입 힌팅을 위해 존재합니다.
 * @package Gnuboard5 & YoungCart5
 */

// ==========================
// 그누보드5 (Community)
// ==========================

/**
 * @typedef array{
 * od_id: int,                 // 주문번호
 * mb_id: string,              // 회원 아이디
 * od_name: string,            // 주문자명
 * od_email: string,           // 주문자 이메일
 * od_tel: string,             // 주문자 전화번호
 * od_hp: string,              // 주문자 휴대폰
 * od_zip1: string,            // 주문자 우편번호 1
 * od_zip2: string,            // 주문자 우편번호 2
 * od_addr1: string,           // 주문자 주소 1
 * od_addr2: string,           // 주문자 주소 2
 * od_addr3: string,           // 주문자 주소 3
 * od_addr_jibeon: string,     // 주문자 지번주소
 * od_deposit_name: string,    // 입금자명
 * od_b_name: string,          // 받는분 이름
 * od_b_tel: string,           // 받는분 전화번호
 * od_b_hp: string,            // 받는분 휴대폰
 * od_b_zip1: string,          // 받는분 우편번호 1
 * od_b_zip2: string,          // 받는분 우편번호 2
 * od_b_addr1: string,         // 받는분 주소 1
 * od_b_addr2: string,         // 받는분 주소 2
 * od_b_addr3: string,         // 받는분 주소 3
 * od_b_addr_jibeon: string,   // 받는분 지번주소
 * od_memo: string,            // 주문 메모
 * od_cart_count: int,         // 장바구니 상품 수
 * od_cart_price: int,         // 장바구니 총 금액
 * od_cart_coupon: int,        // 장바구니 쿠폰 금액
 * od_send_cost: int,          // 배송비
 * od_send_cost2: int,         // 추가 배송비
 * od_send_coupon: int,        // 배송비 쿠폰
 * od_receipt_price: int,      // 입금금액
 * od_cancel_price: int,       // 취소금액
 * od_receipt_point: int,      // 입금 포인트
 * od_refund_price: int,       // 환불금액
 * od_bank_account: string,    // 입금계좌
 * od_receipt_time: string,    // 입금일시
 * od_coupon: int,             // 쿠폰 금액
 * od_misu: int,               // 미수금
 * od_shop_memo: string,       // 상점 메모
 * od_mod_history: string,     // 수정 히스토리
 * od_status: string,          // 주문상태
 * od_hope_date: string,       // 희망배송일
 * od_settle_case: string,     // 결제수단
 * od_other_pay_type: string,  // 기타 결제수단
 * od_test: int,               // 테스트 주문
 * od_mobile: int,             // 모바일 주문
 * od_pg: string,              // PG사
 * od_tno: string,             // 거래고유번호
 * od_app_no: string,          // 승인번호
 * od_escrow: int,             // 에스크로 사용
 * od_casseqno: string,        // 현금영수증 일련번호
 * od_tax_flag: int,           // 과세/비과세
 * od_tax_mny: int,            // 과세 금액
 * od_vat_mny: int,            // 부가세
 * od_free_mny: int,           // 면세 금액
 * od_delivery_company: string,// 배송업체
 * od_invoice: string,         // 운송장번호
 * od_invoice_time: string,    // 운송장 입력일시
 * od_cash: int,               // 현금영수증
 * od_cash_no: string,         // 현금영수증 번호
 * od_cash_info: string,       // 현금영수증 정보
 * od_time: string,            // 주문일시
 * od_pwd: string,             // 비회원 비밀번호
 * od_ip: string               // IP
 * } G5ShopOrderShape
 */

/**
 * @typedef array{
 * mb_no: int,                // 회원 일련번호
 * mb_id: string,             // 회원 아이디
 * mb_password: string,       // 비밀번호 (암호화)
 * mb_name: string,           // 이름
 * mb_nick: string,           // 닉네임
 * mb_nick_date: string,      // 닉네임 변경일
 * mb_email: string,          // 이메일
 * mb_homepage: string,       // 홈페이지
 * mb_level: int,             // 권한 레벨
 * mb_sex: string,            // 성별 (M:남, F:여)
 * mb_birth: string,          // 생년월일
 * mb_tel: string,            // 전화번호
 * mb_hp: string,             // 휴대폰번호
 * mb_certify: string,        // 본인확인 여부 (ipin, hp)
 * mb_adult: int,             // 성인인증 여부
 * mb_dupinfo: string,        // 중복가입 확인정보 (DI)
 * mb_zip1: string,           // 우편번호 앞자리 (구)
 * mb_zip2: string,           // 우편번호 뒷자리 (구)
 * mb_addr1: string,          // 주소
 * mb_addr2: string,          // 상세주소
 * mb_addr3: string,          // 참고항목 (도로명주소)
 * mb_addr_jibeon: string,    // 지번주소
 * mb_signature: string,      // 서명
 * mb_recommend: string,      // 추천인 아이디
 * mb_point: int,             // 포인트
 * mb_today_login: string,    // 최근 로그인 일시
 * mb_login_ip: string,       // 로그인 IP
 * mb_datetime: string,       // 가입일시
 * mb_ip: string,             // 가입 IP
 * mb_leave_date: string,     // 탈퇴일자
 * mb_intercept_date: string, // 차단일자
 * mb_email_certify: string,  // 이메일 인증일시
 * mb_email_certify2: string, // 이메일 인증 키
 * mb_memo: string,           // 관리자 메모
 * mb_lost_certify: string,   // 아이디/비번 찾기 인증 키
 * mb_mailling: int,          // 메일 수신 여부
 * mb_mailling_date: string,  // 메일 수신 변경일시
 * mb_sms: int,               // SMS 수신 여부
 * mb_sms_date: string,       // SMS 수신 변경일시
 * mb_open: int,              // 정보 공개 여부
 * mb_open_date: string,      // 정보 공개 변경일시
 * mb_profile: string,        // 자기소개
 * mb_memo_call: string,      // 쪽지 알림 (실명 아이디 등)
 * mb_memo_cnt: int,          // 읽지 않은 쪽지 수
 * mb_scrap_cnt: int,         // 스크랩 수
 * mb_marketing_agree: int,   // 마케팅 수신 동의 여부
 * mb_marketing_date: string, // 마케팅 수신 동의 일시
 * mb_thirdparty_agree: int,  // 제3자 정보제공 동의 여부
 * mb_thirdparty_date: string,// 제3자 정보제공 동의 일시
 * mb_agree_log: string,      // 약관 동의 로그
 * mb_1: string,              // 여분필드 1
 * mb_2: string,              // 여분필드 2
 * mb_3: string,              // 여분필드 3
 * mb_4: string,              // 여분필드 4
 * mb_5: string,              // 여분필드 5
 * mb_6: string,              // 여분필드 6
 * mb_7: string,              // 여분필드 7
 * mb_8: string,              // 여분필드 8
 * mb_9: string,              // 여분필드 9
 * mb_10: string              // 여분필드 10
 * } G5MemberShape
 */

/**
 * @typedef array{
 * it_id: string,              // 상품 코드
 * ca_id: string,              // 카테고리 코드
 * ca_id2: string,             // 카테고리 코드 2
 * ca_id3: string,             // 카테고리 코드 3
 * it_skin: string,            // 스킨
 * it_mobile_skin: string,     // 모바일 스킨
 * it_name: string,            // 상품명
 * it_seo_title: string,       // SEO 제목
 * it_maker: string,           // 제조사
 * it_origin: string,          // 원산지
 * it_brand: string,           // 브랜드
 * it_model: string,           // 모델
 * it_option_subject: string,  // 옵션 제목
 * it_supply_subject: string,  // 추가옵션 제목
 * it_type1: int,              // 히트 상품
 * it_type2: int,              // 추천 상품
 * it_type3: int,              // 최신 상품
 * it_type4: int,              // 인기 상품
 * it_type5: int,              // 할인 상품
 * it_basic: string,           // 기본설명
 * it_explan: string,          // 상세설명
 * it_explan2: string,         // 모바일 상세설명 (사용안함)
 * it_mobile_explan: string,   // 모바일 상세설명
 * it_cust_price: int,         // 시중가격
 * it_price: int,              // 판매가격
 * it_point: int,              // 포인트
 * it_point_type: int,         // 포인트 유형
 * it_supply_point: int,       // 추가옵션 포인트
 * it_notax: int,              // 과세여부
 * it_sell_email: string,      // 판매자 이메일
 * it_use: int,                // 사용여부
 * it_nocoupon: int,           // 쿠폰적용안함
 * it_soldout: int,            // 품절
 * it_stock_qty: int,          // 재고수량
 * it_stock_sms: int,          // 재고 SMS 통보
 * it_noti_qty: int,           // 재고 통보 수량
 * it_sc_type: int,            // 배송비 유형
 * it_sc_method: int,          // 배송비 결제
 * it_sc_price: int,           // 배송비
 * it_sc_minimum: int,         // 배송비 최소
 * it_sc_qty: int,             // 배송비 수량
 * it_buy_min_qty: int,        // 최소 구매 수량
 * it_buy_max_qty: int,        // 최대 구매 수량
 * it_head_html: string,       // 상단 HTML
 * it_tail_html: string,       // 하단 HTML
 * it_mobile_head_html: string,// 모바일 상단 HTML
 * it_mobile_tail_html: string,// 모바일 하단 HTML
 * it_hit: int,                // 조회수
 * it_time: string,            // 등록일
 * it_update_time: string,     // 수정일
 * it_ip: string,              // 등록 IP
 * it_order: int,              // 출력 순서
 * it_tel_inq: int,            // 전화문의
 * it_info_gubun: string,      // 상품정보고시 구분
 * it_info_value: string,      // 상품정보고시 값
 * it_sum_qty: int,            // 판매수량
 * it_use_cnt: int,            // 후기수
 * it_use_avg: string,         // 후기 평점
 * it_shop_memo: string,       // 상점 메모
 * ec_mall_pid: string,        // 네이버 지식쇼핑 PID
 * it_img1: string,            // 이미지 1
 * it_img2: string,            // 이미지 2
 * it_img3: string,            // 이미지 3
 * it_img4: string,            // 이미지 4
 * it_img5: string,            // 이미지 5
 * it_img6: string,            // 이미지 6
 * it_img7: string,            // 이미지 7
 * it_img8: string,            // 이미지 8
 * it_img9: string,            // 이미지 9
 * it_img10: string,           // 이미지 10
 * it_1_subj: string,          // 여분필드 제목 1
 * it_2_subj: string,          // 여분필드 제목 2
 * it_3_subj: string,          // 여분필드 제목 3
 * it_4_subj: string,          // 여분필드 제목 4
 * it_5_subj: string,          // 여분필드 제목 5
 * it_6_subj: string,          // 여분필드 제목 6
 * it_7_subj: string,          // 여분필드 제목 7
 * it_8_subj: string,          // 여분필드 제목 8
 * it_9_subj: string,          // 여분필드 제목 9
 * it_10_subj: string,         // 여분필드 제목 10
 * it_1: string,               // 여분필드 1
 * it_2: string,               // 여분필드 2
 * it_3: string,               // 여분필드 3
 * it_4: string,               // 여분필드 4
 * it_5: string,               // 여분필드 5
 * it_6: string,               // 여분필드 6
 * it_7: string,               // 여분필드 7
 * it_8: string,               // 여분필드 8
 * it_9: string,               // 여분필드 9
 * it_10: string               // 여분필드 10
 * } G5ShopItemShape
 */

/**
 * @typedef array{
 * cf_id: int,                 // 설정 아이디
 * cf_title: string,           // 홈페이지 제목
 * cf_theme: string,           // 테마
 * cf_admin: string,           // 최고관리자 아이디
 * cf_admin_email: string,     // 최고관리자 이메일
 * cf_admin_email_name: string,// 최고관리자 이름
 * cf_add_script: string,      // 추가 스크립트
 * cf_use_point: int,          // 포인트 사용 여부
 * cf_point_term: int,         // 포인트 유효기간
 * cf_use_copy_log: int,       // 복사 로그 사용 여부
 * cf_use_email_certify: int,  // 메일 인증 사용 여부
 * cf_login_point: int,        // 로그인 포인트
 * cf_cut_name: int,           // 이름 가리기
 * cf_nick_modify: int,        // 닉네임 수정일
 * cf_new_skin: string,        // 최근게시물 스킨
 * cf_new_rows: int,           // 최근게시물 라인수
 * cf_search_skin: string,     // 검색 스킨
 * cf_connect_skin: string,    // 접속자 스킨
 * cf_faq_skin: string,        // FAQ 스킨
 * cf_read_point: int,         // 읽기 포인트
 * cf_write_point: int,        // 쓰기 포인트
 * cf_comment_point: int,      // 코멘트 포인트
 * cf_download_point: int,     // 다운로드 포인트
 * cf_write_pages: int,        // 페이지 표시 수
 * cf_mobile_pages: int,       // 모바일 페이지 표시 수
 * cf_link_target: string,     // 링크 타겟
 * cf_bbs_rewrite: int,        // 게시판 주소 재작성
 * cf_delay_sec: int,          // 글쓰기 지연 시간
 * cf_filter: string,          // 단어 필터링
 * cf_possible_ip: string,     // 접근 가능 IP
 * cf_intercept_ip: string,    // 접근 차단 IP
 * cf_analytics: string,       // 방문자 분석 스크립트
 * cf_add_meta: string,        // 추가 메타태그
 * cf_member_skin: string,     // 회원 스킨
 * cf_use_homepage: int,       // 홈페이지 사용 여부
 * cf_req_homepage: int,       // 홈페이지 필수 입력
 * cf_use_tel: int,            // 전화번호 사용 여부
 * cf_req_tel: int,            // 전화번호 필수 입력
 * cf_use_hp: int,             // 휴대폰 사용 여부
 * cf_req_hp: int,             // 휴대폰 필수 입력
 * cf_use_addr: int,           // 주소 사용 여부
 * cf_req_addr: int,           // 주소 필수 입력
 * cf_use_signature: int,      // 서명 사용 여부
 * cf_req_signature: int,      // 서명 필수 입력
 * cf_use_profile: int,        // 자기소개 사용 여부
 * cf_req_profile: int,        // 자기소개 필수 입력
 * cf_register_level: int,     // 회원가입 레벨
 * cf_register_point: int,     // 회원가입 포인트
 * cf_icon_level: int,         // 아이콘 레벨
 * cf_use_recommend: int,      // 추천인 사용 여부
 * cf_recommend_point: int,    // 추천인 포인트
 * cf_leave_day: int,          // 탈퇴후 재가입 기간
 * cf_search_part: int,        // 검색 단위
 * cf_email_use: int,          // 메일 발송 사용 여부
 * cf_email_wr_super_admin: int,
 * cf_email_wr_group_admin: int,
 * cf_email_wr_board_admin: int,
 * cf_email_wr_write: int,
 * cf_email_wr_comment_all: int,
 * cf_email_mb_super_admin: int,
 * cf_email_mb_member: int,
 * cf_email_po_super_admin: int,
 * cf_prohibit_id: string,     // 아이디 금지 단어
 * cf_prohibit_email: string,  // 이메일 금지 단어
 * cf_new_del: int,            // 최근게시물 삭제일
 * cf_memo_del: int,           // 쪽지 삭제일
 * cf_visit_del: int,          // 접속지로 삭제일
 * cf_popular_del: int,        // 인기검색어 삭제일
 * cf_optimize_date: string,   // 최적화 날짜
 * cf_use_member_icon: int,    // 회원아이콘 사용 여부
 * cf_member_icon_size: int,   // 회원아이콘 용량
 * cf_member_icon_width: int,  // 회원아이콘 가로
 * cf_member_icon_height: int, // 회원아이콘 세로
 * cf_member_img_size: int,    // 회원이미지 용량
 * cf_member_img_width: int,   // 회원이미지 가로
 * cf_member_img_height: int,  // 회원이미지 세로
 * cf_login_minutes: int,      // 현재접속자 유효시간
 * cf_image_extension: string, // 이미지 확장자
 * cf_flash_extension: string, // 플래시 확장자
 * cf_movie_extension: string, // 동영상 확장자
 * cf_formmail_is_member: int, // 폼메일 회원만 사용
 * cf_page_rows: int,          // 페이지당 라인수
 * cf_mobile_page_rows: int,   // 모바일 페이지당 라인수
 * cf_visit: string,           // 방문자
 * cf_max_po_id: int,          // 최대 포인트 ID
 * cf_stipulation: string,     // 이용약관
 * cf_privacy: string,         // 개인정보처리방침
 * cf_use_promotion: int,      // 프로모션 사용 여부
 * cf_open_modify: int,        // 정보공개 수정일
 * cf_memo_send_point: int,    // 쪽지 보낼때 포인트
 * cf_mobile_new_skin: string, // 모바일 최근게시물 스킨
 * cf_mobile_search_skin: string,// 모바일 검색 스킨
 * cf_mobile_connect_skin: string,// 모바일 접속자 스킨
 * cf_mobile_faq_skin: string, // 모바일 FAQ 스킨
 * cf_mobile_member_skin: string,// 모바일 회원 스킨
 * cf_captcha_mp3: string,     // 캡차 MP3
 * cf_editor: string,          // 에디터
 * cf_cert_use: int,           // 본인확인 사용 여부
 * cf_cert_find: int,          // 본인확인 찾기 사용 여부
 * cf_cert_ipin: string,       // 아이핀
 * cf_cert_hp: string,         // 휴대폰
 * cf_cert_simple: string,     // 간편인증
 * cf_cert_kg_cd: string,      // KG이니시스 코드
 * cf_cert_kg_mid: string,     // KG이니시스 상점아이디
 * cf_cert_use_seed: int,      // SEED 암호화 사용
 * cf_cert_kcb_cd: string,     // KCB 코드
 * cf_cert_kcp_cd: string,     // KCP 코드
 * cf_cert_kcp_enckey: string, // KCP 암호화키
 * cf_lg_mid: string,          // LGU+ 상점아이디
 * cf_lg_mert_key: string,     // LGU+ 상점키
 * cf_toss_client_key: string, // 토스 클라이언트키
 * cf_toss_secret_key: string, // 토스 시크릿키
 * cf_cert_limit: int,         // 본인확인 제한
 * cf_cert_req: int,           // 본인확인 필수
 * cf_sms_use: string,         // SMS 사용
 * cf_sms_type: string,        // SMS 타입
 * cf_icode_id: string,        // 아이코드 아이디
 * cf_icode_pw: string,        // 아이코드 비밀번호
 * cf_icode_server_ip: string, // 아이코드 서버 IP
 * cf_icode_server_port: string,// 아이코드 서버 포트
 * cf_icode_token_key: string, // 아이코드 토큰키
 * cf_googl_shorturl_apikey: string,// 구글 짧은주소 API 키
 * cf_social_login_use: int,   // 소셜로그인 사용 여부
 * cf_social_servicelist: string,// 소셜로그인 서비스 목록
 * cf_payco_clientid: string,  // 페이코 클라이언트 아이디
 * cf_payco_secret: string,    // 페이코 시크릿
 * cf_facebook_appid: string,  // 페이스북 앱 아이디
 * cf_facebook_secret: string, // 페이스북 시크릿
 * cf_twitter_key: string,     // 트위터 키
 * cf_twitter_secret: string,  // 트위터 시크릿
 * cf_google_clientid: string, // 구글 클라이언트 아이디
 * cf_google_secret: string,   // 구글 시크릿
 * cf_naver_clientid: string,  // 네이버 클라이언트 아이디
 * cf_naver_secret: string,    // 네이버 시크릿
 * cf_kakao_rest_key: string,  // 카카오 REST 키
 * cf_kakao_client_secret: string,// 카카오 클라이언트 시크릿
 * cf_kakao_js_apikey: string, // 카카오 JS API 키
 * cf_captcha: string,         // 캡차
 * cf_recaptcha_site_key: string,// 리캡차 사이트 키
 * cf_recaptcha_secret_key: string,// 리캡차 시크릿 키
 * cf_1_subj: string,          // 여분필드 제목 1
 * cf_2_subj: string,          // 여분필드 제목 2
 * cf_3_subj: string,          // 여분필드 제목 3
 * cf_4_subj: string,          // 여분필드 제목 4
 * cf_5_subj: string,          // 여분필드 제목 5
 * cf_6_subj: string,          // 여분필드 제목 6
 * cf_7_subj: string,          // 여분필드 제목 7
 * cf_8_subj: string,          // 여분필드 제목 8
 * cf_9_subj: string,          // 여분필드 제목 9
 * cf_10_subj: string,         // 여분필드 제목 10
 * cf_1: string,               // 여분필드 1
 * cf_2: string,               // 여분필드 2
 * cf_3: string,               // 여분필드 3
 * cf_4: string,               // 여분필드 4
 * cf_5: string,               // 여분필드 5
 * cf_6: string,               // 여분필드 6
 * cf_7: string,               // 여분필드 7
 * cf_8: string,               // 여분필드 8
 * cf_9: string,               // 여분필드 9
 * cf_10: string               // 여분필드 10
 * } G5ConfigShape
 */

/**
 * @typedef array{
 * bo_table: string,           // 게시판 테이블명
 * gr_id: string,              // 그룹 아이디
 * bo_subject: string,         // 게시판 제목
 * bo_mobile_subject: string,  // 모바일 게시판 제목
 * bo_device: string,          // 접속기기 (both, pc, mobile)
 * bo_admin: string,           // 게시판 관리자
 * bo_list_level: int,         // 목록 권한
 * bo_read_level: int,         // 읽기 권한
 * bo_write_level: int,        // 쓰기 권한
 * bo_reply_level: int,        // 답변 권한
 * bo_comment_level: int,      // 댓글 권한
 * bo_upload_level: int,       // 업로드 권한
 * bo_download_level: int,     // 다운로드 권한
 * bo_html_level: int,         // HTML 권한
 * bo_link_level: int,         // 링크 권한
 * bo_count_delete: int,       // 삭제시 차감 포인트
 * bo_count_modify: int,       // 수정시 차감 포인트
 * bo_read_point: int,         // 읽기 포인트
 * bo_write_point: int,        // 쓰기 포인트
 * bo_comment_point: int,      // 댓글 포인트
 * bo_download_point: int,     // 다운로드 포인트
 * bo_use_category: int,       // 카테고리 사용 여부
 * bo_category_list: string,   // 카테고리 목록
 * bo_use_sideview: int,       // 사이드뷰 사용 여부
 * bo_use_file_content: int,   // 파일 내용 사용 여부
 * bo_use_secret: int,         // 비밀글 사용 여부
 * bo_use_dhtml_editor: int,   // DHTML 에디터 사용 여부
 * bo_select_editor: string,   // 선택 에디터
 * bo_use_rss_view: int,       // RSS 사용 여부
 * bo_use_good: int,           // 추천 사용 여부
 * bo_use_nogood: int,         // 비추천 사용 여부
 * bo_use_name: int,           // 이름 사용 여부
 * bo_use_signature: int,      // 서명 사용 여부
 * bo_use_ip_view: int,        // IP 표시 여부
 * bo_use_list_view: int,      // 목록에서 내용 보기
 * bo_use_list_file: int,      // 목록에서 파일 보기
 * bo_use_list_content: int,   // 목록에서 내용 보기
 * bo_table_width: int,        // 테이블 폭
 * bo_subject_len: int,        // 제목 길이
 * bo_mobile_subject_len: int, // 모바일 제목 길이
 * bo_page_rows: int,          // 페이지당 목록 수
 * bo_mobile_page_rows: int,   // 모바일 페이지당 목록 수
 * bo_new: int,                // 새글 아이콘 출력 시간
 * bo_hot: int,                // 인기글 아이콘 출력 조회수
 * bo_image_width: int,        // 이미지 폭
 * bo_skin: string,            // 스킨 디렉토리
 * bo_mobile_skin: string,     // 모바일 스킨 디렉토리
 * bo_include_head: string,    // 상단 파일 경로
 * bo_include_tail: string,    // 하단 파일 경로
 * bo_content_head: string,    // 상단 내용
 * bo_mobile_content_head: string,// 모바일 상단 내용
 * bo_content_tail: string,    // 하단 내용
 * bo_mobile_content_tail: string,// 모바일 하단 내용
 * bo_insert_content: string,  // 글쓰기 기본 내용
 * bo_gallery_cols: int,       // 갤러리 이미지 수
 * bo_gallery_width: int,      // 갤러리 이미지 폭
 * bo_gallery_height: int,     // 갤러리 이미지 높이
 * bo_mobile_gallery_width: int,// 모바일 갤러리 이미지 폭
 * bo_mobile_gallery_height: int,// 모바일 갤러리 이미지 높이
 * bo_upload_size: int,        // 업로드 파일 크기
 * bo_reply_order: int,        // 답변 출력 순서
 * bo_use_search: int,         // 검색 사용 여부
 * bo_order: int,              // 출력 순서
 * bo_count_write: int,        // 글 등록 수
 * bo_count_comment: int,      // 댓글 등록 수
 * bo_write_min: int,          // 최소 글자 수
 * bo_write_max: int,          // 최대 글자 수
 * bo_comment_min: int,        // 최소 댓글 수
 * bo_comment_max: int,        // 최대 댓글 수
 * bo_notice: string,          // 공지사항
 * bo_upload_count: int,       // 업로드 파일 수
 * bo_use_email: int,          // 메일 발송 사용 여부
 * bo_use_cert: string,        // 본인확인 사용 여부
 * bo_use_sns: int,            // SNS 사용 여부
 * bo_use_captcha: int,        // 캡차 사용 여부
 * bo_sort_field: string,      // 정렬 필드
 * bo_1_subj: string,          // 여분필드 제목 1
 * bo_2_subj: string,          // 여분필드 제목 2
 * bo_3_subj: string,          // 여분필드 제목 3
 * bo_4_subj: string,          // 여분필드 제목 4
 * bo_5_subj: string,          // 여분필드 제목 5
 * bo_6_subj: string,          // 여분필드 제목 6
 * bo_7_subj: string,          // 여분필드 제목 7
 * bo_8_subj: string,          // 여분필드 제목 8
 * bo_9_subj: string,          // 여분필드 제목 9
 * bo_10_subj: string,         // 여분필드 제목 10
 * bo_1: string,               // 여분필드 1
 * bo_2: string,               // 여분필드 2
 * bo_3: string,               // 여분필드 3
 * bo_4: string,               // 여분필드 4
 * bo_5: string,               // 여분필드 5
 * bo_6: string,               // 여분필드 6
 * bo_7: string,               // 여분필드 7
 * bo_8: string,               // 여분필드 8
 * bo_9: string,               // 여분필드 9
 * bo_10: string               // 여분필드 10
 * } G5BoardShape
 */

/**
 * @typedef array{
 * wr_id: int,                 // 게시물 아이디
 * wr_num: int,                // 게시물 정렬 순서
 * wr_reply: string,           // 답변 단계
 * wr_parent: int,             // 부모글 아이디
 * wr_is_comment: int,         // 댓글 여부
 * wr_comment: int,            // 댓글 수
 * wr_comment_reply: string,   // 댓글 답변 단계
 * wr_subject: string,         // 제목
 * wr_content: string,         // 내용
 * wr_link1: string,           // 링크 1
 * wr_link2: string,           // 링크 2
 * wr_link1_hit: int,          // 링크 1 조회수
 * wr_link2_hit: int,          // 링크 2 조회수
 * wr_hit: int,                // 조회수
 * wr_good: int,               // 추천수
 * wr_nogood: int,             // 비추천수
 * mb_id: string,              // 회원 아이디
 * wr_password: string,        // 비밀번호
 * wr_name: string,            // 이름
 * wr_email: string,           // 이메일
 * wr_homepage: string,        // 홈페이지
 * wr_datetime: string,        // 작성일시
 * wr_file: int,               // 파일 수
 * wr_last: string,            // 마지막 댓글 일시
 * wr_ip: string,              // IP
 * wr_facebook_user: string,   // 페이스북 사용자
 * wr_twitter_user: string,    // 트위터 사용자
 * wr_1: string,               // 여분필드 1
 * wr_2: string,               // 여분필드 2
 * wr_3: string,               // 여분필드 3
 * wr_4: string,               // 여분필드 4
 * wr_5: string,               // 여분필드 5
 * wr_6: string,               // 여분필드 6
 * wr_7: string,               // 여분필드 7
 * wr_8: string,               // 여분필드 8
 * wr_9: string,               // 여분필드 9
 * wr_10: string               // 여분필드 10
 * } G5WriteShape
 */

// ==========================
// 영카트5 (Shopping Mall)
// ==========================

/**
 * it_type2: int,
 * it_type3: int,
 * it_type4: int,
 * it_type5: int,
 * it_basic: string,
 * it_explan: string,
 * it_mobile_explan: string,
 * it_cust_price: int,
 * it_price: int,
 * it_point: int,
 * it_point_type: int,
 * it_stock_qty: int,
 * it_noti_qty: int,
 * it_buy_min_qty: int,
 * it_buy_max_qty: int,
 * it_head_html: string,
 * it_tail_html: string,
 * it_mobile_head_html: string,
 * it_mobile_tail_html: string,
 * it_hit: int,
 * it_time: string,
 * it_ip: string,
 * it_order: int,
 * it_tel_inq: int,
 * it_info_value: string,
 * it_use: int,
 * it_nocoupon: int,
 * it_soldout: int,
 * it_img1: string,
 * it_img2: string
 * } YCItemShape
 */

/**
 * 장바구니 정보
 * @typedef array{
 * ct_id: int,
 * od_id: string,
 * mb_id: string,
 * it_id: string,
 * it_name: string,
 * ct_status: string,
 * ct_history: string,
 * ct_price: int,
 * ct_point: int,
 * ct_point_use: int,
 * ct_stock_use: int,
 * ct_option: string,
 * ct_qty: int,
 * ct_notax: int,
 * io_id: string,
 * io_type: int,
 * io_price: int,
 * ct_time: string,
 * ct_ip: string,
 * ct_send_cost: int,
 * ct_direct: int,
 * ct_select: int
 * } YCCartShape
 */

/**
 * 주문 정보
 * @typedef array{
 * od_id: string,
 * mb_id: string,
 * od_name: string,
 * od_email: string,
 * od_tel: string,
 * od_hp: string,
 * od_zip1: string,
 * od_zip2: string,
 * od_addr1: string,
 * od_addr2: string,
 * od_addr3: string,
 * od_addr_jibeon: string,
 * od_deposit_name: string,
 * od_b_name: string,
 * od_b_tel: string,
 * od_b_hp: string,
 * od_b_zip1: string,
 * od_b_zip2: string,
 * od_b_addr1: string,
 * od_b_addr2: string,
 * od_b_addr3: string,
 * od_b_addr_jibeon: string,
 * od_memo: string,
 * od_cart_count: int,
 * od_cart_price: int,
 * od_cart_coupon: int,
 * od_send_cost: int,
 * od_send_coupon: int,
 * od_receipt_price: int,
 * od_cancel_price: int,
 * od_receipt_point: int,
 * od_refund_price: int,
 * od_bank_account: string,
 * od_receipt_time: string,
 * od_coupon: int,
 * od_misu: int,
 * od_shop_memo: string,
 * od_mod_history: string,
 * od_status: string,
 * od_hope_date: string,
 * od_settle_case: string,
 * od_other_pay_type: string,
 * od_test: int,
 * od_mobile: int,
 * od_pg: string,
 * od_tno: string,
 * od_app_no: string,
 * od_escrow: int,
 * od_tax_flag: int,
 * od_tax_mny: int,
 * od_vat_mny: int,
 * od_free_mny: int,
 * od_delivery_company: string,
 * od_invoice: string,
 * od_invoice_time: string,
 * od_cash: int,
 * od_cash_no: string,
 * od_cash_info: string,
 * od_time: string,
 * od_ip: string
 * } YCOrderShape
 */