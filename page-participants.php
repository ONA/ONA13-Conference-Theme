<?php get_header(); ?>

<div id="content-row" class="container_12">

	<div id="primary" class="site-content sponsor-page">
		<div id="content" role="main">
		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title">Midway Participants</h1>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			//
			// We need to order these by groups of sponsor level
			//
			
			$sponsor_level_array = array("Midway Participant");
			//
			foreach ( $sponsor_level_array as $level ) {
				$args = array (
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
							'value' => $level
						)
					)
		  		 ); 
				$my_query = new WP_Query($args);
				if ($my_query->have_posts()) { 
				
					echo '<h2 class="sponsor_level">Innovators</h2>';
					
					while ( $my_query->have_posts() ) : $my_query->the_post(); 
						$external_link = get_post_meta( get_the_ID(), '_sponsor_url', true );
						$sponsor_level = get_post_meta( get_the_ID(), '_sponsor_level', true );?>
		
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
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
						</div><!-- .entry-content -->
					</article><!-- #post -->	
	
				<?php 	wp_reset_postdata();
					endwhile;
				}
			}
		endif; ?>
			

		</div><!-- #posts -->
	</div><!-- #posts-container -->
</div><!-- #content-row -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>