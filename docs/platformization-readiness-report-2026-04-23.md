# Platformization Readiness Report

## 1. 목적

- `member-only` 1차 정리 이후, 저장소의 다음 방향을 플랫폼화 기준으로 확정한다.
- 현재 코드베이스를 플랫폼 확장 관점에서 전수 점검하고, 다음 사이클 우선순위를 정한다.

## 2. 결론

- `member-only` 범위 정리는 현재 기준선으로 본다.
- `admin` 도메인 표준화의 구조 정리는 2026-04-24 기준으로 대부분 마무리됐다.
- 관리자 회원 엑셀 내보내기 경계 복구, 관리자 셸/메뉴 공통 규칙 정리, 화면 데이터 계약 명시화는 코드 기준 완료로 본다.
- 다음 사이클의 우선순위는 신규 도메인 추가보다 관리자 내보내기 런타임 검증과 최소 회귀 가드 보강에 둔다.
- `community`, `shop`, `booking` 도메인 도입은 위 항목이 정리된 뒤에 착수한다.

## 3. 전수조사 요약

### 완료

- `lib/domain/admin/export.lib.php` 가 더 이상 `adm/member_list_exel_export.php` 내부 함수에 의존하지 않는다.
- `adm/member_list_exel_export.php` 는 권한 체크와 공통 include 후 `admin_run_member_export()` 만 호출하는 얇은 stream controller 로 축소됐다.
- 관리자 셸/메뉴 계산은 `lib/domain/admin/ui.lib.php` 로 이동했고, `adm/admin.head.php` 는 준비된 view data 를 렌더하는 쪽으로 정리됐다.
- `adm/member_list.php`, `adm/member_form.php`, `adm/config_form.php`, `adm/member_list_exel.php`, `adm/admin.head.php`, `adm/admin.tail.php`, `adm/head.sub.admin.php`, `adm/index.php` 의 `extract()` 기반 계약은 제거됐다.
- `lib/domain/admin/member-list.lib.php` 는 목록 액션, sideview, 전체 보기 링크 같은 HTML 조각을 직접 만들지 않고 화면이 렌더할 데이터 구조를 반환한다.
- `lib/domain/admin/ui.lib.php` 에 함께 섞여 있던 select/helper 성격 함수는 `lib/domain/admin/view-helper.lib.php` 로 분리됐고, 사용되지 않는 legacy helper (`get_member_id_select()`, `get_member_level_select()`, `help()`, `icon()`, `order_select()`) 는 제거됐다.

### 남은 P1

- 관리자 회원 엑셀 내보내기 경로의 `PHPExcel` 의존은 제거했지만, 새 `xlsx` writer 경로는 운영 데이터 기준으로 한 번 더 검증이 필요하다.
- 현재는 `domain/admin` 쪽으로 경계는 복구됐지만, 라이브러리 교체 리스크보다 출력 호환성과 대용량 처리 동작을 확인하는 쪽이 우선이다.
- 신규 도메인을 붙이기 전에 이 경로가 대용량, 압축 다운로드, 운영 Excel 열기 시나리오에서 안정적으로 동작하는지 확인해야 한다.

### 남은 P2

- 관리자 셸/메뉴와 export 경계는 정리됐지만, 실제 브라우저 기준 회귀 확인은 아직 필요하다.
- 우선 확인 대상은 관리자 메뉴 열림/접힘, 회원 목록 sideview 위치, 회원 엑셀 다운로드 진행 팝업, 분할 압축 다운로드 경로다.

### 남은 P3

- `admin` 도메인 내부에 아직 절차형 유틸이 남아 있어 파일 책임이 완전히 최소화된 상태는 아니다.
- 특히 `export.lib.php` 는 실행 로직이 많이 모여 있으므로, 이후에는 runtime/stream, file/log, query/xlsx writer 정도로 더 쪼갤 여지가 있다.

## 4. 확인한 긍정 신호

- 1차 프로젝트 목표였던 `member-only` 구조 정리는 대체로 안정적으로 반영되어 있다.
- `lib/bootstrap`, `lib/support`, `lib/domain/member`, `lib/domain/admin` 경계는 실제 코드에 존재한다.
- 저장소 전체 PHP lint 는 1차 프로젝트 파일 기준으로 통과했다.
- 관리자 내보내기 경로는 이제 `PHPExcel` 없이 `xlsx` 를 직접 생성한다.
- 관리자 주요 화면은 `page_view`, `filter_state`, `items`, `actions` 같은 명시적 계약을 사용한다.
- 관리자 폼과 설정 화면은 helper HTML 문자열보다 옵션 데이터와 화면 렌더링을 우선하는 구조로 이동했다.

## 5. 다음 실행 계획

### 1단계. export 런타임 검증

- 관리자 회원 엑셀 내보내기 경로를 실제 운영 시나리오로 검증한다.
- 단건, 1만건 초과 분할, zip 묶음, 오류 응답, 다운로드 재시도 경로를 모두 확인한다.
- 완료 기준:
  - 운영 Excel 열기와 다운로드 완료 흐름에서 치명적인 호환성 이슈가 없다.
  - 실패 시 사용자 메시지와 로그가 기대대로 남는다.

### 2단계. 최소 회귀 가드 추가

- 구조 정리 이후 되돌아오는 회귀를 얇게 감지할 수 있는 체크를 추가한다.
- 후보는 관리자 관련 `PHP lint`, 금지 패턴 grep(`extract(`, legacy helper 호출), 필요시 엑셀 export smoke check 다.
- 완료 기준:
  - 구조 회귀를 CI 또는 로컬 스크립트로 빠르게 확인할 수 있다.

### 3단계. admin 도메인 세부 분리

- `export.lib.php` 와 남은 UI 보조 파일의 책임을 더 좁힌다.
- 다만 이 단계는 1, 2단계가 끝난 뒤에 착수한다.
- 완료 기준:
  - 한 파일이 화면 계약, DB 조회, 파일 처리, 런타임 제어를 과도하게 동시에 맡지 않는다.

## 6. 플랫폼화 착수 조건

- `admin` 도메인 구조 정리와 export 런타임 검증이 모두 완료 상태가 된다.
- 관리자 stream/export 경로가 컨트롤러 의존 없이 독립적으로 동작한다.
- PHP 8.4 기준 주요 관리 경로에 알려진 호환성 경고와 회귀 포인트가 방치되지 않는다.
- 위 조건을 만족한 뒤에만 `community`, `shop`, `booking` 도메인 도입을 다시 평가한다.

## 7. 2026-04-24 코드 기준 메모

- 완료: 관리자 셸/메뉴 view-model 화, `extract()` 제거, member list 액션 데이터화, export stream 경계 복구, form helper 정리.
- 미완료: 브라우저 실동작 회귀 확인, export 대용량/압축/운영 Excel 호환성 검증, 최소 회귀 가드 자동화.
