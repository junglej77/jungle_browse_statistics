<?php
global $wpdb;
$table_name = 'seogtp_user';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name  (
    `id` bigint(0) NOT NULL COMMENT 'ID',
    `guest_id` bigint(0) NULL DEFAULT NULL COMMENT '访客ID',
    `username` varchar(31) NULL DEFAULT NULL COMMENT '用户',
    `account` varchar(31) NULL DEFAULT NULL COMMENT '账号',
    `password` varchar(31) NULL DEFAULT NULL COMMENT '密码',
    `mobile` varchar(31) NULL DEFAULT NULL COMMENT '手机号',
    `sex` tinyint(1) NULL DEFAULT NULL COMMENT '性别\r\n            0 未选择\r\n            1 男\r\n            2 女\r\n            3 未知',
    `balance` decimal(13, 4) NULL DEFAULT NULL COMMENT '余额',
    `points` int(0) NULL DEFAULT NULL COMMENT '积分',
    `level` int(0) NULL DEFAULT NULL COMMENT '等级',
    `email` varchar(63) NULL DEFAULT NULL COMMENT '邮箱',
    `ip` varchar(15) NULL DEFAULT NULL COMMENT 'IP',
    `country` varchar(31) NULL DEFAULT NULL COMMENT '国家',
    `country_code` varchar(7) NULL DEFAULT NULL COMMENT '国家码',
    `state_province` varchar(127) NULL DEFAULT NULL COMMENT '州/省',
    `city` varchar(127) NULL DEFAULT NULL COMMENT '城市',
    `fake_flag` tinyint(1) NULL DEFAULT NULL COMMENT '假用户标志\r\n            0 真用户\r\n            1 假用户',
    `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态\r\n            0 正常\r\n            1 冻结',
    `browser` varchar(63) NULL DEFAULT NULL COMMENT '浏览器',
    `device` varchar(63) NULL DEFAULT NULL COMMENT '设备',
    `delete_status` tinyint(1) NULL DEFAULT NULL COMMENT '删除状态\r\n            0 未删除\r\n            1 已删除',
    `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`) USING BTREE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}