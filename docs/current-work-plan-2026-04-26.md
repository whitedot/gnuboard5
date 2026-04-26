# Current Work Plan

기준일: 2026-04-26

## 목적

이 문서는 흩어져 있던 프로젝트 상태, 작업계획, 로드맵, 기술부채, 핸드오프 문서를 하나로 합친 현재 기준 작업계획이다.

아키텍처 규칙은 `docs/architecture/*` 문서에 남기고, 과거 QA 결과는 `docs/project-evaluation-report-2026-04-22.md`에 보존한다. 새 작업 순서와 보류 판단은 이 문서를 우선 기준으로 삼는다.

## 통합한 문서

- `docs/current-project-status-2026-04-26.md`
- `docs/refactoring-priority-roadmap-2026-04-24.md`
- `docs/platformization-readiness-report-2026-04-23.md`
- `docs/platform-structure-roadmap.md`
- `docs/technical-debt-register-2026-04-24.md`
- `docs/admin-refactor-handoff-2026-04-24.md`
- `docs/member-only-implementation-plan.md`
- `docs/member-only-remaining-work.md`
- `docs/member-only-follow-up-2026-04-23.md`
- `docs/project-evaluation-plan.md`
- `docs/project-evaluation-report-template.md`
- `docs/admin-form-conversion-priority.md`
- `docs/legacy-comment-cleanup-plan-2026-04-22.md`

## 현재 결론

- `member-only` 범위 정리는 1차 기준선으로 본다.
- 회원, 인증, 관리자 회원 관리 중심의 운영 범위는 현재 코드와 문서가 대체로 일치한다.
- 관리자 회원/export 리팩토링은 entry shell, domain 분리, view 계약, request context 경계까지 상당 부분 완료됐다.
- 다음 사이클은 신규 `community`, `shop`, `booking` 도메인 추가보다 관리자 export 운영 검증과 최소 회귀 가드 유지에 둔다.
- `lib/PHPExcel` 활성 런타임 의존은 제거됐지만, 레거시 트리 삭제는 운영 export 검증 후 판단한다.

## 완료된 범위

### Member-only 정리

- 계정, 인증, 관리자 회원 관리 중심으로 운영 범위를 축소했다.
- 게시판, 쇼핑몰, 주문/결제, 소셜, SMS, 현재 접속자, 사용자 디바이스 전환, 가입 운영 메일 등 범위 밖 기능을 제거 또는 축소했다.
- 회원가입, 로그인/로그아웃, 이메일 인증, 비밀번호 재설정, 회원정보 수정, 회원탈퇴, 관리자 회원 CRUD는 2026-04-22 수동 QA에서 통과했다.
- 삭제/탈퇴 시 회원아이디와 상태 정보만 유지하고 개인정보와 인증 이력은 비식별 처리 또는 삭제하는 정책으로 정리했다.

### Admin 도메인 정리

- `adm/*.php`는 `_common.php`, 권한 확인, request/page builder, domain complete 함수 호출 중심의 얇은 controller로 이동 중이다.
- `adm/admin.head.php`, `adm/admin.tail.php`, `adm/head.sub.admin.php`의 표시 상태와 inline 동작을 view 계약과 `adm/admin.js`로 옮겼다.
- `config_form.php`, `member_list.php`, `member_form.php`, `member_list_exel.php`, `index.php`는 page/section/filter view-model 계약을 사용한다.
- admin entry의 직접 `$_GET`, `$_POST`, `$_REQUEST`, `$_SERVER` 접근은 runtime request context helper 기준으로 정리했다.
- `extract()` 기반 숨은 계약, legacy request-global extract helper, legacy query alias opt-out define 재도입은 refactor check에서 차단한다.

### Admin export 정리

- `adm/member_list_exel.php`는 export 화면 controller로 축소했다.
- `adm/member_list_exel_export.php`는 stream controller로 축소했다.
- export 책임은 `lib/domain/admin/export-config.lib.php`, `export-query.lib.php`, `export-file.lib.php`, `export-stream.lib.php`, `export-view.lib.php`, `export-maintenance.lib.php`로 나눴다.
- XLSX 생성은 `lib/domain/admin/xlsx.lib.php`의 자체 writer를 사용한다.
- `ZipArchive`가 있으면 확장을 사용하고, 없으면 순수 PHP ZIP writer fallback으로 압축 파일 생성을 시도한다.
- export 런타임 상수는 `ADMIN_MEMBER_EXPORT_*` 기준으로 정리했다.
- legacy `member_export_*`, `MEMBER_EXPORT_*`, `MEMBER_BASE_*`, `MEMBER_LOG_DIR`, `PHPExcel` 재유입은 check script에서 차단한다.

### 검증/가드

- `npm run check:refactor`는 Node.js 기반 check script 묶음으로 실행한다.
- PowerShell check 파일은 Node.js check를 호출하는 호환 shim으로 유지한다.
- GitHub Actions는 Windows, PHP 8.3 + zip, Node.js 20 기준으로 `npm run check:refactor`를 실행한다.
- 자동 브라우저 smoke 테스트와 Playwright 설정은 제거했다.
- 현재 회귀 기준은 PHP lint, 금지 패턴, admin/member request/view 계약 정적 가드다.

## 현재 검증 상태

최근 로컬 점검 결과:

