<?php

if ( ! defined( 'CASE27_THEME_DIR' ) ) {
	define( 'CASE27_THEME_DIR', get_template_directory() );
}

if ( ! defined( 'CASE27_INTEGRATIONS_DIR' ) ) {
	define( 'CASE27_INTEGRATIONS_DIR', CASE27_THEME_DIR . '/includes/integrations' );
}

if ( ! defined( 'CASE27_ASSETS_DIR' ) ) {
	define( 'CASE27_ASSETS_DIR', CASE27_THEME_DIR . '/assets' );
}

if ( ! defined( 'CASE27_ENV' ) ) {
	define( 'CASE27_ENV', 'production' );
}

if ( ! defined( 'PT_OCDI_PATH' ) ) {
	define( 'PT_OCDI_PATH', trailingslashit( CASE27_THEME_DIR ) . 'includes/extensions/demo-import/plugin/' );
}

if ( ! defined( 'PT_OCDI_URL' ) ) {
	define( 'PT_OCDI_URL', trailingslashit( get_template_directory_uri() ) . 'includes/extensions/demo-import/plugin/' );
}

if ( ! defined( 'CASE27_THEME_VERSION' ) ) {
	if (CASE27_ENV == 'dev') {
		define( 'CASE27_THEME_VERSION', rand(1, 10e3) );
	} else {
		define( 'CASE27_THEME_VERSION', wp_get_theme( get_template() )->get('Version') );
	}
}

if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
	define( 'ELEMENTOR_PARTNER_ID', 2124 );
}

// Load textdomain early to include strings that are localized before
// the 'after_setup_theme' is called.
load_theme_textdomain( 'my-listing', CASE27_THEME_DIR . '/languages' );

// Load classes.
require_once CASE27_THEME_DIR . '/includes/autoload.php';


function custom_postcount($id, $taxonomy)
{
	$count = 0;
	//$taxonomy = 'category';
	$args = array(
	  'child_of' => $id,
	);
	$tax_terms = get_terms($taxonomy,$args);
	foreach ($tax_terms as $tax_term) {
		$count +=$tax_term->count;
	}
return $count;
}

// Add Shortcode for job
function custom_shortcode() {
global $wpdb;
 $numpost = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts
 WHERE post_status in ('publish','private','draft') AND post_type = 'job_listing'"); ; 
 return $numpost; 
}
add_shortcode( 'livecount', 'custom_shortcode' );


// Shortcode for cites

function custom_shortcode1() {
$cat_count = get_category( '361' );
return $cat_count->count;
}
add_shortcode( 'citiescount', 'custom_shortcode1' );





function twentyseventeen_child_setup() {
    $path = get_template_directory_uri().'/languages';
    load_theme_textdomain( 'my-listing', $path );
}
add_action( 'after_setup_theme', 'twentyseventeen_child_setup' );