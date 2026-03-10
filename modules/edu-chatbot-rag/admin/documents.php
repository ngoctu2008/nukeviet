<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['documents'];
$action = $nv_Request->get_string('action', 'get', '');
$id = $nv_Request->get_int('id', 'get', 0);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/functions.php';

// Get current config for API keys
$sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$result = $db->query($sql);
$mod_config = [];
while ($row = $result->fetch()) {
    $mod_config[$row['config_name']] = $row['config_value'];
}

if ($action == 'delete' && $id > 0) {
    if (!check_sess_token($nv_Request->get_string('checksess', 'get', ''))) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_token']);
    }

    $sql = "SELECT file_path FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents WHERE id=" . $id;
    $row = $db->query($sql)->fetch();
    if ($row) {
        if (file_exists(NV_ROOTDIR . '/' . $row['file_path'])) {
            unlink(NV_ROOTDIR . '/' . $row['file_path']);
        }
        $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents WHERE id=" . $id);
    }
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

if ($action == 'vectorize' && $id > 0) {
    if (!check_sess_token($nv_Request->get_string('checksess', 'get', ''))) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_token']);
    }

    $sql = "SELECT id, title, file_path FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents WHERE id=" . $id;
    $row = $db->query($sql)->fetch();

    if ($row && file_exists(NV_ROOTDIR . '/' . $row['file_path'])) {
        $ext = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
        $text = '';

        if ($ext == 'txt') {
            $text = file_get_contents(NV_ROOTDIR . '/' . $row['file_path']);
        } elseif ($ext == 'pdf') {
            require_once NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php';
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile(NV_ROOTDIR . '/' . $row['file_path']);
            $text = $pdf->getText();
        }

        if (!empty($text)) {
            $chunks = nv_edu_chatbot_rag_chunk_text($text);
            $vectors = [];

            foreach ($chunks as $index => $chunk) {
                if (empty(trim($chunk))) continue;

                $embedding = nv_edu_chatbot_rag_get_embedding($chunk, $mod_config['openai_api_key']);
                if ($embedding) {
                    $vector_id = 'doc_' . $id . '_chunk_' . $index;
                    $vectors[] = [
                        'id' => $vector_id,
                        'values' => $embedding,
                        'metadata' => [
                            'doc_id' => $id,
                            'title' => $row['title'],
                            'text' => $chunk
                        ]
                    ];
                }
            }

            if (!empty($vectors)) {
                $upsert_result = nv_edu_chatbot_rag_pinecone_upsert($vectors, $mod_config['pinecone_api_key'], $mod_config['pinecone_url']);
                if ($upsert_result) {
                    $db->query("UPDATE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents SET is_vectorized=1 WHERE id=" . $id);
                    $msg = $lang_module['vectorize_success'];
                } else {
                    $msg = $lang_module['vectorize_error'];
                }
            } else {
                $msg = $lang_module['vectorize_error'] . " (No vectors generated)";
            }
        } else {
            $msg = $lang_module['vectorize_error'] . " (Empty file)";
        }
    } else {
         $msg = $lang_module['vectorize_error'] . " (File not found)";
    }

    nv_info_die($lang_global['info'], $lang_global['info'], $msg, 3, NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

$error = '';
if ($nv_Request->isset_request('save', 'post')) {
    if (!check_sess_token($nv_Request->get_string('checksess', 'post', ''))) {
        $error = $lang_global['error_token'];
    } else {
        $title = $nv_Request->get_title('title', 'post', '');

        if (isset($_FILES['doc_file']) && is_uploaded_file($_FILES['doc_file']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['doc_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['txt', 'pdf'])) {
                $upload_dir = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload;
                if (!is_dir($upload_dir)) {
                    nv_mkdir($upload_dir, NV_ROOTDIR . '/' . NV_UPLOADS_DIR, true);
                }

                $filename = md5(uniqid(rand(), true)) . '.' . $ext;
                $file_path = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $filename;

                if (move_uploaded_file($_FILES['doc_file']['tmp_name'], NV_ROOTDIR . '/' . $file_path)) {
                    $stmt = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents (title, file_path, is_vectorized, add_time) VALUES (:title, :file_path, 0, " . NV_CURRENTTIME . ")");
                    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                    $stmt->bindParam(':file_path', $file_path, PDO::PARAM_STR);
                    $stmt->execute();

                    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                    die();
                } else {
                    $error = $lang_module['upload_error'];
                }
            } else {
                $error = "Only TXT and PDF files are allowed.";
            }
        } else {
            $error = $lang_module['upload_error'];
        }
    }
}

$xtpl = new XTemplate('documents.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('NV_CHECK_SESSION', nv_genpass());

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents ORDER BY add_time DESC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['add_time'] = nv_date('d/m/Y H:i', $row['add_time']);
    $row['status_text'] = $row['is_vectorized'] ? '<span class="label label-success">' . $lang_module['doc_vectorized'] . '</span>' : '<span class="label label-danger">' . $lang_module['doc_not_vectorized'] . '</span>';
    $row['vectorize_url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=vectorize&amp;id=" . $row['id'] . "&amp;checksess=" . nv_genpass();
    $row['delete_url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;action=delete&amp;id=" . $row['id'] . "&amp;checksess=" . nv_genpass();

    $xtpl->assign('ROW', $row);
    if (!$row['is_vectorized']) {
        $xtpl->parse('main.row.vectorize');
    }
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
