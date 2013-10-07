<?php

require_once dirname( __FILE__ ) . '/inc/class-ona-admin.php';
require_once dirname( __FILE__ ) . '/inc/class-ona-session.php';
require_once dirname( __FILE__ ) . '/inc/class-ona-speaker.php';
require_once dirname( __FILE__ ) . '/inc/class-ona13-importer.php';
require_once dirname( __FILE__ ) . '/inc/post-types.php';
require_once dirname( __FILE__ ) . '/inc/taxonomies.php';

if ( defined( 'WP_CLI' ) && WP_CLI )
	require_once dirname( __FILE__ ) . '/inc/class-ona13-cli-command.php';



/* ADMIN extension */

if( is_admin() ) {
	add_action( 'add_meta_boxes', 'featured_sponsor_metabox' );
	add_action( 'save_post', 'featured_sponsor_save' );
	add_action( 'add_meta_boxes', 'sponsor_extras_metabox' );
	add_action( 'save_post', 'sponsor_extras_save' );
	add_action( 'add_meta_boxes', 'post_featured_image_position_metabox' );
	add_action( 'save_post', 'post_featured_image_position_save' );
	
	/* Adds "FEATURED SPONSOR" box to posts and sessions */
	function featured_sponsor_metabox() {
		$screens = array( 'post', 'ona_session' );
		remove_meta_box('formatdiv', 'post', 'side');
		foreach ($screens as $screen) {
			add_meta_box( 'sponsor_metabox', __( 'Featured Sponsor', 'myplugin_textdomain' ),
				'featured_sponsor_print', $screen, 'side'
			);
		}
	}
	
	/* Prints "FEATURED SPONSOR" box */
	function featured_sponsor_print( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		$value = get_post_meta( $post->ID, '_assigned_sponsor', true );
		echo '<label for="post_sponsor">';
		   _e("Select a sponsor:", 'myplugin_textdomain' );
		echo '</label> ';
		echo '<select id="post_sponsor" name="post_sponsor" style="max-width: 100%;">';
		echo '<option value="">None</option>';
		$args = array( 'post_type' => 'sponsors', 'posts_per_page' => -1 );
		$recent_posts = wp_get_recent_posts( $args );
		foreach( $recent_posts as $recent ){
			$selected = "";
			if (esc_attr($value) == $recent["ID"]){
				$selected = ' selected="selected"';	
			}
			echo '<option value="'.$recent["ID"].'"'.$selected.'>'.$recent["post_title"].'</option>';
		}
		echo '</select>';
	}
	
	/* Saves "FEATURED SPONSOR" content */
	function featured_sponsor_save( $post_id ) {
		if ( 'page' == $_REQUEST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
		}
		if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
		  return;
		$post_ID = $_POST['post_ID'];
		$mydata = sanitize_text_field( $_POST['post_sponsor'] );
		add_post_meta($post_ID, '_assigned_sponsor', $mydata, true) or
		update_post_meta($post_ID, '_assigned_sponsor', $mydata);
	}
	
	/* ----------------------------------------------- */
	/* Adds "FEATURED IMAGE POSITION" box to posts and sessions */
	function post_featured_image_position_metabox() {
		$screens = array( 'post' );
		foreach ($screens as $screen) {
			add_meta_box( 'featured_image_position_metabox', __( 'Position Featured Image', 'myplugin_textdomain' ),
				'post_featured_image_position_print', $screen, 'side'
			);
		}
	}
	
	/* Prints "FEATURED IMAGE POSITION" box */
	function post_featured_image_position_print( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		$value = get_post_meta( $post->ID, '_featured_image_position', true );
		echo '<label for="featured_image_position">';
		   _e("Position Featured Image:", 'myplugin_textdomain' );
		echo '</label> ';
		echo '<select id="featured_image_position" name="featured_image_position" style="width: 100%;">';
			echo '<option value="">Normal, within body</option>';
			echo '<option value="big">Big, above headline</option>';
		echo '</select>';
	}
	
	/* Saves "FEATURED IMAGE POSITION" content */
	function post_featured_image_position_save( $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
		if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
		  return;
		$post_ID = $_POST['post_ID'];
		$mydata = sanitize_text_field( $_POST['featured_image_position'] );
		add_post_meta($post_ID, '_featured_image_position', $mydata, true) or
		update_post_meta($post_ID, '_featured_image_position', $mydata);
	}
	
	/* ----------------------------------------------- */
	/* Adds "SPONSOR EXTRAS" box to posts and sessions */
	function sponsor_extras_metabox() {
		$screens = array( 'sponsors' );
		foreach ($screens as $screen) {
			add_meta_box( 'sponsor_extras', __( 'Sponsor Metadata', 'myplugin_textdomain' ),
				'sponsor_extras_print', $screen
			);
		}
	}
	
	/* Prints "SPONSOR EXTRAS" box */
	function sponsor_extras_print( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
		$url = get_post_meta( $post->ID, '_sponsor_url', true );
		$tagline = get_post_meta( $post->ID, '_sponsor_tagline', true );
		$level = get_post_meta( $post->ID, '_sponsor_level', true );
		// URL
		echo '<label for="sponsor_url">';
		   _e("Sponsor's external URL:", 'myplugin_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="sponsor_url" name="sponsor_url" style="width: 100%;" value="'.$url.'"/>
			<br /><br />';
		// URL
		echo '<label for="sponsor_tagline">';
		   _e("Sponsor tagline:", 'myplugin_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="sponsor_tagline" name="sponsor_tagline" style="width: 100%;" value="'.$tagline.'"/>
			<br /><br />';
		// Sponsor Level ?>
		<label for="sponsor_level">Select sponsor level:</label>
		<select id="sponsor_level" name="sponsor_level" style="max-width: 100%;">
			<option value="Exhibitors"<?php if($level=="Exhibitors"){?> selected="selected"<?php }?>>Exhibitors</option>
			<option value="ONAngel"<?php if($level=="ONAngel"){?> selected="selected"<?php }?>>ONAngel</option>
            <option value="Diamond"<?php if($level=="Diamond"){?> selected="selected"<?php }?>>Diamond</option>
            <option value="Platinum"<?php if($level=="Platinum"){?> selected="selected"<?php }?>>Platinum</option>
            <option value="Gold"<?php if($level=="Gold"){?> selected="selected"<?php }?>>Gold</option>
            <option value="Silver"<?php if($level=="Silver"){?> selected="selected"<?php }?>>Silver</option>
            <option value="Bronze"<?php if($level=="Bronze"){?> selected="selected"<?php }?>>Bronze</option>
            <option value="Supporters"<?php if($level=="Supporters"){?> selected="selected"<?php }?>>Supporters</option>
            <option value="Midway Sponsor"<?php if($level=="Midway Sponsor"){?> selected="selected"<?php }?>>Midway Sponsor</option>
            <option value="Midway Participant"<?php if($level=="Midway Participant"){?> selected="selected"<?php }?>>Midway Participant</option>
            <option value="Digital Sponsor"<?php if($level=="Digital Sponsor"){?> selected="selected"<?php }?>>Digital Sponsor</option>
            
            
		</select>
	<?php }
	
	/* Saves "SPONSOR EXTRAS" content */
	function sponsor_extras_save( $post_id ) {
		if ( 'page' == $_REQUEST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
		}
		if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
		  return;
		$post_ID = $_POST['post_ID'];
		$url = sanitize_text_field( $_POST['sponsor_url'] );
		$tagline = sanitize_text_field( $_POST['sponsor_tagline'] );
		$level = sanitize_text_field( $_POST['sponsor_level'] );
		add_post_meta($post_ID, '_sponsor_url', $url, true) or
			update_post_meta($post_ID, '_sponsor_url', $url);
		add_post_meta($post_ID, '_sponsor_tagline', $tagline, true) or
			update_post_meta($post_ID, '_sponsor_tagline', $tagline);
		add_post_meta($post_ID, '_sponsor_level', $level, true) or
			update_post_meta($post_ID, '_sponsor_level', $level);
	}
}
/* End ADMIN */

/* Custom SPONSOR post */
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'Sponsors',
		array(
			'labels' => array(
				'name' => __( 'Sponsors' ),
				'singular_name' => __( 'Sponsor' )
			),
		'public' => true,
		'has_archive' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' )
		)
	);
}
/* End SPONSORS */

