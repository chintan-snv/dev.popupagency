<?php
/*
Plugin Name:  Slick Popup Pro
Plugin URI:   https://www.slickpopupup.com/?utm_source=pluginuri&utm_medium=sppro&utm_campaign=OmAkSols
Description:  Slick Popup Pro is a popup plugin for WordPress which enables you to create a popup with any type of content in a WordPress website. You can include <code>any HTML, a shortcode, an image, an iframe, a Youtube Video or a contact form</code>. This is the best plugin for creating a popup for <code>Contact Form 7 plugin</code>.
Author URI:   http://www.omaksolutions.com 
Author:       Om Ak Solutions 
Version:      2.3.3
Text Domain:  sp-pro-txt-domain
*/


define( 'SPPRO_VERSION', '2.3.3' );
define( 'SPPRO_PLUGIN_TITLE', 'Slick Popup Pro' );
define( 'SPPRO_REQUIRED_WP_VERSION', '3.0.1' );
define( 'SPPRO_PLUGIN', __FILE__ );
define( 'SPPRO_PLUGIN_BASENAME', plugin_basename( SPPRO_PLUGIN ) );
define( 'SPPRO_PLUGIN_NAME', trim( dirname( SPPRO_PLUGIN_BASENAME ), '/' ) );
define( 'SPPRO_PLUGIN_DIR', untrailingslashit( dirname( SPPRO_PLUGIN ) ) );
define( 'SPPRO_PLUGIN_LIBS_DIR', SPPRO_PLUGIN_DIR . '/libs' );
define( 'SPPRO_PLUGIN_URL', plugins_url( '' , __FILE__ ) );
define( 'SPPRO_PLUGIN_IMG_URL', SPPRO_PLUGIN_URL . '/libs/admin/img' );
define( 'SPPRO_SUPPORT_EMAIL', 'poke@slickpopup.com' );
define( 'SPPRO_SUPPORT_USER', 'slickpopupupteam' );

if ( ! defined( 'SPPRO_DEBUG' ) ) {
	define ( 'SPPRO_DEBUG', FALSE );
}

if ( ! defined( 'SITE_URL' ) ) {
	define ( 'SITE_URL', site_url() );	
}

if ( ! defined( 'ADMIN_URL' ) ) {
	define ( 'ADMIN_URL', trim( admin_url(), '/' ) );	
}

define ( 'SPPRO_OPTIONS_URL', ADMIN_URL . '/admin.php?page=sppro_options' );
define ( 'SPPRO_POPUPS_URL', ADMIN_URL . '/admin.php?page=sp-pro' );

require_once( SPPRO_PLUGIN_DIR . '/settings.php' );
require_once( SPPRO_PLUGIN_DIR . '/functions-form-data.php' );
require_once( SPPRO_PLUGIN_DIR . '/functions-ajax.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/sppro-forms.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/admin/codestar/cs-framework.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/classes/sppro_auto_update.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/classes/sppro_importer.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/classes/class-sppro-forms-list-table.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/classes/class-sppro-shortcode.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/extras.php' );
require_once( SPPRO_PLUGIN_LIBS_DIR . '/extras.php' );

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/libs/admin/redux-framework/framework.php' ) ) {
	require_once( dirname( __FILE__ ) . '/libs/admin/redux-framework/framework.php' );
}

if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/libs/admin/admin-init.php' ) ) {
	require_once( dirname( __FILE__ ) . '/libs/admin/admin-init.php' );
}


/////////////////////////////////////
// Activation Hook
/////////////////////////////////////
register_activation_hook(__FILE__, 'sppro_on_activate'); 
function sppro_on_activate(){
	// Empty Activation Hook
	update_option('sppro_install_date', current_time('Y-m-d H:i:s')); 
	update_option('sppro_delete_data', 0); 	
	set_transient( 'sppro_activated', 1 );
	add_option('sppro_do_activation_redirect', true);
}

add_action('admin_init', 'sppro_after_activation');
function sppro_after_activation() {
	
	if (get_option('sppro_do_activation_redirect', false)) {
        delete_option('sppro_do_activation_redirect');
		$url = admin_url('admin.php?page=sp-pro-updates');
		if(wp_redirect($url))
			exit;
    }
}

function my_remove_wp_seo_meta_box() {
    remove_meta_box('wpseo_meta', "sppro_forms", 'normal');
}
add_action('add_meta_boxes', 'my_remove_wp_seo_meta_box', 100);

/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 * @param $upgrader_object Array
 * @param $options Array
 */
function sppro_upgrade_completed( $upgrader_object, $options ) {
	// The path to our plugin's main file
	$our_plugin = plugin_basename( __FILE__ );
	// If an update has taken place and the updated type is plugins and the plugins element exists
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
		// Iterate through the plugins being updated and check if ours is there
		foreach( $options['plugins'] as $plugin ) {
			if( $plugin == $our_plugin ) {
				// Set a transient to record that our plugin has just been updated
				set_transient( 'sppro_updated', 1 );
			}
		}
	}
}
add_action( 'upgrader_process_complete', 'sppro_upgrade_completed', 10, 2 );

/*
 * Save sppro_delete_data option when redux settings are saved
 * Used in uninstall.php file to delete the options
*/
add_action ('redux/options/sp_opts/saved', 'sppro_redux_option_saved');
function sppro_redux_option_saved() {
	global $sp_opts; 	
	$delete_data = $sp_opts['delete_data'];	
	update_option('sppro_delete_data', $delete_data); 		
}

/////////////////////////////////////
// Deactivation Hook
/////////////////////////////////////
register_deactivation_hook(__FILE__, 'sppro_on_deactivate'); 
function sppro_on_deactivate(){
	// Empty Deactivation Hook
}

/////////////////////////////////////
// Uninstall Hook
/////////////////////////////////////
register_uninstall_hook(__FILE__, 'sppro_on_uninstall'); 
function sppro_on_uninstall(){
	// Empty Activation Hook
	// Temporary Fix
	//delete_option( 'sp_opts' ); 	
	
	// Do the action
	// sppro_uninstall_plugin(true);
}

/////////////////////////////////////////
// Integrate sppro_auto_update class
/////////////////////////////////////////
add_action('init','sppro_auto_update_loader', 99);
function sppro_auto_update_loader(){	
	global $sp_opts;

	$license_key = get_option('sppro_license_key');
	$sppro_license_key = (isset($license_key) AND !empty($license_key)) ? $license_key : '';
	
	new SPPRO_AutoUpdate($sppro_license_key);
}


/////////////////////////////////////////
// Initialise the plugin and scripts
/////////////////////////////////////////
add_action('template_redirect','sppro_slick_popup_loaded');
function sppro_slick_popup_loaded(){
    
	if(is_admin()) return; 

	$sppro_license_key = get_option('sppro_license_key', '');
	$sppro_is_activated = get_option('sppro_is_activated', false);

	if(empty($sppro_license_key) OR !$sppro_is_activated) {
		$sppro_notices = array();

		$sppro_notices = array(
			'id' => 'no-license', 
			'class' => 'error', 
		    'is-dismissible' => 'is-dismissible', 
		    'is-shown' => false,
		    'message' => 'Please enter a License key to activate the popup on the <a href="'.site_url("/wp-admin/admin.php?page=sp-pro-updates").'">Updates</a> page. If you don\'t have a licence key, please see <a href="https://codecanyon.net/item/slick-popup-pro/16115931?ref=OmAkSols">details & pricing</a>.',
		);
		update_option('sppro_notices', $sppro_notices);
		return;
	}
	else {
		update_option('sppro_notices', '');
	}
	
	$show = sppro_decide_dollar_show();	
	$show = apply_filters( 'sppro_dollar_show', $show );
	
	if( $show ) {		
		// If Plugin State is Enabled = 1
		// Let us Create the Beauty		
		sppro_add_html();
		sppro_add_html_and_scripts();		
	}
	else {
		// So, it's Sunday. Don't Do Nothing!!
	}
}

/////////////////////////////////////////
// Enqueue Scripts and Custom CSS
/////////////////////////////////////////
function sppro_add_html_and_scripts(){
	// Add Pop Up Scripts to Footer Here
	add_action( 'wp_enqueue_scripts', 'sppro_enqueue_popup_scripts' );	
	add_action('wp_footer', 'sppro_option_css');	
}

/////////////////////////////////////////
// Add Popup HTML To the Footer
/////////////////////////////////////////
function sppro_add_html(){
	global $sp_opts; 	
	$output_hook = isset($sp_opts['output_hook']) ? $sp_opts['output_hook'] : 'wp_footer'; 	
	$output_hook = apply_filters('sppro_output_hook', $output_hook); 	
	$priority = ($output_hook=='wp_head') ? 99 : ''; 
	add_action($output_hook, 'sppro_add_my_popup', $priority);		
}

