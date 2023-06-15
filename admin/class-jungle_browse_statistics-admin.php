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
		global $pagenow;
		if (
			$pagenow == 'admin.php'
		) {
			wp_enqueue_style('elementPlusCss', plugin_dir_url(__FILE__) . 'css/elementPlus.css', array(), $this->version, 'all');
		}
	}
	public function enqueue_scripts()
	{
		global $pagenow;
		if (
			$pagenow == 'admin.php'
		) {
			wp_enqueue_script('vue3', plugin_dir_url(__FILE__) . 'js/vue.js', array(), $this->version, false);
			wp_enqueue_script('elementPlusAdmin', plugin_dir_url(__FILE__) . 'js/elementPlus.js', array('vue3'), $this->version, false);

			if (isset($_GET['page'])) {
				$current_menu =  $_GET['page'];
				if ($current_menu == 'seogtp_statistics') {
					wp_enqueue_script('seogtp_statistics_overview', plugin_dir_url(__FILE__) . 'js/seogtp_statistics_overview.js', array('elementPlusAdmin'), $this->version, true);
				} else if ($current_menu == 'visitor_analytics') {
					wp_enqueue_script('seogtp_statistics_visitor_analytics', plugin_dir_url(__FILE__) . 'js/seogtp_statistics_visitor_analytics.js', array('elementPlusAdmin'), $this->version, true);
				} else if ($current_menu == 'accessSource_analytics') {
					wp_enqueue_script('seogtp_statistics_accessSource_analytics', plugin_dir_url(__FILE__) . 'js/seogtp_statistics_accessSource_analytics.js', array('elementPlusAdmin'), $this->version, true);
				} else if ($current_menu == 'pages_view_analytics') {
					wp_enqueue_script('seogtp_statistics_pages_view_analytics', plugin_dir_url(__FILE__) . 'js/seogtp_statistics_pages_view_analytics.js', array('elementPlusAdmin'), $this->version, true);
				} else if ($current_menu == 'seogtp_statistics_setup') {
					wp_enqueue_script('seogtp_statistics_setup', plugin_dir_url(__FILE__) . 'js/seogtp_statistics_setup.js', array('elementPlusAdmin'), $this->version, true);
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
	/************************************************流量数据分析 */
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
		/************************************************总览 */
		add_submenu_page(
			'seogtp_statistics',
			'visitor_analytics',
			'访客分析',
			'manage_options',
			'visitor_analytics',
			array($this, 'seogtp_statistics_visitor_analytics')
		);
		/************************************************访客分析 */
		add_submenu_page(
			'seogtp_statistics',
			'accessSource_analytics',
			'来源分析',
			'manage_options',
			'accessSource_analytics',
			array($this, 'seogtp_statistics_accessSource_analytics')
		);
		/************************************************来源分析 */
		add_submenu_page(
			'seogtp_statistics',
			'pages_view_analytics',
			'页面分析',
			'manage_options',
			'pages_view_analytics',
			array($this, 'seogtp_statistics_pages_view_analytics')
		);
		/************************************************页面分析 */
		add_submenu_page(
			'seogtp_statistics',
			'seogtp_statistics_setup',
			'设置',
			'manage_options',
			'seogtp_statistics_setup',
			array($this, 'seogtp_statistics_setup')
		);
		/************************************************设置 */
	}
	public function seogtp_statistics_overview() // 页面总览
	{
		include_once('partials/seogtp_statistics_overview.php');
	}
	public function seogtp_statistics_visitor_analytics() // 访客分析
	{
		include_once('partials/seogtp_statistics_visitor_analytics.php');
	}
	public function seogtp_statistics_accessSource_analytics() // 来源分析
	{
		include_once('partials/seogtp_statistics_accessSource_analytics.php');
	}
	public function seogtp_statistics_pages_view_analytics() // 页面分析
	{
		include_once('partials/seogtp_statistics_pages_view_analytics.php');
	}
	public function seogtp_statistics_setup() // 设置
	{
		include_once('partials/seogtp_statistics_setup.php');
	}
}
