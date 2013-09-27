jQuery(document).ready(function(){
	
	jQuery('.schedule_nav div div').click(function(e) {
        var button = jQuery(this).index()-1;
		var day = jQuery(this).parent().index();
		jQuery('tr').hide();
		if (button==0){
			jQuery('tr.listen').show();
		} else if (button==1){
			jQuery('tr.solve').show();
		} else if (button==2){
			jQuery('tr.make').show();
		}
		jQuery("html, body").animate({ scrollTop: jQuery('#title'+day).offset().top - 60 }, 1000);
    });
	jQuery('.schedule_nav div label').click(function(e) {
		jQuery('tr').show();
		var day = jQuery(this).parent().index();
		jQuery("html, body").animate({ scrollTop: jQuery('#title'+day).offset().top - 60 }, 1000);
	});
	
});