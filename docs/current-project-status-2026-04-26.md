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

- `admin.head.php` 의 메뉴/셸 계산 로직을 `lib/domain/admin/` 으로 더 이동한다.
- 화면 파일에는 렌더링에 필요한 최소 변수만 남긴다.
- 변경 시 `scripts/check-admin-refactor.js` 의 구조 가드도 함께 갱신한다.

### 4. 화면 계약 명시화

- `member_list.php`, `member_form.php`, `config_form.php`, `member_list_exel.php`, `index.php` 의 page view 계약을 더 명시적으로 정리한다.
- partial 에서 숨은 전역이나 page-local 상태를 직접 읽지 않도록 유지한다.

## 보류

- 신규 도메인 확장
- `lib/PHPExcel` 즉시 삭제
- 브라우저 자동 검증 도입