/////////////////////////////////////////
// Add Popup
/////////////////////////////////////////
function sppro_add_my_popup() {
	
	if( is_admin() ) 
		return; 
		
	$forms = sppro_get_active_forms();
	?>
	
	<?php foreach($forms as $formID=>$value) { ?>		
		
		<?php 
			$id = ''; 				
			$side_btn_id = 'sppro_sideEnquiry';
			if( $value['type']!='global' ) {
				$id = $value['id']; 
				$side_btn_id = $side_btn_id.'-'.$value['id'];
			}
			
			// Update Insights Here, can also be done after page load on document ready
			// if slow speed is an issue
			if($id!='') {
				if(! $insights = get_post_meta($id, 'popup_insights', true)) {
					$insights = array(
						'loaded' => 0, 
						'opened' => 0, 
						'submitted' => 0, 
					);
				}
				
				$form_options = get_post_meta($id, '_sppro_form_options', true);
				if(isset($form_options['_sppro_forms_meta_fieldset_advance_options']['_sppro_forms_meta_insights']))
					$insights['loaded'] = $insights['loaded'] + 1; 
				
				update_post_meta($id, 'popup_insights', $insights); 				
			}
			
			//$side_button_position = $form['btn_position'];
			$data = sppro_get_form_data($id); 

			if(is_bool($data['bodyscroll']) ) {
				$data['bodyscroll'] = var_export($data['bodyscroll'], true); 
			}


			$animator = array(
				'data-form_type' => $data['form_type'],
				'data-cf7formid' => $value['cf7formid'],
				'data-sidebtn' => $side_btn_id,
				'data-cookieexpiration' => isset($data['cookie_delay']) ? $data['cookie_delay'] : 'days',
				'data-cookieexpirationdays' => isset($data['cookie_days']) ? $data['cookie_days'] : '',
				'data-activationmode' => $data['activation_mode']['mode'],
				'data-loadspeed' => $data['popup_load_speed'],
				'data-loadeffect' => $data['popup_load_effect'],
				'data-unloadeffect' => $data['popup_unload_effect'],
				'data-unloadspeed' => $data['popup_unload_speed'],
				'data-bodyscroll' => $data['bodyscroll'],
				'data-autopopupdelay' => $data['activation_mode']['autopopup_delay'],
				'data-onscroll_type' => $data['activation_mode']['onscroll_type'],
				'data-onscroll_pixels' => $data['activation_mode']['onscroll_pixels'],
				'data-onscroll_percentage' => $data['activation_mode']['onscroll_percentage'],
				
				'data-autohidemode' => $data['autohidemode'],
				'data-cursorcolor' => $data['cursorcolor'],
				'data-cursorbordercolor' => $data['cursorbordercolor'],
				'data-cursoropacitymax' => $data['cursoropacitymax'],
				'data-cursorwidth' => $data['cursorwidth'],
				'data-cursorbackground' => $data['cursorbackground'],
				'data-cursorborderradius' => $data['cursorborderradius'],
				'data-unixtime' => current_time('U'),
				
				'data-external_selectors' => $data['external_selectors'],
				'data-insights' => $data['insights'],
				'data-autoclose' => $data['autoclose'],
				'data-autoclose_time' => $data['autoclose_time'],
				'data-redirect' => $data['redirect'],
				'data-redirect_url' => $data['redirect_url'],
				'data-enableTips' => $data['enableTips'],
				'data-enableMessage' => $data['enableMessage'],
				'data-popTop' => $data['popTop'],
			);

			$animator_data = ''; 
			foreach($animator as $key=>$value) {
				$animator_data .= $key.'="'.$value.'" ';
			}
			
			$form_type = $data['form_type'];

			if($data['sideButton'] == 'true') {
				$button_link = "javascript:sppro_loader('$id')";
			}
			else {
				$button_link = '#';
			}
			
		?>
		
		<?php if($data['form_type']=='cf7') { ?>
			<!-- SP Pro <?php echo $formID ?> - Popup Box Curtain Arrangement -->
			<div id="<?php echo $formID; ?>_curtain" class="sppro_curtain manage" data-activationmode="<?php echo $data['activation_mode']['mode']; ?>" data-formid="<?php echo $formID;?>"></div>			
			<div id="<?php echo $formID; ?>" data-numID="<?php echo $id; ?>" data-cf7formid="<?php echo $animator['data-cf7formid']; ?>" class="sppro_popup_box <?php echo 'layout_'.$data['choose_layout'].' '. $data['box_classes'].' '. $data['form_type']; ?> manage">
				<div class="sppro_popup_animator" <?php echo trim($animator_data); ?>></div>		
				
				<?php if( isset($data['popup_heading']) AND !empty($data['popup_heading']) ) { ?>
					<div data-id="sppro_popup_title" class="sppro_popup_title"><?php echo $data['popup_heading']; ?></div>			
				<?php } ?>
				
				<div data-id="sppro_form_container" class="sppro_form_container">
					
					<?php 
						switch($form_type) {
							case 'sp_image': 
								echo '<img src="'.$data['popup_image'].'" alt="'.get_the_title($formID).'" />';
								break; 
								
							default: 
								echo '<div data-id="sppro_popup_description" class="sppro_popup_description">'.do_shortcode($data['cta_text']).'</div>';
						}
						if($data['form_type']=='cf7') {
							if( $data['message'] != '' ) { 
								echo '<div class="sppro_form no-form">'.$data['message'].'</div>'; 
							}
							else {						
								echo do_shortcode( '[contact-form-7 id="' .$data['cf7_id']. '" title="' . '' . '"]'); 
							}
						}
					?>
				</div>
				
				<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
				<?php if($data['activation_mode']['mode']!='forced') { ?>
					<a data-id="sppro_popupBoxClose" class="sppro_popupBoxClose" onClick="sppro_unloader('<?php echo $formID; ?>');">X</a>  
				<?php } ?>
			</div>	
			
			<?php if( $data['side_button_position'] != 'pos_none' ) { ?>
				<a href="<?php echo $button_link; ?>" id="<?php echo $side_btn_id; ?>" data-popupID="<?php echo $formID; ?>" class="sppro_sideEnquiry <?php echo $data['side_button_position']; ?> on_mobile <?php echo $data['side_button_switch']; ?>"><?php echo $data['side_button_text']; ?></a>
			<?php } ?>
			
			<?php } elseif($data['form_type']=='sp_html' OR $data['form_type']=='sp_video' OR $data['form_type']=='sp_maps') { ?>
			<!-- SP Pro <?php echo $formID ?> - Popup Box Curtain Arrangement -->
			<div id="<?php echo $formID; ?>_curtain" class="sppro_curtain manage" data-activationmode="<?php echo $data['activation_mode']['mode']; ?>" data-formid="<?php echo $formID;?>"></div>			
			<div id="<?php echo $formID; ?>" data-numID="<?php echo $id; ?>" data-cf7formid="<?php echo $animator['data-cf7formid']; ?>" class="sppro_popup_box <?php echo 'layout_'.$data['choose_layout'].' '. $data['box_classes'].' '. $data['form_type']; ?> manage">
				<div class="sppro_popup_animator" <?php echo trim($animator_data); ?>></div>		
				
				<?php if( isset($data['popup_heading']) AND !empty($data['popup_heading']) ) { ?>
					<div data-id="sppro_popup_title" class="sppro_popup_title"><?php echo $data['popup_heading']; ?></div>			
				<?php } ?>
				
				<div data-id="sppro_form_container" class="sppro_form_container">
					
					<?php 
						echo '<div data-id="sppro_popup_description" class="sppro_popup_description">'.do_shortcode($data['cta_text']).'</div>';
					?>
				</div>
				
				<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
				<?php if($data['activation_mode']['mode']!='forced') { ?>
					<a data-id="sppro_popupBoxClose" class="sppro_popupBoxClose" onClick="sppro_unloader('<?php echo $formID; ?>');">X</a>  
				<?php } ?>
			</div>	
			
			<?php if( $data['side_button_position'] != 'pos_none' ) { ?>
				<a href="#" id="<?php echo $side_btn_id; ?>" data-popupID="<?php echo $formID; ?>" class="sppro_sideEnquiry <?php echo $data['side_button_position']; ?> on_mobile <?php echo $data['side_button_switch']; ?>"><?php echo $data['side_button_text']; ?></a>
			<?php } ?>
		
		<?php } elseif($data['form_type']=='sp_image') { ?>
			<!-- SP Pro <?php echo $formID ?> - Popup Box Curtain Arrangement -->
			<div id="<?php echo $formID; ?>_curtain" class="sppro_curtain manage" data-activationmode="<?php echo $data['activation_mode']['mode']; ?>" data-formid="<?php echo $formID;?>"></div>			
			<div id="<?php echo $formID; ?>" data-numID="<?php echo $id; ?>" class="<?php echo $formID; ?> sppro_popup_box <?php echo 'layout_'.$data['choose_layout'].' '. $data['box_classes'].' '. $data['form_type']; ?> <?php echo $form_type; ?> manage">  			
				<div class="sppro_popup_animator" <?php echo trim($animator_data); ?>></div>		
				
				<div data-id="sppro_form_container" class="sppro_form_container">					
					<?php 
						if(isset($data['use_link'])) {
							echo '<a href="'.$data['link_url'].'" target="'.$data['link_target'].'">';
								echo '<img src="'.$data['popup_image'].'" height="'.$data['popup_image_height'].'" width="'.$data['popup_image_width'].'" alt="'.get_the_title($formID).'" />';
							echo '</a>';
						}
						else {
							echo '<img src="'.$data['popup_image'].'" alt="'.get_the_title($formID).'" />';
						}
					?>
				</div>
				
				<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
				<?php if($data['activation_mode']['mode']!='forced') { ?>
					<a data-id="sppro_popupBoxClose" class="sppro_popupBoxClose" onClick="sppro_unloader('<?php echo $formID; ?>');">X</a>  
				<?php } ?>
			</div>	
			
			<?php if( $data['side_button_position'] != 'pos_none' ) { ?>
				<a href="<?php echo $button_link; ?>" id="<?php echo $side_btn_id; ?>" data-popupID="<?php echo $formID; ?>" class="sppro_sideEnquiry <?php echo $data['side_button_position']; ?> on_mobile <?php echo $data['side_button_switch']; ?>"><?php echo $data['side_button_text']; ?></a>
			<?php } ?>

		<?php } elseif($data['form_type']=='sp_video' AND $data['form_type']=='sp_maps' AND $data['form_type']=='sp_html') { ?>
			<!-- SP Pro <?php echo $formID ?> - Popup Box Curtain Arrangement -->
			<div id="<?php echo $formID; ?>_curtain" class="sppro_curtain manage" data-activationmode="<?php echo $data['activation_mode']['mode']; ?>" data-formid="<?php echo $formID;?>"></div>			
			<div id="<?php echo $formID; ?>" data-numID="<?php echo $id; ?>" data-cf7formid="<?php echo $animator['data-cf7formid']; ?>" class="sppro_popup_box <?php echo 'layout_'.$data['choose_layout'].' '. $data['box_classes'].' '. $data['form_type']; ?> manage">
				<div class="sppro_popup_animator" <?php echo trim($animator_data); ?>></div>		
				
				<?php if( isset($data['popup_heading']) AND !empty($data['popup_heading']) ) { ?>
					<div data-id="sppro_popup_title" class="sppro_popup_title"><?php echo $data['popup_heading']; ?></div>			
				<?php } ?>
				
				<div data-id="sppro_form_container" class="sppro_form_container">
					<?php 
						echo '<div data-id="sppro_popup_description" class="sppro_popup_description">'.do_shortcode($data['cta_text']).'</div>';
					?>
				</div>
				
				<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
				<?php if($data['activation_mode']['mode']!='forced') { ?>
					<a data-id="sppro_popupBoxClose" class="sppro_popupBoxClose" onClick="sppro_unloader('<?php echo $formID; ?>');">X</a>  
				<?php } ?>
			</div>	
			
			<?php if( $data['side_button_position'] != 'pos_none' ) { ?>
				<a href="<?php echo $button_link; ?>" id="<?php echo $side_btn_id; ?>" data-popupID="<?php echo $formID; ?>" class="sppro_sideEnquiry <?php echo $data['side_button_position']; ?> on_mobile <?php echo $data['side_button_switch']; ?>"><?php echo $data['side_button_text']; ?></a>
			<?php } ?>
		
		<?php } elseif($data['form_type']=='login') { ?>
			<!-- SP Pro <?php echo $formID ?> - Popup Box Curtain Arrangement -->
			<div id="<?php echo $formID; ?>_curtain" class="sppro_curtain manage" data-activationmode="<?php echo $data['activation_mode']['mode']; ?>" data-formid="<?php echo $formID;?>"></div>			
			<div id="<?php echo $formID; ?>" data-numID="<?php echo $id; ?>" class="<?php echo $formID; ?> sppro_popup_box <?php echo 'layout_'.$data['choose_layout'].' '. $data['box_classes'].' '. $data['form_type']; ?> <?php echo $form_type; ?> manage">  			
				<div class="sppro_popup_animator" <?php echo trim($animator_data); ?>></div>		
				
				<?php if( isset($data['popup_heading']) AND !empty($data['popup_heading']) ) { ?>
					<div data-id="sppro_popup_title" class="sppro_popup_title"><?php echo $data['popup_heading']; ?></div>			
				<?php } ?>
				
				<div data-id="sppro_form_container" class="sppro_form_container">
					<div data-id="sppro_popup_description" class="sppro_popup_description"><?php echo do_shortcode($data['cta_text']); ?></div>
					<!-- WordPress Login Form Here -->
					<form id="sppro-login" class="sppro-form" action="login" method="post">
						<div class="sppro-form-row">
							<label for="username">Username</label>
							<input type="text" class="sppro-text username" name="username">
						</div>
						<div class="sppro-form-row">
							<label for="password">Password</label>
							<input type="password" class="sppro-password password" name="password">
						</div>
						
						<input class="sppro-submit sppro_login_button" type="submit" value="Login" name="submit">									
						<span class="sppro_toggleHandle lostpassword-handle">Lost Password?</span>									
						<?php 
							if(function_exists( 'wp_nonce_field'))
								wp_nonce_field('sppro-login-nonce', 'login_security'); 
						?>
						<div class="sppro-status"></div>
					</form>
					
					<form id="sppro-lostpassword" class="sppro-form" action="" method="post" style="display:none;">
						<?php
							if(function_exists('wp_nonce_field'))
								wp_nonce_field('sppro-lostpassword-nonce', 'lostpassword_security');
						?>
						<div class="sppro-form-row">
							<label for="user_name"><?php _e('Username or E-mail:') ?></label>
							<input type="text" name="user_name" id="user_name" class="user_name input" value="<?php echo esc_attr($user_login); ?>" size="20" />									
						</div>
						<?php
						// WP Default Action Hook 
						do_action( 'lostpassword_form' ); ?>
						<input class="sppro-submit sppro_lostpassword_button" type="submit" name="wp-submit" id="wp-submit" value="<?php esc_attr_e('Get New Password'); ?>" />											
						
						<span class="sppro_toggleHandle login-handle">Sign In</span>								
						<div class="sppro-status"></div>
					</form>												
				</div>
				
				<!--<div class="success" style="display: none;">Successfully Submitted ...</div>-->
				<?php if($data['activation_mode']['mode']!='forced') { ?>
					<a data-id="sppro_popupBoxClose" class="sppro_popupBoxClose" onClick="sppro_unloader('<?php echo $formID; ?>');">X</a>  
				<?php } ?>
			</div>	
			
			<?php if( $data['side_button_position'] != 'pos_none' AND !is_user_logged_in() ) { ?>
				<a href="#" id="<?php echo $side_btn_id; ?>" data-popupID="<?php echo $formID; ?>" class="sppro_sideEnquiry <?php echo $data['side_button_position']; ?> on_mobile <?php echo $data['side_button_switch']; ?>"><?php echo $data['side_button_text']; ?></a>
			<?php } else { ?>
				<a href="#" id="<?php echo $side_btn_id; ?>" data-popupID="<?php echo $formID; ?>" class="sppro_sideEnquiry logout_button <?php echo $data['side_button_position']; ?> on_mobile <?php echo $data['side_button_switch']; ?>" data-logoutlink="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>" ><?php echo 'Logout'; ?></a>
			<?php } ?>

		<?php } ?>
		
		
		<?php // sppro_fire_activation_mode_script($data['activation_mode'], $formID); ?>
		<!-- Ends SP Pro <?php echo $formID ?> - Popup Box Curtain Arrangement -->
	
	<?php } //foreach ?>
	
	<?php	
}

