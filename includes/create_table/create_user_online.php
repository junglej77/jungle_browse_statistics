<?php
global $wpdb;
$table_name = $wpdb->prefix . 'jungle_statistics_user_online';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        browser VARCHAR(100) NOT NULL,
        device VARCHAR(100) NOT NULL,
        cache_ip TEXT NOT NULL,
        is_new_user TINYINT NOT NULL DEFAULT 0,
        visited_page TEXT NOT NULL,
        source_page TEXT NOT NULL,
        create_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        now_time TIMESTAMP NULL,
        PRIMARY KEY id (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
