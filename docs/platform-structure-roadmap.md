# Platform Structure Roadmap

## 목표

현재 프로젝트를 회원 전용 리팩토링 결과물에서, 커뮤니티, 쇼핑몰, 예약 같은 추가 비즈니스를 병렬로 붙일 수 있는 절차형 모듈 플랫폼으로 확장한다.

장기 기준 구조와 운영 규칙은 `docs/architecture/procedural-platform-reference.md`에 별도로 보관한다. 이 문서는 단계별 실행 순서와 현재 착수 범위를 관리하는 작업 로드맵이다.
신규 도메인 스타터 템플릿은 `docs/architecture/procedural-domain-starter.md`에 별도로 보관한다.

핵심 원칙은 다음과 같다.

- 절차형 PHP 유지
- 전역 부트스트랩과 도메인 로직 분리
- 도메인별로 같은 파일 경계 반복
- 기존 URL과 진입점은 단계적으로 유지
- 리라이트보다 점진 이행 우선

## 목표 구조

```text
lib/
├─ bootstrap/
│  ├─ core.lib.php
│  ├─ runtime.lib.php
│  ├─ auth.lib.php
│  └─ session.lib.php
├─ support/
│  ├─ base.util.lib.php
│  ├─ member.lib.php
│  ├─ sql.lib.php
│  ├─ string.lib.php
│  ├─ html.lib.php
│  ├─ url.lib.php
│  ├─ security.lib.php
│  ├─ file.lib.php
│  ├─ mail.lib.php
│  └─ paging.lib.php
├─ domain/
│  ├─ member/
│  │  ├─ request.lib.php
│  │  ├─ validation.lib.php
│  │  ├─ persist.lib.php
│  │  ├─ flow.lib.php
│  │  ├─ render.lib.php
│  │  └─ page.lib.php
│  ├─ admin/
│  │  ├─ helper.lib.php
│  │  ├─ security.lib.php
│  │  └─ bootstrap.lib.php
│  ├─ community/
│  ├─ shop/
│  └─ booking/
└─ legacy/
```

## 단계

### 1단계. 구조 뼈대 도입

- `lib/bootstrap/` 도입
- `lib/domain/member/` 도입
- `lib/domain/admin/` 도입
- 기존 파일은 얇은 래퍼로 유지
- `common.php`, `adm/_common.php`는 새 구조를 기준으로 로드

완료 기준:

- 기존 동작 유지
- 전체 PHP lint 통과
- 기존 include 경로 호환 유지

### 2단계. support 레이어 정렬

- `common.base.util.lib.php`, `common.url.lib.php`, `common.member.lib.php`, `common.security.lib.php`를 `lib/support/`로 재배치
- `common.sql.lib.php`, `common.string.lib.php`, `common.html.lib.php`도 `support` 기준으로 설명과 로드 경로 정리

완료 기준:

- 공통 함수가 bootstrap과 domain 양쪽에 섞이지 않음
- utility 잡탕 파일이 로더 역할만 수행

### 3단계. member 도메인 표준화

- `member/*.php` 컨트롤러 책임 재확인
- `request -> validation -> persist -> flow -> render/page` 순서 문서화
- 새 기능은 반드시 이 경계 안에만 추가
- 기준 문서는 `docs/architecture/member-controller-pattern.md`에 유지

완료 기준:

- member 도메인이 이후 도메인의 템플릿 역할 수행

### 4단계. admin 도메인 표준화

- 관리자 공통 권한/토큰/메뉴/부트스트랩 책임을 `lib/domain/admin/`에 집중
- `adm/*.php`는 화면 진입점만 담당
- 기준 문서는 `docs/architecture/admin-controller-pattern.md`에 유지

완료 기준:

- 관리자 공통 규칙이 화면 파일에 다시 퍼지지 않음

### 5단계. community 도메인 도입

- 게시판, 댓글, moderation, article rendering을 도메인 단위로 분리
- 기존 기능 추가 시 member 도메인 패턴을 그대로 반복

### 6단계. shop 도메인 도입

- 카탈로그, 장바구니, 주문, 결제, 가격 계산, 배송/처리 분리
- 결제는 support와 domain 경계 분리 유지

### 7단계. booking 도메인 도입

- 예약 요청, 가용성, 재고/슬롯, 알림 분리
- 시간/재고 계산은 화면 파일에서 직접 처리하지 않음

## 운영 규칙

- 화면 파일에서 직접 SQL 작성 금지
- 새 비즈니스 규칙을 `common.php`나 `adm/admin.lib.php` 류 파일에 추가 금지
- 신규 도메인은 `request`, `validation`, `persist` 최소 3분할 필수
- 기존 경로 호환은 유지하되, 새 개발은 목표 구조 기준으로만 추가

## 이번 착수 범위

이번 작업에서는 아래까지 실제 반영한다.

- `lib/bootstrap/` 도입
- `lib/support/` 1차 도입
- `lib/domain/member/` 도입
- `lib/domain/admin/` 도입
- 기존 경로 래퍼 유지
- `common.php`와 `adm/admin.lib.php` 로더 정렬
- `common.util.lib.php`를 support 로더로 전환
