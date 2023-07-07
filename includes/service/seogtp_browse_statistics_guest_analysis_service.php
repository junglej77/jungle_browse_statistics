<?php
/**
 * 访客页面接口
 */

/**
 * 获取当前访问人数、在线人数
 */
function seogtp_browse_statistics_guest_analysis_online_user_count($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];

    $online_count = get_option('seogtp_browse_statistics_online_count');
    if($online_count==null){
        $online_count = 0;
    }

    global $wpdb;
    $table_name='seogtp_browse_statistics_everytime';
    $browse_count = 0;
    $query = $wpdb->prepare("SELECT SUM(guest_id) browse_count FROM {$table_name} 
    WHERE create_time BETWEEN %s AND %s AND `type`=1 AND new_browse_flag =1 ",$query_time['start_time'],$query_time['end_time']);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $browse_count = $row->browse_count;
        }
    }
    $result = array(
        'online_count'=>$online_count,
        'browse_count'=>$browse_count,
    );
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_guest_analysis_online_user_count', 'seogtp_browse_statistics_guest_analysis_online_user_count');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_guest_analysis_online_user_count', 'seogtp_browse_statistics_guest_analysis_online_user_count');

/**
 * 获取最新访问详情
 */
function seogtp_browse_statistics_guest_analysis_browse_detail($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    
    global $wpdb;
    $table_name='seogtp_browse_statistics_everytime';
    $result =array();
    $query = $wpdb->prepare("SELECT everytime.* ,guest.new_guest_flag, guest.first_referrer_page,guest.create_time as first_browse_time,
    guest.total_view_time ,guest.total_browse_day ,guest.total_browse_time ,guest.total_browse_page ,guest.total_jump_out,`user`.email
    FROM  seogtp_browse_statistics_everytime everytime
    INNER JOIN seogtp_browse_statistics_guest guest
    ON everytime.guest_id = guest.id
    LEFT JOIN seogtp_user `user`
    ON everytime.guest_id = `user`.guest_id
    WHERE everytime.create_time BETWEEN %s AND %s LIMIT %i ",$query_time['start_time'],$query_time['end_time'] ,$limit);
    $results = $wpdb->get_results($query);

    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'enter_time' => $row->enter_time,
                'leave_time' => $row->leave_time,
                'view_time' => $row->view_time,
                'brower' => $row->brower,
                'device' => $row->device,
                'source' => $row->source,
                'enter_tisocial_mediame' => $row->social_media,
                'browse_page_count' => $row->browse_page_count,
                'new_guest_flag' => $row->new_guest_flag,
                'first_referrer_page' => $row->first_referrer_page,
                'first_browse_time' => $row->first_browse_time,
                'total_view_time' => $row->total_view_time,
                'total_browse_day' => $row->total_browse_day,
                'total_browse_time' => $row->total_browse_time,
                'total_browse_page' => $row->total_browse_page,
                'total_jump_out' => $row->total_jump_out,
                'email' => $row->email,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_guest_analysis_browse_detail', 'seogtp_browse_statistics_guest_analysis_browse_detail');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_guest_analysis_browse_detail', 'seogtp_browse_statistics_guest_analysis_browse_detail');


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