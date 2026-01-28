<?php
/**
 * IDE와 AI를 위한 전역 변수 정의 파일
 * 실제 실행 시에는 포함되지 않아야 함.
 * @see .ide_helper/types.php
 */

require_once __DIR__ . '/types.php';

// --- 그누보드 핵심 전역 변수 ---

/** @var G5MemberShape $member 현재 로그인한 회원 정보 */
$member = [];

/** @var G5ConfigShape $config 사이트 설정 정보 */
$config = [];

/** @var G5SystemShape $g5 그누보드 전역 상수 및 테이블 정보 배열 */
$g5 = [];

/** @var G5BoardShape $board 현재 접속한 게시판 설정 */
$board = [];

/** @var G5GroupShape $group 현재 접속한 게시판 그룹 설정 */
$group = [];

/** @var G5WriteShape $write 현재 조회 중인 게시물/댓글 정보 */
$write = [];

/** @var string $bo_table 게시판 테이블 ID */
$bo_table = '';

/** @var int $wr_id 게시물 ID */
$wr_id = 0;


// --- 영카트(쇼핑몰) 핵심 전역 변수 ---

/** @var G5ShopDefaultShape $default 쇼핑몰 설정 정보 */
$default = [];

/** @var G5ShopItemShape $it 상품 정보 (루프 내에서 주로 사용됨) */
$it = [];

/** @var G5ShopOrderShape $od 주문 정보 */
$od = [];

/** @var G5ShopCartShape $ct 장바구니 정보 (루프 내에서 주로 사용됨) */
$ct = [];

/** @var G5ShopCategoryShape $ca 카테고리 정보 */
$ca = [];

/** @var G5QaConfigShape $qaconfig 1:1문의 설정 정보 */
$qaconfig = [];


// --- 기타 공통 전역 변수 ---

/** @var bool $is_admin 관리자 여부 */
$is_admin = false;

/** @var bool $is_member 회원 여부 */
$is_member = false;

/** @var bool $is_guest 비회원 여부 */
$is_guest = false;

/** @var string $url 현재 URL */
$url = '';