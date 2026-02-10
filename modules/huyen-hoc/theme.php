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

    $xtpl->assign('SELECTED_' . $input['h'], 'selected="selected"');
    $xtpl->assign('SELECTED_G_' . $input['g'], 'checked="checked"');

    if (!empty($data['laso'])) {
        $laso = $data['laso'];

        // Thien Ban Info
        if (isset($laso['thien_ban'])) {
            $xtpl->assign('THIEN_BAN', $laso['thien_ban']);
            $xtpl->assign('META', $laso['meta']);
        }

        // Score Box (Overview Tab)
        if (isset($laso['structured_report']['score'])) {
            $xtpl->assign('SCORE', $laso['structured_report']['score']);
            $xtpl->parse('main.result.score_box');
        }

        // Overview (Overview Tab)
        if (isset($laso['luan_giai_tong_quan']['overview'])) {
            foreach ($laso['luan_giai_tong_quan']['overview'] as $ov) {
                $xtpl->assign('OVERVIEW', $ov);
                $xtpl->parse('main.result.overview');
            }
        }

        // Palaces (Grid View)
        if (isset($laso['dia_ban']) && is_array($laso['dia_ban'])) {
            foreach ($laso['dia_ban'] as $key => $palace) {
                $xtpl->assign('PALACE', $palace);

                if (!empty($palace['tuan'])) $xtpl->parse('main.result.palace.tuan');
                if (!empty($palace['triet'])) $xtpl->parse('main.result.palace.triet');
                if (!empty($palace['tieu_van'])) $xtpl->parse('main.result.palace.tieu_van');

                foreach (['chinh_tinh', 'phu_tinh_tot', 'phu_tinh_xau'] as $starType) {
                    if (!empty($palace[$starType])) {
                        foreach ($palace[$starType] as $star) {
                            $xtpl->assign('STAR', $star);
                            $xtpl->parse("main.result.palace.$starType");
                        }
                    }
                }

                if (!empty($palace['vong_trang_sinh'])) {
                     // Need star object for loop? No, just name in text.
                     // Current tpl uses {PALACE.vong_trang_sinh} in footer, but also loops?
                     // Let's stick to simple display in footer.
                }

                $xtpl->parse('main.result.palace');
            }
        }

        // Structured Report (Detail Tab)
        if (isset($laso['structured_report'])) {
            $rep = $laso['structured_report'];

            // Section 1: Overview
            if (isset($rep['section_1'])) {
                $s1 = $rep['section_1'];
                $xtpl->assign('SEC1', [
                    'info' => $s1['info'],
                    'am_duong' => $s1['am_duong'],
                    'cuc_menh' => $s1['cuc_menh'],
                    'menh_text' => $s1['menh_text'],
                    'than_text' => $s1['than_text']
                ]);

                $xtpl->parse('main.result.report_detail.sec1');
            }

            // Section 2: Detailed Palaces
            if (isset($rep['section_2'])) {
                foreach ($rep['section_2'] as $p) {
                    $xtpl->assign('SEC2_NAME', $p['name']);

                    // Chinh Tinh
                    if (!empty($p['reading']['chinh_tinh'])) {
                        foreach ($p['reading']['chinh_tinh'] as $r) {
                            $xtpl->assign('READING', $r);
                            $xtpl->parse('main.result.report_detail.sec2.chinh_tinh');
                        }
                    }

                    // Phu Tinh
                    if (!empty($p['reading']['phu_tinh'])) {
                        foreach ($p['reading']['phu_tinh'] as $r) {
                            $xtpl->assign('READING', $r);
                            $xtpl->parse('main.result.report_detail.sec2.phu_tinh');
                        }
                    }

                    // General/Combinations
                    if (!empty($p['reading']['general'])) {
                         foreach ($p['reading']['general'] as $r) {
                             $xtpl->assign('READING', $r);
                             $xtpl->parse('main.result.report_detail.sec2.general');
                         }
                    }

                    // Evaluation
                    if (!empty($p['evaluation'])) {
                        $xtpl->assign('SEC2_EVAL', $p['evaluation']);
                        $xtpl->parse('main.result.report_detail.sec2.evaluation');
                    }

                    $xtpl->parse('main.result.report_detail.sec2');
                }
            }
            $xtpl->parse('main.result.report_detail');

            // Section 3: Limits (Van Han)
            if (isset($rep['section_3'])) {
                // Dai Van
                if (isset($rep['section_3']['dai_van'])) {
                    $dv = $rep['section_3']['dai_van'];
                    $xtpl->assign('LIMIT_NAME', $dv['name']);

                    if (!empty($dv['reading']['general'])) {
                        foreach ($dv['reading']['general'] as $r) {
                            $xtpl->assign('READING', $r);
                            $xtpl->parse('main.result.report_limit.sec3.dai_van.reading');
                        }
                    }
                    if (!empty($dv['evaluation'])) {
                        $xtpl->assign('LIMIT_EVAL', $dv['evaluation']);
                    }
                    $xtpl->parse('main.result.report_limit.sec3.dai_van');
                }

                // Tieu Van
                if (isset($rep['section_3']['tieu_van'])) {
                    $tv = $rep['section_3']['tieu_van'];
                    $xtpl->assign('LIMIT_NAME', $tv['name']);

                    if (!empty($tv['reading']['general'])) {
                         foreach ($tv['reading']['general'] as $r) {
                             $xtpl->assign('READING', $r);
                             $xtpl->parse('main.result.report_limit.sec3.tieu_van.reading');
                         }
                    }
                    if (!empty($tv['evaluation'])) {
                         $xtpl->assign('LIMIT_EVAL', $tv['evaluation']);
                    }
                    $xtpl->parse('main.result.report_limit.sec3.tieu_van');
                }

                $xtpl->parse('main.result.report_limit.sec3');
                $xtpl->parse('main.result.report_limit');
            }
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
