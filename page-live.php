<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
add_filter('body_class','scheduleClass');
function scheduleClass($classes) {
	// add 'class-name' to the $classes array
	$classes[] = 'full-width';
	// return the $classes array
	return $classes;
}
get_header(); ?>

<style>
.entry-title span {
color:#c00;
font-size:2em;
line-height: 0;
position: relative;
top: 5px;
}
.video {
background:#000;
height:400px;	
margin-bottom:30px;
}
.related h4 {
margin: 15px 0 5px;
}
#content .headlines {
float: left;
width: 32%;
margin-right: 2%;
list-style: none;
}
.headlines.last {
margin-right:0;
}
#content .headlines li {
margin-left:0;
margin-bottom:10px;
}
li.now {
background: #DAD4F0;
padding: 5px 8px;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
border-radius: 8px;
}
li.now .date {
color:#fff;
}
li.now .date:before {
content: "LIVE:";
font-weight:bold;
color:#c00;
padding-right:5px;
}
</style>

	<div id="primary" class="site-content">
		<div id="content" role="main">
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title();?> <span>&bull;</span></h1>
            </header>
            <div class="entry-content">
                <div class="video"></div>   
                   
                <div class="related">
                    <ul class="headlines">
                        <h4 class="widget-title">Thursday sessions</h4>
                 
                
                <?php $args = array(
                    'meta_query' => array (
                        'relation' => 'AND',
                        array (
                          'key' => '_av_content'
                        ),
                        array (
                          'key' => 'start_time'
                        )
                      ),
                    'meta_key' => 'start_time',
                    'orderby' => 'meta_value',
                    'order' => 'ASC',
                    'post_type' => 'ona_session',
                    'posts_per_page' => -1
                    );
                
                $sessions = new WP_Query( $args );
                $lastday = 'Thursday';
                while( $sessions->have_posts() ) {
                    $sessions->the_post(); 
                    $start_timestamp = get_post_meta( get_the_ID(), 'start_time', true );
					$end_timestamp = get_post_meta( get_the_ID(), 'start_time', true );
					$now = time();
					if ($now > $start_timestamp && $now < $end_timestamp) {
						$nowclass = "now";	
					} else {
						$nowclass = '';	
					}
                    if ( date('l', $start_timestamp) != $lastday ) {
                        $lastday =	date('l', $start_timestamp);
                        echo '</ul><ul class="headlines"><h4 class="widget-title">'.$lastday.' sessions</h4>';
                    } ?>
                    <li class="<?php echo $nowclass;?>">
                        <a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?><br/><span class="date"><?php echo date('g:i a', $start_timestamp);?></span></a>
                    </li>
                <?php } ?>
                
                    </ul>
            	</div><!-- .related -->
            </div><!-- .entry-content -->
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>