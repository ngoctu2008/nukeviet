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
    'report' => $lang_module['report_manager']
);

$allow_func = array('main', 'config', 'exam', 'questions', 'units', 'report', 'result');

$submenu['exam'] = $lang_module['exam_list'];
$submenu['units'] = $lang_module['unit_list'];
$submenu['questions'] = $lang_module['question_list'];
$submenu['report'] = $lang_module['report_main'];
