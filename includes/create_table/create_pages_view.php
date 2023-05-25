<?php
global $wpdb;
$table_name = $wpdb->prefix . 'jungle_statistics_pages_view';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                cache_ip TEXT NOT NULL,
                current_page VARCHAR(200) NOT NULL,
                referrer_page VARCHAR(200) NOT NULL,
                enter_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                leave_time TIMESTAMP NULL,
                PRIMARY KEY id (id)
            ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
