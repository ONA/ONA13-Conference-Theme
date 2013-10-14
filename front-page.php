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

	<script type="text/javascript">
        var timeline_config = {
            width:              '100%',
            height:             '600',
            source:             'https://docs.google.com/spreadsheet/pub?key=0AliJph1LBLsOdEtSdTBhd3ltMjY3bHhjNUJrTl9xUlE&output=html',
            embed_id:           'my-timeline',               //OPTIONAL USE A DIFFERENT DIV ID FOR EMBED
            start_at_end:       true,                          //OPTIONAL START AT LATEST DATE
            font:               'Bevan-PotanoSans',             //OPTIONAL FONT
            maptype:            'watercolor',                   //OPTIONAL MAP STYLE
            css:                '<?php echo get_stylesheet_directory_uri();?>/css/timeline.css',     //OPTIONAL PATH TO CSS
            js:                 '<?php echo get_stylesheet_directory_uri();?>/js/timeline-min.js'    //OPTIONAL PATH TO JS
        }
    </script>
    
    <script type="text/javascript" class="rebelmouse-embed-script" src="https://www.rebelmouse.com/static/js-build/embed/embed.js?site=ona13_broadcast_for_all&height=1500&flexible=1&theme=4880"></script>
    
<?php get_footer(); ?>