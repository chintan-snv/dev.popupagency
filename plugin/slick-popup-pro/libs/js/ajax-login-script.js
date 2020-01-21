jQuery(document).ready(function($) {
	
	// Perform AJAX login on form submit
    jQuery('form#sppro-login').on('submit', function(e){
        
        e.preventDefault();		
		thisForm = jQuery(this); 
		//statusArea = jQuery('form#login .sppro-status');
		statusArea = thisForm.find('.sppro-status');
		
		statusArea.show().html(ajax_login_object.loadingmessage);
		sppro_animate_me(statusArea, 'slideInUp'); 			
		
		username = thisForm.find('.username').val();
		pass = thisForm.find('.password').val(); 
		login_security = jQuery('#login_security').val(); 
		
		if(username=='') {
			statusArea.html('<p class="error">'+ajax_login_object.emptyusername+'</p>');
			sppro_animate_me(statusArea, 'slideInUp'); 			
			return false; 
		}
		
		if(pass=='') {
			statusArea.html('<p class="error">'+ajax_login_object.emptypassword+'</p>');
			sppro_animate_me(statusArea, 'slideInUp'); 			
			return false;
		}
		
		jQuery.post(
			ajax_login_object.ajaxurl,
			{
				'action' : 'sppro_login_function',
				'username': username, 
                'password': pass, 
                'login_security': login_security,
				'_wp_http_referer': jQuery('input[name="_wp_http_referer"]').val(),
			},
			function( response ) {
				response = jQuery.parseJSON(response); 				                
				statusArea.html(response.message);
				if (response.loggedin == true){										
					setTimeout(function() {						
						// Will be used be custom - redirect URL option value from user
						//window.location.replace = response.redirect;
						location.reload(); 
					}, 2000);				
				}
				else {
					sppro_animate_me(statusArea, 'slideInUp');
				}
			}
		);
    });

	// for lost password
	jQuery("form#sppro-lostpassword").submit(function(){
		
		thisForm = jQuery(this); 
		//statusArea = jQuery('form#login .sppro-status');
		statusArea = thisForm.find('.sppro-status');
		statusArea.show().html(ajax_login_object.loadingmessage);
		
		user_name = thisForm.find('.user_name').val();		
		lostpassword_security = thisForm.find('#lostpassword_security').val(); 
		
		if(user_name=='') {
			statusArea.html('<p class="error">'+ajax_login_object.emptyusername+'</p>');
			sppro_animate_me(statusArea, 'slideInUp'); 			
			return false; 
		}
		
		jQuery.post(
			ajax_login_object.ajaxurl,
			{
				'action' : 'lost_pass_callback',
				'user_name': user_name,  
                'lostpassword_security': lostpassword_security,
				'_wp_http_referer': thisForm.find('input[name="_wp_http_referer"]').val(),
			},
			function(response) {
				if(response.success==true) {
					statusArea.removeClass('success').addClass('error'); 
				}
				else {
					statusArea.removeClass('error').addClass('success'); 
				}
				
				statusArea.html(response.data.message);
				sppro_animate_me(statusArea, 'slideInUp');
			}
		);
		
		
		
		return false;
	});

	jQuery(".logout_button").click(function() {
        var logout = confirm('Are you sure?');
        if(logout) {
        	logoutLink = jQuery(this).dataAttr('logoutlink');
        	location.assign(logoutLink);	
        }
    });		
});

function sppro_update_insights(form_id, toupdate='opened', insights=true) {
	
	if(form_id===undefined || form_id=='') return; 
	if(insights=="false") return; 
	
	if(form_id.indexOf('-') > -1) {
		splits = form_id.split("-"); 
		form_id = splits[1]; 
	}
	
	jQuery.post(
		ajax_login_object.ajaxurl,
		{
			'action' : 'sppro_update_insights',
			'toupdate': toupdate,  
			'form_id': form_id,  
		},
		function(response) {
			//console.log('Insights updated for popup '+toupdate+ ' for popupid ' + form_id); 
		}
	);
}

function sppro_animate_me(ele, effect) {
	if(ele===undefined) return false; 
	if(effect===undefined) effect = 'shake'; 
	
	ele.addClass(effect + ' animated'); 
	setTimeout(function() {
		ele.removeClass(effect + ' animated'); 
	}, 10000);
}