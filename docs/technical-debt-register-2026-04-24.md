# Technical Debt Register

기준일: 2026-04-24

## 요약

- 현재 가장 큰 기술 부채는 구조 그 자체보다 `admin export` 운영 호환성 확인 공백이다.
- `member-only` 및 `admin` 경계 정리는 상당 부분 진행됐고, 구조 회귀는 정적 가드로 빠르게 확인할 수 있다.
- 이번 정리에서 `PHPExcel` 재유입 방지 가드와 구조 회귀 가드를 정비해 즉시 줄일 수 있는 부채를 먼저 해소한다.

## 부채 목록

### 1. 관리자 회원 export 운영 호환성 확인 공백

- 우선순위: P1
- 상태: 일부 해결
- 증상:
  - `xlsx` writer로 교체됐지만 운영 Excel 열기 호환성과 대용량 다운로드 확인 기록이 아직 부족하다.
  - 정적 lint 와 패턴 검사만으로는 실제 Excel 앱 호환성, 다운로드 재시도 UX, 운영 데이터 규모 이슈를 판단하기 어렵다.
- 이번 처리:
  - `ZipArchive` 부재 시 관리자 화면에서 바로 안내하고 다운로드 버튼을 비활성화하도록 선행 가드 추가
- 남은 일:
  - 운영 Excel 열기 호환성 확인
  - 실제 운영 데이터 기준 대용량 export 확인

### 2. PHPExcel 레거시 트리 잔존

- 우선순위: P1
- 상태: 일부 해결
- 증상:
  - 활성 export 코드는 `PHPExcel` 에서 벗어났지만, 레거시 트리 자체는 저장소에 남아 있다.
  - PHP 8.3/8.4 계열에서 deprecated notice를 유발하는 파일이 있어, 참조가 되살아나면 바로 런타임 리스크가 된다.
- 이번 처리:
  - `scripts/check-legacy-phpexcel-retirement.ps1` 추가
  - `scripts/check-legacy-phpexcel-retirement.js` 추가 및 npm script 실행 경로 전환
  - `lib/PHPExcel` 바깥의 PHP 코드에서 `PHPExcel` 참조가 다시 생기면 즉시 실패하도록 가드 추가
- 남은 일:
  - 운영 Excel 열기 호환성과 대용량 export 확인 후 `lib/PHPExcel` 제거 여부 최종 판단

### 3. 회귀 감지 자동화 부족

- 우선순위: P1
- 상태: 추가 해결
- 이번 처리:
  - `package.json` 의 `check:refactor` 에 PHPExcel retirement check 연결
  - `check:refactor` 하위 refactor npm script 실행 경로를 Node.js 래퍼로 전환하고 PowerShell 파일은 호환 shim으로 축소
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

## 이번 턴에서 실제로 줄인 부채

- `PHPExcel` 참조 재유입 위험
- 저장소 수준 CI 부재
- OS별 refactor check 실행 래퍼 차이

## 다음 우선순위

1. 운영 Excel 열기 호환성 확인
2. 실제 운영 데이터 기준 대용량 export 확인
3. 브랜치 보호 규칙과 필수 status check 연결
4. `lib/PHPExcel` 제거 판단
