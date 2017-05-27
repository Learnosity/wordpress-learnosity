<?php

namespace Learnosity\Shortcodes;

class SubmitEmbed
{

	public function render()
	{
		ob_start();
		include(__DIR__ . '/../../../templates/submit.php');
		return ob_get_clean();
	}

}
