<?php

namespace Learnosity\Shortcodes;

require_once 'ItemEmbed.php';
require_once 'ItemsEmbed.php';
require_once 'SubmitEmbed.php';
require_once 'ReportEmbed.php';
require_once 'AuthorEmbed.php';

use Learnosity\Shortcodes\ItemEmbed as ItemEmbed;
use Learnosity\Shortcodes\ItemsEmbed as ItemsEmbed;
use Learnosity\Shortcodes\SubmitEmbed as SubmitEmbed;
use Learnosity\Shortcodes\ReportEmbed as ReportEmbed;
use Learnosity\Shortcodes\AuthorEmbed as AuthorEmbed;

class Generator
{
    /**
     * @var callable callback to set is_rtl in Learnosity/Plugin
     */
    private $set_rtl_callback;

    public function __construct(callable $set_rtl_callback)
    {
        add_shortcode('lrn-items', array(&$this, 'render_items'));
        add_shortcode('lrn-item', array(&$this, 'render_item'));
        add_shortcode('lrn-submit', array(&$this, 'render_submit'));
        add_shortcode('lrn-assess', array(&$this, 'render_assess'));
        add_shortcode('lrn-report', array(&$this, 'render_report'));
        add_shortcode('lrn-author', array(&$this, 'render_author'));
        $this->set_rtl_callback = $set_rtl_callback;
    }

    public function render_item($attrs)
    {
        $item_embed = new ItemEmbed($attrs);
        return $item_embed->render();
    }

    public function render_items($attrs, $content)
    {
        wp_enqueue_script('learnosity-items');
        $items_embed = new ItemsEmbed($attrs, 'inline', $content);
        $this->set_rtl_if_required($attrs);
        return $items_embed->render();
    }

    public function render_submit($attrs)
    {
        $submit_embed = new SubmitEmbed($attrs);
        return $submit_embed->render();
    }

    public function render_assess($attrs, $content)
    {
        wp_enqueue_script('learnosity-items');
        $assess_embed = new ItemsEmbed($attrs, 'assess', $content);
        $this->set_rtl_if_required($attrs);
        return $assess_embed->render();
    }

    public function render_report($attrs, $content)
    {
        wp_enqueue_script('learnosity-reports');
        $report_embed = new ReportEmbed($attrs, $content);
        return $report_embed->render();
    }

    public function render_author($attrs, $content)
    {
        wp_enqueue_script('learnosity-author');
        $author_embed = new AuthorEmbed($attrs, $content);
        $this->set_rtl_if_required($attrs);
        return $author_embed->render();
    }

    private function set_rtl_if_required($attrs)
    {
        if (isset($attrs['rtl']) && $attrs['rtl'] === 'true') {
            call_user_func($this->set_rtl_callback, true);
        }
    }
}
