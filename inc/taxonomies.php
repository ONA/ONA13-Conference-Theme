<?php
/**
 * Register the taxonomies
 */
add_action( 'init', function() {

	register_taxonomy( 'session-type', array( 'ona_session' ), array(
		'hierarchical'            => false,
		'public'                  => true,
		'show_in_nav_menus'       => true,
		'show_ui'                 => true,
		'query_var'               => true,
		'rewrite'                 => true,
		'capabilities'            => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'                  => array(
			'name'                       =>  __( 'Types', 'ona13' ),
			'singular_name'              =>  _x( 'Type', 'taxonomy general name', 'ona13' ),
			'search_items'               =>  __( 'Search Types', 'ona13' ),
			'popular_items'              =>  __( 'Popular Types', 'ona13' ),
			'all_items'                  =>  __( 'All Types', 'ona13' ),
			'parent_item'                =>  __( 'Parent Type', 'ona13' ),
			'parent_item_colon'          =>  __( 'Parent Type:', 'ona13' ),
			'edit_item'                  =>  __( 'Edit Type', 'ona13' ),
			'update_item'                =>  __( 'Update Type', 'ona13' ),
			'add_new_item'               =>  __( 'New Type', 'ona13' ),
			'new_item_name'              =>  __( 'New Type', 'ona13' ),
			'separate_items_with_commas' =>  __( 'Types separated by comma', 'ona13' ),
			'add_or_remove_items'        =>  __( 'Add or remove Types', 'ona13' ),
			'choose_from_most_used'      =>  __( 'Choose from the most used Types', 'ona13' ),
			'menu_name'                  =>  __( 'Types', 'ona13' ),
		),
	) );

	register_taxonomy( 'session-room', array( 'ona_session' ), array(
		'hierarchical'            => false,
		'public'                  => true,
		'show_in_nav_menus'       => true,
		'show_ui'                 => true,
		'query_var'               => true,
		'rewrite'                 => true,
		'capabilities'            => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'                  => array(
			'name'                       =>  __( 'Rooms', 'ona13' ),
			'singular_name'              =>  _x( 'Room', 'taxonomy general name', 'ona13' ),
			'search_items'               =>  __( 'Search Rooms', 'ona13' ),
			'popular_items'              =>  __( 'Popular Rooms', 'ona13' ),
			'all_items'                  =>  __( 'All Rooms', 'ona13' ),
			'parent_item'                =>  __( 'Parent Room', 'ona13' ),
			'parent_item_colon'          =>  __( 'Parent Room:', 'ona13' ),
			'edit_item'                  =>  __( 'Edit Room', 'ona13' ),
			'update_item'                =>  __( 'Update Room', 'ona13' ),
			'add_new_item'               =>  __( 'New Room', 'ona13' ),
			'new_item_name'              =>  __( 'New Room', 'ona13' ),
			'separate_items_with_commas' =>  __( 'Rooms separated by comma', 'ona13' ),
			'add_or_remove_items'        =>  __( 'Add or remove Rooms', 'ona13' ),
			'choose_from_most_used'      =>  __( 'Choose from the most used Rooms', 'ona13' ),
			'menu_name'                  =>  __( 'Rooms', 'ona13' ),
		),
	) );

	register_taxonomy( 'session-format', array( 'ona_session' ), array(
		'hierarchical'            => false,
		'public'                  => true,
		'show_in_nav_menus'       => true,
		'show_ui'                 => true,
		'query_var'               => true,
		'rewrite'                 => true,
		'capabilities'            => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'                  => array(
			'name'                       =>  __( 'Formats', 'ona13' ),
			'singular_name'              =>  _x( 'Format', 'taxonomy general name', 'ona13' ),
			'search_items'               =>  __( 'Search Formats', 'ona13' ),
			'popular_items'              =>  __( 'Popular Formats', 'ona13' ),
			'all_items'                  =>  __( 'All Formats', 'ona13' ),
			'parent_item'                =>  __( 'Parent Format', 'ona13' ),
			'parent_item_colon'          =>  __( 'Parent Format:', 'ona13' ),
			'edit_item'                  =>  __( 'Edit Format', 'ona13' ),
			'update_item'                =>  __( 'Update Format', 'ona13' ),
			'add_new_item'               =>  __( 'New Format', 'ona13' ),
			'new_item_name'              =>  __( 'New Format', 'ona13' ),
			'separate_items_with_commas' =>  __( 'Formats separated by comma', 'ona13' ),
			'add_or_remove_items'        =>  __( 'Add or remove Formats', 'ona13' ),
			'choose_from_most_used'      =>  __( 'Choose from the most used Formats', 'ona13' ),
			'menu_name'                  =>  __( 'Formats', 'ona13' ),
		),
	) );

});

/**
 * Create the default terms for each taxonomy if they don't exist
 */
add_action( 'admin_init', function() {

	$session_types = array(
			'Keynote',
			'Listen',
			'Make',
			'Solve',
		);
	$existing_session_types = get_terms( 'session-type', array( 'hide_empty' => false, 'fields' => 'names' ) );
	foreach( $session_types as $session_type ) {
		if ( ! in_array( $session_type, $existing_session_types ) )
			wp_insert_term( $session_type, 'session-type' );
	}

	$session_rooms = array(
			'Ballroom B',
			'Ballroom C',
			'Ballroom D',
			'Ballroom BCD',
			'International 4',
			'International 6',
			'International 8',
			'Room 401',
			'Room 402',
			'Room 403',
			'Room 404',
			'Room 405',
		);
	$existing_session_rooms = get_terms( 'session-room', array( 'hide_empty' => false, 'fields' => 'names' ) );
	foreach( $session_rooms as $session_room ) {
		if ( ! in_array( $session_room, $existing_session_rooms ) )
			wp_insert_term( $session_room, 'session-room' );
	}

	$session_formats = array(
			'Core Conversation',
			'Duo',
			'Interactive',
			'Keynote',
			'Lightning Talk',
			'Panel',
			'Showcase',
			'Simulation',
			'Solo',
			'Workshop',
		);
	$existing_session_formats = get_terms( 'session-format', array( 'hide_empty' => false, 'fields' => 'names' ) );
	foreach( $session_formats as $session_format ) {
		if ( ! in_array( $session_format, $existing_session_formats ) )
			wp_insert_term( $session_format, 'session-format' );
	}

});