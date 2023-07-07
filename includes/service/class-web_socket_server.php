<?php
include 'class-yousen_websocket.php';
$k_guest_ip;
$config=array('address'=>'127.0.0.1',
  'port'=>'8088',
  'event'=>'WSevent', //回调函数的函数名
  'log'=>false,
);
$websocket=new websocket($config);
$websocket->run();

function WSevent($type, $event) {
  global $websocket;
  global $k_guest_ip;

  if('in'==$type) {
    $websocket->log('client in,id:'.$event['k']);
  }elseif('out'==$type) {
    $websocket->log('client out,id:'.$event['k']);
    updateLeaveTime($event['k'],$k_guest_ip);
  }elseif('msg'==$type) {
    $websocket->log($event['k'].'message:'.$event['msg']);
    handleMsg($event, $k_guest_ip);
  }
  // elseif('break'==$type){
  //   $websocket->log('break,id:'.$event['k']);
  //   updateLeaveTime($event['k'],$k_guest_ip);
  // }
}

function handleMsg($event, $k_guest_ip) {
  global $websocket;
  global $k_guest_ip;
  $msg = $event['msg'];
  if(!strpos($msg,"~")){
    return;
  }
  $split = explode("~",$msg);
  $guest_id=$split[0];
  $page=$split[1];
  $timestamp = time();
  $value = array('id'=>$guest_id,'current_page'=>$page,'leave_time'=>$timestamp);
  if(empty($k_guest_ip)) {
    // 向Map中添加键值对
    $k_guest_ip=array();
    $k_guest_ip[$event['k']]=$value;
  }else {
    $k_guest_ip[$event['k']]=$value;
  }
  $websocket->write($event['sign'], 'done:'.$k_guest_ip[$event['k']]['id']);
}