/////////////////////////////////////////
// Add CSS Based on Options
/////////////////////////////////////////
function sppro_option_css() {
	
	global $sp_opts; 
	// Custom CSS Code
	$custom_css_code = isset($sp_opts['custom-css-code']) ? $sp_opts['custom-css-code'] : '';
	
	if( !is_admin() ) { 
		$forms = sppro_get_active_forms(); 
		foreach($forms as $form) {
			sppro_print_form_css($form);
		}
		
		if( !empty($custom_css_code) )
			echo '
		<style>
			'.$custom_css_code.'
		</style>'; 
		
	}
}
add_action( 'redux/page/sp_opts/enqueue', 'sppro_addAndOverridePanelCSS' );

/////////////////////////////////////////
// Override Redux Panel CSS (farbtastci)
/////////////////////////////////////////
function sppro_addAndOverridePanelCSS() {
	wp_register_style( 'redux-custom-css', sppro_plugin_url( '/libs/admin/css/redux-custom.css' ), '', time(), 'all' );    
	wp_enqueue_style('redux-custom-css');
}

/////////////////////////////////////////
// Add Scripts for the Popup
/////////////////////////////////////////
function sppro_enqueue_popup_scripts() {
	if ( !is_admin() ) {
		wp_register_style( 'sppro-css', sppro_plugin_url( '/libs/css/styles.css' ) );
		wp_enqueue_style( 'sppro-css' ); 
		wp_register_style( 'sppro-animate', sppro_plugin_url( '/libs/css/animate.css' ) );
		wp_enqueue_style( 'sppro-animate' ); 
		wp_register_script( 'sppro-js', sppro_plugin_url( '/libs/js/custom.js' ), array('jquery'), null, true );
		wp_enqueue_script( 'sppro-js' );

		wp_localize_script( 'sppro-js', 'ajax_login_object', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirecturl' => home_url(),
			'loadingmessage' => __('Sending user info, please wait...'),
			'emptyusername' => __('Please enter username'),
			'emptypassword' => __('Please enter password'),
		));
	}	
}

