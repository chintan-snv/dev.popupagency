<?php

/**
 * Get Color Scheme Options  
 * Since Version 1.0
 * @param string $color_scheme (choosen color scheme)
 * @param string $custom_color_scheme (choosen color when custom scheme)
 * @param string $custom_text_color (choosen text color when custom scheme)
 
 * @return array() $colors (keys: main-text-color, main-color, main-background-color)
 * Called in sppro_option_css() to generate custom CSS
 */
function sppro_get_theme_colors_values($color_scheme, $custom_color_scheme="", $custom_text_color="", $custom_form_background_color="", $custom_curtain="") {
	
	$colors = array();
	$custom_form_background_color = !is_array($custom_form_background_color) ? $custom_form_background_color : $custom_form_background_color['color']; 
	$colors['main-text-color'] = '#EFEFEF'; 
	$colors['main-background-color'] = '#EFEFEF'; 	
	$colors['custom-curtain-background'] = 'none repeat scroll 0 0 rgba(0, 0, 0, 0.75)'; 	
	switch($color_scheme) {
		case 'master_red' :
			$colors['main-color'] = '#ED1C24';
			break; 
		case 'creamy_orange' :
			$colors['main-color'] = '#EE5921'; 
			break; 
		case 'cool_green' :
			$colors['main-color'] = '#00A560'; 
			break; 
		case 'light_blue' :
			$colors['main-color'] = '#08ADDC'; 
			$colors['main-text-color'] = '#484848'; 
			break; 
		case 'custom' : 
		case 'custom_theme' : 
			$colors['main-color'] = $custom_color_scheme; 
			$colors['main-text-color'] = $custom_text_color; 
			$colors['main-background-color'] = $custom_form_background_color;
			break; 
		case 'light' :
			$colors['main-color'] = '#BBB'; 
			$colors['main-text-color'] = '#484848'; 
			break; 
		case 'dark' :
		default :
			$colors['main-color'] = '#484848'; 
			$colors['main-text-color'] = '#DDDDDD'; 
			break; 
	}
	
	if($custom_curtain) {
		$colors['custom-curtain-background'] = $custom_curtain;
	}
	
	return apply_filters( 'sppro_dollar_colors', $colors );
		
}

/**
 * Get Popup Border Options
 * Since Version 1.0
 * @param string $popup_corners (choosen popup border radius)
 
 * @return array() $borders (keys: width(radius))
 * Called in sppro_option_css() to generate custom CSS
 */
function sppro_get_popup_border_values($popup_corners) {
	
	if( SPPRO_DEBUG ) {
		echo '<br/>Corners in Popup: '. $popup_corners; 
	}
	
	global $sp_opts; 	
	$borders = array();	
	$custom_popup_corners = isset($sp_opts['custom-popup-border']) ? $sp_opts['custom-popup-border'] : array('width'=>'20px');
	
	switch($popup_corners) {
		case 'square':
			$border_radius_value = '0px';
			break;
		case 'rounded':
			$border_radius_value = '20px';
			break;
		case 'custom':
			$border_radius_value = $custom_popup_corners['width'];
			break;
		default: 
			$border_radius_value = '0px';
	}
	
	$borders['radius'] = $border_radius_value; 
	return $borders; 
}

/**
 * Get Side Button Options
 * Since Version 1.0
 * @param string $side_button_scheme (choosen scheme for side button (inherit,custom))
 * @param string $side_button_background (choosen color when scheme is custom)
 
 * @return array() $side_button (keys: background-color)
 * Called in sppro_option_css() to generate custom CSS
 */
function sppro_get_side_button_values($scheme, $background="", $color="", $theme_colors=array()) {
	
	if( SPPRO_DEBUG ) {
		echo '<br/><hr>Input in function: sppro_get_side_button_values';
		echo '<br/><span style="background:'.$scheme.';">.side_button_scheme: '.$scheme.'</span>'; 
		echo '<br/><span style="background:'.$background.';">.side_button_background: '.$background.'</span>'; 
		echo '<br/><span style="background:'.$color.';">.side_button_text_color: '.$color.'</span>'; 
		echo '<br/><span style="background:'.$color.';">.theme_colors: '.print_r($theme_colors, true).'</span>'; 		
	}
	global $sp_opts; 	
	$side_button = array();	
	$side_button['background-color'] = '';
	$side_button['text-color'] = '';

	if($scheme=='inherit_from_color_scheme' OR $scheme=='inherit') {	
		$side_button['background-color'] = $theme_colors['main-color'];
		$side_button['text-color'] = $theme_colors['main-text-color'];
	}
	elseif($scheme=='custom') {
		$side_button['background-color'] = $background;
		$side_button['text-color'] = $color;
	}
	
	if( SPPRO_DEBUG ) {
		echo '<br/><hr>Output in function: sppro_get_side_button_values';
		echo '<br/><span style="background:">.side_button: '.print_r($side_button, true).'</span>'; 		
	}
	return $side_button; 
}

