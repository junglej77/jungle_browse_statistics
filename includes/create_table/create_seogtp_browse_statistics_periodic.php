<?php
global $wpdb;
$table_name = 'seogtp_browse_statistics_periodic';
//检查数据表是否已经存在
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    // 创建数据表
    $charset_collate = $wpdb->get_charset_collate();
     
    $sql = "CREATE TABLE $table_name  (
      `id` bigint(0) NOT NULL AUTO_INCREMENT COMMENT 'ID',
      `browse_count` int(0) NULL DEFAULT NULL COMMENT '总访问次数',
      `new_guest_flag` tinyint(1) NULL DEFAULT NULL COMMENT '是否是新用户\r0 不是\r1 是',
      `type` tinyint(1) NULL DEFAULT NULL COMMENT '统计周期类型\r0 小时\r1 天',
      `create_time` datetime(0) NULL DEFAULT NULL COMMENT '统计的时间',
      PRIMARY KEY (`id`) USING BTREE
      ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
