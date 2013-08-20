<?php get_header(); ?>


	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); 
				$session_id = get_post_meta( $post->ID, 'session_id', true );
				$session_start = get_post_meta( $post->ID, 'start_time', true );
				$session = ONA_Session::get_by_session_id( $session_id ); ?>
                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><?=$session->get_title();?></h1>
                    </header>
                    <ul class="session-meta">
                    	<li class="day"><?= date('l - g:i A', $session_start);?></li>
                        <li class="room">Room</li>
                        <li class="track type1">Track</li>
                        <li class="hash">Hash</li>
                    </ul>
                    <div class="entry-content">
                    	<div class="presenters"></div>
                        <? $sponsor = get_post_meta( get_the_ID(), '_assigned_sponsor' );
						if ( $sponsor[0] != "") { 
							$sponsor_name = get_the_title($sponsor[0]); 
							$sponsor_link = get_permalink($sponsor[0]);
							$external_link = get_post_meta( $sponsor[0], '_sponsor_url', true );
							$tagline = get_post_meta( $sponsor[0], '_sponsor_tagline', true );?>
                       	<div class="shoulderbox">
                        	<p class="sponsored">Sponsor</p>
                            <p>ONA13 is sponsored by <a href="<?=$sponsor_link?>"><?=$sponsor_name?></a><? if($tagline){?>, <? echo $tagline; } ?></p>
                            <? if ( has_post_thumbnail($sponsor[0]) ) { 
								 echo "<a href='".$external_link."'>".get_the_post_thumbnail($sponsor[0], 'sponsor-shoulder' )."</a>"; 
							} ?>
                        </div>
                        <? } ?>
                        <p><?=$session->get_description();?></p>
                        <div class="resources"></div>
                        <? ONA_display_related_by_tag(); ?>
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
