<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_AVATAR', true);

// Fix for empty op causing include errors
if (!isset($op) || empty($op)) {
    $op = 'main';
}

// Get Config
if (!function_exists('nv_avatar_get_config')) {
    function nv_avatar_get_config($module_data)
    {
        global $nv_Cache, $module_name;
        $sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
        $list = $nv_Cache->db($sql, '', $module_name);
        $data = array();
        foreach ($list as $row) {
            $data[$row['config_name']] = $row['config_value'];
        }
        return $data;
    }
}
$module_config = nv_avatar_get_config($module_data);

// Get Category Info
if (!function_exists('nv_avatar_get_cat')) {
    function nv_avatar_get_cat($catid)
    {
        global $db, $module_data;
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid;
        return $db->query($sql)->fetch();
    }
}

// Global variables for View
$catid = 0;
$id = 0;

// Cache all categories aliases for routing
$array_cat_alias = array();
$sql = "SELECT catid, alias FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat";
$list = $nv_Cache->db($sql, 'catid', $module_name);
foreach ($list as $row) {
    $array_cat_alias[$row['alias']] = $row['catid'];
}

// Manual Routing Logic
if ($op == 'main') {
    // Case 1: domain.com/avatar/alias-id (Item detail at root)
    if (sizeof($array_op) == 1) {
        $alias_url = $array_op[0];
        if (preg_match('/^([a-z0-9\-]+)\-([0-9]+)$/i', $alias_url, $m)) {
            $op = 'detail';
            $alias = $m[1];
            $id = intval($m[2]);
        } elseif (isset($array_cat_alias[$alias_url])) {
            // Case 2: domain.com/avatar/cat-alias (Category view)
            $op = 'viewcat';
            $catid = $array_cat_alias[$alias_url];
        }
    }
    // Case 3: domain.com/avatar/cat-alias/alias-id (Item detail inside category)
    elseif (sizeof($array_op) == 2) {
        $alias_cat = $array_op[0];
        $alias_item = $array_op[1];
        if (isset($array_cat_alias[$alias_cat])) {
            $catid = $array_cat_alias[$alias_cat];
            if (preg_match('/^([a-z0-9\-]+)\-([0-9]+)$/i', $alias_item, $m)) {
                $op = 'detail';
                $alias = $m[1];
                $id = intval($m[2]);
            }
        }
    }
} else {
    // Fallback for non-rewritten URLs (op=cat-alias)
    if (isset($array_cat_alias[$op])) {
        $catid = $array_cat_alias[$op];
        $op = 'viewcat';
    }
}
