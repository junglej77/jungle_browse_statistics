<?php
class Jungle_browse_statistics_i18n
{
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'jungle_browse_statistics',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
