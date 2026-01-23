<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_HUYEN_HOC')) {
    die('Stop!!!');
}

function nv_theme_huyen_hoc_main($module_name)
{
    global $module_info, $lang_module, $module_file, $op;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_huyen_hoc_lo_ban($result, $length)
{
    global $module_info, $lang_module, $module_file, $op, $module_name;

    $xtpl = new XTemplate('lo_ban.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('LENGTH', $length > 0 ? $length : '');

    if (!empty($result)) {
        foreach ($result as $type => $info) {
            $info['id'] = $type;
            $info['color'] = $info['good'] ? 'red' : 'black';
            $info['result_text'] = $info['good'] ? 'Tốt' : 'Xấu';
            $xtpl->assign('RULER', $info);
            $xtpl->parse('main.result.ruler');
        }
        $xtpl->parse('main.result');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_huyen_hoc_tu_vi($data, $input)
{
    global $module_info, $lang_module, $module_file, $op, $module_name;

    $xtpl = new XTemplate('tu_vi.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('INPUT', $input);

    // Assign selected hour
    $xtpl->assign('SELECTED_' . $input['h'], 'selected="selected"');

    if (!empty($data['chart'])) {
        $xtpl->assign('INFO', $data['chart']['info']);
        $xtpl->assign('DEBUG_DATA', print_r($data, true));

        // Output Palaces
        // We need to sort or map them to the grid?
        // For now, simple loop in index order (0..11)
        foreach ($data['chart'] as $key => $palace) {
            if (is_numeric($key)) {
                $xtpl->assign('PALACE', $palace);

                foreach ($palace['stars'] as $star) {
                    // Assign color based on type or element if available
                    $star['color'] = ($star['type'] == 1) ? 'red' : 'black';
                    $xtpl->assign('STAR', $star);
                    $xtpl->parse('main.result.palace.star');
                }

                $xtpl->parse('main.result.palace');
            }
        }

        $xtpl->parse('main.result');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
