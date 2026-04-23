# Platformization Readiness Report

## 1. 목적

- `member-only` 1차 정리 이후, 저장소의 다음 방향을 플랫폼화 기준으로 확정한다.
- 현재 코드베이스를 플랫폼 확장 관점에서 전수 점검하고, 다음 사이클 우선순위를 정한다.

## 2. 결론

- `member-only` 범위 정리는 현재 기준선으로 본다.
- 다음 사이클은 신규 비즈니스 도메인 추가보다 `admin` 도메인 표준화 마무리에 집중한다.
- 특히 관리자 회원 엑셀 내보내기, 관리자 셸/메뉴, 화면 데이터 계약, PHP 8.4 호환성을 먼저 정리해야 한다.
- `community`, `shop`, `booking` 도메인 도입은 위 항목이 정리된 뒤에 착수한다.

## 3. 전수조사 요약

### P1

- `lib/domain/admin/export.lib.php` 가 `adm/member_list_exel_export.php` 내부 함수에 의존한다.
- `admin_complete_member_export_stream_request()` 는 `member_export_delete()`, `member_export_set_sse_headers()`, `member_export_write_log()` 를 직접 호출한다.
- 이 함수들은 실제로 `adm/member_list_exel_export.php` 안에 정의되어 있어, `domain/admin` 이 컨트롤러 파일 없이는 독립적으로 동작하지 못한다.
- 플랫폼화 기준에서는 의존 방향이 반대로 되어 있으므로 가장 먼저 바로잡아야 한다.

### P2

- 관리자 회원 엑셀 내보내기 경로의 `PHPExcel` 의존은 제거했지만, 새 `xlsx` writer 경로는 운영 데이터 기준으로 한 번 더 검증이 필요하다.
- 현재는 `adm/member_list_exel_export.php` 가 내부 `xlsx` writer 로 직접 생성하며, 라이브러리 교체 리스크보다 출력 호환성과 대용량 처리 동작을 확인하는 쪽이 우선이다.
- 신규 도메인을 붙이기 전에 이 경로가 대용량, 압축 다운로드, 운영 Excel 열기 시나리오에서 안정적으로 동작하는지 확인해야 한다.

- `adm/admin.head.php` 에 관리자 메뉴 렌더링 헬퍼가 여전히 인라인으로 남아 있다.
- `print_menu1()`, `admin_menu_icon_id()`, `print_menu2()` 가 `admin.head.php` 안에 있고, 파일 자체도 `extract($admin_head_view, EXTR_SKIP)` 후 대형 마크업을 직접 렌더한다.
- 로드맵의 `admin` 표준화 완료 기준인 "공통 규칙이 화면 파일에 다시 퍼지지 않음" 이 아직 완전히 충족되지 않았다.

- `lib/domain/admin/member-list.lib.php` 가 view-model 이 아니라 HTML 조각까지 반환한다.
- `manage_links`, `listall` 같은 값이 `<a>`, `<button>` 문자열로 조립되어 도메인 레이어에서 바로 내려온다.
- 현재 구조에서는 동작상 문제는 없지만, 이후 `community`, `shop`, `booking` 으로 같은 패턴을 확장할 때 재사용성과 테스트 가능성이 떨어진다.

### P3

- 관리자 화면 파일에서 `extract()` 기반 계약이 아직 넓게 남아 있다.
- `adm/member_list.php`, `adm/member_form.php`, `adm/config_form.php`, `adm/member_list_exel.php`, `adm/admin.head.php`, `adm/admin.tail.php`, `adm/head.sub.admin.php`, `adm/index.php` 가 같은 패턴을 사용한다.
- 현재 단계에서는 허용 가능하지만, 플랫폼화 다음 사이클에서는 화면 계약을 더 명시적으로 드러내는 쪽이 안전하다.

