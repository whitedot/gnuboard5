# 그누보드 5 / 영카트 현대화 및 리팩토링 프로젝트

## 1. 프로젝트 개요 (Project Overview)
이 프로젝트는 그누보드 5(G5) 및 영카트 기반 시스템의 현대화 및 리팩토링 프로젝트입니다.
기존의 비대한 레거시 코드를 현대적인 개발 표준에 맞춰 개선하고, 유지보수성과 확장성을 확보하는 것을 목표로 합니다.

## 2. 핵심 리팩토링 목표 (Key Refactoring Objectives)
- **모듈화 (Modularization)**: 기능이 비대한 "God files"를 단일 책임 원칙에 따라 작은 모듈로 분리합니다.
- **유지보수성 (Maintainability)**: 코드 가독성 향상 및 디버깅 용이성을 확보합니다.
- **안정성 (Stability)**: 경로 참조 방식을 표준화하여(상대 경로 -> 절대 경로 상수 사용) 시스템 안정성을 높입니다.
- **UX/DX 개선**: 관리자 인터페이스 개선 및 사용자 경험을 통합합니다.
- **AI 활용성 개선 (AI Usability Improvement)**: 명확한 모듈 분리와 구조화를 통해 AI 코딩 어시스턴트가 코드 문맥을 더 정확히 이해하고 효율적으로 협업할 수 있는 환경을 조성합니다.

## 3. 상세 변경 내역 (Detailed Changes)

### 핵심 라이브러리 리팩토링 (Core Library Refactoring)
`lib/common.lib.php` 및 `lib/shop.lib.php`와 같은 거대 라이브러리 파일을 기능별로 분리하였습니다.

- **`lib/common.lib.php` 분리**:
    - `common.crypto.lib.php`: 암호화 관련 함수
    - `common.data.lib.php`: 데이터 처리 및 가공
    - `common.mobile.lib.php`: 모바일 기기 감지 및 처리
    - `common.sql.lib.php`: SQL 쿼리 빌더 및 실행
    - `common.string.lib.php`: 문자열 조작 유틸리티
    - `common.uri.lib.php`: URI 처리 및 리다이렉트
    - `common.util.lib.php`: 기타 범용 유틸리티

- **`lib/shop.lib.php` 분리**:
    - `shop.item.lib.php`: 상품 리스트, 재고 체크 등 상품 관련 기능
    - `shop.image.lib.php`: 이미지 리사이징, 썸네일 생성 등 이미지 처리
    - `shop.price.lib.php`: 금액 표시, 계산 등 가격 관련 기능
    - `shop.point.lib.php`: 포인트 지급, 차감, 내역 조회 등 포인트 관련 기능
    - `shop.user.lib.php`: 위시리스트, 오늘 본 상품 등 유저 데이터 기능
    - `shop.order.lib.php`: 장바구니, 주문 처리, 결제 정보 등 주문 관련 기능
    - `shop.delivery.lib.php`: 배송비 계산, 배송 조회 등 배송 관련 기능
    - `shop.pay.lib.php`: PG사 결제 연동, 결제 수단 확인 등 결제 관련 기능
    - `shop.coupon.lib.php`: 쿠폰 발급, 사용 확인 등 쿠폰 관련 기능
    - `shop.util.lib.php`: 쇼핑몰 전용 유틸리티 기능

### 관리자 페이지 컴포넌트화 (Admin Page Componentization)
유지보수가 어렵고 복잡했던 관리자 페이지들을 작은 단위로 분할하고 구조를 개선했습니다.

- **관리자 폼 분할**: `member_form.php`, `config_form.php` 등 수천 줄에 달하는 파일들을 `*_parts` 디렉토리 내의 작은 파일들로 분리하여 관리 용이성을 높였습니다.
- **탭 내비게이션 도입**: 정보량이 많은 페이지에 탭 방식을 도입하여 스크롤 압박을 줄이고 정보 접근성을 개선했습니다.
- **주문서 로직 통합**: PC와 모바일로 나뉘어 있던 주문서 로직을 통합하고 구조를 통일하여 중복 코드를 제거했습니다.
