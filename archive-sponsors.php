<? get_header(); ?>

<div id="content-row" class="container_12">

	<div id="primary" class="site-content sponsor-page">
		<div id="content" role="main">
		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title">Sponsors</h1>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            	
                <header class="entry-header">
                    <h1 class="entry-title"><a href="<?php the_permalink(); ?> "><?php the_title(); ?></a></h1>
                </header>
        
                <div class="entry-content">
					<? if ( has_post_thumbnail() ) { 
                         echo the_post_thumbnail( 'medium' ); 
                    } 
					the_content(); 
					$external_link = get_post_meta( get_the_ID(), '_sponsor_url', true );
					?>
                    <p><a href="<?=$external_link;?>"><?=$external_link;?></a></p>
                </div><!-- .entry-content -->
            </article><!-- #post -->	

			<? endwhile;

		endif; ?>
			

		</div><!-- #posts -->
	</div><!-- #posts-container -->
</div><!-- #content-row -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>