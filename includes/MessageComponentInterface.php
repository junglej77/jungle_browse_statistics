<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MyChat implements MessageComponentInterface
{
    protected $clients;
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }
    public function onOpen(ConnectionInterface $conn)
    {
        // 添加新连接到客户端列表

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // 当服务器收到一个新的消息时，这个方法将被调用
        $from->send('Hello');  // 发送一个消息给客户端
    }

    public function onClose(ConnectionInterface $conn)
    {
        // global $wpdb;
        // $table_name_user_cache = $wpdb->prefix . 'jungle_statistics_user_cache';
        // // 当一个连接关闭时，这个方法将被调用
        // $ip_address = '116.25.106.143';
        // $cache_ip = JungleBrowseStatisticsTools::generate_cache_ip($ip_address);

        // $data = array(
        //     'ip_address' => $ip_address,
        //     'countryCode' => 'CN',
        //     'country' => '中国',
        //     'state_province' =>  '广东',
        //     'city' => '深圳',
        //     'cache_ip' => $cache_ip,
        //     'device' => '电脑',
        //     'browser' => '谷歌',
        // );
        // var_dump($data);
        // $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'); //设置数据的格式，这里都是字符串
        // $wpdb->insert($table_name_user_cache, $data, $format);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // 当发生错误时，这个方法将被调用
        // global $wpdb;
        // $table_name_user_cache = $wpdb->prefix . 'jungle_statistics_user_cache';
        // // 当一个连接关闭时，这个方法将被调用
        // $ip_address = '116.25.106.143';
        // $cache_ip = JungleBrowseStatisticsTools::generate_cache_ip($ip_address);

        // $data = array(
        //     'ip_address' => $ip_address,
        //     'countryCode' => 'CN',
        //     'country' => '中国',
        //     'state_province' =>  '错误',
        //     'city' => '深圳',
        //     'cache_ip' => $cache_ip,
        //     'device' => '电脑',
        //     'browser' => '谷歌',
        // );
        // var_dump($data);
        // $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'); //设置数据的格式，这里都是字符串
        // $wpdb->insert($table_name_user_cache, $data, $format);
    }
}

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyChat()
        )
    ),
    8080
);

$server->run();
