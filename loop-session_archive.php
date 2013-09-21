<div id="primary" class="site-content">
		<div id="content" role="main">

	<?php
		$args = array(
			'post_type' => 'ona_session',		
			'posts_per_page' => 100,
			'meta_key' => 'start_time',
			'orderby' => 'meta_value',
			'order' => 'asc',
			'no_found_rows' => true,
		);
	
		if ( is_tax() ) {
			$queried_term = get_queried_object();	
			$args['tax_query'] = array(
				array(
					'taxonomy' => $queried_term->taxonomy,
					'field' => 'id',
					'terms' => $queried_term->term_id,
				),
			);
		}
	
		$sessions = new WP_Query( $args );
	
		$session_days = array(
			'10/17/2013',
			'10/18/2013',
			'10/19/2013',
		);

		// Some crafty shit to put the current date at the top
		$today = date( 'm/d/Y', ( time() - 25200 ) );
		if ( in_array( $today, $session_days ) ) {
			if ( 1 == array_search( $today, $session_days ) ) {
				$yesterday = array_shift( $session_days );
				$session_days[] = $yesterday;
			} else if ( 2 == array_search( $today, $session_days ) ) {
				$saturday = array_pop( $session_days );
				array_unshift( $session_days, $saturday );
			}
		}
	
		// Load all of the sessions into an array based on start date and time
		$all_sessions = array(
				$session_days[0] => array(),
				$session_days[1] => array(),
				$session_days[2] => array(),
			);
		while( $sessions->have_posts() ) {
			$sessions->the_post();
			$start_timestamp = get_post_meta( get_the_ID(), 'start_time', true );
			$start_date = date( 'm/d/Y', $start_timestamp );
			$start_time = date( 'g:i a', $start_timestamp );
			$all_sessions[$start_date][$start_time][$post->ID] = $post;
		}
		?>

		<?php if ( is_tax() ): ?>
			<?php $queried_object = get_queried_object(); ?>
			<h2><a href="<?php echo get_site_url( null, '/sessions/' ); ?>"><?php _e( 'All Sessions' ); ?></a>
			<?php
				$term_title_html = ' &rarr; <a href="' . get_term_link( $queried_object ) . '">' . esc_html( $queried_object->name ) . '</a>';
				if ( $queried_object->parent ) {
					$parent_term = get_term_by( 'id', $queried_object->parent, $queried_object->taxonomy );
					$term_title_html = ' &rarr; <a href="' . get_term_link( $parent_term ) . '">' . esc_html( $parent_term->name ) . '</a>' . $term_title_html;
				}
				echo $term_title_html;
			?></h2>
			<?php if ( $queried_object->description ): ?>
			<div class="term-description">
			<?php echo wpautop( $queried_object->description ); ?>	
			</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="left">
            	<header class="entry-header">
                	<h1 class="entry-title">Program Schedule</h1>
                </header>
                <p>Welcome to the first edition of our program. For a complete description of <strong>Listen</strong>, <strong>Solve</strong> and <strong>Make</strong> and details on the conference, take a look at our <a href="http://ona13.journalists.org/2013/07/12/join-us-at-ona13-the-town-hall-for-journalism/" target="_blank">blog post</a>. You can organize your view by clicking on  Day 1, 2 or 3 or the L, S, and M buttons at the top of the schedule. Look for more sessions and speakers in the coming weeks and an interactive version will be rolled out in August.</p>
            </div>
            <div class="right">
                <p><strong>ONA13 Guiding Principles</strong></p>
            	<p><strong>Engage</strong> with technology, the journalism community and each other.</p>
                <p><strong>Innovate</strong> ideas and approaches to challenges and creating compelling stories.</p>
                <p><strong>Inspire</strong> through conversations with dedicated professionals who remind us why we do what we do.</p>
            </div>
            <div class="key">
                <div>
                    <div class="label listen">Listen</div>
                    <div>Core sessions</div>
                </div>
                <div>
                    <div class="label solve">Solve</div>
                    <div>Interactive conversations</div>
                </div>
                <div>
                    <div class="label make">Make</div>
                    <div>Workshops</div>
                </div>
                <div>
                    <div class="label midway">Midway</div>
                    <div>Immersive learning</div>
                </div>
                <div>
                    <div class="label other">Other</div>
                    <div>Keynotes, Lunches</div>
                </div>
            </div>
		<?php endif; ?>

<?php $i = -1;
foreach( $all_sessions as $session_day => $days_sessions ):
	$day_full_name = date( 'l, F d', strtotime( $session_day ) );
	$i++;
	$day_slugify = sanitize_title( $day_full_name );
?>

<div id="session-day-<?php echo $day_slugify; ?>" class="session-day">
	<div class="sponsor-row">
    	<p>ONA13 is sponsored in part by:</p>
        <div class="logos">
            <?php dynamic_sidebar( 'sponsors'.$i ); ?>
        </div>
    </div>
    <h3 id="title<?php echo $i;?>" class="schedule_day"><span>Day <?php echo ($i+1);?> - <?php echo $day_full_name;?></span></h3>
    <div class="schedule_nav">
    	<div class="labels">
        	<div class="days">Move to <span>a day</span></div>
            <div class="types">Filter to <span>a session type</span></div>
        </div>
        <div class="buttons">
            <div class="day">Day 1</div>
            <div class="day">Day 2</div>
            <div class="day">Day 3</div>
            <div class="type listen" data:name="Listen">L</div>
            <div class="type solve" data:name="Solve">S</div>
            <div class="type make" data:name="Make">M</div>
            <div class="type midway" data:name="Midway">MW</div>
            <div class="type other" data:name="Other">Other</div>
            <div class="type all" data:name="All">All</div>
        </div>
    </div>
    
    
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
<?php endforeach; ?>

	</div><!-- #posts -->
	</div><!-- #posts-container -->