/* Custom thumbnail sizes for sponsors */
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
    // set_post_thumbnail_size( 150, 150 ); // default Post Thumbnail dimensions   
}
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'sponsor-banner', 200, 25 ); //max 200 pixels wide, 25 tall
	add_image_size( 'sponsor-shoulder', 120, 200 ); //max 120 pixels wide, 200 tall
	add_image_size( 'sponsor-row', 140, 140 ); //max 120 pixels wide, 250 tall
}


add_filter( 'body_class', 'ona13_body_class' );
function ona13_body_class( $classes ) {
	if ( is_page_template( 'page-templates/student-newsroom-page.php' ) ) {
		$classes[] = 'template-student-newsroom-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-4' ) && is_active_sidebar( 'sidebar-5' ) )
			$classes[] = 'two-sidebars';
	}
	return $classes;
}

class Home_Card extends WP_Widget {
 /* Declares the Featured_Sidebar_Widget class */
    function Home_Card(){
		$widget_ops = array('description' => 'Widget for homepage.' );
    	//$control_ops = array('width' => 300, 'height' => 300);
    	$this->WP_Widget('Home_Card','Home Card', $widget_ops);
		$this->widget_count = 1;
    }

  /* Displays the Widget */
    function widget($args, $instance){
      global $widget_count;
	  extract($args);
      echo preg_replace('/widget_count/i', 'position-'.$this->widget_count, $before_widget);	      if ($instance['icon']) { ?>
        <img src="<?php echo $instance['icon'];?>" alt="">
      <?php } ?>
        <h4><a href="<?php echo $instance['link'];?>" title="<?php echo $instance['title']?>"><?php echo wptexturize($instance['title']);?></a></h4>
        <p><?php echo wptexturize($instance['text']);?></p>
        <p><a href="<?php echo $instance['link'];?>">More &rarr;</a></p>
      <?php
      echo $after_widget;
	  $this->widget_count++;
  }

