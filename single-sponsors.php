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

			<?php while ( have_posts() ) : the_post(); 
				$external_link = get_post_meta( get_the_ID(), '_sponsor_url', true );
				$sponsor_level = get_post_meta( get_the_ID(), '_sponsor_level', true ); ?>

                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title();?></h1>
                    </header>
                    <div class="entry-content">
						<?php if($sponsor_level!="Exhibitors" && $sponsor_level!="Supporters"){ ?>
                        <div class="sponsor-level <?php echo $sponsor_level;?>"><?php echo $sponsor_level;?> Sponsor</div>
                        <?php } else if($sponsor_level=="Supporters"){ ?>
                        <div class="sponsor-level <?php echo $sponsor_level;?>">Supporter</div>
                        <?php } else { ?>
                        <div class="sponsor-level">Exhibitor</div>
                        <?php } ?>
                    	<?php if ( has_post_thumbnail() ) { 
							 echo "<a href='".$external_link."' target='_blank'>";
							 echo the_post_thumbnail( 'medium' ); 
							 echo "</a>";
						} ?>
                        <?php the_content();?>
                        <p><a href="<?php echo $external_link;?>"><?php echo $external_link;?></a></p>
                        <div class="related">
                            <?	// Column for SPONSORED SESSIONS by this sponsor
							$args = array(
								'meta_key' => '_assigned_sponsor',
                				'meta_value' => $post->ID,
								'numberposts' => 5,
								'exclude' => $post->ID,
								'orderby' => 'post_date',
								'order' => 'DESC',
								'post_type' => 'ona_session',
								'post_status' => 'publish');
							$recent_posts = wp_get_recent_posts( $args );
							if(count($recent_posts) > 0) { 
								echo '<ul class="headlines">';
								echo '<h4 class="widget-title">Sponsored Sessions</h4>'; 
							}
							foreach( $recent_posts as $recent ){
								$session = new ONA_Session( $recent["ID"] );
								echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="'.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'<br/><span class="date">';
								echo $session->get_start_time( 'l - g:i A' );
								echo '</span></a></li> ';
							} 
							if(count($recent_posts) > 0) { 
								echo '</ul>';
							} 
                            // Column for SPONSORED POSTS by this sponsor
							$args = array(
								'meta_key' => '_assigned_sponsor',
                				'meta_value' => $post->ID,
								'numberposts' => 5,
								'exclude' => $post->ID,
								'orderby' => 'post_date',
								'order' => 'DESC',
								'post_type' => 'post',
								'post_status' => 'publish');
							$recent_posts = wp_get_recent_posts( $args );
							if(count($recent_posts) > 0) { 
								echo '<ul class="headlines">';
								echo '<h4 class="widget-title">Sponsored Posts</h4>'; 
							}
							foreach( $recent_posts as $recent ){
								echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="'.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'<br/><span class="date">';
								echo get_the_time("M j, Y", $recent["ID"]);
								echo '</span></a></li> ';
							} 
							if(count($recent_posts) > 0) { 
								echo '</ul>';
							} ?>
                        </div>
                    </div>
				</article>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>