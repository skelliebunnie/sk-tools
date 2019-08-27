<?php

function skb_directory_shortcode($atts) {
	wp_enqueue_style('skb-directory-styles');

	global $skb_options;

	$a = shortcode_atts( array(
		'role'					=> 'all',
		'photo_shape'		=> 'circle',
		'default_photo'	=> $skb_options['skb-d_default_photo'],
		'photo_size'		=> $skb_options['skb-d_photo_size']
	), $atts );

	$role = $a['role'];
	if( strpos($role, ",") >= 0 ) {
		$role = explode(",", $role);
	}
	
	$users = get_users();
	if( $a['role'] !== "all" ) {
		$users = get_users( array( 'role' => $a['role'] ) );
	}

	ob_start();
?>
	<div id="skb-directory">
		<?php
			foreach($users as $user) :
				$name = $user->display_name;
				$email = $user->user_email;
				$avatar = get_avatar( $user->ID, 200 );
		?>
		<figure class="entry">
			<?php echo $avatar; ?>
			<figcaption>
				<p class='name'><a href="mailto:<?php echo $email; ?>"><?php echo $name; ?></a></p>
			</figcaption>
		</figure>
		<?php
			endforeach;
		?>
	</div>
<?php
	 return ob_get_clean();
}
add_shortcode('skb_directory', 'skb_directory_shortcode');