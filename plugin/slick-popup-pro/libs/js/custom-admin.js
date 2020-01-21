function sppro_copyToClipboard(element) {	
	element.preventDefault(); 
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);
	$temp.val(jQuery(element).text()).select();
	document.execCommand("copy");
	$temp.remove();	
}

jQuery(document).ready(function() { // wait for page to finish loading

	//Copy to clipboard for manage popups
	jQuery('.sppro_ctc').click(function() {
    	jQuery(this).focus();
    	jQuery(this).select();
     	document.execCommand('copy');
   	});

	//Delete Popup
	jQuery(".delete-popup").on('click', function(e) {	
		//alert("clicked");	
		e.preventDefault();	
		$btnClicked = jQuery(this);
		popup_delete = confirm('Are you sure you want to delete popup?');
		if(popup_delete) {
		 	jQuery.post(
				ajaxurl,
				{
					post_id : $btnClicked.attr('data-popup_id'),
					action : 'action_delete_sppro_popup',
				},

				function( response ) {
					if( response.success === true ) {
						if(response.data.reason!=undefined) {
							jQuery('.sppro-notice-area').html(response.data.reason);
						}
						if(response.data.reload!=undefined) {
							location.reload(); 
						}

						$btnClicked.closest('.each-popup').remove(); 
					}
					else {
						jQuery('.sppro-notice-area').html(response.data.reason);
					}
				}      
			);
		}		
	});

	//Notice dismissable
	jQuery('.sppro-dismissable').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		$parent = jQuery(this).parent(); 
		$parentBox = jQuery(this).closest('.notice'); 
		
		$parentBox.hide(); 
		
		jQuery.post(
			ajaxurl,
			{
				action : 'sppro_notice_dismissable',
				dataBtn : $btnClicked.attr('data-btn'),
			},
			function( response ) {				
				if( response.success === true ) {					
					
				}
				else {
					
				}				
			} 
		);
	});
	
	//Import page import button
	jQuery('.sppro-btn-importer').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		$parent = jQuery(this).parent(); 
		$parentBox = jQuery(this).closest('.import-box'); 
		$loader = $parent.find('.sp-loader'); 
		$importResult = $parentBox.find('.import-box-result'); 
		
		//$btnClicked.addClass('animate'); 
		$loader.css({'visibility':'visible'}); //slideDown(); 		
		$importResult.html('').removeClass('error').removeClass('success').slideUp(); 
		$btnClicked.addClass('disable');
		jQuery('.sppro-btn-importer').hide();

		jQuery.post(
			ajaxurl,
			{
				action : 'sppro_action_importDemo',
				title : $btnClicked.attr('data-title'),
			},
			function( response ) {				
				if( response.success === true ) {					
					$importResult.addClass('success').html(response.data.reason);
					if(response.data.reload)
						setTimeout(function() {location.reload();}, 1000);					 
				}
				else {
					$importResult.addClass('error').html(response.data.reason);
					if(response.data.reason.indexOf("exists")==0) {
					}						
				}
				$importResult.slideDown();
				$loader.css({'visibility':'hidden'}) //.slideUp(); 
				jQuery('.sppro-btn-importer').show();
			} 
		);
	});
	
	jQuery('.sp-update-license').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		$parentForm = jQuery(this).closest('form'); 
		$purchaseInput = jQuery('#purchase_code');
		$loader = $parentForm.find('.sp-loader'); 
		$importResult = $parentForm.find('.result-area'); 
		
		$purchaseInput.prop('disabled', false); 
		
		//$btnClicked.addClass('animate'); 
		$loader.css({'visibility':'visible'}); //slideDown(); 		
		$importResult.html('').removeClass('error').removeClass('success'); 
		$btnClicked.addClass('disable');
		
		formFields = $parentForm.serialize(); 

		jQuery.post(
			ajaxurl,
			{
				action : 'action_sppro_update_license',
				fields : formFields,
			},
			function( response ) {				
				if( response.success === true ) {					
					$importResult.addClass('notice notice-success').html(response.data.reason);
					if(response.data.reload)
						setTimeout(function() {location.reload();}, 1000);		
				}
				else {
					$importResult.addClass('error').html(response.data.reason);
					if(response.data.reason.indexOf("exists")==0) {
					}						
				}
				$loader.css({'visibility':'hidden'}) //.slideUp(); 
			} 
		);
	});
	
	//Help and Support form submit button
	jQuery('.sp-submit-btn').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		$parentForm = jQuery(this).closest('form'); 
		$loader = $parentForm.find('.sp-loader'); 
		$importResult = $parentForm.find('.result-area'); 
		
		//$btnClicked.addClass('animate'); 
		$loader.css({'visibility':'visible'}); //slideDown(); 		
		$importResult.html('').removeClass('error').removeClass('success').slideUp(); 
		$btnClicked.addClass('disable');
		
		formFields = $parentForm.serialize(); 

		jQuery.post(
			ajaxurl,
			{
				action : 'action_sppro_contact_support',
				fields : formFields,
			},
			function( response ) {				
				if( response.success === true ) {					
					$importResult.addClass('notice notice-success').html(response.data.reason);
					if(response.data.reload)
						setTimeout(function() {location.reload();}, 1000);		

					$parentForm[0].reset(); 
				}
				else {
					$importResult.addClass('error').html(response.data.reason);
					if(response.data.reason.indexOf("exists")==0) {
					}						
				}
				$importResult.slideDown();
				$loader.css({'visibility':'hidden'}) //.slideUp(); 
			} 
		);
	});
	
	//Help and Support Grant and Revoke access button
	jQuery('.sp-ajax-btn').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		ajaxAction = jQuery(this).attr('data-ajax-action'); 
		todo = jQuery(this).attr('data-todo'); 
		
		$parent = jQuery(this).parent(); 
		$loader = $parent.find('.sp-loader'); 
		$importResult = $parent.find('.result-area'); 
		
		//$btnClicked.addClass('animate'); 
		$loader.css({'visibility':'visible'}); //slideDown(); 		
		$importResult.html('').removeClass('error').removeClass('success').slideUp(); 
		$btnClicked.addClass('disable');
		
		if(ajaxAction===undefined)
			ajaxAction = 'sppro_support_access';
		if(todo===undefined)
			todo = 'createuser';
		
		jQuery.post(
			ajaxurl,
			{
				action : ajaxAction,
				todo : todo,
			},
			function( response ) {				
				if( response.success === true ) {					
					$importResult.addClass('notice notice-success').html(response.data.reason);
					if(response.data.reload)
						setTimeout(function() {location.reload();}, 1000);		

					if(todo=='createuser') {
						$btnClicked.html('Revoke Access <i class="fa fa-user"></i>').attr('data-todo','deleteuser');
						jQuery('.sppro-last-granted-time').html(response.data.last_granted); 
					}
					else {
						$btnClicked.html('Grant Access Again <i class="fa fa-user"></i>').attr('data-todo','createuser');
					}
						
				}
				else {
					$importResult.addClass('error').html(response.data.reason);
					if(response.data.reason.indexOf("exists")==0) {
					}						
				}
				$importResult.slideDown();
				$loader.css({'visibility':'hidden'}) //.slideUp(); 
			} 
		);
	});
	
});