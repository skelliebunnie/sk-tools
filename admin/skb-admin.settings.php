<?php
// create options page content
function skb_admin_settings() {
	global $skb_options;

	// echo "<pre>";
	// var_dump($skb_options);
	// echo "</pre>";

	wp_enqueue_media();
	wp_enqueue_script('skb-misha-script');

	wp_enqueue_style('skb-admin-styles');

	ob_start();
?>
	<div class="skb-wrap">
		<h1><?php _e("SKB Tools Settings", "skb_domain"); ?></h1>
		<!-- <p><?php //_e("Settings for the SKB Tools Plugin", "skb_domain"); ?></p> -->
		<!-- form action MUST be options.php, so that WP-Options handles all this -->
		<form action="options.php" method="post">
			<!-- Define the settings group -->
			<?php settings_fields('skb_settings_group'); ?>
			<table class="form-table">
				<tbody>
					<!-- ENABLE / DISABLE TOOLS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Enable / Disable Tools</h3>
						</th>
					</tr>
					<!-- enable skb-butterflies -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb_enable_butterflies]"><?php _e('Enable Butterflies AirPress', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb_enable_butterflies]" value="false">
							<input type="checkbox" name="skb_settings[skb_enable_butterflies]" value="true" <?php if ($skb_options['skb_enable_butterflies'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SKB-Tools Butterflies (data from AirPress) shortcode be available?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable skb-breadcrumbs -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb_enable_breadcrumbs]"><?php _e('Enable Breadcrumbs', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb_enable_breadcrumbs]" value="false">
							<input type="checkbox" name="skb_settings[skb_enable_breadcrumbs]" value="true" <?php if ($skb_options['skb_enable_breadcrumbs'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SKB-Tools Breadcrumbs be available?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable skb-notices -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb_enable_notices]"><?php _e('Enable Notices', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb_enable_notices]" value="false">
							<input type="checkbox" name="skb_settings[skb_enable_notices]" value="true" <?php if ($skb_options['skb_enable_notices'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SKB-Tools Notices be available?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable skb-filter -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb_enable_filter]"><?php _e('Enable Filter', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb_enable_filter]" value="false">
							<input type="checkbox" name="skb_settings[skb_enable_filter]" value="true" <?php if ($skb_options['skb_enable_filter'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SKB-Tools Filter be available?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- SETTINGS PER TOOL -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Breadcrumbs Nav Options</h3>
						</th>
					</tr>
					<!-- include home link in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc-show_home]"><?php _e('Include Home Link', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc-show_home]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc-show_home]" value="true" <?php if ($skb_options['skb-bc-show_home'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Show the home icon in the breadcrumbs nav?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- show icon for home in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc-show_home_icon]"><?php _e('Show Home Icon', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc-show_home_icon]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc-show_home_icon]" value="true" <?php if ($skb_options['skb-bc-show_home_icon'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description no-pad">
								<?php _e("Show the home icon in the breadcrumbs nav?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- show current page in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc-show_current]"><?php _e('Show Current', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc-show_current]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc-show_current]" value="true" <?php if ($skb_options['skb-bc-show_current'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class='no-pad-top'>
						<td colspan="2">
							<p class="description no-pad">
								<?php _e("Show the name of the current page in the breadcrumbs nav?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- include link to current page in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc-current_url]"><?php _e('Current As URL', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc-current_url]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc-current_url]" value="true" <?php if ($skb_options['skb-bc-current_url'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class='no-pad-top'>
						<td colspan="2">
							<p class="description no-pad">
								<?php _e("Make the current page in the breadcrumbs nav a link?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- author for Butterflies virtual pages -->
					<tr class="no-pad">
						<th scope="row">
							<label for="skb_settings[skb-btf-author]"><?php _e('Butterflies Virtual Pages Author', 'skb_domain'); ?></label>
						</th>
						<td>
							<select name="skb_settings[skb-btf-author]" id="skb_settings[skb-btf-author]">
							<?php
								$users = get_users(); 
							  foreach ($users as $user) {
							  	$option = "<option value='{$user->ID}'>";
							  	if( $skb_options['skb-btf-author'] == $user->ID ) {
							  		$option = "<option value='{$user->ID}' selected>";
							  	}
							    $option .= $user->display_name;
							    $option .= "</option>";
							    echo $option;
							  }
							?>
							</select>
						</td>
					</tr>
					<!-- NOTICES OPTIONS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Notices Options</h3>
						</th>
					</tr>
					<!-- set default message for notices if none is provided in shortcode -->
					<tr class="no-pad">
						<th scope="row">
							<label for="skb_settings[skb-n-default_message]"><?php _e('Default Message', 'skb_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default message if none provided", "skb_domain"); ?>
							</p>
						</th>
						<td>
							<input type="text" name="skb_settings[skb-n-default_message]" value="<?php echo $skb_options['skb-n-default_message']; ?>">
						</td>
					</tr>
					<!-- notices default date format -->
					<tr class="no-pad">
						<th scope="row">
							<label for="skb_settings[skb-n-default_date_format]"><?php _e('Default Date Format', 'skb_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default date format if none provided", "skb_domain"); ?>
							</p>
						</th>
						<td>
							<input type="text" name="skb_settings[skb-n-default_date_format]" value="<?php echo $skb_options['skb-n-default_date_format']; ?>">
						</td>
					</tr>
					<!-- notices default "type" -->
					<?php $msg_types = array('info', 'warn', 'alert', 'success'); ?>
					<tr class="no-pad">
						<th scope="row">
							<label for="skb_settings[skb-n-default_message_type]"><?php _e('Default Message Type', 'skb_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default date format if none provided", "skb_domain"); ?>
							</p>
						</th>
						<td>
							<select name="skb_settings[skb-n-default_message_type]" id="skb_settings[skb-n-default_message_type]">
							<?php
								foreach($msg_types as $index=>$type) {
									$selected = "";
									if( (isset($skb_options['skb-n-default_message_type']) && $skb_options['skb-n-default_message_type'] === $type) || (!isset($skb_options['skb-n-default_message_type']) && $index=0) ) {
										$selected = "selected";
									}
									echo "<option value='{$type}' $selected>". ucfirst($type) ."</option>";
								}
							?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'skb_domain'); ?>">
			</p>
		</form>
	</div>

<?php
	echo ob_get_clean();
}

// Register Settings
function skb_register_settings() {
	register_setting('skb_settings_group', 'skb_settings');
}
add_action('admin_init', 'skb_register_settings');