/**
 * Enqueue Admin Scripts
 * Once used for creating the copy button
 * Copy script is ready 
 */
add_action('admin_enqueue_scripts', 'sppro_enqueue_admin_popup_scripts');
function sppro_enqueue_admin_popup_scripts() {
	if ( is_admin() ) {
		wp_register_script( 'sppro-admin-js', sppro_plugin_url( '/libs/js/custom-admin.js' ), array('jquery'), null, true );
		wp_enqueue_script( 'sppro-admin-js' ); 
	}	
}

/**
 * Check Form Availability
 * @param  int - ID of the CF7 Form
 * @return string - message for admin or front-end user
 * If the Form ID is not available for not valid, return appropriate message
 */
function sppro_check_form_id($cf7_id) {
	
	if( is_user_logged_in()  AND current_user_can('manage_options') )
		$user_is_admin = true; 
	
	$message = '';
	if( empty($cf7_id)) {
		if( isset($user_is_admin) ) { $message = __('No form choosen. Please select a form from <a target="_blank" href="'.admin_url('admin.php?page=sppro_options').'">plugin options</a>.'); }
		else { $message = __('Form is not available. Please visit our contact page.'); }		
	}
	else {
		$post_type = get_post_type($cf7_id);
		if( !absint($cf7_id) OR ($post_type != 'wpcf7_contact_form') OR !is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
			if( isset($user_is_admin) ) { $message = __('Invalid Form ID. Please select a form from <a target="_blank" href="'.admin_url('admin.php?page=sppro_options').'">plugin options</a>.'); }
			else { $message = __('Form is temporarily not available. Please visit our contact page.'); }
		}
	}
	
	return $message;
}

/**
 * Create Cookie (Not ajax)
 * @param  none
 * @return true 
 * It is used by Contact Form 7 mail sent hook to create cookie
 */
//add_action( 'init', 'sppro_set_cookie' );
function sppro_set_cookie($id, $value='closed', $exp=1) {
	
	$name = '#sppro_popup_box-'.$id; 
	
	global $sp_opts; 
	$cf7_id = isset($sp_opts['form-id'])? $sp_opts['form-id'] : '';
	
	if($id==$cf7_id) 
		$name = '#sppro_popup_box_test_cookie'; 
	
	$value = 'submitted'; 
	$days = $exp; 
	
	$created = setcookie( $name, $value, 1, '/' );
	return $created; 
}

/**
 * sppro_decide_dollar_show
 * @param  none
 * @return $show array(show=boolean, global=boolean)
 */
function sppro_decide_dollar_show() {
	
	global $sp_opts, $post, $short_forms; 
	$current_post_id = is_object($post) ? strval($post->ID) : '';
	$show = $global = false; 
	
	if( ! isset( $sp_opts['plugin_state'] ) ) {
		// Temporary Fix For First Installation
		// Error Notice: Plugin State Variable Not Available
		// Is expected to be done in Activation Hook 
		$sp_opts['plugin_state'] = 1;
		$sp_opts['where_to_show'] = 'everywhere';
	}	
	
	if( $sp_opts['plugin_state'] == 1 ) {
		
		$page_ids = isset($sp_opts['choose_pages']) ? $sp_opts['choose_pages'] : '';
		$page_ids = is_array($page_ids) ? $page_ids : array($page_ids);
		
		switch($sp_opts['where_to_show']) {			
			case 'everywhere': $show = true; $global = true; break; 
			case 'onselected': 
				if( isset($page_ids) AND is_array($page_ids) AND in_array($current_post_id, $page_ids)) {
					$show = true; 
					$global = true; 
				}
				break; 
			case 'notonselected': 
				if( isset($page_ids) AND is_array($page_ids) AND !in_array($current_post_id, $page_ids)) {
					$show = true; 
					$global = true; 
				}
				break; 
			default: break; 
		}
		
		$sppro_pages = get_option('sppro-pages');
		
		$custom =  get_post_meta( $current_post_id, '_sppro_page_options', true); 		
		// Check if Multiple Popups Added to This Page
		if( isset($custom['_sppro_meta_add_multiple']) AND $custom['_sppro_meta_add_multiple'] ) {
			$add_multiple_ids = $custom['_sppro_meta_add_multiple'];
			if( isset($custom['_sppro_meta_multiple_ids']) AND $custom['_sppro_meta_multiple_ids'] ) {
				$multiple_ids = $custom['_sppro_meta_multiple_ids'];				
			}
		}
		
		if(isset($multiple_ids)) {
			if(is_array($sppro_pages)) {
				$sppro_pages = array_merge($sppro_pages, $multiple_ids);
			} else {
				$sppro_pages = $multiple_ids; 
			}
		}
		
		if( is_array($sppro_pages) AND in_array($current_post_id, $sppro_pages) ) {
			$show = true; 
		}	
		
	}
	
	$return = array('show'=>$show,'global'=>$global);
	
	return $return; 
}

/**
 * sppro_get_active_forms
 * @param  none
 * @return $forms - global plus all active forms
 */
