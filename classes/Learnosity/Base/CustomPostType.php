<?php

namespace Learnosity;

abstract class Base_Custom_Post_Type
{

	const POST_TYPE = 'base-custom-post';
	const POST_DESCRIPTION = 'This is a basic custom post';

	public function __construct()
	{
		add_action('init', array(&$this, 'init'));
		add_action('admin_init', array(&$this, 'admin_init'));
	}

	public function init()
	{
		$this->create_post_type();
		add_action('save_post', array(&$this, 'save_post'));
	}

	public function create_post_type()
	{
		$humanized_names = $this->get_humanized_names();
		register_post_type(static::POST_TYPE,
			array(
				'labels' => array(
					'name' => $humanized_names['pluralized'],
					'singular_name' => $humanized_names['singular']
				),
				'public' => true,
				'has_archive' => true,
				'description' => __(static::POST_DESCRIPTION),
				'supports' => array(
					'title', 'editor', 'excerpt'
				)
			)
		);
	}

	public function get_humanized_names() {
		$name = __(ucwords(str_replace('-', ' ', static::POST_TYPE)));
		return array(
			'pluralized' => $name . 's',
			'singular' => $name
		);
	}

}
