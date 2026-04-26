# Admin Include Map

기준일: 2026-04-26

## 목적

이 문서는 관리자 영역의 include 흐름을 정리한 지도다.

관리자 코드는 기존 G5 방식의 `require_once`를 유지한다. 대신 순수 함수 로더와 실행형 bootstrap을 구분해서, 레거시 개발자가 어디에 새 코드를 넣고 어떤 파일을 추적해야 하는지 빠르게 판단할 수 있게 한다.

## 런타임 진입 흐름

```text
adm/{page}.php
└─ adm/_common.php
   ├─ common.php
   └─ adm/admin.lib.php
      ├─ lib/domain/admin/helper.lib.php      # 순수 helper 로더
      ├─ lib/domain/admin/security.lib.php    # 순수 security 로더
      └─ lib/domain/admin/bootstrap.lib.php   # 실행형 bootstrap
```

`helper.lib.php`와 `security.lib.php`는 함수를 로드하는 파일이다. 권한 확인, 메뉴 로딩, request 검증 같은 실행 흐름은 `bootstrap.lib.php`에서 시작된다.

## 순수 Helper 로더

```text
lib/domain/admin/helper.lib.php
├─ ui.lib.php
├─ view-helper.lib.php
├─ member.lib.php
├─ config.lib.php
├─ export.lib.php
└─ xlsx.lib.php
```

각 aggregate loader는 기존 include 경로를 유지하기 위한 얇은 파일이다. 실제 구현은 아래 세부 파일에 둔다.

## 관리자 화면 도메인

```text
member.lib.php
├─ member-form.lib.php
│  ├─ member-form-request.lib.php
│  ├─ member-form-view.lib.php
│  └─ member-form-update.lib.php
├─ member-list.lib.php
│  ├─ member-list-request.lib.php
│  ├─ member-list-update.lib.php
│  └─ member-list-view.lib.php
└─ dashboard.lib.php

config.lib.php
├─ config-view.lib.php
└─ config-update.lib.php
```

회원 목록과 회원 폼은 요청 파싱, 화면 데이터, 저장/삭제 흐름을 분리한다. 설정 화면도 view와 update를 분리한다.

## Export/XLSX 도메인

```text
export.lib.php
├─ export-query.lib.php
├─ export-config.lib.php
├─ export-file.lib.php
├─ export-stream.lib.php
├─ export-view.lib.php
└─ export-maintenance.lib.php

xlsx.lib.php
├─ archive.lib.php
└─ xlsx-writer.lib.php
```

`xlsx.lib.php`는 XLSX 생성과 ZIP fallback writer를 함께 로드하지만, 실제 구현은 `xlsx-writer.lib.php`와 `archive.lib.php`로 분리한다.

## UI 도메인

```text
ui.lib.php
├─ ui-legacy.lib.php
├─ ui-anchor.lib.php
└─ ui-shell.lib.php
```

- `ui-legacy.lib.php`: 기존 G5 호환 헬퍼, 로그/파일/확장 CSS 관련 함수
- `ui-anchor.lib.php`: 관리자 탭/앵커 메뉴 view와 렌더링
- `ui-shell.lib.php`: 관리자 head/tail/sub head view model과 메뉴 navigation

## Security와 Bootstrap

```text
security.lib.php
├─ auth.lib.php
├─ token.lib.php
└─ request-security.lib.php

bootstrap.lib.php
├─ access-bootstrap.lib.php
├─ menu-bootstrap.lib.php
└─ request-bootstrap.lib.php
```

`security.lib.php`는 토큰, 권한 helper, request security helper를 로드한다.

`bootstrap.lib.php`는 로드만 하는 파일이 아니다. include되는 순간 현재 관리자 요청에 대해 접근 권한 확인, 메뉴 로딩, request 검증, `$auth`, `$amenu`, `$menu`, `$qstr` 준비를 수행한다.

## 추가 규칙

- 새 helper가 필요하면 먼저 책임에 맞는 세부 파일에 추가한다.
- 기존 include 경로가 필요할 때만 aggregate loader에 `require_once`를 추가한다.
- `bootstrap.lib.php`에는 실행 시점에 필요한 준비 코드만 둔다.
- 화면 controller에서 세부 파일을 직접 require하지 않는다. controller는 `_common.php`를 통해 로드된 aggregate loader를 사용한다.
- aggregate loader에는 업무 로직을 넣지 않는다.