- `lib/domain/admin/ui.lib.php` 가 화면 유틸, DB 조회, HTML 생성, 파일 유틸을 한 파일에 함께 담고 있다.
- `get_member_id_select()`, `admin_build_anchor_menu()`, `rm_rf()`, `help()`, `admin_build_head_view()` 가 같은 파일에 공존해 있어 역할이 넓다.
- 즉시 오류는 아니지만 `admin` 도메인을 플랫폼 템플릿으로 쓰려면 쪼개는 편이 낫다.

## 4. 확인한 긍정 신호

- 1차 프로젝트 목표였던 `member-only` 구조 정리는 대체로 안정적으로 반영되어 있다.
- `lib/bootstrap`, `lib/support`, `lib/domain/member`, `lib/domain/admin` 경계는 실제 코드에 존재한다.
- 저장소 전체 PHP lint 는 1차 프로젝트 파일 기준으로 통과했다.
- 관리자 내보내기 경로는 이제 `PHPExcel` 없이 `xlsx` 를 직접 생성한다.

## 5. 다음 실행 계획

### 1단계. admin export 경계 복구

- `member_list_exel_export.php` 안의 재사용 가능한 함수들을 `lib/domain/admin/export.lib.php` 또는 새 세부 파일로 이동한다.
- stream controller 는 권한 체크, 공통 include, 단일 엔트리 호출만 남기고 끝나는 구조를 목표로 한다.
- 완료 기준:
  - `lib/domain/admin/export.lib.php` 가 컨트롤러 파일 내부 함수에 의존하지 않는다.
  - `adm/member_list_exel_export.php` 는 얇은 stream controller 가 된다.

### 2단계. admin shell/menu 표준화 마무리

- `adm/admin.head.php` 의 메뉴 렌더링 헬퍼와 셸 계산 로직을 `lib/domain/admin/` 으로 이동한다.
- 관리자 공통 셸에서 필요한 값은 명시적 view data 로 준비하고, 화면 파일은 렌더링에만 집중시킨다.
- 완료 기준:
  - 관리자 셸 공통 규칙이 `admin.head.php` 에 다시 누적되지 않는다.

### 3단계. 화면 계약 명시화

- `extract()` 에 과도하게 기대는 관리자 화면부터 정리한다.
- `page_view`, `form_state`, `items`, `actions` 같은 계약을 명시적으로 유지하고, `domain/admin` 이 가능한 한 순수 데이터만 반환하도록 맞춘다.
- 완료 기준:
  - 신규 관리자 화면은 HTML 문자열보다 데이터 구조를 우선 반환한다.
  - 화면 파일은 필요한 값만 명시적으로 사용한다.

### 4단계. 런타임 호환성 정리

- 새 `xlsx` writer 의 출력 호환 범위를 점검한다.
- 운영 Excel 열기, 다건 분할, zip 묶음, 문자열 인코딩 같은 실제 사용 경로에서 문제가 없는지 확인한다.
- 완료 기준:
  - 관리자 내보내기 경로의 런타임 호환성 정책이 문서와 코드에서 함께 설명된다.

### 5단계. 확장 전 최소 회귀 가드 추가

- 무거운 테스트 체계 대신 얇은 정적 가드를 둔다.
- 기본 후보는 `PHP lint`, 레거시 금지 패턴 grep, 빌드 스크립트 통과 여부다.
- 완료 기준:
  - 구조 정리 이후 되돌아오는 회귀를 빠르게 감지할 수 있다.

## 6. 플랫폼화 착수 조건

- `admin` 도메인 표준화 4단계가 문서 기준과 실제 코드 기준에서 모두 완료 상태가 된다.
- 관리자 stream/export 경로가 컨트롤러 의존 없이 독립적으로 동작한다.
- PHP 8.4 기준 주요 관리 경로에 알려진 호환성 경고가 방치되지 않는다.
- 위 조건을 만족한 뒤에만 `community`, `shop`, `booking` 도메인 도입을 다시 평가한다.
