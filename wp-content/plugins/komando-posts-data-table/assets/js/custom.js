/*!
 * Posts Data Table
 * Copyright 2016 Barn2 Media Ltd
 */

(function($) {
    
    $(document).ready(function() {

      
	  $( "#acf-field-vl_video_type" ).change(function() {
		if($(this).val() == 'html'){
			$('#titlediv').show();
			$('#postdivrich').show();
			$('#postimagediv').show();
			
		} else {
			$('#titlediv').hide();
			$('#postdivrich').hide();
			$('#postimagediv').hide();
		}
	});
        
    }); // end document.ready
    
})(jQuery);