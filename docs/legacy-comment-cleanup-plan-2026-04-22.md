# 레거시 주석/오해 유발 표현 정리 계획

## 목적

이번 계획의 목적은 최근 전수 점검에서 발견된 레거시 주석, 주석 처리된 구코드, 출처성 메모, 그리고 AI나 다음 작업자가 현재 기능 범위를 오해할 수 있는 활성 문자열까지 한 번에 정리하는 것이다.

이번 작업은 "코드가 실제로 무엇을 한다"와 "소스가 무엇을 암시하는가"를 일치시키는 데 초점을 둔다.

## 정리 원칙

- 실행 중인 기능 설명에 꼭 필요한 주석만 남긴다.
- 예전 구현, 임시 패치, 출처 링크, 역사성 메모는 제거를 기본으로 한다.
- 주석 처리된 코드는 되살릴 계획이 문서화되어 있지 않으면 삭제한다.
- 현재 member-only 범위와 맞지 않는 활성 문자열은 주석보다 우선해서 정리한다.
- 라이선스, 외부 라이브러리 저작권 표기, 실제 설정 설명은 유지 대상이다.

## 제거 대상

### 1. 즉시 제거 대상

현재 코드와 다른 구현이 살아 있는 것처럼 보이거나, 임시 수정 흔적이 노출되는 항목들이다.

- `lib/Hook/hook.class.php`
  - `이부분 수정` 메모
  - 주석 처리된 이전 `getInstance()` 구현
  - `priority 로 정렬 하려면 ksort` 메모
- `lib/common.file.lib.php`
  - 이전 함수 시그니처와 `filesize()` 호출 주석
- `member/password_reset.php`
  - 주석 처리된 `captcha.lib.php` include
- `skin/member/basic/register_form.skin.php`
  - 이름 한글 검증용 주석 처리 JS 블록

### 2. 출처/역사성 주석 제거 대상

현재 동작 이해에 필요하지 않고, 프로젝트 기원이나 외부 출처만 드러내는 항목들이다.

- `lib/common.string.lib.php`
  - Google 검색 URL 출처 주석
- `lib/common.sql.lib.php`
  - `PHPMyAdmin 참고`
- `lib/support/base.util.lib.php`
  - `기존의 get_unique_id()` 비교 설명
- `install/install_db.php`
  - `그누보드5 재설치` 문구가 들어간 설치 주석
- `config.php`
  - `그누보드 디버그바 설정` 문구
- `cloudflare.check.php`
  - 기능 범위와 무관한 역사성 표현이 남아 있다면 중립 문구로 교체하거나 제거

### 3. 활성 코드 정합성 정리 대상

주석은 아니지만 이번 점검에서 함께 확인된 오해 유발 표현이다. 이 항목은 실제 범위 정합성에 직접 영향을 주므로 우선순위를 높게 둔다.

- `skin/member/basic/register.skin.php`
  - 개인정보 처리 항목 설명의 `아이핀 제외` 문구 제거
- `skin/member/basic/register_form.skin.php`
  - `cf_cert_ipin` 조건 참조 제거
  - 인증 상태 표시의 `아이핀` 분기 제거
  - `회원정보 입력/수정` HTML 구획 주석 제거
- `skin/member/basic/member_cert_refresh.skin.php`
  - `cf_cert_ipin` 조건 참조 제거
  - `아이핀 제외` 문구 제거
  - `기존 회원 본인인증` HTML 구획 주석 제거
- `skin/member/basic/password_lost.skin.php`
  - `cf_cert_ipin` 조건 참조 제거

### 4. 저위험 정리 대상

기능 오해 유발 정도는 낮지만, "정리 완료" 기준으로 보면 같이 비우는 편이 좋은 항목들이다.

- `head.php`, `tail.php`
  - 상단/하단/콘텐츠 시작·끝 HTML 주석
- `skin/member/basic/*.skin.php`
  - 화면 시작·끝을 표시하는 HTML 주석
