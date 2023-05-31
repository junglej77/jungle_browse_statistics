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
checkTimeOut();

function WSevent($type, $event) {
  global $websocket;
  global $k_cache_ip;

  if('in'==$type) {
    $websocket->log('client in,id:'.$event['k']);
  }elseif('out'==$type) {
    $websocket->log('client out,id:'.$event['k']);
    updateLeaveTime($event['k'],$k_cache_ip,true);
  }elseif('msg'==$type) {
    $websocket->log($event['k'].'message:'.$event['msg']);
    handleMsg($event, $k_cache_ip);
  }elseif('timeout'==$type){
    $websocket->log('client timeout,id:'.$event['k']);
    updateLeaveTime($event['k'],$k_cache_ip,true);
  }
}

function handleMsg($event, $k_cache_ip) {
  global $websocket;
  global $k_cache_ip;
  $cache_ip=$event['msg'];
  $timestamp = time();
  $value = array('ip'=>$cache_ip,'leave_time'=>$timestamp);
  if(empty($k_cache_ip)) {
    // 向Map中添加键值对  
    $k_cache_ip=array();
    $k_cache_ip[$event['k']]=$value;
  }else {
    $k_cache_ip[$event['k']]=$value;
  }
  $websocket->write($event['sign'], 'done:'.$cache_ip.$event['k']);
  //校验是否有超时的，超时就移除
    foreach ($k_cache_ip as $key => $value) {
      if($value['leave_time']-$timestamp<-6){
        updateLeaveTime($key,$k_cache_ip,false);
      }
    }
}

function updateLeaveTime($k,$k_cache_ip,$closeFlag){
  global $k_cache_ip;
    // 检查某个键是否存在  
    if (isset($k_cache_ip[$k])) {
      $cache_ip=$k_cache_ip[$k]['ip'];
          
    // 创建连接
    $conn = new mysqli("localhost", "yousentest", "siYaojing.748", "www_yousentest_com");
    // Check connection
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    } 
    $formatted_time = strftime('%Y-%m-%d %H:%M:%S', time()); 
    $sql="UPDATE wp_jungle_statistics_pages_view SET leave_time='".$formatted_time."'
    WHERE cache_ip='".$cache_ip."'
    limit 1 ";
    $result = $conn->query($sql);
    
    $conn->close();
      // 删除某个键值对  
    if($closeFlag){
      unset($k_cache_ip[$k]);
    }
    }
}
?>