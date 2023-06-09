<?php
function user_cache()
{
    global $wpdb;
    $table_name_user_cache = $wpdb->prefix . 'jungle_statistics_user_cache';
    $table_name_user_online = $wpdb->prefix . 'jungle_statistics_user_online';
    $table_name_pages_view = $wpdb->prefix . 'jungle_statistics_pages_view';
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    /*check_ajax_referer()是WordPress中的一个函数，用于验证在执行AJAX请求时提交的nonce值。
    *nonce值是一种安全机制，用于防止恶意请求或CSRF攻击（跨站请求伪造）
    */
    check_ajax_referer('jungle_browse_statistics_nonce');
    /**************************************************************************************获取当前用户的IP地址 */
    $ip_address = JungleBrowseStatisticsTools::get_location_ip_address();
    // $ip_address = '116.25.106.143';
    /**************************************************************************************获取设备*/
    $device = JungleBrowseStatisticsTools::get_device_name($user_agent);
    /**************************************************************************************获取浏览器*/
    $browser = JungleBrowseStatisticsTools::get_browser_name($user_agent);
    /**************************************************************************************获取IP地址的位置信息 */
    // 获取IP地址的位置信息，你需要使用你自己的函数替换下面的代码
    $location = JungleBrowseStatisticsTools::get_location_by_ip($ip_address);
    /**************************************************************************************存储数据库的元数据 */
    $data = array();
    /**************************************************************************************处理Ajax请求，例如保存数据到数据库 */
    /**************************************************************************************根据一年时间的缓存值判断新老客户，获取前端cache_ip值 */
    $cache_ip = JungleBrowseStatisticsTools::get_frontend_cookie('cache_ip');
    /**
     * 1 先检验当前时候有cache_ip缓存，
     *      没有则是新访客  需先生成一个独立缓存（ip+当前时间戳），再存入数据库
     *      有则是老访客,则判断是否登录，更新数据
     */
    if (isset($cache_ip) && empty($cache_ip)) {
        $cache_ip = JungleBrowseStatisticsTools::generate_cache_ip($ip_address);
        JungleBrowseStatisticsTools::set_frontend_cookie('cache_ip',  $cache_ip, 60 * 60 * 24 * 365);

        $data = array(
            'ip_address' => $ip_address,
            'countryCode' => isset($location) ? $location['countryCode'] : '',
            'country' => isset($location) ? $location['country'] : '',
            'state_province' => isset($location) ? $location['regionName'] : '',
            'city' => isset($location) ? $location['city'] : '',
            'cache_ip' => $cache_ip,
            'device' => $device,
            'browser' => $browser,
        );
        var_dump($data);
        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'); //设置数据的格式，这里都是字符串
        $wpdb->insert($table_name_user_cache, $data, $format);
    } else {
        /**************************************************************************************用户是否登录 */
        if (is_user_logged_in()) {
            $user_logged_id = get_current_user_id();
            $wpdb->update($table_name_user_cache, array(
                'u_id' => $user_logged_id,
            ), array(
                'cache_ip' => $cache_ip    // 唯一缓存ip
            ));
        }
    }
    /**************************************************************************************获取当前访问页面 */
    $page_url = $_POST['page_url'];
    /**************************************************************************************获取来源页 */
    $referrer = wp_get_referer();
    /**************************************************************************************cache_ip为核心判断是新老访客*/
    $is_new = JungleBrowseStatisticsTools::is_new_visitor($wpdb, $table_name_user_cache, $cache_ip);
    /**************************************************************************************cache_ip为核心判断在线用户表中是否记录用户*/
    $is_online = JungleBrowseStatisticsTools::is_online_visitor($wpdb, $table_name_user_online, $cache_ip);
    $online_user = array(
        'browser' => $browser,
        'device' => $device,
        'cache_ip' => $cache_ip,
        'is_new_user' => $is_new === 'new' ? 0 : 1,
        'visited_page' => $page_url,
        'source_page' => $referrer,
    );
    if ($is_online) {
        /**************************************************************************************没有记录当前访客则添加created_time*/
        $online_user['create_time'] = current_time('Y-m-d H:i:s');
        $online_user['now_time'] = current_time('Y-m-d H:i:s');
        $wpdb->insert($table_name_user_online, $online_user);
    } else {
        /**************************************************************************************有记录当前访客则更新now_time*/
        $wpdb->update($table_name_user_online, array(
            'now_time' => current_time('Y-m-d H:i:s'),
        ),  array(
            'cache_ip' => $cache_ip
        ));
    }
    /**************************************************************************************记录访客的页面起始时间*/
    $page_viewed = array(
        'cache_ip' => $cache_ip,
        'current_page' => $page_url,
        'referrer_page' => $referrer,
        'enter_time' => current_time('Y-m-d H:i:s'),
    );
    //先把该cache_ip最后一次的访问记录离开时间更新了
    update_leave_time($cache_ip,$table_name_pages_view);
    $wpdb->insert($table_name_pages_view, $page_viewed);
    $echo = array(
        'device' => $device,
        'browser' => $browser,
        'cache_ip' => $cache_ip,
        'current_time' => time(),
        'is_new' => $is_new,
        'is_online' => $is_online,
        'is_online_boolean' => empty($is_online),
        'page_url' => $page_url,
        'referrer' => $referrer,
    );
    // 最后返回结果
    wp_send_json_success($echo);
}

/**
 * 更新访客页面离开页面时间,
 */
function update_leave_time($ip,$table_name){
    //先判断访客最近一次的时间距离这次请求的时间
    $conn = new mysqli("localhost", "yousentest", "siYaojing.748", "www_yousentest_com");
    //TODO 这里通过wpdb获取当前数据库的信息。
    // global $wpdb;
    // $conn = new mysqli($wpdb->db_host(), $wpdb->db_user(), $wpdb->db_password(), "www_yousentest_com");
    // // Check connection
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $sql = "SELECT enter_time,leave_time FROM ".$table_name." WHERE cache_ip = ? ORDER BY id DESC LIMIT 1";  
    echo $sql;
    $stmt = $conn->prepare($sql);  
    $stmt->bind_param('s',$ip);  
    $stmt->execute();  
    $result = $stmt->get_result(); 
    $enter_time;
    $leave_time;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $enter_time =  $row["enter_time"];
        $leave_time =  $row["leave_time"];
    }
    if($leave_time==null){    
        $current_time = strftime('%Y-%m-%d %H:%M:%S', time());
        $view_time = computeViewTime($enter_time,$current_time);
        $sql = "UPDATE ".$table_name." SET leave_time= ? , view_time = ?  WHERE cache_ip= ?   order by id desc limit 1 ";  
        $stmt = $conn->prepare($sql);  
        $stmt->bind_param('sis', $current_time,$view_time,$ip);  
        $stmt->execute();  
    }   
    $conn->close();
}

/**
 * 计算页面访问时间
 */
function computeViewTime($enter_time,$current_time){
    $startTime = strtotime($enter_time);
    $endTime = strtotime($current_time);
    return $endTime - $startTime;
}

// // 注册 Ajax 动作
add_action('wp_ajax_user_cache', 'user_cache');
add_action('wp_ajax_nopriv_user_cache', 'user_cache');
