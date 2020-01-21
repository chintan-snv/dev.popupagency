<?php

/*
Plugin Name: WP All Import - MyListing Add On
Description: Supports importing into the MyListing theme.
Version: 1.0
Author: Soflyy
*/

include "rapid-addon.php";
include_once( plugin_dir_path( __FILE__ ) . "helpers/helper_class.php" );
include_once( plugin_dir_path( __FILE__ ) . "helpers/image_file_import_funcs.php" );
include_once( plugin_dir_path( __FILE__ ) . "helpers/geolocation.php" );
include_once( ABSPATH.'wp-admin/includes/plugin.php' );

if ( defined( 'PMXI_VERSION' ) && defined( 'PMXI_EDITION' ) ) {
    if ( PMXI_EDITION == 'paid' && version_compare( PMXI_VERSION, "4.5.5-beta-2.93" ) == "-1" ) {
        update_option( 'wpai_mlao_old_wpai', array( 'version' => 'Pro', 'show_error' => 'yes', 'display_version' => '4.5.6' ) );
    } elseif ( PMXI_EDITION == 'free' && version_compare( PMXI_VERSION, "3.5.1-beta-1.1" ) == "-1" ) {
        update_option( 'wpai_mlao_old_wpai', array( 'version' => 'Free', 'show_error' => 'yes', 'display_version' => '3.5.1' ) );
    } else {
        update_option( 'wpai_mlao_old_wpai', array( 'version' => PMXI_EDITION, 'show_error' => 'no', 'display_version' => '' ) );
    }
}

// Root directory for the plugin.
define( 'WPAI_MYLISTING_ROOT_DIR', str_replace( '\\', '/', dirname( __FILE__ ) ) );

// Path to the main plugin file.
define( 'WPAI_MYLISTING_PLUGIN_PATH', WPAI_MYLISTING_ROOT_DIR . '/' . basename( __FILE__ ) );

// Initialize addon.
$wpai_mylisting_addon = new RapidAddon( 'MyListing Add-On', 'wpai_mylisting_addon' );

// Hide default images section.
$wpai_mylisting_addon->disable_default_images();

// Import function is function wpai_mylisting_addon_import();
$wpai_mylisting_addon->set_import_function( 'wpai_mylisting_addon_import' );

 // Initialize helper classes.
 $wpai_mylisting_addon_funcs = new wpai_mylisting_addon_helper();
 $wpai_mylisting_addon_geo = new wpai_mylisting_addon_geocode();

 // Build an array of the "Listing Types" so that we know when to run the import.
 $get_the_types = $wpai_mylisting_addon_funcs->get_types();
 $all_types = array( 'job_listing', 'case27_listing_type' );
 if ( !empty( $get_the_types ) ) {
    foreach ( $get_the_types as $key => $type ) {
        $all_types[] = $type['listing_name'];
    }
}

 // Only run if MyListing is active and they're importing a valid listing type.
$wpai_mylisting_addon->run(
    array(
        "themes" => array( 'My Listing' ),
        "post_types" => $all_types
    )
);

$wpai_mylisting_addon->admin_notice(
    "The MyListing Add-On requires WP All Import <a href='http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=mylisting' target='_blank'>Pro</a> or <a href='https://wordpress.org/plugins/wp-all-import/'>Free</a>, and the <a href='https://themeforest.net/item/mylisting-directory-listing-wordpress-theme/20593226' target='_blank'>MyListing Theme</a>.", 
	array(
        "themes" => array( "My Listing" )
    )   
);

$image_field_count = 0; // Used to determine image import function.
$file_field_count = 0; // Used to determine file import function.

