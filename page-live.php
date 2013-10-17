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
margin-left: 2%;
list-style: none;
}
#content .headlines.first {
margin-left:0;
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
@media (max-width:700px){
	#content .headlines {
	float:none;
	margin:0;
	width:100%;	
	}
}
</style>

	<div id="primary" class="site-content">
		<div id="content" role="main">
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title();?> <span>&bull;</span></h1>
            </header>
            <div class="entry-content">
                <div class="video">
                	<iframe width="100%" height="400" src="http://cdn.livestream.com/embed/ona13?layout=4&amp;height=400&amp;autoplay=false" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>
                </div>   
                   
                <div class="related">
                    <ul class="headlines first">
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
				$now = time()-14000;
				echo "<!-- Now: ".$now."-->";
                while( $sessions->have_posts() ) {
                    $sessions->the_post(); 
					$av_content = get_post_meta( get_the_ID(), '_av_content', true );
					if ( array_key_exists("video", $av_content) ) {
                    $start_timestamp = get_post_meta( get_the_ID(), 'start_time', true );
					$end_timestamp = get_post_meta( get_the_ID(), 'end_time', true ) + 900;
					echo "<!-- Start: ".$start_timestamp."-->";
					if ($now > $start_timestamp && $now < $end_timestamp) {
						$nowclass = "now";	
					} else {
						$nowclass = '';	
					}
                    if ( date('l', $start_timestamp) != $lastday ) {
                        $lastday =	date('l', $start_timestamp);
                        echo '</ul><ul class="headlines"><h4 class="widget-title">'.$lastday.' sessions</h4>';
                    } ?>
                    <li class="<?php echo $nowclass;?>" data:start="<?php echo $start_timestamp;?>" data:end="<?php echo $end_timestamp;?>">
                        <a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?><br/><span class="date"><?php echo date('g:i a', $start_timestamp);?></span></a>
                    </li>
                <?php } } ?>
                
                    </ul>
            	</div><!-- .related -->
            </div><!-- .entry-content -->
		</div><!-- #content -->
	</div><!-- #primary -->
    
<script>

function checkLive() {
	var currentdate = new Date().getTime();
	currentdate = (currentdate/1000) - 14000;
	jQuery('.headlines li').each(function(index, element) {
		if (jQuery(this).attr("data:start") < currentdate) {
			if (jQuery(this).attr("data:end") > currentdate) {
				if ( !jQuery(this).hasClass("now") ) {
					jQuery(".now").removeClass("now");
					jQuery(this).addClass("now");
				} else {
				}
			}
		}
	});	
}
	
jQuery(function() {	
	timeout = setTimeout('checkLive()', 1000); // one second after load
	timeout = setInterval('checkLive()', 5000); // every minute 60000
});
</script>

<?php get_footer(); ?>