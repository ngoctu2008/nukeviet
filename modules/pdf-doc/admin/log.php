<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['log'];

$xtpl = new XTemplate('log.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

$db_table_name = str_replace('-', '_', $module_data);
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $db_table_name . "_logs ORDER BY id DESC LIMIT 50";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['action_time'] = nv_date('d/m/Y H:i:s', $row['action_time']);
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
