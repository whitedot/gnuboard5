# Current Project Status

기준일: 2026-04-26

## 현재 상태

- `main` 은 `origin/main` 최신 커밋을 기준으로 작업 중이다.
- 관리자 회원/export 리팩토링은 entry shell, domain 분리, naming 정리까지 상당 부분 완료됐다.
- export 런타임 상수는 `ADMIN_MEMBER_EXPORT_*` 기준으로 정리됐다.
- export domain 파일 책임은 `docs/architecture/admin-export-pattern.md` 에 문서화됐다.
- refactor check npm scripts 는 Node.js 기반으로 실행된다.
- 기존 PowerShell check 파일은 Node.js 체크를 호출하는 호환 shim으로 유지한다.
- 자동 브라우저 검증 코드는 사용하지 않는다.

## 현재 검증 상태

- `npm run check:refactor` 통과
- `git diff --check` 통과
- 기본 PATH 에는 `php` 실행 파일이 없지만, 로컬 `.tools/php/php` 를 PATH 에 추가하면 PHP lint 포함 `npm run check:refactor` 가 통과한다.
- CI 또는 PHP 설치 환경에서도 같은 npm 명령으로 PHP lint 까지 수행된다.

## 현재 변경 묶음

- export 런타임 naming 정리
- export 로그 prefix 정리
- admin export pattern 문서 추가
- refactor check Node.js 전환
- 자동 브라우저 검증 관련 코드, 문서, npm script 제거
- 관련 문서의 다음 작업 방향을 수동 운영 검증과 정적 구조 가드 기준으로 정리
- `admin.head.php` 의 프로필/링크/sidebar/nav 표시 상태 view-model 의존 정리 1차 완료
- `admin.head.php` 의 사이드바/프로필/테마/nav 동작을 `adm/admin.js` 의 `AdminShell` 로 이동
- `config_form.php` 의 submit/captcha/cert 탭 동작을 `adm/admin.js` 의 `AdminConfigForm` 으로 이동
- `config_form_parts/*` 의 직접 `$config`/`$config_form_view` 의존 제거 및 legacy script partial 삭제
- `index.php` 의 dashboard 추가 콘텐츠 hook 출력을 page view 계약으로 이동
- `member_list_exel_parts/filter.php` 의 필터 표시 상태를 `filter_view` 계약으로 이동
- `member_form.php` 의 hidden fields/list URL/token/event 인자를 page view 계약으로 이동
- `member_list_parts/*` 의 검색어/hidden fields/paging 표시 상태를 page view 계약으로 이동
- `admin.tail.php` 의 popup/scroll top inline 동작을 `adm/admin.js` 로 이동
- `admin.tail.php`/`head.sub.admin.php` 의 일부 서버 상태 출력을 view 계약으로 이동
- admin entry 의 직접 `$_GET`/`$_POST`/`$_SERVER` 접근을 runtime request context helper 로 이동

## 다음 작업 순서

### 1. 현재 변경 안정화

- 변경 파일 목록을 검토하고 의도하지 않은 삭제가 없는지 확인한다.
- PHP 실행 파일이 PATH 에 잡힌 환경에서 `npm run check:refactor` 를 한 번 더 실행한다.
- 문제가 없으면 현재 변경 묶음을 별도 커밋 단위로 고정한다.

### 2. 운영 export 호환성 확인

- 운영 Excel 에서 생성 XLSX 파일이 정상적으로 열리는지 확인한다.
- 실제 운영 데이터 기준으로 단건, 1만 건 초과 분할, ZIP 묶음, 실패 메시지, 로그 기록을 수동으로 확인한다.
- 확인 결과에 따라 `lib/PHPExcel` 제거 여부를 판단한다.

### 3. admin head 구조 정리

- `admin.head.php` 의 프로필/링크/sidebar/nav 표시 상태 계산은 `admin_build_head_view()` 로 이동했다.
- 사이드바/프로필/테마/nav 동작은 `adm/admin.js` 의 `AdminShell` 로 이동했다.
- 남은 메뉴/셸 출력 계산 로직을 `lib/domain/admin/` 으로 더 이동한다.
- 화면 파일에는 렌더링에 필요한 최소 변수만 남긴다.
- 변경 시 `scripts/check-admin-refactor.js` 의 구조 가드도 함께 갱신한다.

### 4. 화면 계약 명시화

- `config_form.php` 의 submit/captcha/cert 탭 동작은 `AdminConfigForm` 으로 이동했다.
- `config_form_parts/*` 는 section view-model 을 읽도록 정리했다.
- `index.php` 의 hook 출력 계약은 dashboard view 에 포함했다.
- `member_list_exel.php` 의 필터 partial 은 `filter_view` 를 읽도록 정리했다.
- `member_form.php` 의 폼 shell 값은 page view 에서 공급한다.
- `member_list.php` 의 partial 요청 상태 의존을 page view 로 이동했다.
- `admin.tail.php` 의 inline UI 동작은 `adm/admin.js` 에서 바인딩한다.
- `admin.tail.php`/`head.sub.admin.php` 는 서버 상태를 view helper 에서 받아 출력한다.
- admin entry 는 `g5_get_runtime_get_input()`/`g5_get_runtime_post_input()`/`g5_get_runtime_server_input()` 기반으로 request 를 읽는다.
- partial 에서 숨은 전역이나 page-local 상태를 직접 읽지 않도록 유지한다.

## 보류

- 신규 도메인 확장
- `lib/PHPExcel` 즉시 삭제
- 브라우저 자동 검증 도입
