<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// check onces and wordpress rights, else DIE
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( !wp_verify_nonce($_POST['save_options_field'], 'save_options') || !current_user_can('publish_pages') ) {
		die('Sorry, but this request is invalid');
	}

    $translation = array(
        0 => array('id' => 0, 'string' => __('Seconds', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_0'] ) ),
        1 => array('id' => 1, 'string' => __('Minutes', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_1'] )),
        2 => array('id' => 2, 'string' => __('Hours', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_2'] )),
        3 => array('id' => 3, 'string' => __('Days', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_3'] )),
        4 => array('id' => 4, 'string' => __('Insert your email address.', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_4'] )),
        5 => array('id' => 5, 'string' => __('Oops! This email address is already on our list.', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_5'] )),
        6 => array('id' => 6, 'string' => __('Oops! We need a valid address. Please try again.', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_6'] )),
        7 => array('id' => 7, 'string' => __('Thank you! Your sign up request was successful.', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_7'] )),
        8 => array('id' => 8, 'string' => __('Submit', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_8'] )),
        9 => array('id' => 9, 'string' => __('Scroll', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_9'] )),
        10 => array('id' => 10, 'string' => __('First Name', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_10'] )),
        11 => array('id' => 11, 'string' => __('Last Name', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_11'] )),
        12 => array('id' => 12, 'string' => __('Subscribe', 'cmp-coming-soon-maintenance'), 'translation' => sanitize_text_field( $_POST['niteoCS_translate_12'] )),
    );

    update_option('niteoCS_translation', wp_json_encode( $translation ));
}

// Populate translation list, if not yet created
if ( !get_option('niteoCS_translation') ) {
    $translation = array(
        0 => array('id' => 0, 'string' => __('Seconds', 'cmp-coming-soon-maintenance'), 'translation' => __('Seconds', 'cmp-coming-soon-maintenance') ),
        1 => array('id' => 1, 'string' => __('Minutes', 'cmp-coming-soon-maintenance'), 'translation' => __('Minutes', 'cmp-coming-soon-maintenance') ),
        2 => array('id' => 2, 'string' => __('Hours', 'cmp-coming-soon-maintenance'), 'translation' => __('Hours', 'cmp-coming-soon-maintenance') ),
        3 => array('id' => 3, 'string' => __('Days', 'cmp-coming-soon-maintenance'), 'translation' => __('Days', 'cmp-coming-soon-maintenance') ),
        4 => array('id' => 4, 'string' => __('Insert your email address.', 'cmp-coming-soon-maintenance'), 'translation' => __('Insert your email address.', 'cmp-coming-soon-maintenance') ),
        5 => array('id' => 5, 'string' => __('Oops! This email address is already on our list', 'cmp-coming-soon-maintenance'), 'translation' => __('Oops! This email address is already on our list.', 'cmp-coming-soon-maintenance') ),
        6 => array('id' => 6, 'string' => __('Oops! We need a valid address. Please try again.', 'cmp-coming-soon-maintenance'), 'translation' => __('Oops! We need a valid address. Please try again.', 'cmp-coming-soon-maintenance') ),
        7 => array('id' => 7, 'string' => __('Thank you! Your sign up request was successful.', 'cmp-coming-soon-maintenance'), 'translation' => __('Thank you! Your sign up request was successful.', 'cmp-coming-soon-maintenance') ),
        8 => array('id' => 8, 'string' => __('Submit', 'cmp-coming-soon-maintenance'), 'translation' => __('Submit', 'cmp-coming-soon-maintenance') ),
        9 => array('id' => 9, 'string' => __('Scroll', 'cmp-coming-soon-maintenance'), 'translation' => __('Scroll', 'cmp-coming-soon-maintenance') ),
        10 => array('id' => 10, 'string' => __('First Name', 'cmp-coming-soon-maintenance'), 'translation' => __('First Name', 'cmp-coming-soon-maintenance') ),
        11 => array('id' => 11, 'string' => __('Last Name', 'cmp-coming-soon-maintenance'), 'translation' => __('Last Name', 'cmp-coming-soon-maintenance') ),
        12 => array('id' => 12, 'string' => __('Subscribe', 'cmp-coming-soon-maintenance'), 'translation' => __('Subscribe', 'cmp-coming-soon-maintenance') ),
    );

    update_option('niteoCS_translation', wp_json_encode( $translation ));
}



// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
// load WP_List_Table extension
if( ! class_exists( 'cmp_translate_table' ) ) {
    require( dirname(__FILE__).'/inc/class-cmp-translate.php' );
}

// create subscriber table
$cmp_translate_table = new cmp_translate_table();
$cmp_translate_table->prepare_items();
?>
	
<div class="wrap cmp-coming-soon-maintenance">
	<h1></h1>
	<div id="icon-users" class="icon32"></div>
    <div class="settings-wrap">
    	<div class="cmp-inputs-wrapper translate-settings">
    		 <h2><?php _e('CMP Translation', 'cmp-coming-soon-maintenance');?></h2>
             <p><?php _e('You can edit any text on CMP landing page - button labels, countdown, subscription messages, etc.', 'cmp-coming-soon-maintenance');?></p>
    		 <form name="cmp_translate_form" method="post" action="admin.php?page=cmp-translate&status=settings-saved">
    			<?php $cmp_translate_table->display(); ?>
    		<p class="cmp-submit">
    			<?php wp_nonce_field('save_options','save_options_field'); ?>
    			<input type="submit" name="Submit" class="button cmp-button submit" value="<?php _e('Save All Changes', 'cmp-coming-soon-maintenance'); ?>" id="submitChanges" />
    		</p>
    		</form>

    	</div>
    	<?php 
    	// get sidebar with "widgets"
    	if ( file_exists(dirname(__FILE__) . '/cmp-sidebar.php') ) {
    		require (dirname(__FILE__) . '/cmp-sidebar.php');
    	} ?>
    </div>
</div>
