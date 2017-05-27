<?php

namespace Learnosity\Shortcodes;

class ItemEmbed
{

	public function __construct($attrs)
	{
		$this->reference = $attrs['ref'];
	}

	public function render()
	{
		$reference = $this->reference;
		ob_start();
		include(__DIR__ . '/../../../templates/item.php');
		return ob_get_clean();
	}

}
