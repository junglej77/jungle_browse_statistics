<?php
/**
 * 总览页面接口
 */

/**
 * 访客统计
 */
function seogtp_browse_statistics_overview_periodic($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    
    //分别获取新老用户的数据
    global $wpdb;
    $table_name='seogtp_browse_statistics_periodic';
    $old_user_now_hourly_data =array();
    $old_user_old_hourly_data =array();
    $new_user_now_hourly_data =array();
    $new_user_old_hourly_data =array();
    $old_user_now_daily_data =array();
    $old_user_old_daily_data =array();
    $new_user_now_daily_data =array();
    $new_user_old_daily_data =array();
    $query = $wpdb->prepare("SELECT browse_count,new_guest_flag,`type`,create_time FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s LIMIT %i ",$query_time['start_time'],$query_time['end_time'] ,$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        // // 查询到了记录
        foreach ($results as $row) {
            // 处理每一行数据
            $browse_count = $row->browse_count;
            $new_guest_flag = $row->new_guest_flag;
            $type = $row->type;
            $create_time = $row->create_time;
            $data = array(
                'browse_count'=>$browse_count,
                'type'=>$type,
                'enter_time'=>$enter_time,
            );
            if(same_day_flag && $type==0){
                $new_guest_flag==0 ?array_push($old_user_now_hourly_data,$data):array_push($new_user_now_hourly_data,$data);
            }elseif(!same_day_flag && $type==1){
                $new_guest_flag==0 ?array_push($old_user_now_daily_data,$data):array_push($new_user_now_daily_data,$data);
            }
        }
    }

    $query = $wpdb->prepare("SELECT browse_count,new_guest_flag,`type`,create_time FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s LIMIT %i ",$query_time['previous_start_time'],$query_time['previous_end_time'] ,$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        // // 查询到了记录
        foreach ($results as $row) {
            // 处理每一行数据
            $browse_count = $row->browse_count;
            $new_guest_flag = $row->new_guest_flag;
            $type = $row->type;
            $create_time = $row->create_time;
            $data = array(
                'browse_count'=>$browse_count,
                'type'=>$type,
                'create_time'=>$create_time,
            );
            if(same_day_flag && $type==0){
                $new_guest_flag==0 ?array_push($old_user_old_hourly_data,$data):array_push($new_user_old_hourly_data,$data);
            }elseif(!same_day_flag && $type==1){
                $new_guest_flag==0 ?array_push($old_user_old_daily_data,$data):array_push($new_user_old_daily_data,$data);
            }
        }
    }
    //访客统计
    $old_total_browse_count =0;
    $now_total_browse_count =0;
    $now_hourly_data =array();
    $old_hourly_data =array();
    $now_daily_data =array();
    $old_daily_data =array();
    //新旧访客
    $old_user_now_total_browse_count =0;
    $new_user_now_total_browse_count =0;
    $old_user_old_total_browse_count =0;
    $new_user_old_total_browse_count =0;

    $old_user_now_hourly_data =array();
    $new_user_now_hourly_data =array();
    $old_user_old_hourly_data =array();
    $new_user_old_hourly_data =array();
    $old_user_now_daily_data =array();
    $new_user_now_daily_data =array();
    $old_user_old_daily_data =array();
    $new_user_old_daily_data =array();
    
    foreach ($old_user_now_hourly_data as $value) {
        $create_time=$value['create_time'];
        $browse_count=$value['browse_count'];

        $now_total_browse_count = $now_total_browse_count+$browse_count;
        $old_user_now_total_browse_count = $old_user_now_total_browse_count+$browse_count;
       
        $data = $now_hourly_data[$create_time];
        isset($data)? $now_hourly_data[$create_time] =$data+$browse_count:$now_hourly_data[$create_time] =$browse_count;
    }
    foreach ($new_user_now_hourly_data as $value) {
        $create_time=$value['create_time'];
        $browse_count=$value['browse_count'];

        $now_total_browse_count = $now_total_browse_count+$browse_count;
        $new_user_now_total_browse_count = $new_user_now_total_browse_count+$browse_count;
       
        $data = $now_hourly_data[$create_time];
        isset($data)? $now_hourly_data[$create_time] =$data+$browse_count:$now_hourly_data[$create_time] =$browse_count;
    }
    foreach ($old_user_old_hourly_data as $value) {
        $create_time=$value['create_time'];
        $browse_count=$value['browse_count'];

        $old_total_browse_count = $old_total_browse_count+$browse_count;
        $old_user_old_total_browse_count = $old_user_old_total_browse_count+$browse_count;
       
        $data = $old_hourly_data[$create_time];
        isset($data)? $old_hourly_data[$create_time] =$data+$browse_count:$old_hourly_data[$create_time] =$browse_count;
    }
    foreach ($new_user_old_hourly_data as $value) {
        $create_time=$value['create_time'];
        $browse_count=$value['browse_count'];

        $old_total_browse_count = $old_total_browse_count+$browse_count;
        $new_user_old_total_browse_count = $new_user_old_total_browse_count+$browse_count;
       
        $data = $old_hourly_data[$create_time];
        isset($data)? $old_hourly_data[$create_time] =$data+$browse_count:$old_hourly_data[$create_time] =$browse_count;
    }
    
    $total_browse_count_percent = (($now_total_browse_count - $old_total_browse_count) / $old_total_browse_count) * 100;
    $total_browse_count_percent = number_format($total_browse_count_percent, 2);
    $old_user_total_browse_count_percent = (($old_user_now_total_browse_count - $old_user_old_total_browse_count) / $old_user_old_total_browse_count) * 100;
    $old_user_total_browse_count_percent = number_format($old_user_total_browse_count_percent, 2);
    $new_user_total_browse_count_percent = (($new_user_now_total_browse_count - $new_user_old_total_browse_count) / $new_user_old_total_browse_count) * 100;
    $new_user_total_browse_count_percent = number_format($new_user_total_browse_count_percent, 2);
    
    $result = array(
        'now_total_browse_count'=>$now_total_browse_count,
        'new_user_now_total_browse_count'=>$new_user_now_total_browse_count,
        'old_user_now_total_browse_count'=>$old_user_now_total_browse_count,
        'total_browse_count_percent'=>$total_browse_count_percent,
        'old_user_total_browse_count_percent'=>$old_user_total_browse_count_percent,
        'new_user_total_browse_count_percent'=>$new_user_total_browse_count_percent,
        'now_hourly_data'=>$now_hourly_data,
        'old_hourly_data'=>$old_hourly_data,
        'now_daily_data'=>$now_daily_data,
        'old_daily_data'=>$old_daily_data,
        'old_user_now_hourly_data'=>$old_user_now_hourly_data,
        'new_user_now_hourly_data'=>$new_user_now_hourly_data,
        'old_user_old_hourly_data'=>$old_user_old_hourly_data,
        'new_user_old_hourly_data'=>$new_user_old_hourly_data,
        'old_user_now_daily_data'=>$old_user_now_daily_data,
        'new_user_now_daily_data'=>$new_user_now_daily_data,
        'old_user_old_daily_data'=>$old_user_old_daily_data,
        'new_user_old_daily_data'=>$new_user_old_daily_data,
    );
    wp_send_json_success($result);
} 

