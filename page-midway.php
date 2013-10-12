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
add_filter('body_class','scheduleClass');
function scheduleClass($classes) {
	$classes[] = 'full-width';
	return $classes;
}
get_header(); ?>

	<div class="midway_logo">
    	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/midway2.png" />
    </div>
        
	<div id="primary" class="site-content midway">
		<div id="content" role="main">
        <div class="lead_text">
		<p>The Midway is where ONA13 comes to life. In this participatory space, we connect people, technology and journalism. The place where innovative ideas are born at the conference &mdash; this is a home to...</p>
        </div>
        <div id="icons">
        	<h3>Explore, Play, Build, Repeat.</h3>
        	<div>
            	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/midway-icon-explore.png" />
                <h4>Explore</h4>
                <p>We discover and connect with the most creative innovators of digital journalism.</p>
            </div>
            <div>
            	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/midway-icon-play.png" />
                <h4>Play</h4>
                <p>We bring a child-like curiosity using crafts, blinky lights and games to create new solutions for journalism.</p>
            </div>
            <div>
            	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/midway-icon-repeat.png" />
                <h4>Build</h4>
                <p>We collaborate with experts in the space to design, iterate and create. Then repeat.</p>
            </div>
        </div>
        <div class="sponsor-row">
        	<h4><a href="<?php echo home_url(); ?>/midway/participants/">Innovators</a></h4>
        	<p>The Midway is brought to you with the generous support of:</p>
            <div class="logos">
                <div id="sponsor_logo-2" class="sponsor_widget widget_sponsor_logo">
                    <a href="http://knightfoundation.org/" target="_blank"><img src="http://ona13.journalists.org/wp-content/uploads/2013/10/knight-foundation-300x42.png" class="attachment-sponsor-row wp-post-image" alt="Knight Foundation"></a>
                </div>
            </div>
		</div>
        <!--<div>
        	<?php dynamic_sidebar( 'midway' ); ?>
        </div>-->
        
		<h1 class="entry-title" style="display:none;"><?php the_title(); ?></h1>

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
				while ( $my_query->have_posts() ) : $my_query->the_post(); 
					$external_link = get_permalink(get_the_ID());
					if ( has_post_thumbnail() ) { 
						 echo '<a href="'.$external_link.'">';
						 echo the_post_thumbnail( 'medium' );
						 echo '</a>'; 
					} 				
				endwhile; 
				wp_reset_postdata(); 
			} ?>
			</div>


		<? $args = array(
                // 'meta_key' => 'start_time',
                // 'meta_value' => 'Midway',
				'tax_query' => array(
					array(
						'taxonomy' => 'session-type',
						'field' => 'name',
						'terms' => 'Midway' // <-- Needs to be "midway"
					)
				),
                'posts_per_page' => -1,
				'meta_key' => 'start_time',
				'orderby' => 'meta_value',
                'order' => 'ASC',
                'post_type' => 'ona_session',
				'no_found_rows' => true
				);
			
			$sessions = new WP_Query( $args );
			while( $sessions->have_posts() ) {
				$sessions->the_post();
				$start_timestamp = get_post_meta( get_the_ID(), 'start_time', true );
				$start_date = date( 'm/d/Y', $start_timestamp );
				$start_time = date( 'g:i a', $start_timestamp );
				$all_sessions[$start_date][$start_time][$post->ID] = $post;
			}
			$i = -1;
			if (count($all_sessions) == 0) {
				echo '<h3 class="schedule_day">Midway schedule coming soon</h3>';
			} else {
			foreach( $all_sessions as $session_day => $days_sessions ):
				$day_full_name = date( 'l, F d', strtotime( $session_day ) );
				$i++;
				$day_slugify = sanitize_title( $day_full_name );
			?>
            <div id="session-day-<?php echo $day_slugify; ?>" class="session-day">
            <h3 id="title<?php echo $i;?>" class="schedule_day"><span>Day <?php echo ($i+1);?> - <?php echo $day_full_name;?></span></h3>
            <div class="schedule_nav"></div>
            <div class="day-sessions">
			<?php foreach( $days_sessions as $start_time => $posts ): ?>
                <div class="session-time-block">
                    <div class="session-start-time"><?php echo $start_time; ?></div>			
                    <ul class="session-list session-count-<?php echo count( $posts ); ?>">
                    <?php foreach( $posts as $post ): 
                        setup_postdata( $post ); 
                        $session = new ONA_Session( get_the_ID() ); ?>
                        <a href="<?php the_permalink(); ?>">
                        <?php if ($session->get_session_type_name() == ''){ 
                            $session_type = "Other";
                        } else {
                            $session_type = $session->get_session_type_name();
                        } ?>
                        <li class="single-session <?php echo $session_type;?>">
                            <h4 class="session-title"><?php echo $session->get_title(); ?></h4>
                            <div class="meta"><?php echo $session->get_room_name();
                            if ( $session->get_hashtag() != "" ) {
                                $hash = $session->get_hashtag(); 
                                echo ' | #'.$hash;
                            } ?></div>
                            <!--<div class="session-description"><?php the_excerpt(); ?></div>-->
                        </li>
                        </a>
                    <?php endforeach; ?>
                    </ul>
                    <div class="clear-left"></div>
                </div>
            <?php endforeach; ?>
            </div>
            </div>
        	<?php endforeach; 
			} ?>

		</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>