	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
        	<? if ( is_singular() ) { ?>
        	<aside id="pagenav" class="widget widget_text">
            <? 	if ( is_page() ) {
					echo '<ul>';
					echo '<h3 class="widget-title">Related pages</h3>';
					global $post;
					$ancestors = get_post_ancestors($post);
					
					if(count($ancestors)==0){
						$top_page = $post->ID;
					} else {
						$top_page = array_pop($ancestors);
						$uri = get_page_uri($top_page);
						$page = get_page($top_page);
						echo '<li><a href="'. $uri .'">'.$page->post_title.'</a></li>';
					}
					wp_list_pages(array(
						'child_of' => $top_page,
						'title_li' => ''
					));
				} else if (in_category( 'news' ) && is_single() ) {
					echo '<ul class="headlines">';
					echo '<h3 class="widget-title">Recent News</h3>';
					$args = array(
					'numberposts' => 5,
					'category' => 4,
    				'exclude' => get_the_ID(),
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
					echo '<li class="more"><a href="'.home_url().'/category/news/">More news &rarr;</a></li>';
				} 
				echo '</ul>'; ?>
            </aside>
            <? } ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>