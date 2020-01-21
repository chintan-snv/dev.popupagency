<?php
/*
Plugin Name: Disable Elementor Editor Translation
Description: This plugin keeps Elementor editor in English, even when site language is set to something else.
Plugin URI: https://plugins.leap13.com/elementor-plugins/disable-elementor-editor-translation-plugin/
Version: 1.0.2
Author: Leap13
Author URI: https://leap13.com/
Text Domain: disable-elementor-editor-translation
License: GNU General Public License v3.0
@package Disable Elementor Editor Translation
*/

if ( ! defined('ABSPATH') ) exit;  // No access of directly access

define( 'DET_FILE', __FILE__ );
define( 'DET_PATH', plugin_dir_path( __FILE__ ) );
define( 'DET_BASENAME', plugin_basename( DET_FILE ) );
define( 'DET_URL', plugins_url('/', DET_FILE ));
define( 'DET_VERSION', '1.0.2');

if ( ! class_exists('Disable_Elementor_Translation') ) {
    
	/**
	 * Main Plug-in Class
	 */
	class Disable_Elementor_Translation  {
		
        /*
         * Instance of the class
         * 
         * @access private
         * @since 1.0.0
         * 
         */
		private static $instance = null;

        /*
         * Construct
         * 
         * Class Constructor
         * 
         * @since 1.0.0
         * @access public
         * 
         */
		public function __construct() {
            
            add_action( 'plugins_loaded', array( $this, 'load_domain' ) );
            
            add_action( 'init', array( $this, 'init' ) );
            
			add_action( 'elementor/init',  array( $this, 'unload_elementor_textdomain' ), 100 );
            
		}

        /*
         * Unload ELementor Text Domain
         * 
         * unloads Elementor editor text domain
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
		public function unload_elementor_textdomain() {
            
            $key = get_option( 'det_save_settings' )['det'];
            
            $is_disabled = isset ( $key ) ? $key : false;
            
            //Unload Elementor text domain only if installed
			if ( defined( 'ELEMENTOR_VERSION' ) && $is_disabled ) {
                
				unload_textdomain( 'elementor' );
                
                if ( defined( 'PREMIUM_ADDONS_VERSION' ) )
                    unload_textdomain( 'premium-addons-for-elementor' );
                
                if ( defined( 'PREMIUM_PRO_ADDONS_VERSION' ) )
                    unload_textdomain( 'premium-addons-pro' );
                
                //Unload Elementor PRO text domain only if installed
                if ( defined( 'ELEMENTOR_PRO_VERSION' ) )    
                    unload_textdomain( 'elementor-pro' );	
                    
			}
			
		}
        
        
        /*
         * Load Domain
         * 
         * loads plugin text domain
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
        public function load_domain() {
            
            load_plugin_textdomain( 'disable-elementor-editor-translation' );
            
        }
        
        /*
         * Admin Init
         * 
         * loads required files on dashboard
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
        public function init() {
            
            if ( is_admin() ) {
                require_once ( DET_PATH . 'admin/admin.php' );
            }
            
        }

		/**
         * 
		 * Get instance of the class
         * 
		 * @since 1.0.0
         * @access public
         * 
		 * @return object
		 */
		public static function get_instance() {

			if ( self::$instance == null ) {
				self::$instance = new self;
			}
			return self::$instance;

		}
	}
}

// Trigger the plugin by calling `get_instance()`
if ( ! function_exists( 'disable_elementor_translation' ) ) {
    
	function disable_elementor_translation() {
        
		return Disable_Elementor_Translation::get_instance();
        
	}
}

disable_elementor_translation();