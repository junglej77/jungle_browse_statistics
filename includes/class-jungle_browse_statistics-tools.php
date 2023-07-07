<?php
class JungleBrowseStatisticsTools
{
	//获取用户IP
	public static function get_location_ip_address()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	// 获取用户ip信息
	public static function get_location_by_ip($ip)
	{
		$url = "http://ip-api.com/json/$ip?lang=zh-CN";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		curl_close($ch);

		if ($response === false) {
			echo 'Curl error: ' . curl_error($ch);
			return null;
		}

		$data = json_decode($response, true);
		if ($data['status'] === 'fail') {
			return null;
		}

		return [
			'country' => isset($data['country']) ? $data['country'] : '',
			'countryCode' => isset($data['countryCode']) ? $data['countryCode'] : '',
			'regionName' => isset($data['regionName']) ? $data['regionName'] : '',
			'city' => isset($data['city']) ? $data['city'] : '',
		];
	}
	// 获取设备信息
	public static function get_device_name($user_agent)
	{
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $user_agent)) {
			return '平板';
		} elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $user_agent)) {
			return '手机';
		} elseif (preg_match('/(ipod|iphone|ipad)/i', $user_agent)) {
			return 'iOS设备';
		} elseif (preg_match('/android/i', $user_agent)) {
			return '安卓设备';
		} elseif (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobi))/i', $user_agent)) {
			return '平板';
		} elseif (preg_match('/(kindle|silk|kftt|kfot|kfjwi|kfjwa|kfote|kfsowi|kfthwi|kfthwa|kfapwi|kfapwa)/i', $user_agent)) {
			return 'Kindle';
		} else {
			return '电脑';
		}
	}
	// 获取浏览器信息
	public static function get_browser_name($user_agent)
	{
		if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
		elseif (strpos($user_agent, 'Edge')) return 'Edge';
		elseif (strpos($user_agent, 'Chrome')) return '谷歌';
		elseif (strpos($user_agent, 'Safari')) return 'Safari';
		elseif (strpos($user_agent, 'Firefox')) return '火狐';
		elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'IE';
		return 'Other';
	}
	// 设置前端cookie
	public static function set_frontend_cookie($cookie_name, $cookie_value, $expiration)
	{
		$expiration = time() + $expiration; // 30天的过期时间
		$path = '/';
		$domain = ''; // 设置为你的域名
		$secure = false; // 设置为 true，如果你的网站使用了 SSL
		$httponly = false; // 设置为 true，如果你只希望通过 HTTP 协议访问 Cookie

		setcookie($cookie_name, $cookie_value, $expiration, $path, $domain, $secure, $httponly);
	}
	//获取前端 Cookie
	public static function get_frontend_cookie($cookie_name)
	{
		if (isset($_COOKIE[$cookie_name])) {
			return $_COOKIE[$cookie_name];
			// 处理你的逻辑，使用获取到的 Cookie 值
		}
		return '';
	}
	// 在这里生成一个唯一的缓存值（ip+当前时间戳）
	public static function generate_cache_ip($ip)
	{
		return time() . '-' . $ip;
	}
	// 判断访客是否是新访客，24小时之后再次访问就算老访客
	public static function is_new_visitor($cache_ip)
	{
		$is_new = 1;
		$split = explode('-',$cache_ip);
		$oldTime = $split[0];
		$timezone = "Asia/Shanghai"; // 北京位于"Asia/Shanghai"时区
		$dt = new DateTime($first_stored_time, new DateTimeZone($timezone));
		$timestamp = $dt->getTimestamp(); // 最早访问的时间
		$current_time = time();
		$is_new = $current_time - $timestamp > 60 * 60 * 24 ? 0 : 1;
		return $is_new;
	}
	// 判断访客是否是在线访客， 根据暂缓的数据表
	public static function is_online_visitor($wpdb, $table_name, $cache_ip)
	{

		$sql = "SELECT * FROM $table_name WHERE cache_ip =%s";
		$params = array($cache_ip);
		$sql .= " ORDER BY id ASC";
		$sql .= " LIMIT 0, 1";
		$query = $wpdb->prepare($sql, $params);
		$results = $wpdb->get_results($query);
		return empty($results[0]);
	}

	/**
	 * 启动一个定时任务，检查在线访客有多少
	 *  */ 
	public static function online_visitor_count()
	{
		global $wpdb;
		$table_name = 'seogtp_browse_statistics_guest';
		$current_time = current_time('timestamp');
		$previous_time = strtotime('-300 seconds', $current_time);
		$formatted_time = date('Y-m-d H:i:s', $previous_time);

		$count =$wpdb->get_var("SELECT COUNT(1) FROM $table_name WHERE create_time>$formatted_time");
		update_option("seogtp_browse_statistics_online_count",$count);
	}

	/**
	 * 定期将非新访客的的新访客标志改为0
	 */
	public static function update_new_guest_flag()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'seogtp_browse_statistics_guest';
		// 删除 now_time 和当前时间相差超过300秒的记录
		$wpdb->query("UPDATE $table_name SET new_guest_flag=0 WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(create_time)) > 60*60*24 ");
	}
	
	/**
	 * 获取访客ID
	 */
	public static function getGuestId($cache_ip){
		
    // global $wpdb;
    $query = $wpdb->prepare("SELECT id FROM `seogtp_browse_statistics_guest` WHERE cache_ip = %s",$cache_ip);
    $results = $wpdb->get_results($query);

    if ($results) {
        // // 查询到了记录
        foreach ($results as $row) {
            // 处理每一行数据
            $guest_id = $row->id;
			return $guest_id;
			}
		}
	}
	
	/**
	 * 统计过去一小时的访客页面访问时长，TODO 改代码.当前这小时的最新数据，查询的时候算。
	 */
	public static function hourlyStatistics()
	{
		global $wpdb;
		$table_name_statistics_periodic = 'seogtp_browse_statistics_periodic'; 
	
		$today = date('Y-m-d', strtotime('today'));
		$zeroHour = $today . ' 00:00:00';
		$today = date('Y-m-d H:i:s', strtotime($zeroHour));

		$now = current_time('Y-m-d H');
		$nowHour = $now . ':00:00';
		$lastHour = date('Y-m-d H:i:s', strtotime('-1 hours', strtotime($nowHour)));
		//直接统计访问时长viewTime，
		$query = $wpdb->prepare(
			"SELECT * 
			FROM {$table_name_statistics_periodic}
			WHERE create_time= %s AND `type`=1",
			$zeroHour
		);
		// 执行查询
		$results = $wpdb->get_results($query);
		$inserOld=0;
		$inserNew=0;
		if ($results) {
			// // 查询到了记录
			foreach ($results as $row) {
				// 处理每一行数据
   				$browse_count = $row->browse_count;
   				$new_guest_flag = $row->new_guest_flag;
				$data = array(
					'browse_count' => $browse_count,
					'new_guest_flag' => $new_guest_flag,
					'type' => 0,
					'create_time' => $lastHour,
				);
				$new_guest_flag==0?$inserOld=1:$inserNew=1;
				//获取上一个小时的数据，
				//当前小时数为1就直接插入，不为1，就获取上一个小时的数据，当前值减去上一个值，获取本小时的浏览量
				if(date('H')==00){
					$wpdb->insert( $table_name_pages_view_statistics, $data );
				}else{
					$query2 = $wpdb->prepare(
						"SELECT browse_count 
						FROM {$table_name_statistics_periodic}
						WHERE `new_guest_flag`=%i AND `type`=0 AND create_time= %s ",
						$new_guest_flag,$zeroHour
					);
					// 执行查询
					$results2 = $wpdb->get_results($query2);
			
					if ($results2) {
						foreach ($results2 as $row) {
							$browse_count2 = $row->browse_count;
							$data = array(
								'browse_count' => $browse_count-$browse_count2,
								'new_guest_flag' => $new_guest_flag,
								'type' => 0,
								'create_time' => $lastHour,
							);
							$wpdb->insert( $table_name_pages_view_statistics, $data );
						}
					}

				}
			} 
		}
		if($inserOld==0){
			$data = array(
				'browse_count' => 0,
				'new_guest_flag' =>0,
				'type' => 0,
				'create_time' => $lastHour,
			);
			$wpdb->insert( $table_name_pages_view_statistics, $data );
		}
		if($inserNew==0){
			$data = array(
				'browse_count' => 0,
				'new_guest_flag' => 1,
				'type' => 0,
				'create_time' => $lastHour,
			);
			$wpdb->insert( $table_name_pages_view_statistics, $data );
		}
	}
}

