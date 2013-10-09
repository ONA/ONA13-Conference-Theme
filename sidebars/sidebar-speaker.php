		<div id="secondary" class="widget-area" role="complementary">
        	<aside id="related_sessions" class="widget widget_text">
            	<ul class="headlines">
                	<h3 class="widget-title">Sessions</h3>
					<?  global $speaker;
					$name = $speaker->get_name();
					$args = array(
						'post_type' => 'ona_session',
						'post_status' => 'publish',
						'post_per_page' => -1,
						'meta_query' => array(
							array(
								'key' => 'speakers',
								'value' => $name,
								'compare' => 'LIKE'
							)
						)
					);
					$sessions = new WP_Query($args);
					if( $sessions->have_posts() ) {
						while ($sessions->have_posts()) {
            				$sessions->the_post();
							if (get_the_title() != "" && get_the_title() != "Hello world!") { ?>
							<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php } 
						} 
					}
					wp_reset_postdata(); ?>
                    
                    
                    
                    <li class="more"><a href="<?php echo home_url(); ?>/sessions/">More sessions &rarr;</a></li>
                </ul>
            </aside>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->