/**
 * Get Submit Button Options
 * Since Version 1.0
 * @param string $submit_button_scheme (choosen scheme for submit button (inherit,custom))
 * @param string $submit_button_background (choosen color when scheme is custom)
 
 * @return array() $submit_button (keys: background-color)
 * Called in sppro_option_css() to generate custom CSS
 */
function sppro_get_submit_button_values($scheme, $background="", $color="", $theme_colors=array()) {
	
	if( SPPRO_DEBUG ) {
		echo '<br/><hr>Input in function: sppro_get_submit_button_values';
		echo '<br/><span style="background:'.$scheme.';">.submit_button_scheme: '.$scheme.'</span>'; 
		echo '<br/><span style="background:'.$background.';">.submit_button_background: '.$background.'</span>'; 
		echo '<br/><span style="background:'.$color.';">.submit_button_text_color: '.$color.'</span>'; 		
		echo '<br/><span style="background:'.$color.';">.theme_colors: '.print_r($theme_colors, true).'</span>'; 		
	}	
	global $sp_opts; 	
	$submit_button = array();		
	if($scheme=='inherit_from_color_scheme' OR $scheme=='inherit') {
		$submit_button['background-color'] = $theme_colors['main-color'];
		$submit_button['text-color'] = $theme_colors['main-text-color'];
	}
	else {
		$submit_button['background-color'] = $background; 
		$submit_button['text-color'] = $color; 
	}
	
	if( SPPRO_DEBUG ) { echo '<br/>Submit Button: '; print_r($submit_button); }	
	return $submit_button; 
}

/**
 * Get sppro_fire_activation_mode_script
 * Since Version 1.2
 * @param string $activation_mode (manually,autopopup,onscroll,onexit) 
 
 * @return none
 * Echo the script for activation mode choosen
 * Called in sppro_add_my_popup() 
 */
function sppro_fire_activation_mode_script($activation_mode, $id='') {
	/*
	switch($activation_mode['mode']) {
		case 'autopopup':
			echo '
				<script>
				jQuery(document).ready(function($) {
					cookie = sppro_readCookie("'.$id.'");
					if(cookie!="closed" && cookie!="closewithsession") {
						setTimeout(function () { sppro_loader("'.$id.'"); }, '.($activation_mode['autopopup_delay'] * 1000).');
					}
				});
				</script>';			
			break; 
		case 'onscroll':
			if( $activation_mode['onscroll_type'] == 'pixels'  ) {
				echo '
				<script>
				jQuery(document).ready(function($) {
					cookie = sppro_readCookie("'.$id.'");
					if(cookie!="closed" && cookie!="closewithsession") {
						var eventFired = false;
						jQuery(window).on("scroll", function() {
							var currentPosition = jQuery(document).scrollTop();
							if (currentPosition > '.$activation_mode['onscroll_pixels'].' && eventFired == false) {
								eventFired = true;
								//console.log( "scrolled" );
								sppro_loader("'.$id.'"); 
							}
						});
					}
				});
				</script>';
			}
			if( $activation_mode['onscroll_type'] == 'percentage'  ) {
				echo '
				<script>
					cookie = sppro_readCookie("'.$id.'");
					if(cookie!="closed" && cookie!="closewithsession") {
						var eventFired = false;
						jQuery(window).on("scroll", function() {
							var currentPosition = jQuery(document).scrollTop();
							if (currentPosition > jQuery(document).height()* '.($activation_mode['onscroll_percentage']/100).' && eventFired == false) {
								eventFired = true;
								//console.log( "scrolled" );
								sppro_loader("'.$id.'"); 
							}
						});
					}
					</script>';
			}
			break; 
		case 'onexit':
			echo '
				<script>
					window.addEventListener("beforeunload", function (e) {
						//console.log("unloading");
						e.preventDefault();
						e.stopPropagation();
							
						setTimeout(function () { // Timeout to wait for user response
							setTimeout(function () { // Timeout to wait onunload, if not fired then this will be executed
								//console.log("User stayed on the page.");
								sppro_loader("'.$id.'");	
						}, 1500)}, 1);
							
						return "Would you like to fill up our form?";						
					});
					window.addEventListener("unload", function (e) {						
						//sppro_unloader(); 						
						jQuery("#sppro_popup_box, #sppro_curtain").remove();
					});					
				</script>';
			break; 
		default: break; 
	}
	*/
	//echo '		
		//		<script>jQuery(document).ready(function($) { sppro_set_popup("'.$id.'"); });</script>';
}

