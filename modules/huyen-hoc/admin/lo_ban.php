<?php
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');
$contents = "Chức năng đang phát triển";
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
