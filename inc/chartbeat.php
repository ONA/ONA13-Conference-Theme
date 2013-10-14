<?php global $post; 
	$categories = get_the_category($post->ID); 
	$user_info = get_userdata($post->post_author); ?>

<script type='text/javascript'>
    var _sf_async_config={};
    /** CONFIGURATION START **/
    _sf_async_config.uid = 50676;
    _sf_async_config.domain = 'ona13.journalists.org';
    _sf_async_config.useCanonical = true;
    _sf_async_config.sections = '<?php echo $categories[0]->name;?>';
    _sf_async_config.authors = '<?php echo $user_info->user_firstname.' '.$user_info->user_lastname; ?>';
    /** CONFIGURATION END **/
    (function(){
      function loadChartbeat() {
        window._sf_endpt=(new Date()).getTime();
        var e = document.createElement('script');
        e.setAttribute('language', 'javascript');
        e.setAttribute('type', 'text/javascript');
        e.setAttribute('src', '//static.chartbeat.com/js/chartbeat.js');
        document.body.appendChild(e);
      }
      var oldonload = window.onload;
      window.onload = (typeof window.onload != 'function') ?
         loadChartbeat : function() { oldonload(); loadChartbeat(); };
    })();
</script>