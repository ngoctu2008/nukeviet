<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_popup')) {
    function nv_block_popup($block_config)
    {
        global $db, $db_config, $module_name, $user_info, $site_mods, $nv_Request;

        // Determine current environment
        $current_time = NV_CURRENTTIME;
        $is_mobile = (defined('NV_IS_MOBILE') && NV_IS_MOBILE === true);
        $device_type = $is_mobile ? 'mobile' : 'desktop';

        // Groups
        $user_groups = !empty($user_info['in_groups']) ? $user_info['in_groups'] : [4]; // 4 is usually guest

        // SQL to fetch active popups
        // Note: Filtering JSON fields in SQL is hard in MyISAM/Old MySQL.
        // Better to fetch candidates and filter in PHP.
        // We filter by Time and Status and Device (simple string check) in SQL to reduce load.

        $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_popup_rows
                WHERE status=1
                AND (begin_time = 0 OR begin_time <= " . $current_time . ")
                AND (end_time = 0 OR end_time >= " . $current_time . ")
                ORDER BY priority DESC";

        $result = $db->query($sql);
        $candidates = [];

        while ($row = $result->fetch()) {
            // 1. Device Filter
            if ($row['device_type'] != 'all' && $row['device_type'] != $device_type) {
                continue;
            }

            // 2. Module Filter
            $display_pages = json_decode($row['display_pages'], true);
            if (!empty($display_pages) && !in_array($module_name, $display_pages)) {
                continue;
            }

            // 3. Group Filter
            $allowed_groups = json_decode($row['user_groups'], true);
            if (!empty($allowed_groups)) {
                $intersect = array_intersect($user_groups, $allowed_groups);
                if (empty($intersect)) {
                    continue;
                }
            }

            // 4. Cookie Filter
            // If frequency > 0, check cookie.
            // Cookie name: nv_popup_{id}
            if ($row['frequency'] > 0) {
                $cookie_name = 'nv_popup_' . $row['id'];
                if ($nv_Request->isset_request($cookie_name, 'cookie')) {
                    continue;
                }
            } else {
                // If frequency == 0 (once per session)
                // We use session cookie (expires when browser closes).
                // Same cookie name, but logic in JS might set it differently?
                // Or we check here. If standard cookie is present, skip.
                $cookie_name = 'nv_popup_' . $row['id'];
                if ($nv_Request->isset_request($cookie_name, 'cookie')) {
                    continue;
                }
            }

            // Group by Position/Type to ensure we don't spam multiple modals
            // Types: modal, bar_top, bar_bottom, corner_left, corner_right
            $type = $row['type'];
            if (!isset($candidates[$type])) {
                $candidates[$type] = $row;
            }
        }

        if (empty($candidates)) {
            return '';
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/popup/block_popup.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/popup/block_popup.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block_popup.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/popup');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);

        foreach ($candidates as $row) {
            $row['trigger_config'] = json_decode($row['trigger_config'], true);
            $row['trigger_json'] = json_encode($row['trigger_config']);

            $xtpl->assign('ROW', $row);

            // Assign specific template block based on type
            if ($row['type'] == 'modal') {
                $xtpl->parse('main.modal');
            } else {
                $xtpl->assign('POSITION_CLASS', 'popup-' . $row['type']);
                $xtpl->parse('main.banner');
            }
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_popup($block_config);
}
