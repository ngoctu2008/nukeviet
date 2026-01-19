<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = "Export Interpretations (JSON)";
$table_name = $db_config['prefix'] . '_' . $lang . '_' . str_replace('-', '_', $module_data) . '_interpretations';

// Fetch all data
$sql = "SELECT * FROM " . $table_name;
try {
    $result = $db->query($sql);
    $data = $result->fetchAll(PDO::FETCH_ASSOC);

    // Output JSON
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="tu_vi_interpretations.json"');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
} catch (PDOException $e) {
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme("Error: Table not found. Please reinstall module.");
    include NV_ROOTDIR . '/includes/footer.php';
}
