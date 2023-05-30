<?php

class Jungle_browse_statistics_Deactivator
{
	public static function deactivate()
	{
		//关闭定时任务
		$timestamp = wp_next_scheduled( 'jungle_browse_statistics_cron_hook' );
		wp_unschedule_event( $timestamp, 'jungle_browse_statistics_cron_hook' );
		//移除option字段
		do_action( 'delete_option', 'jungle_browse_statistics_online_count');
	}
}
