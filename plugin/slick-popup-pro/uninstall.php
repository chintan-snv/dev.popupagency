<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


function sppro_uninstall_plugin() { // Uninstallation actions here
	
	$delete_data = get_option('sppro_delete_data') ? get_option('sppro_delete_data') : 0; 
	
	if($delete_data) {
		delete_option('sp_opts');
		delete_option('sppro_delete_data');
		delete_option('sppro_install_date');
		delete_option('sppro_last_import');
		delete_option('sppro_review_notice');
		delete_option('sppro_notices');
		delete_option('sppro_plugin_message');
	}
}

sppro_uninstall_plugin();


?>