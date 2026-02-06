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
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_FILE', $module_file);
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

        // Overview & Limit (from luan_giai_tong_quan)
        if (isset($laso['luan_giai_tong_quan'])) {
            // Overview
            if (!empty($laso['luan_giai_tong_quan']['overview'])) {
                foreach ($laso['luan_giai_tong_quan']['overview'] as $ov) {
                    $xtpl->assign('OVERVIEW', $ov);
                    $xtpl->parse('main.result.overview');
                }
            }
            // Limit
            if (!empty($laso['luan_giai_tong_quan']['limit'])) {
                foreach ($laso['luan_giai_tong_quan']['limit'] as $lim) {
                    $xtpl->assign('LIMIT', $lim);
                    $xtpl->parse('main.result.limit');
                }
            }
        }

        // Dia Ban (Palaces)
        if (isset($laso['dia_ban'])) {
            foreach ($laso['dia_ban'] as $key => $palace) {
                $xtpl->assign('PALACE', $palace);

                // Tuan/Triet
                if ($palace['tuan']) $xtpl->parse('main.result.palace.tuan');
                if ($palace['triet']) $xtpl->parse('main.result.palace.triet');
                if ($palace['tieu_van']) $xtpl->parse('main.result.palace.tieu_van');

                // Sanitize function
                $sanitizeStar = function($s) {
                    $s['name'] = htmlspecialchars($s['name'], ENT_QUOTES);
                    if (isset($s['content'])) $s['content'] = htmlspecialchars($s['content'], ENT_QUOTES);
                    if (isset($s['element'])) $s['element'] = htmlspecialchars($s['element'], ENT_QUOTES);
                    return $s;
                };

                // Chinh Tinh
                if (!empty($palace['chinh_tinh'])) {
                    foreach ($palace['chinh_tinh'] as $star) {
                        $xtpl->assign('STAR', $sanitizeStar($star));
                        $xtpl->parse('main.result.palace.chinh_tinh');
                    }
                }

                // Phu Tinh Tot
                if (!empty($palace['phu_tinh_tot'])) {
                    foreach ($palace['phu_tinh_tot'] as $star) {
                        $xtpl->assign('STAR', $sanitizeStar($star));
                        $xtpl->parse('main.result.palace.phu_tinh_tot');
                    }
                }

                // Phu Tinh Xau
                if (!empty($palace['phu_tinh_xau'])) {
                    foreach ($palace['phu_tinh_xau'] as $star) {
                        $xtpl->assign('STAR', $sanitizeStar($star));
                        $xtpl->parse('main.result.palace.phu_tinh_xau');
                    }
                }

                $xtpl->parse('main.result.palace');
            }

            // Loop again for Luan Giai tab (palace_luan)
            foreach ($laso['dia_ban'] as $key => $palace) {
                $xtpl->assign('PALACE', $palace);

                if (!empty($palace['luan_giai'])) {
                    foreach ($palace['luan_giai'] as $reading) {
                        $xtpl->assign('CONTENT', $reading);
                        $xtpl->parse('main.result.palace_luan.content');
                    }
                } else {
                    $xtpl->parse('main.result.palace_luan.empty');
                }

                $xtpl->parse('main.result.palace_luan');
            }
        }

        // Structured Report (Binh Giai Chi Tiet)
        if (isset($laso['structured_report'])) {
            $rep = $laso['structured_report'];

            // Section 1
            $xtpl->assign('SEC1_INFO', $rep['section_1']['info']);
            foreach ($rep['section_1']['am_duong'] as $line) {
                $xtpl->assign('SEC1_AD', $line);
                $xtpl->parse('main.result.report.sec1.am_duong');
            }
            $xtpl->assign('SEC1_MENH', $rep['section_1']['menh_than']['menh']);
            $xtpl->assign('SEC1_THAN', $rep['section_1']['menh_than']['than']);
            $xtpl->parse('main.result.report.sec1');

            // Section 2
            foreach ($rep['section_2'] as $p) {
                $xtpl->assign('SEC2_PNAME', $p['name']);

                // Chinh Tinh
                if (!empty($p['reading']['chinh_tinh'])) {
                    foreach ($p['reading']['chinh_tinh'] as $r) {
                        $xtpl->assign('READING', $r);
                        $xtpl->parse('main.result.report.sec2.reading.chinh_tinh');
                    }
                }
                // Phu Tinh
                if (!empty($p['reading']['phu_tinh'])) {
                    foreach ($p['reading']['phu_tinh'] as $r) {
                        $xtpl->assign('READING', $r);
                        $xtpl->parse('main.result.report.sec2.reading.phu_tinh');
                    }
                }
                // General
                if (!empty($p['reading']['general'])) {
                    foreach ($p['reading']['general'] as $r) {
                        $xtpl->assign('READING', $r);
                        $xtpl->parse('main.result.report.sec2.reading.general');
                    }
                }

                $xtpl->parse('main.result.report.sec2.reading'); // ensure block exists if empty?
                $xtpl->parse('main.result.report.sec2');
            }

            // Section 3
            foreach ($rep['section_3'] as $line) {
                $xtpl->assign('SEC3_LINE', $line);
                $xtpl->parse('main.result.report.sec3.line');
            }
            $xtpl->parse('main.result.report.sec3');

            $xtpl->parse('main.result.report');
        }

        $xtpl->parse('main.result');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_huyen_hoc_gieo_que()
{
    global $module_info, $lang_module, $module_file, $op, $module_name;

    $xtpl = new XTemplate('gieo-que.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_FILE', $module_file);

    $xtpl->parse('main');
    return $xtpl->text('main');
}
