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
                    <div class="speaker_img">
                    <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                    	<?php the_post_thumbnail(array(300,160,true)); ?>
                    </a>
                    </div>
                    <div<?php if(is_numeric($sponsor[0])) { ?> class="Sponsored"<?php } ?>>
                        <p>
                        <?php if( $speaker->get_twitter() != '' ) { 
							$twitterlink = str_replace("@", "", $speaker->get_twitter());
							echo '<a href="'.$twitterlink.'" target="_blank">'.$speaker->get_twitter().'</a>'; 
						} else { 
							echo "---";
						} ?>
                        </p>
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