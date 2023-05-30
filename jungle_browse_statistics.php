<?php

/**
 * 插件的引导文件
 *
 * 这个文件被WordPress读取，以便在插件管理区生成插件信息。
 * 管理区。这个文件还包括插件使用的所有依赖性、
 * 注册了激活和停用功能，并定义了一个函数
 * 启动该插件的函数。
 *
 * @link              https://www.grdtest.com:81/
 * @since             1.0.0
 * @package           Jungle_browse_statistics
 *
 * @wordpress-plugin
 * Plugin Name:       jungle browse statistics
 * Plugin URI:        https://www.grdtest.com:81/
 * Description:       记录独立IP什么时候（ip会解析到对应国家，城市）对网站每个页面的访问时长，方便分析，每天的流量和每个页面是否吸引用户，方便对页面做出及时调整
 * Version:           1.0.0
 * Author:            jungle
 * Author URI:        https://www.grdtest.com:81/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jungle_browse_statistics
 * Domain Path:       /languages
 */

// 如果这个文件被直接调用，则中止。
if (!defined('WPINC')) {
	die;
}

/**
 * 定义常量：JUNGLE_BROWSE_STATISTICS_VERSION 这个常量用于表示插件的版本。
 * 这在后续的版本更新中可能会用到，例如在执行数据库更新或是加载不同版本的脚本和样式表时。
 */
define('JUNGLE_BROWSE_STATISTICS_VERSION', '1.0.0');

/**
 * 在插件激活时运行的代码。
 * 这个动作在includes/class-jungle_browse_statistics-activator.php中有记录。
 */
function activate_jungle_browse_statistics()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-jungle_browse_statistics-activator.php';
	Jungle_browse_statistics_Activator::activate();
}
/**
 * 在插件停用时运行的代码。
 * 这个动作在includes/class-jungle_browse_statistics-deactivator.php中有记录。
 */
function deactivate_jungle_browse_statistics()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-jungle_browse_statistics-deactivator.php';
	Jungle_browse_statistics_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_jungle_browse_statistics');
register_deactivation_hook(__FILE__, 'deactivate_jungle_browse_statistics');

/**
 * 这行代码加载了插件的主类。这个类通常会负责设置插件的主要功能，例如注册脚本、样式表，添加短代码，处理 AJAX 请求等。
 */
require plugin_dir_path(__FILE__) . 'includes/class-jungle_browse_statistics.php';

//引入类
require plugin_dir_path(__FILE__) . 'includes/service/class-user_service.php';

/**
 * 开始执行该插件。
 *
 * 由于该插件中的所有内容都是通过钩子注册的、
 * 那么从文件中的这一点开始启动该插件就不会
 * 不影响页面的生命周期。
 *
 *自1.0.0以来
 */
function run_jungle_browse_statistics()
{

	$plugin = new Jungle_browse_statistics();
	$plugin->run();
}
run_jungle_browse_statistics();
