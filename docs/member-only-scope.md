# Member-Only Scope

## 목적

`BBS`, `SHOP` 기능을 제거하고 회원 기능만 남기는 방향으로 코드베이스를 축소한다.

이번 문서는 다음 4가지를 고정하기 위한 기준 문서다.

- 유지 기능
- 제거 기능
- 1차 삭제 대상
- `member` 모듈 이관 대상

## 전제

- 현재 회원 기능의 실제 엔트리포인트는 대부분 `bbs/` 아래에 있다.
- 따라서 `bbs` 제거는 게시판 제거와 동일하지 않다. 회원 기능을 새 위치로 옮긴 뒤 `bbs`를 비워야 한다.
- 현재 운영 기준 경로는 대부분 `member/`를 우선 사용하도록 바꿨고, `bbs/`는 호환 레이어 성격이 강해졌다.
- 신규 설치에서는 쇼핑몰 생성과 FAQ 기본 설정을 더 이상 만들지 않는다.

## 유지 기능

### 필수 유지

- 회원가입
- 로그인 / 로그아웃
- 아이디 / 비밀번호 찾기 및 재설정
- 이메일 인증
- 본인인증 갱신
- 회원정보 조회 / 수정
- 회원탈퇴
- 관리자 회원관리
- 관리자 권한관리
- 세션 / 자동로그인
- 회원 데이터 조회 공통 함수

### 운영 판단 후 유지 여부 결정

- 방문 통계
- 메일 발송 이력

포인트, 공개 프로필, 회원간 쪽지는 회원 전용 범위에서 제거하기로 확정했다.

## 제거 기능

### 게시판 계열

- 게시판 목록 / 읽기 / 쓰기 / 수정 / 삭제
- 댓글
- 첨부 다운로드
- 검색
- 추천 / 비추천
- 새글 / 인기글 / 현재접속자
- 그룹 / 게시판 관리
- FAQ
- 1:1 문의(QA)
- 설문
- RSS
- 스크랩
- 게시판 자동저장
- 게시판 토큰 / 댓글 토큰

### 쇼핑몰 계열

- 상품 목록 / 상세
- 카테고리
- 장바구니
- 주문 / 결제
- 배송비 계산
- 주문조회
- 쿠폰
- 위시리스트
- 개인결제
- PG 연동
- 쇼핑몰 마이페이지
- 쇼핑몰 관리자 메뉴 및 설정

### 이번 방향에서 제거 확정

- 회원간 쪽지
- 회원 포인트 및 포인트 내역 페이지
- 공개 프로필 페이지
- FAQ
- 설문(poll)

위 항목들은 현재 코드에서 사용자 진입점과 관리자 노출을 종료했고, 기존 `bbs` 경로는 호환용 안내 스텁만 유지한다.

## 유지 기능 기준 파일

### 공통 / 부트스트랩

- `common.php`
- `_common.php`
- `config.php`
- `extend/`
- `lib/common.lib.php`
- `lib/common.data.lib.php`
- `lib/common.session.lib.php`
- `lib/common.crypto.lib.php`
- `lib/register.lib.php`
- `lib/mailer.lib.php`

### 회원 엔트리포인트

- `bbs/login.php`
- `bbs/login_check.php`
- `bbs/logout.php`
- `bbs/register.php`
- `bbs/register_form.php`
- `bbs/register_form_update.php`
- `bbs/register_result.php`
- `bbs/member_confirm.php`
- `bbs/member_leave.php`
- `bbs/password_lost.php`
- `bbs/password_lost2.php`
- `bbs/password_lost_certify.php`
- `bbs/password_reset.php`
- `bbs/password_reset_update.php`
- `bbs/register_email.php`
- `bbs/register_email_update.php`
- `bbs/email_certify.php`
- `bbs/email_stop.php`
- `bbs/member_cert_refresh.php`
- `bbs/member_cert_refresh_update.php`

### 관리자 기능

