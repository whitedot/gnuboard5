# Upstream 동기화 이력

원본 리포지토리(gnuboard/gnuboard5)와의 동기화 이력을 기록합니다.

## 현재 상태

| 항목 | 값 |
|------|-----|
| Fork 분기점 | v5.6.23 (`e26fb62f5`) - 2025-09-22 |
| 원본 최신 버전 | v5.7.5 |
| 미적용 커밋 수 | 14개 |
| Upstream Remote | `https://github.com/gnuboard/gnuboard5.git` |

---

## 함수 매핑 테이블

원본 `lib/common.lib.php`와 `lib/shop.lib.php`가 Fork에서 모듈화되었습니다.
원본 변경사항 적용 시 아래 매핑을 참조하세요.

### common.lib.php → Fork 모듈 매핑

| 원본 함수/클래스 | Fork 모듈 | 설명 |
|-----------------|-----------|------|
| `str_encrypt` 클래스 | `lib/common.crypto.lib.php` | 암호화/복호화 |
| `create_hash()` | `lib/common.crypto.lib.php` | 해시 생성 |
| `get_encrypt_string()` | `lib/common.crypto.lib.php` | 문자열 암호화 |
| `get_decrypt_string()` | `lib/common.crypto.lib.php` | 문자열 복호화 |
| `sql_query()` | `lib/common.sql.lib.php` | SQL 실행 |
| `sql_fetch()` | `lib/common.sql.lib.php` | 단일 레코드 조회 |
| `sql_fetch_array()` | `lib/common.sql.lib.php` | 다중 레코드 조회 |
| `get_member()` | `lib/common.data.lib.php` | 회원 정보 조회 |
| `get_board()` | `lib/common.data.lib.php` | 게시판 정보 조회 |
| `conv_content()` | `lib/common.string.lib.php` | 내용 변환 |
| `cut_str()` | `lib/common.string.lib.php` | 문자열 자르기 |
| `get_text()` | `lib/common.string.lib.php` | 텍스트 변환 |
| `upload_file()` | `lib/common.file.lib.php` | 파일 업로드 |
| `delete_file()` | `lib/common.file.lib.php` | 파일 삭제 |
| `get_paging()` | `lib/common.html.lib.php` | 페이징 HTML |
| `insert_point()` | `lib/common.point.lib.php` | 포인트 지급 |
| `delete_point()` | `lib/common.point.lib.php` | 포인트 차감 |
| `set_session()` | `lib/common.session.lib.php` | 세션 설정 |
| `is_mobile()` | `lib/common.mobile.lib.php` | 모바일 감지 |

### shop.lib.php → Fork 모듈 매핑

| 원본 함수 | Fork 모듈 | 설명 |
|-----------|-----------|------|
| `get_item()` | `lib/shop.item.lib.php` | 상품 조회 |
| `get_item_options()` | `lib/shop.item.lib.php` | 상품 옵션 |
| `get_item_image()` | `lib/shop.image.lib.php` | 상품 이미지 |
| `shop_get_price()` | `lib/shop.price.lib.php` | 가격 계산 |
| `display_price()` | `lib/shop.price.lib.php` | 가격 표시 |
| `shop_is_taxsave()` | `lib/shop.price.lib.php` | 면세 여부 (오타 수정 필요) |
| `shop_point_proc()` | `lib/shop.point.lib.php` | 포인트 처리 |
| `get_cart()` | `lib/shop.order.lib.php` | 장바구니 |
| `get_order()` | `lib/shop.order.lib.php` | 주문 조회 |
| `get_delivery_cost()` | `lib/shop.delivery.lib.php` | 배송비 계산 |
| `get_coupon()` | `lib/shop.coupon.lib.php` | 쿠폰 조회 |
| `shop_item_url()` | `lib/shop.uri.lib.php` | 상품 URL |

---

## 미적용 원본 커밋 목록 (v5.6.23 → v5.7.5)

### 보안 패치 (우선 적용 필요)

