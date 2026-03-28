<?php
// Mock setup
define('NV_IS_MOD_HUYEN_HOC', true);
define('NV_ROOTDIR', __DIR__ . '/../../');
$module_info = ['template' => 'default'];
$module_file = 'huyen-hoc';
$lang_module = [
    'tu_vi' => 'Tử Vi',
    'full_name' => 'Họ tên',
    'gender' => 'Giới tính'
];
$op = 'main';
$module_name = 'huyen-hoc';

// Mock XTemplate
class XTemplate {
    private $blocks = [];
    private $vars = [];

    public function __construct($file, $path) {}
    public function assign($k, $v) { $this->vars[$k] = $v; }
    public function parse($block) {
        $this->blocks[] = $block;
    }
    public function text($block) { return 'Rendered content'; }
}

// Check theme.php
require_once __DIR__ . '/../../modules/huyen-hoc/theme.php';

// Prepare data for nv_theme_huyen_hoc_tu_vi
$data = [
    'laso' => [
        'dia_ban' => [
            ['key' => 'ty', 'palace_name' => 'Tý', 'chinh_tinh' => [['name'=>'Vi', 'color'=>'red', 'element'=>'Thổ', 'dacs'=>'M']], 'phu_tinh_tot' => [['name'=>'Tot', 'color'=>'blue', 'element'=>'Thủy']], 'phu_tinh_xau' => [['name'=>'Xau', 'color'=>'black', 'element'=>'Hỏa']]]
        ]
    ]
];
$input = ['name' => 'Test', 'h' => 0, 'g' => 1, 'd'=>1, 'm'=>1, 'y'=>2000];

// Execute theme function (simulated)
// Since we can't fully run XTemplate without the file system and parser, we will statically analyze the file content updates.

$tplContent = file_get_contents(__DIR__ . '/../../themes/default/modules/huyen-hoc/tu-vi.tpl');

echo "Checking tu-vi.tpl content...\n";

if (strpos($tplContent, 'BEGIN: phu_tinh_tot') !== false) {
    echo "SUCCESS: Found phu_tinh_tot block.\n";
} else {
    echo "FAILURE: Missing phu_tinh_tot block.\n";
}

if (strpos($tplContent, 'BEGIN: phu_tinh_xau') !== false) {
    echo "SUCCESS: Found phu_tinh_xau block.\n";
} else {
    echo "FAILURE: Missing phu_tinh_xau block.\n";
}

if (strpos($tplContent, '$(\'[data-toggle="tooltip"]\').tooltip()') !== false) {
    echo "SUCCESS: Found Tooltip initialization.\n";
} else {
    echo "FAILURE: Missing Tooltip initialization.\n";
}

// Check if theme.php handles the loops
// The generic loop in theme.php:
// foreach ($laso['dia_ban'] as $key => $palace) { $xtpl->assign('PALACE', $palace); ... }
// XTemplate assign handles arrays automatically if key matches?
// Actually, standard XTemplate usage in NukeViet requires explicit loop parsing in PHP if it's a nested array of arrays.
// Let's check theme.php content.

$themeContent = file_get_contents(__DIR__ . '/../../modules/huyen-hoc/theme.php');
if (strpos($themeContent, 'phu_tinh_tot') === false) {
    echo "WARNING: theme.php might not be parsing phu_tinh_tot loops explicitly. Checking logic.\n";
}

?>
