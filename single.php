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

				<? if (has_post_thumbnail( $post->ID ) ): 
					$featured_pos = get_post_meta( $sponsor[0], '_featured_image_position', true );
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
					if ($featured_pos == 'big') { ?>
                <div id="article_img">
                	<img src="<?=$image[0];?>" width="<?=$image[1];?>" height="<?=$image[2];?>"/>
                </div>
                <? 	} endif; ?>
                <article>
                    <header class="entry-header">
                        <h1 class="entry-title"><? the_title();?></h1>
                        <p><span>By <? the_author();?> / </span>
                        <? the_time('M d, Y'); ?>
                        </p>
                    </header>
                    <? $sponsor = get_post_meta( get_the_ID(), '_assigned_sponsor' );
					if ( $sponsor[0] != "") { 
						$sponsor_name = get_the_title($sponsor[0]); 
						$sponsor_link = get_permalink($sponsor[0]);
						$external_link = get_post_meta( $sponsor[0], '_sponsor_url', true );
						$tagline = get_post_meta( $sponsor[0], '_sponsor_tagline', true );?>
                    <div class="sponsor">
                        <? if ( has_post_thumbnail($sponsor[0]) ) { 
							echo "<a href='".$external_link."'>".get_the_post_thumbnail($sponsor[0], 'sponsor-banner' )."</a>"; 
						} ?>
                        <div>Sponsor</div>
                        <p>ONA13 is sponsored by <a href="<?=$sponsor_link?>"><?=$sponsor_name?></a><? if($tagline){?>, <? echo $tagline; } ?></p>
                    </div>
                    <? } ?>
                    <div class="entry-content">
                    	<? if (isset($image) && ($featured_pos == '' || !isset($featured_pos))) { 
							// Small featured ?>
                        	<img src="<?=$image[0];?>" class="small-featured"/>
                        <? } ?>
                        <? the_content();?>
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