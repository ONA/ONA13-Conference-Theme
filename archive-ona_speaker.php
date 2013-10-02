<?php
add_filter( 'body_class', 'session_body' );
function session_body( $classes ) {
	$classes[] = 'full-width';
	return $classes;
}

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php 
		if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title">Speakers</h1>

			<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="archive-meta"><?php echo category_description(); ?></div>
			<?php endif; ?>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post(); 
				$category = get_the_category();
				$speaker = new ONA_Speaker( get_the_ID() ); ?>
				<div class="news_item">
                	<?php if ( has_post_thumbnail() ) { ?>
                    	<div class="speaker_img">
                    	<?php the_post_thumbnail(array(120,160,true)); ?>
                        </div>
                    <?php } else { ?>
                    	<img class="attachment-thumbnail wp-post-image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/category-filler.png" width="55" height="55"/>
                    <?php } ?>
                    <div<?php if(is_numeric($sponsor[0])) { ?> class="Sponsored"<?php } ?>>
                        
                        <?php if( $speaker->get_twitter() != '' ) { 
							echo '<p>';
							$twitterlink = str_replace("@", "", $speaker->get_twitter());
							echo '<a href="'.$twitterlink.'" target="_blank">'.$speaker->get_twitter().'</a>'; 
							echo '</p>';
						}?>
                        <h2><a href="<?php the_permalink();?>" title="<?php the_title();?>" ><?php the_title();?></a></h2>
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

<?php get_footer(); ?>