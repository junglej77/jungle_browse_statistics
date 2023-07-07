<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_browse_detail';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
      $sql = "CREATE TABLE $table_name  (
        `id` bigint(0) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `guest_id` bigint(0) NULL DEFAULT NULL COMMENT '访客ID',
        `referrer_page` varchar(255) NULL DEFAULT NULL COMMENT '来源页',
        `current_page` varchar(255) NULL DEFAULT NULL COMMENT '访问页',
        `enter_time` datetime(0) NULL DEFAULT NULL COMMENT '访问开始时间',
        `leave_time` datetime(0) NULL DEFAULT NULL COMMENT '访问结束时间',
        `view_time` int(0) NULL DEFAULT NULL COMMENT '访问时长（秒）',
        `socket_out_flag` tinyint(1) NULL DEFAULT NULL COMMENT 'socket关闭标志\r0 否\r1 是',
        `brower` varchar(31) NULL DEFAULT NULL COMMENT '浏览器',
        `device` varchar(31) NULL DEFAULT NULL COMMENT '设备',
        `keyword` varchar(31) NULL DEFAULT NULL COMMENT '关键字',
        `search_engines` varchar(31) NULL DEFAULT NULL COMMENT '搜索引擎',
        PRIMARY KEY (`id`) USING BTREE
        ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
