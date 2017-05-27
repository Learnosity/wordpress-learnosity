<?php

namespace Learnosity;

require_once 'Base/Plugin.php';
require_once 'Shortcodes/Generator.php';

use \Learnosity\Base\Plugin as BasePlugin;
use \Learnosity\Shortcodes\Generator as ShortcodesGenerator;

class Plugin extends BasePlugin
{

	public function __construct()
	{
		parent::__construct();
		new ShortcodesGenerator();
	}

	public function add_menu()
	{
		add_options_page(
			'Learnosity API Settings',
			'Learnosity API',
			'manage_options',
			'lrn_api',
			array(&$this, 'render_plugin_settings_page')
		);
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script(
			'learnosity',
			'//items.learnosity.com/',
			array(),
			null,
			false
		);
	}

	public function init_settings()
	{
		register_setting('lrn_api_group', 'lrn_consumer_key');
		register_setting('lrn_api_group', 'lrn_consumer_secret');
		register_setting('lrn_api_group', 'lrn_course_id');
	}

	/**
	 * Menu Callback
	 */
	public function render_plugin_settings_page()
	{
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		// Render the settings template
		include(__DIR__ . '../../../templates/settings.php');

	}

}
