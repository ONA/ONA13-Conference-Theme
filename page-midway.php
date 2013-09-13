<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div class="midway_logo">
    	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/midway.png" />
    </div>
    
    <?php get_sidebar(); ?>
    
	<div id="primary" class="site-content">
		<div id="content" role="main">
        <div class="lead_text">
		<p>An experimental space for journalism and technology and the conference playground, the Midway is the place for hands-on learning and experimentation with the most innovative tech and tools in digital journalism.</p>
        </div>
        <div class="sponsor-row">
        	<p>The Midway is sponsored by:</p>
            <div class="logos">
                <div id="sponsor_logo-2" class="sponsor_widget widget_sponsor_logo">
                    <a href="http://diederich.marquette.edu/" target="_blank"><img width="140" height="49" src="http://adam.ona/ona13/wp-content/uploads/2013/08/marquette-140x49.png" class="attachment-sponsor-row wp-post-image" alt="marquette"></a>
                </div>
                <div id="sponsor_logo-3" class="sponsor_widget widget_sponsor_logo"><div class="more">Your logo here</div></div>
            </div>
		</div>
        
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<?php _e( 'Featured post', 'twentytwelve' ); ?>
		</div>
		<?php endif; ?>
		<header class="entry-header" style="display:none;">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
			<?php twentytwelve_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->


		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>