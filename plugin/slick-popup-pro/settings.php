<?php

require_once SPPRO_PLUGIN_LIBS_DIR . '/includes/admin-functions.php';
require_once SPPRO_PLUGIN_LIBS_DIR . '/includes/admin-pages.php';
require_once SPPRO_PLUGIN_LIBS_DIR . '/includes/admin-welcome-panel.php';
require_once SPPRO_PLUGIN_LIBS_DIR . '/includes/forms-functions.php';
require_once SPPRO_PLUGIN_LIBS_DIR . '/includes/functions.php';
require_once SPPRO_PLUGIN_LIBS_DIR . '/includes/l10n.php';


class SPPRO {

	public static function get_option( $name, $default = false ) {
		$option = get_option( 'sppro' );

		if ( false === $option ) {
			return $default;
		}

		if ( isset( $option[$name] ) ) {
			return $option[$name];
		} else {
			return $default;
		}
	}

	public static function update_option( $name, $value ) {
		$option = get_option( 'sppro' );
		$option = ( false === $option ) ? array() : (array) $option;
		$option = array_merge( $option, array( $name => $value ) );
		update_option( 'sppro', $option );
	}
}

//add_action( 'plugins_loaded', 'sppro' );
function sppro() {
	sppro_load_textdomain();
	SPPRO::load_modules();

	/* Shortcodes */
	add_shortcode( 'contact-form-7', 'sppro_contact_form_tag_func' );
	add_shortcode( 'contact-form', 'sppro_contact_form_tag_func' );
}

add_action( 'init', 'sppro_init' );
function sppro_init() {
	//sppro_get_request_uri();
	// Commented out in update 2.1.9
	sppro_register_post_types();
	do_action( 'sppro_init' );
}

add_action( 'admin_init', 'sppro_upgrade' );
function sppro_upgrade() {
	$old_ver = SPPRO::get_option( 'version', '0' );
	$new_ver = SPPRO_VERSION;

	if ( $old_ver == $new_ver ) {
		return;
	}

	do_action( 'sppro_upgrade', $new_ver, $old_ver );
	
	// -1 if first version is lower, 0 if equal, 1 first version is greater greater
	$compare = version_compare($new_ver,$old_ver);
	$datetime = new DateTime(); 
	$datetime->setTimeZone(new DateTimeZone('UTC'));
	$unix = $datetime->format('Y-m-d g:i A');
	
	if($compare==1) {

		if(version_compare($old_ver,'2.1.3')!=1) {
			SPPRO::update_option( 'installdate', $unix );
		}
		
		if(version_compare($old_ver, '2.2.3') != 1) {
			sppro_update_for_version_2_2_3(); 
		}

		if(version_compare($old_ver, '2.3.0') != 1) {
			if(version_compare($old_ver, '2.2.9') < 1)
				sppro_update_for_version_2_3_0(); 
		}
	}

	SPPRO::update_option( 'version', $new_ver );
}

/* Install and default settings */
add_action( 'activate_' . SPPRO_PLUGIN_BASENAME, 'sppro_install' );
function sppro_install() {
	
	sppro_load_textdomain();
	sppro_register_post_types();
	sppro_upgrade();

	if ( get_posts( array( 'post_type' => 'sppro_forms' ) ) ) {
		return;
	}
	
	return; 
	
	/*
	$contact_form = WPCF7_ContactForm::get_template(
		array(
			'title' => sprintf( __( 'Contact form %d', 'sp-pro-txt-domain' ), 1 ),
		)
	);

	$contact_form->save();
	*/
	
}

function sppro_update_for_version_2_2_3() {
	
	global $sp_opts; 	
	$license_key = isset($sp_opts['license_key']) ? $sp_opts['license_key'] : ""; 
	
	if($license_key=='') return; 	
	update_option('sppro_license_key', $license_key);

	$SPPRO_AutoUpdate = new SPPRO_AutoUpdate();

	$response = $SPPRO_AutoUpdate->activate_license($license_key);
	$reason = $response['message'];
	
	update_option('sppro_is_activated', $response['is_activated']);	
}

function sppro_update_for_version_2_3_0() {
	$forms = get_posts( array( 'post_type' => 'sppro_forms', 'numberposts' => -1 ) ); 
	$form_ids = array(); 
	$updates = array(); 
	
	foreach($forms as $form) {
		$form_id = $form->ID; 
		
		$form_options =  get_post_meta( $form_id, '_sppro_form_options', true); 
		$old_form_options = $form_options; 
		$where_to_show = isset($form_options['_sppro_forms_meta_where_to_show']) ? $form_options['_sppro_forms_meta_where_to_show'] : 'everywhere'; 
		$selected_pages = isset($form_options['_sppro_forms_meta_selected_pages']) ? $form_options['_sppro_forms_meta_selected_pages'] : ""; 
		$background_color = isset($form_options['_sppro_forms_meta_fieldset_theme_colors']['_sppro_forms_meta_custom_background_color']) ? $form_options['_sppro_forms_meta_fieldset_theme_colors']['_sppro_forms_meta_custom_background_color'] : "#EFEFEF"; 

		// Unset the deleted options
		if(isset($form_options['_sppro_forms_meta_selected_pages']))
			unset($form_options['_sppro_forms_meta_selected_pages']); 

		// Add new options to the _sppro_form_options
		$form_options["_sppro_forms_meta_fieldset_theme_colors"]["_sppro_forms_meta_custom_background_color"] = array(
			'color' => $background_color,
			'image'		=> '',
			'repeat'	=> 'no-repeat',
			'position'	=> 'center center',
			'attachment'=> 'fixed',
			'size'		=> 'cover',
		);
		$form_options["_sppro_forms_meta_fieldset_where_to_show"] = array(
			//"show_on_pages" => false,
			"pages_choices" => 'everywhere',
			"choose_pages" => "",
			//"show_on_posts" => false,
			"posts_choices" => 'everywhere',
			"choose_posts" => "",
			//"show_on_categories" => false,
			"categories_choices" => 'everywhere',
			"choose_categories" => "",
			//"show_on_tags" => false,
			"tags_choices" => 'everywhere',
			"choose_tags" => "",
			//"show_on_search_pages" => false,
			//"show_on_404_page" => false,
		); 
		
		if($where_to_show=='everywhere') {
			
		}
		else {
			$form_options['_sppro_forms_meta_where_to_show'] = 'onselected'; 
 			$form_options["_sppro_forms_meta_fieldset_where_to_show"]['show_on_pages'] = '1'; 
			$form_options["_sppro_forms_meta_fieldset_where_to_show"]['pages_choices'] = 'onselected'; 
			$form_options["_sppro_forms_meta_fieldset_where_to_show"]['choose_pages'] = $selected_pages;
		}
		$update = update_post_meta($form_id, '_sppro_form_options', $form_options); 

		if($update) {
			$updates[$form_id] = get_the_title($form_id) . " : updated with pages: " .  print_r($selected_pages, true); 
		}
		else $updates[$form_id] = get_the_title($form_id) . " : NOT updated with pages: " .  print_r($selected_pages, true); 
	}


	//$new_data = implode("<br>", $updates);
	//wp_mail( 'om.akdeveloper@gmail.com', "Mail Check data at: " . current_time("H:i:s"), $new_data ); 
	
}