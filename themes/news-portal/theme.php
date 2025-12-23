<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mystery Themes / Ported by Jules
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 */

if (!defined('NV_SYSTEM') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

function nv_site_theme($contents, $full = true)
{
    global $home, $array_mod_title, $lang_global, $global_config, $site_mods, $module_name, $module_info, $op_file, $mod_title, $my_head, $my_footer, $client_info, $module_config, $op, $nv_plugin_area;

    $layout_file = ($full) ? 'layout.' . $module_info['layout_funcs'][$op_file] . '.tpl' : 'simple.tpl';

    // Fallback to layout.body.tpl if specific layout doesn't exist
    if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/' . $layout_file)) {
         $layout_file = 'layout.body.tpl';
    }

    if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/' . $layout_file)) {
        nv_info_die($lang_global['error_layout_title'], $lang_global['error_layout_title'], $lang_global['error_layout_content']);
    }

    $xtpl = new XTemplate($layout_file, NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

    // Meta tags
    $metatags = nv_html_meta_tags(false);
    foreach ($metatags as $meta) {
        $xtpl->assign('THEME_META_TAGS', $meta);
        $xtpl->parse('main.metatags');
    }

    $xtpl->assign('THEME_PAGE_TITLE', nv_html_page_title(false));

    // CSS Files
    $html_links = [];
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/font-awesome.min.css'
    ];
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/lightslider.min.css'
    ];
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/style.css'
    ];
     $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/np-dark.css'
    ];
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/np-responsive.css'
    ];
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/np-preloader.css'
    ];

     // Google Fonts
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => 'https://fonts.googleapis.com/css?family=Roboto+Condensed%3A300italic%2C400italic%2C700italic%2C400%2C300%2C700%7CRoboto%3A300%2C400%2C400i%2C500%2C700%7CTitillium+Web%3A400%2C600%2C700%2C300&#038;subset=latin%2Clatin-ext'
    ];

    if (defined('NV_IS_ADMIN') and $full) {
         // Keep NukeViet Admin bar styles if needed, or handle differently
    }

    foreach ($html_links as $links) {
        $xtpl->assign('LINKS', [
            'key' => 'rel="' . $links['rel'] . '" href="' . $links['href'] . '"',
            'value' => ''
        ]);
        $xtpl->parse('main.links');
    }

    // JS Files
    $html_js = nv_html_site_js(false);

    // Core NukeViet JS is handled by nv_html_site_js
    // Add theme specific JS
    $html_js[] = [
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/navigation.js'
    ];
    $html_js[] = [
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/jquery.sticky.js'
    ];
    $html_js[] = [
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/lightslider.min.js'
    ];
    $html_js[] = [
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/theia-sticky-sidebar.min.js'
    ];
    $html_js[] = [
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/np-custom-scripts.js'
    ];

    foreach ($html_js as $js) {
        if ($js['ext']) {
            $xtpl->assign('JS_SRC', $js['content']);
            $xtpl->parse('main.js.ext');
        } else {
            $xtpl->assign('JS_CONTENT', PHP_EOL . $js['content'] . PHP_EOL);
            $xtpl->parse('main.js.int');
        }
        $xtpl->parse('main.js');
    }

    $xtpl->assign('MODULE_CONTENT', $contents);

    // Assign other global vars
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('SITE_DESCRIPTION', $global_config['site_description']);

    // Parse main
    $xtpl->parse('main');
    $sitecontent = $xtpl->text('main');

    if ($full) {
        $sitecontent = nv_blocks_content($sitecontent);
        $sitecontent = str_replace('[THEME_ERROR_INFO]', nv_error_info(), $sitecontent);
    }

    if (!empty($my_head)) {
        $sitecontent = preg_replace('/(<\/head>)/i', $my_head . '\\1', $sitecontent, 1);
    }
    if (!empty($my_footer)) {
        $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . '\\1', $sitecontent, 1);
    }

    if (defined('NV_IS_ADMIN') and $full) {
        $sitecontent = preg_replace('/(<\/body>)/i', PHP_EOL . nv_admin_menu() . PHP_EOL . '\\1', $sitecontent, 1);
    }

    return $sitecontent;
}

function nv_error_theme($title, $content, $code)
{
    nv_info_die($title, $title, $content, $code);
}