add_action('wp_ajax_seogtp_browse_statistics_overview_periodic', 'seogtp_browse_statistics_overview_periodic');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_overview_periodic', 'seogtp_browse_statistics_overview_periodic');

 /**
  * 地域分布、访问设备、访问来源、社媒来源统计
  */
function seogtp_browse_statistics_overview_source($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    
    global $wpdb;
    $table_name='seogtp_browse_statistics_everytime';
    $source_data =array();
    $social_media_data =array();
    $device_data =array();
    $country_data =array();
    $source_temp =array();
    $social_media_temp =array();
    $device_temp =array();
    $country_temp =array();
    $sources ='(';
    $social_medias ='(';
    $devices ='(';
    $countrys ='(';
    //访问来源  
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,source FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY source ORDER BY `count` DESC LIMIT %i ",
    $query_time['start_time'],$query_time['end_time'],$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $source = $row->source;
            $count = $row->count;
            $sources.$source.',';
            $data = array(
                'source'=>$source,
                'count'=>$count,
            );
            array_push($source_temp,$data);
        }
        $sources = rtrim($sources, ','); 
        $sources.')';
        
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,source FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s AND source IN %s GROUP BY source  ",
    $query_time['previous_start_time'],$query_time['previous_end_time'],$sources);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $source = $row->source;
            $count = $row->count;
            foreach ($source_temp as $data) {
                if($source==$data['source']){
                    $percent = (($data['count'] - $count) / $count) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'source'=>$source,
                        'count'=>$count,
                        'percent'=>$percent,
                    );
                    array_push($source_data,$data1);
                    }
                }
            }
        }
    }
    //社媒来源
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,social_media FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY social_media  ORDER BY `count` DESC LIMIT %i ",$query_time['start_time'],$query_time['end_time'],$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $social_media = $row->social_media;
            $count = $row->count;
            $social_medias.$social_media.',';
            $data = array(
                'social_media'=>$social_media,
                'count'=>$count,
            );
            array_push($social_media_temp,$data);
        }
        $social_medias = rtrim($social_medias, ','); 
        $social_medias.')';
        
        $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,social_media FROM  {$table_name} 
        WHERE create_time BETWEEN %s AND %s AND social_media IN %s GROUP BY social_media  ",
        $query_time['previous_start_time'],$query_time['previous_end_time'],$social_medias);
        $results = $wpdb->get_results($query);

      if ($results) {
        foreach ($results as $row) {
            $social_media = $row->social_media;
            $count = $row->count;
            foreach ($social_media_temp as $data) {
                if($social_media==$data['social_media']){
                    $percent = (($data['count'] - $count) / $count) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'social_media'=>$social_media,
                        'count'=>$count,
                        'percent'=>$percent,
                    );
                    array_push($social_media_data,$data1);
                    }
                }
            }
        }
    }
    //访问设备
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,device FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY device  ORDER BY `count` DESC LIMIT %i ",$query_time['start_time'],$query_time['end_time'],$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $device = $row->device;
            $count = $row->count;
            $devices.$device.',';
            $data = array(
                'device'=>$device,
                'count'=>$count,
            );
            array_push($device_temp,$data);
        }
        $devices = rtrim($devices, ','); 
        $devices.')';
        
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,device FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s AND device IN %s GROUP BY device  ",
    $query_time['previous_start_time'],$query_time['previous_end_time'],$devices);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $device = $row->device;
            $count = $row->count;
            foreach ($device_temp as $data) {
                if($device==$data['device']){
                    $percent = (($data['count'] - $count) / $count) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'device'=>$device,
                        'count'=>$count,
                        'percent'=>$percent,
                    );
                    array_push($device_data,$data1);
                    }
                }
            }
        }
    }
    //地域分布
    
    $query = $wpdb->prepare("SELECT COUNT(1) as `count` ,guest.country FROM {$table_name} everytime
    INNER JOIN  seogtp_browse_statistics_guest guest
    ON everytime.guest_id = guest.id
    WHERE create_time BETWEEN %s AND %s GROUP BY guest.country  ORDER BY `count` DESC LIMIT %i ",$query_time['start_time'],$query_time['end_time'] ,$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $country = $row->country;
            $count = $row->count;
            $countrys.$country.',';
            $data = array(
                'country'=>$country,
                'count'=>$count,
            );
            array_push($country_temp,$data);
        }
        $countrys = rtrim($countrys, ','); 
        $countrys.')';
        
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,country FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s AND country IN %s GROUP BY country ",
    $query_time['previous_start_time'],$query_time['previous_end_time'],$countrys);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $country = $row->country;
            $count = $row->count;
            foreach ($country_temp as $data) {
                if($country==$data['country']){
                    $percent = (($data['count'] - $count) / $count) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'country'=>$country,
                        'count'=>$count,
                        'percent'=>$percent,
                    );
                    array_push($country_data,$data1);
                    }
                }
            }
        }
    }
    
    $result=array(
        'source_data'=>$source_data,
        'social_media_data'=>$social_media_data,
        'device_data'=>$device_data,
        'sourcecountry_data_data'=>$country_data,
    );
    wp_send_json_success($result);
} 

