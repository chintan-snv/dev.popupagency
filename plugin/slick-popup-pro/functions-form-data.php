<?php
/**
 * sppro_get_form_data
 * @param  none
 * @return $data - array with all form properties
 */
function sppro_get_form_data($id) {
	global $sp_opts;	
	if( is_user_logged_in() ) {
		if (current_user_can('manage_options')) {
			$user_is_admin = true;
		}	
	}
	$data = array();
	$data['choose_layout'] = $sp_opts['choose-layout'];		
	$data['fixed_side'] = (isset($sp_opts['fixed-corner']) AND !empty($sp_opts['fixed-corner']) ) ? $sp_opts['fixed-corner'] : 'corner_left';
	$data['widgetized_popup'] = (isset($sp_opts['widgetized-popup']) AND !empty($sp_opts['widgetized-popup']) ) ? $sp_opts['widgetized-popup'] : 'corner_left';
	$data['color_scheme'] = $sp_opts['choose-color-scheme'];
	$data['custom_color_scheme'] = $sp_opts['custom-theme-color'];		
	$data['custom_text_color'] = $sp_opts['custom-text-color'];		
	
	$data['autohidemode'] = isset($sp_opts['autohidemode']) ? $sp_opts['autohidemode'] : 'hidden';
	$data['cursorcolor'] = isset($sp_opts['cursorcolor']) ? $sp_opts['cursorcolor'] : '#757575';
	$data['cursorbordercolor'] = isset($sp_opts['cursorbordercolor']) ? $sp_opts['cursorbordercolor'] : '#333';
	$data['cursoropacitymax'] = isset($sp_opts['cursoropacitymax']) ? $sp_opts['cursoropacitymax'] : 1;
	$data['cursorwidth'] = isset($sp_opts['cursorwidth']) ? $sp_opts['cursorwidth'] : 10;
	$data['cursorbackground'] = isset($sp_opts['cursorbackground']) ? $sp_opts['cursorbackground'] : '#aaa';	
	$data['cursorborderradius'] = isset($sp_opts['cursorborderradius']) ? $sp_opts['cursorborderradius'] : '#aaa';	
	
	$data['popup_heading'] = $sp_opts['popup-heading'];		
	$data['cta_text'] = $sp_opts['popup-cta-text'];			
	
	$data['side_button_scheme'] = $sp_opts['choose-side-button'];
	$data['submit_button_scheme'] = $sp_opts['choose-submit-button'];
	
	$data['side_button_text'] = !empty($sp_opts['side-button-text']) ? $sp_opts['side-button-text'] : 'Contact Us';
	$data['side_button_position'] = isset($sp_opts['side-button-position']) ? $sp_opts['side-button-position'] : 'left';
	$data['side_button_switch'] = $sp_opts['plugin_state_on_mobile'] ? 'enabled_on_mobile' : 'disabled_on_mobile';
	
	$data['activation_mode'] = array();
	$data['activation_mode']['mode'] = $sp_opts['activation_mode'] ? $sp_opts['activation_mode'] : 'manually';
	$data['activation_mode']['cookie_delay'] = isset($sp_opts['cookie-delay']) ? $sp_opts['cookie-delay'] : '1';
	$data['activation_mode']['cookie_days'] = isset($sp_opts['cookie-days']) ? $sp_opts['cookie-days'] : '1';
	$data['activation_mode']['autopopup_delay'] = isset($sp_opts['autopopup-delay']) ? $sp_opts['autopopup-delay'] : 4;
	$data['activation_mode']['onscroll_type'] = $sp_opts['onscroll-type'] ? $sp_opts['onscroll-type'] : 'pixels';
	$data['activation_mode']['onscroll_pixels'] = isset($sp_opts['onscroll-pixels']) ? $sp_opts['onscroll-pixels'] : 300;	
	$data['activation_mode']['onscroll_percentage'] = $sp_opts['onscroll-percentage'] ? $sp_opts['onscroll-percentage'] : 20;
	
	$data['popup_load_effect'] = $sp_opts['loader-animation'] ? $sp_opts['loader-animation'] : 'fadeIn';
	$data['popup_load_speed'] = $sp_opts['loader-speed'] ? $sp_opts['loader-speed'] : .75;
	$data['popup_unload_effect'] = $sp_opts['unloader-animation'] ? $sp_opts['unloader-animation'] : 'fadeOut';
	$data['popup_unload_speed'] = $sp_opts['unloader-speed'] ? $sp_opts['unloader-speed'] : .50;
	
	// Form type to use
	$data['form_type'] = isset($sp_opts['form_type']) ? $sp_opts['form_type'] : 'cf7';
	$data['cf7_id'] = isset($sp_opts['form-id'])? $sp_opts['form-id'] : '';
	
	$data['external_selectors'] = isset($sp_opts['external_selectors']) ? $sp_opts['external_selectors'] : '';		
	$data['insights'] = (isset($sp_opts['insights']) AND $sp_opts['insights']) ? 'true' : 'false';
	$data['autoclose'] = (isset($sp_opts['autoclose']) AND $sp_opts['autoclose']) ? 'true' : 'false';
	$data['autoclose_time'] = $sp_opts['autoclose_time'] ? $sp_opts['autoclose_time'] : '';		
	$data['redirect'] = (isset($sp_opts['redirect']) AND $sp_opts['redirect']) ? 'true' : 'false';
	$data['redirect_url'] = $sp_opts['redirect_url'] ? $sp_opts['redirect_url'] : '';		
	$data['sideButton'] = (isset($sp_opts['sideButton']) AND $sp_opts['sideButton']) ? 'true' : 'false';
	$data['enableTips'] = (isset($sp_opts['enableTips']) AND $sp_opts['enableTips']) ? 'true' : 'false';
	$data['enableMessage'] = (isset($sp_opts['enableMessage']) AND $sp_opts['enableMessage']) ? 'true' : 'false';
	$data['popTop'] = (isset($sp_opts['popTop']) AND $sp_opts['popTop']) ? 'true' : 'false';
	
	if( empty($id) ) 
		$data = sppro_get_global_form_data($data, $id);
	else 
		$data = sppro_get_popup_form_data($data, $id);
	
	$data = apply_filters('sppro_form_data_control', $data, $id); 
	
	return $data; 
}


