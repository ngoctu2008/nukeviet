<?php

namespace NukeViet\Module\TuVi;

use NukeViet\Module\TuVi\Includes\LunarDate;

class Lunisolar
{
    /**
     * Convert Solar to Lunar using the integrated LunarDate library
     */
    public static function convertSolarToLunar($dd, $mm, $yy, $timezone = 7)
    {
        $lunarDate = new LunarDate();
        $result = $lunarDate->convertSolarToLunar($dd, $mm, $yy, $timezone);
        // Result is [day, month, year, leap, ...]
        return array_slice($result, 0, 4);
    }

    public static function getCanChiYear($year)
    {
        $can = ['Canh', 'Tan', 'Nham', 'Quy', 'Giap', 'At', 'Binh', 'Dinh', 'Mau', 'Ky'];
        $chi = ['Than', 'Dau', 'Tuat', 'Hoi', 'Ty', 'Suu', 'Dan', 'Mao', 'Thin', 'Ty.', 'Ngo', 'Mui'];
        return $can[$year % 10] . ' ' . $chi[$year % 12];
    }

    public static function getCanYearID($year) {
        return $year % 10; // 0=Canh, 4=Giap...
    }

    public static function getChiYearID($year) {
        return $year % 12; // 0=Than, 4=Ty (Rat)...
    }

    // Standard Vietnamese Zodiac names
    public static $zodiac = ['Ty', 'Suu', 'Dan', 'Mao', 'Thin', 'Ti', 'Ngo', 'Mui', 'Than', 'Dau', 'Tuat', 'Hoi'];
    public static $stems = ['Giap', 'At', 'Binh', 'Dinh', 'Mau', 'Ky', 'Canh', 'Tan', 'Nham', 'Quy'];
}
