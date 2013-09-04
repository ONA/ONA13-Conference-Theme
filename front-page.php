<?php
/**
 * The home template for ONA13
 *
 * @package WordPress
 * @subpackage ONA13
 * @since ONA13 1.0
 */
get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
        
        <!-- Main Info -->
        <div class="lead_package">
        	<div class="lead_text">
            	<p>The Online News Association 2013 Conference & Awards Banquet is the premier gathering of highly engaged digital journalists shaping media now. Learn about new tools and technologies, network with peers from around the world and celebrate excellence at the Online Journalism Awards.</p>
                <a href="<?php echo home_url(); ?>/register/"><div class="button">Register today</div></a>
            </div>
            <ul class="headlines">
            	<li class="title"><a href="<?php echo home_url(); ?>/category/headlines/">Recent Headlines</a></li>
            <?php $category_id = get_cat_ID('Headlines');
				$args = array(
				'numberposts' => 3,
				'category' => $category_id,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_type' => 'post',
				'post_status' => 'publish');
			
				$recent_posts = wp_get_recent_posts( $args );
				foreach( $recent_posts as $recent ){
					$category = get_the_category($recent["ID"]); 
					$sponsor = get_post_meta( $recent["ID"], '_assigned_sponsor' );
					echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="'.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'<br/>';
					echo '<p class="date">'.get_the_time('M j, Y', $recent["ID"]);
					echo ' | <span>'.$category[0]->cat_name.'</span>';
					if(is_numeric($sponsor[0])) {
						echo '<span class="Sponsored">Sponsored</span>';	
					}
					echo '</p>';
					echo '</a></li> ';
				}
			?>
            	<li class="more"><a href="<?php echo home_url(); ?>/category/headlines/">More headlines &rarr;</a></li>
            </ul>
        </div>
        
        <!-- Conference Details -->
        <div>
        	<?php dynamic_sidebar( 'row1' ); ?>
        </div>
        
        
        <!-- Participate -->
        <div>
        	<h3><span>Participate</span></h3>
            <?php dynamic_sidebar( 'row2' ); ?>
        </div>
        
        <!-- Sponsors -->
        <div class="sponsor-row">
        	<h3><span>Sponsors</span></h3>
            <div class="logos">
                <?php dynamic_sidebar( 'sponsors' ); ?>
            </div>
        	<div><p>Sponsorships are still available for ONA13. <a href="/sponsor/sponsor-packages/">Learn more &rarr;</a></p></div>
        </div>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>