/**
 * sppro_get_popup_form_data
 * @param  none
 * @return $data - array with overriden values from spforms
 */
function sppro_get_popup_form_data($data, $id) {
	
	if( 'sppro_forms'!=get_post_type($id) )
		return $data; 
	
	$custom =  get_post_meta($id, '_sppro_form_options', true); 
	$insights =  get_post_meta($id, 'popup_insights', true); 	
	
	//echo '<div style="width:400px;margin:40px auto;">'; var_dump($id);  echo '</div>';
	//echo '<div style="width:400px;margin:40px auto;">'; var_dump($insights);  echo '</div>';
	
	// Check if Override is choosen from Page Options
	if( 1 or isset($custom['_sppro_forms_meta_override']) AND $custom['_sppro_forms_meta_override'] ) {
		
		// echo '<br/><br/>'; print_r($custom);
		
		// Form type to use
		if( isset($custom['_sppro_forms_meta_form_type']) AND !empty($custom['_sppro_forms_meta_form_type']) )
			$data['form_type'] = $custom['_sppro_forms_meta_form_type'];
		else 
			$data['form_type'] = 'cf7';
		
		// Get Data for Form to choose
		if( isset($custom['_sppro_forms_meta_form_id']) AND !empty($custom['_sppro_forms_meta_form_id']) )
			$data['cf7_id'] = $custom['_sppro_forms_meta_form_id'];
		
		$custom_fieldset_layout = isset($custom['_sppro_forms_meta_fieldset_popup_layout']) ? $custom['_sppro_forms_meta_fieldset_popup_layout'] : array();
		$custom_fieldset_sidebutton = isset($custom['_sppro_forms_meta_fieldset_side_button']) ? $custom['_sppro_forms_meta_fieldset_side_button'] : array();
		$custom_fieldset_heading = isset($custom['_sppro_forms_meta_fieldset_heading_cta']) ? $custom['_sppro_forms_meta_fieldset_heading_cta'] : array();
		$custom_fieldset_activation = isset($custom['_sppro_forms_meta_fieldset_activation_modes']) ? $custom['_sppro_forms_meta_fieldset_activation_modes'] : array();
		$custom_fieldset_animation = isset($custom['_sppro_forms_meta_fieldset_animation_effects']) ? $custom['_sppro_forms_meta_fieldset_animation_effects'] : array();
		$custom_fieldset_advance_options = isset($custom['_sppro_forms_meta_fieldset_advance_options']) ? $custom['_sppro_forms_meta_fieldset_advance_options'] : array();
		
		// Popup Layout Settings
		if( isset($custom_fieldset_layout['_sppro_forms_meta_popup_layout']) AND !empty($custom_fieldset_layout['_sppro_forms_meta_popup_layout']) )
			$data['choose_layout'] = $custom_fieldset_layout['_sppro_forms_meta_popup_layout'];
		if( isset($custom_fieldset_layout['_sppro_forms_meta_fixed_side']) AND !empty($custom_fieldset_layout['_sppro_forms_meta_fixed_side']) )
			$data['fixed_side'] = $custom_fieldset_layout['_sppro_forms_meta_fixed_side'];
		if( isset($custom_fieldset_layout['_sppro_forms_meta_widgetized_popup']) AND !empty($custom_fieldset_layout['_sppro_forms_meta_widgetized_popup']) )
			$data['widgetized_popup'] = $custom_fieldset_layout['_sppro_forms_meta_widgetized_popup'];
		
		// Side Button Settings
		if( isset($custom_fieldset_sidebutton['_sppro_forms_meta_side_button_show']) AND !empty($custom_fieldset_sidebutton['_sppro_forms_meta_side_button_show']) )
			$data['side_button_position'] = $custom_fieldset_sidebutton['_sppro_forms_meta_side_button_show'];			
		if( isset($custom_fieldset_sidebutton['_sppro_forms_meta_side_button']) AND !empty($custom_fieldset_sidebutton['_sppro_forms_meta_side_button']) )
			$data['side_button_text'] = $custom_fieldset_sidebutton['_sppro_forms_meta_side_button'];			
		
		// Heading & CTA
		if( isset($custom_fieldset_heading['_sppro_forms_meta_popup_heading']) )
			$data['popup_heading'] = $custom_fieldset_heading['_sppro_forms_meta_popup_heading'];			
		if( isset($custom_fieldset_heading['_sppro_forms_meta_cta']) )
			$data['cta_text'] = $custom_fieldset_heading['_sppro_forms_meta_cta'];			
		
		// Activation Modes
		if( isset($custom_fieldset_activation['_sppro_forms_meta_activation_mode']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_activation_mode']) )
			$data['activation_mode']['mode'] = $custom_fieldset_activation['_sppro_forms_meta_activation_mode'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_autopopup-delay']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_autopopup-delay']) )
			$data['activation_mode']['autopopup_delay'] = $custom_fieldset_activation['_sppro_forms_meta_autopopup-delay'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_autopopup-days']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_autopopup-days']) )
			$data['activation_mode']['autopopup_days'] = $custom_fieldset_activation['_sppro_forms_meta_autopopup-days'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_onscroll-type']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_onscroll-type']) )
			$data['activation_mode']['onscroll_type'] = $custom_fieldset_activation['_sppro_forms_meta_onscroll-type'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_onscroll-pixels']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_onscroll-pixels']) )
			$data['activation_mode']['onscroll_pixels'] = $custom_fieldset_activation['_sppro_forms_meta_onscroll-pixels'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_onscroll-percentage']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_onscroll-percentage']) )
			$data['activation_mode']['onscroll_percentage'] = $custom_fieldset_activation['_sppro_forms_meta_onscroll-percentage'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_cookie-delay']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_cookie-delay']) )
			$data['activation_mode']['cookie_delay'] = $custom_fieldset_activation['_sppro_forms_meta_cookie-delay'];			
		if( isset($custom_fieldset_activation['_sppro_forms_meta_cookie-days']) AND !empty($custom_fieldset_activation['_sppro_forms_meta_cookie-days']) )
			$data['activation_mode']['cookie_days'] = $custom_fieldset_activation['_sppro_forms_meta_cookie-days'];			
		
		// Animation Effects
		if( isset($custom_fieldset_animation['_sppro_forms_meta_change_loader_animation']) AND !empty($custom_fieldset_animation['_sppro_forms_meta_change_loader_animation']) ) {
			//$activation_mode['mode']'] = $custom_fieldset_animation['_sppro_forms_meta_change_loader_animation'];		
			if( 'change' == $custom_fieldset_animation['_sppro_forms_meta_change_loader_animation'] ) {
				if( isset($custom_fieldset_animation['_sppro_forms_meta_loader_animation']) AND !empty($custom_fieldset_animation['_sppro_forms_meta_loader_animation']) ) {
					$data['popup_load_effect'] = isset($custom_fieldset_animation['_sppro_forms_meta_loader_animation']) ? $custom_fieldset_animation['_sppro_forms_meta_loader_animation'] : '';	
					$data['popup_load_speed'] = isset($custom_fieldset_animation['_sppro_forms_meta_loader_speed']) ? $custom_fieldset_animation['_sppro_forms_meta_loader_speed'] : '';
				}
			}
		}	
		
		if( isset($custom_fieldset_animation['_sppro_forms_meta_change_unloader_animation']) AND !empty($custom_fieldset_animation['_sppro_forms_meta_change_unloader_animation']) ) {
			//$activation_mode['mode']'] = $custom_fieldset_animation['_sppro_forms_meta_change_unloader_animation'];
			if( 'change' == $custom_fieldset_animation['_sppro_forms_meta_change_unloader_animation'] ) {
				if( isset($custom_fieldset_animation['_sppro_forms_meta_unloader_animation']) AND !empty($custom_fieldset_animation['_sppro_forms_meta_unloader_animation']) ) {
					$data['popup_unload_effect'] = isset($custom_fieldset_animation['_sppro_forms_meta_unloader_animation']) ? $custom_fieldset_animation['_sppro_forms_meta_unloader_animation'] : '';
					$data['popup_unload_speed'] = isset($custom_fieldset_animation['_sppro_forms_meta_unloader_speed']) ? $custom_fieldset_animation['_sppro_forms_meta_unloader_speed'] : '';	
				}
			}
		}
		
		// Add external selectors
		if( isset($custom_fieldset_advance_options['_sppro_forms_meta_external_selectors']) AND !empty($custom_fieldset_advance_options['_sppro_forms_meta_external_selectors']) ) {
			$data['external_selectors'] = $custom_fieldset_advance_options['_sppro_forms_meta_external_selectors'];		
		}
		
		// Insights Switch
		$data['insights'] = isset($custom_fieldset_advance_options['_sppro_forms_meta_insights']) ? 'true' : 'false';		
		
		// Autoclose Switch
		$data['autoclose'] = isset($custom_fieldset_advance_options['_sppro_forms_meta_autoclose']) ? 'true' : 'false';
		$custom_fieldset_advance_options['_sppro_forms_meta_autoclose_time'] = isset($custom_fieldset_advance_options['_sppro_forms_meta_autoclose_time']) ? $custom_fieldset_advance_options['_sppro_forms_meta_autoclose_time'] : 5;
		$data['autoclose_time'] = ($data['autoclose']==true) ? $custom_fieldset_advance_options['_sppro_forms_meta_autoclose_time'] : 5;		
		
		// Redirect Switch
		$data['redirect'] = isset($custom_fieldset_advance_options['_sppro_forms_meta_redirect']) ? 'true' : 'false'; 				
		$data['redirect_url'] = ($data['redirect']=='true') ? $custom_fieldset_advance_options['_sppro_forms_meta_redirect_url'] : ""; 		

		$data['bodyscroll'] = isset($custom_fieldset_advance_options['_sppro_forms_meta_bodyscroll']) ? 'false' : 'true'; 				
		
		// Fetch Data for Image Popup
		if($data['form_type']=='sp_image') {
			
			// Image Popup Should also be centered
			$data['choose_layout'] = 'centered'; 
			
			$custom_fieldset_sp_image = $custom['_sppro_forms_meta_fieldset_sp_image'];
			
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_image']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_image'])) {
				$image_id = $custom_fieldset_sp_image['_sppro_forms_meta_popup_image'];
				$popup_image = wp_get_attachment_image_src($image_id, 'full');
				//echo '<div style="width:400px;margin:40px auto;">'; var_dump($popup_image);  echo '</div>';
				$data['popup_image'] = $popup_image[0]; 
				$data['popup_image_width'] = $popup_image[1]; 
				$data['popup_image_height'] = $popup_image[2]; 
				$data['popup_image_is_intermediate'] = $popup_image[3]; 				
			}
			
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_type']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_type'])) {
				$data['link_type'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_link_type']; 
			}
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_page']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_page'])) {
				$data['link_page'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_link_page']; 
			}
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_custom']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_custom'])) {
				$data['link_custom'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_link_custom']; 
			}
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_target']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_link_target'])) {
				$data['link_target'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_link_target']; 
			}

			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_use_link']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_use_link'])) {
				$data['use_link'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_use_link']; 
				// Decide link on image (either to page or custom link)
				$data['link_url'] = ($data['link_type']=='custom') ? $data['link_custom'] : get_the_permalink($data['link_page']); 	
				$data['link_target'] = isset($data['link_target']) ? '_blank' : ''; 			
			}
			
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_width']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_width'])) {				
				$data['popup_image_width'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_image_width']; 
			}
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_height']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_height'])) {
				$data['popup_image_height'] = $custom_fieldset_sp_image['_sppro_forms_meta_popup_image_height']; 
			}			
			
		}		
	}
	
	// Check if overriding is desired		
	$data['message'] = sppro_check_form_id($data['cf7_id']);
	$data['cookie_delay'] = $data['activation_mode']['cookie_delay'];
	$data['cookie_days'] = $data['activation_mode']['cookie_days'];
	
	// To be provided as in-built options
	// To change use: sppro_form_data filter
	// 29 March: Introduced option in plugin edit page
	//$data['bodyscroll'] = 'true';
	
	// Get Extra Box Classes
	$data = sppro_get_box_classes($data);
	
	$data = apply_filters( 'sppro_form_data_'.$id, $data );
	
	return $data; 
}

