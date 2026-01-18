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

    $module_dir = NV_ROOTDIR . '/modules/' . $module_file;
    if (is_dir($module_dir)) {
        if (!function_exists('exec')) {
             $xtpl->assign('INSTALL_MESSAGE', 'Error: exec() function is disabled. Please run "composer install" manually via terminal.');
             $xtpl->assign('INSTALL_CLASS', 'alert-danger');
        } else {
            $output = array();
            $return_var = 0;

            // Setup environment - remove if causing issues, but usually helpful
            putenv('COMPOSER_HOME=' . NV_ROOTDIR . '/tmp/composer');

            // Determine PHP executable
            $php_bin = defined('PHP_BINARY') ? PHP_BINARY : 'php';

            // Check if composer is globally installed
            $composer_bin = 'composer';
            // Use 'where' on Windows, 'which' on Linux
            $check_cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'where composer' : 'which composer';
            $check_composer = @shell_exec($check_cmd);

            if (empty($check_composer)) {
                // Fallback to local composer.phar
                $composer_phar = $module_dir . '/composer.phar';
                if (!file_exists($composer_phar)) {
                    // Download composer.phar
                    $phar_url = 'https://getcomposer.org/download/latest-stable/composer.phar';
                    $downloaded = false;

                    if (ini_get('allow_url_fopen')) {
                        $content = @file_get_contents($phar_url);
                        if ($content) {
                            file_put_contents($composer_phar, $content);
                            $downloaded = true;
                        }
                    }

                    if (!$downloaded && function_exists('curl_version')) {
                        $fp = fopen($composer_phar, 'w+');
                        $ch = curl_init($phar_url);
                        curl_setopt($ch, CURLOPT_FILE, $fp);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_exec($ch);
                        if (curl_errno($ch) == 0 && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                            $downloaded = true;
                        }
                        curl_close($ch);
                        fclose($fp);
                    }
                }

                if (file_exists($composer_phar)) {
                    $composer_bin = $php_bin . ' ' . escapeshellarg($composer_phar);
                } else {
                    $output[] = "Could not find or download composer.phar.";
                    $return_var = 1;
                }
            } else {
                 $lines = explode("\n", trim($check_composer));
                 $composer_bin = '"' . trim($lines[0]) . '"';
            }

            if ($return_var === 0) {
                // Run install
                $cmd = 'cd ' . escapeshellarg($module_dir) . ' && ' . $composer_bin . ' install --no-dev 2>&1';
                @exec($cmd, $output, $return_var);
            }

            if ($return_var === 0 && file_exists($module_dir . '/vendor/autoload.php')) {
                $xtpl->assign('INSTALL_MESSAGE', $lang_module['install_composer_success']);
                $xtpl->assign('INSTALL_CLASS', 'alert-success');
            } else {
                $error_detail = implode("<br>", $output);
                $xtpl->assign('INSTALL_MESSAGE', $lang_module['install_composer_error'] . '<br>Command: ' . $cmd . '<br><pre>' . $error_detail . '</pre>');
                $xtpl->assign('INSTALL_CLASS', 'alert-danger');
            }
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
    $xtpl->assign('ERROR_DEPENDENCY', 'Cảnh báo: Thư viện chưa được cài đặt! <br>Vui lòng chạy lệnh sau trong thư mục <strong>' . NV_ROOTDIR . '/modules/' . $module_file . '</strong>:<br><code>composer install</code><br>Hoặc nhấn nút bên dưới để thử cài đặt tự động.');
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
