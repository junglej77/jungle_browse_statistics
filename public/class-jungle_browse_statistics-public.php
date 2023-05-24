<?php
require_once 'utils/utils.php'; // 引入工具函数
require_once 'ajax/index.php'; // 引入所有的ajax
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

		// 把IP和位置信息传给JavaScript
		wp_localize_script($this->plugin_name, 'jungle_browse_statistics', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('jungle_browse_statistics_nonce'), // 后台生成验证安全验证机制码，前台请求_ajax_nonce需要传相关值
		));
	}
}
