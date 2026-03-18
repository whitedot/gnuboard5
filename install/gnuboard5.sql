-- --------------------------------------------------------

--
-- Table structure for table `g5_auth`
--

DROP TABLE IF EXISTS `g5_auth`;
CREATE TABLE IF NOT EXISTS `g5_auth` (
  `mb_id` varchar(20) NOT NULL default '',
  `au_menu` varchar(50) NOT NULL default '',
  `au_auth` set('r','w','d') NOT NULL default '',
  PRIMARY KEY  (`mb_id`,`au_menu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_config`
--

DROP TABLE IF EXISTS `g5_config`;
CREATE TABLE IF NOT EXISTS `g5_config` (
  `cf_id` int(11) NOT NULL auto_increment,
  `cf_title` varchar(255) NOT NULL DEFAULT '',
  `cf_admin` varchar(100) NOT NULL DEFAULT '',
  `cf_admin_email` varchar(100) NOT NULL DEFAULT '',
  `cf_admin_email_name` varchar(100) NOT NULL DEFAULT '',
  `cf_use_email_certify` tinyint(4) NOT NULL DEFAULT '0',
  `cf_cut_name` tinyint(4) NOT NULL DEFAULT '0',
  `cf_nick_modify` int(11) NOT NULL DEFAULT '0',
  `cf_write_pages` int(11) NOT NULL DEFAULT '0',
  `cf_possible_ip` text NOT NULL,
  `cf_intercept_ip` text NOT NULL,
  `cf_use_hp` tinyint(4) NOT NULL DEFAULT '0',
  `cf_req_hp` tinyint(4) NOT NULL DEFAULT '0',
  `cf_register_level` tinyint(4) NOT NULL DEFAULT '0',
  `cf_leave_day` int(11) NOT NULL DEFAULT '0',
  `cf_email_use` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_mb_super_admin` tinyint(4) NOT NULL DEFAULT '0',
  `cf_email_mb_member` tinyint(4) NOT NULL DEFAULT '0',
  `cf_prohibit_id` text NOT NULL,
  `cf_prohibit_email` text NOT NULL,
  `cf_optimize_date` date NOT NULL default '0000-00-00',
  `cf_image_extension` varchar(255) NOT NULL DEFAULT '',
  `cf_flash_extension` varchar(255) NOT NULL DEFAULT '',
  `cf_movie_extension` varchar(255) NOT NULL DEFAULT '',
  `cf_page_rows` int(11) NOT NULL DEFAULT '0',
  `cf_stipulation` text NOT NULL,
  `cf_privacy` text NOT NULL,
  `cf_use_promotion` tinyint(1) NOT NULL DEFAULT '0',
  `cf_open_modify` int(11) NOT NULL DEFAULT '0',
  `cf_captcha_mp3` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_use` tinyint(4) NOT NULL DEFAULT '0',
  `cf_cert_find` tinyint(4) NOT NULL DEFAULT '0',
  `cf_cert_ipin` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_hp` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_simple` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_kg_cd` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_kg_mid` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_use_seed` tinyint(4) NOT NULL DEFAULT '1',
  `cf_cert_kcp_cd` varchar(255) NOT NULL DEFAULT '',
  `cf_cert_kcp_enckey` varchar(100) NOT NULL DEFAULT '',
  `cf_lg_mid` varchar(100) NOT NULL DEFAULT '',
  `cf_lg_mert_key` varchar(100) NOT NULL DEFAULT '',
  `cf_toss_client_key` varchar(100) NOT NULL DEFAULT '',
  `cf_toss_secret_key` varchar(100) NOT NULL DEFAULT '',
  `cf_cert_limit` int(11) NOT NULL DEFAULT '0',
  `cf_cert_req` tinyint(4) NOT NULL DEFAULT '0',
  `cf_captcha` varchar(100) NOT NULL DEFAULT '',
  `cf_recaptcha_site_key` varchar(100) NOT NULL DEFAULT '',
  `cf_recaptcha_secret_key` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY  (`cf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_cert_history`
--

DROP TABLE IF EXISTS `g5_cert_history`;
CREATE TABLE IF NOT EXISTS `g5_cert_history` (
  `cr_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `cr_company` varchar(255) NOT NULL DEFAULT '',
  `cr_method` varchar(255) NOT NULL DEFAULT '',
  `cr_ip` varchar(255) NOT NULL DEFAULT '',
  `cr_date` date NOT NULL DEFAULT '0000-00-00',
  `cr_time` time NOT NULL DEFAULT '00:00:00',
  PRIMARY KEY (`cr_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_cert_history`
--

DROP TABLE IF EXISTS `g5_member_cert_history`;
CREATE TABLE IF NOT EXISTS `g5_member_cert_history` (
  `ch_id` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL DEFAULT '',
  `ch_name` varchar(255) NOT NULL DEFAULT '',
  `ch_hp` varchar(255) NOT NULL DEFAULT '',
  `ch_birth` varchar(255) NOT NULL DEFAULT '',
  `ch_type` varchar(20) NOT NULL DEFAULT '',
  `ch_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`ch_id`),
  KEY `mb_id` (`mb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `g5_member`
--

DROP TABLE IF EXISTS `g5_member`;
CREATE TABLE IF NOT EXISTS `g5_member` (
  `mb_no` int(11) NOT NULL auto_increment,
  `mb_id` varchar(20) NOT NULL default '',
  `mb_password` varchar(255) NOT NULL default '',
  `mb_name` varchar(255) NOT NULL default '',
  `mb_nick` varchar(255) NOT NULL default '',
  `mb_nick_date` date NOT NULL default '0000-00-00',
  `mb_email` varchar(255) NOT NULL default '',
  `mb_level` tinyint(4) NOT NULL default '0',
  `mb_sex` char(1) NOT NULL default '',
  `mb_birth` varchar(255) NOT NULL default '',
  `mb_hp` varchar(255) NOT NULL default '',
  `mb_certify` varchar(20) NOT NULL default '',
  `mb_adult` tinyint(4) NOT NULL default '0',
  `mb_dupinfo` varchar(255) NOT NULL default '',
  `mb_today_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `mb_login_ip` varchar(255) NOT NULL default '',
  `mb_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `mb_ip` varchar(255) NOT NULL default '',
  `mb_leave_date` varchar(8) NOT NULL default '',
  `mb_intercept_date` varchar(8) NOT NULL default '',
  `mb_email_certify` datetime NOT NULL default '0000-00-00 00:00:00',
  `mb_email_certify2` varchar(255) NOT NULL default '',
  `mb_memo` text NOT NULL,
  `mb_lost_certify` varchar(255) NOT NULL,
  `mb_mailling` tinyint(4) NOT NULL default '0',
  `mb_mailling_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `mb_open` tinyint(4) NOT NULL default '0',
  `mb_open_date` date NOT NULL default '0000-00-00',
  `mb_marketing_agree` tinyint(1) NOT NULL default '0',
  `mb_marketing_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `mb_agree_log` TEXT NOT NULL,
  PRIMARY KEY  (`mb_no`),
  UNIQUE KEY `mb_id` (`mb_id`),
  KEY `mb_today_login` (`mb_today_login`),
  KEY `mb_datetime` (`mb_datetime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Table structure for table `g5_unique`
--

DROP TABLE IF EXISTS `g5_uniqid`;
CREATE TABLE IF NOT EXISTS `g5_uniqid` (
  `uq_id` bigint(20) unsigned NOT NULL,
  `uq_ip` varchar(255) NOT NULL,
  PRIMARY KEY (`uq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `g5_new_win`
--
