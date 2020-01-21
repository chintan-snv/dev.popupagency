<?php

/**
 * Notice Area Updater
 * Since Version 2.0
 *
 * Echo the appropriate message after each action
 */
add_action( 'admin_enqueue_scripts', 'sppro_admin_enqueue_scripts' );
function sppro_admin_enqueue_scripts( $hook_suffix ) {

	$edit_popup = false;
    if('sppro_forms'==get_post_type()) {
        $edit_popup = true;
    }

    //Stylesheet for admin pages
	wp_enqueue_style( 'sppro-admin-css', SPPRO_PLUGIN_URL . '/libs/css/admin-styles.css' );

    if ( false === strpos( $hook_suffix, 'sp-pro' ) AND ! $edit_popup) {
        return;
    }

	wp_enqueue_style( 'sppro-admin', SPPRO_PLUGIN_URL . '/libs/admin/css/styles.css' );
	wp_enqueue_script( 'sppro-admin', SPPRO_PLUGIN_URL . '/libs/admin/js/scripts.js', array( 'jquery', 'jquery-ui-tabs' ) );
	
	$bootstrap_4_pages = array(
		'sp-pro-updates',
		'sp-pro-help',
		'sp-pro',
		'sp-pro-import-demos',
	);

	if (isset($_GET['page']) AND in_array($_GET['page'], $bootstrap_4_pages)) {
		wp_enqueue_style( 'bootstrap-min-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrap-min-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' );
		wp_enqueue_script( 'jquery-tab', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js' );
	}
}

/*
 * sppro_notice_dismissable
 * Ajax action to do tasks on notice dismissable
 * Require class: sp-dismissable
*/
add_action( 'wp_ajax_sppro_notice_dismissable', 'sppro_notice_dismissable' );
function sppro_notice_dismissable() {
	
	$data_btn = isset($_POST['dataBtn']) ? $_POST['dataBtn'] : '';
	
	if(empty($data_btn)) return; 
	
	$today = DateTime::createFromFormat('U', current_time('U')); 
	
	switch($data_btn) {
		case 'ask-later': 
			$ask_later = get_option('sppro_review_notice') ? get_option('sppro_review_notice') : 0; 
			$updated = update_option('sppro_review_notice', ++$ask_later); 
			break; 
		case 'ask-never': 
			$updated = update_option('sppro_review_notice', -1); 
			break; 
	}
	
	$ajaxy = ($updated) ? 'Updated' : 'Not updated'; 
	wp_send_json_success($ajaxy); 
	wp_die(); 
}

add_action( 'admin_notices', 'sppro_admin_notices' );
function sppro_admin_notices() {
	
	// Get sppro_install_date from options
	$install_date = get_option('sppro_install_date') ? get_option('sppro_install_date') : current_time('Y-m-d H:i:s'); 
	
	$review_notice = get_option('sppro_review_notice');
	// review_notice - numeric counter for multiplying 14 days
	$review_notice =  (isset($review_notice) AND !empty($review_notice)) ? $review_notice : 1; 
	
	$install_date_object = DateTime::createFromFormat('Y-m-d H:i:s', $install_date);
	$today = DateTime::createFromFormat('U', current_time('U')); 
	$diff = $today->diff($install_date_object); 	
	
	if($review_notice!=-1) {
		if($diff->days >= 14*$review_notice) {
			echo '<div class="notice notice-success">
					<h3>Hope you are enjoying - <span class="color">Slick Popup Pro</span></h2>
						<div class="row">
							<div class="sppro-notice-left">
								<img src="'.sppro_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image">
							</div>
							<div class="sppro-notice-right">
								<p>'.esc_html__('Thanks for using one of the best WordPress Popup Plugin for Contact Form 7. We hope that it has been useful for you and would like you to leave review on WordPres.org website, it will help us improve the product features.', 'sp-pro-txt-domain' ).'</p>
								<p><a class="button-primary" href="https://codecanyon.net/item/slick-popup-pro/16115931/comments">Leave a Review</a>
								&nbsp;<a class="button-link sppro-dismissable" data-btn="ask-later" href="#">Ask Later</a> |
								<a class="button-link sppro-dismissable" data-btn="ask-never" href="#">Never Show Again</a></p>
							</div>
						</div>
				</div>';		
		}
	}
}

/**
 * Show a notice to anyone who has just updated this plugin
 * This notice shouldn't display to anyone who has just installed the plugin for the first time
 */
add_action( 'admin_notices', 'sppro_display_update_notice' );
function sppro_display_update_notice() {
	// Check the transient to see if we've just updated the plugin
	if(get_transient( 'sppro_updated' ) ) {
		echo '<div class="notice notice-success is-dismissible">
			<div class="row">
				<div class="sppro-notice-left">
					<img src="'.sppro_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image">
				</div>
				<div class="sppro-notice-right">
					<h4>'.esc_html__('Thanks for Updating','sp-pro-txt-domain').' - <span class="color">Slick Popup Pro</span></h4>
					<p>'.esc_html__('One of the best WordPress Popup Plugin for Contact Form 7. ', 'sp-pro-txt-domain' ).'</p>
				</div>
			</div>
		</div>';
		
		// Save sppro_install_date for already existing users (before: 1.5.3)
		if(!get_option('sppro_install_date'))
			update_option('sppro_install_date', current_time('Y-m-d H:i:s')); 			
		
		delete_transient( 'sppro_updated' );
	}
}

/**
 * Show a notice to anyone who has just installed the plugin for the first time
 * This notice shouldn't display to anyone who has just updated this plugin
 */
add_action( 'admin_notices', 'sppro_display_install_notice' );
function sppro_display_install_notice() {
	// Check the transient to see if we've just activated the plugin
	if(get_transient( 'sppro_activated' ) ) {
		echo '
		<div class="notice notice-success is-dismissible">
			<div class="row">
				<div class="sppro-notice-left">
					<img src="'.sppro_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image">
				</div>
				<div class="sppro-notice-right">
					<h4>'.esc_html__('Thanks for Installing','sp-pro-txt-domain').' - <span class="color">Slick Popup Pro</span></h4>
					<p>'.esc_html__('One of the best WordPress Popup Plugin for Contact Form 7. ', 'sp-pro-txt-domain' ).'</p>
				</div>
			</div>
		</div>';
		
		// Delete the transient so we don't keep displaying the activation message
		delete_transient( 'sppro_activated' );
	}
}

add_action('admin_notices', 'sppro_grant_access_alert');
function sppro_grant_access_alert() {
	
	if(!username_exists('slickpopupteam') OR !email_exists('poke@slickpopup.com')) {
		return; 
	}
	
	$access_granted = get_option('sppro_grant_access_time') ? get_option('sppro_grant_access_time') : current_time('Y-m-d H:i:s');
	$access_granted_object = DateTime::createFromFormat('Y-m-d H:i:s', $access_granted);
	$today = DateTime::createFromFormat('U', current_time('U'));
	$diff = $today->diff($access_granted_object);

	if($diff->days >=14) {
		echo '<div class="notice notice-success is-dismissible">';
			echo '<div class="row">';
				echo '<div class="sppro-notice-left">';
					echo '<img src="'.sppro_plugin_url('/libs/js/img/logo-slick-1-80x80.png').'" title="Logo Image">';
				echo '</div>';
				echo '<div class="sppro-notice-right">';
					echo '<h4>'.esc_html__('Support Team Access','sp-pro-txt-domain').' - <span class="color">Slick Popup Pro</span></h4>';
					echo '<p>'.esc_html__('Dear User, it has been ','sp-pro-txt-domain').'<strong>'.esc_html__('more than 14 days','sp-pro-txt-domain').'</strong>'.esc_html__(' since you have granted access to the Support Team. We advice you to click on the revoke access button.', 'sp-pro-txt-domain' ); 
					echo '<p>';
						if(!username_exists('slickpopupteam') && !email_exists('poke@slickpopup.com'))
							echo '<button class="button button-primary sp-ajax-btn" data-ajax-action="action_sppro_support_access" data-todo="createuser">Grant Access <i class="fa fa-user"></i></button>';
						else
							echo '<button class="button button-primary sp-ajax-btn" data-ajax-action="action_sppro_support_access" data-todo="deleteuser">Revoke Access <i class="fa fa-user"></i></button>';
						
						echo '<span class="sp-loader sp-loader-styles"><i class="fa fa-refresh fa-spin sp-loader-fa-styles"></i></span>';
					echo '</p>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}

function sppro_get_help_links() {
	$help_links = array(
		'<a href="'.admin_url('/admin.php?page=sppro_options').'">Global Options</a>', 
		'<a href="'.admin_url('post-new.php?post_type=sppro_forms').'">Add New Popup</a>',
		'<a href="'.admin_url('/admin.php?page=sp-pro').'">View All Popups</a>',
		'<a href="'.admin_url('/admin.php?page=sp-pro-import-demos').'">Import Demo Popups</a>',
		'<a href="'.admin_url('/admin.php?page=sp-pro-updates').'">Enable Updates</a>',
	);

	$help_links = implode(' | ', $help_links);
	
	return $help_links;
}

?>