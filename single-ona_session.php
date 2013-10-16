<?php get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); 
				$session = new ONA_Session( get_the_ID() );
				$rebelmouse = $session->get_rebelmouse();
                ?>
                <article>
                	<ul class="session-meta">
                    	<li class="track">
                        	<div class="<?php echo $session->get_session_type_name();?>"><?php echo ( $session->get_session_type_name() ) ? $session->get_session_type_name() : 'Other'; ?></div>
                        </li>
                        <?php if ( $session->get_session_format_name() != "" ) { ?>
                        <li>
                        <?php echo $session->get_session_format_name();?>
                        </li>
                        <?php } ?>
                        <li>
                        <a href="<?php echo home_url(); ?>/sessions/">All sessions &rarr;</a>
                        </li>
                    </ul>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php echo $session->get_title(); ?></h1>
                    </header>
                    <ul class="session-meta">
                    	<li class="day"><?php echo $session->get_start_time( 'l - g:i A' ); ?> - <?php echo $session->get_end_time( 'g:i A' );?></li>
                        <li class="room"><?php echo ( $session->get_room_name() ) ? $session->get_room_name() : '<em>No Room</em>'; ?></li>
                        <li class="hash">
                        <?php if ( $session->get_hashtag() != "" ) {
							$hash = $session->get_hashtag(); ?>
                        <a href="https://twitter.com/search?q=%23<?php echo $hash;?>" target="_blank">#<?php echo $hash;?></a>
                        <?php } else { ?>
                        <a href="https://twitter.com/search?q=%23ONA13" target="_blank">#ONA13</a>
                        <?php } ?>
                        </li>
                        <li>
                        <a href="<?php the_permalink();?> " title="Add to Calendar" class="addthisevent">
                            Add to Calendar
                            <span class="_start"><?php echo $session->get_start_time( 'm-d-Y H:i' ); ?></span>
                            <span class="_end"><?php echo $session->get_end_time( 'm-d-Y H:i' ); ?></span>
                            <span class="_zonecode">15</span>
                            <span class="_summary"><?php echo $session->get_title(); ?></span>
                            <span class="_description"><?php echo substr(strip_tags($session->get_description()), 0, 500);?>... More at <?php the_permalink();?></span>
                            <span class="_location">Atlanta Marriott - <?php echo ( $session->get_room_name() ) ? $session->get_room_name() : '<em>No Room</em>'; ?></span>
                            <span class="_organizer">ONA13</span>
                            <span class="_all_day_event">false</span>
                            <span class="_date_format">DD/MM/YYYY</span>
                        </a>
                        </li>
                    </ul>
                    <div class="entry-content">
                    	
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
                        <p><?php echo wpautop($session->get_description());?></p>
                        <?php 
						$av_content = get_post_meta( get_the_ID(), '_av_content' );
						if( $av_content ) { $av_content = $av_content[0]; }
						if( $av_content ) { ?>
							<?php if ($av_content['video']) { 
								if ($now > ($session->get_start_time()-300) && $now < ($session->get_end_time()+300)) { 
								
								// LIVESTREAM EMBED CODE HERE
								
								} else { ?>
                            <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-video.png" height="14" /> &mdash; This session will have live video</p>
                            <?php }
							} if($av_content['audio']) { ?>
                            <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-audio.png" height="14" /> &mdash; This session will have recorded audio</p>
                            <?php } ?>
                        <?php } ?>
                        <?php $speakers = $session->get_speakers(); 
						if ($speakers[0]) { ?>
                    	<div class="speakers">
                        	<h4>Speakers</h4>
							<?php $speakers = explode( ',', $speakers[0]);
							foreach ($speakers as $speaker){
                                $speaker_name = trim($speaker);
                                ?>
                            <div class="speaker">
                            <?php if ( $speaker_obj = ONA_Speaker::get_by_name( $speaker_name ) ) : 
								$twitterlink = str_replace("@", "", $speaker_obj->get_twitter()); 
								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $speaker_obj->get_id() ), "thumbnail" );
								$thumburl = $thumb[0];?>
                                <?php if ( $thumburl ) { ?>
                                <img src="<?php echo esc_url( $thumburl ); ?>" />
                                <p><a href="<?php echo get_permalink( $speaker_obj->get_id() ); ?>"><?php echo $speaker;?></a> - <?php echo $speaker_obj->get_title().', '.$speaker_obj->get_organization();?><br /><?php echo '<a href="https://twitter.com/'.$twitterlink.'" target="_blank">'.$speaker_obj->get_twitter().'</a>';
								$websites = explode(",", $speaker_obj->get_website());
								foreach($websites as $website){
								echo ' | <a href="'.trim($website).'" target="_blank">'.trim($website).'</a>';}?></p>
                                <?php } else { ?>
                                <p class="noinfo"><?php echo $speaker.' - '.$speaker_obj->get_organization();?></p>
                                <?php } ?>
                            <?php else : ?>
                                <p><?php echo $speaker_name;?></p>
                            <?php endif; ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        
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
                        <?php  // Liveblogging begins
						if ($rebelmouse) { ?>
                        <div class="liveblogging">
                        <script type="text/javascript" class="rebelmouse-embed-script" src="https://www.rebelmouse.com/static/js-build/embed/embed.js?site=<?php echo $rebelmouse;?>&height=1500&flexible=1"></script>
                        </div>
                        <?php } ?>
                        <?php ONA_display_related_by_tag(); ?>
                    </div>
                    
                    
                    <footer class="entry-meta">
                    
                    </footer>
				</article>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
