jQuery(window).load(function(){
	
	jQuery(document).on('change','.mwb_custom_checkbox', function(){
		if(jQuery(this).is(':checked')){
			var pricedata = jQuery(this).attr('data-value');
			if(pricedata == ''){
				// console.log(jQuery(this).parent().siblings('.form-item-value').find('.input-text'));				
				pricedata = jQuery(this).parent().siblings('.form-item-value').find('.input-text').val();				
			}
			// var pricedata = jQuery(this).attr('data-key');
			jQuery(document).find('.form-field > .form-item-value > #_prioterad-pris').val(pricedata);
			jQuery(document).find('.form-field > .mwb-md-checkbox > .mwb_custom_checkbox').not(this).attr('checked', false);
		}
	});

});