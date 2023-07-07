<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_everytime';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name  (
        `id` bigint(0) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `guest_id` bigint(0) NULL DEFAULT NULL COMMENT '访客ID',
        `new_browse_flag` int(0) NULL DEFAULT NULL COMMENT '是否算一次新的访问',
        `enter_time` datetime(0) NULL DEFAULT NULL COMMENT '访问开始时间',
        `leave_time` datetime(0) NULL DEFAULT NULL COMMENT '访问结束时间',
        `view_time` int(0) NULL DEFAULT NULL COMMENT '访问总时长',
        `brower` varchar(31)  NULL DEFAULT NULL COMMENT '浏览器',
        `device` varchar(31) NULL DEFAULT NULL COMMENT '设备',
        `source` varchar(31) NULL DEFAULT NULL COMMENT '来源',
        `social_media` varchar(31) NULL DEFAULT NULL COMMENT '社媒',
        `browse_page_count` int(0) NULL DEFAULT NULL COMMENT '访问页面数',
        `jump_out_flag` tinyint(1) NULL DEFAULT NULL COMMENT '是否跳出\r0 未跳\r1 跳出',
        PRIMARY KEY (`id`) USING BTREE
      ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
