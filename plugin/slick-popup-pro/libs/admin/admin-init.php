<?php

    // Load the TGM init if it exists
    if ( !class_exists( 'TGM_Plugin_Activation' ) ) {
        require_once dirname( __FILE__ ) . '/../thetgm/class-tgm-plugin-activation.php';
		require_once( dirname( __FILE__ ) . '/tgm-init.php' );
    }

    // Load the embedded Redux Framework
    if ( file_exists( dirname( __FILE__ ).'/redux-framework/framework.php' ) ) {
        require_once dirname(__FILE__).'/redux-framework/framework.php';
    }

    // Load the theme/plugin options
    if ( file_exists( dirname( __FILE__ ) . '/options-init.php' ) ) {
        require_once dirname( __FILE__ ) . '/options-init.php';
    }

    // Load Redux extensions
    if ( file_exists( dirname( __FILE__ ) . '/redux-extensions/extensions-init.php' ) ) {
        require_once dirname( __FILE__ ) . '/redux-extensions/extensions-init.php';
    }
	
	if(!function_exists('removeDemoModeLink')) {
		function removeDemoModeLink() { // Be sure to rename this function to something more unique
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
			}
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
			}
		}
		add_action('init', 'removeDemoModeLink');
	}
	
?>