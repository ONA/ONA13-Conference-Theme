<?php

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
        <img src="<?=$instance['icon'];?>" alt="">
      <? } ?>
        <h4><a href="<?=$instance['link'];?>" title="<?=$instance['title']?>"><?=wptexturize($instance['title']);?></a></h4>
        <p><?=wptexturize($instance['text']);?></p>
        <p><a href="<?=$instance['link'];?>">More &rarr;</a></p>
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
      
            <p><label for="<?=$this->get_field_name('title');?>">Title</label>
      <input id="<?=$this->get_field_id('title')?>" name="<?=$this->get_field_name('title');?>" class="widefat" type="text" value="<?=$title;?>" /></p>
      
            <p><label for="<?=$this->get_field_name('link');?>">Link</label>
      <input id="<?=$this->get_field_id('link')?>" name="<?=$this->get_field_name('link');?>" class="widefat" type="text" value="<?=$link;?>" /></p>

			 <p><label for="<?=$this->get_field_name('icon');?>">Icon URL</label>
      <input id="<?=$this->get_field_id('icon')?>" name="<?=$this->get_field_name('icon');?>" class="widefat" type="text" value="<?=$image;?>" /></p>
			
             <p><label for="<?=$this->get_field_name('text');?>">Text</label>
      <textarea id="<?=$this->get_field_id('text')?>" name="<?=$this->get_field_name('text');?>" class="widefat" cols="20" rows="5" type="text"><?=$text;?></textarea></p>
            
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
}

add_action( 'wp_enqueue_scripts', 'ona13_wp_enqueue_scripts' );
function ona13_wp_enqueue_scripts() {
	wp_enqueue_script( 'jquery-isotope', get_stylesheet_directory_uri() . '/js/jquery.isotope.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'uberMenu_extension', get_stylesheet_directory_uri() . '/js/uberMenu_extension.js', array( 'jquery', 'ubermenu' ) );
}

 
wp_register_style("homepage", get_stylesheet_directory_uri()."/css/homepage.css", array("twentytwelve-fonts", "twentytwelve-style"));
wp_register_style("post", get_stylesheet_directory_uri()."/css/post.css", array("twentytwelve-fonts", "twentytwelve-style"));






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

?>