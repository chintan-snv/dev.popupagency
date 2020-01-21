<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.codetides.com/
 * @since             1.2.0
 * @package           Magic_Shortcodes
 *
 * @wordpress-plugin
 * Plugin Name:       Magic Shortcodes Builder
 * Plugin URI:        http://www.codetides.com/magic-shortcodes-builder/
 * Description:       Shortcode Magic is a very unique and powerful “Shortcode Builder”. You can easily create shortcodes from any HTML/CSS/JS code. You can also style and configure existing shortocdes. All Shortcodes are dynamically generated and you can easily add them to post and pages and there is also a widget available for you to easily add them to widget area.


 * Version:           1.2.0
 * Author:            CodeTides
 * Author URI:        http://www.codetides.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       magic-shortcodes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-magic-shortcodes-activator.php
 */
function activate_magic_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-magic-shortcodes-activator.php';
	Magic_Shortcodes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-magic-shortcodes-deactivator.php
 */
function deactivate_magic_shortcodes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-magic-shortcodes-deactivator.php';
	Magic_Shortcodes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_magic_shortcodes' );
register_deactivation_hook( __FILE__, 'deactivate_magic_shortcodes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-magic-shortcodes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_magic_shortcodes() {

	$plugin = new Magic_Shortcodes();
	$plugin->run();

}
run_magic_shortcodes();
