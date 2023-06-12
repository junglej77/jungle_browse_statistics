<?php
include 'class-yousen_websocket.php';
$k_cache_ip;
$config=array('address'=>'127.0.0.1',
  'port'=>'8088',
  'event'=>'WSevent', //回调函数的函数名
  'log'=>false,
);
$websocket=new websocket($config);
$websocket->run();

function WSevent($type, $event) {
  global $websocket;
  global $k_cache_ip;

  if('in'==$type) {
    $websocket->log('client in,id:'.$event['k']);
  }elseif('out'==$type) {
    $websocket->log('client out,id:'.$event['k']);
    updateLeaveTime($event['k'],$k_cache_ip);
  }elseif('msg'==$type) {
    $websocket->log($event['k'].'message:'.$event['msg']);
    handleMsg($event, $k_cache_ip);
  }elseif('break'==$type){
    $websocket->log('break,id:'.$event['k']);
    updateLeaveTime($event['k'],$k_cache_ip);
  }
}

function handleMsg($event, $k_cache_ip) {
  global $websocket;
  global $k_cache_ip;
  $msg = $event['msg'];
  if(!strpos($msg,"~")){
    return;
  }
  $split = explode("~",$msg);
  $cache_ip=$split[0];
  $page=$split[1];
  $timestamp = time();
  $value = array('ip'=>$cache_ip,'current_page'=>$page,'leave_time'=>$timestamp);
  if(empty($k_cache_ip)) {
    // 向Map中添加键值对
    $k_cache_ip=array();
    $k_cache_ip[$event['k']]=$value;
  }else {
    $k_cache_ip[$event['k']]=$value;
  }
  $websocket->write($event['sign'], 'done:'.$k_cache_ip[$event['k']]['ip']);
}

function updateLeaveTime($k,$k_cache_ip){
  global $websocket;
  global $k_cache_ip;
    // 检查某个键是否存在
    if (isset($k_cache_ip[$k])) {
      $cache_ip=$k_cache_ip[$k]['ip'];
      $page=$k_cache_ip[$k]['current_page'];
    // 创建连接
    // $conn = new mysqli("localhost", "yousentest", "siYaojing.748", "www_yousentest_com");
    $conn = new mysqli("localhost", "www_grdtest_com", "jW4QmYmmDs", "www_grdtest_com");

    // Check connection
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    // 定义 SQL 查询语句
$sql = 'SELECT `enter_time`,leave_time FROM `wp_jungle_statistics_pages_view` where cache_ip = ? and current_page = ? order by id desc limit 1';

// 创建预处理语句对象
$stmt = $conn->prepare($sql);

// 绑定参数
    $websocket->log('out,cache_ip:'.$cache_ip);
    $websocket->log('out,cache_ip:'.$page);
$stmt->bind_param('ss', $cache_ip,$page);

// 执行查询
$stmt->execute();

// 获取查询结果
$result = $stmt->get_result();
$enter_time;
$leave_time;
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $enter_time =  $row["enter_time"];
    $leave_time =  $row["leave_time"];
    }
    if($leave_time ==null){
    $formatted_time = strftime('%Y-%m-%d %H:%M:%S', time());
    $view_time = computeViewTime($enter_time,$formatted_time);

    // 定义 SQL 查询语句
    $sql = "UPDATE wp_jungle_statistics_pages_view SET leave_time=? ,socket_out_flag = 1, view_time = ? WHERE cache_ip=? AND current_page =? order by id desc limit 1 ";

    // 创建预处理语句对象
    $stmt = $conn->prepare($sql);

    // 绑定参数
    $stmt->bind_param('ssss', $formatted_time,$view_time,$cache_ip,$page);

    // 执行查询
    $stmt->execute();
    }
    $conn->close();
      // 删除某个键值对
      unset($k_cache_ip[$k]);
    }
}

/**
 * 计算页面访问时间
 */
function computeViewTime($enter_time,$current_time){
  $startTime = strtotime($enter_time);
  $endTime = strtotime($current_time);
  return $endTime - $startTime;
}
