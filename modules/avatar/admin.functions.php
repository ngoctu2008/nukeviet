<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

/**
 * Fix category order
 */
function nv_fix_cat_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = "SELECT catid, parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $array_cat_order = array();
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['catid'];
    }
    $weight = 0;
    if ($parentid > 0) {
        ++$order;
        ++$lev;
    } else {
        $lev = 0;
    }

    foreach ($array_cat_order as $catid) {
        ++$order;
        ++$weight;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $weight . ", sort=" . $order . ", lev=" . $lev . " WHERE catid=" . $catid;
        $db->query($sql);
        $order = nv_fix_cat_order($catid, $order, $lev);
    }

    // Update numsubcat
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET numsubcat=" . $numsubcat . " WHERE catid=" . $parentid;
        $db->query($sql);
    }
    return $order;
}

// Function to handle alias change
function change_alias($title)
{
    return change_alias_func($title); // Core function
}