add_action('wp_ajax_seogtp_browse_statistics_overview_source', 'seogtp_browse_statistics_overview_source');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_overview_source', 'seogtp_browse_statistics_overview_source');



/**
 * 推荐页面排行
 */
function seogtp_browse_statistics_overview_referrer_page($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    
    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';
    $referrer_page_data =array();
    $referrer_page_temp =array();
    $referrer_pages ='(';
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,referrer_page FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY referrer_page  ORDER BY `count` DESC LIMIT %i ",$query_time['start_time'],$query_time['end_time'],$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $referrer_page = $row->referrer_page;
            $count = $row->count;
            $referrer_page.$referrer_page.',';
            $data = array(
                'referrer_page'=>$referrer_page,
                'count'=>$count,
            );
            array_push($referrer_page_temp,$data);
        }
        $referrer_pages = rtrim($referrer_pages, ','); 
        $referrer_pages.')';
        
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,referrer_page FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s AND referrer_page IN %s GROUP BY referrer_page ",
    $query_time['previous_start_time'],$query_time['previous_end_time'],$referrer_pages);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $referrer_page = $row->referrer_page;
            $count = $row->count;
            foreach ($referrer_page_temp as $data) {
                if($referrer_page==$data['referrer_page']){
                    $percent = (($data['count'] - $count) / $count) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'referrer_page'=>$referrer_page,
                        'count'=>$count,
                        'percent'=>$percent,
                    );
                    array_push($referrer_page_data,$data1);
                    }
                }
            }
        }
    }
    wp_send_json_success($referrer_page_data);
}
add_action('wp_ajax_seogtp_browse_statistics_overview_referrer_page', 'seogtp_browse_statistics_overview_referrer_page');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_overview_referrer_page', 'seogtp_browse_statistics_overview_referrer_page');



