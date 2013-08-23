<?php
/**
 * The template for displaying Category pages.
 *
 * @subpackage Twenty_Twelve_Child for ONA
 */
get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
       

		<?php 
		if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php echo  single_cat_title( '', false ); ?></h1>

			<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="archive-meta"><?php echo category_description(); ?></div>
			<?php endif; ?>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post(); ?>
				<div class="news_item">
                	<?php if ( has_post_thumbnail() ) {
                    	the_post_thumbnail('thumbnail');
                    } else { ?>
                    	<img class="attachment-thumbnail wp-post-image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/category-filler.png" />
                    <?php } ?>
                    <div>
                        <h2><a href="<?php the_permalink();?>" title="<?php the_title();?>" ><?php the_title();?></a></h2>
                        <p class="date"><?php the_time('M d, Y'); ?></p>
                        <p class="excerpt"><?php the_excerpt_max_charlength(280);?></p>
                    </div>
                </div>
			<?php endwhile;

			twentytwelve_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>