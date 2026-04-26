# Legacy Maintainer Guide

기준일: 2026-04-26

## 목적

이 문서는 기존 G5 절차형 PHP 개발자가 현재 저장소를 빠르게 이해하고 유지보수할 수 있도록 만든 입문 안내서다.

현재 구조는 Composer autoload나 프레임워크 전환 없이, 기존 include 기반 실행 흐름을 유지한다. 대신 controller를 얇게 만들고 실제 입력 정리, 검증, 저장, 화면 데이터 조립 책임을 `lib/domain/*`와 `lib/support/*`로 나누는 방식으로 정리되어 있다.

## 먼저 읽을 문서

1. `README.md`
2. `docs/current-work-plan-2026-04-26.md`
3. `docs/member-only-scope.md`
4. `docs/architecture/member-controller-pattern.md`
5. `docs/architecture/admin-controller-pattern.md`
6. `docs/architecture/admin-include-map.md`
7. `docs/architecture/admin-export-pattern.md`

## 큰 구조

- `common.php`: 사이트 공통 부트스트랩 진입점이다. 새 업무 로직을 넣는 곳이 아니다.
- `adm/_common.php`: 관리자 공통 부트스트랩 진입점이다. 관리자 controller는 이 파일을 먼저 포함한다.
- `member/`: 회원/인증 화면과 action controller가 남아 있는 영역이다.
- `adm/member_*`, `adm/config_*`, `adm/index.php`: 현재 유지 중인 관리자 화면 controller다.
- `lib/domain/member/`: 회원 도메인의 입력 정리, 검증, 저장, 흐름, 화면 데이터 조립 구현 원천이다.
- `lib/domain/admin/`: 관리자 도메인의 화면, 저장, export, 관리자 shell 구현 원천이다.
- `lib/support/`: 여러 화면에서 공유하는 공용 유틸리티 구현 원천이다.
- `lib/bootstrap/`: 런타임 request context, 인증, 세션 같은 실행 준비 구현 원천이다.
- `lib/common.*.lib.php`, `lib/member.*.lib.php`: 기존 include 경로를 살리기 위한 호환 로더다. 새 로직을 추가하기 전에 실제 include 대상 파일을 먼저 확인한다.

## 작업별 파일 지도

| 작업 | 시작 controller | 주 구현 파일 |
| --- | --- | --- |
| 로그인 | `member/login.php`, `member/login_check.php` | `lib/domain/member/request-auth.lib.php`, `validation-auth.lib.php`, `flow-auth.lib.php`, `render-auth.lib.php` |
| 로그아웃 | `member/logout.php` | `lib/domain/member/request-auth.lib.php`, `flow-auth.lib.php` |
| 회원가입/정보수정 화면 | `member/register_form.php` | `lib/domain/member/request-register.lib.php`, `render-register-form.lib.php`, `page.lib.php` |
| 회원가입/정보수정 저장 | `member/register_form_update.php`, `member/member_leave.php` | `lib/domain/member/validation-register.lib.php`, `persist-register.lib.php`, `flow-register.lib.php` |
| 비밀번호 찾기/재설정 | `member/password_*` | `lib/domain/member/request-auth.lib.php`, `validation-auth.lib.php`, `persist-auth.lib.php`, `flow-auth.lib.php` |
| 이메일 인증/수신중지 | `member/email_*`, `member/register_email*` | `lib/domain/member/request-auth.lib.php`, `validation-auth.lib.php`, `flow-auth.lib.php` |
| 회원 AJAX 중복 검사 | `member/ajax.mb_*.php` | `lib/domain/member/request-ajax.lib.php`, `validation-ajax.lib.php`, `flow-ajax.lib.php` |
| 관리자 대시보드 | `adm/index.php` | `lib/domain/admin/dashboard.lib.php`, `lib/domain/admin/ui.lib.php` |
| 관리자 회원 목록 | `adm/member_list.php` | `lib/domain/admin/member-list.lib.php`, `adm/member_list_parts/*` |
| 관리자 회원 일괄 처리 | `adm/member_list_update.php` | `lib/domain/admin/member-list.lib.php` |
| 관리자 회원 등록/수정 | `adm/member_form.php`, `adm/member_form_update.php` | `lib/domain/admin/member-form.lib.php`, `adm/member_form_parts/*` |
| 관리자 설정 | `adm/config_form.php`, `adm/config_form_update.php` | `lib/domain/admin/config.lib.php`, `adm/config_form_parts/*` |
| 관리자 회원 export 화면 | `adm/member_list_exel.php` | `lib/domain/admin/export-view.lib.php`, `export-config.lib.php` |
| 관리자 회원 export 다운로드 | `adm/member_list_exel_export.php` | `lib/domain/admin/export-stream.lib.php`, `export-file.lib.php`, `xlsx.lib.php` |
| 관리자 shell/menu | `adm/admin.head.php`, `adm/admin.tail.php`, `adm/head.sub.admin.php` | `lib/domain/admin/ui.lib.php`, `lib/domain/admin/bootstrap.lib.php`, `adm/admin.js` |

## 어디에 코드를 추가할까

- 새 입력 파싱은 controller가 아니라 `request*.lib.php`에 둔다.
- 필수값, 권한, 상태 검사는 `validation*.lib.php`에 둔다.
- DB 조회, 저장, 삭제는 `persist*.lib.php`나 admin 전용 query/export 파일에 둔다.
- redirect, alert, 세션 정리, 후처리는 `flow*.lib.php`에 둔다.
- 화면에 넘길 배열 조립은 `render*.lib.php`, `page*.lib.php`, admin view 파일에 둔다.
- 여러 도메인이 공유하는 순수 유틸리티는 `lib/support/`에 둔다.
- 기존 include 경로가 꼭 필요할 때만 `lib/common.*.lib.php` 또는 `lib/member.*.lib.php` 호환 로더를 손댄다.

## 피해야 할 변경

- `common.php`, `adm/admin.lib.php`, `lib/common.util.lib.php`에 새 업무 로직을 추가하지 않는다.
- controller에 SQL, 큰 분기, 화면 배열 조립을 다시 쌓지 않는다.
- controller에서 `extract()`로 숨은 변수를 만들지 않는다.
- 관리자 entry와 domain helper에서 `$_GET`, `$_POST`, `$_REQUEST`, `$_SERVER`를 직접 읽지 않는다. `g5_get_runtime_*_input()` 또는 도메인 request context를 사용한다.
- aggregate loader에 업무 로직을 넣지 않는다. include 흐름은 `docs/architecture/admin-include-map.md` 기준으로 유지한다.
- `lib/PHPExcel` 또는 `lib/PHPExcel.php`를 복구하지 않는다.
- Tailwind 생성 CSS를 직접 고치지 않는다. 스타일 변경은 `tailwind4/` 원천 파일을 고친 뒤 빌드한다.

## 작업 전후 확인

작업 전에는 먼저 `git status --short`로 다른 변경이 있는지 확인한다.

작업 후에는 변경 범위에 맞춰 아래 명령을 실행한다.

```sh
npm ci
npm run build
PATH=".tools/php:$PATH" npm run check:refactor
git diff --check
```

문서만 고친 경우에도 `git diff --check`는 실행한다. PHP include 경로나 controller 예시를 건드렸다면 `check:refactor`까지 실행한다.
