<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); 
				$session = new ONA_Session( get_the_ID() );
                ?>
                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php echo $session->get_title(); ?></h1>
                    </header>
                    <ul class="session-meta">
                    	<li class="day"><?php echo $session->get_start_time( 'l - g:i A' ); ?></li>
                        <li class="room"><?php echo $session->get_room_name(); ?></li>
                        <li class="track type1"><?php echo $session->get_session_type_name(); ?></li>
                        <li class="hash"><?php echo ( $session->get_hashtag() ) ? $session->get_hashtag() : '<em>None</em>'; ?></li>
                    </ul>
                    <div class="entry-content">
                    	<div class="presenters"></div>
                        <?php $sponsor = get_post_meta( get_the_ID(), '_assigned_sponsor' );
						if ( $sponsor[0] != "") { 
							$sponsor_name = get_the_title($sponsor[0]); 
							$sponsor_link = get_permalink($sponsor[0]);
							$external_link = get_post_meta( $sponsor[0], '_sponsor_url', true );
							$tagline = get_post_meta( $sponsor[0], '_sponsor_tagline', true );?>
                       	<div class="shoulderbox">
                        	<p class="sponsored">Sponsor</p>
                            <p>ONA13 is sponsored by <a href="<?php echo $sponsor_link?>"><?php echo $sponsor_name?></a><?php if($tagline){?>, <?php echo $tagline; } ?></p>
                            <?php if ( has_post_thumbnail($sponsor[0]) ) { 
								 echo "<a href='".$external_link."'  target='_blank'>".get_the_post_thumbnail($sponsor[0], 'sponsor-shoulder' )."</a>"; 
							} ?>
                        </div>
                        <?php } ?>
                        <p><?php echo $session->get_description();?></p>
                        <div class="resources"></div>
                        <div class="liveblogging">
                        <? 
						$url = 'https://www.rebelmouse.com/static/js-build/embed/embed.js?site=nekolaweb2%2Fdfsdfsf&height=1500&flexible=1';
print_r(get_headers($url, 1));
?>
                        <script type="text/javascript" class="rebelmouse-embed-script" src="https://www.rebelmouse.com/static/js-build/embed/embed.js?site=nekolaweb2%2Fsdsdgsg&height=1500&flexible=1"></script>
                        </div>
                        <?php ONA_display_related_by_tag(); ?>
                        <!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                        <a class="addthis_button_preferred_1"></a>
                        <a class="addthis_button_preferred_2"></a>
                        <a class="addthis_button_preferred_3"></a>
                        <a class="addthis_button_preferred_4"></a>
                        <a class="addthis_button_compact"></a>
                        <a class="addthis_counter addthis_bubble_style"></a>
                        </div>
                        <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=onlinenewsassociation"></script>
                        <!-- AddThis Button END -->
                    </div>
                    
                    
                    <footer class="entry-meta">
                    
                    </footer>
				</article>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
