<?php

namespace Learnosity\Base;

abstract class Plugin
{

	public function __construct()
	{
		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'add_menu'));
		add_action('after_setup_theme', array(&$this, 'after_setup_theme'));
		add_action('widgets_init', array(&$this, 'register_widgets'));
		add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
	}

	/**
	 * Activate the plugin
	 */
	public static function activate()
	{

		// For custom post types
		flush_rewrite_rules();

	}

	/**
	 * Deactivate the plugin
	 */
	public static function deactivate()
	{
		// No-op
	}

	public function add_menu()
	{
		// No-op
	}

	public function admin_init()
	{
		// Set up the settings for this plugin
		$this->init_settings();
		// Possibly do additional admin_init tasks
	}

	// Can be useful for initialising theme dependent plugins
	// e.g. new Single_Post_Template_Plugin();
	public function after_setup_theme()
	{
		// No-op
	}

	/**
	 * Initialize some custom settings
	 */
	public function init_settings()
	{
		// No-op
	}

	public function register_widgets()
	{
		// No-op
	}

}
