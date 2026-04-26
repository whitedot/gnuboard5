# Admin Export Pattern

## 목적

관리자 회원 export 흐름은 화면, 요청 정규화, 조회, 파일 생성, 스트리밍 응답을 분리해서 유지한다.

## 파일 책임

- `adm/member_list_exel.php`: export 화면 controller. `_common.php`, 권한 확인, page request 생성, partial 렌더링만 담당한다.
- `adm/member_list_exel_export.php`: export stream controller. `_common.php`, stream page request 생성, complete 함수 호출만 담당한다.
- `lib/domain/admin/export-config.lib.php`: export 상수, 필터 옵션, 요청 파라미터 정규화, where/count query를 담당한다.
- `lib/domain/admin/export-query.lib.php`: sheet 컬럼 구성과 회원 row 조회 statement를 담당한다.
- `lib/domain/admin/export-file.lib.php`: XLSX/ZIP 파일 생성, 임시 파일 삭제, 로그 기록을 담당한다.
- `lib/domain/admin/export-stream.lib.php`: SSE 준비, stream request 검증, 진행 이벤트, 전체 export 실행 순서를 담당한다.
- `lib/domain/admin/export-view.lib.php`: 화면 view-model, client config, runtime readiness 메시지를 담당한다.
- `lib/domain/admin/export-maintenance.lib.php`: 수동 파일 정리 action을 담당한다.

## Naming 규칙

- export domain 함수는 `admin_*_member_export_*` 또는 `admin_member_export_*` 형태를 유지한다.
- export 런타임 상수는 `ADMIN_MEMBER_EXPORT_*` 형태만 사용한다.
- legacy helper 이름인 `member_export_*` 와 legacy 상수명인 `MEMBER_EXPORT_*`, `MEMBER_BASE_*`, `MEMBER_LOG_DIR` 는 재도입하지 않는다.

## Controller 규칙

- page 파일에서 `check_demo()`, XLSX/ZIP 직접 실행, runtime 직접 조립을 다시 넣지 않는다.
- stream 실행은 `admin_complete_member_export_stream_page()` 안으로 모은다.
- 화면 데이터는 `admin_build_member_export_page_request()` 결과의 `view`를 통해 전달한다.
- partial은 `$member_export_view`, `$filter_state`, `$member_export_links` 같은 화면 데이터만 사용한다.

## 검증 기준

- `npm run check:admin-refactor`
- `npm run check:refactor`
