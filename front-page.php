<?php
/**
 * The home template for ONA13
 *
 * @package WordPress
 * @subpackage ONA13
 * @since ONA13 1.0
 */
wp_enqueue_style("homepage");

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
	
    	<p>This is the new homepage in the front-page.php template</p>
        
        <!-- Main Info -->
        <div>
        	<div class="lead_text">
            	<p>Big intro here</p>
                <div class="button"></div>
            </div>
            <div class="headlines"></div>
        </div>
        
        <!-- Conference Details -->
        <div>
        	<div class="home_widget"></div>
            <div class="home_widget"></div>
            <div class="home_widget"></div>
        </div>
        
        <!-- Participate -->
        <div>
        	<h3>Participate</h3>
            <div class="home_widget"></div>
            <div class="home_widget"></div>
            <div class="home_widget"></div>
        </div>
        
        <!-- Sponsors -->
        <div>
        	<h3>Sponsors</h3>
        	<div></div>
        </div>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>