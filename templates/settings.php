<div class="wrap">
	<h2>Learnosity API settings</h2>
	<form method="post" action="options.php"> 
		<?php @settings_fields('lrn_api_group'); ?>
		<?php @do_settings_fields('lrn_api_group'); ?>

		<table class="form-table">  
			<tr valign="top">
				<th scope="row"><label for="lrn_consumer_key">Consumer Key (required)</label></th>
				<td><input type="text" name="lrn_consumer_key" id="lrn_consumer_key" value="<?php echo get_option('lrn_consumer_key'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="lrn_consumer_secret">Consumer Secret (required)</label></th>
				<td><input type="text" name="lrn_consumer_secret" id="lrn_consumer_secret" value="<?php echo get_option('lrn_consumer_secret'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="lrn_items_api_url">Items API URL</label></th>
				<td><input type="text" name="lrn_items_api_url" id="lrn_items_api_url" value="<?php echo get_option('lrn_items_api_url'); ?>" />Use this to select region and version to use.  Default: https://items.learnosity.com/?v1</td>
			</tr>
		</table>

		<?php @submit_button(); ?>
	</form>
</div>