/////////////////////////////////////
// Uninstall Hook Helper
/////////////////////////////////////
function sppro_uninstall_plugin($test_mail=false) { // Uninstallation actions here
	
	global $sp_opts; 
	$option_name = 'sp_opts'; 	
	$delete_data = $sp_opts['delete_data'];	
	$send_test_email = $test_mail;
	//$admin_email = 'poke@slickpopup.com';
	$site_url = site_url(); 
	//$headers[] = 'From: Om Ak <om.akdeveloper@gmail.com>';
	//$headers[] = 'CC: ';
	//$headers[] = 'BCC: '; 
	
	if( $delete_data=='on' ) {
		if( delete_option($option_name) AND $send_test_email ) {
			$body = 'Settings: ' .$delete_data. ' Plugin has been successfully deleted including options variable.';
			//wp_mail( $admin_email, 'SP Pro Uninstall: '.$site_url, $body, $headers ); 			
		}
		else {
			$body = 'Settings: ' .$delete_data. ' Plugin was uninstalled but the delete data could NOT be deleted.'; 
			//wp_mail( $admin_email, 'SP Pro Uninstall: '.$site_url, $body, $headers ); 
		}
	}
	else {
		$body = 'Settings: ' .$delete_data. ' Plugin was uninstalled but the delete data was not On, so settings are kept.'; 
		//wp_mail( $admin_email, 'SP Pro Uninstall: '.$site_url, $body, $headers ); 
	}
}

//add_action( 'wp_footer', 'sppro_check_global' );
/////////////////////////////////////
// Print Global Variable In Footer
/////////////////////////////////////
function sppro_check_global() { 
	if( SPPRO_DEBUG ) {
		global $sp_opts, $post; 
		echo '<br/><div>'; echo $post->ID; echo '</div><br/>';
		echo '<br/><div>'; var_dump( $sp_opts ); echo '</div><br/>';
		//echo '<br/>'; echo absint('103');
	}
}


add_action( 'wpcf7_mail_sent', 'sppro_onmailsent_create_cookie' );
function sppro_onmailsent_create_cookie() {
	
	if( isset($_POST) AND isset($_POST['_wpcf7']) ) {
		
		global $sp_opts; 
		$cf7_id = isset($sp_opts['form-id']) ? $sp_opts['form-id'] : 'good';
		
		$ids = array();
		$forms = sppro_get_active_forms();
		foreach($forms as $form) {
			if(isset($form['id'])) $ids[] = $form['id'];
			else $ids[] = $cf7_id; 
		}
		
		$a = print_r($forms, true); 
		$a .= '<br/>ID here: '. $cf7_id; 
		$a .= print_r($_POST, true); 
		//die($a);
		
		$expiry_type = isset($sp_opts['cookie-delay'])? $sp_opts['cookie-delay'] : 'days';
		$expiry = isset($sp_opts['cookie-days'])? $sp_opts['cookie-days'] : '7';
		
		// If expiry_type== days then use the days value, else use expiry_type (-1,0)
		$expiry = ($expiry_type=='days') ? $expiry : $expiry_type; 
		
		/////////////////////////////////////
		// Do Something For Global Popup 
		/////////////////////////////////////
		if( in_array($_POST['_wpcf7'], $ids) ) {
			
			$id = $_POST['_wpcf7'];
			
			// Do Something For Multiple Popups
			if( 'sppro_forms' == get_post_type($id) ) {				
				$custom =  get_post_meta( $id, '_sppro_form_options', true); 
				
				if( isset($custom['_sppro_forms_meta_override']) AND $custom['_sppro_forms_meta_override'] ) {
					$activation = $custom['_sppro_forms_meta_fieldset_activation_modes'];
					if( isset($activation['_sppro_forms_meta_autopopup-delay']) AND !empty($activation['_sppro_forms_meta_autopopup-delay']) )
						// Update Expiry Time - from popup meta
						$expiry = $custom_fieldset_activation['_sppro_forms_meta_autopopup-delay'];
				}
			}
			
			//sppro_set_cookie($_POST['_wpcf7'], 'submitted', $expiry);
			//if(sppro_set_cookie($_POST['_wpcf7'], 'submitted', $expiry)) die('good'); else die('bad');
		}
	}
	
	return; 
}

?>