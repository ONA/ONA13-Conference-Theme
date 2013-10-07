	<?php if ( get_post_type() == 'ona_session' && is_single() ) : 
			include ('sidebars/sidebar-session.php');
	   elseif ( get_post_type() == 'sponsors' && is_singular() ) : 
			include ('sidebars/sidebar-sponsor.php');
	   elseif ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
        	<?php if ( is_singular() ) { ?>
        	<aside id="pagenav" class="widget widget_text">
            <?php 	if ( is_page() && get_the_title() != "About") {
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
						echo '<li><a href="../">'.$page->post_title.'</a></li>';
					}
					wp_list_pages(array(
						'child_of' => $top_page,
						'title_li' => ''
					));
				} else if (is_single() || get_the_title() == "About") {
					echo '<ul class="headlines">';
					echo '<h3 class="widget-title">Recent News</h3>';
					$category_id = get_cat_ID('News');
					$args = array(
					'numberposts' => 5,
					'category' => $category_id,
    				'exclude' => get_the_ID(),
					'orderby' => 'post_date',
					'order' => 'DESC',
					'post_type' => 'post',
					'post_status' => 'publish');
				
					$recent_posts = wp_get_recent_posts( $args );
					foreach( $recent_posts as $recent ){
						echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="'.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'<br/><span class="date">';
						echo get_the_time("M j, Y", $recent["ID"]);
						echo '</span></a></li> ';
					}
					echo '<li class="more"><a href="'.home_url().'/category/news/">More news &rarr;</a></li>';
				} 
				echo '</ul>'; ?>
            </aside>
            <?php } ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>