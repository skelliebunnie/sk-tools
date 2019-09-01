<?php

function skb_admin_virtualposts() {
	global $skb_virtualpost_options; // skb_virtualpost_settings[]

	$skb_url_slug = $skb_virtualpost_options['skb-vp-url_slug'];
	$skb_url_slug = str_replace(" ", "-", $skb_url_slug);
	$skb_url_slug = preg_replace("/[\!\@\#]/", "", $skb_url_slug);

	$skb_virtualpost_options['skb-vp-url_slug'] = $skb_url_slug;

	update_option( 'skb_virtualpost_settings', $skb_virtualpost_options );

	echo "<pre>";
	var_dump(get_option('skb_virtualpost_settings'));
	echo "</pre>";

	//echo get_option('skb-vp-url_slug');

	wp_enqueue_style('skb-admin-styles');

	ob_start();
?>
	<div class="skb-wrap">
		<h1><?php _e("SKB Tools Settings", "skb_domain"); ?></h1>
		<!-- <p><?php //_e("Settings for the SKB Tools Plugin", "skb_domain"); ?></p> -->
		<!-- form action MUST be options.php, so that WP-Options handles all this -->
		<form action="options.php" method="post">
			<!-- Define the settings group -->
			<?php settings_fields('skb_virtualpost_settings_group'); ?>
			<table class="form-table">
				<tbody>
					<!-- ENABLE / DISABLE TOOLS -->
					<tr>
						<th scope="row" colspan="2" style='border-top: 1px solid gainsboro; border-bottom: 1px solid gainsboro; padding: 0'>
							<h3 class='no-pad no-margin'>Virtual Post Options</h3>
						</th>
					</tr>
					<!-- skb-vp-url_slug -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_virtualpost_settings[skb-vp-url_slug]"><?php _e('URL Slug', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="text" name="skb_virtualpost_settings[skb-vp-url_slug]" value="<?php echo $skb_virtualpost_options['skb-vp-url_slug']; ?>">
						</td>
					</tr>
					<!-- skb-vp-post_title -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_virtualpost_settings[skb-vp-post_title]"><?php _e('Post Title', 'skb_domain'); ?></label>
						</th>
						<td>
							<input type="text" name="skb_virtualpost_settings[skb-vp-post_title]" value="<?php echo $skb_virtualpost_options['skb-vp-post_title']; ?>">
						</td>
					</tr>
					<!-- post_content -->
					<tr class='no-pad'>
						<th scope="row">
							<label for="skb_virtualpost_settings[skb-vp-post_content]"><?php _e('Post Content', 'skb_domain'); ?></label>
						</th>
						<td>
<textarea name="skb_virtualpost_settings[skb-vp-post_content]"><?php echo $skb_virtualpost_options['skb-vp-post_content']; ?></textarea>
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
function skb_register_vp_settings() {
	register_setting('skb_virtualpost_settings_group', 'skb_virtualpost_settings');
}
add_action('admin_init', 'skb_register_vp_settings');