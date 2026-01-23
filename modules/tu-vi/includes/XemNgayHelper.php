<?php

namespace NukeViet\Module\TuVi\Includes;

class XemNgayHelper
{
    private $generalBadDays = [];
    private $officers = [];

    // Mapping Job Types to preferred Officers (Truc)
    // 0:Kien, 1:Tru, 2:Man, 3:Binh, 4:Dinh, 5:Chap, 6:Pha, 7:Nguy, 8:Thanh, 9:Thu, 10:Khai, 11:Be
    private $jobTrucMap = [
        'wedding' => [0, 4, 8, 10], // Kien, Dinh, Thanh, Khai
        'house_build' => [8, 10, 2, 4], // Thanh, Khai, Man, Dinh
        'opening' => [10, 8, 2], // Khai, Thanh, Man
        'funeral' => [11, 1, 9] // Be, Tru, Thu
    ];

    public function __construct()
    {
        $this->loadData();
    }

    private function loadData()
    {
        $file = NV_ROOTDIR . '/modules/tu-vi/data/general_bad_days.json';
        if (file_exists($file)) {
            $this->generalBadDays = json_decode(file_get_contents($file), true);
        }

        $fileTruc = NV_ROOTDIR . '/modules/tu-vi/data/twelve_officers.json';
        if (file_exists($fileTruc)) {
            $this->officers = json_decode(file_get_contents($fileTruc), true);
        }
    }

    /**
     * Calculate Score and Advices
     * @param array $lunar [day, month, year, leap, dayCan, dayChi, ...]
     * @param int $userBirthYear
     * @param string $jobType
     * @return array
     */
    public function calculate($lunar, $userBirthYear, $jobType)
    {
        $score = 50;
        $warnings = [];
        $advices = [];
        $goodStars = []; // Mock
        $badStars = []; // Mock

        $lunarDay = $lunar[0];
        $lunarMonth = $lunar[1];

        // 1. General Bad Days (Priority 1 - Fatal)
        if (in_array($lunarDay, $this->generalBadDays['tam_nuong'])) {
            $score -= 30;
            $warnings[] = "Ngày Tam Nương: Trăm sự đều kỵ.";
        }
        if (in_array($lunarDay, $this->generalBadDays['nguyet_ky'])) {
            $score -= 30;
            $warnings[] = "Ngày Nguyệt Kỵ: Đi chơi còn lỗ huống là đi buôn.";
        }

        // Duong Cong
        foreach ($this->generalBadDays['duong_cong'] as $dc) {
            if ($dc['month'] == $lunarMonth && $dc['day'] == $lunarDay) {
                $score = 0;
                $warnings[] = "Ngày Dương Công Kỵ Nhật: Đại hung.";
                break;
            }
        }

        // Sat Chu
        foreach ($this->generalBadDays['sat_chu'] as $sc) {
            if ($sc['month'] == $lunarMonth && $sc['day_chi'] == $lunar['dayChi']) {
                $score = 0;
                $warnings[] = "Ngày Sát Chủ: Kỵ xây cất, cưới hỏi.";
                break;
            }
        }

        // Tho Tu
        foreach ($this->generalBadDays['tho_tu'] as $tt) {
            if ($tt['month'] == $lunarMonth && $tt['day_chi'] == $lunar['dayChi']) {
                $score = 0;
                $warnings[] = "Ngày Thọ Tử: Trăm sự đều kỵ.";
                break;
            }
        }

        // 2. Check Truc (12 Officers)
        $truc = $this->getTruc($lunarMonth, $lunar['dayChi']);
        $preferredTruc = isset($this->jobTrucMap[$jobType]) ? $this->jobTrucMap[$jobType] : [];

        if (in_array($truc['id'], $preferredTruc)) {
            $score += 10;
        } else {
             // Neutral or Bad?
             if ($truc['id'] == 6 || $truc['id'] == 7) { // Pha, Nguy
                 $score -= 5;
             }
        }

        // 3. Check Age Conflict (Chi Xung)
        // User Chi
        $userChi = ($userBirthYear + 8) % 12;
        if (abs($userChi - $lunar['dayChi']) == 6) {
             $score -= 20;
             $warnings[] = "Ngày Lục Xung với tuổi gia chủ (" . $this->getChiName($lunar['dayChi']) . " xung " . $this->getChiName($userChi) . ").";
        }

        // 4. Job Specific Logic
        if ($jobType == 'house_build') {
             // Check Kim Lau
             $age = date('Y') - $userBirthYear + 1;
             $kimLau = $this->checkKimLau($age);
             if ($kimLau) {
                 $advices[] = "Gia chủ phạm Kim Lâu ($age tuổi). Gợi ý: Thực hiện thủ tục Mượn Tuổi.";
                 // Does not affect day score directly, but is a major warning for the task itself
             }
        }

        // 5. Finalize Score
        if ($score < 0) $score = 0;
        if ($score > 100) $score = 100;

        // Mitigation
        if ($score < 50 && $score > 0) {
             $advices[] = "Ngày này khí xấu (Điểm: $score). Nếu bắt buộc làm, hãy chọn Giờ Đại Cát để hóa giải.";
        }

        return [
            'score' => $score,
            'rating' => $this->getRating($score),
            'truc' => $truc['name'],
            'warnings' => $warnings,
            'advices' => $advices
        ];
    }

    private function getRating($score) {
        if ($score >= 80) return 'Đại Cát';
        if ($score >= 60) return 'Tiểu Cát';
        if ($score >= 50) return 'Bình thường';
        return 'Hung';
    }

    private function getTruc($month, $dayChi)
    {
        // Truc Kien starts at month index?
        // Thang 1 (Dan) -> Kien tai Dan (2). Thang 2 (Mao) -> Kien tai Mao (3).
        // Month 1 is index 2?
        // Let's use simplified formula:
        // Kien index = (Month + 1) % 12.
        // If Month 1, Kien at Dan (2).
        // If Month 2, Kien at Mao (3).

        $kienIndex = ($month + 1) % 12;

        // Calculate offset from Kien
        // DayChi - KienIndex
        $diff = $dayChi - $kienIndex;
        if ($diff < 0) $diff += 12;

        return isset($this->officers[$diff]) ? $this->officers[$diff] : ['id'=>$diff, 'name'=>'Unknown'];
    }

    private function checkKimLau($age) {
        $r = $age % 9;
        return in_array($r, [1, 3, 6, 8]);
    }

    private function getChiName($idx) {
        $chi = ['Thân', 'Dậu', 'Tuất', 'Hợi', 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi'];
        return isset($chi[$idx]) ? $chi[$idx] : '';
    }
}
