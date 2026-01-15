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

    // Check dependencies
    if (!class_exists('\PhpOffice\PhpWord\IOFactory') || !class_exists('\Dompdf\Dompdf')) {
        $response['mess'] = 'Libraries not found. Please run "composer install" in ' . NV_ROOTDIR . '/modules/' . $module_file;
        die(json_encode($response));
    }

    if (isset($_FILES['upload_file']) && is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
        $file = $_FILES['upload_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, array('doc', 'docx'))) {
            $response['mess'] = $lang_module['error_file_type'];
            die(json_encode($response));
        }

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
                // Load Word file
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filepath);

                // Save as HTML
                $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
                $html_content = '';
                // Capture output to string
                ob_start();
                $htmlWriter->save('php://output');
                $html_content = ob_get_clean();

                // Add UTF-8 meta and font
                $html_content = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style>body { font-family: "DejaVu Sans", sans-serif; }</style></head><body>' . $html_content . '</body></html>';

                // Convert to PDF using Dompdf
                $options = new \Dompdf\Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true);
                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($html_content);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                $pdf_filename = str_replace(array('.docx', '.doc'), '.pdf', $file['name']);
                $out_filename = nv_genpass(10) . '.pdf';
                $out_filepath = $upload_dir . '/' . $out_filename;

                file_put_contents($out_filepath, $dompdf->output());

                // Log
                $db_table_name = str_replace('-', '_', $module_data);
                $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $db_table_name . "_logs (action_type, file_name, action_time, ip, user_id) VALUES (:action_type, :file_name, :action_time, :ip, :user_id)";
                $data_insert = array(
                    ':action_type' => 'word2pdf',
                    ':file_name' => $file['name'],
                    ':action_time' => NV_CURRENTTIME,
                    ':ip' => $client_info['ip'],
                    ':user_id' => $user_info['userid'] ?? 0
                );
                $db->insert_id($sql, 'id', $data_insert);

                $download_link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=download&file=' . $out_filename . '&name=' . urlencode($pdf_filename);

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

$page_title = $lang_module['word2pdf'];
$xtpl = new XTemplate('form_upload.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('ACCEPT_EXT', '.doc,.docx');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