function sppro_get_active_forms() {
	
	global $post, $short_forms, $sp_opts; 
	$show = sppro_decide_dollar_show(); 

	if(!is_object($post)) {
		$post = (object) array( 'ID' => '');
	}
	
	if(is_404() OR is_search() OR is_tag() OR is_category()) {
		$is_post = false; 
		$current_post_id = 0;
		$current_post_type = 'null'; 
	}
	else {		
		$is_post = true; 
		$current_post_id = $post->ID;
		//var_dump($post->ID);
		$current_post_type = get_post_type($current_post_id); 
	}
	
	$multiple_ids = array(); 
	
	$custom =  get_post_meta( $current_post_id, '_sppro_page_options', true); 		
	// Check if Multiple Popups Added to This Page
	if( isset($custom['_sppro_meta_add_multiple']) AND $custom['_sppro_meta_add_multiple'] ) {
		$add_multiple_ids = $custom['_sppro_meta_add_multiple'];
		if( isset($custom['_sppro_meta_multiple_ids']) AND $custom['_sppro_meta_multiple_ids'] ) {
			$multiple_ids = $custom['_sppro_meta_multiple_ids'];				
		}
	}
	
	$forms = array();
	
	if(is_array($short_forms)) {
		$short_forms = array_merge($short_forms, $multiple_ids);
	}
	elseif(is_array($multiple_ids)) {
		$short_forms = $multiple_ids;
	} 
	else $short_forms = array();
	
	// Prepare Multiple forms data
	$args = array(
		'post_type' => 'sppro_forms',
		'posts_per_page' => -1,
	);
	$query = new WP_Query($args); 
	if($query->have_posts()) {		
		while($query->have_posts()) { 
			$query->the_post();
			$form_id = get_the_ID();
			$form_options =  get_post_meta( $form_id, '_sppro_form_options', true); 
			$where_to_show = $form_options['_sppro_forms_meta_where_to_show']; 
			$fieldset_wts = isset($form_options['_sppro_forms_meta_fieldset_where_to_show']) ? $form_options['_sppro_forms_meta_fieldset_where_to_show'] : '';
			
			switch($where_to_show) {
				case 'everywhere':
					$short_forms[] = $form_id; 
					break; 
					
				case 'onselected':						
					if($is_post) {
						
						if(isset($fieldset_wts['show_on_pages'])) {	
							$which_pages = (!isset($fieldset_wts['choose_pages']) OR !is_array($fieldset_wts['choose_pages'])) ? array() : $fieldset_wts['choose_pages'];
							switch($fieldset_wts['pages_choices']) {
								case 'everywhere' : 
									if('page'==$current_post_type) { $short_forms[] = $form_id; }
									break; 
								case 'onselected' : 
									if(in_array($current_post_id, $which_pages)) { $short_forms[] = $form_id; }
									break; 
								case 'notonselected' : 
									if(!in_array($current_post_id, $which_pages)) { $short_forms[] = $form_id; }
									break; 
							}
						}
						
						if(isset($fieldset_wts['show_on_posts'])) {						
							$which_posts = (!isset($fieldset_wts['choose_posts']) OR !is_array($fieldset_wts['choose_posts'])) ? array() : $fieldset_wts['choose_posts']; 
							switch($fieldset_wts['posts_choices']) {							
								case 'everywhere' : 
									if('post'==$current_post_type) { $short_forms[] = $form_id; }
									break; 
								case 'onselected' : 
									if(in_array($current_post_id, $which_posts)) { $short_forms[] = $form_id; }
									break; 
								case 'notonselected' : 
									if(!in_array($current_post_id, $which_posts)) { $short_forms[] = $form_id; }
									break; 
							}
						}
					
					}										
					elseif(is_category()) {
						if(isset($fieldset_wts['show_on_categories'])) {
							$categories_choices = $fieldset_wts['categories_choices']; 
							$which_categories = (!isset($fieldset_wts['choose_categories']) OR !is_array($fieldset_wts['choose_categories'])) ? array() : $fieldset_wts['choose_categories']; 
							if($categories_choices=='everywhere' OR 
								($categories_choices=='onselected' && in_array($cat_id, $which_categories)) OR 
								($categories_choices=='notonselected' && !in_array($cat_id, $which_categories))
							) {
								$short_forms[] = $form_id; 
							}
						}
					}
					elseif(is_tag()) {
						if(isset($fieldset_wts['show_on_tags'])) {								
							$tags_choices = $fieldset_wts['tags_choices']; 
							$which_tags = (!isset($fieldset_wts['choose_tags']) OR !is_array($fieldset_wts['choose_tags'])) ? array() : $fieldset_wts['choose_tags']; 
							if($tags_choices=='everywhere' OR 
								($tags_choices=='onselected' && in_array($tag_id, $which_tags)) OR 
								($tags_choices=='notonselected' && !in_array($tag_id, $which_tags))
							) {
								$short_forms[] = $form_id; 
							}
						}
					}
					elseif(is_404() && isset($fieldset_wts['show_on_404_page'])) {
						$debug_info = 'Debug:  404'; 
						$short_forms[] = $form_id; 
					}
					elseif(is_search() && isset($fieldset_wts['show_on_search_pages'])) {
						$debug_info = 'Debug:  Search'; 
						$short_forms[] = $form_id; 
					}
					
					break; 
				default: 
					$short_forms[] = 'not-good'; 
			}
		}
	}
	
	wp_reset_postdata(); 
	
	//echo 'short: '; print_r($short_forms); 
	
	if(is_array($short_forms)) {
		foreach($short_forms as $form) {
			$forms['sppro_popup_box-'.$form] = array(
				'btn_position' => 'pos_right',
				'type' => 'multiple',
				'id' => $form,
				'cf7formid' => '',				
			);
			
			$custom =  get_post_meta( $form, '_sppro_form_options', true);			
			//if( isset($custom['_sppro_forms_meta_override']) AND $custom['_sppro_forms_meta_override'] ) {
				if( isset($custom['_sppro_forms_meta_form_id']) AND !empty($custom['_sppro_forms_meta_form_id']) )
					$forms['sppro_popup_box-'.$form]['cf7formid'] = $custom['_sppro_forms_meta_form_id'];
				}
			//}
	}
	
	$global_cf7_form = isset($sp_opts['form-id'])? $sp_opts['form-id'] : '11';
	if( $show['global'] ) { 
		$forms['sppro_popup_box'] = array(
			'btn_position' => 'pos_right', 
			'type' => 'global', 
			'cf7formid' => $global_cf7_form,
		);
	}
	
	/*$popups = SPPRO_Forms::find();
	foreach( $popups as $pop ) {
		$p_id = $pop->id();
		$forms['sppro_popup_box-'.$p_id] = array(
			'btn_position' => 'pos_right',
			'type' => 'multiple',
			'id' => $p_id,
		);
	}*/
	
	return $forms; 
}

/**
 * sppro_print_form_css($form);
 * @param  $form - a form from short_forms
 * @return null
 * echo - the appropriate CSS for forms
 */
