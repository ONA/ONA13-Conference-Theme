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
        	<p>The Midway is brought to you with the generous support of:</p>
            <div class="logos">
                <div id="sponsor_logo-2" class="sponsor_widget widget_sponsor_logo">
                    <a href="http://knightfoundation.org/" target="_blank"><img src="http://ona13.journalists.org/wp-content/uploads/2013/10/knight-foundation-300x42.png" class="attachment-sponsor-row wp-post-image" alt="Knight Foundation"></a>
                </div>
            </div>
		</div>
        <div>
        	<?php dynamic_sidebar( 'midway' ); ?>
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
		
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<div id="participants">
		<?	$args = array (
				'post_type'=> 'sponsors',
				'post_status'  => 'publish',
				'posts_per_page' => -1,
				'orderby'=> 'title',
				'order'=> 'ASC',
				'pagination' => false,
				'meta_query' => array(
					array(
						'key' => '_sponsor_level',
						'compare' => '=',
						'value' => 'Midway Participant'
					)
				)
			 ); 
			$my_query = new WP_Query($args);
			if ($my_query->have_posts()) { 
			
				echo '<h2 class="sponsor_level">'.$level.'</h2>';
				
				while ( $my_query->have_posts() ) : $my_query->the_post(); 
					$external_link = get_post_meta( get_the_ID(), '_sponsor_url', true );
					$sponsor_level = get_post_meta( get_the_ID(), '_sponsor_level', true );?>
	
				<!--<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<header class="entry-header">
						<h1 class="entry-title"><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></h1>
					</header>
			
					<div class="entry-content">
						<?php if ( has_post_thumbnail() ) { 
							 echo '<a href="'.$external_link.'" target="_blank">';
							 echo the_post_thumbnail( 'medium' );
							 echo '</a>'; 
						} 
						the_content(); ?>
						<p><a href="<?php echo $external_link;?>"  target='_blank'><?php echo $external_link;?></a></p>
					</div>
				</article>-->	

				<?php if ( has_post_thumbnail() ) { 
						 echo '<a href="'.$external_link.'" target="_blank">';
						 echo the_post_thumbnail( 'medium' );
						 echo '</a>'; 
					} ?>

			<?php 	wp_reset_postdata();
				endwhile;
			} ?>
			</div>

	</article><!-- #post -->


		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>