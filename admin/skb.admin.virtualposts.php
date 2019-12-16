<?php

function sk_admin_virtualposts() {
	global $sk_virtualpost_options; // sk_virtualpost_settings[]

	$sk_url_slug = $sk_virtualpost_options['sk-vp-url_slug'];
	$sk_url_slug = str_replace(" ", "-", $sk_url_slug);
	$sk_url_slug = preg_replace("/[\!\@\#]/", "", $sk_url_slug);

	$sk_virtualpost_options['sk-vp-url_slug'] = $sk_url_slug;

	update_option( 'sk_virtualpost_settings', $sk_virtualpost_options );

	// echo "<pre>";
	// var_dump(get_option('sk_virtualpost_settings'));
	// echo "</pre>";

	//echo get_option('sk-vp-url_slug');

	wp_enqueue_style('sk-admin-styles');

	ob_start();
?>
	<div class="sk-wrap">
		<h1><?php _e("SKB Tools Settings", "sk_domain"); ?></h1>
		<!-- <p><?php //_e("Settings for the SKB Tools Plugin", "sk_domain"); ?></p> -->
		<!-- form action MUST be options.php, so that WP-Options handles all this -->
		<form action="options.php" method="post">
			<!-- Define the settings group -->
			<?php settings_fields('sk_virtualpost_settings_group'); ?>
			<table class="form-table">
				<tbody>
					<!-- ENABLE / DISABLE TOOLS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Virtual Post Options</h3>
						</th>
					</tr>
					<!-- sk-vp-url_slug -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_virtualpost_settings[sk-vp-url_slug]"><?php _e('URL Slug', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="text" name="sk_virtualpost_settings[sk-vp-url_slug]" value="<?php echo $sk_virtualpost_options['sk-vp-url_slug']; ?>">
						</td>
					</tr>
					<!-- sk-vp-post_title -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_virtualpost_settings[sk-vp-post_title]"><?php _e('Post Title', 'sk_domain'); ?></label>
						</th>
						<td>
							<input type="text" name="sk_virtualpost_settings[sk-vp-post_title]" value="<?php echo $sk_virtualpost_options['sk-vp-post_title']; ?>">
						</td>
					</tr>
					<!-- post_content -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="sk_virtualpost_settings[sk-vp-post_content]"><?php _e('Post Content', 'sk_domain'); ?></label>
						</th>
						<td>
<textarea name="sk_virtualpost_settings[sk-vp-post_content]"><?php echo $sk_virtualpost_options['sk-vp-post_content']; ?></textarea>
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
function sk_register_vp_settings() {
	register_setting('sk_virtualpost_settings_group', 'sk_virtualpost_settings');
}
add_action('admin_init', 'sk_register_vp_settings');