- `PATH=".tools/php:$PATH" npm run check:refactor` 통과
- `git diff --check` 통과
- `npm run build` 통과
- 샘플 XLSX 생성 후 `unzip -t` 통과
- fallback ZIP writer 샘플 생성 후 `unzip -t` 통과

주의:

- 기본 PATH에는 `php`가 없을 수 있다. 이 저장소에서는 로컬 점검 시 `.tools/php`를 PATH에 추가하면 PHP lint까지 수행된다.
- `.tools/`는 로컬 실행 도구이므로 저장소 추적 대상에서 제외한다.
- Tailwind 의존성은 현재 lockfile 없이 `^4.1.18`로 열려 있다. CSS 빌드 재현성을 더 강하게 관리하려면 lockfile 추적 또는 버전 고정을 별도 결정한다.

## 남은 P1

### 1. 관리자 회원 export 운영 호환성 확인

정적 체크와 샘플 ZIP 구조 검증은 통과했지만, 실제 운영 판단에는 아래 수동 확인이 필요하다.

- 소량 export 파일이 운영 Excel에서 정상적으로 열리는지 확인
- 1만 건 초과 분할 파일 생성 확인
- ZIP 묶음 다운로드 확인
- 실패 메시지와 사용자 안내 확인
- export 로그 기록 확인
- 운영 데이터 규모에서 시간, 메모리, 다운로드 재시도 UX 확인

완료 기준:

- 운영자가 실제로 사용하는 Excel 또는 스프레드시트 앱에서 파일이 열린다.
- 대용량 분할과 ZIP 묶음에서 치명적인 호환성 문제가 없다.
- 실패 시 사용자 메시지와 로그가 기대대로 남는다.

### 2. `lib/PHPExcel` 삭제 판단

현재 활성 코드의 `PHPExcel` 참조는 제거됐지만 `lib/PHPExcel` 트리는 남아 있다.

삭제 조건:

- export 운영 호환성 확인 완료
- `npm run check:refactor` 통과
- 운영에서 legacy XLS/XLSX reader/writer 의존이 필요하지 않다는 판단 완료

현재 판단:

- 즉시 삭제하지 않는다.
- export 운영 검증 후 별도 커밋으로 제거 여부를 결정한다.

### 3. 빌드 재현성 정책 결정

현재 `package-lock.json`은 `.gitignore` 대상이고, `npm install` 시 Tailwind minor 버전이 달라질 수 있다.

선택지:

- lockfile을 추적해 CI와 로컬 빌드 버전을 고정한다.
- `package.json`의 Tailwind 버전을 exact version으로 고정한다.
- 현재처럼 refactor check만 CI에서 실행하고 CSS 빌드는 수동 검증으로 둔다.

## 남은 P2

### 1. 브라우저 실동작 수동 확인

자동 브라우저 검증은 사용하지 않는다. 대신 다음 항목은 운영 확인 시 수동으로 본다.

- 관리자 메뉴 열림/접힘
- 회원 목록 sideview 위치
- 회원 목록 검색, 페이징, 일괄 처리
- 회원 상세 탭과 저장
- 설정 화면 탭, submit, cert/captcha 표시
- export 진행 팝업, 중단 확인, 다운로드 링크 표시

### 2. admin domain 세부 책임 축소

현재 구조는 운영 가능 수준이지만, 이후 작업에서 파일 책임을 더 좁힐 수 있다.

- `lib/domain/admin/ui.lib.php`의 셸/메뉴 계산 책임 추가 분리 검토
- export file/log/query/runtime 책임이 커지면 더 세부 파일로 분리
- 신규 admin 화면 추가 시 기존 view 계약과 request context helper 사용

### 3. 브랜치 보호와 필수 status check 연결

GitHub Actions check는 존재한다. 운영 저장소 정책에서 필요하면 branch protection과 필수 status check를 연결한다.

## 보류

- 신규 `community`, `shop`, `booking` 도메인 추가
- `lib/PHPExcel` 즉시 삭제
- 자동 브라우저 smoke 테스트 재도입
- `mb_id` 암호화/복호화 방식 도입
- Composer autoload 같은 근본 구조 전환

## 다음 작업 순서

1. 현재 변경 안정화
   - 생성 CSS 산출물 검토
   - `npm run check:refactor` 재실행
   - `git diff --check` 재실행
   - 변경 묶음 커밋

2. export 운영 검증
   - 소량 XLSX 생성과 Excel 열기 확인
   - 1만 건 초과 분할 export 확인
   - ZIP 묶음 다운로드 확인
   - 실패 메시지와 로그 확인
   - 결과를 이 문서 또는 별도 검증 기록에 남김

3. PHPExcel 제거 판단
   - export 검증 통과 후 `lib/PHPExcel` 삭제 여부 결정
   - 삭제한다면 별도 커밋으로 진행

4. 빌드 재현성 결정
   - lockfile 추적 또는 Tailwind exact version 고정 중 선택
   - CI에 build check를 넣을지 결정

5. 다음 플랫폼화 검토
   - admin/export 운영 리스크가 닫힌 뒤 신규 도메인 도입 여부 재평가

## 문서 운영 규칙

- 현재 작업계획, 남은 일, 보류 판단은 이 문서에만 추가한다.
- 구조 규칙은 `docs/architecture/*`에 남긴다.
- 과거 QA 증적은 `docs/project-evaluation-report-2026-04-22.md`처럼 날짜가 붙은 보고서에 보존한다.
- 새 계획 문서를 만들기보다 이 문서를 갱신한다.
