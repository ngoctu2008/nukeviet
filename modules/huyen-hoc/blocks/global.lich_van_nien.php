<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: http://opensource.org/licenses/mit-license.php MIT License
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_config_lich_van_nien')) {
    /**
     * Cấu hình block trong AdminCP
     */
    function nv_block_config_lich_van_nien($module, $data_block, $lang_block)
    {
        $html = '<div class="form-group row">';
        $html .= '<label class="col-sm-4 control-label">Hiển thị giờ Hoàng Đạo</label>';
        $html .= '<div class="col-sm-8">';
        $html .= '<input type="checkbox" name="config_show_zodiac" value="1" ' . (isset($data_block['show_zodiac']) && $data_block['show_zodiac'] ? 'checked="checked"' : '') . ' />';
        $html .= '</div></div>';
        return $html;
    }

    /**
     * Lưu cấu hình block
     */
    function nv_block_config_lich_van_nien_submit($module, $lang_block)
    {
        global $nv_Request;
        return [
            'show_zodiac' => $nv_Request->get_int('config_show_zodiac', 'post', 0),
        ];
    }

    /**
     * Hiển thị block ngoài Front-end
     */
    function nv_block_lich_van_nien($block_config)
    {
        global $module_info, $module_file;

        // Use Module's LunarCalendar Class if available
        $classFile = NV_ROOTDIR . '/modules/' . $module_file . '/classes/LunarCalendar.php';
        if (file_exists($classFile)) {
            require_once $classFile;
            $useModuleClass = true;
        } else {
            // Fallback to core if exists
            if (file_exists(NV_ROOTDIR . '/includes/core/amlich.php')) {
                require_once NV_ROOTDIR . '/includes/core/amlich.php';
            }
            $useModuleClass = false;
        }

        $today = getdate();
        $day = $today['mday'];
        $month = $today['mon'];
        $year = $today['year'];

        $data = [];
        $data['solar_day'] = $day;
        $data['solar_month'] = $month;
        $data['solar_year'] = $year;
        $data['tiet_khi'] = '';
        $data['gio_hoang_dao'] = [];

        $daysOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
        $data['day_of_week'] = $daysOfWeek[$today['wday']];

        if ($useModuleClass && class_exists('\\NukeViet\\Module\\HuyenHoc\\LunarCalendar')) {
            $lunar = \NukeViet\Module\HuyenHoc\LunarCalendar::convertSolar2Lunar($day, $month, $year, 7.0);
            $data['lunar_day'] = $lunar['day'];
            $data['lunar_month'] = $lunar['month'];
            $data['lunar_year'] = $lunar['year'];
            $data['is_leap'] = $lunar['leap'];

            // Get Can Chi using Module Class logic
            $canChiInfo = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiDayInfo($day, $month, $year);
            $data['can_chi_day'] = $canChiInfo['name'];
            $data['can_chi_month'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiMonth($lunar['month'], $lunar['year']);
            $data['can_chi_year'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiYear($lunar['year']);
            $data['tiet_khi'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getTietKhi($day, $month, $year);
            $data['gio_hoang_dao'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getGioHoangDao($canChiInfo['chi_index']);
            $data['ngay_hoang_dao'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getNgayHoangDao($canChiInfo['chi_index'], $lunar['month']);

        } elseif (function_exists('convertSolar2Lunar')) {
            // Core amlich.php usage
            $lunar = convertSolar2Lunar($day, $month, $year, 7.0);
            $data['lunar_day'] = $lunar[0];
            $data['lunar_month'] = $lunar[1];
            $data['lunar_year'] = $lunar[2];
            $data['is_leap'] = $lunar[3];

            if (function_exists('getCanChiDay')) {
                $data['can_chi_day'] = getCanChiDay($day, $month, $year);
                $data['can_chi_month'] = getCanChiMonth($lunar[1], $lunar[2]);
                $data['can_chi_year'] = getCanChiYear($lunar[2]);
            }
        } else {
            // No calendar logic found
            $data['lunar_day'] = '?';
            $data['lunar_month'] = '?';
            $data['lunar_year'] = '?';
            $data['can_chi_day'] = '';
            $data['can_chi_month'] = '';
            $data['can_chi_year'] = '';
        }

        // Determine template file
        if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/block_lich_van_nien.tpl')) {
            $block_tpl_name = 'block_lich_van_nien.tpl';
            $block_tpl_path = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'];
        } else {
            // Default template in module
            $block_tpl_name = 'block_lich_van_nien.tpl';
            $block_tpl_path = NV_ROOTDIR . '/themes/default/modules/' . $module_file;
        }

        $xtpl = new XTemplate($block_tpl_name, $block_tpl_path);
        $xtpl->assign('DATA', $data);
        $xtpl->assign('BLOCK_ID', $block_config['bid']);
        $xtpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_info['module_theme']);

        if (isset($block_config['show_zodiac']) && $block_config['show_zodiac'] && !empty($data['gio_hoang_dao'])) {
            foreach ($data['gio_hoang_dao'] as $gio) {
                $xtpl->assign('GIO', $gio);
                $xtpl->parse('main.show_zodiac.loop');
            }
            $xtpl->parse('main.show_zodiac');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_lich_van_nien($block_config);
}
