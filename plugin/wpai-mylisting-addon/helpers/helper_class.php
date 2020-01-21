<?php
/***
 * Helper class for getting post types and other functions.
 */
if ( !class_exists( 'wpai_mylisting_addon_helper' ) ) {
    class wpai_mylisting_addon_helper {
        public function admin_notices() {
            // The MyListing Add-On only works with Pro v4.5.6 or greater, or Free v3.5.0 or greater.
            // See lines 16-24 in wpai-mylisting-addon.php for more info.
            $wpai_show_error_var = get_option( 'wpai_mlao_old_wpai', false );
            if ( ! empty( $wpai_show_error_var ) ) {
                if ( $wpai_show_error_var['show_error'] == 'yes' ) {
                    $class = 'notice notice-error is-dismissable';
                    $message = __( 'You must be running WP All Import ' . $wpai_show_error_var['version'] . ' ' . $wpai_show_error_var['display_version'] . ' or later in order to use the MyListing Add-On. Please update WP All Import, then re-activate the WP All Import MyListing Add-On.', 'wp-all-import' );

                    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                    deactivate_plugins( WPAI_MYLISTING_PLUGIN_PATH );
                }
            }
            delete_option( 'wpai_mlao_old_wpai' );
        }
        /***
         * This returns an array of the listing types that have been set up. Example:
         * 
         * 'Listing Type Post Title' => 'the-slug-of-the-listing-type'
         * 
         * array (
         * 'Car Listings' => 'car-listings',
         * 'Property Listings' => 'property-listings',
         * 'Ads' => 'ads'
         * );
         */
        public function get_types() {
            global $wpdb;
            $post_table = $wpdb->prefix . "posts";
            $meta_table = $wpdb->prefix . "postmeta";
            $types = array();

            $query = "SELECT `ID`, `post_title`, `post_name` FROM `" . $post_table . "` WHERE `post_type` = 'case27_listing_type'";

            if ( $results = $wpdb->get_results( $query ) ) {
                foreach ( $results as $result ) {
                    $types[] = array(
                        'listing_type' => $result->post_title,
                        'listing_id' => $result->ID,
                        'listing_name' => $result->post_name
                    );
                }
                return $types;
            }
        }
        /***/

        // This function lets us import the post as the post type "job_listing" even though we selected the "Listing Type" in step 1.
        public function filter_import_options( $DefaultOptions, $isWizard ){
    
            $the_active_theme_obj = wp_get_theme();
            $theme_name = null;
            $parent_name = null;

            if ( ! empty( $the_active_theme_obj ) ) {
                $theme_name = $the_active_theme_obj->name;
                if ( ! empty( $the_active_theme_obj->parent() ) ) {
                    // It's a child theme.
                    $parent_name = $the_active_theme_obj->parent()->get( 'Name' );
                }
            }

            if ( $theme_name == "My Listing" || $parent_name == "My Listing" ) {
                if ( is_plugin_active( 'wpai-mylisting-addon/wpai-mylisting-addon.php' ) ) {
                    global $wpdb;
                    $query = "SELECT `ID`, `post_title`, `post_name` FROM `" . $wpdb->prefix . "posts` WHERE `post_type` = 'case27_listing_type'";

                    if ( $results = $wpdb->get_results( $query ) ) {
                        foreach ( $results as $result ) {
                            if ($DefaultOptions['custom_type'] == $result->post_name){
                                $DefaultOptions['custom_type'] = 'job_listing';
                                $DefaultOptions['listing_type'] = $result->post_name;
                            }                    
                        }
                    }
                }
            }

            return $DefaultOptions;
        }

        /*** Returns what type of field we're going to output in the add-on
         * Including all of the relevant information for the field.
         * */
        public function get_field_output( $field ) {
            $map = array(
                'term-multiselect'          => 'term-multiselect',
                'text'                      => 'text',
                'textarea'                  => 'textarea',
                'file'                      => 'file',
                'url'                       => 'text',
                'date'                      => 'date',
                'related-listing'           => 'related-listing',
                'work-hours'                => 'work-hours',
                'select-product'            => 'select-product',
                'select-products'           => 'select-products',
                'links'                     => 'links',
                'select'                    => 'radio',
                'form-heading'              => 'title',
                'location'                  => 'location',
                'email'                     => 'text',
                'wp-editor'                 => 'wp_editor',
                'password'                  => 'text',
                'number'                    => 'number',
                'multiselect'               => 'multiselect',
                'checkbox'                  => 'checkbox', 
                'radio'                     => 'radio',
                'term-select'               => 'term-select',
                'term-checklist'            => 'term-checklist',
                'job_expires'               => 'date',
                'priority'                  => 'priority',
                'claimed'                   => 'claimed',
                'switch-payment-package'    => 'switch-payment-package'
            );
            
            return ( isset( $map[ $field ] ) ) ? $map[ $field ] : 'text';
        }
        /***/

        /***
         * This adds the listing types as faux post types in step 1 of the import.
         */
        public function add_post_types( $custom_types, $type = '' ) {
            global $wpai_mylisting_addon;
            if ( $type == 'all_types' ) {
                $the_active_theme_obj = wp_get_theme();
                $theme_name = null;
                $parent_name = null;

                if ( ! empty( $the_active_theme_obj ) ) {
                    $theme_name = $the_active_theme_obj->name;
                    if ( ! empty( $the_active_theme_obj->parent() ) ) {
                        // It's a child theme.
                        $parent_name = $the_active_theme_obj->parent()->get( 'Name' );
                    }
                }

                if ( $theme_name == "My Listing" || $parent_name == "My Listing" ) {
                    if ( is_plugin_active( 'wpai-mylisting-addon/wpai-mylisting-addon.php' ) ) {
                        global $wpdb;
                        $query = "SELECT `ID`, `post_title`, `post_name`, `post_status` FROM `" . $wpdb->prefix . "posts` WHERE `post_type` = 'case27_listing_type'";

                        if ( $results = $wpdb->get_results( $query ) ) {

                            // Arrays to store post types. Will use these to alter the drop down on step 1 of the import.
                            $move_these = array();
                            $move_to = array();

                            $post_types_with_icons = array();
                            $icons = array();

                            // Store the dashicon.
                            $default_icon = apply_filters( 'wpai_mylisting_addon_default_post_type_icon', 'dashicons-location' );

                            foreach ( $results as $result ) {
                                if ( ! array_key_exists( $result->post_name, $custom_types ) && $result->post_status == 'publish' ) {
                                    $custom_types[ $result->post_name ] = (object) array( 'name' => $result->post_name, 'label' => $result->post_title, 'labels' );
                                    $custom_types[ $result->post_name ]->labels = (object) array( 'name' => $result->post_title );

                                    // Store all listing types so we can move them to the top of the drop down.
                                    $move_these[]               = $result->post_name;
                                    $move_to[]                  = 0;
                                    
                                    $post_types_with_icons[] = $result->post_name;
                                    $icons[]                 = $default_icon;                                     
                                }
                            }
                        }
                    }
                }
            }
            
            if ( ! empty( $move_these ) ) {

                // Move all listing types to the top.
                $wpai_mylisting_addon->move_post_type( $move_these, $move_to );

            }

            if ( ! empty( $post_types_with_icons ) ) {

                // Apply custom icon to all listing types.
                $icons = apply_filters( 'wpai_mylisting_addon_all_post_type_icons', $icons, $post_types_with_icons );
                $wpai_mylisting_addon->set_post_type_image( $post_types_with_icons, $icons );

            }            

            return $custom_types;
        }
        /***/

        public function remove_post_types( $custom_types, $type = '' ) {
            global $wpai_mylisting_addon;
            $rpt = apply_filters( 'wpai_mylisting_addon_remove_post_types', array( 'claim', 'case27_listing_type', 'case27_user_package', 'case27_report', 'cts_promo_package', 'elementor_library', 'user_request', 'wpcf7_contact_form', 'job_listing' ) );
            if ( ! empty( $rpt ) && is_array( $rpt ) ) {
                $wpai_mylisting_addon->remove_post_type( $rpt );
            }
            return $custom_types;
        }

        public function clear_options() {
            delete_option( 'wpai_mylisting_addon_save_fields' );
            delete_option( 'wpai_mylisting_addon_field_info' );
            delete_option( 'wpai_mylisting_addon_listing_type' );
        }

        public function get_term( $data, $type = 'single', $taxonomy = 'category' ) {
            global $wpdb;
            switch ( $type ) {
                case 'single':
                    if ( !empty( $data ) ) {
                        $data = trim( $data );
                        if ( $term = get_term_by( 'name', $data, $taxonomy ) ) {
                            return $term->term_id;
                        } elseif( $term = get_term_by( 'slug', sanitize_title_with_dashes( $data ), $taxonomy ) ) {
                            return $term->term_id;
                        } else {
                            $term = wp_insert_term( $data, $taxonomy );
                            return $term['term_id'];
                        }
                    }
                    break;
                
                case 'multi':
                    if ( !empty( $data ) ) {
                        $data = explode( ",", $data );
                        foreach ( $data as $key => $value ) {
                            $value = trim( $value );
                            if ( $term = get_term_by( 'name', $value, $taxonomy ) ) {
                                $data[ $key ] = $term->term_id;
                            } elseif ( $term = get_term_by( 'slug', sanitize_title_with_dashes( $value ), $taxonomy ) ) {
                                $data[ $key ] = $term->term_id;
                            } else {
                                $term = wp_insert_term( $value, $taxonomy );
                                $data[ $key ] = $term['term_id'];
                            }
                        }
                    }
                    return $data;
                    break;
            }
        }

        public function set_terms( $post_id, $terms, $taxonomy, $single_or_multi, $field, $append = false ) {
            switch ( $single_or_multi ) {
                case "single":
                    if ( wp_set_object_terms( $post_id, $terms, $taxonomy, $append ) ) {
                        // *** This adds a custom field to the post so that the customer can choose it in the import settings to be updated.
                        $this->add_wpaimeta( $post_id, $field, $terms, 'terms' );
                        $return = true;
                    } else {
                        $return = false;
                    }

                    return $return;

                    break;

                case "multi":
                    if ( wp_set_object_terms( $post_id, array_values( $terms ), $taxonomy, $append ) ) {
                        // *** This adds a custom field to the post so that the customer can choose it in the import settings to be updated.
                        $this->add_wpaimeta( $post_id, $field, array_values( $terms ), 'terms' );
                        $return = true;
                    } else {
                        $return = false;
                    }

                    return $return;

                    break;
                default:
                    return false;
                    break;
            }
        }

        // *** This function adds a post meta field that the customer can choose to be updated in the import settings.
        public function add_wpaimeta( $post_id, $field, $data, $type = 'terms' ) {
            update_post_meta( $post_id, $field, $data );
        }

        public function wooco_prod_query( $field, $value ) {
            global $wpdb;
            $product_id = false;

            switch ( $field ) {
                case 'titles':

                    if ( $product = $wpdb->get_row( $wpdb->prepare( 
                                "
                                    SELECT `ID` FROM `" . $wpdb->prefix . "posts` 
                                    WHERE ( `post_type` = 'product' OR `post_type` = 'product_variation' ) 
                                    AND `post_title` = '%s'
                                ", $value ) ) ) {
                            $product_id = $product->ID;
                        }

                    break;

                case 'slugs':

                    if ( $product = $wpdb->get_row( $wpdb->prepare(
                                "
                                    SELECT `ID` FROM `" . $wpdb->prefix . "posts` 
                                    WHERE ( `post_type` = 'product' OR `post_type` = 'product_variation' ) 
                                    AND `post_name` = '%s'
                                ", $value ) ) ) {
                            $product_id = $product->ID;
                        }

                    break;

                case 'skus':
                    if ( 
                        $product = $wpdb->get_row( $wpdb->prepare(
                                "
                                    SELECT `post_id` FROM `" . $wpdb->prefix . "postmeta` 
                                    WHERE `meta_key` = '_sku' 
                                    AND `meta_value` = '%s'
                                ", $value ) ) ) {
                            $product_id = $product->post_id;
                        }

                    break;

                case 'ids':
                    if ( 
                        $product = $wpdb->get_row( $wpdb->prepare(
                                "
                                    SELECT `ID` FROM `" . $wpdb->prefix . "posts` 
                                    WHERE ( `post_type` = 'product' OR `post_type` = 'product_variation' ) 
                                    AND `ID` = '%d'
                                ", $value ) ) ) {
                            $product_id = $product->ID;
                        }

                    break;
                    
                default: 
                    break;
            }
            return $product_id;
        }

        public function get_wooco_prods( $prod_list ) {
            global $wpdb;
            $prod_list = explode( ",", $prod_list );
            $prod_ids = array();
            $queries = array( 'titles', 'slugs', 'skus', 'ids' );
            
            // All queries we need to check.

            foreach ( $prod_list as $value ) {
                $value = trim( $value );
                foreach ( $queries as $query ) {
                    if ( $product = $this->wooco_prod_query( $query, $value ) ) {
                        if ( !in_array( $product, $prod_ids ) ) {
                            $prod_ids[] = $product;
                        }
                        break;
                    }
                }           
            }

            if ( !empty( $prod_ids ) ) {
                return $prod_ids;
            } else {
                return false;
            }
        }

        public function related_listing_query( $listing, $query, $type = '' ) {
            global $wpdb;
            $table = $wpdb->prefix . "posts";
            $post_id = false;
            // Title, slug, or ID.

            switch ( $query ) {
                case 'title':
                    if ( 
                        $post = $wpdb->get_row( $wpdb->prepare(
                            "
                                SELECT `ID` FROM `" . $table . "`
                                WHERE `post_title` = '%s'
                            ", $listing ) ) ) {
                        $post_id = $post->ID;
                    }

                    break;
                
                case 'slug':
                    if ( 
                        $post = $wpdb->get_row( $wpdb->prepare(
                            "
                                SELECT `ID` FROM `" . $table . "`
                                WHERE `post_name` = '%s'
                            ", $listing ) ) ) {
                        $post_id = $post->ID;
                    }

                    break;

                case 'id':
                    if ( 
                        $post = $wpdb->get_row( $wpdb->prepare(
                            "
                                SELECT `ID` FROM `" . $table . "`
                                WHERE `ID` = '%d'
                            ", $listing ) ) ) {
                        $post_id = $post->ID;
                    }

                    break;

                default:
                    break;
            }
            if ( $post_id ) {
                if ( empty( $type ) ) {
                    // They didn't specify a type, so just return the found post.
                    return $post_id;
                } else {
                    // They specified the type, so we need to make sure it matches.
                    $post_type = get_post_meta( $post_id, '_case27_listing_type', true );
                    if ( $post_type == $type ) {
                        // We found a post of the correct listing type, return it!
                        return $post_id;
                    } else {
                        // The type didn't match, return false.
                        return false;
                    }
                }
            } else {
                // Nothing found.
                return false;
            }
        }

        public function get_related_listings( $listing, $type = '' ) {
            $listing = trim( $listing );
            $queries = array( 'title', 'slug', 'id' );
            foreach ( $queries as $query ) {
                if ( $post = $this->related_listing_query( $listing, $query, $type ) ) {
                    return $post;
                }
            }
            return false;
        }

        public function import_networks( $post_id, $field, $networks, $urls ) {
            $accepted_networks = array(
                'facebook'      => 'Facebook',
                'twitter'       => 'Twitter',
                'linkedin'      => 'LinkedIn',
                'youtube'       => 'YouTube',
                'google+'       => 'Google+',
                'instagram'     => 'Instagram',
                'tumblr'        => 'Tumblr',
                'snapchat'      => 'Snapchat',
                'reddit'        => 'Reddit',
                'deviantart'    => 'DeviantArt',
                'pinterest'     => 'Pinterest',
                'vkontakte'     => 'VKontakte',
                'soundcloud'    => 'SoundCloud',
                'website'       => 'Website',
                'other'         => 'Other'
            );
            $end_data = array();
            foreach( $networks as $key => $network ) {
                $network = trim( $network );
                $network_key = strtolower( $network );
                if ( array_key_exists( $network_key, $accepted_networks ) ) {
                    $urls[ $key ] = trim( $urls[ $key ] );
                    $end_data[] = array(
                        'network'   => $accepted_networks[ $network_key ],
                        'url'       => $urls[ $key ]
                    );
                }
            }
            update_post_meta( $post_id, $field, $end_data );
        }

        public function import_work_hours( $post_id, $data, $save_field ) {
            global $wpai_mylisting_addon;
            $data = maybe_unserialize( $data );
            $fields = array(
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

            $hours = array( 
                'Monday'    => array( 'status' => '' ), 
                'Tuesday'   => array( 'status' => '' ), 
                'Wednesday' => array( 'status' => '' ), 
                'Thursday'  => array( 'status' => '' ), 
                'Friday'    => array( 'status' => '' ), 
                'Saturday'  => array( 'status' => '' ), 
                'Sunday'    => array( 'status' => '' ),
                'timezone'  => ''
            );

            foreach ( $fields as $day => $field ) {
                $day = str_replace( array( "From", "To" ), "", $day );

                if ( !empty( $data[ $field ] ) ) {

                    if ( stristr( $data[ $field ], 'open' ) ) {
                        $status = 'open-all-day';
                    } elseif ( stristr( $data[ $field ], 'closed' ) ) {
                        $status = 'closed-all-day';
                    } elseif ( stristr( $data[ $field ], 'appointment' ) ) {
                        $status = 'by-appointment-only';
                    } else {
                        $status = 'enter-hours';
                    }

                    if ( stristr( $field, 'from' ) ) {
                        $subkey = 'from';
                    } else {
                        $subkey = 'to';
                    }
                    $key = 0;

                    switch ( $status ) {

                        case ( $status == 'open-all-day' || $status == 'closed-all-day' || $status == 'by-appointment-only' ):
                            $hours[ $day ][ 'status' ] = $status;
                            $hours[ $day ][ $key ][ $subkey ] = "00:00:00";

                            break;

                        case 'enter-hours':
                            $hours[ $day ][ 'status' ] = 'enter-hours';
                            $all_times = explode( ",", $data[ $field ] );
                            if ( count( $all_times ) > 0 ) {
                                foreach ( $all_times as $key => $time ) {
                                    $seconds = 15 * 60;                                    
                                    $hours[ $day ][ $key ][ $subkey ] = date( "H:i", round( strtotime( $time ) / $seconds ) * $seconds );
                                    $key++;
                                }
                            }

                        break;
                    }
                }
            }
            
            // Now, save the timezone.

            $field = 'work-hours_timezone';

            $timezone = str_replace( " ", "_", $data[ $field ] );

            if ( empty( $timezone ) ) {

                // They did not enter a timezone, so we'll default to UTC.
                $timezone = "UTC";
                $wpai_mylisting_addon->log( "<strong>Timezone</strong>: No timezone imported, defaulting to 'UTC'.");
                

            } else {

                if ( stristr( $timezone, "UTC" ) && $timezone != "UTC" ) {

                    // MyListing only allows the value "UTC", it does not support offsets.
                    $timezone = "UTC";
                    $wpai_mylisting_addon->log( "<strong>Timezone</strong>: String 'UTC' detected in timezone. Offsets are not supported, defaulting to 'UTC'.");

				} else {

                    // They entered a timezone string instead of a UTC offset.
                    if ( ! in_array( $timezone, timezone_identifiers_list() ) ) {
                        
                        // It's not a valid timezone.
                        $wpai_mylisting_addon->log( "<strong>Timezone</strong>: This is not a valid timezone: " . $timezone . ". Using 'UTC'." );
                        $timezone = "UTC";
                        
					} else {

                        $wpai_mylisting_addon->log( "<strong>Timezone</strong>: Timezone '" . $timezone . "' is valid and will be used as the timezone." );
                        
					}
				}
            }

            $hours[ 'timezone' ] = $timezone;
            update_post_meta( $post_id, $save_field, $hours );
        }

        public function after_post_save_operations( $post_id, $xml_data, $is_update ) {
            // Set listing type.
            $listing_type = get_option( 'wpai_mylisting_addon_listing_type' );
            update_post_meta( $post_id, '_case27_listing_type', $listing_type );

            $imported_expiry = get_option( 'wpaimlao_expiry_date' );

            if ( empty( $imported_expiry ) ) {
                if ( ! $is_update ) {
                    // The post is not being updated, so we should set the default expiry date.
                    $expiry_days = get_option( 'job_manager_submission_duration' );
                    
                    if ( ! empty( $expiry_days ) && is_numeric( $expiry_days ) ) {

                        // They have set an expiry date in the WP Job Manager settings.
                        $date = date( "Y-m-d", strtotime( "+ " . $expiry_days . " days" ) );
                        update_post_meta( $post_id, '_job_expires', $date );

                    }
                }
            }
            delete_option( 'wpaimlao_expiry_date' );
        }

        public function import_payment_package( $post_id, $field_name = '', $id = '' ) {
            if ( !empty( $field_name ) && !empty( $id ) ) {
                global $wpai_mylisting_addon;
                global $wpdb;
                $post = $wpdb->get_row( $wpdb->prepare( "SELECT `ID` FROM `" . $wpdb->prefix . "posts` WHERE `ID` = '%d'", $id ) );

                if ( $post ) {
                    if ( function_exists( 'update_field' ) ) {
                        update_field( $field_name, $post->ID, $post_id );
                    }
                } else {
                    $wpai_mylisting_addon->log( "<strong>PAYMENT PACKAGE:</strong> Failed to find payment package ID #" . $id );
                }
            }
        }

        public function select_post_type_in_settings( $selected, $post, $cpt, $page ) {
            $imported_type = get_option( 'wpai_mylisting_addon_listing_type' );

            if ( $imported_type == $cpt ) {
                $selected = true;
            }

            return $selected;
        }
    }
}
/***
 * End Helper Class.
 */