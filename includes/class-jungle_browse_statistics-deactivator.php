<?php

class Jungle_browse_statistics_Deactivator
{
	public static function deactivate()
	{
		//移除option字段
		do_action( 'delete_option', 'jungle_browse_statistics_online_count');
		do_action( 'delete_option', 'jungle_browse_statistics_test');
	}
}
