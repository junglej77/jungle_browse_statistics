<?php
class Jungle_browse_statistics_Activator
{
	public static function activate()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'user_cache';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			user_cache text NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}