- 기타 first-party 파일의 범용 구획 주석 중 현재 탐색 가치가 낮은 항목

## 실행 순서

### 1단계. 활성 코드 정합성 정리

주석보다 먼저, 실제 화면과 조건 분기에 남아 있는 `아이핀`, `cf_cert_ipin`, 범위 밖 표현을 제거한다.

이 단계에서 수정 대상:

- `skin/member/basic/register.skin.php`
- `skin/member/basic/register_form.skin.php`
- `skin/member/basic/member_cert_refresh.skin.php`
- `skin/member/basic/password_lost.skin.php`

검증:

- `.tools/php/php -l` 로 수정 파일 lint
- `rg -n "cf_cert_ipin|아이핀" --glob '*.php'` 재검사
- 회원가입/본인확인/비밀번호 찾기 화면 렌더 확인

### 2단계. 주석 처리된 구코드와 임시 메모 제거

오래된 대체 구현과 패치 메모를 삭제한다.

이 단계에서 수정 대상:

- `lib/Hook/hook.class.php`
- `lib/common.file.lib.php`
- `member/password_reset.php`
- `skin/member/basic/register_form.skin.php`

검증:

- `.tools/php/php -l` 로 수정 파일 lint
- `rg -n "^\\s*//include_once\\(|^\\s*//function |^\\s*//\\s*이부분 수정|^\\s*/\\*\\s*$" --glob '*.php'` 재검사

### 3단계. 출처/역사성 주석 제거

현재 동작 설명과 무관한 출처, 참고, 역사성 표현을 제거하거나 중립적인 한 줄 설명으로 정리한다.

이 단계에서 수정 대상:

- `lib/common.string.lib.php`
- `lib/common.sql.lib.php`
- `lib/support/base.util.lib.php`
- `install/install_db.php`
- `config.php`
- `cloudflare.check.php`

검증:

- `.tools/php/php -l` 로 수정 파일 lint
- `rg -n "(출처|참고|그누보드|기존의 get_unique_id|PHPMyAdmin)" --glob '*.php' --glob '!docs/**'` 재검사

### 4단계. HTML 구획 주석 일괄 정리

기능 설명 가치가 낮은 시작/끝 주석을 정리한다.

이 단계에서 수정 대상:

- `head.php`
- `tail.php`
- `skin/member/basic/*.skin.php`

검증:

- 화면 렌더링 차이 없는지 확인
- `rg -n "^\\s*<!--.*(시작|끝).*$" --glob '*.php' --glob '!docs/**'` 재검사

### 5단계. 최종 잔존 검사

first-party 코드에서 아래 패턴이 사실상 사라졌는지 확인한다.

- `@ai-context`
- `wayboard`
- `아이핀`
- `cf_cert_ipin`
- `출처`
- `PHPMyAdmin`
- `이부분 수정`
- `//include_once(`
- `//function `

예외:

- 외부 라이브러리 라이선스
- 실제 설정 설명으로 필요한 주석
- 문서 파일

## 성공 기준

- first-party 실행 코드와 주석에서 범위 밖 기능을 암시하는 표현이 제거된다.
- 주석 처리된 구코드가 member/admin/core 경로에 남지 않는다.
- `cf_cert_ipin` 과 `아이핀` 이 first-party 실행 경로에서 사라진다.
- 수정 파일 PHP lint 가 모두 통과한다.
- 회원가입, 비밀번호 찾기/재설정, 회원정보 수정 화면이 최소 렌더 수준에서 정상 동작한다.

## 주의 사항

- `adm/css/admin.css` 는 이전 빌드 산출물 변경이 이미 워킹트리에 남아 있으므로 이번 정리 작업과 섞어 판단하지 않는다.
- `.tools/` 와 외부 라이브러리 파일은 이번 정리 범위에서 제외한다.
- 기능 설명에 꼭 필요한 주석까지 기계적으로 지우지 않도록, 제거 전에 "현재 동작 이해에 필요한가"를 마지막으로 한 번 더 확인한다.
