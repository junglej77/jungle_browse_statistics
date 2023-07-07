<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_current_page_statistics';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
      $sql = "CREATE TABLE $table_name  (
        `id` bigint(0) NOT NULL COMMENT 'ID',
        `referrer_page` varchar(255) NULL DEFAULT NULL COMMENT '来源页',
        `current_page` varchar(255) NULL DEFAULT NULL COMMENT '访问页',
        `comment` varchar(63) NULL DEFAULT NULL COMMENT '备注',
        `country` varchar(63) NULL DEFAULT NULL COMMENT '国家',
        `new_guest_flag` tinyint(1) NULL DEFAULT NULL COMMENT '是否是新用户 0 不是 1是',
        `keyword` varchar(63) NULL DEFAULT NULL COMMENT '搜索关键词',
        `browser` varchar(31) NULL DEFAULT NULL COMMENT '浏览器',
        `search_engines` varchar(31) NULL DEFAULT NULL COMMENT '搜索引擎',
        `guest_count` int(0) NULL DEFAULT NULL COMMENT '总访客数',
        `browse_count` int(0) NULL DEFAULT NULL COMMENT '总访问次数',
        -- `total_depth` float(8, 2) NULL DEFAULT NULL COMMENT '总访问深度',
        `total_view_time` int(0) NULL DEFAULT NULL COMMENT '总访问时长',
        `total_jump_out` int(0) NULL DEFAULT NULL COMMENT '总跳出数',
        `total_exit` int(0) NULL DEFAULT NULL COMMENT '总退出数',
        `create_time` datetime(0) NULL DEFAULT NULL COMMENT '统计时间',
        PRIMARY KEY (`id`) USING BTREE
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
