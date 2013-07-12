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
wp_enqueue_style("schedule");
wp_enqueue_script("schedule-filter");

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
        	<div class="left">
            	<header class="entry-header">
                	<h1 class="entry-title"><? the_title();?></h1>
                </header>
                <p>Tri-tip corned beef chuck, beef short loin shoulder frankfurter ham. Flank ball tip hamburger, pastrami pancetta turducken fatback brisket doner. Pork loin pastrami ball tip pig. Corned beef doner pork chop strip steak pastrami beef ribs. Ball tip cow jowl flank ground round rump beef ribs tri-tip kielbasa andouille turducken.</p>
                <div class="key">
                    <div>
                    	<label class="listen">Listen</label>
                    	<div>Core sessions</div>
                    </div>
                    <div>
                    	<label class="solve">Solve</label>
                        <div>Interactive conversations</div>
                    </div>
                    <div>
                    	<label class="make">Make</label>
                        <div>Workshops</div>
                    </div>
                </div>
            </div>
            <div class="right">
            	<p><strong>Engage:</strong> with technology, the local community and one another. </p>
                <p><strong>Innovate:</strong> New ideas and approaches to solving challenges and creating compelling stories.</p>
                <p><strong>Inspire:</strong> Motivating conversations with inspiring people reminding us why we do what we do and how to manage it better and grow.</p>
            </div>
            <div class="schedule_nav">
            	<div>
                	<label>Day 1</label>
                    <div class="listen">L</div>
                    <div class="solve">S</div>
                    <div class="make">M</div>
                </div>
                <div>
                	<label>Day 2</label>
                    <div class="listen">L</div>
                    <div class="solve">S</div>
                    <div class="make">M</div>
                </div>
                <div>
                	<label>Day 3</label>
                    <div class="listen">L</div>
                    <div class="solve">S</div>
                    <div class="make">M</div>
                </div>
            </div>
            <div class="entry-content">
            	<? // sched goes here ?>
            </div>
			<?php while ( have_posts() ) : the_post(); ?>
            	<div class="entry-content">
				<? the_content();?>
                </div>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>