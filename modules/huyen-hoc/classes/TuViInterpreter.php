<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class TuViInterpreter {
    private $chart;
    private $luanGiai;

    public function __construct($chart) {
        $this->chart = $chart;
        $this->luanGiai = new TuViLuanGiai();
    }

    /**
     * @return string HTML for General Analysis
     */
    public function luanTongQuan() {
        $data = $this->luanGiai->assessPreDestiny($this->chart);
        $score = $this->luanGiai->calculateScore($this->chart);

        // Construct HTML
        $html = '<div class="panel panel-info">';
        $html .= '<div class="panel-heading"><h3 class="panel-title">Tổng Quan Cốt Cách & Vận Mệnh</h3></div>';
        $html .= '<div class="panel-body">';

        $html .= '<div class="row">';
        $html .= '<div class="col-md-12"><div class="well"><h4><i class="fa fa-adjust"></i> Âm Dương</h4><p>' . $data['am_duong'] . '</p></div></div>';
        $html .= '<div class="col-md-12"><div class="well"><h4><i class="fa fa-cubes"></i> Ngũ Hành Cục Mệnh</h4><p>' . $data['cuc_menh'] . '</p></div></div>';
        $html .= '</div>';

        $html .= '<div class="alert alert-warning text-center"><strong>Chỉ số Thuận Lợi Tổng Quát: ' . $score . '/100</strong></div>';

        $html .= '</div></div>';
        return $html;
    }

    /**
     * @return string HTML for Detailed 12 Palaces Analysis
     */
    public function luanGiaiChiTiet() {
        $analysis = $this->luanGiai->luanGiai($this->chart);

        $html = '<div class="tuvi-detail-report">';

        foreach ($this->chart['dia_ban'] as $i => $palace) {
            if (!isset($analysis[$i])) continue;

            $reading = $analysis[$i];
            $palaceName = $palace['palace_name'];

            $html .= '<div class="panel panel-default palace-section">';
            $html .= '<div class="panel-heading"><h4 class="panel-title text-uppercase text-primary">' . $palaceName . ' (' . $palace['name'] . ')</h4></div>';
            $html .= '<div class="panel-body">';

            // Evaluation Rating
            if (isset($reading['evaluation'])) {
                $html .= '<p class="text-right text-muted"><em>' . $reading['evaluation']['text'] . '</em></p>';
            }

            // Chinh Tinh
            if (!empty($reading['chinh_tinh'])) {
                $html .= '<h5><i class="fa fa-star"></i> Chính Tinh</h5><ul>';
                foreach ($reading['chinh_tinh'] as $star) {
                    $html .= '<li><strong>' . $star['star'] . ':</strong> ' . $star['content'] . '</li>';
                }
                $html .= '</ul>';
            } elseif (!empty($reading['chinh_tinh_borrowed'])) {
                $html .= '<p class="text-warning"><i class="fa fa-exclamation-circle"></i> Cung Vô Chính Diệu (Mượn sao xung chiếu)</p>';
            }

            // Phu Tinh
            if (!empty($reading['phu_tinh'])) {
                $html .= '<h5><i class="fa fa-star-half-o"></i> Phụ Tinh & Sát Tinh</h5><ul>';
                foreach ($reading['phu_tinh'] as $star) {
                    $html .= '<li><strong>' . $star['star'] . ':</strong> ' . $star['content'] . '</li>';
                }
                $html .= '</ul>';
            }

            // General Combinations
            if (!empty($reading['general'])) {
                $html .= '<h5><i class="fa fa-link"></i> Cách Cục & Bộ Sao</h5><ul>';
                foreach ($reading['general'] as $item) {
                    $html .= '<li><strong>' . $item['star'] . ':</strong> ' . $item['content'] . '</li>';
                }
                $html .= '</ul>';
            }

            $html .= '</div></div>';
        }

        $html .= '</div>';
        return $html;
    }
}
