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
			),
		'taxonomies' => array('post_tag') 
		);
	register_post_type( ONA_Session::$post_type, $args );

	// Below code allows Speakers to appear in admin. 
	// We'll want to remove it, if we don't want folks editing them there
	$args = array(
		'label'          => 'Speakers',
		'labels' => array(
				'name'               => 'Speakers',
				'singular_name'      => 'Speaker',
				'add_new'            => 'Add New Speaker',
				'all_items'          => 'All Speakers',
				'add_new_item'       => 'Add New Speaker',
				'edit_item'          => 'Edit Speaker',
				'new_item'           => 'New Speaker',
				'view_item'          => 'View Speaker',
				'search_items'       => 'Search Speakers',
				'not_found'          => 'Speaker Not Found',
			),
		'menu_position'  => 7,
		'public'       => true,
		'has_archive'    => 'speakers',
		'rewrite' => array(
				'slug'   => 'speaker',
				'feeds'  => false,
				'with_front' => true
			),
		'supports' => array(
				'title',
				'thumbnail',
				'zoninator_zones'
			),
		'taxonomies' => array() 
		);
	register_post_type( ONA_Speaker::$post_type, $args );

});