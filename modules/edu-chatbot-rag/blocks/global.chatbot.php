<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_edu_chatbot_rag_block_chatbot')) {
    /**
     * nv_edu_chatbot_rag_block_chatbot()
     *
     * @param array $block_config
     * @return string
     */
    function nv_edu_chatbot_rag_block_chatbot($block_config)
    {
        global $global_config, $module_info, $nv_Request, $language_array, $module_name, $module_data;
        $module = isset($block_config['module']) ? $block_config['module'] : "edu-chatbot-rag";
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module . '/block_chatbot.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module . '/block_chatbot.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block_chatbot.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module);

        $xtpl->assign('MODULE_NAME', $module);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
        $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
        $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
        $xtpl->assign('NV_CHECK_SESSION', nv_genpass());

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_edu_chatbot_rag_block_chatbot($block_config);
}
