<?php
// Filter: wpai_mylisting_addon_default_post_type_icon
// Description: Choose the default icon for all list types in the drop down during step 1 of an import.
// Params:
// $icon - (string) dashicon slug or URL to image for the default icon.

// Example:

add_filter( 'wpai_mylisting_addon_default_post_type_icon', 'my_set_default_icon', 10, 1 );

function my_set_default_icon( $icon ) {
    $icon = 'https://i.imgur.com/5oMzW8k.png';
    return $icon; 
}

// Filter: wpai_mylisting_addon_all_post_type_icons
// Description: Set custom icon images for your listing types.
// Params:
// $icons - (array) contains all icons for the listing types.
// $posts - (array) contains all listing type posts.

// Example: set custom icons for "Cars", "Homes", and "Boats" listing types.

add_filter( 'wpai_mylisting_addon_all_post_type_icons', 'my_set_custom_icon', 10, 2 );

function my_set_custom_icon( $icons, $posts ) {
    if ( ( $key = array_search( 'cars', $posts ) ) !== false ) {
        $icons[ $key ] = 'https://i.imgur.com/4kiORkS.png';
    }

    if ( ( $key = array_search( 'homes', $posts ) ) !== false ) {
        $icons[ $key ] = 'https://i.imgur.com/mbOL2Aj.png';
    }

    if ( ( $key = array_search( 'boats', $posts ) ) !== false ) {
        $icons[ $key ] = 'https://i.imgur.com/5oMzW8k.png';
    }
    return $icons;
}

// Filter: wpai_mylisting_addon_post_types_to_remove
// Description: Determine which posts will be displayed in the post type drop down on step 1 of an import.
// Params:
// $removed_posts - (array) the default list of post types that will be removed.

// Example: remove WooCommerce post types from the list

add_filter( 'wpai_mylisting_addon_remove_post_types', 'my_remove_wooco_posts', 10, 1 );

function my_remove_wooco_posts( $removed_posts ) {

    $remove_these = array( 'shop_order', 'shop_coupon', 'product' );
    $final = array_merge( $removed_posts, $remove_these );

    return $final;
}