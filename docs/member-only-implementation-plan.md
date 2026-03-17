# Member-Only Implementation Plan

## 목적

회원 전용 전환의 남은 작업을 실제 구현 순서대로 고정한다.

이 문서는 다음 세션에서 바로 이어서 작업할 수 있도록 아래 내용을 포함한다.

- 현재 상태 요약
- 우선순위별 구현 단계
- 각 단계의 대상 파일
- 완료 기준
- 검증 방법
- 보류 범위

## 현재 상태

기준 시점:

- 브랜치: `codex/member-only-refactor`
- 최근 정리 방향: `bbs` 회원 엔트리포인트를 `member/` 중심 호환 레이어로 축소

이미 끝난 큰 작업:

- `shop/` 디렉터리 제거
- `adm/shop_admin/` 디렉터리 제거
- 포인트 / 쪽지 / 공개 프로필 종료
- 회원 가입 / 로그인 / 인증 / 비밀번호 재설정 흐름을 `member/` 중심으로 정리
- `bbs` 회원 처리 스크립트와 AJAX를 `member/` 브리지로 축소
- 게시판 비밀번호 진입점 종료 및 `board.php` 리다이렉트 제거
- 게시판 링크 / 첨부 / 썸네일 헬퍼의 member-only URL 생성 차단
- 팝업레이어 include를 루트 `newwin.inc.php`로 이동
- `alert.php`, `alert_close.php`, `confirm.php` 공용 템플릿을 루트로 이동
- member-only 모드에서 SNS 공유 / syndication 비활성화

계획된 구현 단계는 모두 마무리되었고, 남은 일은 최종 체크리스트를 실행하며 결과를 기록하는 것이다.

## 작업 원칙

1. 회원 기능은 `member/`를 단일 구현 원천으로 유지한다.
2. `bbs`는 가능하면 리다이렉트 또는 실행 브리지 외 로직을 갖지 않는다.
3. 게시판 / 쇼핑몰 기능은 “숨김”보다 “비활성화 또는 제거”를 우선한다.
4. 영향 범위가 넓은 공용 라이브러리는 member-only 가드부터 넣고, 전면 제거는 마지막에 검토한다.
5. 각 단계는 문법 검증과 문서 반영까지 포함해 마무리한다.

## 구현 단계

### Phase 1. 게시판 비밀번호 흐름 정리

상태:

- 완료

목표:

- 게시판 글 비밀번호 확인 흐름을 member-only 기준에서 종료한다.

대상 파일:

- `bbs/password.php`
- `bbs/password_check.php`
- 필요 시 연관 호출부:
  - `extend/g5_54version_update.extend.php`

작업:

- 게시판 글 읽기/수정/삭제 전용 비밀번호 진입점을 스텁 처리하거나 홈으로 종료
- member-only에서 더 이상 유효하지 않은 `board.php` 리다이렉트 제거
- 남은 `short_url_clean(G5_HTTP_BBS_URL.'/board.php?...')` 의존 축소

완료 기준:

- `bbs/password.php`, `bbs/password_check.php`가 게시판 기능을 계속 살리지 않음
- 회원 기능에는 영향이 없음

검증:

- `php -l bbs\password.php`
- `php -l bbs\password_check.php`

커밋 분리 권장:

- 가능

### Phase 2. 게시판 링크 / 첨부 / 썸네일 헬퍼 축소

상태:

- 완료

목표:

- 공용 라이브러리에서 게시판 다운로드 / view_image / link 경로 생성을 정리한다.

대상 파일:

- `lib/common.data.lib.php`
- `lib/common.file.lib.php`
- `lib/thumbnail.lib.php`

작업:

- member-only 모드에서 `link.php`, `download.php`, `view_image.php` 링크를 만들지 않도록 정리
- 게시판 리스트 / 첨부가 전제된 속성값을 빈 값 또는 안전한 기본값으로 전환
- 호출부가 이미 제거된 로직은 함께 축소

완료 기준:

- 공용 라이브러리에서 게시판 전용 링크 생성 흔적이 크게 줄어듦
- member-only 모드에서 게시판 URL이 새로 생성되지 않음

검증:

- `php -l lib\common.data.lib.php`
- `php -l lib\common.file.lib.php`
- `php -l lib\thumbnail.lib.php`

커밋 분리 권장:

- 가능

### Phase 3. 공용 BBS include / URL 의존 축소

상태:

- 완료

목표:

- 공용 헬퍼 레벨의 `G5_BBS_*` / `bbs` include 의존을 더 줄인다.

대상 파일:

- `lib/common.html.lib.php`
- `lib/uri.lib.php`
- `lib/URI/uri.class.php`
- 필요 시 확인 파일:
  - `theme/basic/head.sub.php`
  - `adm/head.sub.admin.php`

작업:

- `alert.php`, `alert_close.php`, `confirm.php`의 include 경로를 공용 위치로 이동할지 검토
- `lib/uri.lib.php`의 게시판 rewrite 규칙을 member-only 기준으로 더 축소할지 결정
- `lib/URI/uri.class.php`의 게시판 URL 판별 의존 정리

진행 메모:

- `lib/common.html.lib.php`의 `alert`, `alert_close`, `confirm` include를 루트 공용 템플릿으로 이동함
- `lib/uri.lib.php`의 member-only rewrite 규칙은 게시판 경로를 제외하고 `content`만 유지하도록 조정함
- `lib/URI/uri.class.php`도 member-only 차단 기준과 `content` URL 처리 기준을 맞춤

완료 기준:

- `G5_BBS_PATH`, `G5_BBS_URL` 직접 참조가 게시판 전용 호환층 중심으로만 남음

검증:

