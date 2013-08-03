<? 
add_filter( 'body_class', 'session_body' );
function session_body( $classes ) {
	$classes[] = 'full-width';
	return $classes;
}

get_header(); ?>

<div id="content-row" class="container_12">

	<?php get_template_part( 'loop', 'session_archive' ); ?>

</div><!-- #content-row -->

<?php get_footer(); ?>