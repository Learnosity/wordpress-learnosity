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
		$lrn_items_api_url = get_option('lrn_items_api_url','https://items-va.learnosity.com/?v1');
		$lrn_reports_api_url = get_option('lrn_reports_api_url','https://reports-va.learnosity.com/?v1');
		$lrn_author_api_url = get_option('lrn_author_api_url','https://authorapi-or.learnosity.com/?v1');
		wp_enqueue_script(
			'learnosity-items',
			$lrn_items_api_url,
			array(),
			null,
			false
		);
		wp_enqueue_script(
			'learnosity-reports',
			$lrn_reports_api_url,
			array(),
			null,
			false
		);
        wp_enqueue_script(
            'learnosity-author',
            $lrn_author_api_url,
            array(),
            null,
            false
        );
	}

	public function init_settings()
	{
		register_setting('lrn_api_group', 'lrn_consumer_key');
		register_setting('lrn_api_group', 'lrn_consumer_secret');
		register_setting('lrn_api_group', 'lrn_author_api_url');
		register_setting('lrn_api_group', 'lrn_items_api_url');
		register_setting('lrn_api_group', 'lrn_reports_api_url');
        register_setting('lrn_api_group', 'lrn_default_type');
        register_setting('lrn_api_group', 'lrn_student_prefix');
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