function sppro_print_form_css($form) {
	
	global $sp_opts;
	
	// Set defaults
	$use_custom_width_height = false; // flag to use custom height width
	$popup_height_width_styles = ''; // empty CSS styles holder for height/width
	
	$custom_curtain = $sp_opts['curtain-background'];
	$color_scheme = $sp_opts['choose-color-scheme'];
	$custom_color_scheme = $sp_opts['custom-theme-color'];	
	$custom_text_color = $sp_opts['custom-text-color'];
	$custom_form_background_color = $sp_opts['custom-form-background-color'];		
	
	$popup_corners = $sp_opts['popup-corners'];	
	$custom_popup_corners = isset($sp_opts['custom-popup-corners']) ? $sp_opts['custom-popup-corners'] : '';
	
	$custom_popup_layout = $sp_opts['custom-popup-layout'];
	$popup_height = $sp_opts['popup-height'];
	$popup_width = $sp_opts['popup-width'];
			
	$heading_typography = $sp_opts['heading-typography'];  		
	$cta_typography = $sp_opts['cta-typography'];
		
	// Side Button
	$side_button_scheme = $sp_opts['choose-side-button'];
	$side_button_background = $sp_opts['side-button-background']['background-color'];
	$side_button_text_color = '';
	$side_button_typography = $sp_opts['side-button-typography'];
	
	// Submit Button
	$submit_button_scheme = $sp_opts['choose-submit-button'];
	$submit_button_background = $sp_opts['submit-button-background']['background-color'];	
	$submit_button_text_color = $sp_opts['submit-button-typography']['color'];	
	$submit_button_typography = $sp_opts['submit-button-typography'];
	//$submit_button_border = $sp_opts['submit-button-border'];
  	
	// Custom CSS Code
	$custom_css_code = isset($sp_opts['custom-css-code']) ? $sp_opts['custom-css-code'] : '';
	
	///////////////////////////////////////////
	// Set Default values from Global settings
	///////////////////////////////////////////
	$heading_color = $heading_typography['color'];
	$cta_color = $cta_typography['color'];	
	
	///////////////////////////////////////////
	// Set Submit Button Styles - Parameters
	///////////////////////////////////////////
	if( $submit_button_scheme == 'inherit_from_theme' ) {		
		$submit_bg = '';
		$submit_typo_color = '';
	}	
	elseif( $submit_button_scheme == 'inherit_from_color_scheme' ) {		
		$submit_bg = $custom_color_scheme;
		$submit_typo_color = $custom_text_color;
	}
	elseif ( $submit_button_scheme == 'custom' ) {
		$submit_bg = $submit_button_background;
		$submit_typo_color = $submit_button_text_color;
	}
	
	// Override Settings For Multiple Popup - Not Global Popup
	if( $form['type']!='global' ) {
		
		$custom =  get_post_meta( $form['id'], '_sppro_form_options', true); 
		$form_type =  $custom['_sppro_forms_meta_form_type']; 
		$custom_colors = $custom['_sppro_forms_meta_fieldset_theme_colors'];
		$custom_fieldset_layout = $custom['_sppro_forms_meta_fieldset_popup_layout'];
		$custom_fieldset_sp_image = isset($custom['_sppro_forms_meta_fieldset_sp_image']) ? $custom['_sppro_forms_meta_fieldset_sp_image']: '';
		
		// Check if Override is choosen from Page Options
		//if( isset($custom['_sppro_forms_meta_override']) AND $custom['_sppro_forms_meta_override'] ) {
			
			if( isset($custom_colors['_sppro_forms_meta_custom_curtain']) AND !empty($custom_colors['_sppro_forms_meta_custom_curtain']) )
				$custom_curtain = $custom_colors['_sppro_forms_meta_custom_curtain']; 
			
			if( isset($custom_colors['_sppro_forms_meta_color_scheme']) AND !empty($custom_colors['_sppro_forms_meta_color_scheme']) )
				$color_scheme = $custom_colors['_sppro_forms_meta_color_scheme']; 
			
			if( isset($custom_colors['_sppro_forms_meta_custom_theme_color']) AND !empty($custom_colors['_sppro_forms_meta_custom_theme_color']) )
				$custom_color_scheme = $custom_colors['_sppro_forms_meta_custom_theme_color'];
			if( isset($custom_colors['_sppro_forms_meta_custom_background_color']) AND !empty($custom_colors['_sppro_forms_meta_custom_background_color']) )
				$custom_form_background_color  = $custom_colors['_sppro_forms_meta_custom_background_color'];
			
			if( isset($custom_colors['_sppro_forms_meta_custom_text_color']) AND !empty($custom_colors['_sppro_forms_meta_custom_text_color']) )
				$custom_text_color = $custom_colors['_sppro_forms_meta_custom_text_color'];						
			if( isset($custom_colors['_sppro_forms_meta_custom_cta_color']) AND !empty($custom_colors['_sppro_forms_meta_custom_cta_color']) )
				$custom_cta_color = $custom_colors['_sppro_forms_meta_custom_cta_color'];			
			
			if( isset($custom_colors['_sppro_forms_meta_submit_button_scheme']) AND !empty($custom_colors['_sppro_forms_meta_submit_button_scheme']) )
				$submit_button_scheme = $custom_colors['_sppro_forms_meta_submit_button_scheme'];
			if($submit_button_scheme=='custom') {
				// Submit Button Overrided in Multiple Popup
				if( isset($custom_colors['_sppro_forms_meta_submit_button_background_color']) )
					$submit_bg = $custom_colors['_sppro_forms_meta_submit_button_background_color'];			
				if( isset($custom_colors['_sppro_forms_meta_submit_button_text_color']) )
					$submit_typo_color = $custom_colors['_sppro_forms_meta_submit_button_text_color'];			
			}
			
			if( isset($custom_colors['_sppro_forms_meta_side_button_scheme']) AND !empty($custom_colors['_sppro_forms_meta_side_button_scheme']) )
				$side_button_scheme = $custom_colors['_sppro_forms_meta_side_button_scheme'];	
			if($side_button_scheme=='custom') {
				// Side Button Overrided in Multiple Popup
				if( isset($custom_colors['_sppro_forms_meta_side_button_background_color']) AND !empty($custom_colors['_sppro_forms_meta_side_button_background_color']) )
					$side_button_background = $custom_colors['_sppro_forms_meta_side_button_background_color'];			
				if( isset($custom_colors['_sppro_forms_meta_side_button_text_color']) AND !empty($custom_colors['_sppro_forms_meta_side_button_text_color']) )
					$side_button_text_color = $custom_colors['_sppro_forms_meta_side_button_text_color'];			
			}
			
			// Override Height and Width for Popups
			$user_choice_height_width = $custom_fieldset_layout['_sppro_forms_meta_change_height_and_width'];			
			if( $user_choice_height_width=='change' ) {				
				if( isset($custom_fieldset_layout['_sppro_forms_meta_popup_width']) AND !empty($custom_fieldset_layout['_sppro_forms_meta_popup_width']) )
					$popup_width = array('width'=>$custom_fieldset_layout['_sppro_forms_meta_popup_width']);
				if( isset($custom_fieldset_layout['_sppro_forms_meta_popup_height']) AND !empty($custom_fieldset_layout['_sppro_forms_meta_popup_height']) )
					$popup_height = array('height'=>$custom_fieldset_layout['_sppro_forms_meta_popup_height']);
			}
			
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_width']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_width'])) {
				$popup_width = $custom_fieldset_sp_image['_sppro_forms_meta_popup_image_width']; 
			}
			if(isset($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_height']) AND !empty($custom_fieldset_sp_image['_sppro_forms_meta_popup_image_height'])) {
				$popup_height = $custom_fieldset_sp_image['_sppro_forms_meta_popup_image_height']; 
			}

			$curtain_styles = '';

			$form_id = '-'.$form['id'];

			$theme_colors = sppro_get_theme_colors_values($color_scheme, $custom_color_scheme, $custom_text_color, $custom_form_background_color, $custom_curtain);

			$popup_border = sppro_get_popup_border_values($popup_corners);
			
			$body_after_styles = '
				body::after { 
					position:absolute; width:0; height:0; overflow:hidden; z-index:-1;
					content: ';
	    
			if(isset($theme_colors['custom-curtain-background']['media'])) {
				$custom_curtain_rgba = $theme_colors['custom-curtain-background'];
				unset($custom_curtain_rgba['media']);
				foreach($custom_curtain_rgba as $property=>$value) {
					if(isset($value) AND !empty($value)) {
						if($property=='background-image') {
							$curtain_styles .= $property .' : url("'. $value .'");';
							$body_after_styles .= 'url("'.$value.'") ';
						}
						else 
							$curtain_styles .= $property .' : '. $value .';';
					}
				}
			}
			else {
				$custom_curtain_rgba = $theme_colors['custom-curtain-background'];
				foreach($custom_curtain_rgba as $property=>$value) {
					if(isset($value) AND !empty($value)) {
						if($property=='image') {
							$curtain_styles .= 'background-'.$property .' : url("'. $value .'");';
							$body_after_styles .= 'url("'.$value.'") ';
						}
						else 
							$curtain_styles .= 'background-'.$property .' : '. $value .';';					
					}
				}
			}
			
			$body_after_styles .= '; }';
			
			if ($form_type=='sp_image') {
				echo '<style>';
				echo $body_after_styles.'
				div#sppro_popup_box'.$form_id.'_curtain {'.$curtain_styles.'}
				#sppro_popup_box'.$form_id.' {
					background: '.$theme_colors['main-background-color'].';
					border-bottom: 5px solid '. $theme_colors['main-color'].';
					border-radius: '.$popup_border['radius'].';				
					'.$popup_height_width_styles.'
				}';
				echo ' a#sppro_sideEnquiry'.$form_id.':hover {
					color: '.$theme_colors['main-text-color'].';  
				}
				#sppro_popup_box'.$form_id.' .sppro_popupBoxClose {
					color: '.$theme_colors['main-text-color'].';  
				}
				#sppro_popup_box'.$form_id.' .sppro_popupBoxClose:hover {
					color: '.$theme_colors['main-color'].';
				}
				a#sppro_sideEnquiry'.$form_id.' {
					background: '.$side_button_background.';
					color: '.$side_button_typography['color'].';
					font-family: '.$side_button_typography['font-family'].';
					font-size: '.$side_button_typography['font-size'].';
					font-weight: '.$side_button_typography['font-weight'].';
					line-height: '.$side_button_typography['line-height'].';
				}';
				if(!empty($side_button['background-color'])) {
					echo '
					a#sppro_sideEnquiry'.$form_id.' {
						background: '.$side_button['background-color'].';
						color: '.$side_button['text-color'].';
					}
					a#sppro_sideEnquiry'.$form_id.':hover {
						color: '.$side_button['text-color'].';
					}';
				}
				echo '</style>';
			}
			
		//  }		
	}
	
	// Get The Main Colors from the function
	$theme_colors = sppro_get_theme_colors_values($color_scheme, $custom_color_scheme, $custom_text_color, $custom_form_background_color, $custom_curtain);			
	// echo '<div style="width:400px;margin:40px auto;">'; var_dump($theme_colors);  echo '</div>';
	// Get The Border Options
	$popup_border = sppro_get_popup_border_values($popup_corners);
	// Get Side Button Options
	$side_button = sppro_get_side_button_values($side_button_scheme, $side_button_background, $side_button_text_color, $theme_colors);
	// Get Submit Button Options	
	$submit_button = sppro_get_submit_button_values($submit_button_scheme, $submit_bg, $submit_typo_color, $theme_colors);
	
	if($color_scheme=='custom') {
		if(isset($custom_cta_color)) { $cta_color = $custom_cta_color; }
		if(isset($custom_text_color)) { $heading_color = $custom_text_color; }
	}
	
	?>
	<?php 
		$curtain_styles = '';
		$body_after_styles = '
			body::after { 
				position:absolute; width:0; height:0; overflow:hidden; z-index:-1;
				content: ';
    
		if(isset($theme_colors['custom-curtain-background']['media'])) {
			$custom_curtain_rgba = $theme_colors['custom-curtain-background'];
			unset($custom_curtain_rgba['media']);
			foreach($custom_curtain_rgba as $property=>$value) {
				if(isset($value) AND !empty($value)) {
					if($property=='background-image') {
						$curtain_styles .= $property .' : url("'. $value .'");';
						$body_after_styles .= 'url("'.$value.'") ';
					}
					else 
						$curtain_styles .= $property .' : '. $value .';';
				}
			}
		}
		else {
			$custom_curtain_rgba = $theme_colors['custom-curtain-background'];
			foreach($custom_curtain_rgba as $property=>$value) {
				if(isset($value) AND !empty($value)) {
					if($property=='image') {
						$curtain_styles .= 'background-'.$property .' : url("'. $value .'");';
						$body_after_styles .= 'url("'.$value.'") ';
					}
					else 
						$curtain_styles .= 'background-'.$property .' : '. $value .';';					
				}
			}
		}
		
		$body_after_styles .= '; }';
		
		//print_r($custom_curtain_rgba);	print_r($curtain_styles); //print_r($heading_typography);
		/////////////////////////////////////////////////////
		// Do different things for global and multiple popups
		/////////////////////////////////////////////////////
		if($form['type']=='global') {			
			$form_id = ''; // global form empty form_id
			$css_comment = 'CSS for Global Form'; // css comment for global
			
			// Check if User wants to use Custom Height and Width
			// And set $use_custom_width_height to true
			if($custom_popup_layout=='change')
				$use_custom_width_height = true; 
		}
		else {
			$form_id = '-'.$form['id']; // set form ID for multiple popup
			$css_comment = 'CSS for Popup ID: '.$form['id'];  // CSS comment
			
			// Check if User wants to use Custom Height and Width
			// And set $use_custom_width_height to true
			if(isset($user_choice_height_width) AND in_array($user_choice_height_width, array('global','change')))
				$use_custom_width_height = true; 
			
			if($form_type=='sp_image') {
				$use_custom_width_height = true; 

				$image_id = $custom_fieldset_sp_image['_sppro_forms_meta_popup_image']; 
				$popup_image = wp_get_attachment_image_src($image_id, 'full');
				//echo '<div style="width:400px;margin:40px auto;">'; var_dump($popup_image);  echo '</div>';
				$popup_image_width = $popup_image[1]; 
				$popup_image_height = $popup_image[2]; 
				
				$popup_width = !is_array($popup_width) ? array('width'=> $popup_width) : array('width'=> $popup_image_width . 'px'); 
				$popup_height = !is_array($popup_height) ? array('height'=> $popup_height) : array('height'=> $popup_image_height . 'px'); 

				
			}
		}

		$box_background_image = (isset($custom_form_background_color['image']) AND !empty($custom_form_background_color['image']))  ? $custom_form_background_color['image'] : ''; 
		$box_background_position = (isset($custom_form_background_color['position']) AND !empty($custom_form_background_color['position'])) ? $custom_form_background_color['position'] : ''; 
		$box_background_size = (isset($custom_form_background_color['size']) AND !empty($custom_form_background_color['size'])) ? $custom_form_background_color['size'] : 'cover'; 
		$box_background_repeat = (isset($custom_form_background_color['repeat']) AND !empty($custom_form_background_color['repeat'])) ? $custom_form_background_color['repeat'] : ''; 
		$box_background_media = (isset($custom_form_background_color['media']) AND !empty($custom_form_background_color['media'])) ? $custom_form_background_color['media'] : ''; 
		$box_background_color = (isset($custom_form_background_color['color']) AND !empty($custom_form_background_color['color'])) ? $custom_form_background_color['color'] : ''; 
		$box_background_attachment = (isset($custom_form_background_color['attachment']) AND !empty($custom_form_background_color['attachment'])) ? $custom_form_background_color['attachment'] : ''; 
		//$form_options =  get_post_meta( $form_id, '_sppro_form_options', true); 
		//$box_background = $form_options["_sppro_forms_meta_fieldset_theme_colors"]["_sppro_forms_meta_custom_background_color"]["color"];

		$image_background = $box_background_color.' url("'.$box_background_image.'") '.$box_background_repeat.' '.$box_background_position.' / '.$box_background_size; 
		$box_background = !empty($box_background_image) ? $image_background : $theme_colors['main-background-color'];
		$header_background = !empty($box_background_image) ? 'transparent' : $theme_colors['main-color'];
		$border_bottom = !empty($box_background_image) ? '' : '5px solid '.$theme_colors['main-color'];
		
		// Create styles for Height and width if flag is true
		if($use_custom_width_height) {
			$popup_height_width_styles = '
				height: '.$popup_height['height'].';
				width: '.$popup_width['width'].';				
				max-height: 90%;
				max-width: 90%;
			';
		}
		
		echo '
		<!--' .$css_comment. '-->'; 
		echo '<style>';
		echo $body_after_styles.'
			div#sppro_popup_box'.$form_id.'_curtain {'.$curtain_styles.'}
			#sppro_popup_box'.$form_id.' {
				background: '.$box_background.';
				border-radius: '.$popup_border['radius'].';				
				'.$popup_height_width_styles.'
				border-bottom: '.$border_bottom.' ;
			}';
		echo '
			#sppro_popup_box'.$form_id.' .sppro_popup_title,
			#sppro_popup_box'.$form_id.' div.wpcf7-response-output,
			a#sppro_sideEnquiry'.$form_id.',
			#sppro_popup_box'.$form_id.' .sppro-status,
			#sppro_popup_box'.$form_id.' .sppro-status p {
				background-color: '.$header_background.';
				color: '.$theme_colors['main-text-color'].';  
			}
			a#sppro_sideEnquiry'.$form_id.':hover {
				color: '.$theme_colors['main-text-color'].';  
			}';
			
		// This style seems unnecessary
		echo '			
			#sppro_popup_box'.$form_id.' .sppro_popup_description {  
				color: #959595;  
			}';
		
		// Styles for Close Icon X
		echo '	
			#sppro_popup_box'.$form_id.' .sppro_popupBoxClose {
				color: '.$theme_colors['main-text-color'].';  
			}
			#sppro_popup_box'.$form_id.' .sppro_popupBoxClose:hover {
				color: '.$theme_colors['main-color'].';
			}
			#sppro_popup_box'.$form_id.' .sppro_popupBoxClose.closeIcon-circle {
				background: '.$theme_colors['main-color'].';
				color: '.$theme_colors['main-text-color'].';  
			}
			#sppro_popup_box'.$form_id.' .sppro_popupBoxClose.closeIcon-circle:hover {
				color: '.$theme_colors['main-text-color'].';  
			}';
		
		// Styles: Ajax Loader
		echo '
			#sppro_popup_box'.$form_id.' div.wpcf7 img.ajax-loader,
			#sppro_popup_box'.$form_id.' div.wpcf7 span.ajax-loader.is-active {
				box-shadow: 0 0 5px 1px '. $theme_colors['main-color'].';
			}';		
		
		$typography_styles = '#sppro_popup_box'.$form_id.' .sppro_popup_title {';
			$typography_styles .= '
				color: '.$heading_color.';
				font-family: '.$heading_typography['font-family'].';
				font-size: '.$heading_typography['font-size'].';
				font-weight: '.$heading_typography['font-weight'].';
				line-height: '.$heading_typography['line-height'].';
				font-style: '.$heading_typography['font-style'].';';
				
				if(!isset($heading_typography['text-align']) ) {
					$typography_styles .= 'text-align: '.$heading_typography['text-align'].';';
				}
		$typography_styles .= '}';
		
		$typography_styles .= '
			#sppro_popup_box'.$form_id.' p, 
			#sppro_popup_box'.$form_id.' label {
				color: '.$cta_color.';
			}
			#sppro_popup_box'.$form_id.' .sppro_popup_description {
				color: '.$cta_color.';
				font-family: '.$cta_typography['font-family'].';
				font-size: '.$cta_typography['font-size'].';
				font-weight: '.$cta_typography['font-weight'].';
				line-height: '.$cta_typography['line-height'].';
				text-align: '.$cta_typography['text-align'].';
			}
			a#sppro_sideEnquiry'.$form_id.' {
				background: '.$side_button_background.';
				color: '.$side_button_typography['color'].';
				font-family: '.$side_button_typography['font-family'].';
				font-size: '.$side_button_typography['font-size'].';
				font-weight: '.$side_button_typography['font-weight'].';
				line-height: '.$side_button_typography['line-height'].';
			}
			a#sppro_sideEnquiry'.$form_id.':hover {
				color: '.$side_button_typography['color'].';
			}
			#sppro_popup_box'.$form_id.' .wpcf7-form-control.wpcf7-submit, 
			#sppro_popup_box'.$form_id.' .sppro-submit {				
				color: '.$submit_button_typography['color'].';
				font-family: '.$submit_button_typography['font-family'].';
				font-size: '.$submit_button_typography['font-size'].';
				font-weight: '.$submit_button_typography['font-weight'].';
				line-height: '.$submit_button_typography['line-height'].';
			}';
		
			echo $typography_styles; 
		
		// Styles: Side Button
		if(!empty($side_button['background-color'])) {
			echo '
			a#sppro_sideEnquiry'.$form_id.' {
				background: '.$side_button['background-color'].';
				color: '.$side_button['text-color'].';
			}
			a#sppro_sideEnquiry'.$form_id.':hover {
				color: '.$side_button['text-color'].';
			}';
		}
		
		// Styles: Submit Button - part 1
		if( $submit_button['background-color'] != '' ) {
			echo 
			'#sppro_popup_box'.$form_id.' input.wpcf7-form-control.wpcf7-submit,
			#sppro_popup_box'.$form_id.' .sppro-submit {				
				background: '.$submit_button['background-color'].';
				color: '.$submit_button['text-color'].';
				letter-spacing: 1px;
				padding: 10px 15px;  
				text-align: center;
				border: 0; 
				box-shadow: none;   
			}';
		}	
		if ($sp_opts['enableTips'] == 1) {
			echo '
				.sppro_popup_box input:focus span.wpcf7-not-valid-tip, 
				.sppro_popup_box input[type="*"]:focus span.wpcf7-not-valid-tip, 
				.sppro_popup_box input[type="text"]:focus span.wpcf7-not-valid-tip, 
				.sppro_popup_box input[type="email"]:focus span.wpcf7-not-valid-tip, 
				.sppro_popup_box input[type="tel"]:focus span.wpcf7-not-valid-tip, 
				.sppro_popup_box input[type="date"]:focus span.wpcf7-not-valid-tip, 
				.sppro_popup_box input[type="hidden"]:focus span.wpcf7-not-valid-tip, .sppro_popup_box textarea:focus span.wpcf7-not-valid-tip, .sppro_popup_box select:focus span.wpcf7-not-valid-tip {
					display: none !important;
				}
				.sppro_popup_box span.wpcf7-not-valid-tip {
				  font-size: 0.85em;
				  text-align: left;   
				}
				.sppro_popup_box span.wpcf7-not-valid-tip {
				    background: #ff0033;
				    color: #fff;
				    line-height: 18px;
				    right: 1;
				    text-align: center;
				    top: 1px;
				    width: auto;
				    height: auto;
				    padding: 8px 8px;
				    transition: all 0.3s ease-in;
				    font-family: Open Sans;
				    position: absolute;
				}
				.sppro_popup_box textarea + span.wpcf7-not-valid-tip {
					
				}
				.sppro_popup_box select + span.wpcf7-not-valid-tip {
					
				}
				.sppro_popup_box .wpcf7-checkbox + span.wpcf7-not-valid-tip, 
				.sppro_popup_box .wpcf7-radio + span.wpcf7-not-valid-tip {
					
				}
				.sppro_popup_box .spam-quiz .wpcf7-not-valid-tip {
				  position: relative;
				  top: 0;
				  left: 0;
				  color: #A31B1F;
				}
			';
		}

		if ($sp_opts['enableMessage'] == 1) {
			echo '
				#sppro_popup_box'.$form_id.' div.wpcf7-response-output {
				    width: 50%;
				    position: absolute;
				    left: 25%;
				    padding: 10px;
				    top: 50%;
				    bottom: 30%;
				    border: 2px solid;
				}
				#sppro_popup_box'.$form_id.' div.wpcf7-response-output:before {
				    content: "X";
				    top: 0;
				    right: 0;
				    position: absolute;
				    padding: 2px;
				    cursor: pointer;
				}
			';
		}

		echo '</style>';
}

