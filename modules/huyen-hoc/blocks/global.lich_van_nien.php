<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_config_lich_van_nien')) {
    /**
     * nv_block_config_lich_van_nien()
     *
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_lich_van_nien($module, $data_block, $lang_block)
    {
        $html = '';
        return $html;
    }

    /**
     * nv_block_config_lich_van_nien_submit()
     *
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_lich_van_nien_submit($module, $lang_block)
    {
        return array();
    }

    /**
     * nv_block_lich_van_nien()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_block_lich_van_nien($block_config)
    {
        global $site_mods, $module_info, $module_name, $module_file, $module_data;

        $module = $block_config['module'];

        // Ensure necessary classes are loaded
        if (!class_exists('\\NukeViet\\Module\\HuyenHoc\\LunarCalendar')) {
            // Check if Huyen Hoc is active
            if (isset($site_mods['huyen-hoc'])) {
                $module_path = NV_ROOTDIR . '/modules/' . $site_mods['huyen-hoc']['module_file'];
                require_once $module_path . '/classes/LunarCalendar.php';
            } else {
                 // Try relative to current block file if module not active or standard
                 if (file_exists(NV_ROOTDIR . '/modules/huyen-hoc/classes/LunarCalendar.php')) {
                     require_once NV_ROOTDIR . '/modules/huyen-hoc/classes/LunarCalendar.php';
                 }
            }
        }

        // Use current date or request date (future enhancement)
        $day = (int)date('d');
        $month = (int)date('m');
        $year = (int)date('Y');
        $time = time();

        if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/huyen-hoc/block_lich_van_nien.tpl')) {
            $block_theme = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/huyen-hoc';
        } else {
            $block_theme = NV_ROOTDIR . '/themes/default/modules/huyen-hoc';
        }

        $xtpl = new XTemplate('block_lich_van_nien.tpl', $block_theme);

        $xtpl->assign('TEMPLATE', $module_info['template']);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        // Solar Info
        $solar_info = array(
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'day_name' => nv_date('l', $time), // e.g. "Thứ Bảy"
            'full_date' => nv_date('l, d/m/Y', $time)
        );
        $xtpl->assign('SOLAR', $solar_info);

        // Lunar Info
        $lunar = \NukeViet\Module\HuyenHoc\LunarCalendar::convertSolar2Lunar($day, $month, $year, 7.0);

        // Check if array keys exist (Named keys vs Indexed keys)
        // convertSolar2Lunar returns named keys: 'day', 'month', 'year', 'leap'

        $lunarDay = $lunar['day'];
        $lunarMonth = $lunar['month'];
        $lunarYear = $lunar['year'];
        $lunarLeap = $lunar['leap'];

        $can_chi_day = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiDay($day, $month, $year);
        $can_chi_month = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiMonth($lunarMonth, $lunarYear);
        $can_chi_year = \NukeViet\Module\HuyenHoc\LunarCalendar::getCanChiYear($lunarYear);

        // Hoang Dao / Hac Dao
        $hoang_dao = \NukeViet\Module\HuyenHoc\LunarCalendar::getNgayHoangDao($lunarDay, $lunarMonth);

        // Truc (12 Truc)
        $truc = \NukeViet\Module\HuyenHoc\LunarCalendar::getTruc($day, $month, $year);

        // Tiet Khi
        $tiet_khi = \NukeViet\Module\HuyenHoc\LunarCalendar::getTietKhi($day, $month, $year);

        $lunar_info = array(
            'day' => $lunarDay,
            'month' => $lunarMonth,
            'year' => $lunarYear,
            'leap' => ($lunarLeap ? '(Nhuận)' : ''),
            'can_chi_day' => $can_chi_day,
            'can_chi_month' => $can_chi_month,
            'can_chi_year' => $can_chi_year,
            'hoang_dao' => $hoang_dao,
            'truc' => $truc,
            'tiet_khi' => $tiet_khi
        );
        $xtpl->assign('LUNAR', $lunar_info);

        // Quote (Hardcoded or Random from DB if available)
        $quotes = [
            "Cuộc sống như một cuốn sách. Kẻ điên rồ giở qua nhanh chóng. Người khôn ngoan vừa đọc vừa suy nghĩ vì biết rằng mình chỉ được đọc có một lần. - Jean Paul",
            "Hạnh phúc không phải là đích đến, mà là hành trình chúng ta đang đi.",
            "Hãy hướng về phía mặt trời, bóng tối sẽ ngả về sau bạn."
        ];
        $quote = $quotes[array_rand($quotes)];
        $xtpl->assign('QUOTE', $quote);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}
