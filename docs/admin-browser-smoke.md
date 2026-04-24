# Admin Browser Smoke

## 목적

- 관리자 핵심 브라우저 흐름의 최소 회귀를 Playwright로 확인한다.
- 기본 시나리오는 비인증 상태 접근 리다이렉트 검증이다.
- 관리자 자격 증명이 있으면 회원 목록과 회원 export 화면까지 확장 검증한다.

## 전제 조건

- 로컬 또는 테스트 서버에서 프로젝트가 실행 중이어야 한다.
- Playwright 브라우저가 설치되어 있어야 한다.

```bash
npx playwright install chromium
```

## 환경 변수

- `G5_BASE_URL`
  - 기본값: `http://127.0.0.1/g5`
- `G5_ADMIN_ID`
  - 관리자 로그인 계정
- `G5_ADMIN_PASSWORD`
  - 관리자 로그인 비밀번호

## 실행

비인증 리다이렉트 smoke만 확인:

```bash
npm run check:admin-browser-smoke
```

- 기본 `G5_BASE_URL` 가 응답하지 않으면 체크는 실패 대신 `skip` 처리된다.
- `G5_ADMIN_ID`, `G5_ADMIN_PASSWORD` 가 없으면 비인증 리다이렉트 smoke만 실행된다.

관리자 로그인까지 포함한 smoke 확인:

```bash
$env:G5_BASE_URL='http://127.0.0.1/g5'
$env:G5_ADMIN_ID='admin'
$env:G5_ADMIN_PASSWORD='password'
npm run check:admin-browser-smoke
```

## GitHub Actions

- `.github/workflows/admin-browser-smoke.yml` 로 수동 실행 가능
- `workflow_dispatch` 입력값으로 `base_url` 을 넘긴다
- `G5_ADMIN_ID`, `G5_ADMIN_PASSWORD` 저장소 시크릿이 있으면 인증 smoke까지 함께 실행된다
- 시크릿이 없으면 비인증 smoke만 실행된다

## 현재 커버 범위

1. 비인증 상태에서 `adm/member_list_exel.php` 접근 시 로그인 페이지로 이동하는지 확인
2. 로그인 페이지 hidden `url` 필드가 관리자 경로를 유지하는지 확인
3. 관리자 자격 증명이 있으면 `adm/member_list.php` 진입 확인
4. 관리자 자격 증명이 있으면 `adm/member_list_exel.php` 진입 및 핵심 UI 존재 확인

## 실행 정책

- 서버 미기동: `skip`
- 관리자 자격 증명 없음: 비인증 smoke만 실행
- 관리자 자격 증명 있음: 비인증 + 관리자 최소 진입 smoke 실행
