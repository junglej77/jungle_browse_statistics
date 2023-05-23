<?php
class Jungle_browse_statistics_Activator
{
	public static function activate()
	{
		//创建用户缓存，国家，城市，uid表
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_user_cache.php';
	}
}
