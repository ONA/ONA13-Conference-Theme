<?php
/**
 * Register the custom post types
 */
add_action( 'init', function() {

	$args = array(
		'public'       => true,
		);
	register_post_type( ONA_Session::$post_type, $args );

});