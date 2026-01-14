<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_MOD_PDF_DOC')) {
    die('Stop!!!');
}

if (!nv_pdf_doc_check_perm($module_name, $module_config)) {
    die('Access Denied');
}

if ($nv_Request->isset_request('ajax', 'post')) {
    $response = array('status' => 'error', 'mess' => $lang_module['error_upload']);

    if (isset($_FILES['upload_file']) && is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
        $file = $_FILES['upload_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($ext != 'pdf') {
            $response['mess'] = $lang_module['error_file_type'];
            die(json_encode($response));
        }

        // Check max size
        $max_size = isset($module_config[$module_name]['upload_max_size']) ? $module_config[$module_name]['upload_max_size'] * 1024 * 1024 : 5 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            $response['mess'] = $lang_module['error_file_size'];
            die(json_encode($response));
        }

        $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/temp';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
            file_put_contents($upload_dir . '/.htaccess', 'Deny from all');
        }

        $filename = nv_genpass(10) . '.' . $ext;
        $filepath = $upload_dir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            try {
                // Parse PDF
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($filepath);
                $text = $pdf->getText();

                // Create Word
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection();
                $section->addText($text);

                $docx_filename = str_replace('.pdf', '.docx', $file['name']);
                $out_filename = nv_genpass(10) . '.docx';
                $out_filepath = $upload_dir . '/' . $out_filename;

                $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($out_filepath);

                // Log
                $db_table_name = str_replace('-', '_', $module_data);
                $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $db_table_name . "_logs (action_type, file_name, action_time, ip, user_id) VALUES (:action_type, :file_name, :action_time, :ip, :user_id)";
                $data_insert = array(
                    ':action_type' => 'pdf2word',
                    ':file_name' => $file['name'],
                    ':action_time' => NV_CURRENTTIME,
                    ':ip' => $client_info['ip'],
                    ':user_id' => $user_info['userid'] ?? 0
                );
                $db->insert_id($sql, 'id', $data_insert);

                $download_link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=download&file=' . $out_filename . '&name=' . urlencode($docx_filename);

                $response['status'] = 'ok';
                $response['mess'] = $lang_module['success'];
                $response['link'] = $download_link;

            } catch (Exception $e) {
                $response['mess'] = $e->getMessage();
            }
        }
    }

    die(json_encode($response));
}

$page_title = $lang_module['pdf2word'];
$xtpl = new XTemplate('form_upload.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('ACCEPT_EXT', '.pdf');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
