<?php
class Jungle_browse_statistics_Activator
{
	public static function activate()
	{
		//创建表
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_browse_detail.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_browser.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_country.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_current_page_statistics.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_everytime.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_guest.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_periodic.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_search_engines.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_social_media.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_browse_statistics_source_statistics.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_seogtp_user.php';


		//创建当前页面在线人数数据option
		update_option("seogtp_browse_statistics_online_count",0);
		update_option('timezone_difference',0);
	}

}
?>