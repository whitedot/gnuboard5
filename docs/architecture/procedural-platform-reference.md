# Procedural Platform Reference

## 문서 목적

이 문서는 현재 프로젝트를 커뮤니티, 쇼핑몰, 예약 같은 추가 비즈니스까지 수용하는 절차형 모듈 플랫폼으로 확장할 때 참고하는 기준 문서다.

- `docs/current-work-plan-2026-04-26.md`는 현재 단계의 실행 로드맵이다.
- 이 문서는 장기적으로 유지할 목표 구조와 개발 규칙을 보관하는 기준 문서다.
- `docs/architecture/procedural-domain-starter.md`는 신규 도메인 착수용 템플릿 문서다.

## 목표 방향

- 절차형 PHP 유지
- 전역 부트스트랩과 도메인 로직 분리
- 도메인별 같은 파일 경계 반복
- 기존 URL과 진입점은 단계적으로 유지
- 리라이트보다 점진 이행 우선

## 기준 구조

```text
lib/
├─ bootstrap/
│  ├─ core.lib.php
│  ├─ runtime.lib.php
│  ├─ auth.lib.php
│  └─ session.lib.php
├─ support/
│  ├─ base.util.lib.php
│  ├─ url.lib.php
│  ├─ member.lib.php
│  ├─ security.lib.php
│  ├─ sql.lib.php
│  ├─ string.lib.php
│  ├─ html.lib.php
│  ├─ file.lib.php
│  ├─ mail.lib.php
│  ├─ paging.lib.php
│  ├─ form.lib.php
│  └─ html-process.lib.php
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

## 레이어 규칙

### bootstrap

- 전역 상수, 런타임, 인증, 세션 같은 초기화만 담당한다.
- 비즈니스 규칙을 직접 두지 않는다.
- `common.php`는 이 레이어를 로드하는 엔트리 역할만 맡는다.

### support

- 도메인 비의존 공통 함수만 둔다.
- URL, 보안, 문자열, SQL, 파일, 메일처럼 여러 도메인에서 재사용되는 기능만 허용한다.
- 새 기능은 `common.util.lib.php` 같은 잡탕 파일에 직접 추가하지 않는다.

### domain

- 실제 비즈니스 규칙의 중심이다.
- `member`, `community`, `shop`, `booking`, `admin`은 각자 독립된 도메인처럼 다룬다.
- 최소 단위는 `request`, `validation`, `persist`다.
- 복잡한 도메인은 `flow`, `render`, `pricing`, `availability`, `moderation` 같은 세부 파일을 추가한다.

### controller and view

- `member/*.php`, `adm/*.php`, 이후 `community/*.php`, `shop/*.php`, `booking/*.php`는 얇은 컨트롤러만 둔다.
- 화면 파일은 입력 수집, 도메인 호출, 출력 결정만 담당한다.
- SQL과 핵심 비즈니스 규칙은 화면 파일에 두지 않는다.

## 신규 도메인 추가 규칙

### community

- 게시판, 댓글, moderation, article rendering을 도메인 파일로 분리한다.
- 글쓰기, 수정, 삭제, 댓글 처리에서 직접 SQL과 권한 로직을 섞지 않는다.

### shop

- 카탈로그, 장바구니, 주문, 결제, 가격 계산, 배송 처리를 분리한다.
- 결제 연동은 support와 domain 경계를 분명히 한다.

### booking

- 예약 요청, 가용성 계산, 슬롯/재고, 알림을 분리한다.
- 시간 계산과 재고 계산을 화면 파일에서 직접 처리하지 않는다.

## 운영 규칙

- 화면 파일에서 직접 SQL 작성 금지
- 새 비즈니스 규칙을 `common.php`, `adm/admin.lib.php`, `common.util.lib.php`에 추가 금지
- 기존 경로는 래퍼로 유지하되, 새 개발은 목표 구조 기준으로만 추가
- 큰 파일이 생기면 먼저 support 분리인지 domain 분리인지부터 판단

## 현재 기준 상태

- `bootstrap` 레이어 도입 시작
- `domain/member`, `domain/admin` 도입 시작
- 기존 경로는 호환 래퍼로 유지
- support 레이어는 순차적으로 실제 경로로 이동 중
- `sql`, `string`, `file`, `mail`은 support 경유 경로를 열었고, `html`, `paging`, `form helper`, `html process`는 실제 함수 단위를 support로 분리함
- `member` 도메인은 화면형, 완료형, AJAX형 controller 패턴의 기준 레퍼런스로 정리 중
