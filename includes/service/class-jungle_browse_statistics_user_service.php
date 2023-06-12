<?php
    function jungle_browse_statistics_get_online_user_count(){
		$count = get_option( 'jungle_browse_statistics_online_count');
        if($count==null){
            $count = 0;
        }
    wp_send_json_success($count);
    }
    // // 注册 Ajax 动作
    add_action('wp_ajax_jungle_browse_statistics_get_online_user_count', 'jungle_browse_statistics_get_online_user_count');
    add_action('wp_ajax_nopriv_jungle_browse_statistics_get_online_user_count', 'jungle_browse_statistics_get_online_user_count');
?>
