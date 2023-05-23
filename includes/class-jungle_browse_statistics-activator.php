<?php
class Jungle_browse_statistics_Activator
{
	public static function activate()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'user_cache';

		//检查数据表是否已经存在
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			// 创建数据表
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				 id mediumint(9) NOT NULL AUTO_INCREMENT,
				 ip_address VARCHAR(45) NOT NULL,
				 location TEXT NOT NULL,
				 uid INT NOT NULL,
				 UNIQUE KEY id (id)
			 ) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
}
