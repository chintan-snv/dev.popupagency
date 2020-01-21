<?php
/**
* Plugin Name: Contact Form 7 Builder And Designer
* Plugin URI: https://codecanyon.net/user/rednumber/portfolio
* Description: With Contact Form 7 Builder And Designer you don’t need to a programmer or developer to create form.
* Author: Rednumber
* Version: 1.3
* Author URI: https://codecanyon.net/user/rednumber/portfolio
*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
define( 'CT7_BUILDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CT7_BUILDER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CT7_BUILDER_DOMAIN', "cf7_builder" );
/*
Hide E_NOTICE
 */


include_once(ABSPATH.'wp-admin/includes/plugin.php');
/*
* Include pib
*/
if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' )  ) {
    include CT7_BUILDER_PLUGIN_PATH."backend/index.php";
    include CT7_BUILDER_PLUGIN_PATH."backend/heading.php";
    include CT7_BUILDER_PLUGIN_PATH."backend/text.php";
    include CT7_BUILDER_PLUGIN_PATH."frontend/index.php";
}
/*
* Check plugin contact form 7
*/
class cf7_builder_init {
    function __construct(){
       add_action('admin_notices', array($this, 'on_admin_notices' ) );
       set_error_handler( array($this, 'error_handler') );
       load_plugin_textdomain( 'cf7_builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
    public function error_handler($errno, $errstr, $errfile, $errline, $errcontext = array()) {
        $error_file = str_replace('\\', '/', $errfile);
        $content_dir = str_replace('\\', '/', WP_CONTENT_DIR . '/plugins/contact-form-7');
        $content_dir_this = str_replace('\\', '/', WP_CONTENT_DIR . '/plugins/contact-form-7-builder-designer');
        if (strpos($error_file, $content_dir) !== false) {
            return true;
        }
        if (strpos($error_file, $content_dir_this) !== false) {
            return true;
        }
        return false;
    }
    function on_admin_notices(){
        if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' )  ) {
            echo '<div class="error"><p>' . __('Plugin need active plugin Contact Form 7', CT7_BUILDER_DOMAIN) . '</p></div>';
        }
    }
}
new cf7_builder_init;

