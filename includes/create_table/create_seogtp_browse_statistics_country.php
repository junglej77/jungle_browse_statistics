<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_country';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
      $sql = "CREATE TABLE $table_name  (
        `id` bigint(0) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `country` varchar(63)  NULL DEFAULT NULL COMMENT '国家',
        PRIMARY KEY (`id`) USING BTREE
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
