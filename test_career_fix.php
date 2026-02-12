<?php
define('NV_ROOTDIR', __DIR__);
define('NV_MAINFILE', true);
define('NV_IS_MOD_HUYEN_HOC', true);

// Mock DB
class MockDB {
    public function prepare($sql) { return new MockStmt(); }
}
class MockStmt {
    public function bindValue($k, $v) {}
    public function execute() {}
    public function fetch() { return false; }
}
$db = new MockDB();
$db_config = ['prefix' => 'nv4'];
$module_data = 'huyen-hoc';

// Include Classes
require_once 'modules/huyen-hoc/classes/TuViHuongNghiep.php';

// Mock Data
// Menh=0, Quan=4, Tai=8
$calcData = [
    'dia_ban' => [
        0 => ['palace_name' => 'Mệnh', 'chinh_tinh' => [], 'phu_tinh_tot'=>[], 'phu_tinh_xau'=>[]],
        4 => ['palace_name' => 'Quan Lộc', 'chinh_tinh' => [['code'=>'tu_vi']], 'phu_tinh_tot'=>[], 'phu_tinh_xau'=>[]],
        8 => ['palace_name' => 'Tài Bạch', 'chinh_tinh' => [], 'phu_tinh_tot'=>[], 'phu_tinh_xau'=>[]]
    ],
    'meta' => ['menh_idx' => 0]
];

echo "Testing TuViHuongNghiep Integration...\n";
$career = new \NukeViet\Module\HuyenHoc\TuViHuongNghiep($calcData);
$report = $career->renderReport();

echo "Report Generated: \n" . $report . "\n";

if (strpos($report, 'Lãnh đạo') !== false) {
    echo "SUCCESS: Found expected job keyword.\n";
} else {
    echo "FAILURE: Keyword not found.\n";
}
