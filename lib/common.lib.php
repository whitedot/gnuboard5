<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * @ai-context
 * 이 파일은 그누보드5의 핵심 공통 함수들을 모듈화하여 로드하는 중앙 허브 역할을 합니다.
 * 기존의 거대한 Monolithic 구조였던 common.lib.php를 기능별로 분리된 라이브러리 파일들로 나누어 포함하고 있습니다.
 *
 * 주요 포함 모듈 및 역할:
 * - common.util.lib.php: 유틸리티 함수 (기본 헬퍼, 시간 등)
 * - common.session.lib.php: 세션 및 쿠키 처리
 * - common.mobile.lib.php: 모바일 기기 감지
 * - common.sql.lib.php: 데이터베이스 관련 함수
 * - common.string.lib.php: 문자열 처리 및 변환
 * - common.crypto.lib.php: 암호화 및 비밀번호 처리
 * - common.file.lib.php: 파일 업로드 및 관리
 * - common.data.lib.php: 회원, 게시판, 그룹 등 주요 데이터 엔티티 접근
 * - common.point.lib.php: 포인트 시스템 관리
 * - common.html.lib.php: UI 요소 생성 및 HTML 헬퍼 (페이징, 알림 등)
 */

/**
 * Common Library Modules
 * 
 * The functions previously contained in this file have been moved to separate module files
 * in the lib/ directory for better maintainability and organization.
 */

// Utility functions (basic helpers, microtime, etc)
include_once(G5_LIB_PATH.'/common.util.lib.php');

// Session and Cookie handling
include_once(G5_LIB_PATH.'/common.session.lib.php');

// Mobile device detection
include_once(G5_LIB_PATH.'/common.mobile.lib.php');

// Database functions (SQL)
include_once(G5_LIB_PATH.'/common.sql.lib.php');

// String manipulation (text, cleaning, conversion)
include_once(G5_LIB_PATH.'/common.string.lib.php');

// Cryptography (password, encryption) - requires SQL
include_once(G5_LIB_PATH.'/common.crypto.lib.php');

// File handling (upload, download, view) - requires SQL
include_once(G5_LIB_PATH.'/common.file.lib.php');

// Data entities (Member, Board, Group access) - requires SQL
include_once(G5_LIB_PATH.'/common.data.lib.php');

// Point system - requires SQL
include_once(G5_LIB_PATH.'/common.point.lib.php');

// HTML/UI functions (paging, alerts, selects) - requires Data/SQL
include_once(G5_LIB_PATH.'/common.html.lib.php');