- `php -l lib\common.html.lib.php`
- `php -l lib\uri.lib.php`
- `php -l lib\URI\uri.class.php`

주의:

- 이 단계는 영향 범위가 넓으므로, member-only 가드 추가와 공용 파일 이동을 우선하고 전면 삭제는 마지막에 검토한다.

### Phase 4. 관리자 / 설치 / 레거시 자산 축소

상태:

- 완료

목표:

- 설치 및 업그레이드 자산에서 쇼핑몰 / 게시판 잔재를 더 걷어낸다.

대상 파일:

- `install/gnuboard5shop.sql`
- `orderupgrade.php`
- `adm/config_form_update.php`
- `adm/menu_list.php`
- 필요 시 참고:
  - `data/dbconfig.php`

작업:

- 신규 설치에 실제로 필요 없는 쇼핑 스키마 자산의 보존 여부 결정
- member-only에서 더 이상 의미 없는 업그레이드 / 메뉴 / 설정 저장 분기 축소
- 운영 중 설치 파일 삭제 여부는 별도 판단

진행 메모:

- `install/gnuboard5shop.sql`은 설치 경로에서 더 이상 사용되지 않아 제거함
- `orderupgrade.php`는 member-only에서 즉시 차단하도록 정리함
- `adm/menu_list_update.php`는 member-only에서 게시판 / 쪽지 / 포인트 / 프로필 / 쇼핑몰 링크를 홈으로 치환하도록 정리함
- `adm/menu_list.php`에 member-only 링크 치환 안내를 추가함
- `adm/config_form_update.php`는 member-only에서 의미 없는 syndication / copy-log 저장값을 중립화함

완료 기준:

- member-only 신규 설치 관점에서 불필요한 쇼핑 초기화 자산이 정리됨
- 관리자에서 제거된 기능의 설정 저장 경로가 더 줄어듦

검증:

- 수정 파일별 `php -l`
- 설치 문서 / 범위 문서 동기화 확인

주의:

- 이 단계는 운영 환경과 기존 데이터 마이그레이션 이슈가 있으므로 한 번에 크게 삭제하지 않는다.

### Phase 5. extend / js 잔재 정리

상태:

- 완료

목표:

- member-only 범위에서 이미 종료된 기능의 훅과 프런트 스크립트를 정리한다.

대상 파일:

- `js/common.js`

작업:

- 메모 / 게시판 비밀번호 예외 처리 등 종료된 기능용 extend 훅 제거 또는 member-only 가드 처리
- `win_point`, `win_memo`, `win_profile`, `win_scrap` 등 종료한 팝업 헬퍼와 이벤트 바인딩 제거
- 현재 참조되지 않는 `shop*.js`, `viewimageresize.js`, `sns.js` 자산의 삭제 여부 확정
- 남겨야 하는 스크립트는 실제 사용 경로를 문서화

진행 메모:

- `extend/g5_54version_update.extend.php`의 게시판 비밀번호 복구 훅 제거를 완료함
- `extend/g5_54version_update.extend.php`는 더 이상 사용되는 훅이 없어 제거함
- `js/common.js`에서 종료된 포인트 / 쪽지 / 프로필 / 스크랩 팝업 헬퍼와 바인딩을 제거함
- 참조가 없는 `js/shop*.js`, `js/viewimageresize.js`, `js/sns.js`를 삭제함

완료 기준:

- `extend/`에 종료 기능을 되살릴 수 있는 훅이 남지 않음
- `js/`에 포인트 / 쪽지 / 프로필 / 쇼핑몰 전용 죽은 코드가 크게 줄어듦

검증:

- 주요 화면에서 콘솔 에러 없이 회원가입 / 로그인 / 비밀번호 찾기 동작 확인
- 삭제 후보 JS 파일이 실제로 참조되지 않는지 추가 검색 확인

주의:

- `js/common.js`는 공용 스크립트이므로 비슷한 이름의 다른 기능 바인딩까지 같이 지우지 않도록 범위를 좁힌다.

### Phase 6. 테스트 체크리스트 확정

상태:

- 완료

목표:

- 남은 기능만 기준으로 수동 검증 범위를 고정한다.

대상 문서:

- `docs/member-only-remaining-work.md`
- 필요 시 신규 체크리스트 문서 추가

필수 검증 항목:

- 회원가입
- 로그인 / 로그아웃
- 이메일 인증
- 비밀번호 찾기 / 재설정
- 회원정보 수정
- 회원탈퇴
- 관리자 회원 생성 / 수정 / 삭제
- 소셜 로그인 연동 계정 처리

진행 메모:

- `docs/member-only-remaining-work.md`를 최종 수동 테스트 체크리스트 문서로 재작성함
- 사전 준비, 절차, 기대 결과, 실행 후 기록 항목까지 포함해 다음 작업자가 바로 검증할 수 있게 고정함

완료 기준:

- 다음 작업자도 그대로 따라할 수 있는 수준의 점검 순서와 기대 결과가 문서화됨

## 현재 남은 핵심 파일

우선 확인 순서:

- `docs/member-only-remaining-work.md`

## 보류 항목

- `bbs` 디렉터리 완전 제거
- DB 테이블 / 컬럼 물리 삭제
- 레거시 훅 / 상수 호환성 완전 제거
- 방문통계 / 메일 발송 이력 유지 여부 최종 결정

## 세션 재개 체크포인트

다음 세션에서 바로 시작할 때 확인할 것:

1. 현재 브랜치가 `codex/member-only-refactor`인지 확인
2. 적용할 변경사항과 워킹트리 상태를 먼저 확인
3. `docs/member-only-remaining-work.md` 체크리스트를 실행
4. 테스트 결과와 발견 이슈를 문서 또는 별도 보고서에 반영
