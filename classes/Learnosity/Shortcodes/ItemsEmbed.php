<?php

namespace Learnosity\Shortcodes;

require_once __DIR__ . '/../../../vendor/learnosity-utils/uuid.php';
require_once __DIR__ . '/../../../vendor/learnosity-utils/RequestHelper.php';

class ItemsEmbed
{

	public static $script_has_been_added = false;

	private $config;
	private $security;

	public function __construct($options)
	{
		$this->security = array(
			'consumer_key' => get_option('lrn_consumer_key'),
			'domain' => $_SERVER['SERVER_NAME'],
			'timestamp' => gmdate('Ymd-Hi')
		);
		$defaults = array(
			'activityid' => 'activity_' . \UUID::generateUuid(),
			'autorender' => true,
			'name' => 'My Activity',
			'rendersubmit' => false,
			'renderingtype' => 'inline',
			'sessionid' => \UUID::generateUuid(),
			'state' => 'initial',
			'studentid' => 'student_' . \UUID::generateUuid(),
			'type' => 'local_practice'
		);
		$options = $this->parse_options($options);
		$this->config = array_merge($defaults, $options);
	}

	public function render()
	{
		ob_start();
		$this->render_init_js($this->config);
		if ($this->config['autorender']) {
			$this->render_items(
				$this->items_attr_to_array($this->config['items']),
				$this->config['rendersubmit']
			);
		}
		return ob_get_clean();
	}

	private function generate_signed_request()
	{
		$version = get_option('lrn_api_version');
		$request = array(
			'user_id' => $this->config['studentid'],
			'rendering_type' => $this->config['renderingtype'],
			'name' => $this->config['name'],
			'state' => $this->config['state'],
			'activity_id' => $this->config['activityid'],
			'session_id' => $this->config['sessionid'],
			'items' => $this->items_attr_to_array($this->config['items']),
			'type' => $this->config['type'],
			'config' => array(
				'renderSubmitButton' => $this->config['rendersubmit'],
				'questionsApiVersion' => $this->config['apiversion']
			)
		);
		$request_helper = new \RequestHelper(
			'items',
			$this->security,
			get_option('lrn_consumer_secret'),
			$request
		);
		$signed_request = $request_helper->generateRequest();
		if (isset($this->config['activitytemplateid'])) {
			$signed_request = json_decode($signed_request, true);
			$ati = $this->config['activitytemplateid'];
			$signed_request['request']['activity_template_id'] = $ati;
			$signed_request = json_encode($signed_request);
		}
		return $signed_request;
	}

	private function get_items_api_version()
	{
		if (get_option('lrn_items_api_version')) {
			return get_option('lrn_items_api_version');
		} else {
			return '';
		}
	}

	private function items_attr_to_array($items_string)
	{
		$items_string = preg_replace('/\s+/', '', $items_string);
		return explode(',', $items_string);
	}

	private function parse_boolean($str_val)
	{
		return $str_val === 'true' ||
		       ($str_val !== 'false' && intval($str_val) > 0);
	}

	private function parse_options($options)
	{
		$booleanOptions = ['autorender', 'rendersubmit'];
		foreach ($booleanOptions as $i => $option) {
			if (isset($options[$option])) {
				$str_val = $options[$option];
				$options[$option] = $this->parse_boolean($str_val);
			}
		}
		return $options;
	}

	private function render_init_js()
	{
		$signed_request = $this->generate_signed_request($this->config);
		include(__DIR__ . '/../../../templates/init-js.php');
	}

	private function render_items($references, $should_render_submit)
	{
		include(__DIR__ . '/../../../templates/items.php');
	}

}