  /* Saves the widgets settings */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['link'] = strip_tags(stripslashes($new_instance['link']));
		$instance['icon'] = strip_tags(stripslashes($new_instance['icon']));
		$instance['text'] = strip_tags(stripslashes($new_instance['text']));		
		return $instance;
	}

  /* Creates the edit form for the widget */
    function form($instance){
      $instance = wp_parse_args( (array) $instance, array('title'=>'', 'link'=>'', 'icon'=>'', 'text'=>'') );
	  $title = htmlspecialchars($instance['title']);
      $link = htmlspecialchars($instance['link']);
	  $image = htmlspecialchars($instance['icon']);
	  $text = htmlspecialchars($instance['text']);	  
	  ?>
      
            <p><label for="<?php echo $this->get_field_name('title');?>">Title</label>
      <input id="<?php echo $this->get_field_id('title')?>" name="<?php echo $this->get_field_name('title');?>" class="widefat" type="text" value="<?php echo $title;?>" /></p>
      
            <p><label for="<?php echo $this->get_field_name('link');?>">Link</label>
      <input id="<?php echo $this->get_field_id('link')?>" name="<?php echo $this->get_field_name('link');?>" class="widefat" type="text" value="<?php echo $link;?>" /></p>

			 <p><label for="<?php echo $this->get_field_name('icon');?>">Icon URL</label>
      <input id="<?php echo $this->get_field_id('icon')?>" name="<?php echo $this->get_field_name('icon');?>" class="widefat" type="text" value="<?php echo $image;?>" /></p>
			
             <p><label for="<?php echo $this->get_field_name('text');?>">Text</label>
      <textarea id="<?php echo $this->get_field_id('text')?>" name="<?php echo $this->get_field_name('text');?>" class="widefat" cols="20" rows="5" type="text"><?php echo $text;?></textarea></p>
            
	  <?php
  }

}// END class

/* Sponsor widget to display logo */
class Sponsor_Logo extends WP_Widget {
 /* Declares the Featured_Sidebar_Widget class */
    function Sponsor_Logo(){
		$widget_ops = array('description' => 'Widget for rows of logos.' );
    	$this->WP_Widget('Sponsor_Logo','Sponsor Logo', $widget_ops);
		$this->widget_count = 1;
    }

  /* Displays the Widget */
    function widget($args, $instance){
      global $widget_count;
	  extract($args);
      echo preg_replace('/widget_count/i', 'position-'.$this->widget_count, $before_widget);
	  if ($instance['sponsor'] != '') {
		  $sponsor_link = get_post_meta( $instance['sponsor'], '_sponsor_url', true );?>
		  <a href="<?php echo $sponsor_link?>" target="_blank">
		  <?php if ( has_post_thumbnail($instance['sponsor']) ) { 
				echo get_the_post_thumbnail($instance['sponsor'], 'sponsor-row' ); 
			} ?>
		  </a>
      <?php
	  } else {
		echo '<div class="more">Your logo here</div>';  
	  }
      echo $after_widget;
	  $this->widget_count++;
  }

