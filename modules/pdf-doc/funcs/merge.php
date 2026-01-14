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

    if (isset($_FILES['upload_file']) && !empty($_FILES['upload_file']['name'][0])) {
        $files = $_FILES['upload_file'];
        $file_count = count($files['name']);

        $upload_dir = NV_ROOTDIR . '/uploads/' . $module_name . '/temp';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
            file_put_contents($upload_dir . '/.htaccess', 'Deny from all');
        }

        $pdf = new \setasign\Fpdi\Fpdi();
        $processed_files = 0;

        try {
            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === 0) {
                     $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                     if ($ext != 'pdf') continue;

                     $filepath = $files['tmp_name'][$i];
                     $pageCount = $pdf->setSourceFile($filepath);
                     for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $templateId = $pdf->importPage($pageNo);
                        $size = $pdf->getTemplateSize($templateId);
                        $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                        $pdf->useTemplate($templateId);
                     }
                     $processed_files++;
                }
            }

            if ($processed_files > 0) {
                $out_filename = nv_genpass(10) . '.pdf';
                $out_filepath = $upload_dir . '/' . $out_filename;
                $pdf->Output('F', $out_filepath);

                 // Log
                $db_table_name = str_replace('-', '_', $module_data);
                $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $db_table_name . "_logs (action_type, file_name, action_time, ip, user_id) VALUES (:action_type, :file_name, :action_time, :ip, :user_id)";
                $data_insert = array(
                    ':action_type' => 'merge',
                    ':file_name' => 'merged_' . $processed_files . '_files.pdf',
                    ':action_time' => NV_CURRENTTIME,
                    ':ip' => $client_info['ip'],
                    ':user_id' => $user_info['userid'] ?? 0
                );
                $db->insert_id($sql, 'id', $data_insert);

                $download_link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=download&file=' . $out_filename . '&name=merged';

                $response['status'] = 'ok';
                $response['mess'] = $lang_module['success'];
                $response['link'] = $download_link;
            } else {
                $response['mess'] = 'No valid PDF files to merge';
            }

        } catch (Exception $e) {
            $response['mess'] = $e->getMessage();
        }
    }

    die(json_encode($response));
}

$page_title = $lang_module['merge'];
$xtpl = new XTemplate('merge.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
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
