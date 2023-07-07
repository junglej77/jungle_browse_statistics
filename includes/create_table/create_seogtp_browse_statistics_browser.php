<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_browser';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name  (
      `id` bigint(0) NOT NULL AUTO_INCREMENT COMMENT 'ID',
      `browser` varchar(31) NULL DEFAULT NULL COMMENT '浏览器',
      PRIMARY KEY (`id`) USING BTREE
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
