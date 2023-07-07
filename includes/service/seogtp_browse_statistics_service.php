<?php
//浏览器表接接口，插入接口
function seogtp_browse_statistics_browser_add($array){
    $browser = $array['browser'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_browser';

    $data = array(
        'browser' => $browser,
    );
    $result=$wpdb->insert( $table_name_pages_view_statistics, $data );
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_browser_add', 'seogtp_browse_statistics_browser_add');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_browser_add', 'seogtp_browse_statistics_browser_add');

//浏览器表接接口，查询返回接口
function seogtp_browse_statistics_browser_list($array){
    global $wpdb;
    $table_name='seogtp_browse_statistics_browser';

    $query = $wpdb->prepare("SELECT browser FROM {$table_name} ");
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'browser' => $row->browser,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_browser_list', 'seogtp_browse_statistics_browser_list');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_browser_list', 'seogtp_browse_statistics_browser_list');

//社交媒体表接接口，插入接口
function seogtp_browse_statistics_social_media_add($array){
    $social_media = $array['social_media'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_social_media';

    $data = array(
        'social_media' => $social_media,
    );
    $result=$wpdb->insert( $table_name_pages_view_statistics, $data );
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_social_media_add', 'seogtp_browse_statistics_social_media_add');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_social_media_add', 'seogtp_browse_statistics_social_media_add');

//社交媒体表接接口，查询返回接口
function seogtp_browse_statistics_social_media_list($array){
    global $wpdb;
    $table_name='seogtp_browse_statistics_social_media';

    $query = $wpdb->prepare("SELECT social_media FROM {$table_name} ");
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'social_media' => $row->social_media,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_social_media_list', 'seogtp_browse_statistics_social_media_list');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_social_media_list', 'seogtp_browse_statistics_social_media_list');

//搜索引擎表接接口，插入接口
function seogtp_browse_statistics_search_engines_add($array){
    $search_engines = $array['search_engines'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_search_engines';

    $data = array(
        'search_engines' => $search_engines,
    );
    $result=$wpdb->insert( $table_name_pages_view_statistics, $data );
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_search_engines_add', 'seogtp_browse_statistics_search_engines_add');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_search_engines_add', 'seogtp_browse_statistics_search_engines_add');

//搜索引擎表接接口，查询返回接口
function seogtp_browse_statistics_search_engines_list($array){
    global $wpdb;
    $table_name='seogtp_browse_statistics_search_engines';

    $query = $wpdb->prepare("SELECT search_engines FROM {$table_name} ");
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'search_engines' => $row->search_engines,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_search_engines_list', 'seogtp_browse_statistics_search_engines_list');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_search_engines_list', 'seogtp_browse_statistics_search_engines_list');

//国家表接接口，插入接口
function seogtp_browse_statistics_country_add($array){
    $country = $array['country'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_country';

    $data = array(
        'country' => $country,
    );
    $result=$wpdb->insert( $table_name_pages_view_statistics, $data );
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_country_add', 'seogtp_browse_statistics_country_add');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_country_add', 'seogtp_browse_statistics_country_add');

//国家表接接口，查询返回接口
function seogtp_browse_statistics_country_list($array){
    global $wpdb;
    $table_name='seogtp_browse_statistics_country';

    $query = $wpdb->prepare("SELECT country FROM {$table_name} ");
    $results = $wpdb->get_results($query);
    if ($results) {
        foreach ($results as $row) {
            $data = array(
                'country' => $row->country,
            );
            array_push($result,$data);
        }
    }
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_country_list', 'seogtp_browse_statistics_country_list');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_country_list', 'seogtp_browse_statistics_country_list');

//修改source表的comment接口
function seogtp_browse_statistics_source_comment_add($array){
    $referrer_page = $array['referrer_page'];
    $comment = $array['comment'];

    global $wpdb;
    $table_name='seogtp_browse_statistics_source_statistics';

    $result=$wpdb->update($table_name, array(
        'new_guest_flag' => $is_new,
    ), array(
        'guest_id' => $guest_id 
    ));
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_source_comment_add', 'seogtp_browse_statistics_source_comment_add');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_source_comment_add', 'seogtp_browse_statistics_source_comment_add');

//TODO修改时区差值的接口，默认美国太平洋时区
function seogtp_browse_statistics_datezone_update($timezone_difference){
    update_option('timezone_difference',$timezone_difference);
    wp_send_json_success($result);
}
add_action('wp_ajax_seogtp_browse_statistics_datezone_update', 'seogtp_browse_statistics_datezone_update');
add_action('wp_ajax_nopriv_seogtp_browse_statistics_datezone_update', 'seogtp_browse_statistics_datezone_update');
?>