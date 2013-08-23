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

	<div id="primary" class="site-content">
		<div id="content" role="main">
        	<div class="left">
            	<header class="entry-header">
                	<h1 class="entry-title"><?php the_title();?></h1>
                </header>
                <p>Welcome to the first edition of our program. For a complete description of <strong>Listen</strong>, <strong>Solve</strong> and <strong>Make</strong> and details on the conference, take a look at our <a href="http://ona13.journalists.org/2013/07/12/join-us-at-ona13-the-town-hall-for-journalism/" target="_blank">blog post</a>. You can organize your view by clicking on  Day 1, 2 or 3 or the L, S, and M buttons at the top of the schedule. Look for more sessions and speakers in the coming weeks and an interactive version will be rolled out in August.</p>
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
                <p><strong>ONA13 Guiding Principles</strong></p>
            	<p><strong>Engage</strong> with technology, the journalism community and each other.</p>
                <p><strong>Innovate</strong> ideas and approaches to challenges and creating compelling stories.</p>
                <p><strong>Inspire</strong> through conversations with dedicated professionals who remind us why we do what we do.</p>
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
            	<?php // sched goes here ?>
            </div>
			<?php while ( have_posts() ) : the_post(); ?>
            	<div class="entry-content">
				<?php the_content();?>
                </div>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>