	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
        	<aside id="pagenav" class="widget widget_text">
            	<h3 class="widget-title">Related pages</h3>
                <? 	if ( is_page() ) {
					global $post;
					$ancestors = get_post_ancestors($post);
					echo '<ul>';
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
					echo '</ul>';
				} ?>
            </aside>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>