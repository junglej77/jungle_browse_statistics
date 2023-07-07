<?php
/**
 * 当前页面分析接口
 */

/**
 * 获取页面访问的分析数据
 */
function seogtp_browse_statistics_current_analysis_rank($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $groupBy= $array['groupBy'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_current_page_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT current_page, SUM(guest_count) guest_count,SUM(browse_count) browse_count,
    ROUND(SUM(browse_count) / SUM(guest_count), 2) browse_depth,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent,
    ROUND(SUM(total_exit) / SUM(guest_count), 2) exit_percent
    FROM {$table_name} 
    WHERE  %s=%s create_time AND BETWEEN %s AND %s
    GROUP BY current_page
    ORDER BY %s ",$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'current_page' => $row->current_page,
                'guest_count' => $row->guest_count,
                'browse_count' => $row->browse_count,
                'browse_depth' => $row->browse_depth,
                'view_time_average' => $row->view_time_average,
                'jump_out_percent' => $row->jump_out_percent,
                'exit_percent' => $row->exit_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_current_analysis_rank', 'seogtp_browse_statistics_current_analysis_rank');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_current_analysis_rank', 'seogtp_browse_statistics_current_analysis_rank');

/**
 * 获取最新访问详情
 */
function seogtp_browse_statistics_current_analysis_detail($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $detail_type= $array['$detail_type'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];
    $current_page= $array['current_page'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_current_page_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT %s detail_type,
    SUM(guest_count) guest_count,SUM(browse_count) browse_count,
    ROUND(SUM(browse_count) / SUM(guest_count), 2) browse_depth,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent,
    ROUND(SUM(total_exit) / SUM(guest_count), 2) exit_percent
    FROM {$table_name} 
    WHERE current_page = %s  AND %s=%s AND create_time BETWEEN %s AND %s 
    GROUP BY %s
    ORDER BY %s ",$detail_type,$current_page,$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$detail_type,$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'detail_type' => $row->detail_type,
                'guest_count' => $row->guest_count,
                'browse_count' => $row->browse_count,
                'browse_depth' => $row->browse_depth,
                'view_time_average' => $row->view_time_average,
                'jump_out_percent' => $row->jump_out_percent,
                'exit_percent' => $row->exit_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_current_analysis_detail', 'seogtp_browse_statistics_current_analysis_detail');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_current_analysis_detail', 'seogtp_browse_statistics_current_analysis_detail');


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