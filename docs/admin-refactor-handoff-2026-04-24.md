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

- `scripts/check-admin-refactor.js`
  - `scripts/check-admin-refactor.ps1` 는 Node.js 체크를 호출하는 호환 shim
  - member list/form/export entry shell 회귀 가드 추가
  - `adm/member_list_exel.lib.php` 재생성 금지
  - export legacy naming 재유입 금지

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

### 완료. export 런타임 naming 마무리

- export 런타임 상수는 `ADMIN_MEMBER_EXPORT_*` 형태로 정리했다.
- `scripts/check-admin-refactor.js` 에 legacy export 상수명 재유입 방지 가드를 추가했다.

### P1. export file/runtime API 일관성 점검

- 지금은 주요 함수명이 정리됐지만 파일명은 `export-file.lib.php`, `export-stream.lib.php`, `export-query.lib.php` 로 유지된다
- `docs/architecture/admin-export-pattern.md` 에 파일 책임과 naming 규칙을 문서화했다
- 로그 prefix는 `Admin Member Export` 기준으로 정리했다
- `member_export_*` legacy 함수명과 legacy 상수명은 `scripts/check-admin-refactor.js` 에서 재유입을 막는다

### 완료. member/admin 공통 패턴 문서화 보강

- `docs/architecture/admin-controller-pattern.md` 의 완료된 export stream 항목을 현재 구조에 맞게 갱신했다
- `docs/architecture/admin-export-pattern.md` 를 추가했다

## 4. 검증 명령

리팩토링 이어서 작업할 때 최소한 아래 두 개는 유지:

```bash
npm run check:admin-refactor
npm run check:refactor
```

## 5. 주의사항

- `adm/admin.js` 는 이번 턴에서 역할이 커졌다. 새 페이지 동작을 추가할 때는 또다시 inline script로 되돌리지 말고 여기 공용 모듈 패턴을 따른다.
- `scripts/check-admin-refactor.js` 는 현재 구조를 강하게 고정하고 있다. 새 구조를 넣을 때 체크가 먼저 깨지는 게 정상일 수 있으니, 코드와 체크를 같이 바꿔야 한다.
- `check:*` refactor npm scripts 는 Node.js 기반으로 실행되며, PHP가 설치된 환경에서는 PHP lint까지 수행한다. CI에서는 PHP가 없으면 실패한다.
- 기존 PowerShell check 파일들은 Node.js 체크를 호출하는 호환 shim으로 유지한다.
- npm의 `msvs_version` 경고는 현재 검증 실패 원인이 아니다.
