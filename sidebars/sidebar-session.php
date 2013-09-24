		<div id="secondary" class="widget-area" role="complementary">
		<?php // Get sessions with same start time
            global $session_start;
            $args = array(
                'meta_key' => 'start_time',
                'meta_value' => $session_start,
				/* 'meta_query' => array(
					array(
					'meta_key' => 'start_time',
					'meta_value' => $session_start
					),array(
					'meta_key' => 'start_time',
					'meta_value' => $session_start
					)
				), */
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
                        $session = new ONA_Session( $post['ID'] );
						echo '<li><a href="' . get_permalink( $session->get_id() ) . '" title="'.esc_attr( $session->get_title() ).'" >' . $session->get_title() . '<br/><span class="date">Room: ';
						echo $session->get_room_name();
						echo '</span></a></li> ';
					} ?>
                    <li class="more"><a href="<?php echo home_url(); ?>/sessions/">More sessions &rarr;</a></li>
                </ul>
            </aside>
            <?php } ?>
		</div><!-- #secondary -->