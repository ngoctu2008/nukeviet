<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

// Handle Composer Install Stream
if ($op == 'install_composer_stream') {
    if (!defined('NV_IS_AJAX')) define('NV_IS_AJAX', true);

    // Check permission - strictly require admin
    if (!defined('NV_IS_ADMIN')) {
        die('Access Denied');
    }

    // Disable buffering and compression
    @ini_set('output_buffering', 'off');
    @ini_set('zlib.output_compression', false);
    while (@ob_end_flush());

    // Headers
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-cache');

    echo '<!DOCTYPE html><html><head><style>body { background: #1e1e1e; color: #00ff00; font-family: "Courier New", Courier, monospace; font-size: 14px; margin: 0; padding: 10px; } .error { color: #ff0000; } .success { color: #00ff00; font-weight: bold; }</style></head><body>';
    echo '<div>Starting installation process...</div>';
    echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
    flush();

    // Increase limits
    @set_time_limit(0);
    @ini_set('memory_limit', '-1');

    $module_dir = NV_ROOTDIR . '/modules/' . $module_file;

    // Check disk space (warn if < 200MB)
    $free_space = @disk_free_space($module_dir);
    if ($free_space !== false && $free_space < 200 * 1024 * 1024) {
        echo '<div class="error">Warning: Low disk space detected (' . nv_convertfromBytes($free_space) . '). Installation may fail.</div>';
        flush();
    }

    if (!function_exists('proc_open')) {
        echo '<div class="error">Error: proc_open() function is disabled. Cannot stream output.</div>';
        die('</body></html>');
    }

    // --- Command Construction Logic (Reused) ---
    putenv('COMPOSER_HOME=' . NV_ROOTDIR . '/tmp/composer');
    $php_bin = (defined('PHP_BINARY') && PHP_BINARY != '') ? PHP_BINARY : 'php';
    $is_windows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');

    // Fix PHP path if it looks like apache
    if (stripos($php_bin, 'httpd') !== false || stripos($php_bin, 'apache') !== false) {
        $possible_paths = $is_windows ?
            ['E:/webs/php/php.exe', dirname($php_bin) . '/php.exe', dirname(dirname($php_bin)) . '/php/php.exe', 'C:/xampp/php/php.exe'] :
            ['/usr/bin/php', '/usr/local/bin/php'];

        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $php_bin = $path;
                break;
            }
        }
        if (stripos($php_bin, 'httpd') !== false) $php_bin = 'php'; // Fallback
    }

    // Locate Composer
    $composer_bin = 'composer';
    $check_cmd = $is_windows ? 'where composer' : 'which composer';
    $check_composer = @shell_exec($check_cmd);

    if (empty($check_composer)) {
        $composer_phar = $module_dir . '/composer.phar';
        if (!file_exists($composer_phar)) {
            echo '<div>Downloading composer.phar...</div>';
            flush();
            $phar_url = 'https://getcomposer.org/download/latest-stable/composer.phar';
            $downloaded = false;
            if (ini_get('allow_url_fopen')) {
                $content = @file_get_contents($phar_url);
                if ($content) { file_put_contents($composer_phar, $content); $downloaded = true; }
            }
            if (!$downloaded && function_exists('curl_version')) {
                $fp = fopen($composer_phar, 'w+');
                $ch = curl_init($phar_url);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_exec($ch);
                if (curl_errno($ch) == 0 && curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) $downloaded = true;
                curl_close($ch);
                fclose($fp);
            }
        }

        if (file_exists($composer_phar)) {
            $composer_bin = '"' . $php_bin . '" "' . $composer_phar . '"';
        } else {
            echo '<div class="error">Could not find or download composer.phar.</div></body></html>';
            die();
        }
    } else {
        $lines = explode("\n", trim($check_composer));
        $composer_bin = '"' . trim($lines[0]) . '"';
    }

    $install_cmd = $composer_bin . ' install --no-dev 2>&1';
    $cmd = $is_windows ? 'cd /d ' . escapeshellarg($module_dir) . ' && ' . $install_cmd : 'cd ' . escapeshellarg($module_dir) . ' && ' . $install_cmd;

    echo '<div>Executing: ' . $cmd . '</div><br>';
    echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
    flush();

    // Stream Execution
    $descriptorspec = array(
       0 => array("pipe", "r"),
       1 => array("pipe", "w"),
       2 => array("pipe", "w")
    );

    $process = proc_open($cmd, $descriptorspec, $pipes);

    if (is_resource($process)) {
        fclose($pipes[0]); // Close stdin

        while (!feof($pipes[1])) {
            $line = fgets($pipes[1]);
            if ($line) {
                echo '<div>' . htmlspecialchars($line) . '</div>';
                echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
                flush();
                @ob_flush();
            }
        }
        fclose($pipes[1]);
        fclose($pipes[2]);

        $return_value = proc_close($process);

        if ($return_value === 0 && file_exists($module_dir . '/vendor/autoload.php')) {
            echo '<br><div class="success">Installation Completed Successfully!</div>';
            echo '<script>if(window.parent && window.parent.installComplete) window.parent.installComplete(true);</script>';
        } else {
            echo '<br><div class="error">Installation Failed with code ' . $return_value . '.</div>';
            echo '<script>if(window.parent && window.parent.installComplete) window.parent.installComplete(false);</script>';
        }
    } else {
        echo '<div class="error">Failed to launch process.</div>';
    }

    echo '</body></html>';
    die();
}

$page_title = $lang_module['config'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
// Correct Form Action to include $op (which defaults to main but explicit is better)
$xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('CHECKSS', NV_CHECK_SESSION);
$xtpl->assign('STREAM_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=install_composer_stream");


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
        // FIX: Use NV_LANG_DATA instead of array $lang_global
        $sth->bindValue(':lang', NV_LANG_DATA);
        $sth->bindValue(':module', $module_name);
        $sth->bindValue(':config_name', $config_name);
        $sth->bindValue(':config_value', (string)$config_value);
        $sth->execute();
    }

    $nv_Cache->delMod($module_name);
    // Use nv_redirect_location for proper redirect
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

// Check if vendor exists
if (!file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/vendor/autoload.php')) {
    $xtpl->assign('ERROR_DEPENDENCY', 'Cảnh báo: Thư viện chưa được cài đặt!');
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
