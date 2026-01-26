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

function nv_theme_huyen_hoc_xem_ngay($content) {
    // Handled by controller
}

function nv_theme_huyen_hoc_lo_ban($result, $length)
{
    global $module_info, $lang_module, $module_file, $op, $module_name;

    $xtpl = new XTemplate('lo-ban.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('LENGTH', $length > 0 ? $length : '');

    if (!empty($result)) {
        foreach ($result as $type => $info) {
            $info['id'] = $type;
            $info['color'] = $info['good'] ? '#d9534f' : '#333';
            $info['result_text'] = $info['good'] ? 'TỐT' : 'XẤU';
            $info['result_class'] = $info['good'] ? 'red' : 'black';
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

    $xtpl = new XTemplate('tu-vi.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('INPUT', $input);

    // Assign selected hour
    $xtpl->assign('SELECTED_' . $input['h'], 'selected="selected"');
    $xtpl->assign('SELECTED_G_' . $input['g'], 'checked="checked"');

    if (!empty($data['laso'])) {
        $laso = $data['laso'];

        // Thien Ban
        if (isset($laso['thien_ban'])) {
            $xtpl->assign('THIEN_BAN', $laso['thien_ban']);
        }

        // Dia Ban (Palaces)
        if (isset($laso['dia_ban'])) {
            foreach ($laso['dia_ban'] as $key => $palace) {
                $xtpl->assign('PALACE', $palace);

                // Tuan/Triet
                if ($palace['tuan']) $xtpl->parse('main.result.palace.tuan');
                if ($palace['triet']) $xtpl->parse('main.result.palace.triet');
                if ($palace['tieu_van']) $xtpl->parse('main.result.palace.tieu_van');

                // Chinh Tinh
                if (!empty($palace['chinh_tinh'])) {
                    foreach ($palace['chinh_tinh'] as $star) {
                        $xtpl->assign('STAR', $star);
                        $xtpl->parse('main.result.palace.chinh_tinh');
                    }
                }

                $xtpl->parse('main.result.palace');
            }

            // Loop again for Luan Giai tab (palace_luan)
            foreach ($laso['dia_ban'] as $key => $palace) {
                $xtpl->assign('PALACE', $palace);
                $xtpl->parse('main.result.palace_luan');
            }
        }

        $xtpl->parse('main.result');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