function updateLeaveTime($k,$k_guest_ip){
  global $websocket;
  global $k_guest_ip;
    // 检查某个键是否存在
  if (isset($k_guest_ip[$k])) {
    $guest_ip=$k_guest_ip[$k]['id'];
    $page=$k_guest_ip[$k]['current_page'];
    // 创建连接
    $conn = new mysqli("localhost", "yousentest", "siYaojing.748", "www_yousentest_com");
    // $conn = new mysqli("localhost", "www_grdtest_com", "jW4QmYmmDs", "www_grdtest_com");

    // Check connection
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    //更新seogtp_browse_statistics_browse_detail表
    $sql = 'SELECT * FROM seogtp_browse_statistics_browse_detail 
    WHERE guest_id = ? 
    AND enter_time > ( SELECT leave_time FROM seogtp_browse_statistics_browse_detail WHERE guest_id = ? AND socket_out_flag =1 ORDER BY id DESC LIMIT 1 )';
    $stmt = $conn->prepare($sql);
    $websocket->log('out,cache_ip:'.$guest_ip);
    $websocket->log('out,cache_ip:'.$page);
    $stmt->bind_param('ss', $guest_ip,$guest_ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultMap = array();
    $i = 0;
    $keyword = "";
    $search_engines =  "";
    $lastViewPage =  "";
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $id =  $row["id"];
      $guest_id =  $row["guest_id"];
      $referrer_page =  $row["referrer_page"];
      $current_page =  $row["current_page"];
      $enter_time =  $row["enter_time"];
      $leave_time =  $row["leave_time"];
      $view_time =  $row["view_time"];
      $socket_out_flag =  $row["socket_out_flag"];
      $brower =  $row["brower"];
      $device =  $row["device"];
      $keyword =  $row["keyword"];
      $search_engines =  $row["search_engines"];
      $nowTime = strftime('%Y-%m-%d %H:%M:%S', time());
      if($leave_time ==null){
        $leave_time = $nowTime;
        $view_time = computeViewTime($enter_time,$leave_time);
        // 定义 SQL 查询语句
        $sql = "UPDATE seogtp_browse_statistics_browse_detail SET leave_time=? ,socket_out_flag = 1, view_time = ? WHERE id = ? order by id desc limit 1 ";
        // 创建预处理语句对象
        $stmt = $conn->prepare($sql);
        // 绑定参数
        $stmt->bind_param('sss', $leave_time,$view_time,$id);
        // 执行查询
        $stmt->execute();
      }
      $resultMap[$i]=array(
        'id'=>$id,'guest_id'=>$guest_id,'referrer_page'=>$referrer_page,
        'current_page'=>$current_page,'enter_time'=>$enter_time,'leave_time'=>$leave_time,
        'view_time'=>$view_time,'socket_out_flag'=>$socket_out_flag,'brower'=>$brower,
        'device'=>$device,
       );
      $i= $i +1;
    }
    $referrer_page='';
    $into_page='';
    $enter_time =0;
    $leave_time =0;
    $view_time =0;
    $brower =0;
    $device =0;
    $browse_page_count =$i;
    $jump_out_flag =1;
    for ($j=0; $j <$i+1 ; $j++) { 
      //统计出各个表需要的数据
      $data = $resultMap[i];
      if($j==0){
        $enter_time = $data['enter_time'];
        $referrer_page = $data['referrer_page'];
        $into_page=$data['current_page'];
        $brower =$data['brower'];
        $device =$data['device'];
      }
      if($j==$i){
        $leave_time =$data['leave_time'];
        if($j>0){
          $jump_out_flag =0;
        }
      }
      $view_time = $view_time + $data['view_time'];
    }

    //更新seogtp_browse_statistics_periodic
    //先从访客表中获取是否是新访客，
    $sql = 'SELECT lastly_browse_time,new_guest_flag,country,total_view_time,total_browse_time,total_browse_day,total_browse_page,total_jump_out 
    FROM seogtp_browse_statistics_guest WHERE guest_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$guest_ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $today = strftime('%Y-%m-%d', time());
    $country="";
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $new_guest_flag =  $row["new_guest_flag"];
      $country =  $row["country"];
      $lastly_browse_time =  $row["lastly_browse_time"];
      $total_view_time =  $row["total_view_time"];
      $total_browse_time =  $row["total_browse_time"];
      $total_browse_day =  $row["total_browse_day"];
      $total_browse_page =  $row["total_browse_page"];
      $total_jump_out =  $row["total_jump_out"];
      //比较俩时间是否属于同一天
      $newDayFlag=isSameDay($lastly_browse_time,$nowTime);
      //24小时内不算访问次数，目前这么做的，但是估计有问题。
      $new_browse_flag=isNewBrowse($lastly_browse_time,$nowTime);
      //更新访客表
      $sql = "UPDATE seogtp_browse_statistics_guest 
      SET lastly_browse_time=?,total_view_time=? total_browse_time=? total_browse_day=? total_jump_out=? 
       WHERE id =?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('siiiii',$nowTime, $total_view_time+$view_time,$browse_count+1,$total_browse_day+$newDayFlag,
      $total_browse_page+$browse_page_count,$total_jump_out+$jump_out_flag,$id);
      $stmt->execute();

      $sql = "SELECT browse_count FROM `seogtp_browse_statistics_periodic` WHERE new_guest_flag =? AND create_time =? AND `type`=1";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('is', $new_guest_flag,$today);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $browse_count =  $row["browse_count"];
        $id =  $row["id"];
        $sql = "UPDATE seogtp_browse_statistics_periodic SET browse_count=? WHERE id =? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $browse_count+1,$id);
        $stmt->execute();
      }else{
        $sql = "INSERT INTO seogtp_browse_statistics_periodic (browse_count, new_guest_flag, `type`,create_time)
        VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiis', 1, $new_guest_flag, 1,$today);
        $stmt->execute();
        
        if ($conn->query($sql) === TRUE) {
            echo "新记录插入成功";
        } 
      }
    }
    
    //插入seogtp_browse_statistics_everytime
    //插入前获取来源分类和社交媒体
    $source_social_media = getSourceAndSocialMedia($referrer_page);
    $sql = "INSERT INTO seogtp_browse_statistics_everytime 
    (guest_id, enter_time, leave_time,view_time,brower,device,browse_page_count,country,jump_out_flag,source,social_media,new_browse_flag)
    VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ississii',
     $guest_id, $enter_time, $leave_time,$view_time,$brower,$device,$browse_page_count,$jump_out_flag,$source_social_media['source'],$source_social_media['social_media'],$new_browse_flag);
    $stmt->execute();
    
    if ($conn->query($sql) === TRUE) {
        echo "新记录插入成功";
    } 

    //更新seogtp_browse_statistics_current_page_statistics
    $sql = "SELECT * FROM `seogtp_browse_statistics_current_page_statistics`
    WHERE referrer_page=? AND current_page=? AND country=? AND new_guest_flag=? AND keyword=?
    AND browser=? AND search_engines=? and create_time =? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissss', $referrer_page,current_page,$country,$new_guest_flag,$keyword,$browser,$search_engines,$today);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $id =  $row["id"];
      $guest_count =  $row["guest_count"];
      $browse_count =  $row["browse_count"];
      $total_view_time =  $row["total_view_time"];
      $total_jump_out =  $row["total_jump_out"];
      $total_exit =  $row["total_exit"];
      
      $sql = "UPDATE seogtp_browse_statistics_current_page_statistics 
      SET guest_count=? browse_count=? total_view_time=? total_jump_out=? total_exit=?
       WHERE id =?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('iiiiii', $guest_count+$new_browse_flag,$browse_count+1, 
      $total_view_time+$view_time ,$total_jump_out+$jump_out_flag,$total_exit+1,$id);
      $stmt->execute();
    }else{
      $sql = "INSERT INTO seogtp_browse_statistics_current_page_statistics 
      (guest_count, browse_count,total_view_time,total_jump_out,total_exit,
      referrer_page,current_page,country,new_guest_flag,keyword,browser, search_engines,create_time)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('iiiiissssssss',$new_browse_flag,1,$view_time,$jump_out_flag,1,
      $referrer_page,$current_page,$country,$new_guest_flag,$keyword,$brower,$search_engines,$today);
      $stmt->execute();
      if ($conn->query($sql) === TRUE) {
          echo "新记录插入成功";
      } 
    }
    //更新seogtp_browse_statistics_source_statistics
    $sql = "SELECT id,browse_count,total_jump_out,total_browse_page,total_view_time FROM `seogtp_browse_statistics_source_statistics`
    WHERE referrer_page=? AND into_page=? AND country=? AND new_guest_flag=? AND keyword=?
    AND browser=? AND search_engines=? and create_time =? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssissss', $referrer_page,$into_page,$country,$new_guest_flag,$keyword,$browser,$search_engines,$today);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $id =  $row["id"];
      $browse_count =  $row["browse_count"];
      $total_jump_out =  $row["total_jump_out"];
      $total_browse_page =  $row["total_browse_page"];
      $total_view_time =  $row["total_view_time"];
      
      $sql = "UPDATE seogtp_browse_statistics_source_statistics 
      SET browse_count=? total_jump_out=? total_browse_page=? total_view_time=? 
       WHERE id =?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('iiiii', $browse_count+1,$total_jump_out+$jump_out_flag,$total_browse_page+$browse_page_count, 
      $total_view_time+$view_time,$id);
      $stmt->execute();
    }else{
      $sql = "INSERT INTO seogtp_browse_statistics_source_statistics 
      ( browse_count,total_jump_out, total_browse_page, total_view_time,
      referrer_page,into_page,country,new_guest_flag,keyword,browser, search_engines,create_time)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('iiiissssssss', 1,$jump_out_flag,$browse_page_count,$view_time,
      $referrer_page,$into_page,$country,$new_guest_flag,$keyword,$brower,$search_engines,$today);
      $stmt->execute();
      if ($conn->query($sql) === TRUE) {
          echo "新记录插入成功";
      } 
    }
    $conn->close();
    // 删除某个键值对
    unset($k_guest_ip[$k]);
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
/**
 * 判断俩日期是否是同一天
 */