// Figure out which post type they're importing.
if ( $custom_type = mylisting_addon_get_post_type() ) {

    if ( $custom_type == 'job_listing' ) {

        // They picked "Job Listing" in step 1,
        // Which is actually the default WP Job Manager post type and is not used in this theme.
        $wpai_mylisting_addon->add_text( '<span style="font-size: 1.2em">Cannot import into the "Listing" (job_listing) post type. Please <strong>completely restart your import via All Import > New Import (<em>important!</em>)</strong> and select the name of the listing type from the drop down.</span>' );

        if ( !empty( $get_the_types ) ) {
            $valid_types_string = '<ul style="font-size: 1.2em; list-style-type: square; margin-left: 30px;">';
            foreach ( $get_the_types as $key => $valid_type ) {
                $valid_types_string .= '<li>' . $valid_type['listing_type'] . '</li>';
            }
            $valid_types_string .= '</ul>';

            $wpai_mylisting_addon->add_text( '<span style="font-size: 1.2em;">Valid Listing Types:' . $valid_types_string . '</span>' );
        } else {

            $wpai_mylisting_addon->add_text( '<span style="font-size: 1.2em;"><strong>Warning</strong>: No listing types detected. Please create one via <strong><em>Listing Tools > Listing Types</em></strong>.</span>' );

        }

    } elseif ( $custom_type == 'case27_listing_type' ) {

        // They picked "Listing Type" in the drop down, which we don't support importing.
        $wpai_mylisting_addon->add_text( '<span id="wpaimlao_wrong_post_type">Cannot import "Listing Types" (case27_listing_type) with this add-on. Please <strong>completely restart your import via All Import > New Import (<em>important!</em>)</strong> and select the name of an already existing listing type from the drop down.</span>' );

        if ( !empty( $get_the_types ) ) {
            $valid_types_string = '<ul style="font-size: 1.2em; list-style-type: square; margin-left: 30px;">';
            foreach ( $get_the_types as $key => $valid_type ) {
                $valid_types_string .= '<li>' . $valid_type['listing_type'] . '</li>';
            }
            $valid_types_string .= '</ul>';

            $wpai_mylisting_addon->add_text( '<span style="font-size: 1.2em;">Valid Listing Types:' . $valid_types_string . '</span>' );
        } else {

            $wpai_mylisting_addon->add_text( '<span style="font-size: 1.2em;"><strong>Warning</strong>: No listing types detected. Please create one via <strong><em>Listing Tools > Listing Types</em></strong>.</span>' );

        }

    } else {

        // Save the listing type in the database.
        update_option( 'wpai_mylisting_addon_listing_type', $custom_type );

        // Retrieve the listing type post so that we can get the available fields.
        if ( $post_type_id = get_page_by_path( $custom_type, OBJECT, 'case27_listing_type' ) ) {
            $listing_type_id = $post_type_id->ID;

            // Get all of the fields for the listing type.
            if ( $all_fields = get_post_meta( $listing_type_id, 'case27_listing_type_fields', true ) ) {
                
                $all_fields = maybe_unserialize( $all_fields );

                if ( empty( $all_fields ) ) {

                    // They have not added any fields to their listing type.
                    $wpai_mylisting_addon->add_text( "<span style='font-size: 1.2em;'><strong>Warning</strong>: No fields detected for the '" . $post_type_id->post_title . "' listing type. Please edit the listing type and add some fields, then re-start your import via All Import > New Import.</span>" );

                } else {
                    
                    // Get option to save for import function later.
                    $wpai_addon_save_fields = get_option( 'wpai_mylisting_addon_save_fields' );
                    if ( empty( $wpai_addon_save_fields ) ) {
                        $wpai_addon_save_fields = array();
                    } else {
                        $wpai_addon_save_fields = maybe_unserialize( $wpai_addon_save_fields );
                    }

                    foreach ( $all_fields as $name => $listing_field ) {
                        /*** Now we need to dynamically add import fields.
                         * Field name is $name. Example: job_category
                         * Field slug is $listing_field['slug']. Example: job_category
                         * Field label is $listing_field['label']. Example: Category
                         * Field type is $listing_field['type']. Example: textarea
                         * If $listing_field['taxonomy'] exists, it's a taxonomy term field.
                         */
                        if ( ! in_array( $listing_field['slug'], $wpai_addon_save_fields ) ) {
                            $wpai_addon_save_fields[] = $listing_field['slug'];
                        }
                        
                        // This option is going to tell us how to treat the fields during the import.
                        $wpai_mylisting_addon_field_info = get_option( 'wpai_mylisting_addon_field_info' );

                        if ( empty( $wpai_mylisting_addon_field_info ) ) {

                            $wpai_mylisting_addon_field_info = array();

                        }

                        // Now we'll use a helper function to figure out what type of rapid add-on API field we need to use.
                        if ( array_key_exists( 'type', $listing_field ) ) {

                            $wpai_type = $wpai_mylisting_addon_funcs->get_field_output( $listing_field['type'] );

                        } else {

                            $wpai_type = $wpai_mylisting_addon_funcs->get_field_output( $listing_field['label'] );

                        }

                        // Multi-file and multi-image fields.
                        if ( $wpai_type == 'file' && $listing_field['multiple'] == 1 ) {

                            /* We are going to allow 10 image field and 10 file fields maximum. */
                            $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = "multifile";

                            // Determine field type.
                            $mime_types = array_values( $listing_field['allowed_mime_types'] );
                            $mime_types = implode( " ", $mime_types );
                            if ( 
                                stristr( $mime_types, 'image' ) && (
                                    ! stristr( $mime_types, 'video' )       &&
                                    ! stristr( $mime_types, 'text' )        &&
                                    ! stristr( $mime_types, 'application' ) &&
                                    ! stristr( $mime_types, 'audio' )
                                ) ) {

                                // Output an IMAGE field and increase the function count number (so the next function will be used).
                                $this_field = 'wpai_img_func_fields';

                                $image_field_count = $image_field_count + 1;

                            } else {

                                // Output a FILE field and increase the function count number (so the next function will be used).
                                $this_field = 'wpai_file_func_fields';
                                
                                $file_field_count = $file_field_count + 1;

                            }
                            
                            if ( !array_key_exists( $this_field, $wpai_mylisting_addon_field_info ) ) {

                                // We'll use this array later to figure out how/where to save the images/files.
                                $wpai_mylisting_addon_field_info[ $this_field ] = array();

                            }
                        }

                        // Single image and single file fields.
                        if ( $wpai_type == 'file' && empty( $listing_field['multiple'] ) ) {
                            // Determine field type.
                            $mime_types = array_values( $listing_field['allowed_mime_types'] );
                            $mime_types = implode( " ", $mime_types );
                            if ( 
                                stristr( $mime_types, 'image' ) && (
                                    ! stristr( $mime_types, 'video' )       &&
                                    ! stristr( $mime_types, 'text' )        &&
                                    ! stristr( $mime_types, 'application' ) &&
                                    ! stristr( $mime_types, 'audio' )
                                ) ) {
                                
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'single_file'
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'image' );                    
                            } else {
                                
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'single_file'
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'file' );
                            }
                        }

                        switch ( true ) {

                            case ( $wpai_type == 'text' && $listing_field['slug'] == 'job_title' ):
                                    $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                        'type' => "job_title"
                                    );
                                    update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );
                                break;

                            case ( $wpai_type == 'text' && $listing_field['slug'] == 'job_description' ):
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => "job_description"
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );
                                break;

                            case ( $wpai_type == 'term-multiselect' ):
                                // Do nothing, it's imported in the Taxonomies, Categories, Tags section.                  
                                break;

                            case ( $wpai_type == 'term-select' ):
                                // Do nothing, it's imported in the Taxonomies, Categories, Tags section.
                                break;

                            case ( $wpai_type == 'term-checklist' ):
                                // Do nothing, it's imported in the Taxonomies, Categories, Tags section.
                                break;

                            case ( $wpai_type == 'file' && $listing_field['multiple'] == 1 ):
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = "multifile";
                                if ( !array_key_exists( 'wpai_mlao_clearfields', $wpai_mylisting_addon_field_info ) ) {

                                    $wpai_mylisting_addon_field_info['wpai_mlao_clearfields'] = array();

                                }

                                $wpai_mylisting_addon_field_info['wpai_mlao_clearfields'][] = $listing_field['slug'];

                                // Output proper field type with proper image function.
                                if ( $this_field == 'wpai_img_func_fields' ) {

                                    if ( $image_field_count < 11 ) {

                                        // This is used later to figure out how to save the data in the import function.
                                        $wpai_mylisting_addon_field_info[ $this_field ][ $image_field_count ] = $listing_field['slug'];

                                        update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                        // Since $this_field is 'wpai_img_func_fields', we know to output an image field.
                                        $wpai_mylisting_addon->import_images( 'wpai_mylisting_addon_import_images_' . $image_field_count, $listing_field['label'] );
                                    } else {

                                        // We only support 10 multi image fields.
                                        $wpai_mylisting_addon->add_title( 'Warning: Exceeded 10 multi-image fields. Cannot add any more.', null );

                                    }
                                } elseif ( $this_field == 'wpai_file_func_fields' ) {

                                    if ( $file_field_count < 11 ) {

                                        // This is used later to figure out how to save the data in the import function.
                                        $wpai_mylisting_addon_field_info[ $this_field ][ $file_field_count ] = $listing_field['slug'];
                                        update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                        // Since $this_field is 'wpai_file_func_fields', we know to output a multi file field.
                                        $wpai_mylisting_addon->import_files( 'wpai_mylisting_addon_import_files_' . $file_field_count, $listing_field['label'] );

                                    } else {

                                        // We only support 10 multi-file fields.
                                        $wpai_mylisting_addon->add_title( 'Warning: Exceeded 10 multi-file fields. Cannot add any more.', null );

                                    }
                                }
                                break;
                            
                            case ( $wpai_type == 'select-product' ):
                                $helper_text = 'Enter the Title, slug, SKU, or ID for the product.';

                                // This is used later to figure out how to save the data in the import function.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => "select-product"
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'text', null, $helper_text );
                                break;

                            case ( $wpai_type == 'select-products' ):
                                $helper_text = 'Enter the Title, slug, SKU, or ID for the product. Separate multiple products with commas.';
                                
                                // This is used later to figure out how to save the data in the import function.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => "select-products"
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'text', null, $helper_text );
                                break;

                            case ( $wpai_type == 'related-listing' ):
                                $helper_text = 'Enter the Title, slug, or ID for the listing.';

                                // This is used later to figure out how to save the data in the import function.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type'          => "related-listing",
                                    'listing_type'  => $listing_field['listing_type']
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );
                                
                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'text', null, $helper_text );
                                break;

                            case ( $wpai_type == 'title' ):
                                $wpai_mylisting_addon->add_title( $listing_field['label'], null );
                                break;

                            case ( $listing_field['slug'] == 'job_type' && $listing_field['label'] == "Job Type" ):
                                // This is handled in the Taxonomies area of the import, so we are not going to output anything.
                                break;

                            case ( $wpai_type == 'date' ):
                                $helper_text = 'Use any date that can be interpreted with strototime()';

                                // This is used later to figure out how to save the data in the import function.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type'      => 'date',
                                    'format'    => $listing_field['format']
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'text', null, $helper_text );
                                break;

                            case ( $wpai_type == 'number' ):
                                if ( $listing_field['min'] == '' && $listing_field['max'] == '' ) {
                                    $helper_text = 'Enter a numeric value. No text.';
                                } elseif ( $listing_field['min'] == '' && $listing_field['max'] != '' ) {
                                    $helper_text = 'Maximum value: ' . $listing_field['max'] . '. Enter a numeric value, no text.';
                                } elseif ( $listing_field['min'] != '' && $listing_field['max'] == '' ) {
                                    $helper_text = 'Minimum value: ' . $listing_field['min'] . '. Enter a numeric value, no text.';
                                } elseif ( $listing_field['min'] != '' && $listing_field['max'] != '' ) {
                                    $helper_text = 'Minimum value: ' . $listing_field['min'] . '. Maximum value: ' . $listing_field['max'] . '. Enter a numeric value, no text.';
                                } else {
                                    $helper_text = 'Enter a numeric value. No text.';
                                }

                                // Tell our import function how to save the data.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type'  => 'number',
                                    'min'   => $listing_field['min'],
                                    'max'   => $listing_field['max']
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'text', null, $helper_text );
                                break;

                            case ( $wpai_type == 'multiselect' ):
                                $helper_text = 'Separate multiple values with commas.';

                                // Tell our import function how to save the data.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'multiselect',
                                    'options' => $listing_field['options']
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'text', null, $helper_text );
                                break;

                            case ( $wpai_type == 'checkbox' ):

                                // Tell our import function how to save the data.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'checkbox',
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( 
                                    $listing_field['slug'], 
                                    $listing_field['label'], 
                                    'radio', 
                                    array(
                                        '0' => 'Off',
                                        '1' => 'On'
                                    )
                                );
                                break;

                            case ( $wpai_type == 'radio' ):
                                $new_options = array();

                                // We have to replace the $'s for compatibility with the rapid add-on api.
                                foreach ( $listing_field['options'] as $key => $option ) {
                                    $key = str_replace( "$", "MONEYSIGN", $key );
                                    $new_options[ $key ] = $option;
                                }

                                // Tell our import function how to save the data.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => "radio",
                                    'options' => $new_options
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field(
                                    $listing_field['slug'],
                                    $listing_field['label'],
                                    'radio',
                                    $new_options
                                );

                                break;
                            
                            case ( $wpai_type == "text" ):

                                // Tell import function how to save the data.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => "normal"
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], $wpai_type, null, null );
                                break;

                            case ( $wpai_type == "location" ):

                                // Tell import function how to save the data.                        
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'location'
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon_geo->geocode_field( $listing_field['slug'], $listing_field['label'] );
                                break;

                            case ( $wpai_type == "textarea" ):

                                // Tell import function how to save the data.
                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'normal'
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'textarea', null, null, false, null );
                                break;

                            case ( $wpai_type == "links" ):

                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'links',
                                    'default_label' => ''
                                );
                                if ( array_key_exists( 'default_label', $listing_field ) ) {
                                    $wpai_mylisting_addon_field_info[ $listing_field['slug'] ]['default_label'] = $listing_field['default_label'];
                                }
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'] . '_networks', $listing_field['label'], 'text', null, 'Enter the network name. Separate multiple with pipes e.g.: Facebook|Twitter|Instagram' );
                                $wpai_mylisting_addon->add_field( $listing_field['slug'] . '_networkurls', $listing_field['label'] . " URLs", 'text', null, 'Enter the network URL. Separate multiple with pipes e.g.: http://facebook.com/example|http://twitter.com/example|http://instagram.com/example' );
                                break;

                            case ( $wpai_type == "work-hours" ):

                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'work-hours'
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                // Title
                                $wpai_mylisting_addon->add_title( 'Work Hours', null );

                                // Array of fields. We'll remove "From" and "To" from the keys later.
                                $day_fields = array(
                                    'MondayFrom'    => 'work-hours_mon_from',
                                    'MondayTo'      => 'work-hours_mon_to',
                                    'TuesdayFrom'   => 'work-hours_tue_from',
                                    'TuesdayTo'     => 'work-hours_tue_to',
                                    'WednesdayFrom' => 'work-hours_wed_from',
                                    'WednesdayTo'   => 'work-hours_wed_to',
                                    'ThursdayFrom'  => 'work-hours_thu_from',
                                    'ThursdayTo'    => 'work-hours_thu_to',
                                    'FridayFrom'    => 'work-hours_fri_from',
                                    'FridayTo'      => 'work-hours_fri_to',
                                    'SaturdayFrom'  => 'work-hours_sat_from',
                                    'SaturdayTo'    => 'work-hours_sat_to',
                                    'SundayFrom'    => 'work-hours_sun_from',
                                    'SundayTo'      => 'work-hours_sun_to'
                                );

                                foreach ( $day_fields as $day => $slug ) {

                                    if ( stristr( $day, "From" ) ) {
                                        // Set variables to an opening/from hours field.
                                        $to_from = "From";
                                        $open_close = "Opening";
                                        $add_text = true;
                                    } else {
                                        // Set variables to a closing/to hours field.
                                        $to_from = "To";
                                        $open_close = "Closing";
                                        $add_text = false;
                                    }

                                    // Remove "From" or "To".
                                    $day = str_ireplace( array( "From", "To" ), "", $day );

                                    // Dynamic helper text based on the above variables.
                                    $helper_text = "" . $open_close . " hour for " . $day . ". Separate multiple opening hours with commas, e.g.: 7:00am, 1:00pm. Use 'open' for Open all day, 'closed' for Closed all day, and 'appointment' for By appointment only.";

                                    if ( $add_text ) {
                                        // This is a "From" field, so we need to add the header text with the day name. 
                                        $wpai_mylisting_addon->add_text( '<span style="font-size: 1.1em"><strong>' . $day . '</strong></span>' ); 
                                    }

                                    // Add the import field for "From"/"To" hours field.
                                    $wpai_mylisting_addon->add_field( $slug, $to_from, 'text', null, $helper_text );
                                }
                                
                                // Timezone
                                $wpai_mylisting_addon->add_text( '<span style="font-size: 1.1em"><strong>Timezone</strong></span>' );
                                $wpai_mylisting_addon->add_field( 'work-hours_timezone', 'Enter Timezone', 'text', null, "Enter a valid timezone. Example values: \"America/Los Angeles\", \"America/Indiana/Indianapolis\" (without quotes). Leave blank to use UTC. Start typing to search for timezone from drop down." );

                                break;
                            
                            case ( $wpai_type == "wp_editor" ):

                                $wpai_mylisting_addon_field_info[ $listing_field['slug'] ] = array(
                                    'type' => 'normal'
                                );
                                update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                                $wpai_mylisting_addon->add_field( $listing_field['slug'], $listing_field['label'], 'wp_editor', null, null, false, null );
                                
                                break;

                            default:
                                break;
                        }
                    }

                    // ***** BEGIN ADDITIONAL FIELDS
                    $wpai_mylisting_addon->add_options(
                        null,
                        'Listing Settings',
                        array(
                            $wpai_mylisting_addon->add_field( 'job_expires', 'Listing Expiry Date', 'text', null, 'Use any date that can be interpreted with strototime()' ),
                            $wpai_mylisting_addon->add_field( 'user_package_id', 'Switch Payment Package', 'text', null, 'Enter the payment package ID #. This will switch the payment package for this listing. This will not modify/effect package limit or listing data such as duration or featured status.' ),
                            $wpai_mylisting_addon->add_field(
                                'claimed',
                                'Is listing claimed?',
                                'radio',
                                array(
                                    '0'     => 'No',
                                    '1'     => 'Yes'
                                )
                            ),
                            $wpai_mylisting_addon->add_field(
                                'featured',
                                'Priority',
                                'radio',
                                array(
                                    '0'     => 'Normal',
                                    '1'     => 'Featured',
                                    '2'     => 'Promoted'
                                )
                            )
                        )
                    );

                    // Update field info for all additional fields.

                    // ### Listing Expiry Date
                    $wpai_mylisting_addon_field_info[ 'job_expires' ] = array(
                        'type'      => 'date',
                        'format'    => 'date'
                    );
                    update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );
                    
                    // ### Switch Payment Package
                    $wpai_mylisting_addon_field_info[ 'user_package_id' ] = array(
                        'type' => 'switch-payment-package'
                    );
                    update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                    // ### Is listing claimed
                    // Tell our import function how to save the data.
                    $wpai_mylisting_addon_field_info[ 'claimed' ] = array(
                        'type' => 'claimed',
                        'options' => array(
                            '0'     => 'No',
                            '1'     => 'Yes'
                        )
                    );
                    update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );
                    

                    // ### Priority

                    $wpai_mylisting_addon_field_info[ 'featured' ] = array(
                        'type' => 'priority',
                        'options' => array(
                            '0'     => 'Normal',
                            '1'     => 'Featured',
                            '2'     => 'Promoted'
                        )
                    );
                    update_option( 'wpai_mylisting_addon_field_info', $wpai_mylisting_addon_field_info );

                    // Add all fields as fields to be saved.                    
                    $additional_fields = array(
                        'job_expires',
                        'user_package_id',
                        'claimed',
                        'featured'
                    );
                    foreach ( $additional_fields as $additional_field ) {
                        if ( ! in_array( $additional_field, $wpai_addon_save_fields ) ) {
                            $wpai_addon_save_fields[] = $additional_field;
                        }
                    }
                    update_option( 'wpai_mylisting_addon_save_fields', $wpai_addon_save_fields );

                    // *** END ADDITIONAL FIELDS
                }
            }
        }
    }
}