/**
 * sppro_get_global_form_data($data);
 * @param  $data - all global settings
 * @return null 
 */
function sppro_get_global_form_data($data, $id='') {
	
	global $post; 	
	$custom =  get_post_meta( $post->ID, '_sppro_page_options', true); 

	// Check if Override is choosen from Page Options
	if( isset($custom['_sppro_meta_override']) AND $custom['_sppro_meta_override'] ) {
		
		// Get Data for Form to choose
		if( isset($custom['_sppro_meta_form_id']) AND !empty($custom['_sppro_meta_form_id']) )
			$data['cf7_id'] = $custom['_sppro_meta_form_id'];
		
		$custom_fieldset_layout = $custom['_sppro_meta_fieldset_popup_layout'];
		$custom_fieldset_sidebutton = $custom['_sppro_meta_fieldset_side_button'];
		$custom_fieldset_heading = $custom['_sppro_meta_fieldset_heading_cta'];	
		
		// Deprecated - to be removed (since: 2.1.4)
		$custom_fieldset_activation = isset($custom['_sppro_meta_fieldset_activation_modes']) ? $custom['_sppro_meta_fieldset_activation_modes'] : array();
		$custom_fieldset_animation = isset($custom['_sppro_meta_fieldset_animation_effects']) ? $custom['_sppro_meta_fieldset_animation_effects'] : array();;
		
		// Popup Layout Settings
		if( isset($custom_fieldset_layout['_sppro_meta_popup_layout']) AND !empty($custom_fieldset_layout['_sppro_meta_popup_layout']) )
			$data['choose_layout'] = $custom_fieldset_layout['_sppro_meta_popup_layout'];
		if( isset($custom_fieldset_layout['_sppro_meta_fixed_side']) AND !empty($custom_fieldset_layout['_sppro_meta_fixed_side']) )
			$data['fixed_side'] = $custom_fieldset_layout['_sppro_meta_fixed_side'];
		
		// Side Button Settings
		if( isset($custom_fieldset_sidebutton['_sppro_meta_side_button_show']) AND !empty($custom_fieldset_sidebutton['_sppro_meta_side_button_show']) )
			$data['side_button_position'] = $custom_fieldset_sidebutton['_sppro_meta_side_button_show'];			
		if( isset($custom_fieldset_sidebutton['_sppro_meta_side_button']) AND !empty($custom_fieldset_sidebutton['_sppro_meta_side_button']) )
			$data['side_button_text'] = $custom_fieldset_sidebutton['_sppro_meta_side_button'];			
		
		// Heading & CTA
		if( isset($custom_fieldset_heading['_sppro_meta_popup_heading']) AND !empty($custom_fieldset_heading['_sppro_meta_popup_heading']) )
			$data['popup_heading'] = $custom_fieldset_heading['_sppro_meta_popup_heading'];			
		if( isset($custom_fieldset_heading['_sppro_meta_cta']) AND !empty($custom_fieldset_heading['_sppro_meta_cta']) )
			$data['cta_text'] = $custom_fieldset_heading['_sppro_meta_cta'];			
		
		////////////////////////////////////////////
		// Deprecated - to be removed (since: 2.1.4)
		////////////////////////////////////////////
		// Activation Modes
		if( isset($custom_fieldset_activation['_sppro_meta_activation_mode']) AND !empty($custom_fieldset_activation['_sppro_meta_activation_mode']) )
			$data['activation_mode']['mode'] = $custom_fieldset_activation['_sppro_meta_activation_mode'];			
		if( isset($custom_fieldset_activation['_sppro_meta_autopopup-delay']) AND !empty($custom_fieldset_activation['_sppro_meta_autopopup-delay']) )
			$data['activation_mode']['autopopup_delay'] = $custom_fieldset_activation['_sppro_meta_autopopup-delay'];			
		if( isset($custom_fieldset_activation['_sppro_meta_onscroll-type']) AND !empty($custom_fieldset_activation['_sppro_meta_onscroll-type']) )
			$data['activation_mode']['onscroll_type'] = $custom_fieldset_activation['_sppro_meta_onscroll-type'];			
		if( isset($custom_fieldset_activation['_sppro_meta_onscroll-pixels']) AND !empty($custom_fieldset_activation['_sppro_meta_onscroll-pixels']) )
			$data['activation_mode']['onscroll_pixels'] = $custom_fieldset_activation['_sppro_meta_onscroll-pixels'];			
		if( isset($custom_fieldset_activation['_sppro_meta_onscroll-percentage']) AND !empty($custom_fieldset_activation['_sppro_meta_onscroll-percentage']) )
			$data['activation_mode']['onscroll_percentage'] = $custom_fieldset_activation['_sppro_meta_onscroll-percentage'];			

		// Animation Effects
		if( isset($custom_fieldset_animation['_sppro_meta_change_loader_animation']) AND !empty($custom_fieldset_animation['_sppro_meta_change_loader_animation']) ) {
			//$activation_mode['mode']'] = $custom_fieldset_animation['_sppro_meta_change_loader_animation'];		
			if( 'change' == $custom_fieldset_animation['_sppro_meta_change_loader_animation'] ) {
				if( isset($custom_fieldset_animation['_sppro_meta_loader_animation']) AND !empty($custom_fieldset_animation['_sppro_meta_loader_animation']) ) {
					$data['popup_load_effect'] = $custom_fieldset_animation['_sppro_meta_loader_animation'];	
				}
			}
		}	
		
		if( isset($custom_fieldset_animation['_sppro_meta_change_unloader_animation']) AND !empty($custom_fieldset_animation['_sppro_meta_change_unloader_animation']) ) {
			//$activation_mode['mode']'] = $custom_fieldset_animation['_sppro_meta_change_unloader_animation'];
			if( 'change' == $custom_fieldset_animation['_sppro_meta_change_unloader_animation'] ) {
				if( isset($custom_fieldset_animation['_sppro_meta_unloader_animation']) AND !empty($custom_fieldset_animation['_sppro_meta_unloader_animation']) ) {
					$data['popup_unload_effect'] = $custom_fieldset_animation['_sppro_meta_unloader_animation'];
				}
			}
		}
		////////////////////////////////////////////
		// Deprecated Till Here
		////////////////////////////////////////////
	}

	$data['cf7_id'] = apply_filters( 'sppro_dollar_cf7_id', $data['cf7_id'] );
	$data['side_button_text'] = apply_filters( 'sppro_dollar_side_button_text', $data['side_button_text'] );
	$data['popup_heading'] = apply_filters( 'sppro_dollar_popup_heading', $data['popup_heading'] );
	$data['cta_text'] = apply_filters( 'sppro_dollar_cta_text', $data['cta_text'] );
	
	$data['choose_layout'] = apply_filters( 'sppro_dollar_choose_layout', $data['choose_layout'] );		
	$data['fixed_side'] = apply_filters( 'sppro_dollar_change_fixed_side', $data['fixed_side'] );		
	$data['activation_mode'] = apply_filters( 'sppro_dollar_activation_mode', $data['activation_mode'] );		
	
	$data['popup_load_effect'] = apply_filters( 'sppro_dollar_popup_load_effect', $data['popup_load_effect'] );
	$data['popup_load_speed'] = apply_filters( 'sppro_dollar_popup_load_speed', $data['popup_load_speed'] );		
	$data['popup_unload_effect'] = apply_filters( 'sppro_dollar_popup_unload_effect', $data['popup_unload_effect'] );
	$data['popup_unload_speed'] = apply_filters( 'sppro_dollar_popup_unload_speed', $data['popup_unload_speed'] );
	
	// Check if overriding is desired		
	$data['message'] = sppro_check_form_id($data['cf7_id']);
	$data['cookie_delay'] = $data['activation_mode']['cookie_delay'];
	
	// To be provided as in-built options
	// To change use: sppro_form_data filter
	$data['bodyscroll'] = 'true';
	
	// Get Extra Box Classes
	$data = sppro_get_box_classes($data);
	
	$data = apply_filters( 'sppro_form_data', $data );
	
	return $data; 
}

?>