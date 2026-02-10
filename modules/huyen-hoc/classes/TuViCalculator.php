<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class TuViCalculator {
    private $inputData;

    /**
     * @param array $inputData ['dd', 'mm', 'yyyy', 'hh', 'gender', 'canYear', 'chiYear', 'name']
     */
    public function __construct($inputData) {
        $this->inputData = $inputData;
    }

    /**
     * Execute the chart calculation
     * @return array
     */
    public function execute() {
        if (!class_exists('\\NukeViet\\Module\\HuyenHoc\\TuViLapSo')) {
            require_once NV_ROOTDIR . '/modules/huyen-hoc/classes/TuViLapSo.php';
        }

        // Validate or Default data
        $dd = isset($this->inputData['dd']) ? intval($this->inputData['dd']) : 1;
        $mm = isset($this->inputData['mm']) ? intval($this->inputData['mm']) : 1;
        $yyyy = isset($this->inputData['yyyy']) ? intval($this->inputData['yyyy']) : 2000;
        $hh = isset($this->inputData['hh']) ? intval($this->inputData['hh']) : 0; // Ty=0
        $gender = isset($this->inputData['gender']) ? intval($this->inputData['gender']) : 1;
        $name = isset($this->inputData['name']) ? $this->inputData['name'] : 'Khách';

        // Ensure Can/Chi Year are present if not auto-calculated inside LapSo
        // TuViLapSo::lapLaSo expects $canYear, $chiYear as arguments.
        // We should calculate them if missing, or expect them in input.
        if (!isset($this->inputData['canYear']) || !isset($this->inputData['chiYear'])) {
            // Simple calc based on YYYY
            $canYear = ($yyyy - 4) % 10; if ($canYear < 0) $canYear += 10;
            $chiYear = ($yyyy - 4) % 12; if ($chiYear < 0) $chiYear += 12;
        } else {
            $canYear = $this->inputData['canYear'];
            $chiYear = $this->inputData['chiYear'];
        }

        return TuViLapSo::lapLaSo($dd, $mm, $yyyy, $hh, $gender, $canYear, $chiYear, $name);
    }
}
