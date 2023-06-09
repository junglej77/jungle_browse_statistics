<?php
    /**
     * 所有定时任务都在该脚本
     */
    jungle_browse_statistics_cron_exec();
    function jungle_browse_statistics_cron_exec(){
        global $wpdb;
        $table_name_pages_view = $wpdb->prefix . 'jungle_statistics_pages_view';
        //直接统计访问时长viewTime，

        $startTime = strtotime("now");
        $endTime = strtotime("-1 day");
        $querySql = "SELECT cache_ip,current_page, SUM(view_time) as view_time
        FROM $table_name_pages_view
        where enter_time BETWEEN '${startTime}' AND '${endTime}'
        GROUP BY cache_ip,current_page";
        $result = $wpdb->query($querySql);
        $insertSql = "insert into $table_name_pages_view (`cache_ip`,`view_page`,`view_time`,`create_time`) values";
        if ($result) {
        // 查询到了记录
        foreach ($wpdb->last_result as $post) {
            $cache_ip=$post->cacha_ip;
            $view_page=$post->current_page;
            $view_time=$post->view_time;
            $insertSql=$insertSql."(".$cache_ip.",".$view_page.",".$view_time.",".$startTime."),";
        }
        //把末尾多余的,删除
        $arr = explode(",", $insertSql);
        $last_item = array_pop($arr);
        $insertSql = implode(",", $arr);
        } else {
        // 没有查询到记录
        }
        //更新统计数据到数据库
    	$wpdb->query($insertSql);
    }

?>