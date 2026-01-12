<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_TU_VI_ADMIN')) {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin/admin.functions.php';
}

// Export logic
$sql = "SELECT star_key, palace_key, content FROM " . NV_PRE_TUVI . "_interpretations";
$result = $db->query($sql);
$data = $result->fetchAll(PDO::FETCH_ASSOC);

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="tu_vi_interpretations.json"');
echo $json;
exit();
