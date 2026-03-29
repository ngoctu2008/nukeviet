<?php

namespace NukeViet\Module\TuVi;

use NukeViet\Module\TuVi\Includes\TuViCalculator;
use NukeViet\Module\TuVi\Includes\TuViConstants;
use NukeViet\Module\TuVi\Lunisolar;

class Horoscope
{
    private $calculator;
    private $chartData;

    public function __construct($dd, $mm, $yy, $hour, $gender)
    {
        // $hour is 0-11 (Ty..Hoi)
        // Gender: 1=Male, 0=Female

        // Calculate Can/Chi Year
        // Lunisolar class provides getCanYearID, getChiYearID
        $canYear = Lunisolar::getCanYearID($yy);
        $chiYear = Lunisolar::getChiYearID($yy);

        $input = [
            'day' => $dd,
            'month' => $mm,
            'year' => $yy,
            'can_year' => $canYear,
            'chi_year' => $chiYear,
            'hour' => $hour,
            'gender' => $gender
        ];

        $this->calculator = new TuViCalculator($input);
    }

    public function generateChart()
    {
        $result = $this->calculator->execute();
        $laso = $result['laso'];
        $menhPos = $result['menh_pos'];
        $thanPos = $result['than_pos'];

        $output = [];

        foreach ($laso as $pos => $cung) {
            $stars = [];

            // Main Stars
            foreach ($cung['chinh_tinh'] as $saoCode) {
                // Determine Brightness
                $brightness = 'B';
                if (isset(TuViConstants::DO_SANG[$saoCode])) {
                    $brightness = TuViConstants::DO_SANG[$saoCode][$pos];
                }

                // Determine Element (Simplified or Lookup)
                // For now, mapping element ID is hard without huge constant table.
                // View template uses 'star-kim', 'star-moc'.
                // We can use a default or map common stars.
                // Let's assume TuViConstants doesn't have Element mapping yet.
                // We can check TuViInterpreter for starMeanings or add a small map here.
                $element = $this->guessElement($saoCode);

                $stars[] = [
                    'name' => $this->formatName($saoCode),
                    'type' => 'main',
                    'element' => $element,
                    'brightness' => $brightness
                ];
            }

            // Minor Stars
            foreach ($cung['phu_tinh'] as $saoCode) {
                $stars[] = [
                    'name' => $this->formatName($saoCode),
                    'type' => 'aux',
                    'element' => 'kim', // Placeholder
                    'brightness' => ''
                ];
            }

            // Vong Trang Sinh
            if (!empty($cung['vong_trang_sinh'])) {
                $stars[] = [
                    'name' => $cung['vong_trang_sinh'],
                    'type' => 'aux', // or 'vong_trang_sinh'
                    'element' => 'thuy',
                    'brightness' => ''
                ];
            }

            // Tuan/Triet
            if ($cung['tuan']) {
                $stars[] = ['name' => 'Tuần', 'type' => 'bad', 'element' => 'hoa', 'brightness' => ''];
            }
            if ($cung['triet']) {
                $stars[] = ['name' => 'Triệt', 'type' => 'bad', 'element' => 'kim', 'brightness' => ''];
            }

            $output[] = [
                'index' => $pos,
                'name' => $cung['cung_chuc'], // Menh, Phu Mau...
                'is_menh' => ($pos == $menhPos),
                'is_than' => ($pos == $thanPos),
                'stars' => $stars,
                'zodiac' => $cung['name'], // Ty, Suu...
                'css_class' => $this->getZodiacClass($pos)
            ];
        }

        return $output;
    }

    public function getMeta() {
        $res = $this->calculator->execute();
        return [
            'cuc' => $res['cuc'],
            'can_year' => $this->calculator->input['can_year'],
            'chi_year' => $this->calculator->input['chi_year'],
            'gender' => $this->calculator->input['gender']
        ];
    }

    private function formatName($code) {
        // Convert TU_VI -> Tử Vi
        $name = str_replace('_', ' ', strtolower($code));
        return ucwords($name);
    }

    private function getZodiacClass($i) {
        $arr = ['ty', 'suu', 'dan', 'mao', 'thin', 'ti', 'ngo', 'mui', 'than', 'dau', 'tuat', 'hoi'];
        return $arr[$i];
    }

    private function guessElement($code) {
        // Simple map for 14 main stars
        $map = [
            'TU_VI' => 'tho', 'THIEN_CO' => 'moc', 'THAI_DUONG' => 'hoa', 'VU_KHUC' => 'kim',
            'THIEN_DONG' => 'thuy', 'LIEM_TRINH' => 'hoa', 'THIEN_PHU' => 'tho', 'THAI_AM' => 'thuy',
            'THAM_LANG' => 'thuy', 'CU_MON' => 'thuy', 'THIEN_TUONG' => 'thuy', 'THIEN_LUONG' => 'moc',
            'THAT_SAT' => 'kim', 'PHA_QUAN' => 'thuy'
        ];
        return isset($map[$code]) ? $map[$code] : 'kim';
    }
}
