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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/jungle_browse_statistics-public.js', array('jquery'), $this->version, false);
		// 获取当前用户的IP地址
		$ip_address = $_SERVER['REMOTE_ADDR'];

		// 获取IP地址的位置信息，你需要使用你自己的函数替换下面的代码
		$location = get_location_by_ip($ip_address);

		// 把IP和位置信息传给JavaScript
		wp_localize_script($this->plugin_name, 'jungle_browse_statistics', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'ip_address' => $ip_address,
			'location' => $location,
			'nonce' => wp_create_nonce('jungle_browse_statistics_nonce'),
		));
	}
	public function handle_ajax()
	{
		check_ajax_referer('jungle_browse_statistics_nonce');

		// 处理Ajax请求，例如保存数据到数据库
		$ip_address = sanitize_text_field($_POST['ip_address']);
		$location = sanitize_text_field($_POST['location']);
		$uid = sanitize_text_field($_POST['uid']);

		// 在这里添加你的代码，例如保存数据到数据库

		// 最后返回结果
		wp_send_json_success();
	}
}
