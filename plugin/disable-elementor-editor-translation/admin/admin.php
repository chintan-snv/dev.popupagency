<?php

namespace DET\Admin;

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class DET_Admin {
    
    private static $instance = null;
    
    protected $page_slug = 'det-settings';

    public static $det_keys = [ 'det' ];
    
    private $det_default_settings;
    
    private $det_settings;
    
    private $det_get_settings;
   
    public function __construct() {
        
        add_action( 'admin_menu', array( $this,'det_admin_menu' ), 700 );
        
        add_action( 'admin_enqueue_scripts', array( $this, 'det_admin_page_scripts' ) );
        
        add_action( 'wp_ajax_det_settings', array( $this, 'det_save_settings' ) );
        
        add_filter( 'plugin_action_links_' . DET_BASENAME, array( $this, 'plugin_settings_page' ) );
        
    }
    
    /*
    * Creates `Settings` action link
    * @since 1.0.0
    * @return void
    */
   public function plugin_settings_page( $links ) {

       $settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->page_slug ), __( 'Settings', 'disable-elementor-editor-translation' ) );

       array_unshift( $links, $settings_link );

       return $links;
   }
    
    public function det_admin_page_scripts () {
        
        $current_screen = get_current_screen();
        
        if( strpos( $current_screen->id , $this->page_slug ) !== false ) {
            
            wp_enqueue_style(
                'det-admin-css',
                DET_URL . 'admin/assets/css/admin.css'
            );
            
            wp_enqueue_style(
                'det-sweetalert-style',
                DET_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.css'
            );
            
            wp_enqueue_script(
                'det-admin-js',
                DET_URL .'admin/assets/js/admin.js',
                array('jquery'),
                DET_VERSION,
                true
            );
            
            wp_enqueue_script(
                'det-sweetalert-core',
                DET_URL . 'admin/assets/js/sweetalert2/core.js',
                array('jquery'),
                DET_VERSION,
                true
            );
            
			wp_enqueue_script(
                'det-sweetalert',
                DET_URL . 'admin/assets/js/sweetalert2/sweetalert2.min.js',
                array( 'jquery', 'det-sweetalert-core' ),
                DET_VERSION,
                true
            );
            
        }
    }

    public function det_admin_menu() {
        
        add_submenu_page(
			'elementor',
			'',
			__( 'Disable Editor Translation', 'disable-elementor-editor-translation' ),
			'manage_options',
			$this->page_slug,
			array( $this, 'det_admin_page' )
		);
    }

    public function det_admin_page() {

        $js_info = array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
		);

		wp_localize_script( 'det-admin-js', 'settings', $js_info );
        
        $this->det_default_settings = self::get_default_keys();
       
        $this->det_get_settings = self::get_enabled_keys();
       
        $det_new_settings = array_diff_key( $this->det_default_settings, $this->det_get_settings );
       
        if( ! empty( $det_new_settings ) ) {
            $det_updated_settings = array_merge( $this->det_get_settings, $det_new_settings );
            update_option( 'det_save_settings', $det_updated_settings );
        }
        
        $this->det_get_settings = get_option( 'det_save_settings', $this->det_default_settings );
        
        
	?>
        <div class="wrap">
            <div class="response-wrap"></div>
            <form action="" method="POST" id="det-settings" name="det-settings">
                <div class="det-header-wrapper">
                    <div class="det-title-left">
                        <h1 class="det-title-main"><?php echo __('Disable Elementor Editor Translation', 'disable-elementor-editor-translation'); ?></h1>
                        <h3 class="det-title-sub"><?php echo __('Thank you for using Disable Elementor Editor Translation. This plugin has been developed by Leap13 and we hope you enjoy using it.','disable-elementor-editor-translation'); ?></h3>
                    </div>
                </div>
                <div class="det-settings-tabs">
                    <div id="det-modules" class="det-settings-tab">
                        <table class="det-elements-table">
                            <tbody>
                                <tr>
                                    <th><?php echo __('Disable Editor Translation', 'disable-elementor-editor-translation'); ?></th>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" id="det" name="det" <?php checked( 1, $this->det_get_settings['det'], true ) ?>>
                                            <span class="slider round"></span>
                                    </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="submit" value="<?php echo __('Save Settings', 'disable-elementor-editor-translation'); ?>" class="button det-btn det-save-button">

                    </div>
                </div>
            </form>
        </div>
	<?php
    }

    /*
     * Get Default Keys
     * 
     * Fill an array for settings with default values
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return array
     */
    public static function get_default_keys() {
        
        $default_keys = array_fill_keys( self::$det_keys, true );
        
        return $default_keys;
    }
    
    /*
     * Get Enabled Keys
     * 
     * Get key values of settings
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return array
     */
    public static function get_enabled_keys() {
        
        $enabled_keys = get_option( 'det_save_settings', self::get_default_keys() );
        
        return $enabled_keys;
    }

    /*
     * det Save Settings
     * 
     * Update Settings
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return boolean
     */
    public function det_save_settings() {
        
        if( isset( $_POST['fields'] ) ) {
            parse_str( $_POST['fields'], $settings );
        } else {
            return;
        }

        $this->det_settings = array(
            'det'      => intval( $settings['det'] ? 1 : 0 ),
        );

        update_option( 'det_save_settings', $this->det_settings );

        return true;
        
    }
    
    /*
     * Get Instance
     * 
     * @since 1.0.0
     * @access public
     * 
     * @return array
     */
    public static function get_instance() {

        if ( self::$instance == null ) {
				self::$instance = new self;
        }
        return self::$instance;

    }
}

// Trigger `DET_Admin` by calling `get_instance()`
if ( ! function_exists( 'det_admin' ) ) {
    
	function det_admin() {
        
		return DET_Admin::get_instance();
        
	}
}

det_admin();