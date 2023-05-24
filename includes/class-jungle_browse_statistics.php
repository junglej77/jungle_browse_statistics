<?php
class Jungle_browse_statistics
{
	protected $loader;
	protected $plugin_name;
	protected $version;
	/**
	 * 是类的构造函数。在你创建类的新实例时，
	 * 它会自动调用。在这个函数中，设置了插件的名称和版本，
	 * 然后调用其他几个方法来加载插件的依赖，设定本地化（i18n）设置，定义管理面板的钩子，
	 * 以及定义公共页面的钩子。
	 */
	public function __construct()
	{
		if (defined('JUNGLE_BROWSE_STATISTICS_VERSION')) {
			$this->version = JUNGLE_BROWSE_STATISTICS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'jungle_browse_statistics';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}
	/**
	 * 是加载插件依赖的方法。
	 * 在这里，你包含了其他几个 PHP 文件，这些文件定义了插件的其他几个类，然后创建了 Jungle_browse_statistics_Loader 类的一个实例。
	 */
	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-jungle_browse_statistics-loader.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-jungle_browse_statistics-i18n.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-jungle_browse_statistics-admin.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-jungle_browse_statistics-public.php';
		$this->loader = new Jungle_browse_statistics_Loader();
	}
	/**
	 * 是设定插件本地化的方法。
	 * 创建 Jungle_browse_statistics_i18n 类的新实例，
	 * 并将 load_plugin_textdomain 动作添加到 plugins_loaded 钩子。
	 */
	private function set_locale()
	{
		$plugin_i18n = new Jungle_browse_statistics_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}
	/**
	 * define_admin_hooks() 和 define_public_hooks() 是定义 WordPress 钩子的方法。
	 * 在这里，你创建了 Jungle_browse_statistics_Admin 和 Jungle_browse_statistics_Public 类的新实例
	 * ，然后使用 Jungle_browse_statistics_Loader 类的实例将这些类的方法添加到 WordPress 钩子。
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Jungle_browse_statistics_Admin($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}
	private function define_public_hooks()
	{
		$plugin_public = new Jungle_browse_statistics_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 20);
	}
	/**
	 * 是运行插件的方法。
	 * 在这里，你调用 Jungle_browse_statistics_Loader 类实例的 run 方法，该方法将会运行所有已添加到队列的 WordPress 钩子。
	 */
	public function run()
	{
		$this->loader->run();
	}
	/**
	 * get_plugin_name(), get_loader(), get_version() 这几个函数是获取器方法，
	 * 返回插件名称、加载器实例和插件版本。这些方法可以在插件的其他部分被调用，以获取这些属性。
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}
	public function get_loader()
	{
		return $this->loader;
	}
	public function get_version()
	{
		return $this->version;
	}
}
