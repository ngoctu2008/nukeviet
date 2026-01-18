<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['config'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('CHECKSS', NV_CHECK_SESSION);

// Handle Composer Install
if ($nv_Request->isset_request('install_composer', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if ($checkss != NV_CHECK_SESSION) {
        die('Security Violation');
    }

    // Try to run composer install
    $module_dir = NV_ROOTDIR . '/modules/' . $module_file;
    if (is_dir($module_dir)) {
        $output = array();
        $return_var = 0;

        // Put env to help composer find home
        putenv('COMPOSER_HOME=' . NV_ROOTDIR . '/tmp/composer');

        // Attempt to run composer. This assumes 'composer' is in the system path.
        // Redirect stderr to stdout to capture errors
        exec('cd ' . escapeshellarg($module_dir) . ' && composer install --no-dev 2>&1', $output, $return_var);

        if ($return_var === 0 && file_exists($module_dir . '/vendor/autoload.php')) {
            $xtpl->assign('INSTALL_MESSAGE', $lang_module['install_composer_success']);
            $xtpl->assign('INSTALL_CLASS', 'alert-success');
        } else {
            $error_detail = implode("<br>", $output);
            $xtpl->assign('INSTALL_MESSAGE', $lang_module['install_composer_error'] . '<br><pre>' . $error_detail . '</pre>');
            $xtpl->assign('INSTALL_CLASS', 'alert-danger');
        }
        $xtpl->parse('main.install_result');
    }
}

if ($nv_Request->isset_request('save', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if ($checkss != NV_CHECK_SESSION) {
        die('Security Violation');
    }

    $cfg = array();
    $cfg['upload_max_size'] = $nv_Request->get_int('upload_max_size', 'post', 5);
    $cfg['cleanup_time'] = $nv_Request->get_int('cleanup_time', 'post', 30);
    $cfg['groups_use'] = $nv_Request->get_array('groups_use', 'post', array());

    foreach ($cfg as $config_name => $config_value) {
        if (is_array($config_value)) {
            $config_value = implode(',', $config_value);
        }

        $sth = $db->prepare("REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES (:lang, :module, :config_name, :config_value)");
        $sth->bindValue(':lang', $lang_global);
        $sth->bindValue(':module', $module_name);
        $sth->bindValue(':config_name', $config_name);
        $sth->bindValue(':config_value', (string)$config_value);
        $sth->execute();
    }

    $nv_Cache->delMod($module_name);
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

// Check if vendor exists
if (!file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php')) {
    $xtpl->assign('ERROR_DEPENDENCY', 'Cảnh báo: Thư viện chưa được cài đặt! Vui lòng chạy "composer install" trong thư mục module hoặc nhấn nút bên dưới.');
    $xtpl->parse('main.error_dependency');
}

$config = array();
$config['upload_max_size'] = isset($module_config[$module_name]['upload_max_size']) ? $module_config[$module_name]['upload_max_size'] : 5;
$config['cleanup_time'] = isset($module_config[$module_name]['cleanup_time']) ? $module_config[$module_name]['cleanup_time'] : 30;
$groups_use = isset($module_config[$module_name]['groups_use']) ? explode(',', $module_config[$module_name]['groups_use']) : array();

$xtpl->assign('CONFIG', $config);

// Groups
$groups_list = nv_groups_list();
foreach ($groups_list as $group_id => $group_title) {
    $xtpl->assign('GROUP', array(
        'id' => $group_id,
        'title' => $group_title,
        'checked' => in_array($group_id, $groups_use) ? 'checked="checked"' : ''
    ));
    $xtpl->parse('main.group');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
