<?php
/**
 * The template for displaying Category pages.
 *
 * @subpackage Twenty_Twelve_Child for ONA
 */
wp_enqueue_style("category");
get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
       

		<?php 
		if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?= single_cat_title( '', false ); ?></h1>

			<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="archive-meta"><?php echo category_description(); ?></div>
			<?php endif; ?>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post(); ?>
				<div class="news_item">
                	<? if ( has_post_thumbnail() ) {
                    	the_post_thumbnail('thumbnail');
                    } else { ?>
                    	<img class="attachment-thumbnail wp-post-image" src="<? echo get_stylesheet_directory_uri(); ?>/images/category-filler.png" />
                    <? } ?>
                    <div>
                        <h2><a href="<? the_permalink();?>" title="<? the_title();?>" ><? the_title();?></a></h2>
                        <p class="date"><? the_time('M d, Y'); ?></p>
                        <p class="excerpt"><? the_excerpt_max_charlength(280);?></p>
                    </div>
                </div>
			<? endwhile;

			twentytwelve_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>