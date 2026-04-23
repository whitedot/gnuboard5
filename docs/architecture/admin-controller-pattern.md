# Admin Controller Pattern

## 목적

이 문서는 `adm/*.php` 화면 컨트롤러를 어떤 책임 경계로 유지할지 정의한다.

`admin` 도메인은 관리자 공통 부트스트랩, 권한 검사, 폼 화면 준비, 저장 후처리를 일관된 방식으로 유지하기 위한 기준 문서다.

## 기본 흐름

```text
controller
-> domain/admin helper or security
-> domain/member or support
-> admin.head.php / admin.tail.php
```

관리자 컨트롤러도 아래 3가지로 나눠서 본다.

- 화면형 controller: 목록/폼/대시보드 렌더
- 완료형 controller: 저장/삭제/일괄처리 후 redirect
- AJAX controller: 토큰, 짧은 검증, JSON 응답
- stream controller: SSE/대용량 다운로드처럼 스트리밍 응답을 여는 작업

## 역할 규칙

### controller

- `./_common.php` 포함
- 필요한 최소 권한 체크만 선언
- 화면 준비 상태 계산이나 대량 검증은 헬퍼 함수 호출로 위임
- 저장/삭제 후에는 redirect로 종료
- 완료형 controller는 가능하면 `admin_complete_*()` 호출 1회로 끝낸다
- AJAX controller는 가능하면 `admin_complete_*()` 또는 `admin_process_*()` 호출 1회로 끝낸다
- stream controller는 가능하면 스트림 시작 준비까지 `admin_complete_*()` 호출 1회로 끝낸다
- controller 파일 안에 재사용 가능한 helper/function 을 새로 정의하지 않는다

### domain/admin/helper.lib.php

- 관리자 화면용 request 정규화
- 탭, 화면 제목, 폼 상태, 리스트 화면 메타데이터 조합
- 관리자 회원 관리 화면처럼 뷰 준비가 많은 흐름 처리
- 현재는 `ui.lib.php`, `member.lib.php`, `config.lib.php`, `export.lib.php`를 로드하는 진입 로더 역할만 수행
  `member.lib.php`도 다시 `member-form.lib.php`, `member-list.lib.php`를 로드하는 세부 로더로 분리됨
- 화면형 controller는 가능하면 여기서 `title`, 화면 메타데이터, 폼 상태를 한 번에 받아 렌더한다
- 가능하면 `page_view`와 실제 폼/리스트 데이터는 구분한다
- `member_list.php`, `config_form.php`, `index.php`는 `admin_build_*_page_view()` 이름을 우선 사용한다
- view helper 는 가능하면 HTML 문자열보다 화면용 데이터 구조를 우선 반환한다

### domain/admin/security.lib.php

- 권한 검사
- 관리자 토큰 검사
- referer, XSS, CSRF 같은 공통 보안 처리
- 현재는 `auth.lib.php`, `token.lib.php`, `request-security.lib.php`를 로드하는 진입 로더 역할만 수행

### domain/admin/bootstrap.lib.php

- 관리자 로그인 상태 확인
- 관리자 메뉴/권한 로드
- 관리자 공통 부트스트랩 조합
- 현재는 `access-bootstrap.lib.php`, `menu-bootstrap.lib.php`, `request-bootstrap.lib.php`를 로드하고 실행 조합만 담당

### domain/member and support

- 회원 저장/수정/삭제 자체는 `domain/member` 또는 `support/member`를 재사용
- `admin`은 관리자 관점의 화면 준비와 권한 규칙만 추가한다

## 현재 표준 대상

- `member_form.php`
- `member_form_update.php`
- `member_delete.php`
- `member_list_update.php`
- `member_list.php`
- `config_form.php`
- `config_form_update.php`
- `member_list_file_delete.php`
- `member_list_exel.php`
- `member_list_exel_export.php`
- `member_list_exel.lib.php`
- `admin.head.php`
- `ajax.token.php`
- `index.php`
- `admin.tail.php`
- `_common.php`
- `head.sub.admin.php`
- `admin.bootstrap.lib.php`
- `admin.menu100.php`
- `admin.menu200.php`
- `admin.js`

### 현재 분류 예시

- 화면형: `member_list.php`, `member_form.php`, `config_form.php`, `index.php`
- 완료형: `member_list_update.php`, `config_form_update.php`, `member_delete.php`, `member_list_file_delete.php`
- AJAX형: `ajax.token.php`
- stream형: `member_list_exel_export.php`

## 금지 사항

- 관리자 화면 파일에서 직접 긴 권한 분기 누적
- 관리자 화면 파일에서 직접 대량 SQL 조립
- 토큰 검사와 권한 검사를 화면마다 제각각 재구현
- `adm/admin.lib.php`에 새 비즈니스 규칙 추가
- `lib/domain/admin/*` 에서 controller 파일 내부 함수에 의존

## 다음 정리 우선순위

1. `member_list_exel_export.php` 의 재사용 로직을 `lib/domain/admin/` 으로 이동해 stream controller 를 얇게 만든다.
2. `admin.head.php` 메뉴/셸 계산 로직을 `lib/domain/admin/` 으로 더 이동시켜 공통 규칙이 화면 파일에 다시 쌓이지 않게 한다.
3. `member_list.php`, `member_form.php`, `config_form.php`, `member_list_exel.php`, `index.php` 의 화면 계약을 더 명시적으로 정리하고 `extract()` 의존을 줄인다.
