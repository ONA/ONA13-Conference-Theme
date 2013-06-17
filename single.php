<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
wp_enqueue_style("post");
wp_enqueue_style("google-sans-serif", "http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,100", array(), null );

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<? if (has_post_thumbnail( $post->ID ) ): ?>
				<? $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                <div id="article_img" style="height:<?=$image[2];?>px;">
                	<img src="<?=$image[0];?>" width="<?=$image[1];?>" height="<?=$image[2];?>"/>
                </div>
                <? endif; ?>
                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><? the_title();?></h1>
                        <p><span>By <? the_author();?> / </span>
                        <? the_time('M d, Y'); ?>
                        </p>
                    </header>
                    <div class="entry-content">
                        <? the_content();?>
                    </div>
                    <footer class="entry-meta">
                    
                    </footer>
				</article>

				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>