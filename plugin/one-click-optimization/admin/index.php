<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Code is poetry.';
    exit;
}

/*
* Menu Settings
*/
add_action( 'admin_menu', 'WPOP_menu' );

function WPOP_menu(){
	add_options_page( __('One Click Optimization', 'WPOS-lang'), __('One Click Optimization', 'WPOS-lang'), 'manage_options', 'WPOS_options', 'WPOS_options' );
}


/*
* Call
*/
require WPOP_DIR . 'admin/admin.php';