		<div id="secondary" class="widget-area" role="complementary">
		<?php // Get sessions with same start time
            global $session;
            $args = array(
                'meta_key' => 'start_time',
                'meta_value' => $session->get_start_time(),
                'numberposts' => 5,
                'exclude' => get_the_ID(),
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'ona_session',
                'post_status' => 'publish');
            $concurrent_sessions = wp_get_recent_posts( $args ); 
            if (count($concurrent_sessions) > 0) { ?>
            <aside id="concurrent_sessions" class="widget widget_text">
            	<ul class="headlines">
                	<h3 class="widget-title">Concurrent sessions</h3>
                    <?php foreach( $concurrent_sessions as $post ){
                        $sidesession = new ONA_Session( $post['ID'] );
						echo '<li><a href="' . get_permalink( $sidesession->get_id() ) . '" title="'.esc_attr( $sidesession->get_title() ).'" >' . $sidesession->get_title() . '<br/>';
						if( $sidesession->get_room_name() ){
							echo '<span class="date">Room: ';
							echo $sidesession->get_room_name();
							echo '</span>';
						}
						echo '</a></li> ';
					} ?>
                    <li class="more"><a href="<?php echo home_url(); ?>/sessions/">More sessions &rarr;</a></li>
                </ul>
            </aside>
            <?php } ?>
            
            
        <?php // Get session in this room
            $args = array(
				'meta_query' => array(
					array(
						'key' => 'start_time',
						'value' => $session->get_start_time(),
						'compare' => '>'
					)
				),
				'tax_query' => array(
					array(
						'taxonomy' => 'session-room',
						'field' => 'slug',
						'terms' => $session->get_room_name()
					), 
				),
                'numberposts' => 3,
                'exclude' => get_the_ID(),
                'orderby' => 'post_date',
                'order' => 'ASC',
                'post_type' => 'ona_session',
                'post_status' => 'publish');
            $concurrent_sessions = wp_get_recent_posts( $args ); 
            if (count($concurrent_sessions) > 0) { ?>
            <aside id="concurrent_sessions" class="widget widget_text">
            	<ul class="headlines">
                	<h3 class="widget-title">Next in this room</h3>
                    <?php foreach( $concurrent_sessions as $post ){
                        $sidesession = new ONA_Session( $post['ID'] );
						echo '<li><a href="' . get_permalink( $sidesession->get_id() ) . '" title="'.esc_attr( $sidesession->get_title() ).'" >' . $sidesession->get_title() . '<br/>';
						if( $sidesession->get_room_name() ){
							echo '<span class="date">';
							echo $sidesession->get_start_time('l - g:i A').' - '.$sidesession->get_end_time('g:i A');
							echo '</span>';
						}
						echo '</a></li> ';
					} ?>
                    <li class="more"><a href="<?php echo home_url(); ?>/sessions/">More sessions &rarr;</a></li>
                </ul>
            </aside>
            <?php } ?>
		</div><!-- #secondary -->