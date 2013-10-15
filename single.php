<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php if (has_post_thumbnail( $post->ID ) ): 
					$featured_pos = get_post_meta( $post->ID, '_featured_image_position', true );
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
					if ($featured_pos == 'big') { ?>
                <div class="wp-caption">
                    <div id="article_img">
                        <img src="<?php echo $image[0];?>" width="<?php echo $image[1];?>" height="<?php echo $image[2];?>"/>
                    </div>
					<?php if (get_post(get_post_thumbnail_id())->post_content){ ?>
                    <p class="wp-caption-text"><?php echo get_post(get_post_thumbnail_id())->post_content; ?></p><?php } ?>
                </div>
                <?php 	} endif; ?>
                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title();?></h1>
                        <p><span>By <?php the_author();?> / </span>
                        <?php the_time('M j, Y'); ?>
                        </p>
                    </header>
                    <?php $sponsor = get_post_meta( get_the_ID(), '_assigned_sponsor' );
					if ( $sponsor[0] != "") { 
						$sponsor_name = get_the_title($sponsor[0]); 
						$sponsor_link = get_permalink($sponsor[0]);
						$external_link = get_post_meta( $sponsor[0], '_sponsor_url', true );
						$tagline = get_post_meta( $sponsor[0], '_sponsor_tagline', true );?>
                    <div class="sponsor">
                        <?php if ( has_post_thumbnail($sponsor[0]) ) { 
							echo "<a href='".$external_link."'  target='_blank'>".get_the_post_thumbnail($sponsor[0], 'sponsor-banner' )."</a>"; 
						} ?>
                        <div>Sponsor</div>
                        <p>ONA13 is sponsored by <a href="<?php echo $sponsor_link?>"><?php echo $sponsor_name?></a><?php if($tagline){?>, <?php echo $tagline; } ?></p>
                    </div>
                    <?php } ?>
                    <div class="entry-content">
                    	<?php if (isset($image) && ($featured_pos == '' || !isset($featured_pos))) { 
							// Small featured ?>
                        <div class="wp-caption alignright">
                        	<img src="<?php echo $image[0];?>" class="small-featured"/>
                            <?php if (get_post(get_post_thumbnail_id())->post_content){ ?>
                    <p class="wp-caption-text"><?php echo get_post(get_post_thumbnail_id())->post_content; ?></p><?php } ?>
                    	</div>
                        <?php } ?>
                        <?php the_content();?>
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
                        <?php ONA_display_related_by_tag(); ?>
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