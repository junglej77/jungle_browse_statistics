<?php
class Jungle_browse_statistics_Admin
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
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/jungle_browse_statistics-admin.css', array(), $this->version, 'all');
	}
	public function enqueue_scripts()
	{

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/jungle_browse_statistics-admin.js', array('jquery'), $this->version, true);
		global $pagenow;
		if (
			$pagenow == 'admin.php'
		) {

			if (isset($_GET['page'])) {

				$cuurent_menu =  $_GET['page'];
				if ($cuurent_menu == 'seogtp_statistics') {
					wp_enqueue_script('seogtp_statistics_overview', plugin_dir_url(__FILE__) . 'js/seogtp_statistics_overview.js', array(), $this->version, true);
				}
			}
		}
	}
	public function add_plugin_admin_menu() // 主菜单
	{
		add_menu_page(
			'seogtp statistics',    // 主菜单名称
			'流量数据分析',    // 菜单标题
			'manage_options', // 用户权限
			'seogtp_statistics',    // 菜单标识
			array($this, 'seogtp_statistics_overview') // 回调函数
		);
	}
	public function add_plugin_admin_submenu() // 子菜单
	{
		add_submenu_page(
			'seogtp_statistics',     // 父菜单标识
			'overview',       // 子菜单页面标题
			'总览',       // 子菜单标题
			'manage_options',  // 用户权限
			'seogtp_statistics',   // 子菜单标识
			array($this, 'seogtp_statistics_overview') // 回调函数
		);
		add_submenu_page(
			'seogtp_statistics',     // 父菜单标识
			'online',           // 子菜单页面标题
			'在线人数',           // 子菜单标题
			'manage_options',  // 用户权限
			'online',  // 子菜单标识
			array($this, 'seogtp_statistics_online') // 回调函数
		);
		add_submenu_page(
			'seogtp_statistics',     // 父菜单标识
			'pages_view_statistics',           // 子菜单页面标题
			'页面统计',           // 子菜单标题
			'manage_options',  // 用户权限
			'pages_view_statistics',  // 子菜单标识
			array($this, 'seogtp_statistics_pages_view_statistics') // 回调函数
		);
		add_submenu_page(
			'seogtp_statistics',     // 父菜单标识
			'Setup',           // 子菜单页面标题
			'设置',           // 子菜单标题
			'manage_options',  // 用户权限
			'Setup',  // 子菜单标识
			array($this, 'seogtp_statistics_setup') // 回调函数
		);
	}
	public function seogtp_statistics_overview() // 页面总览
	{
		include_once('partials/seogtp_statistics_overview.php');
	}
	public function seogtp_statistics_online() // 在线人数
	{
		include_once('partials/seogtp_statistics_online.php');
	}
	public function seogtp_statistics_pages_view_statistics() // 页面统计
	{
		include_once('partials/seogtp_statistics_pages_view_statistics.php');
	}
	public function seogtp_statistics_setup() // 设置
	{
		include_once('partials/seogtp_statistics_setup.php');
	}
}