/**
 * 受访页面排行
 */
function seogtp_browse_statistics_overview_current($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    
    global $wpdb;

    //访问页面
    $table_name='seogtp_browse_statistics_current_page_statistics';
    $current_page_count_data =array();
    $current_page_count_temp =array();
    $current_pages_count ='(';
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,current_page FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY current_page  ORDER BY `count` DESC LIMIT %i ",$query_time['start_time'],$query_time['end_time'],$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $current_page = $row->current_page;
            $count = $row->count;
            $current_pages_count.$current_page.',';
            $data = array(
                'current_page'=>$current_page,
                'count'=>$count,
            );
            array_push($current_page_count_temp,$data);
        }
        $current_pages_count = rtrim($current_pages_count, ','); 
        $current_pages_count.')';
        
    $query = $wpdb->prepare("SELECT COUNT(1) AS `count`,current_page FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s AND current_page IN %s GROUP BY current_page ",
    $query_time['previous_start_time'],$query_time['previous_end_time'],$current_pages_count);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $current_page = $row->current_page;
            $count = $row->count;
            foreach ($current_page_temp as $data) {
                if($current_page==$data['current_page']){
                    $percent = (($data['count'] - $count) / $count) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'current_page'=>$current_page,
                        'count'=>$count,
                        'percent'=>$percent,
                    );
                    array_push($current_page_count_data,$data1);
                    }
                }
            }
        }
    }
    //访问时长
    $current_page_time_data =array();
    $current_page_time_temp =array();
    $current_pages_time ='(';
    $query = $wpdb->prepare("SELECT SUM(total_view_time) AS total_view_time,current_page FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY current_page  ORDER BY total_view_time DESC LIMIT %i ",$query_time['start_time'],$query_time['end_time'],$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $current_page = $row->current_page;
            $total_view_time = $row->total_view_time;
            $current_pages_time.$country.',';
            $data = array(
                'current_page'=>$current_page,
                'total_view_time'=>$total_view_time,
            );
            array_push($current_page_time_temp,$data);
        }
        $current_pages_time = rtrim($current_pages_times, ','); 
        $current_pages_time.')';
        
    $query = $wpdb->prepare("SELECT SUM(total_view_time) AS total_view_time,current_page FROM  {$table_name} 
    WHERE create_time BETWEEN %s AND %s GROUP BY current_page",
    $query_time['previous_start_time'],$query_time['previous_end_time'],$current_pages_time);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $current_page = $row->current_page;
            $total_view_time = $row->total_view_time;
            foreach ($current_page_time_temp as $data) {
                if($current_page==$data['current_page']){
                    $percent = (($data['total_view_time'] - $total_view_time) / $total_view_time) * 100;
                    $percent = number_format($percent, 2);
                    $data1 = array(
                        'current_page'=>$current_page,
                        'total_view_time'=>$total_view_time,
                        'percent'=>$percent,
                    );
                    array_push($current_page_time_data,$data1);
                    }
                }
            }
        }
    }
    $result = array(
        'current_page_count_data'=>$current_page_count_data,
        'current_page_time_data'=>$current_page_time_data,
    );
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_overview_current', 'seogtp_browse_statistics_overview_current');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_overview_current', 'seogtp_browse_statistics_overview_current');


function computeQueryTime($query_time){
    
    $split = explode(" - ",$query_time);
    $date1 = $split[0];
    $date2 = $split[1];
    // 计算日期差值
    $diff = strtotime($date2) - strtotime($date1);

    // 转换为周期数量
    $periods = floor($diff / (60 * 60 * 24 -1)); 
    $same_day_flag=false;
    if($periods==1){
        $same_day_flag=true;
    }
    // 获取前一个周期的起始日期
    $previousStartTime = date('Y-m-d H:i:s', strtotime($date1 . " -$periods days"));

    return array(
        'previous_start_time' => $previousStartTime,
        'previous_end_time' => $date1,
        'start_time' => $date1,
        'end_time' => $date2,
        'same_day_flag' => $same_day_flag,
    );
}
?>