/**
 * sppro_print_form_css($form);
 * @param  $form - a form from short_forms
 * @return null
 * echo - the appropriate CSS for forms
 */
function sppro_get_box_classes($data) {
	
	$notcenter = array('left_bottom', 'right_bottom', 'left_top', 'right_top');
	
	// Set Extra Box Class to variable
	$data['box_classes'] = ''; 
	if( $data['choose_layout'] == 'corner-fixed' )
		$data['box_classes'] .= $data['fixed_side'];
	
	if( $data['choose_layout'] == 'widgetized' ) {
		$data['box_classes'] .= $data['widgetized_popup'];
		if(in_array($data['widgetized_popup'], $notcenter)) {
			$data['box_classes'] .= ' sp-nocenter';
		}
	}
	
	return $data; 
}

add_action('wp_ajax_get_popup_list', 'get_popup_list');
function get_popup_list(){
	$reponse = array();
	$response['response'] = array(); 
	$post_lists = array();
    
	$args = array(
		'post_type'     => array( 'sppro_forms' ),
		'post_status'   => 'any',
		'order' => 'DESC',
		'posts_per_page' => -1,
	);		
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$count = 0; 
		while ( $query->have_posts() ) { 
			$query->the_post(); $count++; 					
			$custom = get_post_custom(); 
			$select_value = (string)get_the_ID(); //popup id
			$select_name = get_the_title().' ('.$select_value.')'; // popup title
			$response['response'][] = array('text'=>$select_name,'value'=>$select_value);
		}
	}	
	wp_reset_query(); 
	wp_reset_postdata(); 	
	$response['response'] = json_encode($response['response']);
    header( "Content-Type: application/json" );
    echo json_encode($response);
    exit();
}

