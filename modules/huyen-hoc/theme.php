<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
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

        // Cach Cuc (Patterns)
        if (isset($laso['cach_cuc']) && is_array($laso['cach_cuc'])) {
            foreach ($laso['cach_cuc'] as $cc) {
                // Ensure desc is present
                if (!isset($cc['desc'])) $cc['desc'] = $cc['content'];
                $xtpl->assign('ITEM', $cc);
                $xtpl->parse('main.result.cach_cuc.item');
            }
            if (!empty($laso['cach_cuc'])) {
                $xtpl->parse('main.result.cach_cuc');
            }
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
        }

        // Career (Advanced Tab)
        if (isset($laso['career_report'])) {
            $xtpl->assign('CAREER_REPORT', $laso['career_report']);
        }

        // Relations (Advanced Tab)
        if (isset($laso['relations']) && is_array($laso['relations'])) {
            foreach ($laso['relations'] as $relKey => $relData) {
                if (!$relData) continue;

                $title = isset($relData['title']) ? $relData['title'] : strtoupper($relKey);
                $xtpl->assign('RELATION_TITLE', $title);
                $xtpl->assign('RELATION_KEY', $relKey);

                if (isset($relData['readings'])) {
                    foreach ($relData['readings'] as $map) {
                        $xtpl->assign('MAP', [
                            'chuc_nang_moi' => $map['name'],
                            'cung_goc' => ['palace_name' => $map['original_name']],
                            'sao_chinh' => $map['stars'],
                            'relation_desc' => $map['desc']
                        ]);
                        $xtpl->parse('main.result.relation.map');
                    }
                }

                $xtpl->parse('main.result.relation');
            }
        }

        // Health (Suc Khoe Tab)
        if (isset($laso['health_diagnosis'])) {
            $hd = $laso['health_diagnosis'];
            $xtpl->assign('HEALTH_DIAGNOSIS', $hd);

            // Determine warning class based on content length or keywords?
            // Simple default
            $xtpl->assign('HEALTH_WARN_CLASS', 'info');

            if (isset($hd['diagnosis']) && is_array($hd['diagnosis'])) {
                foreach ($hd['diagnosis'] as $line) {
                    $xtpl->assign('HEALTH_LINE', $line);
                    $xtpl->parse('main.result.health_detail');
                }
            }
        }

        if (isset($laso['health_diet'])) {
            $xtpl->assign('HEALTH_DIET', $laso['health_diet']);
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
