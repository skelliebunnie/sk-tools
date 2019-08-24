<?php
// create options page content
function skb_admin_settings() {
	// Init general ST options
	global $skb_options;

	wp_enqueue_style('skb-admin-styles');

	ob_start();
?>

	<div class="st-wrap">
		<h1><?php _e("SKB Tools Settings", "skb_domain"); ?></h1>
		<!-- <p><?php //_e("Settings for the SKB Tools Plugin", "skb_domain"); ?></p> -->
		<!-- form action MUST be options.php, so that WP-Options handles all this -->
		<form action="options.php" method="post">
			<!-- Define the settings group -->
			<?php settings_fields('skb_settings_group'); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro;'>
							<h4 class='no-pad no-margin'>Breadcrumbs Nav Options</h4>
						</th>
					</tr>
					<!-- include home link in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc_show_home]"><?php _e('Include Home Link', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc_show_home]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc_show_home]" value="true" <?php if ($skb_options['skb-bc_show_home'] == "true" ) { echo "checked"; } ?> >
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
							<label for="skb_settings[skb-bc_show_home_icon]"><?php _e('Show Home Icon', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc_show_home_icon]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc_show_home_icon]" value="true" <?php if ($skb_options['skb-bc_show_home_icon'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class="no-pad-top">
						<td colspan="2">
							<p class="description">
								<?php _e("Show the home icon in the breadcrumbs nav?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- show current page in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc_show_current]"><?php _e('Show Current', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc_show_current]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc_show_current]" value="true" <?php if ($skb_options['skb-bc_show_current'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class='no-pad-top'>
						<td colspan="2">
							<p class="description" style="padding: 0">
								<?php _e("Show the name of the current page in the breadcrumbs nav?", "skb_domain"); ?>
							</p>
						</td>
					</tr>
					<!-- include link to current page in breadcrumbs nav -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_settings[skb-bc_current_url]"><?php _e('Current As URL', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="hidden" name="skb_settings[skb-bc_current_url]" value="false">
							<input type="checkbox" name="skb_settings[skb-bc_current_url]" value="true" <?php if ($skb_options['skb-bc_current_url'] == "true" ) { echo "checked"; } ?> >
						</td>
					</tr>
					<tr class='no-pad-top'>
						<td colspan="2">
							<p class="description" style="padding: 0">
								<?php _e("Make the current page in the breadcrumbs nav a link?", "skb_domain"); ?>
							</p>
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