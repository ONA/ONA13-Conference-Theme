		<div id="secondary" class="widget-area" role="complementary">
		<?	// Get sessions with same start time
            global $session_start;
            $args = array(
                'meta_key' => 'start_time',
                'meta_value' => $session_start,
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
                    <? foreach( $concurrent_sessions as $session ){
						echo '<li><a href="' . get_permalink($session["ID"]) . '" title="'.esc_attr($session["post_title"]).'" >' .   $session["post_title"].'<br/><span>Room ';
						echo get_post_meta( get_the_ID(), 'session_room', true );
						echo '</span></a></li> ';
					} ?>
                    <li class="more"><a href="<? echo home_url(); ?>/sessions/">More sessions &rarr;</a></li>
                </ul>
            </aside>
            <? } ?>
		</div><!-- #secondary -->