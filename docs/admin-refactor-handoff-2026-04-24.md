# Admin Refactor Handoff

기준일: 2026-04-24

## 1. 이번 턴에서 끝낸 범위

### 관리자 회원 화면

- `adm/member_list.php`
  - inline script, `onclick`, `onsubmit` 제거
  - `adm/member_list_parts/*` partial 분리
  - 동작은 `adm/admin.js` 의 `AdminMemberList` 로 이동
- `adm/member_form.php`
  - inline submit/script partial 제거
  - `adm/member_form_parts/*` 를 section view-model 기반으로 정리
  - 동작은 `adm/admin.js` 의 `AdminMemberForm` 로 이동
- `adm/member_form_update.php`
  - entry shell화
  - request 읽기, 저장, redirect 조합을 `lib/domain/admin/member-form.lib.php` 로 이동
- `adm/member_delete.php`
  - delete action request + redirect 조합을 domain으로 이동

### 관리자 회원 export

- `adm/member_list_exel.php`
  - shell + `adm/member_list_exel_parts/*` 구조로 정리
- `adm/member_list_exel_export.php`
  - stream page request + stream runner 조합만 남긴 shell로 정리
- `adm/member_list_exel.lib.php`
  - 완전 제거
- `lib/domain/admin/export-config.lib.php`
  - export 상수, 필터 config, params 정규화, count query, where builder 이동
- `lib/domain/admin/export-*.lib.php`
  - naming을 `admin_` prefix 기준으로 정리
  - shell/view/runtime/config/query/file 책임을 분리
- `lib/domain/admin/xlsx.lib.php`
  - `ZipArchive` 부재 시 순수 PHP ZIP fallback 추가

### 검증/가드

- `scripts/check-admin-refactor.ps1`
  - member list/form/export entry shell 회귀 가드 추가
  - `adm/member_list_exel.lib.php` 재생성 금지
  - export legacy naming 재유입 금지
- `scripts/check-admin-export-smoke.php`
- `scripts/check-admin-export-smoke.ps1`
  - CLI `ZipArchive` 부재 시 .NET ZIP fallback으로 XLSX 구조 검증

## 2. 현재 코드에서 기대하는 구조

### entry page 원칙

- `adm/*.php` 는 가능한 한 아래 3단계만 수행
  - `_common.php` 로 bootstrap
  - page/action request builder 호출
  - domain complete/page-view 함수 호출

### member form 원칙

- partial은 전체 `$member_form_view` 를 직접 읽지 않는다.
- 가능하면 `basic/contact/consent/profile/history` section view만 읽는다.
- 새 필드 추가 시:
  - `lib/domain/admin/member-form.lib.php` 의 section builder에 먼저 반영
  - partial은 section 데이터만 사용

### export 원칙

- export 관련 신규 함수는 `lib/domain/admin/export-*.lib.php` 로만 추가
- export 함수 네이밍은 `admin_` prefix 유지
- page 파일에서:
  - `check_demo()`
  - export runtime 조립
  - legacy helper include
  - XLSX/ZIP 직접 실행
  를 다시 넣지 않는다

## 3. 지금 바로 이어서 할 다음 작업

### P1. export 런타임 naming 마무리

- 아직 남아 있는 상수명은 legacy 형태다
  - `MEMBER_EXPORT_PAGE_SIZE`
  - `MEMBER_EXPORT_MAX_SIZE`
  - `MEMBER_BASE_DIR`
  - `MEMBER_BASE_DATE`
  - `MEMBER_EXPORT_DIR`
  - `MEMBER_LOG_DIR`
- 다음 단계에서는 이것도 `ADMIN_MEMBER_EXPORT_*` 로 바꾸고 호출부를 같이 이동하는 편이 좋다

### P1. export file/runtime API 일관성 점검

- 지금은 주요 함수명이 정리됐지만 파일명은 `export-file.lib.php`, `export-stream.lib.php`, `export-query.lib.php` 로 유지된다
- 기능적으로는 충분하지만, 필요하면 아래를 검토
  - `member_export_*` 문자열이 로그 메시지나 테스트 fixture에 남아 있는지 점검
  - `admin_*` naming과 파일명 구성이 팀 기준에 충분히 읽기 쉬운지 점검

### P2. member/admin 공통 패턴 문서화 보강

- 현재 리팩토링은 코드로는 정리됐지만 문서가 아직 요약형이다
- 다음 문서 보강 후보
  - `docs/architecture/admin-controller-pattern.md`
  - 새 `docs/architecture/admin-export-pattern.md`

### P2. 브라우저 실검증 확대

- 현재 smoke는 구조와 최소 runtime 유효성 보장까지는 됨
- 다음 확대 대상
  - export 진행 팝업 완료 흐름
  - 분할 다운로드 후 ZIP 완료 흐름
  - member list sideview 위치 회귀

## 4. 검증 명령

리팩토링 이어서 작업할 때 최소한 아래 두 개는 유지:

```powershell
npm run check:admin-refactor
npm run check:refactor
```

추가로 export 쪽만 볼 때:

```powershell
powershell -ExecutionPolicy Bypass -File ./scripts/check-admin-export-smoke.ps1
```

## 5. 주의사항

- `adm/admin.js` 는 이번 턴에서 역할이 커졌다. 새 페이지 동작을 추가할 때는 또다시 inline script로 되돌리지 말고 여기 공용 모듈 패턴을 따른다.
- `scripts/check-admin-refactor.ps1` 는 현재 구조를 강하게 고정하고 있다. 새 구조를 넣을 때 체크가 먼저 깨지는 게 정상일 수 있으니, 코드와 체크를 같이 바꿔야 한다.
- npm의 `msvs_version` 경고는 현재 검증 실패 원인이 아니다.
