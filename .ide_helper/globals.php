<?php
/**
 * IDE와 AI를 위한 최소 전역 변수 정의 파일
 * 실제 실행 시에는 포함되지 않아야 함.
 */

/** @var array<string, mixed> $member 현재 로그인한 회원 정보 */
$member = [];

/** @var array<string, mixed> $config 사이트 설정 정보 */
$config = [];

/** @var array<string, mixed> $g5 전역 상수 및 테이블 정보 배열 */
$g5 = [];

/** @var array<string, mixed> $write 에디터/필터용 임시 데이터 */
$write = [];

/** @var bool|string $is_admin 관리자 여부 */
$is_admin = false;

/** @var bool $is_member 회원 여부 */
$is_member = false;

/** @var bool $is_guest 비회원 여부 */
$is_guest = false;

/** @var string $url 현재 URL */
$url = '';
