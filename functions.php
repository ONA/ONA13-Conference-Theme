<?php

add_filter( 'body_class', 'ona13_body_class' );
function ona13_body_class( $classes ) {

	if ( is_page_template( 'page-templates/student-newsroom-page.php' ) ) {
		$classes[] = 'template-student-newsroom-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-4' ) && is_active_sidebar( 'sidebar-5' ) )
			$classes[] = 'two-sidebars';
	}
	return $classes;
}

add_action( 'widgets_init', 'ona13_widgets_init' );
function ona13_widgets_init() {

	register_sidebar( array(
		'name' => __( 'First Student Newsroom Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-4',
		'description' => __( 'Appears when using the optional Student Newsroom Page template', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
		register_sidebar( array(
		'name' => __( 'Second Student Newsroom Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-5',
		'description' => __( 'Appears when using the optional Student Newsroom Page template', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

add_action( 'wp_enqueue_scripts', 'ona13_wp_enqueue_scripts' );
function ona13_wp_enqueue_scripts() {

	wp_enqueue_script( 'jquery-isotope', get_stylesheet_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ) );
}