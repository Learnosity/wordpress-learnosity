<?php

namespace Learnosity\Shortcodes;

require_once 'ItemEmbed.php';
require_once 'ItemsEmbed.php';
require_once 'SubmitEmbed.php';
require_once 'ReportEmbed.php';

use Learnosity\Shortcodes\ItemEmbed as ItemEmbed;
use Learnosity\Shortcodes\ItemsEmbed as ItemsEmbed;
use Learnosity\Shortcodes\SubmitEmbed as SubmitEmbed;
use Learnosity\Shortcodes\ReportEmbed as ReportEmbed;

class Generator
{

	public function __construct()
	{
		add_shortcode('lrn-items', array(&$this, 'render_items'));
		add_shortcode('lrn-item', array(&$this, 'render_item'));
		add_shortcode('lrn-submit', array(&$this, 'render_submit'));
		add_shortcode('lrn-assess', array(&$this, 'render_assess'));
		add_shortcode('lrn-report', array(&$this, 'render_report'));
	}

	public function render_item($attrs)
	{
		$item_embed = new ItemEmbed($attrs);
		return $item_embed->render();
	}

	public function render_items($attrs)
	{
		$items_embed = new ItemsEmbed($attrs,'inline');
		return $items_embed->render();
	}

	public function render_submit($attrs)
	{
		$submit_embed = new SubmitEmbed($attrs);
		return $submit_embed->render();
	}

	public function render_assess($attrs)
	{
		$assess_embed = new ItemsEmbed($attrs,'assess');
		return $assess_embed->render();
	}

	public function render_report($attrs)
	{
		$report_embed = new ReportEmbed($attrs);
		return $report_embed->render();
	}

}
