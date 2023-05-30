<?php
    function get_online_user_count(){
		$count = get_option( 'jungle_browse_statistics_online_count');
        if($count==null){
            $count = 0;
        }
    wp_send_json_success($count);
    }
?>
