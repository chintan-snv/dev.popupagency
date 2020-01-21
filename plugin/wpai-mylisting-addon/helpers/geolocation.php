<?php
// Geolocation helper functions.
if ( !class_exists( 'wpai_mylisting_addon_geocode' ) ) {
    class wpai_mylisting_addon_geocode {
        public function geocode_field( $slug, $label ) {
            global $wpai_mylisting_addon;
            if ( ! empty( $wpai_mylisting_addon ) ) {
                $wpai_mylisting_addon->add_field(
                    '_job_location',
                    'Location',
                    'radio',
                    array(
                        'search_by_address' => array(
                            'Search by Address',
                            $wpai_mylisting_addon->add_options(
                                $wpai_mylisting_addon->add_field(
                                    'job_address',
                                    'Job Address',
                                    'text'
                                ),
                                'Google Geocode API Settings',
                                array(
                                    $wpai_mylisting_addon->add_field(
                                        'address_geocode',
                                        'Request Method',
                                        'radio',
                                        array(
                                            'address_no_key' => array(
                                                'No API Key',
                                                'Limited number of requests.'
                                            ),
                                            'address_google_developers' => array(
                                                'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>',
                                                $wpai_mylisting_addon->add_field(
                                                    'address_google_developers_api_key',
                                                    'API Key',
                                                    'text'
                                                ),
                                                'Up to 2500 requests per day and 5 requests per second.'
                                            ),
                                            'address_google_for_work' => array(
                                                'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>',
                                                $wpai_mylisting_addon->add_field(
                                                    'address_google_for_work_client_id',
                                                    'Google Maps Premium Client ID',
                                                    'text'
                                                ),
                                                $wpai_mylisting_addon->add_field(
                                                    'address_google_for_work_digital_signature',
                                                    'Google Maps Premium Digital Signature',
                                                    'text'
                                                ),
                                                'Up to 100,000 requests per day and 10 requests per second'
                                            )
                                        ) // end Request Method options array
                                    ), // end Request Method nested radio field
                
                                ) // end Google Geocode API Settings fields
                            ) // end Google Gecode API Settings options panel
                        ), // end Search by Address radio field
                        'search_by_coordinates' => array(
                            'Search by Coordinates',
                            $wpai_mylisting_addon->add_field(
                                'job_lat',
                                'Latitude',
                                'text',
                                null,
                                'Example: 34.0194543'
                            ),
                            $wpai_mylisting_addon->add_options(
                                $wpai_mylisting_addon->add_field(
                                    'job_lng',
                                    'Longitude',
                                    'text',
                                    null,
                                    'Example: -118.4911912'
                                ),
                                'Google Geocode API Settings',
                                array(
                                    $wpai_mylisting_addon->add_field(
                                        'coord_geocode',
                                        'Request Method',
                                        'radio',
                                        array(
                                            'coord_no_key' => array(
                                                'No API Key',
                                                'Limited number of requests.'
                                            ),
                                            'coord_google_developers' => array(
                                                'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>',
                                                $wpai_mylisting_addon->add_field(
                                                    'coord_google_developers_api_key',
                                                    'API Key',
                                                    'text'
                                                ),
                                                'Up to 2500 requests per day and 5 requests per second.'
                                            ),
                                            'coord_google_for_work' => array(
                                                'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>',
                                                $wpai_mylisting_addon->add_field(
                                                    'coord_google_for_work_client_id',
                                                    'Google Maps Premium Client ID',
                                                    'text'
                                                ),
                                                $wpai_mylisting_addon->add_field(
                                                    'coord_google_for_work_digital_signature',
                                                    'Google Maps Premium Digital Signature',
                                                    'text'
                                                ),
                                                'Up to 100,000 requests per day and 10 requests per second'
                                            )
                                        ) // end Geocode API options array
                                    ), // end Geocode nested radio field
                
                                ) // end Geocode settings
                            ) // end coordinates Option panel
                        ) // end Search by Coordinates radio field
                    ) // end Job Location radio field
                );
            }
        } // End geolocation function.

        public function import_location( $post_id, $field, $data, $import_options ) {
            global $wpai_mylisting_addon;
            // update job location
            $field   = 'job_address';

            $api_key = '';
            
            $address = $data[$field];

            $lat  = $data['job_lat'];

            $long = $data['job_lng'];

            //  build search query
            if ( $data['_job_location'] == 'search_by_address' ) {

                $search = ( !empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

            } else {

                $search = ( !empty( $lat ) && !empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

            }

            // build api key
            if ( $data['_job_location'] == 'search_by_address' ) {

                if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {

                    $api_key = '&key=' . $data['address_google_developers_api_key'];

                } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {

                    $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

                }

            } else {

                if ( $data['coord_geocode'] == 'coord_google_developers' && !empty( $data['coord_google_developers_api_key'] ) ) {

                    $api_key = '&key=' . $data['coord_google_developers_api_key'];

                } elseif ( $data['coord_geocode'] == 'coord_google_for_work' && !empty( $data['coord_google_for_work_client_id'] ) && !empty( $data['coord_google_for_work_signature'] ) ) {

                    $api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];

                }

            }

            // Store _job_location value for later use

            if ( $data['_job_location'] == 'search_by_address' ) {

                $job_location = $address;

            } else {

                $job_location = $lat . ', ' . $long;

            }

            // if all fields are updateable and $search has a value
            if (  empty( $article['ID'] ) or ( $wpai_mylisting_addon->can_update_meta( $field, $import_options ) && $wpai_mylisting_addon->can_update_meta( '_job_location', $import_options ) && !empty ( $search ) ) ) {

                // build $request_url for api call
                $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
                $curl        = curl_init();

                curl_setopt( $curl, CURLOPT_URL, $request_url );
                curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

                $wpai_mylisting_addon->log( '- Getting location data from Geocoding API: '.$request_url );

                $json = curl_exec( $curl );

                curl_close( $curl );

                // parse api response
                if ( !empty( $json ) ) {

                    $details = json_decode( $json, true );
                    
                    if ( empty( $details['results'] ) ) {
                        $wpai_mylisting_addon->log( "<strong>WARNING:</strong> Cannot import location, no results were returned by Google." );
                        return false;
                    }

                    $address_data = array();

                    foreach ( $details['results'][0]['address_components'] as $type ) {
                        // Went for type_name here to try to make the if statement a bit shorter,
                        // and hopefully clearer as well
                        $type_name = $type['types'][0];

                        if ($type_name == "administrative_area_level_1" || $type_name == "administrative_area_level_2" || $type_name == "country") {
                            // short_name & long_name must be stored for these three field types, as
                            // the short & long names are stored by WP Job Manager
                            $address_data[ $type_name . "_short_name" ] = $type['short_name'];
                            $address_data[ $type_name . "_long_name" ] = $type['long_name'];
                        } else {
                            // The rest of the data from Google Maps can be returned in long format,
                            // as the other fields only store data in that format
                            $address_data[ $type_name ] = $type['long_name'];
                        }

                    }

                    // It's a long list, but this is what WP Job Manager stores in the database
                    $geo_status = ($details['status'] == "ZERO_RESULTS") ? 0 : 1;

                    $latitude  = $details['results'][0]['geometry']['location']['lat'];

                    $longitude = $details['results'][0]['geometry']['location']['lng'];

                    $formatted_address = $details['results'][0]['formatted_address'];

                    $street_number =  ( array_key_exists( 'street_number', $address_data ) ? $address_data['street_number'] : null );

                    $street = ( array_key_exists( 'route', $address_data ) ? $address_data['route'] : null );

                    $city = $address_data['locality'];

                    $country_short = $address_data['country_short_name'];

                    $country_long = $address_data['country_long_name'];

                    $zip = ( array_key_exists( 'postal_code', $address_data ) ? $address_data['postal_code'] : null );

                    // Important because the "geolocation_state_short" & "geolocation_state_long" fields
                    // can get data from "administrative_area_level_1" or "administrative_area_level_2",
                    // depending on the address that's provided
                    $state_short = !empty( $address_data['administrative_area_level_1_short_name'] ) ? $address_data['administrative_area_level_1_short_name'] : $address_data['administrative_area_level_2_short_name'];

                    $state_long = !empty( $address_data['administrative_area_level_1_long_name'] ) ? $address_data['administrative_area_level_1_long_name'] : $address_data['administrative_area_level_2_long_name'];

                    // Checks for empty location elements

                    if ( empty( $zip ) ) {

                        $wpai_mylisting_addon->log( '<b>WARNING:</b> Google Maps has not returned a Postal Code for this job location.' );

                    }

                    if ( empty( $country_short ) && empty( $country_long ) ) {

                        $wpai_mylisting_addon->log( '<b>WARNING:</b> Google Maps has not returned a Country for this job location.' );

                    }

                    if ( empty( $state_short ) && empty( $state_long ) ) {

                        $wpai_mylisting_addon->log( '<b>WARNING:</b> Google Maps has not returned a State for this job location.' );

                    }

                    if ( empty( $city ) ) {

                        $wpai_mylisting_addon->log( '<b>WARNING:</b> Google Maps has not returned a City for this job location.' );

                    }

                    if ( empty( $street_number ) ) {

                        $wpai_mylisting_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Number for this job location.' );

                    }

                    if ( empty( $street ) ) {

                        $wpai_mylisting_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Name for this job location.' );

                    }

                } else {
                    $wpai_mylisting_addon->log( '<b>WARNING:</b> Could not retrieve response data from Google Maps API.' );
                    $geo_status = '0';
                    $latitude = '';
                    $longitude = '';
                    $formatted_address = '';
                    $street_number = '';
                    $street = '';
                    $city = '';
                    $state_short = '';
                    $state_long = '';
                    $zip = '';
                    $country_short = '';
                    $country_long = '';
                    $job_location = '';
                }

            }

            // List of location fields to update
            $fields = array(
                'geolocation_lat' => $latitude,
                'geolocation_long' => $longitude,
                'geolocation_formatted_address' => $formatted_address,
                'geolocation_street_number' => $street_number,
                'geolocation_street' => $street,
                'geolocation_city' => $city,
                'geolocation_state_short' => $state_short,
                'geolocation_state_long' => $state_long,
                'geolocation_postcode' => $zip,
                'geolocation_country_short' => $country_short,
                'geolocation_country_long' => $country_long,
                '_job_location' => $job_location
            );


            $wpai_mylisting_addon->log( '- Updating location data' );

            foreach ( $fields as $field => $value ) {
                update_post_meta( $post_id, $field, $value );
            }

            // Check if "geolocated" field should be created or deleted
            if ($geo_status == "0") {
                delete_post_meta( $post_id, "geolocated" );
            } elseif ($geo_status == "1") {
                update_post_meta( $post_id, "geolocated", $geo_status );
            } else {
                // Do nothing, it's possible that we didn't get a response from the Google Maps API
            }
        } // end import location
    }
}