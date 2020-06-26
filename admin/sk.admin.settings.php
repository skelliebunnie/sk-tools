<?php
if( !defined( 'ABSPATH' ) ) { exit; }

// create options page content
function sk_admin_settings() {
	global $sk_options;

	// echo "<pre>";
	// var_dump($sk_options);
	// echo "</pre>";

	wp_enqueue_media();
	wp_enqueue_script('sk-misha-script');

	wp_enqueue_style('sk-admin-styles');

	ob_start();
?>
	<div class="sk-wrap">
		<h1><?php _e("SK Tools Settings", "sk_domain"); ?></h1>
		<!-- <p><?php //_e("Settings for the SKB Tools Plugin", "sk_domain"); ?></p> -->
		<!-- form action MUST be options.php, so that WP-Options handles all this -->
		<form action="options.php" method="post">
			<!-- Define the settings group -->
			<?php settings_fields('sk_settings_group'); ?>
			<table class="form-table">
				<tbody>
					<!-- ENABLE / DISABLE TOOLS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Enable / Disable Tools</h3>
						</th>
					</tr>
					<!-- enable sk-addressbook -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_addressbook]"><?php _e('Enable AddressBook', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_addressbook]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_addressbook]" value="true" <?php if ($sk_options['sk_enable_addressbook'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools AddressBook be available? If the AddressBook is enabled, please enter the information on the AddressBook page.", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable sk-breadcrumbs -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_breadcrumbs]"><?php _e('Enable Breadcrumbs', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_breadcrumbs]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_breadcrumbs]" value="true" <?php if ($sk_options['sk_enable_breadcrumbs'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools Breadcrumbs be available?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable sk-notices -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_notices]"><?php _e('Enable Notices', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_notices]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_notices]" value="true" <?php if ($sk_options['sk_enable_notices'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools Notices be available?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable sk-notices -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_datetime]"><?php _e('Enable Date/Time', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_datetime]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_datetime]" value="true" <?php if ($sk_options['sk_enable_datetime'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools Date/Time be available?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable sk-filter -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_filter]"><?php _e('Enable Filter', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_filter]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_filter]" value="true" <?php if ($sk_options['sk_enable_filter'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools Filter be available?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable sk-filter-advanced -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_filter_advanced]"><?php _e('Enable Advanced Filter', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_filter_advanced]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_filter_advanced]" value="true" <?php if ($sk_options['sk_enable_filter_advanced'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools Advanced Filter be available?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- enable sk-color-palettes -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk_enable_colorpalettes]"><?php _e('Enable Color Palettes', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk_enable_colorpalettes]" value="false">
							<input type="checkbox" name="sk_settings[sk_enable_colorpalettes]" value="true" <?php if ($sk_options['sk_enable_colorpalettes'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Should the SK-Tools Color Palettes be available?", "sk_domain"); ?>
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
							<label for="sk_settings[sk-bc-show_home]"><?php _e('Include Home Link', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk-bc-show_home]" value="false">
							<input type="checkbox" name="sk_settings[sk-bc-show_home]" value="true" <?php if ($sk_options['sk-bc-show_home'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Show the home icon in the breadcrumbs nav?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- show icon for home in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk-bc-show_home_icon]"><?php _e('Show Home Icon', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk-bc-show_home_icon]" value="false">
							<input type="checkbox" name="sk_settings[sk-bc-show_home_icon]" value="true" <?php if ($sk_options['sk-bc-show_home_icon'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description no-pad">
								<?php _e("Show the home icon in the breadcrumbs nav?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- show current page in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk-bc-show_current]"><?php _e('Show Current', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk-bc-show_current]" value="false">
							<input type="checkbox" name="sk_settings[sk-bc-show_current]" value="true" <?php if ($sk_options['sk-bc-show_current'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class='no-pad-top'>
						<td colspan="2">
							<p class="description no-pad">
								<?php _e("Show the name of the current page in the breadcrumbs nav?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- include link to current page in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_settings[sk-bc-current_url]"><?php _e('Current As URL', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="sk_settings[sk-bc-current_url]" value="false">
							<input type="checkbox" name="sk_settings[sk-bc-current_url]" value="true" <?php if ($sk_options['sk-bc-current_url'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class='no-pad-top'>
						<td colspan="2">
							<p class="description no-pad">
								<?php _e("Make the current page in the breadcrumbs nav a link?", "sk_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- author for Butterflies virtual pages -->
					<!-- <tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-btf-author]"><?php //_e('Butterflies Virtual Pages Author', 'sk_domain'); ?></label>
						</th>
						<td>
							<select name="sk_settings[sk-btf-author]" id="sk_settings[sk-btf-author]"> -->
							<?php
								// $users = get_users(); 
							 //  foreach ($users as $user) {
							 //  	$option = "<option value='{$user->ID}'>";
							 //  	if( $sk_options['sk-btf-author'] == $user->ID ) {
							 //  		$option = "<option value='{$user->ID}' selected>";
							 //  	}
							 //    $option .= $user->display_name;
							 //    $option .= "</option>";
							 //    echo $option;
							 //  }
							?>
							<!-- </select>
						</td>
					</tr> -->
					<!-- NOTICES OPTIONS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Notices Options</h3>
						</th>
					</tr>
					<!-- set default message for notices if none is provided in shortcode -->
					<tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-n-default_message]"><?php _e('Default Message', 'sk_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default message if none provided", "sk_domain"); ?>
							</p>
						</th>
						<td>
							<input type="text" name="sk_settings[sk-n-default_message]" value="<?php echo $sk_options['sk-n-default_message']; ?>">
						</td>
					</tr>
					<!-- notices default date format -->
					<tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-n-default_date_format]"><?php _e('Default Date Format (Notice)', 'sk_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default date format (for sk_notice) if none provided", "sk_domain"); ?>
							</p>
						</th>
						<td>
							<input type="text" name="sk_settings[sk-n-default_date_format]" value="<?php echo $sk_options['sk-n-default_date_format']; ?>">
						</td>
					</tr>
					<!-- notices default "type" -->
					<?php $msg_types = array('info', 'warn', 'alert', 'success', 'neutral', 'simple'); ?>
					<tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-n-default_message_type]"><?php _e('Default Message Type', 'sk_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default date format if none provided", "sk_domain"); ?>
							</p>
						</th>
						<td>
							<select name="sk_settings[sk-n-default_message_type]" id="sk_settings[sk-n-default_message_type]">
							<?php
								foreach($msg_types as $index=>$type) {
									$selected = "";
									if( (isset($sk_options['sk-n-default_message_type']) && $sk_options['sk-n-default_message_type'] === $type) || (!isset($sk_options['sk-n-default_message_type']) && $index=0) ) {
										$selected = "selected";
									}
									echo "<option value='{$type}' $selected>". ucfirst($type) ."</option>";
								}
							?>
							</select>
						</td>
					</tr>
					<!-- notices weekdays -->
					<tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-n-default_weekdays][]"><?php _e('Default Weekdays', 'sk_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Weekdays for scheduling Notices", "sk_domain"); ?>
							</p>
						</th>
						<td>
							<fieldset>
							<?php
								$days = array('mon' => 'Monday','tue' => 'Tuesday','wed' => 'Wednesday','thu' => 'Thursday','fri' => 'Friday','sat' => 'Saturday','sun' => 'Sunday');
								$weekdays = $sk_options['sk-n-default_weekdays'];
								foreach($days as $key=>$day) {
									$checked = "";
									if(in_array($key, $weekdays)) { $checked = "checked"; }
									echo "<label for='{$key}'><input type='checkbox' name='sk_settings[sk-n-default_weekdays][]' value='{$key}' $checked/>$key</label>";
								}
							?>
							</fieldset>
						</td>
					</tr>
					<!-- DATETIME OPTIONS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Date/Time Options</h3>
						</th>
					</tr>
					<!-- datetime default date format -->
					<tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-dt-default_date_format]"><?php _e('Default Date Format (Date/Time)', 'sk_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default date format (for sk_datetime) if none provided", "sk_domain"); ?>
							</p>
						</th>
						<td>
							<input type="text" name="sk_settings[sk-dt-default_date_format]" value="<?php echo $sk_options['sk-dt-default_date_format']; ?>">
						</td>
					</tr>
					<!-- datetime default time format -->
					<tr class="no-pad">
						<th scope="row">
							<label for="sk_settings[sk-dt-default_time_format]"><?php _e('Default Time Format (Date/Time)', 'sk_domain'); ?></label>
							<p class="description no-pad" style="font-weight: normal;">
								<?php _e("Default time format (for sk_datetime) if none provided", "sk_domain"); ?>
							</p>
						</th>
						<td>
							<input type="text" name="sk_settings[sk-dt-default_time_format]" value="<?php echo $sk_options['sk-dt-default_time_format']; ?>">
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'sk_domain'); ?>">
			</p>
		</form>
	</div>

<?php
	echo ob_get_clean();
}

// Register Settings
function sk_register_settings() {
	register_setting('sk_settings_group', 'sk_settings');
}
add_action('admin_init', 'sk_register_settings');