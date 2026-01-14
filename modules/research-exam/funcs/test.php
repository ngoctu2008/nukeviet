<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_IS_MOD_RESEARCH_EXAM')) {
    die('Stop!!!');
}

$exam_id = $nv_Request->get_int('id', 'get', 0);
$user_session = $nv_Request->get_Session($module_data . '_user', array());

if (empty($user_session) || $user_session['exam_id'] != $exam_id) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=detail&id=' . $exam_id);
    die();
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE id=" . $exam_id;
$exam = $db->query($sql)->fetch();

if (empty($exam)) {
    die('Exam not found');
}

$page_title = $exam['title'];

// Get Questions
// If num_questions > 0, we should random. But for simplicity and consistency (and keeping questions associated with exam), we will select random if configured, or all.
// Constraint: "Strict Binding" - questions are strictly in this exam.
// We select questions for this exam.
$limit = ($exam['num_questions'] > 0) ? "LIMIT " . $exam['num_questions'] : "";
$sql_q = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $exam_id . " ORDER BY weight ASC, id ASC " . $limit;
// Note: If random is needed per user session, we need to store question order in session. For now, simple ordering.
if ($exam['num_questions'] > 0) {
    $sql_q = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_questions WHERE exam_id=" . $exam_id . " ORDER BY RAND() " . $limit;
}

$questions = array();
$result = $db->query($sql_q);
while ($row = $result->fetch()) {
    $questions[] = $row;
}

$xtpl = new XTemplate('test.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'test');
$xtpl->assign('EXAM', $exam);
$xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

// Calculate end time
// Duration is in minutes.
// We use client side countdown mostly, but server check on save.
// We pass seconds remaining to template.
$elapsed = NV_CURRENTTIME - $user_session['start_time'];
$duration_seconds = $exam['duration'] * 60;
$remaining = $duration_seconds - $elapsed;
if ($remaining < 0) $remaining = 0;

$xtpl->assign('REMAINING_SECONDS', $remaining);

foreach ($questions as $index => $q) {
    $q['index'] = $index + 1;
    $xtpl->assign('Q', $q);

    // Get Answers
    $sql_a = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_answers WHERE question_id=" . $q['id'] . " ORDER BY id ASC"; // Do not select is_correct
    $res_a = $db->query($sql_a);

    if ($q['type'] == 1) { // Radio
        while ($ans = $res_a->fetch()) {
            $xtpl->assign('ANS', $ans);
            $xtpl->parse('main.loop.type_1.loop');
        }
        $xtpl->parse('main.loop.type_1');
    } elseif ($q['type'] == 2) { // Checkbox
        while ($ans = $res_a->fetch()) {
            $xtpl->assign('ANS', $ans);
            $xtpl->parse('main.loop.type_2.loop');
        }
        $xtpl->parse('main.loop.type_2');
    } elseif ($q['type'] == 3) { // Fill
        // Replace [[input]] with <input>
        // We need to know how many inputs to name them uniquely or use array.
        // name="answer[qid][index]"
        $content = $q['title'];
        $count = 0;
        $content = preg_replace_callback('/\[\[input\]\]/', function($matches) use (&$count, $q, $lang_module) {
            $html = '<input type="text" class="form-control d-inline-block w-auto" name="answer['.$q['id'].']['.$count.']" placeholder="'.$lang_module['fill_answer'].'">';
            $count++;
            return $html;
        }, $content);
        $q['content_parsed'] = $content;
        $xtpl->assign('Q_PARSED', $q);
        $xtpl->parse('main.loop.type_3');
    } elseif ($q['type'] == 4) { // Essay
        $xtpl->parse('main.loop.type_4');
    }

    $xtpl->parse('main.loop');
}

if ($exam['has_prediction'] == 1) {
    $xtpl->parse('main.prediction');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
