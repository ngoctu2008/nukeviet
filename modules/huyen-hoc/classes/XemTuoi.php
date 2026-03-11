<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class XemTuoi {

    const SCORE_CAN_HOP = 2;
    const SCORE_CAN_PHA = -2;
    const SCORE_CHI_TAM_HOP = 3;
    const SCORE_CHI_LUC_HOP = 2;
    const SCORE_CHI_LUC_XUNG = -3;
    const SCORE_CHI_TU_HANH_XUNG = -1;
    const SCORE_MENH_TUONG_SINH = 4;
    const SCORE_MENH_TUONG_HOA = 1;
    const SCORE_MENH_TUONG_KHAC = -4;
    const SCORE_CUNG_PHI_TOT = 3;
    const SCORE_CUNG_PHI_XAU = -3;

    protected $batSanMap = [];

    public function __construct() {
        $this->initBatSan();
    }

    /**
     * So sánh 2 tuổi
     * @param int $year1
     * @param int $year2
     * @param int $gender1
     * @param int $gender2
     * @return array
     */
    public function soSanhTuoi($year1, $year2, $gender1 = 1, $gender2 = 0) {
        $info1 = $this->getYearInfo($year1, $gender1);
        $info2 = $this->getYearInfo($year2, $gender2);

        $canScore = $this->checkThienCan($info1['can_id'], $info2['can_id']);
        $chiScore = $this->checkDiaChi($info1['chi_id'], $info2['chi_id']);
        $menhScore = $this->checkNguHanh($info1['hanh_id'], $info2['hanh_id']);
        $cungScore = $this->checkCungPhi($info1['cung_phi_id'], $info2['cung_phi_id']);

        $totalScore = $canScore['score'] + $chiScore['score'] + $menhScore['score'] + $cungScore['score'];

        return [
            'nguoi_1' => $info1,
            'nguoi_2' => $info2,
            'phan_tich' => [
                'thien_can' => $canScore,
                'dia_chi' => $chiScore,
                'ngu_hanh' => $menhScore,
                'cung_phi' => $cungScore
            ],
            'tong_diem' => $totalScore,
            'ket_luan' => $this->getKetLuan($totalScore)
        ];
    }

    private function getYearInfo($year, $gender) {
        $canID = ($year - 4) % 10; if($canID < 0) $canID += 10;
        $chiID = ($year - 4) % 12; if($chiID < 0) $chiID += 12;

        $hanhID = FengShuiUtils::getNguHanhNapAm($canID, $chiID);
        $hanhName = FengShuiUtils::getNguHanhName($hanhID);

        $cungPhiID = FengShuiUtils::getCungPhi($year, $gender);
        $cungName = FengShuiUtils::getCungName($cungPhiID);

        return [
            'year' => $year,
            'gender' => $gender,
            'can_name' => FengShuiUtils::$CAN[$canID],
            'can_id' => $canID,
            'chi_name' => FengShuiUtils::$CHI[$chiID],
            'chi_id' => $chiID,
            'hanh_name' => $hanhName,
            'hanh_id' => $hanhID,
            'cung_phi_name' => $cungName,
            'cung_phi_id' => $cungPhiID
        ];
    }

    private function checkThienCan($c1, $c2) {
        if (abs($c1 - $c2) == 5) {
            return ['score' => self::SCORE_CAN_HOP, 'msg' => 'Tương Hợp (Tốt)', 'detail' => 'Thiên can hợp hóa, hỗ trợ nhau.'];
        }

        // Pha: 0-6 (Giap-Canh), 1-7 (At-Tan)... diff=6 OR diff=4?
        // Standard Tu Vi: Pha is diff=6 (Truc Xung).
        // 0(Giap) vs 6(Canh).
        $diff = abs($c1 - $c2);
        if ($diff == 6) {
             return ['score' => self::SCORE_CAN_PHA, 'msg' => 'Tương Phá (Xấu)', 'detail' => 'Thiên can khắc, phá nhau.'];
        }

        // Sinh/Khac theo Ngu Hanh Can? (Optional)
        // For now, keep simple based on request
        return ['score' => 0, 'msg' => 'Bình Hòa', 'detail' => 'Thiên can không xung không hợp.'];
    }

    private function checkDiaChi($c1, $c2) {
        $diff = abs($c1 - $c2);

        if ($diff == 4 || $diff == 8) return ['score' => self::SCORE_CHI_TAM_HOP, 'msg' => 'Tam Hợp (Rất Tốt)', 'detail' => 'Địa chi thuộc nhóm Tam Hợp cục.'];
        if ($diff == 6) return ['score' => self::SCORE_CHI_LUC_XUNG, 'msg' => 'Lục Xung (Xấu)', 'detail' => 'Địa chi xung khắc mạnh.'];

        // Luc Hop: Sum? Or Pairs.
        // Ty(0)-Suu(1), Dan(2)-Hoi(11), Mao(3)-Tuat(10), Thin(4)-Dau(9), Ty(5)-Than(8), Ngo(6)-Mui(7).
        // Check manually
        $isLucHop = false;
        if (($c1==0 && $c2==1) || ($c1==1 && $c2==0)) $isLucHop = true;
        if (($c1==2 && $c2==11) || ($c1==11 && $c2==2)) $isLucHop = true;
        if (($c1==3 && $c2==10) || ($c1==10 && $c2==3)) $isLucHop = true;
        if (($c1==4 && $c2==9) || ($c1==9 && $c2==4)) $isLucHop = true;
        if (($c1==5 && $c2==8) || ($c1==8 && $c2==5)) $isLucHop = true;
        if (($c1==6 && $c2==7) || ($c1==7 && $c2==6)) $isLucHop = true;

        if ($isLucHop) return ['score' => self::SCORE_CHI_LUC_HOP, 'msg' => 'Lục Hợp (Tốt)', 'detail' => 'Địa chi nhị hợp, rất tốt.'];

        // Tu Hanh Xung (Groups of 3: Dan-Than-Ty-Hoi / Ty-Ngo-Mao-Dau / Thin-Tuat-Suu-Mui)
        // But only specific pairs clash in square.
        // If diff=3 or diff=9 (Square relation), often considered Hinh/Hai depending on context.
        // Let's stick to Xung/Hop basic.

        return ['score' => 0, 'msg' => 'Bình Hòa', 'detail' => 'Địa chi không xung không hợp.'];
    }

    private function checkNguHanh($h1, $h2) {
        // IDs: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc (Based on FengShuiUtils usage in read output for getting names)
        // Wait, need to confirm ID mapping in FengShuiUtils::getNguHanhNapAm
        // getNguHanhNapAm returns: 4=Kim, 1=Thuy, 2=Hoa, 3=Tho, 5=Moc.
        // So: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc.

        $sinh = [4=>1, 1=>5, 5=>2, 2=>3, 3=>4]; // Kim->Thuy->Moc->Hoa->Tho->Kim
        $khac = [4=>5, 5=>3, 3=>1, 1=>2, 2=>4]; // Kim->Moc->Tho->Thuy->Hoa->Kim

        if ($h1 == $h2) return ['score' => self::SCORE_MENH_TUONG_HOA, 'msg' => 'Tương Hòa', 'detail' => 'Lưỡng hành tương tự, bình ổn.'];

        if (isset($sinh[$h1]) && $sinh[$h1] == $h2) return ['score' => 0.5, 'msg' => 'Sinh Xuất', 'detail' => 'Mệnh chủ sinh cho đối phương (Hao).'];
        if (isset($sinh[$h2]) && $sinh[$h2] == $h1) return ['score' => self::SCORE_MENH_TUONG_SINH, 'msg' => 'Tương Sinh (Rất Tốt)', 'detail' => 'Mệnh đối phương sinh cho mình (Lợi).'];

        if (isset($khac[$h1]) && $khac[$h1] == $h2) return ['score' => -1, 'msg' => 'Khắc Xuất', 'detail' => 'Mình khắc đối phương (Chế ngự).'];
        if (isset($khac[$h2]) && $khac[$h2] == $h1) return ['score' => self::SCORE_MENH_TUONG_KHAC, 'msg' => 'Tương Khắc (Xấu)', 'detail' => 'Bị đối phương khắc (Bất lợi).'];

        return ['score' => 0, 'msg' => 'Bình', 'detail' => ''];
    }

    private function checkCungPhi($cp1, $cp2) {
        if (isset($this->batSanMap[$cp1][$cp2])) {
            $res = $this->batSanMap[$cp1][$cp2];
            // Score based on type
            $score = ($res['type'] == 'good') ? self::SCORE_CUNG_PHI_TOT : self::SCORE_CUNG_PHI_XAU;
            return ['score' => $score, 'msg' => $res['name'], 'detail' => $res['detail']];
        }
        return ['score' => 0, 'msg' => 'Không xác định', 'detail' => ''];
    }

    private function initBatSan() {
        // 8 stars
        $SK = ['name'=>'Sinh Khí', 'type'=>'good', 'detail'=>'Thu hút tài lộc, danh tiếng, thăng quan phát tài.'];
        $DN = ['name'=>'Diên Niên', 'type'=>'good', 'detail'=>'Củng cố các mối quan hệ trong gia đình, tình yêu.'];
        $TY = ['name'=>'Thiên Y', 'type'=>'good', 'detail'=>'Cải thiện sức khỏe, trường thọ.'];
        $PV = ['name'=>'Phục Vị', 'type'=>'good', 'detail'=>'Củng cố sức mạnh tinh thần, mang lại sự tiến bộ của bản thân, may mắn trong thi cử.'];

        $TM = ['name'=>'Tuyệt Mệnh', 'type'=>'bad', 'detail'=>'Phá sản, bệnh tật chết người.'];
        $NQ = ['name'=>'Ngũ Quỷ', 'type'=>'bad', 'detail'=>'Mất nguồn thu nhập, mất việc làm, cãi lộn.'];
        $LS = ['name'=>'Lục Sát', 'type'=>'bad', 'detail'=>'Xáo trộn trong quan hệ tình cảm, thù hận, kiện tụng, tai nạn.'];
        $HH = ['name'=>'Họa Hại', 'type'=>'bad', 'detail'=>'Không may mắn, thị phi, thất bại.'];

        // Matrix [Cung1][Cung2]
        // 1=Kham, 2=Khon, 3=Chan, 4=Ton, 6=Can, 7=Doai, 8=Can, 9=Ly
        $this->batSanMap = [
            1 => [1=>$PV, 2=>$TM, 3=>$TY, 4=>$SK, 6=>$LS, 7=>$HH, 8=>$NQ, 9=>$DN],
            2 => [1=>$TM, 2=>$PV, 3=>$HH, 4=>$NQ, 6=>$DN, 7=>$TY, 8=>$SK, 9=>$LS],
            3 => [1=>$TY, 2=>$HH, 3=>$PV, 4=>$DN, 6=>$NQ, 7=>$TM, 8=>$LS, 9=>$SK],
            4 => [1=>$SK, 2=>$NQ, 3=>$DN, 4=>$PV, 6=>$HH, 7=>$LS, 8=>$TM, 9=>$TY],
            6 => [1=>$LS, 2=>$DN, 3=>$NQ, 4=>$HH, 6=>$PV, 7=>$SK, 8=>$TY, 9=>$TM],
            7 => [1=>$HH, 2=>$TY, 3=>$TM, 4=>$LS, 6=>$SK, 7=>$PV, 8=>$DN, 9=>$NQ],
            8 => [1=>$NQ, 2=>$SK, 3=>$LS, 4=>$TM, 6=>$TY, 7=>$DN, 8=>$PV, 9=>$HH],
            9 => [1=>$DN, 2=>$LS, 3=>$SK, 4=>$TY, 6=>$TM, 7=>$NQ, 8=>$HH, 9=>$PV]
        ];
    }

    private function getKetLuan($score) {
        if ($score >= 6) return "Rất Hợp (Đại Cát)";
        if ($score >= 2) return "Hợp (Cát)";
        if ($score >= -2) return "Bình Hòa";
        return "Xung Khắc (Hung)";
    }
}