- `adm/member_list.php`
- `adm/member_form.php`
- `adm/member_form_update.php`
- `adm/member_delete.php`
- `adm/member_list_update.php`
- `adm/member_list_delete.php`
- `adm/auth_list.php`
- `adm/auth_update.php`
- `adm/auth_list_delete.php`

## 제거 기능 기준 파일

### `bbs/` 제거 대상

- `board.php`
- `board_head.php`
- `board_tail.php`
- `board_list_update.php`
- `list.php`
- `view.php`
- `write.php`
- `write_update.php`
- `write_update_mail.php`
- `write_comment_update.php`
- `write_comment_update.sns.php`
- `delete.php`
- `delete_all.php`
- `delete_comment.php`
- `download.php`
- `search.php`
- `good.php`
- `group.php`
- `new.php`
- `new_delete.php`
- `current_connect.php`
- `faq.php`
- `qalist.php`
- `qaview.php`
- `qawrite.php`
- `qawrite_update.php`
- `qadelete.php`
- `qadownload.php`
- `qahead.php`
- `qatail.php`
- `poll_result.php`
- `poll_update.php`
- `poll_etc_update.php`
- `poll_etc_update_mail.php`
- `rss.php`
- `scrap.php`
- `scrap_delete.php`
- `scrap_popin.php`
- `scrap_popin_update.php`
- `sns_send.php`
- `ajax.autosave.php`
- `ajax.autosavedel.php`
- `ajax.autosavelist.php`
- `ajax.autosaveload.php`
- `ajax.comment_token.php`
- `ajax.filter.php`
- `ajax.write.token.php`
- `write_token.php`
- `view_comment.php`
- `view_image.php`
- `move.php`
- `move_update.php`
- `link.php`
- `content.php`
- `formmail.php`
- `formmail_send.php`
- `visit_insert.inc.php`
- `visit_browscap.inc.php`
- `db_table.optimize.php`

### `shop/` 제거 대상

- `shop/` 전체 디렉터리
- `shop.config.php`
- `adm/shop_admin/` 전체 디렉터리
- `adm/admin.menu400.shop_1of2.php`
- `adm/admin.menu500.shop_2of2.php`
- `lib/shop.coupon.lib.php`
- `lib/shop.data.lib.php`
- `lib/shop.delivery.lib.php`
- `lib/shop.image.lib.php`
- `lib/shop.item.lib.php`
- `lib/shop.lib.php`
- `lib/shop.order.lib.php`
- `lib/shop.pay.lib.php`
- `lib/shop.point.lib.php`
- `lib/shop.price.lib.php`
- `lib/shop.uri.lib.php`
- `lib/shop.user.lib.php`
- `lib/shop.util.lib.php`
- `lib/iteminfo.lib.php`
- `lib/naverpay.lib.php`

## 1차 삭제 대상

1차 삭제는 "회원 기능과 직접 충돌하지 않으면서 의존성을 빨리 줄일 수 있는 영역"부터 잡는다.

### 즉시 삭제 후보

- `shop/` 전체
- `adm/shop_admin/` 전체
- `adm/admin.menu400.shop_1of2.php`
- `adm/admin.menu500.shop_2of2.php`
- `shop.config.php`
- `lib/shop.*.lib.php`
- `lib/iteminfo.lib.php`
- `lib/naverpay.lib.php`

### 회원 이관 후 삭제 후보

- `bbs/board*.php`
- `bbs/list.php`
- `bbs/view.php`
- `bbs/write*.php`
- `bbs/delete*.php`
- `bbs/download.php`
- `bbs/search.php`
- `bbs/group.php`
- `bbs/faq.php`
- `bbs/qa*.php`
- `bbs/poll*.php`
- `bbs/rss.php`
- `bbs/scrap*.php`
- `bbs/current_connect.php`
- `bbs/formmail*.php`
- `bbs/link.php`
- `bbs/content.php`
- `bbs/visit_*.inc.php`
- `bbs/db_table.optimize.php`

### 호환 레이어 유지 후 삭제 후보

