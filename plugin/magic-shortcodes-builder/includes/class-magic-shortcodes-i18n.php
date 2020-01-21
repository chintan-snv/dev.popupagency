<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.codetides.com/
 * @since      1.0.0
 *
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Magic_Shortcodes
 * @subpackage Magic_Shortcodes/includes
 * @author     CodeTides <contact@codetides.com>
 */
class Magic_Shortcodes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'magic-shortcodes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
