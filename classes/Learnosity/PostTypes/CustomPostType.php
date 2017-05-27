<?php

namespace Learnosity\PostTypes;

require_once __DIR__ . '/../Base/CustomPostType.php';

use \Learnosity\Base\CustomPostType as BaseCustomPostType;

abstract class CustomPostType extends BaseCustomPostType
{

	public function get_humanized_names() {
		$verbose_name = str_replace('lrn', 'learnosity', static::POST_TYPE);
		$name = __(ucwords(str_replace('-', ' ', $verbose_name)));
		return array(
			'pluralized' => $name . 's',
			'singular' => $name
		);
	}

}
