<?php

/*** Image and File import functions ***/

// Main Image Import Function.
// This way we don't have to edit 20 places in the code if something needs to change.

function wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field ) {
    global $wpai_mylisting_addon;

    // Get all current images.
    $current_imgs = get_post_meta( $post_id, $field, true );

    // In case it's empty, turn it into an array. Otherwise, unserialize it.
    if ( empty( $current_imgs ) ) {
        $current_imgs = array();
    } else {
        $current_imgs = maybe_unserialize( $current_imgs );
    }

    $img_url = wp_get_attachment_url( $attachment_id );

    if ( !in_array( $img_url, $current_imgs ) ) {
    // Add the current image to it.
        $current_imgs[] = wp_get_attachment_url( $attachment_id );

        // Update the gallery.
        update_post_meta( $post_id, $field, $current_imgs );
    }
}

// Get Field Function

function wpai_mylisting_addon_import_funcgetfield( $key, $inner_key ) {
    if ( $info = get_option( 'wpai_mylisting_addon_field_info' ) ) {
        if ( is_array( $info ) && array_key_exists( $key, $info ) && array_key_exists( $inner_key, $info[ $key ] ) ) {
            $field = "_" . $info[ $key ][ $inner_key ];
            return $field;
        }
    }
}

// 10 Image Functions

function wpai_mylisting_addon_import_images_1 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 1;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_2 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 2;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_3 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 3;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_4 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 4;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_5 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 5;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_6 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 6;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_7 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 7;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_8 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 8;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_9 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 9;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_images_10 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_img_func_fields';
    $inner_key = 10;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

// 10 File Import Functions

function wpai_mylisting_addon_import_files_1 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 1;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_2 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 2;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_3 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 3;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_4 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 4;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_5 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 5;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_6 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 6;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_7 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 7;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_8 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 8;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_9 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 9;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}

function wpai_mylisting_addon_import_files_10 ( $post_id, $attachment_id, $image_filepath, $import_options ) {
    global $wpai_mylisting_addon;
    $key = 'wpai_file_func_fields';
    $inner_key = 10;
    $field = wpai_mylisting_addon_import_funcgetfield( $key, $inner_key );

    wpai_mylisting_addon_import_realfunc( $post_id, $attachment_id, $image_filepath, $import_options, $field );
}