function mylisting_addon_admin_scripts() {
	$current_screen = get_current_screen();
	
	// Check that we're on an import page
	if ( ( $current_screen->id == "all-import_page_pmxi-admin-import" || $current_screen->id == "all-import_page_pmxi-admin-manage" ) ) {
		
        wp_enqueue_script( 'mylisting-addon-js', plugin_dir_url( __FILE__ ) . 'js/mylisting-addon-js.js', array( 'jquery' ), '1.0.0', true );
	
	}
}

/**
* This gets the post type that we're importing so that we can conditionally show fields.
**/
function mylisting_addon_get_post_type() {
    $custom_type = false;
    // Get import ID from URL or set to 'new'
    if ( isset( $_GET['import_id'] ) ) {
        $import_id = $_GET['import_id'];
    } elseif ( isset( $_GET['id'] ) ) {
        $import_id = $_GET['id'];
    }
    if ( empty( $import_id ) ) {
        $import_id = 'new';
    }
    // Declaring $wpdb as global to access database
    global $wpdb;
    // Get values from import data table
    $imports_table = $wpdb->prefix . 'pmxi_imports';
    // Get import session from database based on import ID or 'new'
    $import_options = $wpdb->get_row( $wpdb->prepare("SELECT options FROM $imports_table WHERE id = %d", $import_id), ARRAY_A );
    // If this is an existing import load the custom post type from the array
    if ( ! empty($import_options) )	{
        $import_options_arr = unserialize($import_options['options']);
        $custom_type = empty( $import_options_arr['listing_type'] ) ? '' : $import_options_arr['listing_type'];
    } else {
        // If this is a new import get the custom post type data from the current session
        $import_options = $wpdb->get_row( $wpdb->prepare("SELECT option_name, option_value FROM $wpdb->options WHERE option_name = %s", '_wpallimport_session_' . $import_id . '_'), ARRAY_A );				
        $import_options_arr = empty($import_options) ? array() : unserialize($import_options['option_value']);
        $custom_type = empty($import_options_arr['custom_type']) ? '' : $import_options_arr['custom_type'];		
    }
    return $custom_type;
}

