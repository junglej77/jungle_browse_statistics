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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/jungle_browse_statistics-admin.js', array('jquery'), $this->version, false);
		global $pagenow;
		if (
			$pagenow == 'admin.php'
		) {
			// 加载 Vue.js 库
			// wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array(), '2.6.12',);
			wp_enqueue_script('vue', 'https://unpkg.com/vue@next');

			// 加载 ElementUI 的 CSS 样式文件
			// wp_enqueue_style('element-ui', 'https://unpkg.com/element-ui/lib/theme-chalk/index.css');
			wp_enqueue_style('elementPlus', 'https://unpkg.com/element-plus@latest/theme-chalk/index.css');



			// 引入图标库
			wp_enqueue_script('elementPlusIcons', 'https://unpkg.com/@element-plus/icons-vue', array('vue'),);

			// 加载 ElementUI 的 JavaScript 文件
			// wp_enqueue_script('element-ui', 'https://unpkg.com/element-ui/lib/index.js', array('vue'), '2.15.1',);
			wp_enqueue_script('elementPlus', 'https://unpkg.com/element-plus@latest', array('vue'),);

			wp_enqueue_script('Sortable', 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.8.3/Sortable.min.js', array(), '',);

			//引入axios 请求
			wp_enqueue_script('axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', array(), '',);

			//引入自定义后台样式表
			wp_enqueue_style('admin-ui', get_stylesheet_directory_uri() . '/assets/css/admin-ui.css', array(), wp_get_theme()->get('Version'), 'all');
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
