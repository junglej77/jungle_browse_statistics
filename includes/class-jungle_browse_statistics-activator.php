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
		//创建访问统计表
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/create_table/create_pages_view_statistics.php';
		//创建当前页面在线人数数据option
		add_option("jungle_browse_statistics_online_count",0);
		
		//检查是否已经开启了定时任务
		$jungle_browse_statistics_cron_flag = wp_schedule_event('jungle_browse_statistics_cron_hook');
		//未开启就开启
		if(!$jungle_browse_statistics_cron_flag){
			create_jungle_browse_statistics_cron();
		}
	}

	
/**
 * 创建定时任务动作
 */
function create_jungle_browse_statistics_cron(){
    // //首先创建时间间隔
    // add_filter( 'cron_schedules', 'jungle_browse_statistics_cron_interval' );
    add_action( 'jungle_browse_statistics_cron_hook', 'jungle_browse_statistics_cron_exec' );
    //立刻执行，随后每天执行一次
    wp_schedule_event( time(), 'daily', 'jungle_browse_statistics_cron_hook' );
}

// function jungle_browse_statistics_cron_interval( $schedules ) { 
    // $schedules['daily'] = array(
        // 'interval' => DAY_IN_SECONDS,
        // 'display'  => esc_html__( 'Once Daily' ), );
    // return $schedules;
// }

/**
 * 统计每天访问情况的定时任务执行器
 */
function jungle_browse_statistics_cron_exec(){
    global $wpdb;
    $table_name_pages_view = $wpdb->prefix . 'jungle_statistics_pages_view';
	//直接统计访问时长viewTime，
	
	$startTime = strtotime("now");
	$endTime = strtotime("-1 day");
    $querySql = "SELECT cache_ip,current_page, SUM(view_time) as view_time 
	FROM $table_name_pages_view
	where enter_time BETWEEN '${startTime}' AND '${endTime}'
	GROUP BY cache_ip,current_page";
	$result = $wpdb->query($querySql);
	$insertSql = "insert into $table_name_pages_view (`cache_ip`,`view_page`,`view_time`,`create_time`) values";
	if ($result) {
	// 查询到了记录
	foreach ($wpdb->last_result as $post) {
		$cache_ip=$post->cacha_ip;
		$view_page=$post->current_page;
		$view_time=$post->view_time;
		$insertSql=$insertSql."(".$cache_ip.",".$view_page.",".$view_time.",".$startTime."),";
	}
	//把末尾多余的,删除
	$arr = explode(",", $insertSql);
	$last_item = array_pop($arr);
	$insertSql = implode(",", $arr);
	} else {
	// 没有查询到记录
	}
	//更新统计数据到数据库
	$wpdb->query($insertSql);

}
}
?>