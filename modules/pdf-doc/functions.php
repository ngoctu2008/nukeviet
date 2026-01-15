<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_PDF_DOC', true);

// Nạp Composer Autoload nếu tồn tại
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

function nv_pdf_doc_check_ext($ext)
{
    $allow_ext = array('pdf', 'doc', 'docx');
    return in_array($ext, $allow_ext);
}

function nv_pdf_doc_check_perm($module_name, $module_config)
{
    global $user_info;
    $groups_use = isset($module_config[$module_name]['groups_use']) ? explode(',', $module_config[$module_name]['groups_use']) : array();

    if (empty($groups_use)) {
        return true; // No restriction if not configured? Or restrict? Let's assume open if empty to avoid lockout on install.
    }

    return nv_user_in_groups($groups_use);
}