function wpai_mylisting_addon_import( $post_id, $data, $import_options, $article ) {
    global $wpai_mylisting_addon;
    global $wpai_mylisting_addon_funcs;
    global $wpai_mylisting_addon_geo;

    // Set title and content
    update_post_meta( $post_id, '_job_title', $article['post_title'] );
    update_post_meta( $post_id, '_job_description', $article['post_content'] );

    $fields = get_option( 'wpai_mylisting_addon_save_fields' );
    $save_type = get_option( 'wpai_mylisting_addon_field_info' );
    $fields = maybe_unserialize( $fields );
    $save_type = maybe_unserialize( $save_type );

    // Clear image fields to override import settings.
    if ( array_key_exists( 'wpai_mlao_clearfields', $save_type ) ) {
        $clear_fields = $save_type['wpai_mlao_clearfields'];
        if ( !empty( $clear_fields ) && is_array( $clear_fields ) ) {
            if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_image( $import_options ) ) {
                foreach ( $clear_fields as $field_to_clear ) {
                    $real_field_name = "_" . $field_to_clear;
                    delete_post_meta( $post_id, $real_field_name );
                }
            }
        }
    }


    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $save_type ) && is_array( $save_type[ $field ] ) && array_key_exists( 'type', $save_type[ $field ] ) ) {
            $type = $save_type[ $field ]['type'];
            
            // This is what we need to save in the postmeta table.
            $real_field_name = "_" . $field;

            switch ( true ) {

                // This is a normal text field.
                case $type == 'normal':
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        if ( !empty( $data[ $field ] ) ) {
                            update_post_meta( $post_id, $real_field_name, $data[ $field ] );
                        }
                    }
                    break;

                // Related singular product.
                case $type == "select-product":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options) ) {
                        if ( !empty( $data[ $field ] ) ) {
                            if ( $products_array = $wpai_mylisting_addon_funcs->get_wooco_prods( $data[ $field ] ) ) {
                                $final_prod = reset( $products_array );
                                update_post_meta( $post_id, $real_field_name, $final_prod );
                            } else {
                                $wpai_mylisting_addon->log( "<strong>WARNING:</strong> Could not find any products for the field " . $real_field_name . "." );
                            }
                        }
                    }
                    break;

                // Related products.
                case $type == "select-products":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options) ) {
                        if ( !empty( $data[ $field ] ) ) {
                            if ( $products_array = $wpai_mylisting_addon_funcs->get_wooco_prods( $data[ $field ] ) ) {
                                update_post_meta( $post_id, $real_field_name, $products_array );
                            } else {
                                $wpai_mylisting_addon->log( "<strong>WARNING:</strong> Could not find any products for the field " . $real_field_name . "." );
                            }
                        }
                    }
                    break;

                // Related listings.
                case $type == "related-listing":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options) ) {
                        if ( !empty( $data[ $field ] ) ) {
                            // The list type is separate with a colon, for example:
                            // related-listing:the_listing_type
                            $list_type = $save_type[ $field ]['listing_type'];
                            if ( $listing = $wpai_mylisting_addon_funcs->get_related_listings( $data[ $field ], $list_type ) ) {
                                update_post_meta( $post_id, $real_field_name, $listing );
                            } else {
                                $wpai_mylisting_addon->log( "<strong>WARNING:</strong> Could not find any related listings for field " . $real_field_name . "." );
                            }
                        }
                    }
                    break;

                // Date field. Could include time.
                case $type == "date":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        if ( !empty( $data[ $field ] ) ) {
                            $format = str_replace( array( 'datetime', 'date' ), array( 'Y-m-d H:i:s', 'Y-m-d' ), $save_type[ $field ]['format'] );
                            $date = date( $format, strtotime( $data[ $field ] ) );
                            if ( $date == '1970-01-01' ) {
                                $wpai_mylisting_addon->log( '<strong>WARNING:</strong> Date for field "' . $real_field_name . '" is invalid and produced: 1970-01-1. Skipping field.' );
                            } else {
                                update_post_meta( $post_id, $real_field_name, $date );
                                update_option( 'wpaimlao_expiry_date', 'yes' );
                            }
                        }
                    }
                    break;

                // Number field. Value must be numeric.
                case $type == "number":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $min = $save_type[ $field ]['min'];
                        $max = $save_type[ $field ]['max'];
                        $number = $data[ $field ];

                        if ( is_numeric( $number ) ) {
                            if ( !empty( $max ) && $number > $max ) {
                                $wpai_mylisting_addon->log( '<strong>WARNING:</strong> Number exceeds max setting for field ' . $real_field_name . '. Skipping.' );
                            } elseif ( !empty( $min ) && $number < $min ) {
                                $wpai_mylisting_addon->log( '<strong>WARNING:</strong> Number is below min setting for field ' . $real_field_name . '. Skipping.' );
                            } else {
                                update_post_meta( $post_id, $real_field_name, $number );
                            }
                        } else {
                            $wpai_mylisting_addon->log( '<strong>WARNING:</strong> Data is not numeric for field ' . $real_field_name . '. Skipping.' );
                        }
                    }

                    break;

                // Multiple selections.
                case $type == "multiselect":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {

                        $options = $save_type[ $field ]['options'];
                        if ( !empty( $data[ $field ] ) ) {
                            $imported_options = explode( ",", $data[ $field ] );
                        }
                        $final_values = array();

                        if ( !empty( $options ) && !empty( $imported_options ) ) {
                            for ( $i = 0; $i < count( $imported_options ); $i++ ) { 
                                $option = trim( $imported_options[ $i ] );
                                foreach ( $options as $key => $value ) {
                                    if ( strtolower( $option ) == strtolower( $key ) || strtolower( $option ) == strtolower( $value ) ) {
                                        $final_values[] = $key;
                                    }
                                }
                            }

                            update_post_meta( $post_id, $real_field_name, $final_values );
                        }

                    }

                    break;

                // Checkbox field.
                case $type == "checkbox":

                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {

                        $imported_value = trim( $data[ $field ] );
                        if ( !empty( $imported_value ) ) {
                            $imported_value = str_replace( array( 'no', 'off', 'unchecked', 'yes', 'on', 'checked' ), array( '0', '0', '0', '1', '1', '1' ), $imported_value );
                        } else {
                            $imported_value = 0;
                        }

                        update_post_meta( $post_id, $real_field_name, $imported_value );

                    }
                    break;


                case $type == "priority":

                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        if ( ! is_numeric( $data[ $field ] ) ) {
                            $wpai_mylisting_addon->log( "<strong>PRIORITY ERROR:</strong> Priority must be a numeric value." );
                        } else {
                            if ( $data[ $field ] == 2 ) {

                                // It's "Featured", so we need to change the menu order.
                                $args = array(
                                    'ID' => $post_id,
                                    'menu_order' => '-1'
                                );                                
                                wp_update_post( $args );

                            }
                            update_post_meta( $post_id, $real_field_name, $data[ $field ] );
                        }                        
                    }

                    break;
                // Radio field.
                case $type == "radio":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $options = $save_type[ $field ]['options'];
                        $options = maybe_unserialize( $options );

                        $new_options = array();

                        foreach ( $options as $key => $option ) {
                            $key = str_replace( "MONEYSIGN", "$", $key );
                            $new_options[ $key ] = $option;
                        }

                        $data[ $field ] = str_replace( "MONEYSIGN", "$", $data[ $field ] );

                        foreach ( $new_options as $key => $option ) {
                            if ( strtolower( $key ) == strtolower( $data[ $field ] ) || strtolower( $option ) == strtolower( $data[ $field ] ) ) {
                                update_post_meta( $post_id, $real_field_name, $key );
                                break;
                            }
                        }
                    }

                    break;

                // Single file import field.
                case $type == "single_file":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        if ( !empty( $data[ $field ]['attachment_id'] ) ) {
                            $url = wp_get_attachment_url( $data[ $field ]['attachment_id'] );
                            update_post_meta( $post_id, $real_field_name, $url );
                        }
                    }
                    break;

                // Location. Uses Google Geocoding API.
                case $type == "location":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $wpai_mylisting_addon_geo->import_location( $post_id, $real_field_name, $data, $import_options );
                    }

                    break;

                // This is the "Social Networks" field.
                case $type == "links":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $network_key = $field . "_networks";
                        $urls_key = $field . "_networkurls";
                        
                        $networks = explode( "|", $data[ $network_key ] );
                        $urls = explode( "|", $data[ $urls_key ] );

                        if ( ( !empty( $networks ) && !empty( $urls ) ) && ( count( $networks ) == count( $urls ) ) ) {
                            $wpai_mylisting_addon_funcs->import_networks( $post_id, $real_field_name, $networks, $urls );
                        } elseif ( empty( $networks ) || empty( $urls ) ) {
                            $wpai_mylisting_addon->log( "<strong>WARNING:</strong> Network or URL data is empty in import template for Social Networks. Skipping.");
                        } elseif ( count( $networks ) != count( $urls ) ) {
                            $wpai_mylisting_addon->log( "<strong>WARNING:</strong> Social networks field can't be imported. Please import the same number of networks and urls." );
                        }
                    }
                    
                    break;

                // Open/close times for each day of the week.
                case $type == "work-hours":
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $not_empty = false;
                        $fields = array( 'work-hours_mon_from', 'work-hours_mon_to', 'work-hours_tue_from', 'work-hours_tue_to', 'work-hours_wed_from', 'work-hours_wed_to', 'work-hours_thu_from', 'work-hours_thu_to', 'work-hours_fri_from', 'work-hours_fri_to', 'work-hours_sat_from', 'work-hours_sat_to', 'work-hours_sun_from', 'work-hours_sun_to' );

                        foreach ( $fields as $field ) {
                            if ( ! empty( $data[ $field ] ) ) {
                                $not_empty = true;
                                break;
                            }
                        }
                        if ( $not_empty ) {
                            $wpai_mylisting_addon_funcs->import_work_hours( $post_id, $data, '_work_hours' );
                        }
                    }
                    break;

                case $type == 'claimed':
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $helper_map = array(
                            'claimed'       => '1',
                            'yes'           => '1',
                            'not claimed'   => '0',
                            'no'            => '0'
                        );

                        $data[ $field ] = str_replace( array_keys( $helper_map ), $helper_map, strtolower( $data[ $field ] ) );

                        if ( function_exists( 'update_field' ) ) {
                            update_field( $real_field_name, $data[ $field ], $post_id );
                        }

                    }
                    break;

                case $type == 'switch-payment-package':
                    if ( empty( $article['ID'] ) || $wpai_mylisting_addon->can_update_meta( $real_field_name, $import_options ) ) {
                        $wpai_mylisting_addon_funcs->import_payment_package( $post_id, $real_field_name, $data[ $field ] );
                    }

                    break;
            }
        }
    }
}

add_filter( 'pmxi_custom_types', array( $wpai_mylisting_addon_funcs, 'add_post_types' ), 10, 2 );
add_filter( 'pmxi_custom_types', array( $wpai_mylisting_addon_funcs, 'remove_post_types' ), 10, 2 );
add_filter( 'pmxi_options_options', array( $wpai_mylisting_addon_funcs, 'filter_import_options' ), 10, 2 );
add_filter( 'wpai_custom_selected_post', array( $wpai_mylisting_addon_funcs, 'select_post_type_in_settings' ), 10, 4 );


add_action( 'pmxi_before_xml_import', array( $wpai_mylisting_addon_funcs, 'clear_options' ), 10 );
add_action( 'pmxi_after_xml_import', array( $wpai_mylisting_addon_funcs, 'clear_options' ), 10 );
add_action( 'pmxi_saved_post', array( $wpai_mylisting_addon_funcs, 'after_post_save_operations' ), 10, 3 );
add_action( 'admin_enqueue_scripts', 'mylisting_addon_admin_scripts' );
add_action( 'admin_notices', array( $wpai_mylisting_addon_funcs, 'admin_notices' ) );