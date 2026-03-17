# Upstream Sync Notes

원본 저장소와의 동기화 시 회원, 인증, 관리자 회원 관리에 영향을 주는 변경만 추적한다.

## 현재 기준

- Fork 분기점: `v5.6.23`
- 유지 범위: 회원/인증/관리자

## 확인 원칙

1. 암호화, 인증, 세션, 메일, 관리자 회원 관리 관련 보안 패치를 우선 확인한다.
2. 제거된 기능 영역의 변경은 별도 보관하지 않는다.

## 검토 파일

- `common.php`
- `config.php`
- `member/`
- `adm/member_*`
- `adm/auth_*`
- `lib/common.*.lib.php`
- `lib/uri.lib.php`
- `lib/URI/uri.class.php`

## 동기화 절차

1. upstream fetch
2. 유지 범위와 겹치는 커밋 선별
3. 로컬 모듈 구조에 맞게 수동 반영
4. `php -l` 및 수동 회원 흐름 검증
