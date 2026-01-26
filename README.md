# 그누보드 5 / 영카트 리팩토링 프로젝트

## 1. 프로젝트 개요 (Project Overview)
이 프로젝트는 그누보드 5(G5) 및 영카트 기반 시스템의 리팩토링 프로젝트입니다.

기존 **레거시 구조에 익숙한 개발자들이 AI를 이용해 개발할 때 겪는 병목 현상 및 이로 인한 토큰 소모 해소**를 제일 큰 목표로 삼습니다.

개발자들은 이제 코드를 직접 작성하는 것을 넘어, AI 에이전트에게 문맥(Context)을 제공하고 논리적 오류를 검증하는 프롬프트 엔지니어링의 영역으로 업무를 확장하고 있습니다. 그러나 이러한 AI 주도 개발(AI-Driven Development)의 효율성은 대상 시스템의 아키텍처가 얼마나 'AI 친화적(AI-Friendly)'인가에 따라 결정적으로 달라진다고 할 수 있습니다.

그누보드5(Gnuboard 5)는 전형적인 초기 PHP 절차적 지향 프로그래밍(Procedural Programming) 패턴을 따르고 있습니다. 그중에서도 핵심 라이브러리 파일인 common.lib.php, shop.lib.php 등은 시스템 전반에 걸친 수천 줄의 유틸리티 함수들이 단일 파일에 집약된 모놀리식(Monolithic) 구조를 취하고 있습니다. 이러한 구조는 인간 개발자가 IDE(통합 개발 환경)의 '정의로 이동(Go to Definition)' 기능을 사용할 때는 큰 문제가 되지 않았으나, 제한된 컨텍스트 윈도우(Context Window)를 가진 AI 모델이 코드를 분석하고 수정하는 데 있어서는 병목 현상을 초래할 수 있습니다.

## 2. 핵심 리팩토링 목표 (Key Refactoring Objectives)
**AI 활용성 개선 (AI Usability Improvement)** 명확한 모듈 분리와 구조화를 통해 AI 코딩 어시스턴트가 코드 문맥을 더 정확히 이해하고 효율적으로 협업할 수 있는 환경을 조성합니다. 이를 위해,
- **모듈화 (Modularization)**: 기능이 비대한 "God files"를 단일 책임 원칙에 따라 작은 모듈로 분리합니다.
- **UX/DX 개선**: 관리자 인터페이스 개선 및 사용자 경험을 통합합니다.

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

## 4. 개발 환경 및 크레딧 (Development Environment & Credits)

- **기획 및 리서치 (Planning Environment)**: Google Gemini Deep Research
- **개발 환경 (Development Environment)**: Google AntiGravity (AI-Native IDE)
- **사용 모델 (AI Models Used)**:
    - Gemini 3 Pro
    - Gemini 3 Flash

## 5. 향후 계획 (Future Plans)

- **UI 프레임워크 현대화 (UI Framework Modernization)**: AI 친화적인 UI/UX 환경 구축 및 개발 생산성 향상을 위해 **Tailwind CSS v4**를 도입하고 적용합니다.
- **명시적 의존성 문서화 (AI-Specific PHPDoc)**: AI 에이전트의 코드 이해도와 문맥 파악 능력을 극대화하기 위한 주석 전략을 도입합니다.
    - **`@global` 태그 활용**: 함수 내부에서 사용되는 전역 변수 의존성을 명시적으로 나열하여 데이터 흐름을 추적 가능하게 합니다.
    - **`@aiagent-description` 태그 도입**: 커스텀 태그를 통해 해당 함수의 역할, 비즈니스 로직, 제약 사항을 AI가 이해하기 쉬운 자연어로 기술합니다.

## 6. 프로젝트의 한계 및 제약 사항 (Limitations & Constraints)

- **아키텍처 현대화의 범위 제한 (Scope Limitation on Modernization)**: 본 프로젝트는 코드 분리 및 정리에 집중하며, Composer 기반의 오토로딩(Autoloading) 도입과 같은 근본적인 PHP 레거시 구조의 현대화는 수행하지 않습니다.
- **개발 주체 및 면책 조항 (Project Leadership & Disclaimer)**: 본 프로젝트는 기획자 및 디자이너 직군 출신인 지운아빠가 주도하여 진행되므로, 전문 엔지니어링 관점의 코드 정합성(Consistency)이나 기술적 유효성(Validity)을 보장하지 않습니다.
    - **면책 (Indemnification)**: 본 프로젝트의 결과물을 사용하여 발생하는 데이터 손실, 서비스 중단, 보안 사고 등 어떠한 형태의 직·간접적 피해에 대해서도 제작자(및 제작자의 소속사)는 법적 책임을 지지 않습니다. 사용자는 실제 서비스 적용 전 반드시 충분한 테스트와 검증 과정을 거쳐야 합니다.
- **라이선스 정책 (License Policy)**: 본 프로젝트는 그누보드5(Gnuboard 5)를 기반으로 한 리팩토링 결과물로서, 원본 소프트웨어인 그누보드5의 라이선스 정책을 그대로 따릅니다.
