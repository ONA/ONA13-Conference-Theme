<?php get_header(); ?>

<div id="primary" class="site-content">
	<div id="content" role="main">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<!--<aside class="presenter-navigation">
			<a href="<?php echo site_url( '/presenters/' ); ?>"><?php _e( 'All Presenters' ); ?></a>
		</aside>-->

		<article class="post session" id="post-<?php the_ID(); ?>">
			<header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            
            <ul class="session-meta">
                <li class="title"><?php echo ONA12_Presenter::get('title'); ?></li>
                <li class="org"><?php echo ONA12_Presenter::get('organization'); ?></li>
                <?php if ( ONA12_Presenter::get( 'twitter' ) ) : ?>
                <li class="twitter"><?php echo ONA12_Presenter::get( 'twitter' ); ?></li>
                <?php endif; ?>
            </ul>
			
			<div class="entry-content">
            	<div class="presenter-avatar">
					<?php echo ONA12_Presenter::get_avatar( 'ona12-medium-tall-avatar' ); ?>
                </div>
				<?php the_content(); ?>
			</div>

			<? // We will eventually add the sessions in which they are speaking here ?>
            
		</article>

	<?php endwhile; ?>

	<?php else : ?>

		<h2>Not Found</h2>

	<?php endif; ?>
	
	</div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>