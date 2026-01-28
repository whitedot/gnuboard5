<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * @ai-context
 * 이 파일은 영카트5(쇼핑몰)의 핵심 함수들을 모듈화하여 로드하는 중앙 허브 파일입니다.
 * 쇼핑몰 운영에 필요한 가격 계산, 상품 관리, 주문 처리, 결제 시스템, 배송 등 다양한 기능을
 * 별도의 라이브러리 파일로 분리하여 관리하고 있습니다.
 *
 * 주요 포함 모듈 및 역할:
 * - shop.price.lib.php: 상품 가격 및 옵션 금액 계산
 * - shop.point.lib.php: 쇼핑몰 전용 포인트 관리 (적립, 차감)
 * - shop.user.lib.php: 쇼핑몰 회원 관련 기능
 * - shop.image.lib.php: 상품 이미지 처리 및 썸네일 생성
 * - shop.delivery.lib.php: 배송비 계산 및 배송 관리
 * - shop.item.lib.php: 상품 정보 조회 및 옵션 처리
 * - shop.order.lib.php: 주문 생성, 수정, 상태 관리
 * - shop.pay.lib.php: PG사 결제 연동 및 처리
 * - shop.coupon.lib.php: 쿠폰 발급, 적용 및 관리
 * - shop.util.lib.php: 쇼핑몰 기타 유틸리티 함수
 */

// 쇼핑몰 라이브러리 모음 시작

include_once(G5_LIB_PATH.'/shop.price.lib.php');
include_once(G5_LIB_PATH.'/shop.point.lib.php');
include_once(G5_LIB_PATH.'/shop.user.lib.php');
include_once(G5_LIB_PATH.'/shop.image.lib.php');
include_once(G5_LIB_PATH.'/shop.delivery.lib.php');
include_once(G5_LIB_PATH.'/shop.item.lib.php');
include_once(G5_LIB_PATH.'/shop.order.lib.php');
include_once(G5_LIB_PATH.'/shop.pay.lib.php');
include_once(G5_LIB_PATH.'/shop.coupon.lib.php');
include_once(G5_LIB_PATH.'/shop.util.lib.php');

// 쇼핑몰 라이브러리 모음 끝
