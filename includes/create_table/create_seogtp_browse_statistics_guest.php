<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_guest';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
      $sql = "CREATE TABLE $table_name  (
        `id` bigint(0) NOT NULL COMMENT 'ID',
        `ip` varchar(31) NULL DEFAULT NULL COMMENT 'IP',
        `cache_ip` varchar(63) NULL DEFAULT NULL COMMENT '缓存IP',
        `country` varchar(63) NULL DEFAULT NULL COMMENT '国家',
        `country_code` varchar(7) NULL DEFAULT NULL COMMENT '国家编码',
        `state_province` varchar(63) NULL DEFAULT NULL COMMENT '省/州',
        `city` varchar(63) NULL DEFAULT NULL COMMENT '城市',
        `device` varchar(15) NULL DEFAULT NULL COMMENT '设备',
        `browser` varchar(63) NULL DEFAULT NULL COMMENT '浏览器',
        `new_guest_flag` tinyint(1) NULL DEFAULT NULL COMMENT '是否是新用户',
        `lastly_browse_time` datetime(0) NULL DEFAULT NULL COMMENT '最近访问时间',
        `first_referrer_page` varchar(255) NULL DEFAULT NULL COMMENT '第一次来源页',
        `total_view_time` int(0) NULL DEFAULT NULL COMMENT '总访问时长',
        `total_browse_time` int(0) NULL DEFAULT NULL COMMENT '总浏览次数',
        `total_browse_day` int(0) NULL DEFAULT NULL COMMENT '总访问天数',
        `total_browse_page` int(0) NULL DEFAULT NULL COMMENT '总访问页面',
        `total_jump_out` int(0) NULL DEFAULT NULL COMMENT '总跳出数',
        `create_time` datetime(0) NULL DEFAULT NULL COMMENT '用户创建时间',
        PRIMARY KEY (`id`) USING BTREE
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
