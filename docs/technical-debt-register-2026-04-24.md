# Technical Debt Register

기준일: 2026-04-24

## 요약

- 현재 가장 큰 기술 부채는 구조 그 자체보다 `admin export` 런타임 검증 공백이다.
- `member-only` 및 `admin` 경계 정리는 상당 부분 진행됐지만, 운영 시나리오 기준 회귀 가드는 아직 얇았다.
- 이번 정리에서 `export smoke check` 와 `PHPExcel` 재유입 방지 가드를 추가해 즉시 줄일 수 있는 부채를 먼저 해소한다.

## 부채 목록

### 1. 관리자 회원 export 런타임 검증 공백

- 우선순위: P1
- 상태: 일부 해결
- 증상:
  - `xlsx` writer로 교체됐지만 생성 파일의 최소 유효성을 자동 점검하는 체크가 없었다.
  - 정적 lint 와 패턴 검사만으로는 zip 구조, sheet xml, style xml 이상 여부를 잡기 어렵다.
- 이번 처리:
  - `scripts/check-admin-export-smoke.php`
  - `scripts/check-admin-export-smoke.ps1`
  - sample XLSX 생성, zip 엔트리 검증, workbook/sheet/styles xml 핵심 구조 검증 추가
  - `ZipArchive` 부재 시 관리자 화면에서 바로 안내하고 다운로드 버튼을 비활성화하도록 선행 가드 추가
- 남은 일:
  - 실제 운영 데이터 기준 대용량 분할/zip 다운로드 브라우저 검증
  - 운영 Excel 열기 호환성 확인

### 2. PHPExcel 레거시 트리 잔존

- 우선순위: P1
- 상태: 일부 해결
- 증상:
  - 활성 export 코드는 `PHPExcel` 에서 벗어났지만, 레거시 트리 자체는 저장소에 남아 있다.
  - PHP 8.3/8.4 계열에서 deprecated notice를 유발하는 파일이 있어, 참조가 되살아나면 바로 런타임 리스크가 된다.
- 이번 처리:
  - `scripts/check-legacy-phpexcel-retirement.ps1` 추가
  - `lib/PHPExcel` 바깥의 PHP 코드에서 `PHPExcel` 참조가 다시 생기면 즉시 실패하도록 가드 추가
- 남은 일:
  - export 실운영 검증 완료 후 `lib/PHPExcel` 제거 여부 최종 판단

### 3. 회귀 감지 자동화 부족

- 우선순위: P1
- 상태: 추가 해결
- 증상:
  - 기존에는 request/admin/support 경계 점검은 있었지만 export 경로 전용 smoke guard가 없었다.
- 이번 처리:
  - `package.json` 의 `check:refactor` 에 export smoke 와 PHPExcel retirement check 연결
  - `.github/workflows/refactor-checks.yml` 추가
  - GitHub Actions 에서 PHP 8.3 + zip 확장 기준으로 `npm run check:refactor` 자동 실행 경로 추가
- 남은 일:
  - 필요하면 브랜치 보호 규칙과 필수 status check 연결

### 4. export.lib.php 책임 과다

- 우선순위: P2
- 상태: 추가 해결
- 증상:
  - stream 제어, query, 파일 생성, zip, logging 이 한 파일에 모여 있다.
- 이번 처리:
  - `lib/domain/admin/export-query.lib.php`
  - `lib/domain/admin/export-file.lib.php`
  - `lib/domain/admin/export-stream.lib.php`
  - `lib/domain/admin/export-view.lib.php`
  - `lib/domain/admin/export-maintenance.lib.php`
  - 기존 `export.lib.php` 를 include 집합 성격으로 축소
- 권장 해법:
  - 현재 구조면 추가 분리는 선택 사항이다
- 남은 일:
  - 파일 이름/경계가 현재 팀 작업 방식에 충분히 명확한지만 추후 점검

### 5. 브라우저 실동작 회귀 미검증

- 우선순위: P2
- 상태: 추가 해결
- 증상:
  - 관리자 메뉴, sideview 위치, export 팝업, 분할 다운로드 UX는 정적 검사로 보장되지 않는다.
- 이번 처리:
  - `playwright.config.js`
  - `tests/admin-browser-smoke.spec.js`
  - `docs/admin-browser-smoke.md`
  - `scripts/check-admin-browser-smoke.ps1`
  - `.github/workflows/admin-browser-smoke.yml`
  - 비인증 상태 admin export 접근 리다이렉트 smoke 추가
  - 관리자 자격 증명 기반 회원 목록/회원 export 최소 진입 smoke 골격 추가
  - 서버 미기동 시 `skip`, 자격 증명 부재 시 비인증 smoke만 실행되도록 래퍼 추가
  - GitHub Actions 수동 실행 경로 추가
- 남은 일:
  - sideview 위치, export 팝업, 분할 다운로드 완료 흐름까지 브라우저 검증 확장

## 이번 턴에서 실제로 줄인 부채

- `admin export` 최소 유효성 검증 공백
- `PHPExcel` 참조 재유입 위험
- `check:refactor` 의 export 경로 누락
- 저장소 수준 CI 부재
- 관리자 브라우저 smoke 부재
- 원격 환경용 브라우저 smoke 실행 경로 부재

## 다음 우선순위

1. 관리자 export 브라우저 실동작 검증
2. 운영 Excel 호환성 및 대용량 zip 검증
3. 브랜치 보호 규칙과 필수 status check 연결
4. `lib/PHPExcel` 제거 판단