| 커밋 | 설명 | 영향 파일 | Fork 적용 위치 |
|------|------|----------|---------------|
| `7c490448e` | 암호화 키 취약점 + sha256 강화 | `lib/common.lib.php` | `lib/common.crypto.lib.php` |
| `d775d2255` | [KVE-2026-0029] Stored XSS | `bbs/write.php` | `bbs/write.php` |
| `f2ab751e5` | [KVE-2025-0828] 영카트 취약점 | `shop/naverpay/*` | 동일 |

### 버그 수정

| 커밋 | 설명 | 영향 파일 | 비고 |
|------|------|----------|------|
| `0ddcda4b0` | 토스 포인트 결제 오류 | `mobile/shop/*` | 모바일 파일 삭제됨 - 별도 확인 필요 |
| `bf27af392` | shop_is_taxsave 오타 | `lib/shop.lib.php` | `lib/shop.price.lib.php`에 적용 |
| `607e15424` | 토스 비회원 결제 오류 | `shop/toss/*`, `mobile/shop/*` | 모바일 파일 삭제됨 |
| `b29e16a19` | 게시판 자동등록방지 오류 | `adm/board_form_update.php` | 동일 |
| `0e06b4f9c` | 현금영수증 버튼 오류 | `shop/orderinquiryview.php` | 동일 |

### 개선/기타

| 커밋 | 설명 | 영향 파일 |
|------|------|----------|
| `137d78ff7` | 관리자 분류출력 개선 | `adm/shop_admin/*.php` |
| `b94771de9` | 썸네일 코드 위치변경 | `lib/thumbnail.lib.php` |
| `3432497ef` | 삼성브라우저 대응 | 확인 필요 |
| `a7541256c` | 관리자 회원목록 스타일 | `adm/member_list.php`, `adm/css/admin.css` |

---

## 동기화 이력

### 2026-02-12 - 초기 설정

- Upstream remote 추가: `https://github.com/gnuboard/gnuboard5.git`
- 분기점 확인: v5.6.23 (`e26fb62f5`)
- 함수 매핑 문서 작성
- 14개 미적용 커밋 분석 완료

**적용 예정:**
- [ ] `7c490448e` - 암호화 키 취약점 (lib/common.crypto.lib.php)
- [ ] `d775d2255` - XSS 취약점 (bbs/write.php)
- [ ] `f2ab751e5` - 영카트 취약점 (shop/naverpay/*)
- [ ] `bf27af392` - shop_is_taxsave 오타 (lib/shop.price.lib.php)
- [ ] `b29e16a19` - 자동등록방지 오류 (adm/board_form_update.php)

---

## Cherry-pick 가이드

### 1. 동기화 브랜치 생성
```bash
git checkout -b upstream-sync-YYYYMMDD main
git fetch upstream
```

### 2. 보안 패치 Cherry-pick
```bash
# 암호화 취약점 (수동 매핑 필요)
git cherry-pick 7c490448e
# 충돌 발생 시:
# - lib/common.lib.php 변경 → lib/common.crypto.lib.php에 적용
git add lib/common.crypto.lib.php
git cherry-pick --continue

# XSS 취약점 (직접 적용 가능)
git cherry-pick d775d2255
```

### 3. 검증
```bash
# 구문 오류 확인
php -l lib/common.crypto.lib.php
php -l bbs/write.php

# 기능 테스트
# - 회원가입/로그인
# - 게시글 작성
# - 결제 프로세스
```

### 4. main 병합
```bash
git checkout main
git merge upstream-sync-YYYYMMDD
```

---

## 주의사항

1. **모바일 파일**: Fork에서 `mobile/` 디렉토리가 삭제됨
   - 모바일 관련 커밋은 해당 로직을 desktop 파일에서 확인

2. **모듈화된 파일**: `lib/common.lib.php`, `lib/shop.lib.php`
   - 원본 변경 시 해당 모듈 파일에 수동 적용 필요
   - 위 매핑 테이블 참조

3. **관리자 분할 파일**: `adm/*_form_parts/`
   - 원본 `adm/*_form.php` 변경 시 해당 parts 파일 확인

---

## 자동 알림

GitHub Actions 워크플로우 설정: `.github/workflows/upstream-check.yml`

매주 월요일 원본 업데이트 확인 후 Issue 생성
