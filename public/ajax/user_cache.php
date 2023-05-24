<?php
function user_cache()
{
    /*check_ajax_referer()是WordPress中的一个函数，用于验证在执行AJAX请求时提交的nonce值。
    *nonce值是一种安全机制，用于防止恶意请求或CSRF攻击（跨站请求伪造）
    */
    check_ajax_referer('jungle_browse_statistics_nonce');
    // 获取当前用户的IP地址
    // $ip_address = get_location_ip_address();
    $ip_address = '116.25.106.143';
    // 获取IP地址的位置信息，你需要使用你自己的函数替换下面的代码
    $location = get_location_by_ip($ip_address);
    // 处理Ajax请求，例如保存数据到数据库
    /**
     * 1 先检验当前时候有cache_ip缓存，
     *      没有则是新访客  需先生成一个独立缓存（ip+当前时间戳），再存入数据库
     *      有则是老访客,则不存
     */
    // 获取前端cache_ip值
    $old_cache_ip = get_frontend_cookie('cache_ip');
    if (isset($old_cache_ip) && empty($old_cache_ip)) {
        $new_cache_ip = generate_cache_ip($ip_address);
        set_frontend_cookie('cache_ip',  $new_cache_ip, 60 * 60 * 24 * 365);
        // 在这里添加你的代码，例如保存数据到数据库
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_cache';
        $data = array(
            'ip_address' => $ip_address,
            'countryCode' => $location['countryCode'],
            'country' => $location['country'],
            'state_province' => $location['regionName'],
            'city' => $location['city'],
            'cache_ip' => $new_cache_ip
        );
        $format = array('%s', '%s', '%s', '%s', '%s'); //设置数据的格式，这里都是字符串
        $wpdb->insert($table_name, $data, $format);
    }

    // 最后返回结果
    wp_send_json_success();
}
// // 注册 Ajax 动作
add_action('wp_ajax_user_cache', 'user_cache');
add_action('wp_ajax_nopriv_user_cache', 'user_cache');