- `bbs/memo*.php`
- `bbs/point.php`
- `bbs/profile.php`

위 파일들은 현재 기능을 제공하지 않으며, 구 경로 접근 시 안내 후 홈으로 보내는 호환 레이어만 남겨둔 상태다.

## 이관 대상

회원 기능을 새 `member/` 모듈로 옮긴다는 가정으로 정리한다.

### 1차 이관 대상 엔트리포인트

- `bbs/login.php` -> `member/login.php`
- `bbs/login_check.php` -> `member/login_check.php`
- `bbs/logout.php` -> `member/logout.php`
- `bbs/register.php` -> `member/register.php`
- `bbs/register_form.php` -> `member/register_form.php`
- `bbs/register_form_update.php` -> `member/register_form_update.php`
- `bbs/register_result.php` -> `member/register_result.php`
- `bbs/member_confirm.php` -> `member/member_confirm.php`
- `bbs/member_leave.php` -> `member/member_leave.php`
- `bbs/password_lost.php` -> `member/password_lost.php`
- `bbs/password_lost2.php` -> `member/password_lost2.php`
- `bbs/password_lost_certify.php` -> `member/password_lost_certify.php`
- `bbs/password_reset.php` -> `member/password_reset.php`
- `bbs/password_reset_update.php` -> `member/password_reset_update.php`
- `bbs/register_email.php` -> `member/register_email.php`
- `bbs/register_email_update.php` -> `member/register_email_update.php`
- `bbs/email_certify.php` -> `member/email_certify.php`
- `bbs/email_stop.php` -> `member/email_stop.php`
- `bbs/member_cert_refresh.php` -> `member/member_cert_refresh.php`
- `bbs/member_cert_refresh_update.php` -> `member/member_cert_refresh_update.php`

### 1차 이관 대상 AJAX

- `bbs/ajax.mb_email.php` -> `member/ajax.mb_email.php`
- `bbs/ajax.mb_hp.php` -> `member/ajax.mb_hp.php`
- `bbs/ajax.mb_id.php` -> `member/ajax.mb_id.php`
- `bbs/ajax.mb_nick.php` -> `member/ajax.mb_nick.php`
- `bbs/ajax.mb_recommend.php` -> `member/ajax.mb_recommend.php`

### 1차 이관 대상 공통 템플릿

- `bbs/_common.php`
- `bbs/_head.php`
- `bbs/_head.sub.php`
- `bbs/_tail.php`
- `bbs/_tail.sub.php`

이 파일들은 바로 옮기기보다 `member/`에서 사용할 최소 레이어만 분리하는 편이 안전하다.

### 2차 이관 대상 후보

- 없음

포인트, 프로필, 쪽지는 이관하지 않고 종료하는 방향으로 확정했다.

## 권장 작업 순서

1. `member/` 디렉터리를 만들고 회원 엔트리포인트를 복제 이관한다.
2. `common.php`에서 `shop.config.php` 로드와 쇼핑 결합을 제거한다.
3. `bbs/login_check.php` 안의 쇼핑 장바구니 후처리를 삭제하거나 분리한다.
4. 기존 `bbs` 회원 URL은 `member`로 리다이렉트한다.
5. `shop/`과 쇼핑 라이브러리를 삭제한다.
6. 게시판 / QA / 설문 / FAQ / 검색 / 스크랩을 삭제한다.
7. 마지막에 `bbs` 디렉터리와 `G5_BBS_*` 상수 의존을 정리한다.

## 결정 메모

현재 기준의 기본 방침은 아래와 같다.

- 유지: 계정, 인증, 회원관리
- 제거: 게시판, 쇼핑몰, 포인트, 쪽지, 공개 프로필, FAQ, poll
- 보류: 방문통계, 메일 발송 이력, `bbs` 경로 완전 제거, 설치 스키마의 잔여 게시판/쇼핑 테이블 정리

현재 범위 기준으로는 계정 시스템에 직접 필요한 기능만 남기고, 부가 회원 기능은 종료하는 방향으로 정리한다.
