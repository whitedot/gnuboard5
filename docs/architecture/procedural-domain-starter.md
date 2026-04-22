# Procedural Domain Starter

## 목적

이 문서는 `community`, `shop`, `booking` 같은 신규 도메인을 추가할 때 바로 복제해서 사용할 수 있는 절차형 도메인 스타터 템플릿이다.

기준 구조 원칙은 `procedural-platform-reference.md`를 따르고, 이 문서는 실제 컨트롤러와 도메인 파일을 어떤 모양으로 시작할지 정의한다.

## 기본 폴더 템플릿

```text
lib/domain/{domain}/
├─ request.lib.php
├─ validation.lib.php
├─ persist.lib.php
├─ flow.lib.php
├─ render.lib.php
└─ page.lib.php
```

필요하면 아래처럼 세분화한다.

```text
lib/domain/{domain}/
├─ request-list.lib.php
├─ request-write.lib.php
├─ validation-list.lib.php
├─ validation-write.lib.php
├─ persist-list.lib.php
├─ persist-write.lib.php
├─ flow-write.lib.php
├─ render-page-view.lib.php
├─ page-controller.lib.php
└─ page-shell.lib.php
```

## 컨트롤러 종류

### 1. 화면형 controller

조회와 화면 렌더만 담당한다.

```php
<?php
include_once('./_common.php');

$request = domain_read_list_request($_GET, $page);
domain_validate_list_request($request, $member, $is_admin);

$page_view = domain_build_list_page_view($request, $member);
DomainPageController::render($page_view['title'], 'list.skin.php', $page_view['data'], $page_view['options']);
```

규칙:

- SQL 금지
- 템플릿 데이터 배열 직접 조합 최소화
- 가능하면 `domain_build_*_page_view()`로 `title`, `data`, `options`를 받는다

관리자 콘솔 화면도 같은 원칙을 쓴다.

```php
<?php
require_once './_common.php';

$request = admin_read_list_request($_GET);
$page_view = admin_build_list_page_view($request, $member, $is_admin, $config);
extract($page_view, EXTR_SKIP);

$g5['title'] = $title;
require_once './admin.head.php';
// render...
require_once './admin.tail.php';
```

### 2. 완료형 action controller

검증, 저장, 후처리 후 종료한다.

```php
<?php
include_once('./_common.php');

$request = domain_read_write_request($_POST, $_SESSION);
domain_complete_write_request($request, $member, $config);
```

규칙:

- 가능한 한 `domain_complete_*()` 호출 1회로 끝낸다
- redirect, alert, session 정리, 이벤트 호출은 `flow`에서 끝낸다

관리자 저장형도 같은 패턴을 쓴다.

```php
<?php
require_once './_common.php';

$request = admin_read_write_request($_POST);
admin_complete_write_request($request, $member, $auth, $sub_menu, $qstr);
```

### 3. AJAX controller

입력 검증과 짧은 응답만 담당한다.

```php
<?php
include_once('./_common.php');

$request = domain_read_ajax_request($_POST);
domain_process_ajax_duplicate_check($request);
```

규칙:

- 세션 반영이나 짧은 출력 종료도 `flow`에서 마무리한다
- controller에 분기문을 쌓지 않는다

관리자 AJAX도 같은 방식으로 정리한다.

```php
<?php
require_once './_common.php';

$request = admin_read_ajax_request($_POST);
admin_complete_ajax_request($request);
```

### 4. Stream controller

SSE, 대용량 다운로드, 긴 작업 진행률 응답처럼 스트림을 여는 경우에만 쓴다.

```php
<?php
require_once './_common.php';

$stream = admin_complete_member_export_stream_request($_GET, $auth, $sub_menu);
domain_run_export_stream($stream['params']);
```

규칙:

- 권한 체크, 런타임 준비, 스트림 헤더 설정, 초기 검증은 `complete_*`에서 끝낸다
- 실제 긴 작업 실행만 controller 또는 별도 실행 함수로 남긴다

## 도메인 파일 역할

### request

- `$_GET`, `$_POST`, `$_SESSION` 정규화
- 기본값 설정
- 형식 변환

### validation

- 필수값
- 권한
- 접근 가능 여부
- 상태 검증

### persist

- 조회/저장/삭제
- 트랜잭션
- 도메인별 DB 접근

### flow

- 여러 단계 조합
- redirect/alert
- 이벤트 호출
- 메일/알림
- 세션 정리

### render/page

- 템플릿 데이터 조합
- 화면 공통 shell
- 템플릿 렌더

## 추천 네이밍

- `domain_read_*_request`
- `domain_validate_*`
- `domain_find_*`, `domain_store_*`, `domain_update_*`
- `domain_process_*`
- `domain_complete_*`
- `domain_build_*_page_view`

## 현재 프로젝트 기준 참조 예시

- 화면형: `member/login.php`, `member/register_form.php`
- 완료형: `member/register_form_update.php`, `member/password_reset_update.php`
- AJAX형: `member/ajax.mb_id.php`, `member/ajax.mb_email.php`
- 관리자 화면형: `adm/member_list.php`, `adm/config_form.php`
- 관리자 완료형: `adm/member_list_update.php`, `adm/config_form_update.php`
- 관리자 AJAX형: `adm/ajax.token.php`
- 관리자 stream형: `adm/member_list_exel_export.php`

## 신규 도메인 착수 체크리스트

1. `_common.php`와 공용 shell 진입 규칙부터 정한다.
2. `request/validation/persist` 3개는 최소 단위로 먼저 만든다.
3. 첫 화면형 controller 하나를 `page_view` 패턴으로 만든다.
4. 첫 완료형 controller 하나를 `complete_*` 패턴으로 만든다.
5. 중복검사나 짧은 응답이 있다면 AJAX controller를 `process_*` 패턴으로 맞춘다.
6. 이후에만 세부 파일을 분해한다.
