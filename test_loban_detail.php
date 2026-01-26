<?php
define('NV_SYSTEM', true);
define('NV_ROOTDIR', __DIR__);

// Mock class for checking
class LoBanDetailTest {
    public static function run() {
        require_once 'modules/huyen-hoc/classes/LoBan.php';

        $len = 236; // Example length

        $result = \NukeViet\Module\HuyenHoc\LoBan::calculate($len);

        echo "Length: " . $len . "cm\n";
        foreach ($result as $type => $info) {
             echo "Ruler " . $type . ": " . $info['name'] . " (" . $info['sub_name'] . ")\n";
        }
    }
}

LoBanDetailTest::run();
