<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_chat_ai_widget_block')) {
    function nv_chat_ai_widget_block($block_config)
    {
        global $nv_Cache, $global_config, $site_mods, $db_config, $db, $module_name, $module_info, $module_file;

        $module = $block_config['module'];

        // Determine template path
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module . '/block_chat_widget.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module . '/block_chat_widget.tpl')) {
             $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block_chat_widget.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module);

        // Load Language
        // Try to load module language if not already loaded (blocks might run outside module context)
        $lang_module = array();
        $lang_global = $global_config['site_lang'];
        if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . $lang_global . '.php')) {
            include NV_ROOTDIR . '/modules/' . $module . '/language/' . $lang_global . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/en.php')) {
             include NV_ROOTDIR . '/modules/' . $module . '/language/en.php';
        }

        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('MODULE_NAME', $module);
        $xtpl->assign('LANG', $lang_module);

        // Pass Language strings to JS
        $js_lang = [
            'type_message' => $lang_module['type_message'] ?? 'Type message...',
            'error_empty' => $lang_module['error_empty'] ?? 'Error'
        ];
        $xtpl->assign('JS_LANG', json_encode($js_lang));

        // Get Config for Position
        $module_data_config = 'chat_ai';
        // Try to find if installed under a different alias
        foreach ($site_mods as $mod => $info) {
            if ($info['module_file'] == $module) {
                $module_data_config = str_replace('-', '_', $info['module_data']);
                break;
            }
        }

        $sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_config . "_config WHERE config_name IN ('widget_bottom', 'widget_right')";
        $result = $db->query($sql);
        $widget_config = ['widget_bottom' => 20, 'widget_right' => 20];

        while ($row = $result->fetch()) {
            $widget_config[$row['config_name']] = intval($row['config_value']);
        }

        $xtpl->assign('WIDGET_BOTTOM', $widget_config['widget_bottom']);
        $xtpl->assign('WIDGET_RIGHT', $widget_config['widget_right']);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_chat_ai_widget_block($block_config);
}
