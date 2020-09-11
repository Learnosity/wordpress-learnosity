<?php

namespace Learnosity;

require_once 'Base/Plugin.php';
require_once 'Shortcodes/Generator.php';

use \Learnosity\Base\Plugin as BasePlugin;
use \Learnosity\Shortcodes\Generator as ShortcodesGenerator;

class Plugin extends BasePlugin
{
    /**
     * @var bool determines if rtl should be enabled or not
     */
    private $is_rtl;

    public function __construct()
    {
        parent::__construct();
        $this->is_rtl = false;
        new ShortcodesGenerator(array($this, 'set_rtl_callback'));
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

        /*
         * For items and author, in_footer is set to true. This is on purpose as the information, whether
         * the read direction is right to left is set in the plugin short-code. If we loaded the scripts
         * in the header, they'd be loaded before the short-code scripts are encountered, thus we
         * wouldn't know whether to add the rtl flag in the src tag.
         */
        wp_enqueue_script(
            'learnosity-items',
            $lrn_items_api_url,
            array(),
            null,
            true
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
            true
        );

        // Before the script tags are added to page, add the rtl data attribute
        add_filter(
            'script_loader_tag',
            array($this, 'add_rtl_data'),
            10,
            3
        );
    }

    /**
     * Adds data-lrn-dir="rtl" to the source tag if $this->is_rtl is true
     *
     * @param $tag
     * @param $handle
     * @param $src
     * @return string
     */
    public function add_rtl_data($tag, $handle, $src)
    {
        if ($this->is_rtl) {
            $tag = '<script type="text/javascript" src="' . esc_url($src) . '" data-lrn-dir="rtl"></script>';
        }
        return $tag;
    }

    /**
     * Sets $this->is_rtl to $value. Used as a callback in Generator
     *
     * @param $value
     */
    public function set_rtl_callback($value)
    {
        $this->is_rtl = $value;
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
