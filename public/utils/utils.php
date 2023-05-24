<?php

//获取用户IP
function get_location_ip_address()
{
    return $_SERVER['REMOTE_ADDR'];
}
// 获取用户ip信息
function get_location_by_ip($ip)
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
};
// 设置前端cookie
function set_frontend_cookie($cookie_name, $cookie_value, $expiration)
{
    $expiration = time() + $expiration; // 30天的过期时间
    $path = '/';
    $domain = ''; // 设置为你的域名
    $secure = false; // 设置为 true，如果你的网站使用了 SSL
    $httponly = false; // 设置为 true，如果你只希望通过 HTTP 协议访问 Cookie

    setcookie($cookie_name, $cookie_value, $expiration, $path, $domain, $secure, $httponly);
}
//获取前端 Cookie
function get_frontend_cookie($cookie_name)
{
    if (isset($_COOKIE[$cookie_name])) {
        return $_COOKIE[$cookie_name];
        // 处理你的逻辑，使用获取到的 Cookie 值
    }
    return '';
}

// 在这里生成一个唯一的缓存值（ip+当前时间戳）
function generate_cache_ip($ip)
{
    return time() . '#' . $ip;
}
