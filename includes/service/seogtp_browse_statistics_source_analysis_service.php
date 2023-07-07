<?php
/**
 * 来源页面分析接口
 */

/**
 * 来源分析-推荐页排行
 */
function seogtp_browse_statistics_source_referrer_rank($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $groupBy= $array['groupBy'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT referrer_page,into_page, comment,SUM(guest_count) guest_count,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_browse_page) / SUM(guest_count), 2) browse_page_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent
    FROM {$table_name} 
    WHERE %s=%s AND create_time BETWEEN %s AND %s
    GROUP BY referrer_page,into_page
    ORDER BY %s ",$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'referrer_page' => $row->referrer_page,
                'into_page' => $row->guestinto_page_count,
                'comment' => $row->comment,
                'guest_count' => $row->guest_count,
                'view_time_average' => $row->view_time_average,
                'exit_percebrowse_page_averagent' => $row->browse_page_average,
                'jump_out_percent' => $row->jump_out_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_source_referrer_rank', 'seogtp_browse_statistics_source_referrer_rank');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_source_referrer_rank', 'seogtp_browse_statistics_source_referrer_rank');

/**
 * 来源分析-推荐页详情
 */
function seogtp_browse_statistics_source_referrer_detail($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $rankTypeValue= $array['rankTypeValue'];
    $detail_type= $array['$detail_type'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT %s detail_type,
    SUM(guest_count) guest_count,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_browse_page) / SUM(guest_count), 2) browse_page_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent
    FROM {$table_name} 
    WHERE  referrer_page=%s AND %s=%s AND create_time BETWEEN %s AND %s 
    GROUP BY referrer_page,%s
    ORDER BY %s ",$detail_type,$rankTypeValue,$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$detail_type,$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'detail_type' => $row->detail_type,
                'guest_count' => $row->guest_count,
                'view_time_average' => $row->view_time_average,
                'exit_percebrowse_page_averagent' => $row->browse_page_average,
                'jump_out_percent' => $row->jump_out_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_source_referrer_detail', 'seogtp_browse_statistics_source_referrer_detail');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_source_referrer_detail', 'seogtp_browse_statistics_source_referrer_detail');

/**
 * 来源分析-国家、设备、浏览器排行
 */
function seogtp_browse_statistics_source_rank($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $rankType= $array['rankType'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT %s,SUM(guest_count) guest_count,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_browse_page) / SUM(guest_count), 2) browse_page_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent
    FROM {$table_name} 
    WHERE  %s=%s AND create_time BETWEEN %s AND %s 
    GROUP BY %s
    ORDER BY %s ",$rankType,$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$rankType,$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'rankType' => $rankType,
                'guest_count' => $row->guest_count,
                'view_time_average' => $row->view_time_average,
                'exit_percebrowse_page_averagent' => $row->browse_page_average,
                'jump_out_percent' => $row->jump_out_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_source_rank', 'seogtp_browse_statistics_source_rank');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_sourseogtp_browse_statistics_source_rankce_country_rank', 'seogtp_browse_statistics_source_rank');

/**
 * 来源分析-国家、设备、浏览器详情
 */
function seogtp_browse_statistics_source_detail($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $rankType= $array['rankType'];
    $rankTypeValue= $array['rankTypeValue'];
    $sortBy= $array['sortBy'];
    $detail_type= $array['$detail_type'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT %s detail_type,
    SUM(guest_count) guest_count,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_browse_page) / SUM(guest_count), 2) browse_page_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent
    FROM {$table_name} 
    WHERE %s=%s AND %s=%s AND create_time BETWEEN %s AND %s
    GROUP BY %s
    ORDER BY %s ",$detail_type,$rankType,$rankTypeValue,$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$detail_type,$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'detail_type' => $row->detail_type,
                'guest_count' => $row->guest_count,
                'view_time_average' => $row->view_time_average,
                'exit_percebrowse_page_averagent' => $row->browse_page_average,
                'jump_out_percent' => $row->jump_out_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_source_detail', 'seogtp_browse_statistics_source_detail');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_source_detail', 'seogtp_browse_statistics_source_detail');

/**
 * 来源分析-搜索关键词排行
 */
function seogtp_browse_statistics_source_search_rank($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $groupBy= $array['groupBy'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT keyword,search_engines,SUM(guest_count) guest_count,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_browse_page) / SUM(guest_count), 2) browse_page_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent
    FROM {$table_name} 
    WHERE %s=%s AND create_time BETWEEN %s AND %s
    GROUP BY keyword,search_engines
    ORDER BY %s ",$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'keyword' => $row->keyword,
                'search_engines' => $row->search_engines,
                'guest_count' => $row->guest_count,
                'view_time_average' => $row->view_time_average,
                'exit_percebrowse_page_averagent' => $row->browse_page_average,
                'jump_out_percent' => $row->jump_out_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_source_search_rank', 'seogtp_browse_statistics_source_search_rank');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_source_search_rank', 'seogtp_browse_statistics_source_search_rank');

/**
 * 来源分析-搜索关键词详情
 */
function seogtp_browse_statistics_source_search_detail($array){
    $time= $array['queryTime'];
    $query_time=computeQueryTime($time);
    $limit= $array['limit'];
    $sortBy= $array['sortBy'];
    $rankTypeValue= $array['rankTypeValue'];
    $detail_type= $array['$detail_type'];
    //没有条件时下面俩值都输入1
    $searchBy= $array['searchBy'];
    $searchValue= $array['searchValue'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result = array();
    $query = $wpdb->prepare("SELECT %s detail_type,
    SUM(guest_count) guest_count,
    ROUND(SUM(total_view_time) / SUM(guest_count) view_time_average,
    ROUND(SUM(total_browse_page) / SUM(guest_count), 2) browse_page_average,
    ROUND(SUM(total_jump_out) / SUM(guest_count), 2) jump_out_percent
    FROM {$table_name} 
    WHERE  keyword=%s AND %s=%s AND create_time BETWEEN %s AND %s 
    GROUP BY keyword,%s
    ORDER BY %s ",$detail_type,$rankTypeValue,$searchBy,$searchValue,$query_time['start_time'],$query_time['end_time'],$detail_type,$sortBy);
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'detail_type' => $row->detail_type,
                'guest_count' => $row->guest_count,
                'view_time_average' => $row->view_time_average,
                'exit_percebrowse_page_averagent' => $row->browse_page_average,
                'jump_out_percent' => $row->jump_out_percent,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
// 注册 Ajax 动作
add_action('wp_ajax_seogtp_browse_statistics_source_search_detail', 'seogtp_browse_statistics_source_search_detail');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_source_search_detail', 'seogtp_browse_statistics_source_search_detail');




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