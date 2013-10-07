		<div id="secondary" class="widget-area" role="complementary">
		<?php // Get sessions with same start time
            global $session;
            $args = array(
                // 'meta_key' => 'start_time',
                // 'meta_value' => 'Midway',
				'tax_query' => array(
					array(
						'taxonomy' => 'session-type',
						'field' => 'slug',
						'terms' => 'make' // <-- Needs to be "midway"
					)
				),
                'numberposts' => 15,
                'exclude' => get_the_ID(),
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'ona_session',
                'post_status' => 'publish');
            $concurrent_sessions = wp_get_recent_posts( $args ); 
            if (count($concurrent_sessions) > 0) { ?>
            <aside id="concurrent_sessions" class="widget widget_text">
            	<ul class="headlines">
                	<h3 class="widget-title">Midway sessions</h3>
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
		</div><!-- #secondary -->