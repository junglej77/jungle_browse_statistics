<?php
class Jungle_browse_statistics_Activator
{
	public static function activate()
	{
		//创建用户缓存，国家，城市，uid表
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_user_cache.php';
		//创建页面访问表
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_pages_view.php';
		//创建用户在线表
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_user_online.php';
		register_activation_hook(__FILE__, 'start_websocket_server');

		function start_websocket_server()
		{
			// 这个命令应该指向你的 WebSocket 服务器脚本
			exec('php includes/MessageComponentInterface.php > /dev/null 2>&1 &');
		}

		//创建当前页面在线人数数据option
		add_option("jungle_browse_statistics_online_count",0);
	}
}
