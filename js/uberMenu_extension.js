// Extenstion to Uber Menu for ONA13 template

jQuery(document).ready(function($){
		
	// This way we can apply a style to the menu button once clicked
	$( '#megaMenuToggle' ).click( function(){
		$( 'ul.megaMenu' ).toggleClass( 'megaMenuToggleOpen' );
	});
	
	var $flyItems = $("#megaMenu").find( 'ul.megaMenu > li.ss-nav-menu-reg.mega-with-sub, li.ss-nav-menu-reg li.megaReg-with-sub' );
	
	// This overrides the standard uberMenu functionality
	// On mobile views, submenus will not appear and therefore not overlay the menu
	$flyItems.hoverIntent({
		over: function(){	
			if(!$( '#megaMenuToggle' ).is(":visible")){
				uberMenu_openFlyout( this );
			}
		}, 			
		out: function(e){
			if(!$( '#megaMenuToggle' ).is(":visible")){
				if(typeof e === 'object' && $u(e.fromElement).is('#megaMenu form, #megaMenu input, #megaMenu select, #megaMenu textarea, #megaMenu label')){
					return; //Chrome has difficulty with Form element hovers
				}
				uberMenu_close( this );
			}
		},				
		timeout: 200,
		interval: 100,
		sensitivity: 2
	});
	// End closeSubMenu
	//Mobile - iOS
	var deviceAgent = navigator.userAgent.toLowerCase();
	var is_iOS = deviceAgent.match(/(iphone|ipod|ipad)/);
	
	//if (is_iOS) {
	if( jQuery.uber_mobile || is_iOS ){
		var $mobileItems = $("#megaMenu").find( 'ul.megaMenu > li.mega-with-sub' );
		$mobileItems.hover(function(e){
			if(!$( '#megaMenuToggle' ).is(":visible")){
				e.preventDefault();
				$u( this ).find( '.uber-close' ).html( '&times;' ).attr( 'data-uber-status' , 'open' ).show();			
			}
			// If not...
			// Hoping that just leaving this empty will override line 334 in menu js file	
		}, function(e){
			if(!$( '#megaMenuToggle' ).is(":visible")){
				$u( this ).find( '.uber-close' ).hide();
			}
		});	
	}
	

});