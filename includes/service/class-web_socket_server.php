<?php
include 'class-yousen_websocket.php';
$k_cache_ip;
$k_cache_leave_time;
$config=array('address'=>'192.168.101.69',
  'port'=>'8088',
  'event'=>'WSevent', //回调函数的函数名
  'log'=>true,
);
$websocket=new websocket($config);
$websocket->run();

function WSevent($type, $event) {
  global $websocket;
  global $k_cache_ip;
  global $k_cache_leave_time;

  if('in'==$type) {
    $websocket->log('client in,id:'.$event['k']);
  }elseif('out'==$type) {
    $websocket->log('client out,id:'.$event['k']);
    updateLeaveTime($event,$k_cache_ip);
  }elseif('msg'==$type) {
    $websocket->log($event['k'].'message:'.$event['msg']);
    handleMsg($event, $k_cache_ip);
  }elseif('timeout'==$type){
    $websocket->log('client timeout,id:'.$event['k']);
    updateLeaveTime($event,$k_cache_ip);
  }
}

function handleMsg($event, $k_cache_ip) {
  global $websocket;
  global $k_cache_ip;
  global $k_cache_leave_time;
  $cache_ip=$event['msg'];

  if(empty($k_cache_ip)) {
    // 向Map中添加键值对  
    $k_cache_ip=array();
    $value = array('ip'=>$cache_ip,'leave_time'=>time())
    $k_cache_ip[$event['k']]=$cache_ip;
  }else {
    $k_cache_ip[$event['k']]=$cache_ip;
  }
  $websocket->write($event['sign'], 'done:'.$cache_ip.$event['k']);
  //校验是否有超时的，超时就移除

}

function updateLeaveTime($event,$k_cache_ip){
  global $k_cache_ip;
    // 检查某个键是否存在  
    if (isset($k_cache_ip[$event['k']])) {
      $cache_ip=$k_cache_ip[$event['k']];
          
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
    echo $sql;
    $result = $conn->query($sql);
    
    $conn->close();
      // 删除某个键值对  
      unset($k_cache_ip[$event['k']]);
    }
}
?>