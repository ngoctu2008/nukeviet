<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();
$sql_create_module = array();

// Table: _units
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_units";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_units (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    note text,
    weight smallint(4) NOT NULL DEFAULT '0',
    status tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table: _topics (New)
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topics";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topics (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    note text,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table: _exams
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_exams";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_exams (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    alias varchar(255) NOT NULL DEFAULT '',
    description text,
    time_start int(11) unsigned NOT NULL DEFAULT '0',
    time_end int(11) unsigned NOT NULL DEFAULT '0',
    duration smallint(4) unsigned NOT NULL DEFAULT '0',
    num_questions smallint(4) unsigned NOT NULL DEFAULT '0',
    status tinyint(1) NOT NULL DEFAULT '1',
    has_prediction tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    UNIQUE KEY alias (alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table: _exam_structure (New)
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_exam_structure";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_exam_structure (
    exam_id mediumint(8) unsigned NOT NULL,
    topic_id mediumint(8) unsigned NOT NULL,
    quantity smallint(4) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (exam_id, topic_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";


// Table: _questions (Updated with topic_id)
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_questions";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_questions (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    exam_id mediumint(8) unsigned NOT NULL,
    topic_id mediumint(8) unsigned NOT NULL DEFAULT '0',
    title text NOT NULL,
    type tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:Radio, 2:Checkbox, 3:Fill, 4:Essay',
    note text,
    score float NOT NULL DEFAULT '1',
    weight smallint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY exam_id (exam_id),
    KEY topic_id (topic_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table: _answers
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    question_id int(11) unsigned NOT NULL,
    title text NOT NULL,
    is_correct tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY question_id (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table: _users_result
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_users_result";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_users_result (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    exam_id mediumint(8) unsigned NOT NULL,
    unit_id mediumint(8) unsigned NOT NULL,
    user_id mediumint(8) unsigned NOT NULL DEFAULT '0',
    fullname varchar(255) NOT NULL DEFAULT '',
    phone varchar(20) NOT NULL DEFAULT '',
    address varchar(255) DEFAULT '',
    score float NOT NULL DEFAULT '0',
    essay_score float NOT NULL DEFAULT '0',
    total_score float NOT NULL DEFAULT '0',
    correct_count smallint(4) NOT NULL DEFAULT '0',
    prediction int(11) NOT NULL DEFAULT '0',
    time_submit int(11) unsigned NOT NULL DEFAULT '0',
    duration_used int(11) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    KEY exam_id (exam_id),
    KEY unit_id (unit_id),
    KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table: _users_detail
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_users_detail";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_users_detail (
    result_id int(11) unsigned NOT NULL,
    question_id int(11) unsigned NOT NULL,
    answer_id int(11) unsigned NOT NULL DEFAULT '0',
    user_answer text,
    KEY result_id (result_id),
    KEY question_id (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
