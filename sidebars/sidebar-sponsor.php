		<div id="secondary" class="widget-area" role="complementary">
        	<aside id="related_sessions" class="widget widget_text">
            	<ul class="headlines">
                	<h3 class="widget-title">More sponsors</h3>
				<?	$args = array(
						'showposts' => 5,
						'post__not_in' => array(get_the_ID()),
						'orderby' => 'rand',
						'post_type' => 'Sponsors',
						'post_status' => 'publish');
					$query = new WP_Query($args);
					if( $query->have_posts() ) { 
						while ($query->have_posts()) {
            			$query->the_post();?>
						<li><a href="<?php the_permalink() ?>" title="'.esc_attr($recent["post_title"]).'" ><?php the_title() ?></a></li>
					<? } } 
					wp_reset_query(); ?>
                    <li class="more"><a href="<? echo home_url(); ?>/sponsors/">More sponsors &rarr;</a></li>
                </ul>
            </aside>
			<? dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->