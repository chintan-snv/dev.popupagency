<?php
/*
Plugin Name: One Click Optimization
Plugin URI: https://premium.divcoder.com/one-click-plugins/
Description: Optimize your website with one click!
Author: Divcoder
Version: 2.0.3
Author URI: https://premium.divcoder.com
Authoras URI: https://premium.divcoder.com
Domain Path: /languages
*/

if ( !function_exists( 'add_action' ) ) {
    echo 'Code is poetry.';
    exit;
}

//Plugin DIR URL
define( 'WPOP_URL', plugin_dir_url( __FILE__ ) );
//Plugin DIR Path
define( 'WPOP_DIR', plugin_dir_path( __FILE__ ) );

//Admin Styles
add_action( 'admin_init', 'wpop_echo_css' );

function wpop_echo_css() {
	//Add CSS Style for Admin Panel
   wp_enqueue_style( 'wpop-style', WPOP_URL."admin/assets/css/style.min.css",array(), "2.0.0" );
}

/*
* Load Admin Settings
*/
include WPOP_DIR ."admin/index.php";

/*
* Load Functions
*/
include WPOP_DIR ."functions/WPOP-functions.php";

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function wpop_language() {
  load_plugin_textdomain( 'WPOS-lang', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

//Load Plugin Functions
add_action( 'plugins_loaded', 'wpop_language' );

/*
* Default Options for Plugin
*/

function wpop_activation() {

	add_option("WPOP_check_enable","0");
	add_option("WPOP_adv_enable","0");
	add_option("WPOP_html_enable","0");
	add_option("WPOP_comm_enable","0");
	add_option("WPOP_emoj_enable","0");
	add_option("WPOP_migr_enable","0");
	add_option("WPOP_shor_enable","0");
	add_option("WPOP_quer_enable","0");
	add_option("WPOP_foot_enable","0");
	add_option("WPOP_async_enable","0");
	add_option("WPOP_lazy_enable","0");
	add_option("WPOP_cach_enable","0");
	add_option("WPOP_embd_enable","0");
	add_option("WPOP_admn_enable","0");
	
}


//Register Options
register_activation_hook( __FILE__, 'wpop_activation' );

// Plugin WP-Admin Settings Text
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wpop_plugin_page');

function wpop_plugin_page( $links ) {
    $links[] = '<a href="' . admin_url( 'options-general.php?page=WPOS_options' ) . '">' . __('Settings') . '</a>';
    return $links;
}