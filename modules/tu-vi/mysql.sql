CREATE TABLE `nv4_vi_tu_vi_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL DEFAULT '0',
  `full_name` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Male, 0: Female',
  `birth_day` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `birth_month` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `birth_year` smallint(4) unsigned NOT NULL DEFAULT '0',
  `birth_hour` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `calendar_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Duong, 0: Am',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `nv4_vi_tu_vi_interpretations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `star_key` varchar(50) NOT NULL DEFAULT '',
  `palace_key` varchar(50) NOT NULL DEFAULT '',
  `topic` varchar(50) NOT NULL DEFAULT 'tong_quan',
  `content` mediumtext,
  `weight` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `star_palace` (`star_key`, `palace_key`),
  KEY `topic` (`topic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `nv4_vi_tu_vi_config` (
  `config_name` varchar(100) NOT NULL DEFAULT '',
  `config_value` mediumtext,
  PRIMARY KEY (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nv4_vi_tu_vi_config` (`config_name`, `config_value`) VALUES ('horoscope_method', 'nam_phai');
INSERT INTO `nv4_vi_tu_vi_config` (`config_name`, `config_value`) VALUES ('allow_guest', '1');
