<?php

/**
 * Plugin News SEO Plus
 * @author Jules
 * @description Advanced SEO features for NukeViet News Module (Real-time Audit, Auto Redirect, JSON-LD)
 * @version 1.0.0
 *
 * =========================================================================
 * 1. HƯỚNG DẪN TẠO BẢNG DATABASE (SQL)
 * =========================================================================
 * Nếu hệ thống không tự tạo bảng, hãy chạy câu lệnh SQL sau (thay {PREFIX} bằng prefix của site, ví dụ: nv4):
 *
 * CREATE TABLE IF NOT EXISTS `{PREFIX}_vi_news_seo_redirect` (
 *   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 *   `redirect_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 *   `redirect_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
 *   `add_time` int(11) unsigned NOT NULL DEFAULT '0',
 *   PRIMARY KEY (`id`),
 *   KEY `redirect_from` (`redirect_from`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 *
 * =========================================================================
 * 2. HƯỚNG DẪN CÀI ĐẶT THỦ CÔNG (MANUAL PATCH) CHO CHỨC NĂNG REDIRECT 301
 * =========================================================================
 * Mở file: modules/news/admin/content.php
 * Tìm dòng (khoảng dòng 930):
 *    $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id = ' . $rowcontent['id']);
 *
 * Chèn đoạn code sau vào NGAY TRÊN dòng đó:
 *
 *    // --- PLUGIN: NEWS SEO PLUS - AUTO REDIRECT 301 ---
 *    if (isset($old_rowcontent) && isset($old_rowcontent['alias']) && $old_rowcontent['alias'] != $rowcontent['alias']) {
 *        $table_redirect = NV_PREFIXLANG . '_' . $module_data . '_seo_redirect';
 *        try {
 *            $sql_redirect = "INSERT INTO " . $table_redirect . " (redirect_from, redirect_to, add_time) VALUES (:redirect_from, :redirect_to, :add_time)";
 *            $sth_redirect = $db->prepare($sql_redirect);
 *            $sth_redirect->bindValue(':redirect_from', $old_rowcontent['alias'], PDO::PARAM_STR);
 *            $sth_redirect->bindValue(':redirect_to', $rowcontent['alias'], PDO::PARAM_STR);
 *            $sth_redirect->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
 *            $sth_redirect->execute();
 *        } catch (Exception $e) {
 *             // Ignore error
 *        }
 *    }
 *    // --- END PLUGIN ---
 *
 * =========================================================================
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Global variables needed
global $module_name, $module_data, $op, $db, $nv_Request, $my_head, $my_footer, $nv_Cache;

// Chỉ chạy nếu đang ở trong module News (hoặc các module ảo của News)
if (isset($module_name) && $module_name != '' && isset($module_data)) {

    // --- LOGIC TẠO BẢNG DATABASE (Tự động kiểm tra trong Admin) ---
    if (defined('NV_IS_FILE_ADMIN') && defined('NV_IS_ADMIN_MODULE')) {
        $table_redirect = NV_PREFIXLANG . '_' . $module_data . '_seo_redirect';
        static $seo_plus_table_checked = false;

        if (!$seo_plus_table_checked) {
            $sql_check = "SHOW TABLES LIKE '" . $table_redirect . "'";
            $result = $db->query($sql_check);
            if ($result->rowCount() == 0) {
                $sql_create = "CREATE TABLE " . $table_redirect . " (
                    id int(11) unsigned NOT NULL AUTO_INCREMENT,
                    redirect_from varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                    redirect_to varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                    add_time int(11) unsigned NOT NULL DEFAULT '0',
                    PRIMARY KEY (id),
                    KEY redirect_from (redirect_from)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                try {
                    $db->query($sql_create);
                } catch (PDOException $e) {
                    // Log error if needed
                }
            }
            $seo_plus_table_checked = true;
        }
    }

    // --- CHỨC NĂNG A: SEO CONTENT ANALYSIS (ADMIN CP) ---
    if (defined('NV_IS_FILE_ADMIN') && $op == 'content') {
        $seo_js_code = <<<EOT
<script type="text/javascript">
$(document).ready(function() {
    // HTML Template cho Widget (Bootstrap 3/4 compatible)
    var seoWidgetHtml = `
    <div class="panel panel-info" id="seo-analysis-widget" style="margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.05);">
        <div class="panel-heading">
            <h3 class="panel-title" style="font-weight:bold;"><i class="fa fa-line-chart"></i> Phân tích SEO (Real-time)</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group" style="margin-bottom: 10px;">
                <li class="list-group-item clearfix" id="seo-check-title">
                    <span class="badge pull-right">0</span> <strong>Tiêu đề:</strong> <span class="chk-status">Checking...</span>
                </li>
                <li class="list-group-item clearfix" id="seo-check-alias">
                    <span class="badge pull-right">Wait</span> <strong>Alias:</strong> <span class="chk-status">Checking...</span>
                </li>
                <li class="list-group-item clearfix" id="seo-check-heading">
                    <span class="badge pull-right">0</span> <strong>Sub-heading:</strong> <span class="chk-status">Checking...</span>
                </li>
                <li class="list-group-item clearfix" id="seo-check-image">
                    <span class="badge pull-right">No</span> <strong>Ảnh đại diện:</strong> <span class="chk-status">Checking...</span>
                </li>
            </ul>
            <div class="progress" style="height: 20px; margin-bottom: 0;">
                <div id="seo-score-bar" class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; line-height: 20px;">
                    0%
                </div>
            </div>
        </div>
    </div>
    `;

    // Inject Widget vào cột bên phải (Sidebar)
    var sidebar = $('.col-md-6 .row .col-md-24').first();
    if (sidebar.length > 0) {
        sidebar.prepend(seoWidgetHtml);
    } else {
        // Fallback
        $('.col-md-6').first().prepend(seoWidgetHtml);
    }

    function analyzeSEO() {
        var score = 0;
        var totalChecks = 4;
        var passedChecks = 0;

        // 1. Kiểm tra Tiêu đề (60-70 ký tự là tốt nhất cho Google)
        var title = $('#idtitle').val();
        var titleLen = title ? title.length : 0;
        $('#seo-check-title .badge').text(titleLen);

        if (titleLen >= 50 && titleLen <= 70) {
            $('#seo-check-title').addClass('list-group-item-success').removeClass('list-group-item-danger list-group-item-warning');
            $('#seo-check-title .badge').css('background-color', '#5cb85c');
            $('#seo-check-title .chk-status').text('Tốt (50-70 ký tự)');
            passedChecks++;
        } else if (titleLen > 0) {
            $('#seo-check-title').addClass('list-group-item-warning').removeClass('list-group-item-danger list-group-item-success');
            $('#seo-check-title .badge').css('background-color', '#f0ad4e');
            $('#seo-check-title .chk-status').text('Cần tối ưu');
            passedChecks += 0.5;
        } else {
            $('#seo-check-title').addClass('list-group-item-danger').removeClass('list-group-item-success list-group-item-warning');
            $('#seo-check-title .badge').css('background-color', '#d9534f');
            $('#seo-check-title .chk-status').text('Quá ngắn');
        }

        // 2. Kiểm tra Alias
        var alias = $('#idalias').val();
        if (alias && /^[a-z0-9-]+$/.test(alias)) {
             $('#seo-check-alias').addClass('list-group-item-success').removeClass('list-group-item-danger');
             $('#seo-check-alias .badge').text('OK').css('background-color', '#5cb85c');
             $('#seo-check-alias .chk-status').text('Hợp lệ');
             passedChecks++;
        } else {
             $('#seo-check-alias').addClass('list-group-item-danger').removeClass('list-group-item-success');
             $('#seo-check-alias .badge').text('Fix').css('background-color', '#d9534f');
             $('#seo-check-alias .chk-status').text('Chưa chuẩn SEO');
        }

        // 3. Kiểm tra Heading
        var content = '';
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['bodyhtml']) {
            content = CKEDITOR.instances['bodyhtml'].getData();
        } else {
            content = $('textarea[name="bodyhtml"]').val() || '';
        }

        var h2Count = (content.match(/<h2/g) || []).length;
        var h3Count = (content.match(/<h3/g) || []).length;
        var totalH = h2Count + h3Count;
        $('#seo-check-heading .badge').text(totalH);

        if (totalH > 0) {
             $('#seo-check-heading').addClass('list-group-item-success').removeClass('list-group-item-danger list-group-item-warning');
             $('#seo-check-heading .badge').css('background-color', '#5cb85c');
             $('#seo-check-heading .chk-status').text('Có ' + h2Count + ' H2, ' + h3Count + ' H3');
             passedChecks++;
        } else {
             $('#seo-check-heading').addClass('list-group-item-warning').removeClass('list-group-item-success list-group-item-danger');
             $('#seo-check-heading .badge').css('background-color', '#f0ad4e');
             $('#seo-check-heading .chk-status').text('Nên thêm H2, H3');
             passedChecks += 0.5; // Warning
        }

        // 4. Kiểm tra ảnh
        var homeimg = $('#homeimg').val();
        if (homeimg && homeimg.trim() !== '') {
             $('#seo-check-image').addClass('list-group-item-success').removeClass('list-group-item-danger');
             $('#seo-check-image .badge').text('OK').css('background-color', '#5cb85c');
             $('#seo-check-image .chk-status').text('Đã có');
             passedChecks++;
        } else {
             $('#seo-check-image').addClass('list-group-item-danger').removeClass('list-group-item-success');
             $('#seo-check-image .badge').text('Missing').css('background-color', '#d9534f');
             $('#seo-check-image .chk-status').text('Thiếu ảnh');
        }

        // Score
        var percent = Math.round((passedChecks / totalChecks) * 100);
        $('#seo-score-bar').css('width', percent + '%').text(percent + '%').attr('aria-valuenow', percent);
        if(percent >= 80) {
            $('#seo-score-bar').addClass('progress-bar-success').removeClass('progress-bar-warning progress-bar-danger');
        } else if (percent >= 50) {
            $('#seo-score-bar').addClass('progress-bar-warning').removeClass('progress-bar-success progress-bar-danger');
        } else {
            $('#seo-score-bar').addClass('progress-bar-danger').removeClass('progress-bar-success progress-bar-warning');
        }
    }

    $('#idtitle, #idalias, #homeimg').on('input change keyup', analyzeSEO);

    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.on('instanceReady', function(evt) {
            if (evt.editor.name === 'bodyhtml') {
                evt.editor.on('change', analyzeSEO);
                analyzeSEO();
            }
        });
    }

    setTimeout(analyzeSEO, 1500);
});
</script>
EOT;
        // Inject JS vào cuối trang admin
        $my_footer = isset($my_footer) ? $my_footer : '';
        $my_footer .= $seo_js_code;
    }

    // --- CHỨC NĂNG C: JSON-LD SCHEMA (FRONTEND) ---
    if (!defined('NV_IS_FILE_ADMIN') && $op == 'detail') {
        $alias = $nv_Request->get_string('alias', 'get', '');
        $id = $nv_Request->get_int('id', 'get', 0);

        // Xử lý Alias từ URL (Rewrite)
        if (empty($id) && empty($alias)) {
            $sys_info = $nv_Request->get_string('nvUri', 'get', '');
            $arr_url = explode('/', $sys_info);
            $arr_url = array_filter($arr_url);
            $last_segment = end($arr_url);

            if (!empty($last_segment) && !preg_match('/^page\-([0-9]+)$/', $last_segment)) {
                 // Loại bỏ đuôi .html
                 $alias_clean = preg_replace('/\.html$/i', '', $last_segment);
                 $alias = nv_clean_key($alias_clean);
            }
        }

        $where = "";
        $params = array();

        if ($id > 0) {
            $where = "id = :id";
            $params[':id'] = $id;
            $cache_suffix = $id;
        } elseif (!empty($alias)) {
            $where = "alias = :alias";
            $params[':alias'] = $alias;
            $cache_suffix = $alias;
        }

        if (!empty($where)) {
            // Cache Key format: Plugin_NewsSEO_JSONLD_{$id_or_alias}_{$lang}
            $cache_key = 'Plugin_NewsSEO_JSONLD_' . $cache_suffix . '_' . NV_LANG_DATA;

            // Lấy cache (NV4 cache object)
            // getItem(module_name, cache_key)
            $json_ld_data = '';
            if (is_object($nv_Cache)) {
                $json_ld_data = $nv_Cache->getItem($module_name, $cache_key);
            }

            if (empty($json_ld_data)) {
                $sql = "SELECT id, title, hometext, homeimgfile, addtime, edittime, author FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE " . $where . " AND status=1";
                $sth = $db->prepare($sql);
                foreach ($params as $key => $val) {
                    $sth->bindValue($key, $val);
                }
                $sth->execute();
                $row = $sth->fetch();

                if ($row) {
                    $image_url = '';
                    if (!empty($row['homeimgfile'])) {
                        if (nv_is_url($row['homeimgfile'])) {
                            $image_url = $row['homeimgfile'];
                        } else {
                            $image_url = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_data . '/' . $row['homeimgfile'];
                        }
                    }

                    $schema = array(
                        "@context" => "https://schema.org",
                        "@type" => "NewsArticle",
                        "headline" => $row['title'],
                        "image" => array_filter([$image_url]), // Remove empty if no image
                        "datePublished" => date('c', $row['addtime']),
                        "dateModified" => date('c', $row['edittime']),
                        "author" => array(
                            "@type" => "Person",
                            "name" => $row['author'] ? $row['author'] : "Admin"
                        ),
                        "description" => strip_tags($row['hometext'])
                    );

                    $json_ld_data = '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

                    // Set Cache 1 hour (3600s)
                    if (is_object($nv_Cache)) {
                        $nv_Cache->setItem($module_name, $cache_key, $json_ld_data, 3600);
                    }
                }
            }

            if (!empty($json_ld_data)) {
                global $my_head;
                $my_head .= $json_ld_data;
            }
        }
    }
}
