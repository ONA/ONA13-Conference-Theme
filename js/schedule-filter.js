jQuery(document).ready(function(){

	jQuery('.schedule_nav div div').click(function(e) {
        var button = jQuery(this).index()-1;
		var day = jQuery(this).parent().index();
		jQuery('.single-session').parent().hide();
		if (button==0){
			jQuery('.single-session.Listen').parent().show();
		} else if (button==1){
			jQuery('.single-session.Solve').parent().show();
		} else if (button==2){
			jQuery('.single-session.Make').parent().show();
		}
		jQuery("html, body").animate({ scrollTop: jQuery('#title'+day).offset().top - 60 }, 1000);
    });
	jQuery('.schedule_nav div label').click(function(e) {
		jQuery('tr').show();
		var day = jQuery(this).parent().index();
		jQuery("html, body").animate({ scrollTop: jQuery('#title'+day).offset().top - 60 }, 1000);
	});

});