function isSameDay($lastly_browse_time,$nowTime){
  $split1 = explode(" ",$lastly_browse_time);
  $day1=$split1[0];
  $split2 = explode(" ",$nowTime);
  $day2=$split2[0];
  return $day1==$day2;
}
/**
* 判断俩日期是否超过一天
*/
function isNewBrowse($lastly_browse_time,$nowTime){
  $timestamp1 = strtotime($lastly_browse_time);
  $timestamp2 = strtotime($nowTime);
  
  $timeDiff = abs($timestamp2 - $timestamp1);
  $hoursDiff = $timeDiff / (60 * 60);
  
  if ($hoursDiff >= 24) {
    return 1;
  } else {
    return 0;
  }
  
 return $day1==$day2;
}

/**
 * 根据来源页判断处来源类型，如果是社交媒体，就判断出具体社交媒体
 */
function getSourceAndSocialMedia($referrer_page){
  $source = '';
  $social_media;
  if(!isset($referrer_page)&&empty($referrer_page)){
    $source ='直接访问';
  }else if(strpos($str, 'email') !== false){
    $source ='邮件营销';
  }else{
    //先确认是否是社交媒体
    $social_media_flag=false;
    $search_flag=false;
    $conn = new mysqli("localhost", "yousentest", "siYaojing.748", "www_yousentest_com");
    // $conn = new mysqli("localhost", "www_grdtest_com", "jW4QmYmmDs", "www_grdtest_com");
    $sql = 'SELECT social_media,keyword FROM seogtp_browse_statistics_social_media';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
      // 处理每一行数据，$row 是一个对象，对象的属性名对应查询结果的字段名，属性值对应字段的值
      // 例如，$row->column_name 可以获取某个字段的值
      $keyword =  $row["keyword"];
      $social_media2 =  $row["social_media"];
      if(strpos($str, $keyword) !== false){
        $social_media_flag=true;
        $social_media=$social_media2;
        $source ='社交媒体';
        break;
      }
    }
    //再确认是否是搜索
    if(!$social_media_flag){
      $sql = 'SELECT search_engines,keyword FROM seogtp_browse_statistics_search_engines';
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = mysqli_fetch_assoc($result)) {
        // 处理每一行数据，$row 是一个对象，对象的属性名对应查询结果的字段名，属性值对应字段的值
        // 例如，$row->column_name 可以获取某个字段的值
        $keyword =  $row["keyword"];
        $search_engines =  $row["search_engines"];
        if(strpos($str, $keyword) !== false){
          $search_flag=true;
          $social_media=$social_media2;
          $source ='搜索引擎';
          break;
        }
    }

    }
    //否则是其他
    if(!$social_media_flag&&!$search_flag){
      $source ='其他';
    }
    return array(
      'source' => $source,
      'social_media' => $social_media,
    );
    $conn->close();
  }
}
