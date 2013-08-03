<?php
/**
 * Register the custom post types
 */
add_action( 'init', function() {

	$args = array(
		'public'       => true,
		'rewrite' => array(
				'slug'   => 'sessions',
				'feeds'  => false,
				'with_front' => true
			)
		);
		
	// Below code allows Sessions to appear in admin. 
	// We'll want to remove it, if we don't want folks editing them there
	$args = array(
		'label'          => 'Sessions',
		'labels' => array(
				'name'               => 'Sessions',
				'singular_name'      => 'Session',
				'add_new'            => 'Add New Session',
				'all_items'          => 'All Sessions',
				'add_new_item'       => 'Add New Session',
				'edit_item'          => 'Edit Session',
				'new_item'           => 'New Session',
				'view_item'          => 'View Session',
				'search_items'       => 'Search Sessions',
				'not_found'          => 'Session Not Found',
			),
		'menu_position'  => 6,
		'public'       => true,
		'has_archive'    => true,
		'rewrite' => array(
				'slug'   => 'sessions',
				'feeds'  => false,
				'with_front' => true
			),
		'supports' => array(
				'title',
				'thumbnail',
				'zoninator_zones'
			)
		);
	register_post_type( ONA_Session::$post_type, $args );

});