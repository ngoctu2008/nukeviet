<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$menu_top = array(
    'exam' => $lang_module['exam_manager'],
    'questions' => $lang_module['question_manager'],
    'units' => $lang_module['unit_manager'],
    'topics' => $lang_module['topic_manager'],
    'report' => $lang_module['report_manager'],
    'custom_title' => $lang_module['main']
);

$allow_func = array('main', 'config', 'exam', 'questions', 'units', 'report', 'result', 'topics');

$submenu['exam'] = $lang_module['exam_list'];
$submenu['units'] = $lang_module['unit_list'];
$submenu['topics'] = $lang_module['topic_list'];
$submenu['questions'] = $lang_module['question_list'];
$submenu['report'] = $lang_module['report_main'];
