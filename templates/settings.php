<div class="wrap">
	<h2>Learnosity API settings</h2>
	<form method="post" action="options.php"> 
		<?php settings_fields('lrn_api_group'); ?>
		<?php do_settings_sections('lrn_api_group'); ?>

		<table class="form-table">  
			<tr valign="top">
				<th scope="row"><label for="lrn_consumer_key">Consumer Key (required)</label></th>
				<td><input type="text" name="lrn_consumer_key" id="lrn_consumer_key" value="<?php echo get_option('lrn_consumer_key'); ?>" class="regular-text ltr"/></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="lrn_consumer_secret">Consumer Secret (required)</label></th>
				<td><input type="text" name="lrn_consumer_secret" id="lrn_consumer_secret" value="<?php echo get_option('lrn_consumer_secret'); ?>" class="regular-text ltr"/></td>
			</tr>
            <tr valign="top">
                <th scope="row"><label for="lrn_author_api_url">Author API URL</label></th>
                <td><input type="text" name="lrn_author_api_url" id="lrn_author_api_url" value="<?php echo get_option('lrn_author_api_url'); ?>" class="regular-text ltr"/><p><i>Use this to select region and version to use.  Default: https://authorapi-or.learnosity.com/?v1</i></p></td>
            </tr>
			<tr valign="top">
				<th scope="row"><label for="lrn_items_api_url">Items API URL</label></th>
				<td><input type="text" name="lrn_items_api_url" id="lrn_items_api_url" value="<?php echo get_option('lrn_items_api_url'); ?>" class="regular-text ltr"/><p><i>Use this to select region and version to use.  Default: https://items-va.learnosity.com/?v1</i></p></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="lrn_reports_api_url">Reports API URL</label></th>
				<td><input type="text" name="lrn_reports_api_url" id="lrn_reports_api_url" value="<?php echo get_option('lrn_reports_api_url'); ?>" class="regular-text ltr"/><p><i>Use this to select region and version to use.  Default: https://reports-va.learnosity.com/?v1</i></p></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default Type:</th>
				<td>
				<input type="radio" name="lrn_default_type" id="submit_practice" value="submit_practice" <?php echo(get_option('lrn_default_type')=='submit_practice'?'checked="checked"':'') ?> >
				<label for="submit_practice">Submit</label>
				<br>
				<input type="radio" name="lrn_default_type" id="local_practice" value="local_practice"  <?php echo(get_option('lrn_default_type')=='local_practice'?'checked="checked"':'') ?> >
				<label for="local_practice">Local Practice</label>
				<p><i>Submit saves all results - great for real use, Local Practice doesn't save anything - great for demos.  This can be over-ridden at an activity level by specifying the type="local_practice|submit_practice"</i></p></td>
			</tr>
            <tr valign="top">
                <th scope="row"><label for="lrn_student_prefix">Student Prefix</label></th>
                <td><input type="text" name="lrn_student_prefix" id="lrn_student_prefix" value="<?php echo get_option('lrn_student_prefix','student_'); ?>" class="regular-text ltr"/><p><i>Prefix before the wordpress user id for submission to Learnosity.  Default is: "student_"</i></p></td>
            </tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>
