<?php
function user_cache()
{
    global $wpdb;
    $table_name_guest = 'seogtp_browse_statistics_guest';
    $table_name_browse_detail = 'seogtp_browse_statistics_browse_detail';
    $table_name_pages_view = 'jungle_statistics_pages_view';
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    /*check_ajax_referer()是WordPress中的一个函数，用于验证在执行AJAX请求时提交的nonce值。
    *nonce值是一种安全机制，用于防止恶意请求或CSRF攻击（跨站请求伪造）
    */
    check_ajax_referer('jungle_browse_statistics_nonce');

    /****TODO 这里不需要每次跑的代码太多，需要重新梳理逻辑 */

    //获取当前用户的IP地址 */
    $ip_address = JungleBrowseStatisticsTools::get_location_ip_address();
    // $ip_address = '116.25.106.143';
    //获取设备*/
    $device = JungleBrowseStatisticsTools::get_device_name($user_agent);
    //获取浏览器*/
    $browser = JungleBrowseStatisticsTools::get_browser_name($user_agent);
    //获取IP地址的位置信息 */
    // 获取IP地址的位置信息，你需要使用你自己的函数替换下面的代码
    $location = JungleBrowseStatisticsTools::get_location_by_ip($ip_address);
    //存储数据库的元数据 */
    $data = array();
    //处理Ajax请求，例如保存数据到数据库 */
    //根据一年时间的缓存值判断新老客户，获取前端cache_ip值 */
    $cache_ip = JungleBrowseStatisticsTools::get_frontend_cookie('seogtp_cache_ip');
    $guest_ip = JungleBrowseStatisticsTools::get_frontend_cookie('seogtp_guest_ip');
    //获取当前访问页面
     $page_url = $_POST['page_url'];
     //获取来源页 
     $referrer = wp_get_referer();
    /**
     * 1 先检验当前时候有cache_ip缓存，
     *      没有则是新访客  需先生成一个独立缓存（ip+当前时间戳），再存入数据库
     *      有则是老访客,则判断是否登录，更新数据
     */
    if (!isset($cache_ip) && empty($cache_ip)) {
        //新访客的处理逻辑，添加访客，存访客的信息
        $cache_ip = JungleBrowseStatisticsTools::generate_cache_ip($ip_address);
        JungleBrowseStatisticsTools::set_frontend_cookie('seogtp_cache_ip',  $cache_ip, 60 * 60 * 24 * 365);
        $data = array(
            'ip' => $ip_address,
            'cache_ip' => $cache_ip,
            'country' => isset($location) ? $location['country'] : '',
            'countryCode' => isset($location) ? $location['countryCode'] : '',
            'state_province' => isset($location) ? $location['regionName'] : '',
            'city' => isset($location) ? $location['city'] : '',
            'device' => $device,
            'browser' => $browser,
            'new_guest_flag' => 1,
            'first_referrer_page' => $referrer,
        );
        var_dump($data);
        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'); //设置数据的格式，这里都是字符串
        $wpdb->insert($table_name_guest, $data, $format);
    }
    //cache_ip为核心判断是新老访客，直接用哦过cachae_ip的时间戳判断是否是24小时内。
    $is_new = JungleBrowseStatisticsTools::is_new_visitor($cache_ip);

    if(!isset($guest_ip) && empty($guest_ip)){
        $guest_id = JungleBrowseStatisticsTools::getGuestId($cache_ip);
        JungleBrowseStatisticsTools::set_frontend_cookie('seogtp_guest_ip',  $guest_id, 60 * 60 * 24 * 365);
    }
    //这里实际就是记录用户访问情况。
    $online_user = array(
        'guest_id' => $guest_id,
        'referrer_page' => $referrer,
        'current_page' => $page_url,
        'browser' => $browser,
        'device' => $device,
        'enter_time' => current_time('Y-m-d H:i:s'),
    );

    $wpdb->insert($table_name_browse_detail, $online_user);
        
    //更新用户最后在线时间
    // wpdb::update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
    // wpdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
   
    $wpdb->update($table_name_guest, array(
        'new_guest_flag' => $is_new,
        'lastly_browse_time' => current_time('Y-m-d H:i:s'),
    ), array(
        'guest_id' => $guest_id 
    ));

    //先把该guest_ip最后一次的访问记录离开时间更新了
    update_leave_time($guest_id, $table_name_browse_detail);

    $wpdb->insert($table_name_browse_detail, $page_viewed);
    $echo = array(
        'guest_id' => $guest_id,
        'cache_ip' => $cache_ip,
        'is_new' => $is_new,
        'page_url' => $page_url,
        'referrer' => $referrer,
        'device' => $device,
        'browser' => $browser,
        'current_time' => time(),
    );
    // 最后返回结果
    wp_send_json_success($echo);
}

/**
 * 更新访客页面离开页面时间,
 */
function update_leave_time($guestId, $table_name)
{
    //先判断访客最近一次的时间距离这次请求的时间
    // $conn = new mysqli("localhost", "yousentest", "siYaojing.748", "www_yousentest_com");
    // if ($conn->connect_error) {
    //     die("连接失败: " . $conn->connect_error);
    // }
    // $sql = "SELECT enter_time,leave_time FROM " . $table_name . " WHERE guest_id = ? ORDER BY id DESC LIMIT 1";
    // echo $sql;
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param('s', $guestId);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $enter_time;
    // $leave_time;
    // if ($result->num_rows > 0) {
    //     $row = $result->fetch_assoc();
    //     $enter_time =  $row["enter_time"];
    //     $leave_time =  $row["leave_time"];
    // }
    // if ($leave_time == null) {
    //     $now = current_time('Y-m-d H:i:s');
    //     $current_time = strftime('%Y-%m-%d %H:%M:%S', $now);;

    //     // 格式化本地时区时间
    //     $view_time = computeViewTime($enter_time, $now);
    //     $sql = "UPDATE " . $table_name . " SET leave_time= ? , view_time = ?  WHERE guest_id= ?   order by id desc limit 1 ";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param('sis', current_time('Y-m-d H:i:s'), $view_time, $guestId);
    //     $stmt->execute();
    // }
    // $conn->close();

    //用wpdb实现查询
    // global $wpdb;

    $query = $wpdb->prepare("SELECT enter_time,leave_time FROM  {$table_name} WHERE guest_id = %s ORDER BY id DESC LIMIT 1", $guestId);
    $results = $wpdb->get_results($query);

    if ($results) {
        // // 查询到了记录
        foreach ($results as $row) {
            // 处理每一行数据
            $enter_time = $row->enter_time;
            $leave_time = $row->leave_time;
            
            if ($leave_time == null) {
                $now = current_time('Y-m-d H:i:s');
                // $current_time = strftime('%Y-%m-%d %H:%M:%S', $now);

                // 格式化本地时区时间
                $view_time = computeViewTime($enter_time, $now);
                //更新
                $wpdb->update($table_name, array(
                    'leave_time' => $now,
                    'view_time' =>  $view_time,
                ), array(
                    'cache_ip' => $guestId
                ));
            }

        }
    }

}

/**
 * 计算页面访问时间
 */
function computeViewTime($enter_time, $current_time)
{
    $startTime = strtotime($enter_time);
    $endTime = strtotime($current_time);
    return $endTime - $startTime;
}

// // 注册 Ajax 动作
add_action('wp_ajax_user_cache', 'user_cache');
add_action('wp_ajax_nopriv_user_cache', 'user_cache');
