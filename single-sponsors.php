<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><? the_title();?></h1>
                    </header>
                    <div class="entry-content">
                    	<? if ( has_post_thumbnail() ) { 
							 echo the_post_thumbnail( 'medium' ); 
						} ?>
                        <? the_content();?>
                    </div>
				</article>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>