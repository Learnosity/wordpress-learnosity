<?php
/**
 * Plugin Name: Learnosity API
 * Plugin URI: https://docs.learnosity.com/developers/developerguide/integration
 * Description: Simple Learnosity API integration in WordPress.
 * Version: 1.0.1
 * Author: Learnosity
 * Author URI: http://www.learnosity.com
 * License: Copyright 2014-2017, Learnosity
 */

require_once 'classes/Learnosity/Plugin.php';

use \Learnosity\Plugin as LrnPlugin;

// Installation and uninstallation hooks
register_activation_hook(__FILE__, array('LrnPlugin', 'activate'));
register_deactivation_hook(__FILE__, array('LrnPlugin', 'deactivate'));

// Instantiate the plugin class
new LrnPlugin();

// Add a link to the settings page onto the plugin page
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
function plugin_settings_link($links)
{ 
	$settings_link = '<a href="options-general.php?page=lrn_api">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}
