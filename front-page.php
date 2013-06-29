<?php
/**
 * The home template for ONA13
 *
 * @package WordPress
 * @subpackage ONA13
 * @since ONA13 1.0
 */
wp_enqueue_style("homepage");
wp_enqueue_style("google-sans-serif", "http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,100", array(), null );

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
        
        <!-- Main Info -->
        <div>
        	<div class="lead_text">
            	<p>The Online News Association 2013 Conference & Awards Banquet is the premier gathering of highly engaged digital journalists shaping the future of media. Learn about new tools and technologies, network with peers from around the world and celebrate excellence at the Online Journalism Awards.</p>
                <a href="<? echo home_url(); ?>/register/"><div class="button">Register today</div></a>
            </div>
            <ul class="headlines">
            	<li class="title"><a href="<? echo home_url(); ?>/category/news/">Recent News</a></li>
            <? $args = array(
				'numberposts' => 5,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'post_type' => 'post',
				'post_status' => 'publish');
			
				$recent_posts = wp_get_recent_posts( $args );
				foreach( $recent_posts as $recent ){
					echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="'.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'<br/><span>';
					the_time(get_option('date_format'));
					echo '</span></a></li> ';
				}
			?>
            	<li class="more"><a href="<? echo home_url(); ?>/category/news/">More news &rarr;</a></li>
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
        <div class="sponsors">
        	<h3><span>Sponsors</span></h3>
        	<div><p>Sponsorships are still available for ONA13. <a href="/sponsor/sponsor-packages/">Learn more &rarr;</a></p></div>
        </div>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>