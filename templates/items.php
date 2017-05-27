<?php

foreach ($references as $reference) {
	include('item.php');
}
if ($should_render_submit) {
	include('submit.php');
}
