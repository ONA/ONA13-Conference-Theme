<?php
/**
 * The home template for ONA13
 *
 * @package WordPress
 * @subpackage ONA13
 * @since ONA13 1.0
 */
get_header(); ?>

	<div id="primary" class="site-content">
	
    	<div id="my-timeline"></div>
    
	</div><!-- #primary -->
<?php 
	if ($_SERVER['SERVER_NAME'] == 'ona13.journalists.org') {
		$timeline_URL = 'https://docs.google.com/spreadsheet/pub?key=0AliJph1LBLsOdGRyb2NiXzBKMGgwcUpSbWFpd1VDWFE&output=html';
	} else {
		$timeline_URL = 'https://docs.google.com/spreadsheet/pub?key=0AliJph1LBLsOdEtSdTBhd3ltMjY3bHhjNUJrTl9xUlE&output=html';
	} ?>
    
	<script type="text/javascript">
        var timeline_config = {
            width:              '100%',
            height:             '550',
            source:             '<?php echo $timeline_URL;?>',
            embed_id:           'my-timeline',               //OPTIONAL USE A DIFFERENT DIV ID FOR EMBED
            start_at_end:       true,                          //OPTIONAL START AT LATEST DATE
            font:               'Bevan-PotanoSans',             //OPTIONAL FONT
            maptype:            'watercolor',                   //OPTIONAL MAP STYLE
            css:                '<?php echo get_stylesheet_directory_uri();?>/css/timeline.css',     //OPTIONAL PATH TO CSS
            js:                 '<?php echo get_stylesheet_directory_uri();?>/js/timeline-min.js'    //OPTIONAL PATH TO JS
        }
    </script>
    
     <!-- Sponsors -->
        <div class="sponsor-row">
            <div class="logos">
                <?php dynamic_sidebar( 'sponsors' ); ?>
            </div>
        </div>
    
    <script type="text/javascript" class="rebelmouse-embed-script" src="https://www.rebelmouse.com/static/js-build/embed/embed.js?site=ona13_front_page&height=1500&flexible=1&theme=4880&show_rebelnav=1"></script>
    
<?php get_footer(); ?>