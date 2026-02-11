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

        // Require classes
        $lunarFile = NV_ROOTDIR . '/modules/' . $module_file . '/classes/LunarCalendar.php';
        $lichFile = NV_ROOTDIR . '/modules/' . $module_file . '/classes/LichVanNien.php';

        if (file_exists($lunarFile)) require_once $lunarFile;
        if (file_exists($lichFile)) require_once $lichFile;

        $today = getdate();
        $day = $today['mday'];
        $month = $today['mon'];
        $year = $today['year'];
        $hour = $today['hours'];

        $data = [];
        $data['solar_day'] = $day;
        $data['solar_month'] = $month;
        $data['solar_year'] = $year;

        $daysOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
        $data['day_of_week'] = $daysOfWeek[$today['wday']];

        // Use new LichVanNien class if available
        if (class_exists('\\NukeViet\\Module\\HuyenHoc\\LichVanNien')) {
            $app = new \NukeViet\Module\HuyenHoc\LichVanNien();
            $info = $app->getInfo($day, $month, $year, $hour);

            $data['lunar_day'] = $info['am_lich']['day'];
            $data['lunar_month'] = $info['am_lich']['month'];
            $data['lunar_year'] = $info['am_lich']['year'];
            $data['is_leap'] = $info['am_lich']['leap'];

            $data['can_chi_day'] = $info['can_chi']['ngay'];
            $data['can_chi_month'] = $info['can_chi']['thang'];
            $data['can_chi_year'] = $info['can_chi']['nam'];
            $data['can_chi_gio'] = $info['can_chi']['gio'];

            $data['tiet_khi'] = $info['tiet_khi'];
            $data['ngay_hoang_dao'] = $info['ngay_hoang_dao']['msg']; // Use msg for full text
            $data['ngay_hoang_dao_type'] = $info['ngay_hoang_dao']['type'];

            // Get Lucky Hours list from LunarCalendar (LichVanNien doesn't provide list yet)
            if (class_exists('\\NukeViet\\Module\\HuyenHoc\\LunarCalendar')) {
                // LichVanNien provides IDs for easy lookup
                $chiNgay = $info['ids']['chi_ngay'];
                $data['gio_hoang_dao'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getGioHoangDao($chiNgay);
            }

        } elseif (class_exists('\\NukeViet\\Module\\HuyenHoc\\LunarCalendar')) {
            // Fallback to old logic
            $lunar = \NukeViet\Module\HuyenHoc\LunarCalendar::convertSolar2Lunar($day, $month, $year, 7.0);
            $data['lunar_day'] = $lunar['day'];
            $data['lunar_month'] = $lunar['month'];
            $data['lunar_year'] = $lunar['year'];
            $data['is_leap'] = $lunar['leap'];

            $canChiInfo = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiDayInfo($day, $month, $year);
            $data['can_chi_day'] = $canChiInfo['name'];
            $data['can_chi_month'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiMonth($lunar['month'], $lunar['year']);
            $data['can_chi_year'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiYear($lunar['year']);
            $data['tiet_khi'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getTietKhi($day, $month, $year);
            $data['gio_hoang_dao'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getGioHoangDao($canChiInfo['chi_index']);
            $data['ngay_hoang_dao'] = \NukeViet\Module\HuyenHoc\LunarCalendar::getNgayHoangDao($canChiInfo['chi_index'], $lunar['month']);
        } else {
             // Basic Fallback
             $data['lunar_day'] = '?';
             $data['gio_hoang_dao'] = [];
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
