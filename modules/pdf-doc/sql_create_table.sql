CREATE TABLE `nv4_vi_pdf_doc_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action_type` varchar(50) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `action_time` int(11) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
