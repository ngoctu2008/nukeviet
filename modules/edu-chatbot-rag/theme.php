<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_EDU_CHATBOT_RAG')) {
    exit('Stop!!!');
}

/**
 * nv_theme_edu_chatbot_rag_main()
 *
 * @param array $array_data
 * @return string
 */
function nv_theme_edu_chatbot_rag_main($array_data)
{
    global $module_info, $lang_module, $module_name, $module_file;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);

    $xtpl->parse('main');
    return $xtpl->text('main');
}
