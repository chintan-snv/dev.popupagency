<?php

namespace MyListing\Src\Listing_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Location extends Base_Filter {

	public function filter_props() {
		$this->props['type'] = 'location';
		$this->props['label'] = 'Location';
		$this->props['placeholder'] = 'Enter location...';
	}

	public function get_editor_options() {
		$this->getLabelField();
		$this->getPlaceholderField();
	}

	public function apply_to_query( $args, $form_data ) {
		if ( ! empty( $form_data['search_location'] ) ) {
			$args['search_location'] = sanitize_text_field( stripslashes( $form_data['search_location'] ) );
		}

		return $args;
	}

	public function get_request_value() {
		return ! empty( $_GET['search_location'] ) ? $_GET['search_location'] : '';
	}

	public function get_request_components() {
		$lat = ! empty( $_GET['lat'] ) ? floatval( $_GET['lat'] ) : false;
		if ( $lat > 90 || $lat < -90 ) {
			$lat = false;
		}

		$lng = ! empty( $_GET['lng'] ) ? floatval( $_GET['lng'] ) : false;
		if ( $lng > 180 || $lng < -180 ) {
			$lng = false;
		}

		return [
			'search_location' => $this->get_request_value(),
			'search_location_lat' => $lat,
			'search_location_lng' => $lng,
		];
	}
}