  /* Saves the widgets settings */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['sponsor'] = strip_tags(stripslashes($new_instance['sponsor']));	
		return $instance;
	}

  /* Creates the edit form for the widget */
    function form($instance){
      $instance = wp_parse_args( (array) $instance, array('sponsor'=>'') );
	  $sponsor = $instance['sponsor']; ?>
            <select id="<?php echo $this->get_field_id('sponsor')?>" name="<?php echo $this->get_field_name('sponsor')?>" class="widefat">
				<option value="">None</option>
	<?	$args = array( 'post_type' => 'sponsors', 'posts_per_page' => -1 );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) { $loop->the_post();
			$selected = "";
			if ($sponsor == get_the_ID()){
				$selected = ' selected="selected"';	
			}
			echo '<option value="'.get_the_ID().'"'.$selected.'>'.get_the_title().'</option>';
		} ?>
			</select>
	  <?php
  }

}// END class

/* ---------------------------------- */
/* Create and remove sidebars/widgets */

add_action( 'widgets_init', 'ona13_widgets_init', 11);

function ona13_widgets_init() {
	
	register_sidebar( array(
		'name' => __( 'Student Newsroom (1)', 'twentytwelve' ),
		'id' => 'sidebar-4',
		'description' => __( 'Appears when using the optional Student Newsroom Page template', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Student Newsroom (2)', 'twentytwelve' ),
		'id' => 'sidebar-5',
		'description' => __( 'Appears when using the optional Student Newsroom Page template', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Home - About Conference', 'twentytwelve' ),
		'id' => 'row1',
		'description' => __( 'First row of three sections on homepage', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="home_widget %2$s">',
		'after_widget' => '</div>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Home - Participate', 'twentytwelve' ),
		'id' => 'row2',
		'description' => __( 'Second row of three sections on homepage', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="home_widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="home-widget-title">',
		'after_title' => '</h4>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Sponsors - Home', 'twentytwelve' ),
		'id' => 'sponsors',
		'description' => __( 'List of sponsor logos for the bottom of the home page', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="sponsor_widget %2$s">',
		'after_widget' => '</div>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Sponsors - Day 1', 'twentytwelve' ),
		'id' => 'sponsors0',
		'description' => __( 'List of sponsor logos for Day 1 on session page', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="sponsor_widget %2$s">',
		'after_widget' => '</div>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Sponsors - Day 2', 'twentytwelve' ),
		'id' => 'sponsors1',
		'description' => __( 'List of sponsor logos for Day 2 on session page', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="sponsor_widget %2$s">',
		'after_widget' => '</div>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Sponsors - Day 3', 'twentytwelve' ),
		'id' => 'sponsors2',
		'description' => __( 'List of sponsor logos for Day 3 on session page', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="sponsor_widget %2$s">',
		'after_widget' => '</div>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Midway', 'twentytwelve' ),
		'id' => 'midway',
		'description' => __( 'Content tiles on midway page', 'twentytwelve' ),
		'before_widget' => '<div id="%1$s" class="home_widget %2$s">',
		'after_widget' => '</div>'
	) );
	
	unregister_sidebar( "sidebar-2" );
	unregister_sidebar( "sidebar-3" );
	unregister_widget( "WP_Widget_Calendar" );
	unregister_widget( "WP_Widget_RSS" );
	unregister_widget( "WP_Widget_Tag_Cloud" );
	unregister_widget( "WP_Widget_Meta" );
	unregister_widget( "WP_Widget_Search" );
	unregister_widget( "WP_Widget_Recent_Comments" );
	unregister_widget( "WP_Widget_Archives" );
	unregister_widget( "WP_Widget_Recent_Posts" );
	
	register_widget('Home_Card');
	register_widget('Sponsor_Logo');
}

/* -------------------------------- */
/* Scripts and Styles declared here */

function ona13_wp_enqueue_scripts() {
	wp_register_style("homepage", get_stylesheet_directory_uri()."/css/homepage.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("home-widget", get_stylesheet_directory_uri()."/css/home-widget.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("post", get_stylesheet_directory_uri()."/css/post.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("category", get_stylesheet_directory_uri()."/css/category.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("schedule", get_stylesheet_directory_uri()."/css/schedule.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("sponsor", get_stylesheet_directory_uri()."/css/sponsor.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("session", get_stylesheet_directory_uri()."/css/session.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("midway", get_stylesheet_directory_uri()."/css/midway.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("session_archive", get_stylesheet_directory_uri()."/css/session_archive.css", array("twentytwelve-fonts", "twentytwelve-style"));
	wp_register_style("speaker_archive", get_stylesheet_directory_uri()."/css/speaker_archive.css", array("twentytwelve-fonts", "twentytwelve-style", "category"));
	
	wp_register_script("schedule-filter", get_stylesheet_directory_uri()."/js/schedule-filter.js", array("jquery"));
	wp_register_script("session-filter", get_stylesheet_directory_uri()."/js/session-filter.js", array("jquery"));
	
	if( is_front_page() ) {
		wp_enqueue_style("homepage");
		wp_enqueue_style("home-widget");
	} else if( is_single() ) {
		wp_enqueue_style("post");
		if (get_post_type() == 'ona_session' || get_post_type() == 'ona_speaker'){
			wp_enqueue_style("session");
		} else if (get_post_type() == 'sponsors'){
			wp_enqueue_style("sponsor");
		}
	} else if( is_post_type_archive('ona_speaker') ) {
		wp_enqueue_style("speaker_archive");
	} else if( is_post_type_archive('ona_session') ) {
		wp_enqueue_style("session_archive");
		wp_enqueue_script("session-filter");
	} else if( is_category() || is_archive() ) {
		wp_enqueue_style("category");
	} else if( is_page('schedule') ) { // Soon to be deprecated
		wp_enqueue_style("schedule");
		wp_enqueue_script("schedule-filter");
	} else if( is_page('midway') ) {
		wp_enqueue_style("midway");
		wp_enqueue_style("home-widget");
	}
		
	wp_enqueue_script( 'jquery-isotope', get_stylesheet_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'uberMenu_extension', get_stylesheet_directory_uri() . '/js/uberMenu_extension.js', array( 'jquery', 'ubermenu' ) );
}
add_action( 'wp_enqueue_scripts', 'ona13_wp_enqueue_scripts' );

/* Function to display related content based on tag */

function ONA_display_related_by_tag(){
	global $post;
	$tags = wp_get_post_tags($post->ID);
	if ($tags) {
		echo '<div class="related">';
		$tag_ids = array();
		$tagged = "Other content related to ";
		foreach($tags as $each_tag) {
			$tag_ids[] = $each_tag->term_id;
			$tagged .= '"<a href="'.get_site_url().'/tag/'.$each_tag->slug.'/">'.$each_tag->name.'</a>," ';
		}
		$tagged = substr($tagged, 0, -3);
		echo "<div class='topics'>".$tagged.'":</div>';
		// Two queries
		$queries = array('post' => 'Posts', 'ona_session' => 'Sessions');
		// Let's get posts
		foreach($queries as $key => $value){
			$args = array(
				'post_type' => $key,
				'tag__in' => $tag_ids,
				'post__not_in' => array($post->ID), 
				'orderby'=> 'date', 
				'showposts' => 5,
				'ignore_sticky_posts' => 1 
			);	 
			$query = new WP_Query($args);
			echo '<ul class="headlines">';
			if( $query->have_posts() ) {
				echo '<h4 class="widget-title">Related '.$value.'</h4>';		 
				while ($query->have_posts()) {
					$query->the_post(); ?>
					<li><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" ><?php the_title(); ?><br/><span class="date"><?php the_time('M d, Y'); ?></span></a></li>
				<?php }
			} else {
				echo '<h4 class="widget-title">No related '.$value.'</h4>';
			}
			echo '</ul>';
			wp_reset_query();
		}
		echo '</div>';
	}
}
	



/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */
/* --------------------------------------------------------------------- */

/*  
 * Need to change UberMenuWalker to UberMenuONA13 in ubermenu.php
 * This overrides the walker in UberMenuWalker.class.php
 */

class UberMenuONA13 extends UberMenuWalker{

	private $index = 0;
	private $menuItemOptions;
	private $noUberOps;
	
	function start_el( &$output, $item, $depth, $args ){

		global $uberMenu;
		$settings = $uberMenu->getSettings();
		
		//Test override settings
		$override = $this->getUberOption( $item->ID, 'shortcode' );
		$overrideOn = $settings->op( 'wpmega-shortcodes' ) && !empty( $override ) ? true : false;
		
		//Test sidebar settings
		$sidebar = $this->getUberOption( $item->ID, 'sidebars' );
		$sidebarOn = ( $settings->op( 'wpmega-top-level-widgets' ) || $depth > 0 ) && $settings->op( 'wpmega-sidebars' ) && !empty( $sidebar ) ? true : false;
		
		//For --Divides-- with no Content
		if( ( $item->title == '' || $item->title == UBERMENU_SKIP ) && !$overrideOn  && !$sidebarOn ){ 
			if( $item->title == UBERMENU_SKIP ) $output.= '<li id="menu-item-'. $item->ID.'" class="wpmega-divider-container">'.UBERMENU_DIVIDER; //.'</li>'; 
			return; 
		}	//perhaps the filter should be called here
				  
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		//Handle class names depending on menu item settings
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		//The Basics
		if( $depth == 0 ) $classes[] = 'ss-nav-menu-item-'.$this->index++;
		$classes[] = 'ss-nav-menu-item-depth-'.$depth;
		   
		//Megafy (top level)
		if( $depth == 0 && $this->getUberOption( $item->ID, 'isMega' ) != 'off' ){
			$classes[] = 'ss-nav-menu-mega';
			
			//Full Width Submenus
			if( $this->getUberOption( $item->ID, 'fullWidth' ) == 'on' ){
				$classes[] = 'ss-nav-menu-mega-fullWidth';
				
				//Menu Item Columns
				$numCols = $this->getUberOption( $item->ID, 'numCols' );
				if( is_numeric( $numCols ) && $numCols <= 7 && $numCols > 0 ){
					$classes[] = 'mega-colgroup mega-colgroup-'.$numCols;
				}
			}
			
			//Submenu Alignment
			$alignment = $this->getUberOption( $item->ID, 'alignSubmenu' );	//center, right, left
			if( empty( $alignment ) ) $alignment = 'center';
			$classes[] = 'ss-nav-menu-mega-align'.ucfirst( $alignment );

		}
		else if($depth == 0) $classes[] = 'ss-nav-menu-reg';
		
		//Right Align
		if( $depth == 0 && $this->getUberOption( $item->ID , 'floatRight' ) == 'on' ) $classes[] = 'ss-nav-menu-mega-floatRight';
				
		//Second Level - Vertical Division
		if($depth == 1){
			if( $this->getUberOption( $item->ID, 'verticaldivision' ) == 'on' ) $classes[] = 'ss-nav-menu-verticaldivision';
		}
		
		//Third Level
		if($depth >= 2){
			if( $this->getUberOption( $item->ID, 'isheader' ) == 'on' ) $classes[] = 'ss-nav-menu-header';			//Headers
			if( $this->getUberOption( $item->ID, 'newcol' ) == 'on' ){												//New Columns
				$output.= '</ul></li>';
				$output.= '<li class="menu-item ss-nav-menu-item-depth-'.($depth-1).' sub-menu-newcol">'.
							'<span class="um-anchoremulator">&nbsp;</span><ul class="sub-menu sub-menu-'.$depth.'">';
			}
		}
		
		//Highlight
		if( $this->getUberOption( $item->ID, 'highlight' ) == 'on' ) $classes[] = 'ss-nav-menu-highlight';		//Highlights
		
		//Thumbnail
		$thumb = $uberMenu->getImage( $item->ID, $settings->op( 'wpmega-image-width' ), $settings->op( 'wpmega-image-height' ) );
		if( !empty( $thumb ) ) $classes[] = 'ss-nav-menu-with-img';
		
		//NoText, NoLink		
		$notext = $this->getUberOption( $item->ID, 'notext' ) == 'on' || $item->title == UBERMENU_NOTEXT ? true : false;
		$nolink = $this->getUberOption( $item->ID, 'nolink' ) == 'on' ? true : false;
		
		if( $notext ) $classes[] = 'ss-nav-menu-notext';
		if( $nolink ) $classes[] = 'ss-nav-menu-nolink';
		
		if( $sidebarOn  ) $classes[] = 'ss-sidebar';
		if( $overrideOn ) $classes[] = 'ss-override';
		
		$prepend = '<span class="wpmega-link-title">';
		$append = '</span>';
		$description  = ! empty( $item->description ) ? '<span class="wpmega-item-description">'.esc_attr( $item->description ).'</span>' : '';
		
		if(	(	$depth == 0		&& 	!$settings->op( 'wpmega-description-0' ) )	||
			(	$depth == 1		&& 	!$settings->op( 'wpmega-description-1' ) )	||
			(	$depth >= 2		&& 	!$settings->op( 'wpmega-description-2' ) )  ){
			$description = '';
		}
		
		if( !empty( $description ) ) $classes[] = 'ss-nav-menu-with-desc';
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$output .= /*$indent . */'<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		//$attributes .= ! empty( $item->class )      ? ' class="'  . esc_attr( $item->class      ) .'"' : '';

		
		
		$item_output = '';
		
		/* Add title and normal link content - skip altogether if nolink and notext are both checked */
		if( !empty( $item->title ) && trim( $item->title ) != '' && !( $nolink && $notext ) ){
			
			//Determine the title
			$title = apply_filters( 'the_title', $item->title, $item->ID );
			if( $item->title == UBERMENU_NOTEXT || $notext ) $title = $prepend = $append = '';
			//Horizontal Divider automatically skips the link
			if( $item->title == UBERMENU_SKIP ){
				$item_output.= UBERMENU_DIVIDER;
			}
			//A normal link or link emulator
			else{
				$item_output = $args->before;
				//To link or not to link?
			//	if( $nolink )  $item_output.= '<span class="um-anchoremulator" >';
			//	else $item_output.= '<a'. $attributes .' test="test">';
				
				if( $nolink )  $item_output.= '<a'. $attributes .' class="hasDrop um-anchoremulator">';
				else $item_output.= '<a'. $attributes .'>';
					//Prepend Thumbnail
					$item_output.= $thumb;
					//Link Before (not added by UberMenu)
					if( !$nolink ) $item_output.= $args->link_before;
						//Text - Title
						if( !$notext ) $item_output.= $prepend . $title . $append;
						//Description
						$item_output.= $description;
					//Link After (not added by UberMenu)
					if( !$nolink ) $item_output.= $args->link_after;
				//Close Link or emulator
			//	if( $nolink ) $item_output.= '</span>'; 
			//	else $item_output.= '</a>';
				$item_output.= '</a>';
				
				//Append after Link (not added by UberMenu)
				$item_output .= $args->after;
			}
		}
		/* Add overrides and widget areas */
		if( $overrideOn || $sidebarOn ){
			$class = 'wpmega-nonlink';
			
			//Get the widget area or shortcode
			$gooeyCenter = '';
			//Content Overrides
			if( $overrideOn ){
				$gooeyCenter = do_shortcode( $override );
			}
			//Widget Areas
			if( $sidebarOn ){
				$class.= ' wpmega-widgetarea ss-colgroup-'.$uberMenu->sidebarCount( $sidebar );	
				$gooeyCenter = $uberMenu->sidebar( $sidebar );
			}
			
			$item_output.= '<div class="'.$class.' uberClearfix">';
			$item_output.= $gooeyCenter;
			//$item_output.= '<div class="clear"></div>';
			$item_output.= '</div>';
		}
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		
	}
	function end_el(&$output, $item, $depth) {
		$output .= "</li>";
	}
}

/* Allows for shorter excerpts when this function is used */
function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}

/* Overriding this pagination function because it seems backwards */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous alignleft"><?php previous_posts_link( __( '<span class="meta-nav">&larr;</span> Newer posts', 'twentytwelve' ) ); ?></div>
            <div class="nav-next alignright"><?php next_posts_link( __( 'Older posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}

add_filter('pre_get_posts', 'change_post_type');
function change_post_type($myquery) {
  if(is_tag()) {
	if ( ! $myquery->is_main_query() )
      return $myquery;
    $post_type = array('post','ona_session'); // replace cpt to your custom post type
    $myquery->set('post_type',$post_type);
	return $myquery;
  } else if( is_post_type_archive('ona_speaker') ) {
	if ( ! $myquery->is_main_query() )
      return $myquery;
    $myquery->set('orderby','title');
	$myquery->set('order',"ASC");
	$myquery->set('posts_per_page',-1);
	return $myquery;
  }
}

?>