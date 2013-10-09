<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<!--<aside class="presenter-navigation">
			<a href="<?php echo site_url( '/presenters/' ); ?>"><?php _e( 'All Presenters' ); ?></a>
		</aside>-->
		<?php $speaker = new ONA_Speaker( get_the_ID() ); ?>

		<article class="post session" id="post-<?php the_ID(); ?>">
        	<ul class="session-meta">
                <li>
                <a href="<?php echo home_url(); ?>/speakers/">All speakers &rarr;</a>
                </li>
            </ul>
			<header class="entry-header">
                <h1 class="entry-title"><?php echo $speaker->get_name(); ?></h1>
            </header>
            
            <ul class="session-meta">
                <li class="title"><?php echo $speaker->get_title(); ?></li>
                <li class="org"><?php echo $speaker->get_organization(); ?></li>
                <?php if ( $twitter = $speaker->get_twitter() ) : 
				$twitterlink = str_replace("@", "", $speaker->get_twitter()); ?>
                <li class="twitter"><a href="https://twitter.com/<?php echo $twitterlink;?>" target="_blank"><?php echo $twitter; ?></a></li>
                <?php endif; ?>
            </ul>
			
			<div class="entry-content">
            	<div class="presenter-avatar">
            	<?php if ( $profile_url = $speaker->get_profile_url() ) : ?>
            		<img src="<?php echo esc_url( $profile_url ); ?>" />
            	<?php endif; ?>
                </div>
				<?php echo wpautop( $speaker->get_bio() );
				$websites = explode(",", $speaker->get_website());
				foreach($websites as $website){
					echo '<p><a href="'.trim($website).'" target="_blank">'.trim($website).'</a></p>';
				}?>
			</div>

			<? // We will eventually add the sessions in which they are speaking here ?>
            
		</article>

	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div><!-- #content -->

</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>