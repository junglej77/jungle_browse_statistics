<?php
class Jungle_browse_statistics_Public
{
	private $plugin_name;
	private $version;
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/jungle_browse_statistics-public.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/jungle_browse_statistics-public.js', array(), $this->version, true);

		function get_location_by_ip($ip)
		{
			$api_key = '6ba46d1bbadb4a8dbad768669a95aa3b'; // 你需要获取API key
			$url = "https://api.ipgeolocation.io/ipgeo?apiKey=$api_key&ip=$ip";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);

			curl_close($ch);

			if ($response === false) {
				return null;
			}

			$data = json_decode($response, true);

			if (isset($data['country_name']) && isset($data['city'])) {
				return $data['country_name'] . ', ' . $data['city'];
			} else {
				return null;
			}
		};
		// 获取当前用户的IP地址
		$ip_address = $_SERVER['REMOTE_ADDR'];

		// 获取IP地址的位置信息，你需要使用你自己的函数替换下面的代码
		$location = get_location_by_ip($ip_address);

		// 把IP和位置信息传给JavaScript

		wp_localize_script($this->plugin_name, 'jungle_browse_statistics', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'ip_address' => $ip_address,
			'location' => $location,
			'nonce' => wp_create_nonce('jungle_browse_statistics_nonce'), // 后台生成验证安全验证机制码，前台请求_ajax_nonce需要传相关值
		));
	}
	public function user_cache()
	{
		/*check_ajax_referer()是WordPress中的一个函数，用于验证在执行AJAX请求时提交的nonce值。
		*nonce值是一种安全机制，用于防止恶意请求或CSRF攻击（跨站请求伪造）
		*/

		check_ajax_referer('jungle_browse_statistics_nonce');
		// 处理Ajax请求，例如保存数据到数据库
		$ip_address = sanitize_text_field($_POST['ip_address']);
		$location = sanitize_text_field($_POST['location']);
		$uid = sanitize_text_field($_POST['uid']);

		// 在这里添加你的代码，例如保存数据到数据库
		global $wpdb;
		$table_name = $wpdb->prefix . 'user_cache';
		$data = array(
			'ip_address' => $ip_address,
			'location' => $location,
			'uid' => $uid,
		);
		$format = array('%s', '%s', '%s'); //设置数据的格式，这里都是字符串
		$wpdb->insert($table_name, $data, $format);

		// 最后返回结果
		wp_send_json_success();
	}
}