add_action('init', 'sppro_forms_side_context_box');
function sppro_forms_side_context_box() {
	add_action( 'post_submitbox_misc_actions', 'sppro_forms_side_context_box_function');
}

function sppro_forms_side_context_box_function() {
	if('sppro_forms'==get_post_type()) {
		echo '<style>
			#minor-publishing-actions, 
			.misc-pub-section.misc-pub-post-status,
			.misc-pub-section.misc-pub-visibility,
			.misc-pub-section.curtime.misc-pub-curtime {
				display: none; 
			}
			#shortcode-copy{
				text-decoration: underline; 
				color: blue; 
				cursor: pointer; 
			}</style>';
		$short = "[sppro id='".get_the_ID()."'][/sppro]";	
		$shortcode = '<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="'.$short.'" class="large-text code" /></span>';
		?>
		<div class="misc-pub-section shortcode-sppro" style="font-weight:bold;">
			Shortcode:<br/> <span id="shortcode-string"><?php echo $shortcode; ?></span> 
			<!--<span onClick="sppro_copyToClipboard('#shortcode-string');" id="shortcode-copy">Copy</span>-->
		</div>
		
		<?php 
			global $post; 
			$custom = get_post_meta( $post->ID, '_sppro_form_options', true);		
			if( isset($custom['_sppro_forms_meta_form_id']) AND !empty($custom['_sppro_forms_meta_form_id']) ) {
				$cf7_id = $custom['_sppro_forms_meta_form_id'];
				$link = site_url() . '/wp-admin/admin.php?page=wpcf7&post=' .$cf7_id.'&action=edit';
				$output =  '<a class="button" href='.$link.'">Edit '.get_the_title($cf7_id).'</a>';
				$output .= ' <a style="font-size:9px;text-decoration:none;" title="Open in new tab" target="_blank" href='.$link.'"><span class="dashicons dashicons-admin-page"></span></a>';
			} else { $output = 'After Update'; }
		?>
		<?php if(isset($output)) { ?>
				<div class="misc-pub-section shortcode-sppro" style="font-weight:bold;">
					Contact Form 7 in Use:<br/> <?php echo $output; ?>
				</div>
		<?php } ?>
		
		<div class="misc-pub-section shortcode-sppro" style="font-weight:bold;">
			Change Global Settings<br/>
			<a class="button" href="<?php echo SPPRO_OPTIONS_URL; ?>" target="_blank">Option Panel</a>
			<a style="font-size:9px;text-decoration:none;" title="Open in new tab" target="_blank" href="<?php echo SPPRO_OPTIONS_URL; ?>"><span class="dashicons dashicons-admin-page"></span></a>
		</div>		
		
		<div class="misc-pub-section shortcode-sppro" style="font-weight:bold;">
			Slick Popup Pro<br/>
			<a class="button" href="<?php echo SPPRO_POPUPS_URL; ?>" target="_blank">View All Popups</a>			
			<a style="font-size:9px;text-decoration:none;" title="Open in new tab" target="_blank" href="<?php echo SPPRO_POPUPS_URL; ?>"><span class="dashicons dashicons-admin-page"></span></a>
		</div>		
		
		<?php
	}	
}

add_action('redux/page/sp_opts/menu/after', 'sppro_redux_after_menu');
function sppro_redux_after_menu($redux_object) {
	$output = ''; 
	
	$output .= '<div class="sppro-ad sppro-after-menu">';
		$output .= '<h3>Support</h3><p>If you face any issues or need help to setup the popup then contact us at <strong><a href="'.admin_url('/admin.php?page=sp-pro-help').'">Support</a></strong> Page</p>';
	$output .= '</div>';
	
	echo $output; 
}

?>