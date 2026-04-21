# Member Collaboration Phases 1-3

## 목표

회원 흐름에서 가장 자주 수정되는 책임을 작은 단위로 나눠서, 사람이든 AI든 수정 위치를 빠르게 찾을 수 있게 한다.

## 1차 분리 결과

- `lib/member.request.lib.php`
  회원/관리자 회원 저장 흐름의 입력 수집과 정규화를 담당한다.
- `lib/member.persist.lib.php`
  회원 저장, 동의 로그 반영, 본인확인 이력 기록, 트랜잭션을 담당한다.
- `lib/member.flow.lib.php`
  회원 인증 연계 계산, 메일 발송, 저장 후 응답 흐름을 담당한다.

## 2차 분리 결과

- `lib/member.validation.lib.php`
  회원가입/회원수정/관리자 회원수정의 입력 검증과 중복 확인을 담당한다.
- `member/register_form_update.php`
  검증 세부 규칙을 직접 들고 있지 않고, validation/persist/flow를 순서대로 조립한다.
- `adm/member_form_update.php`
  관리자 회원 저장의 중복 검사와 트랜잭션 실행을 helper로 넘기고, 화면 분기와 최종 이동에 집중한다.

## 3차 분리 결과

- `lib/member.persist.lib.php`
  회원가입/회원수정/관리자 회원 저장에 필요한 payload 조립까지 맡는다.
- `member/register_form_update.php`
  payload를 직접 조립하지 않고, 회원 저장 단계 조합만 담당한다.
- `adm/member_form_update.php`
  요청 읽기, validation, persist 호출, 후처리만 남는 얇은 오케스트레이터가 되었다.

## 파일 경계 규칙

- 화면 컨트롤러(`member/*.php`, `adm/member_*.php`)는 요청값을 직접 오래 들고 있지 않는다.
- 입력 정규화는 `member.request.lib.php`에 둔다.
- 입력 검증과 중복 확인은 `member.validation.lib.php`에 둔다.
- `insert`, `update`, payload 조립, 동의 로그, 인증 이력, 트랜잭션은 `member.persist.lib.php`에 둔다.
- 메일 발송과 최종 이동/응답은 `member.flow.lib.php`에 둔다.
- 공통 부트스트랩은 `common.php`에서 조립만 하고, 회원 도메인 세부 규칙은 위 모듈로 보낸다.
- 컨트롤러는 직접 SQL 문자열이나 대형 필드 배열을 조립하지 않는다.

## 현재 컨트롤러 역할

- `member/register_form_update.php`
  분기, validation 호출, persist 호출, 최종 응답 호출
- `adm/member_form_update.php`
  관리자 권한 체크, validation 호출, persist 호출, 저장 후 이동

## 현재 파일 맵

- `lib/member.request.lib.php`
  입력 수집, 타입 정규화, 기본값 정리
- `lib/member.validation.lib.php`
  입력 규칙 검증, 중복 검사, 관리자 권한 기반 수정 가능 여부 확인
- `lib/member.persist.lib.php`
  회원 payload 조립, 동의 로그 계산, 인증 이력 기록, 실제 DB 저장
- `lib/member.flow.lib.php`
  인증 메일 발송, 비밀번호 찾기 메일 발송, 저장 후 응답 제어

## 다음 단계

- `member/register_form_update.php`와 `adm/member_form_update.php`의 실패 응답 규격을 더 공통화
- 회원가입/회원수정/관리자수정 실패 사유를 공통 구조로 반환하는 helper 추가
- 회원 흐름 수동 QA 체크리스트를 문서화해 협업자가 바로 검증할 수 있게 만들기
